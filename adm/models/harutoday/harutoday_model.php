
<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Harutoday_Model extends CFX_Model
{
	public $uri;
	public $auth;
	public $member;
	public $profile;
	public $router;
	
	public $member_table = CFX_MEMBER_TABLE;
	public $profile_table = CFX_PROFILE_TABLE;
	public $research_table = CFX_RESEARCH_TABLE;
	public $group_table = CFX_GROUP_TABLE;
	public $group_label_table = CFX_GROUP_LABEL_TABLE;	

	public $harutoday_join_table = 'cfx_harutoday_join';
	public $harutoday_bookmark_table = 'cfx_harutoday_bookmark';
	public $harutoday_rating_table = 'cfx_harutoday_rating';
	public $harutoday_read_table = 'cfx_harutoday_read';
	public $harutoday_point_table = 'cfx_harutoday_point';
	public $harutoday_userdata_table = 'cfx_harutoday_userdata';

	public function __construct()
	{
        parent::__construct();

        $this->initialize();
	}

	public function initialize()
	{
		if (ENVIRONMENT == 'development')
			echo '<!--'.get_class($this).'::initialize()-->'.PHP_EOL;

		$CFX =& get_instance();

		$this->uri = $CFX->uri;
		$this->auth = $CFX->auth;
		$this->member = $CFX->member;
		$this->profile = $CFX->profile;
		$this->router = $CFX->router;
	}

	public function get_lists()
	{
		$level = 3;

		$result = array();

		// 페이지 값 설정
		$member_pages = '';
		$current_page = $this->uri->page;
		$list_page_rows = $this->config->view['page_rows'];
		$total_count = 0;
		$total_page = 1;
		$qstr_sort = '';

		$this->uri->page_rows = ($this->uri->page_rows) ? $this->uri->page_rows : $list_page_rows;

		// 검색필드 옵션 설정
		$result['select_search_field_option'] = $this->uri->select_search_field_option($this->uri->sfield);

		// 페이지 값 설정
		$result['current_page'] = $current_page;

		// 페이지 목록 옵션 설정
		$result['select_page_rows_option'] = $this->uri->select_page_rows_option($this->uri->page_rows, $list_page_rows, $this->config->view['page_rows_list'], $this->router->link_href('lists'));

		// 관리자 정보를 얻어온다.
		$manager = array();
		$manager = $this->member->get_member_view_by_id($this->auth->get_session_member_id());

		if ($this->auth->is_manager() === TRUE) {
			$this->uri->sgroup = $manager['group'];
		}

		// 검색그룹 옵션 설정
		$result['select_group'] =  $this->select_group_option($this->uri->sgroup, $this->router->link_href('lists'));

		// 검색어가 있다면
		if ($this->uri->sgroup || $this->uri->stext)
		{
			$sql_search = $this->member->get_sql_search($this->uri->sgroup, $this->uri->sfield, $this->uri->stext, $this->uri->sop);

			// 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
			$sql = " SELECT MIN(`member_no`) AS `min_member_no` FROM `{$this->member_table}` AS mb WHERE mb.member_level <= {$level};";
			$min_member_row = $this->db->sql_fetch($sql);
			$min_spart = (int)$min_member_row['min_member_no'];

			if (empty($this->uri->spart))
				$this->uri->spart = $min_spart;

			$sql_search .= " AND (`member_no` between {$this->uri->spart} AND ({$this->uri->spart} + {$this->config->view['search_part']})) AND mb.member_level <= {$level}";

			// 회원수를 구한다.
			$sql = " SELECT COUNT(DISTINCT `member_no`) AS `cnt` FROM `{$this->member_table}` AS mb WHERE {$sql_search} ";

			$member_row = $this->db->sql_fetch($sql);
			$total_count = $member_row['cnt'];
		}
		else
		{
			$sql_search = '';
			// 회원수를 구한다.
			$sql = " SELECT COUNT(DISTINCT `member_no`) AS `cnt` FROM `{$this->member_table}` AS mb WHERE mb.member_level <= {$level}; ";

			// echo $sql;

			$row = $this->db->sql_fetch($sql);
			$total_count = $row['cnt'];
		}

		// 전체 카운트 설정
		$result['total_count'] = $total_count;

		$i = 0;
		$total_page = ceil($total_count / $this->uri->page_rows);  // 전체 페이지 계산
		$from_record = ($current_page - 1) * $this->uri->page_rows; // 시작 열을 구함

		// 전체 페이지 설정
		$result['total_page'] = $total_page;

		// 정렬
		// 인덱스 필드가 아니면 정렬에 사용하지 않음
		if (empty($this->uri->ssort))
		{
			$this->uri->ssort = 'no';
			$this->uri->sorder = 'desc';
		}
		else
		{
			// 게시물 리스트의 정렬 대상 필드가 아니라면 공백으로
			$this->uri->ssort = preg_match("/^(id|name|email|datetime|no)$/i", $this->uri->ssort) ? $this->uri->ssort : '';
		}

		if ($this->uri->ssort)
			$sql_order = " ORDER BY mb.member_{$this->uri->ssort} {$this->uri->sorder} ";

		if ($this->uri->sgroup || $this->uri->stext) {
			$sql = " SELECT DISTINCT mb.member_no FROM `{$this->member_table}` AS mb WHERE {$sql_search} {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		} else {
			$sql = " SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->profile_table}` AS pf  INNER JOIN `{$this->research_table}` AS rs INNER JOIN `{$this->harutoday_join_table}` AS htj INNER JOIN `{$this->group_table}` AS gp INNER JOIN `{$this->group_label_table}` AS gl WHERE mb.member_no = pf.member_no AND mb.member_no = rs.member_no  AND mb.member_no = htj.member_no AND mb.member_group = gp.group_no  AND mb.member_group_label = gl.label_no AND mb.member_level <= {$level} {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		}

		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$member_result = $this->db->sql_query($sql);

			$k = 0;

			while ($member_row = $this->db->sql_fetch_array($member_result))
			{
				// 검색일 경우 member_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->sgroup || $this->uri->stext) {
					$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb  INNER JOIN `{$this->profile_table}` AS pf  INNER JOIN `{$this->research_table}` AS rs INNER JOIN `{$this->harutoday_join_table}` AS htj INNER JOIN `{$this->group_table}` AS gp INNER JOIN `{$this->group_label_table}` AS gl WHERE mb.member_no = pf.member_no AND mb.member_no = rs.member_no AND mb.member_no = htj.member_no AND mb.member_group = gp.group_no  AND mb.member_group_label = gl.label_no AND mb.member_level <= {$level} AND mb.member_no = '{$member_row['member_no']}' ");
				}

				$list[$i] = $this->get_harutoday_joined_member_list($member_row, $this->uri->qstr, $this->router->link_href('view'));

				if (strstr($this->uri->sfield, 'id'))
				{
					$list[$i]['id'] = $this->common->search_font($this->uri->stext, $list[$i]['name']);
				}
				elseif (strstr($this->uri->sfield, 'name'))
				{
					$list[$i]['name'] = $this->common->search_font($this->uri->stext, $list[$i]['name']);
				}
				elseif (strstr($this->uri->sfield, 'email'))
				{
					$list[$i]['email'] = $this->common->search_font($this->uri->stext, $list[$i]['email']);
				}

				$list_num = $total_count - ($current_page - 1) * $list_page_rows;
				
				$list[$i]['num'] = intVal($list_num - $k);

				unset($member_row);

				$i++;
				$k++;
			}
		}

		$result['member_list'] = $list;

		$pages = $this->config->view['pages'];
		$list_pages_href = $this->router->link_href('lists');

		if (empty($this->uri->qstr))
			$list_pages_href .= '?';
		else
			$list_pages_href .= $this->uri->qstr;

		$member_pages = $this->common->get_paging($pages, $current_page, $total_page, $list_pages_href);

		// 멤버 페이지
		$result['member_pages'] = $member_pages;

		return $result;
	}

	// 회원 정보($member_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_harutoday_joined_member_list($member_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$member_row['member_no'];

		$list['id'] = get_text($member_row['member_id']);
		$list['name'] = get_text($member_row['member_name']);
		$list['email'] = get_text($member_row['member_email']);
		$list['level'] = (int)$member_row['member_level'];
		$list['group'] = (int)$member_row['member_group'];
		$list['group_name'] = get_text($member_row['group_name']);
		$list['group_label'] = (int)$member_row['member_group_label'];
		$list['group_label_name'] = get_text($member_row['label_name']);

		$list['login_datetime'] = substr($member_row['member_login_datetime'],0,10);

		if ($list['level'] == 1) {
			$list['level_name'] = '일반인';
		} else if ($list['level'] == 3) {
			$list['level_name'] = '참여자';
		} else if ($list['level'] == 6) {
			$list['level_name'] = '담당자';
		} else if ($list['level'] == 9) {
			$list['level_name'] = '관리자';
		}

		$list['birth'] = substr($member_row['profile_birth'],0,10);
		$list['gender'] = get_text($member_row['profile_gender']);

		if ($list['gender'] == 'M') {
			$list['gender_name'] = '남';
		} else if ($list['gender'] == 'F') {
			$list['gender_name'] = '여';
		} else {
			$list['gender_name'] = '-';
		}

		$list['research_check'] = get_text($member_row['research_check']);
		$list['research_name'] = get_text($member_row['research_name']);
		$list['research_level'] = (int)$member_row['research_level'];
		$list['research_date'] = get_text($member_row['research_date']);

		$recur_source = array ('1', '2', '3');
		$recur_target = array ('없음', '재발', '전이');
		$list['research_recur'] = str_replace($recur_source, $recur_target, $member_row['research_recur']);

		$recur_source = array ('1', '2', '3', '4', '5');
		$recur_target = array ('수술', '방사선치료', '항암치료', '호르몬치료', '기타');
		$list['research_care'] = str_replace($recur_source, $recur_target, $member_row['research_care']);

		$test_source = array ('Y', 'N');
		$test_target = array ('O', 'X');
		$list['research_test1'] = str_replace($test_source, $test_target, $member_row['research_test1']);
		$list['research_test2'] = str_replace($test_source, $test_target, $member_row['research_test2']);
		$list['research_test3'] = str_replace($test_source, $test_target, $member_row['research_test3']);
		$list['research_test4'] = str_replace($test_source, $test_target, $member_row['research_test4']);
		$list['research_test5'] = str_replace($test_source, $test_target, $member_row['research_test5']);

		// 당일인 경우 시간으로 표시함
		$list['ht_created_datetime'] = substr($member_row['ht_created_datetime'],0,10);
		$list['ht_created_datetime2'] = $member_row['ht_created_datetime'];
		if ($list['ht_created_datetime'] == CFX_TIME_YMD)
			$list['ht_created_datetime2'] = substr($list['ht_created_datetime2'],11,5);
		else
			$list['ht_created_datetime2'] = substr($list['ht_created_datetime2'],5,5);

		$part_source = array ('1', '2', '3', '4');
		$part_target = array ('우울·불안', '수면', '통증', '미사용');
		$list['ht_current_part'] = str_replace($part_source, $part_target, $member_row['ht_current_part']);
	 	$list['ht_activated'] = ($member_row['ht_activated'] !== '1') ? '미사용' : '사용';

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($member_row);

		return $list;
	}

	public function get_view()
	{
		$result = array();

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		// 회원가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($member_no) || $member_no == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$member_view = $this->member->get_member_view_by_no($member_no);

		if (!isset($member_view['no']) || $member_view['no'] == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['select_group']= $this->select_group_option($member_view['group']);		

		$result['select_group_label']= $this->select_group_label_option($member_view['group'], $member_view['group_label']);

		$result['member_view'] = $member_view;
		unset($member_view);

		$profile_view = $this->profile->get_profile_view_by_no($member_no);
		$result['profile_view'] = $profile_view;
		unset($profile_view);

		$research_view = $this->get_research_view_by_no($member_no);
		$result['research_view'] = $research_view;
		unset($research_view);

		$harutoday_join_view = $this->get_harutoday_view_by_no($member_no);
		$result['harutoday_join_view'] = $harutoday_join_view;
		unset($harutoday_join_view);

		return $result;
	}

	private function get_research_view_by_no($member_no, $fields='*')
	{
		if ( empty($member_no) || !is_numeric(trim($member_no)) )
			return;

		$research_row = array();

		$research_row = $this->db->sql_fetch(" SELECT {$fields} FROM  `{$this->research_table}` WHERE member_no = '{$member_no}'; ");

		$view = array();

		// 게시물 아이디
		$view['key'] = get_text($research_row['member_key']);
		$view['id'] = get_text($research_row['member_id']);
		$view['no'] = (int)$research_row['member_no'];

		$view['check'] = get_text($research_row['research_check']);
		$view['name'] = get_text($research_row['research_name']);
		$view['level'] = (int)$research_row['research_level'];
		$view['date'] = get_text($research_row['research_date']);

		$recur_source = array ('1', '2', '3');
		$recur_target = array ('없음', '재발', '전이');
		$view['recur'] = str_replace($recur_source, $recur_target, $research_row['research_recur']);

		$recur_source = array ('1', '2', '3', '4', '5');
		$recur_target = array ('수술', '방사선치료', '항암치료', '호르몬치료', '기타');
		$view['care'] = str_replace($recur_source, $recur_target, $research_row['research_care']);

		$test_source = array ('Y', 'N');
		$test_target = array ('예', '아니오');
		$view['test1'] = str_replace($test_source, $test_target, $research_row['research_test1']);
		$view['test2'] = str_replace($test_source, $test_target, $research_row['research_test2']);
		$view['test3'] = str_replace($test_source, $test_target, $research_row['research_test3']);
		$view['test4'] = str_replace($test_source, $test_target, $research_row['research_test4']);
		$view['test5'] = str_replace($test_source, $test_target, $research_row['research_test5']);

		unset($research_row);

		return $view;
	}

	public function get_update()
	{
		// 회원수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_harutoday_join_member');

		$result = array();

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		// 회원가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($member_no) || $member_no == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$member_view = $this->member->get_member_view_by_no($member_no);

		if (!isset($member_view['no']) || $member_view['no'] == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['select_group']= $this->select_group_option($member_view['group']);		

		$result['select_group_label']= $this->select_group_label_option($member_view['group'], $member_view['group_label']);

		$result['member_view'] = $member_view;
		unset($member_view);

		$harutoday_join_view = $this->get_harutoday_view_by_no($member_no);
		$result['harutoday_join_view'] = $harutoday_join_view;
		unset($harutoday_join_view);

		return $result;
	}

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_harutoday_join_member';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		$regist = array();		
		$regist['ht_current_part']  = (int)$this->common->get_post_data('ht_current_part', 1);
		$regist['ht_activated'] = (int)$this->common->get_post_data('ht_activated', 1);

		$regist['join1_datetime_org'] = $this->common->get_post_data('join1_datetime_org', 10);
		$regist['join1_datetime'] = $this->common->get_post_data('join1_datetime', 10);

		$regist['join2_datetime_org'] = $this->common->get_post_data('join2_datetime_org', 10);
		$regist['join2_datetime'] = $this->common->get_post_data('join2_datetime', 10);

		$regist['join3_datetime_org'] = $this->common->get_post_data('join3_datetime_org', 10);
		$regist['join3_datetime'] = $this->common->get_post_data('join3_datetime', 10);

		// 하루카드 기초 테이블을 등록한다.
		$this->harutoday_join_update($regist, $member_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $member_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function harutoday_join_update($regist_row, $member_no)
	{

		$sql_join1_datetime = "";
		if ($regist_row['join1_datetime_org'] != $regist_row['join1_datetime'])
		{
			$sql_join1_datetime = ", `ht_join1_datetime` = '{$regist_row['join1_datetime']}'";
		}

		$sql_join2_datetime = "";
		if ($regist_row['join2_datetime_org'] != $regist_row['join2_datetime'])
		{
			$sql_join2_datetime = ", `ht_join2_datetime` = '{$regist_row['join2_datetime']}'";
		}

		$sql_join3_datetime = "";
		if ($regist_row['join3_datetime_org'] != $regist_row['join3_datetime'])
		{
			$sql_join3_datetime = ", `ht_join3_datetime` = '{$regist_row['join3_datetime']}'";
		}


		$datetime = CFX_TIME_YMDHIS;
		$sql = " UPDATE `{$this->harutoday_join_table}`
					SET `ht_last_datetime` = '{$datetime}'
						, `ht_current_part` = '{$regist_row['ht_current_part']}'
						, `ht_activated` = '{$regist_row['ht_activated']}'
						{$sql_join1_datetime}
						{$sql_join2_datetime}
						{$sql_join3_datetime}
					WHERE `member_no` = '{$member_no}';
				";

		$this->db->sql_query($sql);
	}

	private function get_harutoday_view_by_no($member_no, $fields='*')
	{
		if ( empty($member_no) || !is_numeric(trim($member_no)) )
			return;

		$harutoday_join_row = array();
		$harutoday_join_row = $this->db->sql_fetch(" SELECT {$fields} FROM `{$this->harutoday_join_table}` WHERE member_no = '{$member_no}'; ");

		$view = array();

		// 게시물 아이디
		$view['key'] = get_text($harutoday_join_row['member_key']);
		$view['id'] = get_text($harutoday_join_row['member_id']);
		$view['no'] = (int)$harutoday_join_row['member_no'];

		// 당일인 경우 시간으로 표시함
		$view['ht_created_datetime'] = substr($harutoday_join_row['ht_created_datetime'],0,10);
		$view['ht_created_datetime2'] = $harutoday_join_row['ht_created_datetime'];
		if ($view['ht_created_datetime'] == CFX_TIME_YMD)
			$view['ht_created_datetime2'] = substr($view['ht_created_datetime2'],11,5);
		else
			$view['ht_created_datetime2'] = substr($view['ht_created_datetime2'],5,5);

		$view['ht_join1_datetime'] = substr($harutoday_join_row['ht_join1_datetime'],0,10);
		$view['ht_join2_datetime'] = substr($harutoday_join_row['ht_join2_datetime'],0,10);
		$view['ht_join3_datetime'] = substr($harutoday_join_row['ht_join3_datetime'],0,10);

		$view['ht_current_part'] = $harutoday_join_row['ht_current_part'];
		$part_source = array ('1', '2', '3', '4');
		$part_target = array ('우울·불안', '수면', '통증', '미사용');
		$view['ht_current_part_name'] = str_replace($part_source, $part_target, $harutoday_join_row['ht_current_part']);
		$view['ht_activated'] = $harutoday_join_row['ht_activated'];
	 	$view['ht_activated_name'] = ($harutoday_join_row['ht_activated'] !== '1') ? '미사용' : '사용';

		unset($harutoday_join_row);

		$ht_bookmark_items = array();
		$harutoday_bookmark_row = array();
		$harutoday_bookmark_row = $this->db->sql_fetch(" SELECT * FROM `{$this->harutoday_bookmark_table}` WHERE member_no = '{$member_no}' AND ht_part_no = '{$view['ht_current_part']}'; ");
		for ($i = 1; $i <=48; $i++)
		{
			$ht_bookmark_items[$i] = $harutoday_bookmark_row['ht_bookmark'.$i];
		}
		$view['ht_bookmark_items'] = $ht_bookmark_items;
		unset($ht_bookmark_items);
		unset($harutoday_bookmark_row);

		$ht_rating_items = array();
		$harutoday_rating_row = array();
		$harutoday_rating_row = $this->db->sql_fetch(" SELECT * FROM `{$this->harutoday_rating_table}` WHERE member_no = '{$member_no}' AND ht_part_no = '{$view['ht_current_part']}'; ");
		for ($i = 1; $i <=48; $i++)
		{
			$ht_rating_items[$i] = $harutoday_rating_row['ht_rating'.$i];
		}
		$view['ht_rating_items'] = $ht_rating_items;
		unset($ht_rating_items);
		unset($harutoday_rating_row);

		$ht_read_items = array();
		$harutoday_read_row = array();
		$harutoday_read_row = $this->db->sql_fetch(" SELECT * FROM `{$this->harutoday_read_table}` WHERE member_no = '{$member_no}' AND ht_part_no = '{$view['ht_current_part']}'; ");
		for ($i = 1; $i <=48; $i++)
		{
			$ht_read_items[$i] = $harutoday_read_row['ht_read'.$i];
		}
		$view['ht_read_items'] = $ht_read_items;
		unset($ht_read_items);
		unset($harutoday_read_row);

		$total_sum = 0;
		$total_point = 0;
		$total_quiz = 0;
		$total_zone = 0;

		$ht_point_items = array();
		$ht_quiz_items = array();
		$ht_zone_items = array();
		$harutoday_point_row = array();
		$harutoday_point_row = $this->db->sql_fetch(" SELECT * FROM `{$this->harutoday_point_table}` WHERE member_no = '{$member_no}' AND ht_part_no = '{$view['ht_current_part']}'; ");

		for ($i = 1; $i <=48; $i++)
		{
			if ($i == 5) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus1'];
			} else if ($i == 10) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus2'];
			} else if ($i == 15) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus3'];
			} else if ($i == 20) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus4'];
			} else if ($i == 25) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus5'];
			} else if ($i == 30) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus6'];
			} else if ($i == 35) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus7'];
			} else if ($i == 40) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus8'];
			} else if ($i == 45) {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i] + $harutoday_point_row['ht_bonus9'];
			} else {
				$ht_point_items[$i] = $harutoday_point_row['ht_point'.$i];
			}

			$ht_quiz_items[$i] = $harutoday_point_row['ht_quiz'.$i];

			$total_point += $ht_point_items[$i];
			$total_quiz += $ht_quiz_items[$i];

			$total_sum += $ht_quiz_items[$i];
			$total_sum += $ht_point_items[$i];
		}

		for ($i = 1; $i <=5; $i++)
		{
			$ht_zone_items[$i] = $harutoday_point_row['ht_zone'.$i];

			$total_zone += $ht_zone_items[$i];

			$total_sum += $ht_zone_items[$i];
		}

		$view['ht_point_items'] = $ht_point_items;
		$view['ht_quiz_items'] = $ht_quiz_items;
		$view['ht_zone_items'] = $ht_zone_items;
		$view['total_sum'] = $total_sum;
		$view['total_point'] = $total_point;
		$view['total_quiz'] = $total_quiz;
		$view['total_zone'] = $total_zone;

		unset($ht_point_items);
		unset($ht_quiz_items);
		unset($ht_zone_items);
		unset($harutoday_point_row);

		$ht_userdata_items = array();
		$harutoday_userdata_row = array();
		$harutoday_userdata_result = $this->db->sql_query(" SELECT * FROM `{$this->harutoday_userdata_table}` WHERE member_no = '{$member_no}' AND ht_part_no = '{$view['ht_current_part']}' ORDER BY ht_pnum ASC; ");
		
		$i = 1;

		while ($harutoday_userdata_row = $this->db->sql_fetch_array($harutoday_userdata_result))
		{
			$ht_userdata_items[$i] = $harutoday_userdata_row['ht_userdata'];
			$i++;
		}
		$view['ht_userdata_items'] = $ht_userdata_items;

		unset($ht_userdata_items);
		unset($harutoday_userdata_result);
		unset($harutoday_userdata_row);

		return $view;
	}

	// 그룹 검색용 셀렉트박스 옵션을 얻는다.
	public function select_group_option($sgroup='', $group_href = '')
	{
		$select_group_option = '';

		$sql = " SELECT * FROM `{$this->group_table}` WHERE group_activated = 1 ORDER BY `group_no` ASC;";

		$group_rows = array();
		$group_rows = $this->db->sql_query($sql);

		$group_row = array();

		if (!empty($group_href))
		{
			$select_group_option.= '<option value="'.$group_href.'"';

			if (empty(trim($sgroup)))
				$select_group_option.= ' selected';

			$select_group_option.= '>ALL</option>'.PHP_EOL;			
		}

		while ($group_row = $this->db->sql_fetch_array($group_rows))
		{
		    $current_group = trim($group_row['group_no']);
			// if (empty($current_group)) continue;

			if (empty($group_href))
				$select_group_option.= '<option value="'.$current_group.'"';	
			else
				$select_group_option.= '<option value="'.$group_href."?sgroup=".urlencode($current_group).'"';			

			if ($current_group == $sgroup) { // 현재 선택된 카테고리라면
				$select_group_option.= ' selected';
			}

		    $group_name = $group_row['group_name'];
		    $select_group_option.= '>'.$group_name.'</option>'.PHP_EOL;

			unset($group_row);
		}

		unset($group_rows);

		return $select_group_option;
	}

	// 그룹 라벨 셀렉트박스 옵션을 얻는다.
	public function select_group_label_option($group_no, $sgroup_label='')
	{
		$select_group_label_option = '';

		$sql = " SELECT `group_no`, group_concat(`label_no`) AS label_no, group_concat(`label_name`) AS label_name FROM `{$this->group_label_table}` WHERE `group_no` = {$group_no} AND `label_activated` = 1 GROUP BY group_no  ORDER BY `label_no` ASC;";

		$group_label_values = explode(',', '');
		$group_label_names = explode(',', '');
		$label_row = $this->db->sql_fetch($sql);

		$select_group_label_option .= '<option value="0">미지정</option>'.PHP_EOL;

		if (isset($label_row) && !is_null($label_row)) {
			$group_label_values = explode(',', $label_row['label_no']);
			$group_label_names = explode(',', $label_row['label_name']);
	
			for ($i=0; $i<count($group_label_values); $i++) {
			    $current_group_label = trim($group_label_values[$i]);
			    if (empty($current_group_label)) continue;

			    $select_group_label_option.= '<option value="'.$current_group_label.'"';

			    if ($current_group_label == $sgroup_label) { // 현재 선택된 검색필드라면
			        $select_group_label_option.= ' selected';
			    }
			    $group_label_name = trim($group_label_names[$i]);
			    $select_group_label_option.= '>'.$group_label_name.'</option>'.PHP_EOL;
			}
		}

		return $select_group_label_option;
	}
}

?>