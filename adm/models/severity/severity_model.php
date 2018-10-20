<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Severity_Model extends CFX_Model
{
	public $uri;
	public $router;
	public $config;

	public $member_table = CFX_MEMBER_TABLE;	
	public $severity_table = CFX_SEVERITY_TABLE;

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
		$severity_pages = '';
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
		$sql = " SELECT COUNT(DISTINCT `severity_no`) AS `cnt` FROM `{$this->severity_table}` AS sv; ";

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
			$sql_order = " ORDER BY sv.severity_{$this->uri->ssort} {$this->uri->sorder} ";

		if ($this->uri->stext)
			$sql = " SELECT DISTINCT sv.severity_no FROM `{$this->severity_table}` AS sv {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		else
			$sql = " SELECT * FROM `{$this->severity_table}` AS sv  {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";


		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$severity_result = $this->db->sql_query($sql);

			$k = 0;

			while ($severity_row = $this->db->sql_fetch_array($severity_result))
			{
				// 검색일 경우 severity_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->stext)
					$severity_row = $this->db->sql_fetch(" SELECT * FROM `{$this->severity_table}` WHERE `severity_no` = '{$severity_row['severity_no']}' ");

				$list[$i] = $this->get_severity_list($severity_row, $this->uri->qstr, $this->router->link_href('view'));

				$list_num = $total_count - ($current_page - 1) * $list_page_rows;
				
				$list[$i]['num'] = intVal($list_num - $k);

				unset($severity_row);

				$i++;
				$k++;
			}
		}

		$result['severity_list'] = $list;

		$pages = $this->config->view['pages'];
		$list_pages_href = $this->router->link_href('lists');

		if (empty($this->uri->qstr))
			$list_pages_href .= '?';
		else
			$list_pages_href .= $this->uri->qstr;

		$severity_pages = $this->common->get_paging($pages, $current_page, $total_page, $list_pages_href);

		// 심각도 평가 페이지
		$result['severity_pages'] = $severity_pages;

		return $result;
	}

	// 심각도 평가 정보($severity_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_severity_list($severity_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$severity_row['severity_no'];
		$list['severity_group'] = get_text($severity_row['severity_group']);
		$list['severity_order'] = get_text($severity_row['severity_order']);
		$list['severity_title'] = get_text($severity_row['severity_title']);
		$list['severity_show_title'] = (int)$severity_row['severity_show_title'];
		$list['severity_name'] = get_text($severity_row['severity_name']);
		$list['severity_text1'] = get_text($severity_row['severity_text1']);
		$list['severity_text2'] = get_text($severity_row['severity_text2']);
		$list['severity_text3'] = get_text($severity_row['severity_text3']);
		$list['severity_text4'] = get_text($severity_row['severity_text4']);
		$list['severity_text5'] = get_text($severity_row['severity_text5']);
		$list['severity_text6'] = get_text($severity_row['severity_text6']);
		$list['severity_type'] =(int)($severity_row['severity_type']);
		$list['modified_datetime'] = substr($severity_row['severity_modified_datetime'],0,10);

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($severity_row);

		return $list;
	}

	// get_severity_list 의 alias
	public function get_severity_view($severity_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_severity_list($severity_row, $qstr, $view_href, $mode);
	}

	public function get_view()
	{
		$result = array();

		// 심각도 평가 순번 설정
		$severity_no = $this->router->idx;

		// 심각도 평가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($severity_no) || $severity_no == 0)
		{
			alert( '심각도 평가 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$severity_row = array();
		$severity_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->severity_table}` WHERE severity_no = '{$severity_no}'; ");
		$severity_view = $this->get_severity_view($severity_row, urldecode($this->uri->qstr), '', 'view');

		unset($severity_row);

		if (!isset($severity_view['no']) || $severity_view['no'] == 0)
		{
			alert( '심각도 평가 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['severity_view'] = $severity_view;

		unset($severity_view);

		return $result;
	}

	public function get_update()
	{
		// 수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_severity');

		$result = array();

		// 심각도 평가 순번 설정
		$severity_no = $this->router->idx;

		// 심각도 평가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($severity_no) || $severity_no == 0)
		{
			alert( '심각도 평가 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$severity_row = array();
		$severity_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->severity_table}` WHERE severity_no = '{$severity_no}'; ");
		$severity_view = $this->get_severity_view($severity_row, urldecode($this->uri->qstr), '', 'view');

		unset($severity_row);

		if (!isset($severity_view['no']) || $severity_view['no'] == 0)
		{
			alert( '심각도 평가 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['severity_view'] = $severity_view;
		unset($severity_view);

		return $result;
	}

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_severity';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 설정 순번 설정
		$severity_no = $this->router->idx;

		$update = array();

		$update['severity_group']  = $this->common->get_post_data('severity_group', 255);
		$update['severity_title']  = $this->common->get_post_data('severity_title', 255);
		$update['severity_name']  = $this->common->get_post_data('severity_name', 255);
		$update['severity_text1']  = $this->common->get_post_data('severity_text1', 255);
		$update['severity_text2']  = $this->common->get_post_data('severity_text2', 255);
		$update['severity_text3']  = $this->common->get_post_data('severity_text3', 255);
		$update['severity_text4']  = $this->common->get_post_data('severity_text4', 255);
		$update['severity_text5']  = $this->common->get_post_data('severity_text5', 255);
		$update['severity_text6']  = $this->common->get_post_data('severity_text6', 255);

		$this->severity_update($update, $severity_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' =>  $severity_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function severity_update($update_row, $severity_no)
	{
		$severity_created_datetime = CFX_TIME_YMDHIS;

		$sql = " UPDATE `{$this->severity_table}`
					SET `severity_group` = '{$update_row['severity_group']}'
					, `severity_title` = '{$update_row['severity_title']}'
					, `severity_name` = '{$update_row['severity_name']}'
					, `severity_text1` = '{$update_row['severity_text1']}'
					, `severity_text2` = '{$update_row['severity_text2']}'
					, `severity_text3` = '{$update_row['severity_text3']}'
					, `severity_text4` = '{$update_row['severity_text4']}'
					, `severity_text5` = '{$update_row['severity_text5']}'
					, `severity_text6` = '{$update_row['severity_text6']}'
					, `severity_modified_datetime` = '{$severity_created_datetime}'
					WHERE `severity_no` = '{$severity_no}';
				";

		$this->db->sql_query($sql);
	}
}
?>