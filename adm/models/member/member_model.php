<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Member_Model extends CFX_Model
{
	public $db;
	public $auth;
	public $uri;
	public $member;
	public $profile;
	public $router;
	
	public $member_table = CFX_MEMBER_TABLE;
	public $member_memo_table = CFX_MEMBER_MEMO_TABLE;
	public $group_table = CFX_GROUP_TABLE;
	public $group_label_table = CFX_GROUP_LABEL_TABLE;	
	public $profile_table = CFX_PROFILE_TABLE;
	public $research_table = CFX_RESEARCH_TABLE;


	public function __construct()
	{
        parent::__construct();

        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->db = $CFX->db;
		$this->auth = $CFX->auth;
		$this->uri = $CFX->uri;
		$this->member = $CFX->member;
		$this->profile = $CFX->profile;
		$this->router = $CFX->router;
	}

	public function get_lists()
	{
		$level = 3; // 참여자 레벨 설정

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

			$sql_search .= " AND (mb.member_no between {$this->uri->spart} AND ({$this->uri->spart} + {$this->config->view['search_part']})) AND mb.member_level <= {$level} ";

			// 회원수를 구한다.
			$sql = " SELECT COUNT(DISTINCT `member_no`) AS `cnt` FROM `{$this->member_table}` AS mb WHERE {$sql_search}";

			$member_row = $this->db->sql_fetch($sql);
			$total_count = $member_row['cnt'];
		}
		else
		{
			$sql_search = '';
			// 회원수를 구한다.
			$sql = " SELECT COUNT(DISTINCT `member_no`) AS `cnt` FROM `{$this->member_table}` AS mb WHERE mb.member_level <= {$level};";

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
			$sql = " SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->profile_table}` AS pf INNER JOIN `{$this->research_table}` AS rs INNER JOIN `{$this->group_table}` AS gp INNER JOIN `{$this->group_label_table}` AS gl WHERE mb.member_no = pf.member_no AND mb.member_no = rs.member_no AND mb.member_group = gp.group_no AND mb.member_group_label = gl.label_no  AND mb.member_level <= {$level} {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		}

		// echo $sql;

		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$member_result = $this->db->sql_query($sql);

			$k = 0;

			while ($member_row = $this->db->sql_fetch_array($member_result))
			{
				// 검색일 경우 member_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->sgroup || $this->uri->stext) {
					$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->profile_table}` AS pf  INNER JOIN `{$this->research_table}` AS rs INNER JOIN `{$this->group_table}` AS gp INNER JOIN `{$this->group_label_table}` AS gl WHERE mb.member_no = pf.member_no AND mb.member_no = rs.member_no AND mb.member_group = gp.group_no  AND mb.member_group_label = gl.label_no AND mb.member_level <= {$level} AND mb.member_no = '{$member_row['member_no']}' ");
				}

				$list[$i] = $this->get_member_list($member_row, $this->uri->qstr, $this->router->link_href('view'));

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
	public function get_member_list($member_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$member_row['member_no'];

		$list['id'] = get_text($member_row['member_id']);
		$list['name'] = get_text($member_row['member_name']);
		$list['email'] = get_text($member_row['member_email']);
		$list['pw'] = get_text($member_row['member_pw']);
		$list['level'] = (int)$member_row['member_level'];
		$list['group'] = (int)$member_row['member_group'];
		$list['group_name'] = get_text($member_row['group_name']);
		$list['group_label'] = (int)$member_row['member_group_label'];
		$list['group_label_name'] = get_text($member_row['label_name']);

		if ($list['level'] == 1) {
			$list['level_name'] = '일반인';
		} else if ($list['level'] == 3) {
			$list['level_name'] = '참여자';
		} else if ($list['level'] == 6) {
			$list['level_name'] = '프로젝트관리자';
		} else if ($list['level'] == 9) {
			$list['level_name'] = '전체관리자';
		}

		$list['birth'] = substr($member_row['profile_birth'],0,10);
		$list['gender'] = get_text($member_row['profile_gender']);

		if ($list['gender'] == 'M') {
			$list['gender_name'] = '남자';
		} else if ($list['gender'] == 'F') {
			$list['gender_name'] = '여자';
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
		$list['created_datetime'] = substr($member_row['member_created_datetime'],0,10);
		$list['created_datetime2'] = $member_row['member_created_datetime'];
		if ($list['created_datetime'] == CFX_TIME_YMD)
			$list['created_datetime2'] = substr($list['created_datetime2'],11,5);
		else
			$list['created_datetime2'] = substr($list['created_datetime2'],5,5);

		$list['ip'] = get_text($member_row['member_ip']);
		$list['hp'] = get_text($member_row['member_hp']);

		$list['login_ip'] = get_text($member_row['member_login_ip']);
		$list['login_datetime'] = substr($member_row['member_login_datetime'],0,10);
		$list['left_datetime'] = $member_row['member_left_date'];
 		$list['status'] = ($member_row['member_left_date'] !== '') ? '탈퇴' : '등록';
 		$list['activated'] = $member_row['member_activated'];

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($member_row);

		return $list;
	}

	// get_member_list 의 alias
	public function get_member_view($member_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_member_list($member_row, $qstr, $view_href, $mode);
	}

	public function get_view()
	{
		$level = 3; // 참여자 레벨 설정

		$result = array();

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		// 회원가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($member_no) || $member_no == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$member_row = array();
		$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->profile_table}` AS pf  INNER JOIN `{$this->research_table}` AS rs INNER JOIN `{$this->group_table}` AS gp INNER JOIN `{$this->group_label_table}` AS gl WHERE mb.member_no = pf.member_no AND mb.member_no = rs.member_no AND mb.member_group = gp.group_no  AND mb.member_group_label = gl.label_no AND mb.member_level <= {$level} AND mb.member_no = '{$member_no}' ");

		$member_view = $this->get_member_view($member_row, urldecode($this->uri->qstr), '', 'view');

		if (!isset($member_view['no']) || $member_view['no'] == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		// 삭제 토큰생성 설정
		$delete_token = $this->common->get_csrf_token('delete_member');

		// 삭제링크 설정
		if (empty($this->uri->qstr))
			$result['delete_link'] = $this->router->link_href('delete', '?token='.$delete_token);
		else
			$result['delete_link'] = $this->router->link_href('delete', urldecode($this->uri->qstr).'&amp;token='.$delete_token);

		$result['select_group']= $this->select_group_option($member_view['group']);		

		$result['select_group_label']= $this->select_group_label_option($member_view['group'], $member_view['group_label']);

		$result['member_view'] = $member_view;
		unset($member_view);

		$sql = " SELECT * FROM `{$this->member_memo_table}` AS mm WHERE mm.member_no = {$member_no} ORDER BY `memo_created_datetime` DESC;";

		$i = 0;
		$list = array();
		$memo_result = $this->db->sql_query($sql);

		// 삭제 토큰생성 설정
		$delete_memo_token = $this->common->get_csrf_token('delete_memo');

		while ($memo_row = $this->db->sql_fetch_array($memo_result))
		{
			$list[$i] = $this->get_memo_list($memo_row, $delete_memo_token);

			unset($memo_row);
			$i++;
		}

		$result['memo_list'] = $list;

		return $result;
	}

	public function get_update()
	{
		$level = 3; // 참여자 레벨 설정

		// 회원수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_member');

		$result = array();

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		// 회원가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($member_no) || $member_no == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$member_row = array();
		$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->profile_table}` AS pf  INNER JOIN `{$this->research_table}` AS rs INNER JOIN `{$this->group_table}` AS gp INNER JOIN `{$this->group_label_table}` AS gl WHERE mb.member_no = pf.member_no AND mb.member_no = rs.member_no AND mb.member_group = gp.group_no  AND mb.member_group_label = gl.label_no AND mb.member_level <= {$level} AND mb.member_no = '{$member_no}' ");

		$member_view = $this->get_member_view($member_row, urldecode($this->uri->qstr), '', 'view');

		if (!isset($member_view['no']) || $member_view['no'] == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['select_group']= $this->select_group_option($member_view['group']);		

		$result['select_group_label']= $this->select_group_label_option($member_view['group'], $member_view['group_label']);		

		$result['member_view'] = $member_view;
		unset($member_view);

		return $result;
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

		if ($group_no == 0) {
			$select_group_label_option .= '<option value="0">미지정</option>'.PHP_EOL;
		}
		
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

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_member';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		$update = array();		
		$update['activated']  = (int)$this->common->get_post_data('activated', 1);
		$update['group']  = (int)$this->common->get_post_data('group', 10);
		$update['group_label']  = (int)$this->common->get_post_data('group_label', 10);
		$update['member_pw']  = $this->common->get_post_data('pw', 50);

		$this->member_update($update, $member_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $member_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function member_update($update_row, $member_no)
	{
		$sql_activated = "";
		if ($update_row['activated'] == '0')
		{
			$left_date = date("Ymd", CFX_SERVER_TIME);
			$sql_activated = ", `member_activated` = '{$update_row['activated']}', `member_left_date` = '{$left_date}'";
		}
		else
		{
			$sql_activated = ", `member_activated` = '{$update_row['activated']}', `member_left_date` = ''";
		}

		$sql_passwd = "";
		if ($update_row['member_pw'] == '')
		{
			$sql_passwd = "";
		}
		else
		{
			$member_pw = $this->db->get_encrypt_string($update_row['member_pw'] );
			$sql_passwd = ", `member_pw` = '{$member_pw}'";
		}

		$sql = " UPDATE `{$this->member_table}`
					SET  `member_group` = '{$update_row['group']}'
					, `member_group_label` = '{$update_row['group_label']}'
					{$sql_activated}
					{$sql_activated}
					{$sql_passwd}
					WHERE `member_no` = '{$member_no}';
				";

		$this->db->sql_query($sql);
	}

	// 메모 정보($memo_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_memo_list($memo_row, $delete_memo_token='')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$memo_row['memo_no'];

		$list['id'] = get_text($memo_row['memo_member_id']);
		$list['name'] = get_text($memo_row['memo_member_name']);
		$list['content'] = get_text($memo_row['memo_content']);
		$list['created_datetime'] = substr($memo_row['memo_created_datetime'],0,10);
		$list['ip'] = get_text($memo_row['memo_member_ip']);

		// 그룹 라벨 삭제링크 설정
		if (empty($this->uri->qstr))
			$list['delete_link'] = $this->router->link_href('memo_delete', '?token='.$delete_memo_token.'&amp;memo_no='.$list['no']);
		else
			$list['delete_link'] = $this->router->link_href('memo_delete', urldecode($this->uri->qstr).'&amp;token='.$delete_memo_token.'&amp;memo_no='.$list['no']);

		unset($memo_row);

		return $list;
	}

	// POST 메모 등록
	public function post_memo_regist()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'memo_regist';

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		$regist = array();		
		$regist['memo_content']  = $this->common->get_post_data('memo_content', 800);

		$this->memo_regist($regist, $member_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $member_no, 'result' => 'ok' );

		return $result_data;
	}

	private function memo_regist($regist_row, $member_no)
	{
		$memo_created_datetime = CFX_TIME_YMDHIS;

		$sql = " INSERT INTO `{$this->member_memo_table}`
					SET  `member_no` = '{$member_no}'
						,`memo_member_id` = '{$this->auth->get_session_member_id()}'
						,`memo_member_name` = '{$this->auth->get_session_member_name()}'
						,`memo_member_ip` = '{$_SERVER['REMOTE_ADDR']}'
						, `memo_content`= '{$regist_row['memo_content']}'
						, `memo_created_datetime` = '{$memo_created_datetime}'
				";

		$this->db->sql_query($sql);
	}

	// GET 그룹 메모 삭제
	public function get_memo_delete()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'memo_delete';

		$delete = array();	
		$member_no  = $this->router->idx;

		// 토큰 검사
		if ($this->common->check_csrf_token('delete_memo') === FALSE)
		{
			// 실패
			$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => '올바른 접근 방식이 아닙니다.', 'result' => 'error' );
			return;			
		}

		$memo_no  = (int)$this->common->get_data('memo_no', 10);

		$sql = " DELETE FROM  `{$this->member_memo_table}`
					WHERE  `memo_no` = '{$memo_no}';
				";

		$this->db->sql_query($sql);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $member_no, 'result' => 'ok' );

		return $result_data;
	}

	// POST 그룹 라벨 변경
	public function post_group_label_change()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'group_label_change';

		$group_no  = (int)$this->common->get_post_data('group_no', 10);

		$field = $this->select_group_label_option($group_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'ajax', 'field' => $field, 'result' => 'ok' );

		return $result_data;
	}
}

?>