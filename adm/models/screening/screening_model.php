<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Screening_Model extends CFX_Model
{
	public $uri;
	public $router;
	public $config;

	public $member_table = CFX_MEMBER_TABLE;	
	public $screening_table = CFX_SCREENING_TABLE;

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
		$screening_pages = '';
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
		$sql = " SELECT COUNT(DISTINCT `screening_no`) AS `cnt` FROM `{$this->screening_table}` AS sc; ";

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
			$sql_order = " ORDER BY sc.screening_{$this->uri->ssort} {$this->uri->sorder} ";

		if ($this->uri->stext)
			$sql = " SELECT DISTINCT sc.screening_no FROM `{$this->screening_table}` AS sc {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		else
			$sql = " SELECT * FROM `{$this->screening_table}` AS sc  {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";


		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$screening_result = $this->db->sql_query($sql);

			$k = 0;

			while ($screening_row = $this->db->sql_fetch_array($screening_result))
			{
				// 검색일 경우 screening_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->stext)
					$screening_row = $this->db->sql_fetch(" SELECT * FROM `{$this->screening_table}` WHERE `screening_no` = '{$screening_row['screening_no']}' ");

				$list[$i] = $this->get_screening_list($screening_row, $this->uri->qstr, $this->router->link_href('view'));

				$list_num = $total_count - ($current_page - 1) * $list_page_rows;
				
				$list[$i]['num'] = intVal($list_num - $k);

				unset($screening_row);

				$i++;
				$k++;
			}
		}

		$result['screening_list'] = $list;

		$pages = $this->config->view['pages'];
		$list_pages_href = $this->router->link_href('lists');

		if (empty($this->uri->qstr))
			$list_pages_href .= '?';
		else
			$list_pages_href .= $this->uri->qstr;

		$screening_pages = $this->common->get_paging($pages, $current_page, $total_page, $list_pages_href);

		// 설문 스크리닝 페이지
		$result['screening_pages'] = $screening_pages;

		return $result;
	}

	// 설문 스크리닝 정보($screening_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_screening_list($screening_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$screening_row['screening_no'];
		$list['screening_group'] = get_text($screening_row['screening_group']);
		$list['screening_order'] = get_text($screening_row['screening_order']);
		$list['screening_title'] = html_symbol($screening_row['screening_title']);
		$list['title'] = get_text($screening_row['screening_title']);
		$list['screening_text1'] = get_text($screening_row['screening_text1']);
		$list['screening_text2'] = get_text($screening_row['screening_text2']);
		$list['screening_type'] =(int)($screening_row['screening_type']);
		$list['modified_datetime'] = substr($screening_row['screening_modified_datetime'],0,10);

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($screening_row);

		return $list;
	}

	// get_screening_list 의 alias
	public function get_screening_view($screening_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_screening_list($screening_row, $qstr, $view_href, $mode);
	}

	public function get_view()
	{
		$result = array();

		// 설문 스크리닝 순번 설정
		$screening_no = $this->router->idx;

		// 설문 스크리닝 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($screening_no) || $screening_no == 0)
		{
			alert( '설문 스크리닝 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$screening_row = array();
		$screening_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->screening_table}` WHERE screening_no = '{$screening_no}'; ");
		$screening_view = $this->get_screening_view($screening_row, urldecode($this->uri->qstr), '', 'view');

		unset($screening_row);

		if (!isset($screening_view['no']) || $screening_view['no'] == 0)
		{
			alert( '설문 스크리닝 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['screening_view'] = $screening_view;

		unset($screening_view);

		return $result;
	}

	public function get_update()
	{
		// 수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_screening');

		$result = array();

		// 설문 스크리닝 순번 설정
		$screening_no = $this->router->idx;

		// 설문 스크리닝 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($screening_no) || $screening_no == 0)
		{
			alert( '설문 스크리닝 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$screening_row = array();
		$screening_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->screening_table}` WHERE screening_no = '{$screening_no}'; ");
		$screening_view = $this->get_screening_view($screening_row, urldecode($this->uri->qstr), '', 'view');

		unset($screening_row);

		if (!isset($screening_view['no']) || $screening_view['no'] == 0)
		{
			alert( '설문 스크리닝 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['screening_view'] = $screening_view;
		unset($screening_view);

		return $result;
	}

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_screening';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 설정 순번 설정
		$screening_no = $this->router->idx;

		$update = array();

		$update['screening_group']  = $this->common->get_post_data('screening_group', 255);
		$update['screening_title']  = $this->common->get_post_data('screening_title', 255);
		$update['screening_text1']  = $this->common->get_post_data('screening_text1', 255);
		$update['screening_text2']  = $this->common->get_post_data('screening_text2', 255);

		$this->screening_update($update, $screening_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' =>  $screening_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function screening_update($update_row, $screening_no)
	{
		$screening_created_datetime = CFX_TIME_YMDHIS;

		$sql = " UPDATE `{$this->screening_table}`
					SET `screening_group` = '{$update_row['screening_group']}'
					, `screening_title` = '{$update_row['screening_title']}'
					, `screening_text1` = '{$update_row['screening_text1']}'
					, `screening_text2` = '{$update_row['screening_text2']}'
					, `screening_modified_datetime` = '{$screening_created_datetime}'
					WHERE `screening_no` = '{$screening_no}';
				";

		$this->db->sql_query($sql);
	}
}
?>