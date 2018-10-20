<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Manager_Model extends CFX_Model
{
	public $db;
	public $auth;
	public $uri;
	public $member;
	public $router;
	
	public $member_table = CFX_MEMBER_TABLE;
	public $group_table = CFX_GROUP_TABLE;

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
		$this->router = $CFX->router;
	}

	public function get_lists()
	{
		$level = 6; // 담당자 레벨 설정

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

		// 검색어가 있다면
		if ($this->uri->sgroup || $this->uri->stext)
		{
			$sql_search = $this->member->get_sql_search($this->uri->sgroup, $this->uri->sfield, $this->uri->stext, $this->uri->sop);

			// 가장 작은 번호를 얻어서 변수에 저장 (하단의 페이징에서 사용)
			$sql = " SELECT MIN(`member_no`) AS `min_member_no` FROM `{$this->member_table}` AS mb WHERE mb.member_level >= {$level};";
			$min_member_row = $this->db->sql_fetch($sql);
			$min_spart = (int)$min_member_row['min_member_no'];

			if (empty($this->uri->spart))
				$this->uri->spart = $min_spart;

			$sql_search .= " AND (mb.member_no between {$this->uri->spart} AND ({$this->uri->spart} + {$this->config->view['search_part']})) AND mb.member_level >= {$level} ";

			// 회원수를 구한다.
			$sql = " SELECT COUNT(DISTINCT `member_no`) AS `cnt` FROM `{$this->member_table}` AS mb WHERE {$sql_search}";

			$member_row = $this->db->sql_fetch($sql);
			$total_count = $member_row['cnt'];
		}
		else
		{
			$sql_search = '';
			// 회원수를 구한다.
			$sql = " SELECT COUNT(DISTINCT `member_no`) AS `cnt` FROM `{$this->member_table}` AS mb WHERE mb.member_level >= {$level};";

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
			$sql = " SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->group_table}` AS gp WHERE mb.member_group = gp.group_no AND mb.member_level >= {$level} {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
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
					$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->group_table}` AS gp WHERE mb.member_group = gp.group_no AND mb.member_level >= {$level} AND mb.member_no = '{$member_row['member_no']}' ");
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

		if ($list['level'] == 1) {
			$list['level_name'] = '일반인';
		} else if ($list['level'] == 3) {
			$list['level_name'] = '참여자';
		} else if ($list['level'] == 6) {
			$list['level_name'] = '프로젝트관리자';
		} else if ($list['level'] == 9) {
			$list['level_name'] = '전체관리자';
		}

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
		$level = 6; // 참여자 레벨 설정

		$result = array();

		// 멤버 순번 설정
		$member_no = $this->router->idx;

		// 회원가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($member_no) || $member_no == 0)
		{
			alert( '담당자 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$member_row = array();
		$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->group_table}` AS gp WHERE mb.member_group = gp.group_no AND mb.member_level >= {$level} AND mb.member_no = '{$member_no}' ");
		$member_view = $this->get_member_view($member_row, urldecode($this->uri->qstr), '', 'view');

		unset($member_row);

		if (!isset($member_view['no']) || $member_view['no'] == 0)
		{
			alert( '담당자 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		// 삭제 토큰생성 설정
		$delete_token = $this->common->get_csrf_token('delete_manager');

		// 삭제링크 설정
		if (empty($this->uri->qstr))
			$result['delete_link'] = $this->router->link_href('delete', '?token='.$delete_token);
		else
			$result['delete_link'] = $this->router->link_href('delete', urldecode($this->uri->qstr).'&amp;token='.$delete_token);

		$result['select_group']= $this->select_group_option($member_view['group']);		

		$result['member_view'] = $member_view;
		unset($member_view);

		return $result;
	}

	public function get_update()
	{
		$level = 6; // 참여자 레벨 설정

		// 회원수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_manager');

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
		$member_row = $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` AS mb INNER JOIN `{$this->group_table}` AS gp WHERE mb.member_group = gp.group_no AND mb.member_level >= {$level} AND mb.member_no = '{$member_no}' ");
		$member_view = $this->get_member_view($member_row, urldecode($this->uri->qstr), '', 'view');

		unset($member_row);

		if (!isset($member_view['no']) || $member_view['no'] == 0)
		{
			alert( '회원 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['select_group']= $this->select_group_option($member_view['group']);		

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

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_manager';

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
		$update['member_name']  = $this->common->get_post_data('member_name', 20);
		$update['member_pw']  = $this->common->get_post_data('pw', 50);

		$this->member_update($update, $member_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $member_no .urldecode($this->uri->qstr), 'result' => 'ok' );

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
		} else {
			$member_pw = $this->db->get_encrypt_string($update_row['member_pw'] );
			$sql_passwd = ", `member_pw` = '{$member_pw}'";
		}

		$sql = " UPDATE `{$this->member_table}`
					SET `member_name` = '{$update_row['member_name']}'
					,  `member_group` = '{$update_row['group']}'
					{$sql_activated}
					{$sql_activated}
					{$sql_passwd}
					WHERE `member_no` = '{$member_no}';
				";

		$this->db->sql_query($sql);
	}
}

?>