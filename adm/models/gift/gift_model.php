<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Gift_Model extends CFX_Model
{
	public $uri;
	public $router;
	public $config;

	public $member_table = CFX_MEMBER_TABLE;	
	public $gift_table = CFX_GIFT_TABLE;
	public $setup_table = CFX_SETUP_TABLE;

	public function __construct()
	{
        parent::__construct();

        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->uri = $CFX->uri;
		$this->router = $CFX->router;
		$this->config = $CFX->config;
	}

	public function get_lists()
	{
		$result = array();

		// 페이지 값 설정
		$gift_pages = '';
		$current_page = $this->uri->page;
		$list_page_rows = $this->config->view['page_rows'];
		$total_count = 0;
		$total_page = 1;
		$qstr_sort = '';

		$this->uri->page_rows = ($this->uri->page_rows) ? $this->uri->page_rows : $list_page_rows;

		// 페이지 값 설정
		$result['current_page'] = $current_page;

		// 페이지 목록 옵션 설정
		$result['select_page_rows_option'] = $this->uri->select_page_rows_option($this->uri->page_rows, $list_page_rows, $this->config->view['page_rows_list'], $this->router->link_href('lists'));

		// 그룹수를 구한다.
		$sql = " SELECT COUNT(DISTINCT `gift_no`) AS `cnt` FROM `{$this->gift_table}` AS gt; ";

		// echo $sql;

		$row = $this->db->sql_fetch($sql);
		$total_count = $row['cnt'];

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
			$sql_order = " ORDER BY gt.gift_{$this->uri->ssort} {$this->uri->sorder} ";

		if ($this->uri->stext)
			$sql = " SELECT DISTINCT gt.gift_no FROM `{$this->gift_table}` AS gt {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		else
			$sql = " SELECT * FROM `{$this->gift_table}` AS gt INNER JOIN `{$this->member_table}` AS mb WHERE gt.member_id  =  mb.member_id {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";


		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$gift_result = $this->db->sql_query($sql);

			$k = 0;

			while ($gift_row = $this->db->sql_fetch_array($gift_result))
			{
				// 검색일 경우 gift_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->stext)
					$gift_row = $this->db->sql_fetch(" SELECT * FROM `{$this->gift_table}` AS gt INNER JOIN  `{$this->member_table}` AS mb WHERE gt.member_id  =  mb.member_id AND `gift_no` = '{$gift_row['gift_no']}' ");

				$list[$i] = $this->get_gift_list($gift_row, $this->uri->qstr, $this->router->link_href('view'));

				$list_num = $total_count - ($current_page - 1) * $list_page_rows;
				
				$list[$i]['num'] = intVal($list_num - $k);

				unset($gift_row);

				$i++;
				$k++;
			}
		}

		$result['gift_list'] = $list;

		$pages = $this->config->view['pages'];
		$list_pages_href = $this->router->link_href('lists');

		if (empty($this->uri->qstr))
			$list_pages_href .= '?';
		else
			$list_pages_href .= $this->uri->qstr;

		$gift_pages = $this->common->get_paging($pages, $current_page, $total_page, $list_pages_href);

		// 기프티콘 페이지
		$result['gift_pages'] = $gift_pages;

		return $result;
	}

	// 기프티콘 정보($gift_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_gift_list($gift_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$gift_row['gift_no'];
		$list['program'] = get_text($gift_row['gift_program']);
		$list['member_id'] = get_text($gift_row['member_id']);
		$list['member_key'] = get_text($gift_row['member_key']);
		$list['member_no'] = (int)$gift_row['member_no'];
		$list['member_email'] = get_text($gift_row['member_email']);
		$list['member_hp'] = get_text($gift_row['member_hp']);
		$list['member_name'] = get_text($gift_row['member_name']);
		$list['login_ip'] = get_text($gift_row['member_login_ip']);
		$list['login_datetime'] = substr($gift_row['member_login_datetime'],0,10);
		$list['gift_count'] = (int)$gift_row['gift_count'];
		$list['gift_num'] = (int)$gift_row['gift_num'];
		$list['memo'] = get_text($gift_row['gift_memo']);
		$list['confirm_datetime'] = substr($gift_row['gift_confirm_datetime'],0,10);
		$list['gift_created_datetime'] = $gift_row['gift_created_datetime'];
		$list['created_datetime'] = substr($list['gift_created_datetime'],0,10);

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($gift_row);

		return $list;
	}

	// get_gift_list 의 alias
	public function get_gift_view($gift_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_gift_list($gift_row, $qstr, $view_href, $mode);
	}

	public function get_view()
	{
		$result = array();

		// 기프티콘 순번 설정
		$gift_no = $this->router->idx;

		// 기프티콘 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($gift_no) || $gift_no == 0)
		{
			alert( '콘 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$gift_row = array();
		$gift_row = $this->db->sql_fetch(" SELECT * FROM `{$this->gift_table}` AS gt INNER JOIN  `{$this->member_table}` AS mb WHERE gt.member_id  =  mb.member_id AND gt.gift_no = '{$gift_no}'; ");
		$gift_view = $this->get_gift_view($gift_row, urldecode($this->uri->qstr), '', 'view');

		unset($gift_row);

		if (!isset($gift_view['no']) || $gift_view['no'] == 0)
		{
			alert( '기프티콘 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$setup_row = array();
		$setup_row = $this->db->sql_fetch(" SELECT `setup_data{$gift_view['gift_num']}` AS gift_image FROM  `{$this->setup_table}` WHERE setup_no = 1; ");

		$result['gift_image'] = $setup_row['gift_image'];

		unset($setup_row);

		$result['gift_view'] = $gift_view;

		unset($gift_view);

		return $result;
	}

	public function get_update()
	{
		// 수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_gift');

		$result = array();

		// 기프티콘 순번 설정
		$gift_no = $this->router->idx;

		// 기프티콘 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($gift_no) || $gift_no == 0)
		{
			alert( '기프티콘 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$gift_row = array();
		$gift_row = $this->db->sql_fetch(" SELECT * FROM `{$this->gift_table}` AS gt INNER JOIN  `{$this->member_table}` AS mb WHERE gt.member_id  =  mb.member_id AND gt.gift_no = '{$gift_no}'; ");
		$gift_view = $this->get_gift_view($gift_row, urldecode($this->uri->qstr), '', 'view');

		unset($gift_row);

		if (!isset($gift_view['no']) || $gift_view['no'] == 0)
		{
			alert( '기프티콘 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$setup_row = array();
		$setup_row = $this->db->sql_fetch(" SELECT `setup_data{$gift_view['gift_num']}` AS gift_image FROM  `{$this->setup_table}` WHERE setup_no = 1; ");

		$result['gift_image'] = $setup_row['gift_image'];

		unset($setup_row);

		$result['gift_view'] = $gift_view;
		unset($gift_view);

		return $result;
	}

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_gift';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 설정 순번 설정
		$gift_no = $this->router->idx;

		$update = array();

		$update['gift_memo']  = $this->common->get_post_data('gift_memo', 255);

		$this->gift_update($update, $gift_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' =>  $gift_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function gift_update($update_row, $gift_no)
	{
		$gift_confirm_datetime = CFX_TIME_YMDHIS;

		$sql = " UPDATE `{$this->gift_table}`
					SET `gift_memo` = '{$update_row['gift_memo']}'
					, `gift_confirm_datetime` = '{$gift_confirm_datetime}'
					WHERE `gift_no` = '{$gift_no}';
				";

		$this->db->sql_query($sql);
	}
}
?>