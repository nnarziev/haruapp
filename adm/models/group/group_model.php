<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Group_Model extends CFX_Model
{
	public $uri;
	public $router;

	public $member_table = CFX_MEMBER_TABLE;	
	public $group_table = CFX_GROUP_TABLE;
	public $group_label_table = CFX_GROUP_LABEL_TABLE;	

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
	}

	public function get_lists()
	{
		$result = array();

		// 페이지 값 설정
		$group_pages = '';
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
		$sql = " SELECT COUNT(DISTINCT `group_no`) AS `cnt` FROM `{$this->group_table}` AS gp; ";

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
			$sql_order = " ORDER BY gp.group_{$this->uri->ssort} {$this->uri->sorder} ";

		if ($this->uri->stext)
			$sql = " SELECT DISTINCT gp.group_no FROM `{$this->group_table}` AS gp {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		else
			$sql = " SELECT * FROM `{$this->group_table}` AS gp  {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";


		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$group_result = $this->db->sql_query($sql);

			$k = 0;

			while ($group_row = $this->db->sql_fetch_array($group_result))
			{
				// 검색일 경우 group_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->stext) {
					$group_row = $this->db->sql_fetch(" SELECT * FROM `{$this->group_table}` WHERE `group_no` = '{$group_row['group_no']}' ");
				}

				$list[$i] = $this->get_group_list($group_row, $this->uri->qstr, $this->router->link_href('view'));

				$list_num = $total_count - ($current_page - 1) * $list_page_rows;
				
				$list[$i]['num'] = intVal($list_num - $k);

				unset($group_row);

				$i++;
				$k++;
			}
		}

		$result['group_list'] = $list;

		$pages = $this->config->view['pages'];
		$list_pages_href = $this->router->link_href('lists');

		if (empty($this->uri->qstr))
			$list_pages_href .= '?';
		else
			$list_pages_href .= $this->uri->qstr;

		$group_pages = $this->common->get_paging($pages, $current_page, $total_page, $list_pages_href);

		// 그룹 페이지
		$result['group_pages'] = $group_pages;

		return $result;
	}

	// 그룹 정보($group_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_group_list($group_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$group_row['group_no'];
		$list['name'] = get_text($group_row['group_name']);
 		$list['status'] = ($group_row['group_activated'] == 0) ? '비활성화' : '활성화';
 		$list['activated'] = $group_row['group_activated'];

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($group_row);

		$sql = " SELECT `group_no`, group_concat(`label_no`) AS label_no, group_concat(`label_name`) AS label_name FROM `{$this->group_label_table}` WHERE `group_no` = {$list['no']} AND `label_activated` = 1 GROUP BY `group_no`  ORDER BY `label_no` ASC;";

		$list['label_name'] = '미지정'; 
		$label_row = $this->db->sql_fetch($sql);

		if ($label_row) {
			$list['label_name'] = $label_row['label_name']; 
		}

		unset($label_row);

		$sql = "SELECT `member_group`, group_concat(`member_no`) AS member_no, group_concat(`member_id`) AS member_id,  group_concat(`member_name`) AS member_name FROM `{$this->member_table}` WHERE `member_group` = {$list['no']} AND `member_level` >= 6 AND `member_activated` = 1 GROUP BY `member_group` ORDER BY `member_no` ASC;";

		$list['member_no'] = '';
		$list['member_id'] = '';
		$list['manager_name'] = '미지정'; 
		$manager_row = $this->db->sql_fetch($sql);

		if ($manager_row) {
			$list['member_no'] = $manager_row['member_no']; 
			$list['member_id'] = $manager_row['member_id']; 
			$list['manager_name'] = $manager_row['member_name']; 
		}

		unset($manager_row);

		$sql = "SELECT COUNT(mb.member_no) AS activated_cnt FROM `{$this->member_table}` AS mb INNER JOIN  `{$this->group_table}` AS gp INNER JOIN  `{$this->group_label_table}` AS gl WHERE mb.member_group = gp.group_no AND mb.member_group = gl.group_no AND mb.member_activated = 1 AND gp.group_activated = {$list['activated']} AND gl.label_activated = 1 AND mb.member_level = 3 AND mb.member_group = {$list['no']};";

		$activated_count_row = $this->db->sql_fetch($sql);
		$list['member_activated_count'] = $activated_count_row['activated_cnt'];
		unset($activated_count_row);

		$sql = "SELECT COUNT(mb.member_no) AS inactivated_cnt FROM `{$this->member_table}` AS mb INNER JOIN  `{$this->group_table}` AS gp INNER JOIN  `{$this->group_label_table}` AS gl WHERE mb.member_group = gp.group_no AND mb.member_group = gl.group_no AND mb.member_activated = 1 AND gp.group_activated = {$list['activated']} AND gl.label_activated = 0 AND mb.member_level = 3 AND mb.member_group = {$list['no']};";

		$inactivated_count_row = $this->db->sql_fetch($sql);
		$list['member_inactivated_count'] = $inactivated_count_row['inactivated_cnt'];
		unset($inactivated_count_row);

		return $list;
	}

	// get_group_list 의 alias
	public function get_group_view($group_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_group_list($group_row, $qstr, $view_href, $mode);
	}

	public function get_view()
	{
		$result = array();

		// 그룹 순번 설정
		$group_no = $this->router->idx;

		// 그룹정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($group_no) || $group_no == 0)
		{
			alert( '프로젝트 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$group_row = array();
		$group_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->group_table}` WHERE group_no = '{$group_no}'; ");

		$group_view = $this->get_group_view($group_row, urldecode($this->uri->qstr), '', 'view');
		unset($group_row);

		if (!isset($group_view['no']) || $group_view['no'] == 0)
		{
			alert( '프로젝트 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		// 삭제 토큰생성 설정
		$delete_token = $this->common->get_csrf_token('delete_group');

		// 삭제링크 설정
		if (empty($this->uri->qstr))
			$result['delete_link'] = $this->router->link_href('delete', '?token='.$delete_token);
		else
			$result['delete_link'] = $this->router->link_href('delete', urldecode($this->uri->qstr).'&amp;token='.$delete_token);

		$result['group_view'] = $group_view;

		unset($group_view);

		$sql = " SELECT * FROM `{$this->group_label_table}` AS gl WHERE gl.group_no = {$group_no} ORDER BY `label_created_datetime` ASC;";

		$i = 0;
		$list = array();
		$label_result = $this->db->sql_query($sql);

		// 삭제 토큰생성 설정
		$delete_label_token = $this->common->get_csrf_token('delete_label');

		while ($label_row = $this->db->sql_fetch_array($label_result))
		{
			$list[$i] = $this->get_label_list($label_row, $delete_label_token);

			unset($label_row);
			$i++;
		}

		$result['label_list'] = $list;

		$sql = " SELECT mb.member_no, mb.member_id, mb.member_name,  gl.label_name, gl.label_activated, mb.member_login_datetime, mb.member_created_datetime FROM `{$this->member_table}` AS mb INNER JOIN `{$this->group_table}` AS gp INNER JOIN  `{$this->group_label_table}` AS gl  WHERE mb.member_group = gp.group_no  AND mb.member_group = gl.group_no AND mb.member_activated = 1 AND mb.member_level = 3 AND mb.member_group = {$group_no} ORDER BY mb.member_created_datetime ASC;";

		$i = 0;
		$member_list = array();
		$member_result = $this->db->sql_query($sql);

		while ($member_row = $this->db->sql_fetch_array($member_result))
		{
			$member_list[$i] = $this->get_group_member_list($member_row);

			unset($member_row);
			$i++;
		}

		$result['member_list'] = $member_list;
		unset($member_list);

		return $result;
	}

	// 그룹 멤버 정보($member_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_group_member_list($member_row)
	{
		$list = array();

		$list['member_no'] = $member_row['member_no'];
		$list['member_id'] = $member_row['member_id'];
		$list['member_name'] = $member_row['member_name'];
		$list['member_label_name'] = $member_row['label_name'];
		$list['member_label_activated'] = $member_row['label_activated'];
 		$list['member_label_status'] = ($member_row['label_activated'] == 0) ? '비활성화' : '활성화';
		$list['member_login_datetime'] =$member_row['member_login_datetime'];
		$list['member_created_datetime'] = $member_row['member_created_datetime'];

		return $list;
	}

	public function get_update()
	{
		// 회원수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_group');

		$result = array();

		// 그룹 순번 설정
		$group_no = $this->router->idx;

		// 회원가 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($group_no) || $group_no == 0)
		{
			alert( '프로젝트 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$group_row = array();
		$group_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->group_table}` WHERE group_no = '{$group_no}'; ");

		$group_view = $this->get_group_view($group_row, urldecode($this->uri->qstr), '', 'view');
		unset($group_row);

		if (!isset($group_view['no']) || $group_view['no'] == 0)
		{
			alert( '프로젝트 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['group_view'] = $group_view;
		unset($group_view);

		return $result;
	}

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_group';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 그룹 순번 설정
		$group_no = $this->router->idx;

		$update = array();
		$update['name']  = $this->common->get_post_data('name', 255);
		$update['activated']  = (int)$this->common->get_post_data('activated', 1);

		$this->group_update($update, $group_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $group_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function group_update($update_row, $group_no)
	{
		$sql = " UPDATE `{$this->group_table}`
					SET `group_activated` = '{$update_row['activated']}'
					, `group_name` = '{$update_row['name']}'
					WHERE `group_no` = '{$group_no}';
				";

		$this->db->sql_query($sql);
	}

	// 그룹 모임 정보($label_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_label_list($label_row, $delete_label_token = '')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$label_row['label_no'];
		$list['name'] = get_text($label_row['label_name']);
		$list['activated'] = get_text($label_row['label_activated']);
		$list['created_datetime'] = substr($label_row['label_created_datetime'],0,10);

		// 그룹 라벨 삭제링크 설정
		if (empty($this->uri->qstr))
			$list['delete_link'] = $this->router->link_href('label_delete', '?token='.$delete_label_token.'&amp;label_no='.$list['no']);
		else
			$list['delete_link'] = $this->router->link_href('label_delete', urldecode($this->uri->qstr).'&amp;token='.$delete_label_token.'&amp;label_no='.$list['no']);

		unset($label_row);

		return $list;
	}

	// POST 그룹 모임 등록
	public function post_label_regist()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'label_regist';

		// 그룹 순번 설정
		$group_no = $this->router->idx;

		$regist = array();		
		$regist['label_name']  = $this->common->get_post_data('label_name', 255);

		$this->label_regist($regist, $group_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $group_no, 'result' => 'ok' );

		return $result_data;
	}

	private function label_regist($regist_row, $group_no)
	{
		$label_created_datetime = CFX_TIME_YMDHIS;

		$sql = " INSERT INTO `{$this->group_label_table}`
					SET  `group_no` = '{$group_no}'
						, `label_name`= '{$regist_row['label_name']}'
						, `label_created_datetime` = '{$label_created_datetime}'
				";

		$this->db->sql_query($sql);
	}

	// POST 그룹 모임 업데이트
	public function post_label_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'label_update';

		$update = array();	
		$update['no']  = (int)$this->common->get_post_data('no', 10);
		$update['activated']  = (int)$this->common->get_post_data('activated', 1);

		$sql = " UPDATE  `{$this->group_label_table}`
					SET `label_activated`= '{$update['activated']}'
					WHERE  `label_no` = '{$update['no']}';
				";

		$this->db->sql_query($sql);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'ajax', 'field' => '', 'result' => 'ok' );

		return $result_data;
	}

	// GET 그룹 라벨 삭제
	public function get_label_delete()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'label_delete';

		$delete = array();	
		$group_no  = $this->router->idx;

		// 토큰 검사
		if ($this->common->check_csrf_token('delete_label') === FALSE)
		{
			// 실패
			$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => '올바른 접근 방식이 아닙니다.', 'result' => 'error' );
			return;			
		}

		$label_no  = (int)$this->common->get_data('label_no', 10);

		$sql = " DELETE FROM  `{$this->group_label_table}`
					WHERE  `label_no` = '{$label_no}';
				";

		$this->db->sql_query($sql);

		$sql = " UPDATE  `{$this->member_table}`
					SET `member_group_label` = 0
					WHERE  `member_group_label` = '{$label_no}';
				";

		$this->db->sql_query($sql);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => $group_no, 'result' => 'ok' );

		return $result_data;
	}
}
?>