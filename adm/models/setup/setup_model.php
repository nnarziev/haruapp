<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Setup_Model extends CFX_Model
{
	public $uri;
	public $router;
	public $config;

	public $member_table = CFX_MEMBER_TABLE;	
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
		$setup_pages = '';
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
		$sql = " SELECT COUNT(DISTINCT `setup_no`) AS `cnt` FROM `{$this->setup_table}` AS st; ";

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
			$sql_order = " ORDER BY st.setup_{$this->uri->ssort} {$this->uri->sorder} ";

		if ($this->uri->stext)
			$sql = " SELECT DISTINCT st.setup_no FROM `{$this->setup_table}` AS st {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";
		else
			$sql = " SELECT * FROM `{$this->setup_table}` AS st  {$sql_order} LIMIT {$from_record}, {$this->uri->page_rows} ";


		$list = array();
		if ($this->uri->page_rows > 0)
		{
			$setup_result = $this->db->sql_query($sql);

			$k = 0;

			while ($setup_row = $this->db->sql_fetch_array($setup_result))
			{
				// 검색일 경우 setup_no만 얻었으므로 다시 한행을 얻는다
				if ($this->uri->stext)
					$setup_row = $this->db->sql_fetch(" SELECT * FROM `{$this->setup_table}` WHERE `setup_no` = '{$setup_row['setup_no']}' ");

				$list[$i] = $this->get_setup_list($setup_row, $this->uri->qstr, $this->router->link_href('view'));

				$list_num = $total_count - ($current_page - 1) * $list_page_rows;
				
				$list[$i]['num'] = intVal($list_num - $k);

				unset($setup_row);

				$i++;
				$k++;
			}
		}

		$result['setup_list'] = $list;

		$pages = $this->config->view['pages'];
		$list_pages_href = $this->router->link_href('lists');

		if (empty($this->uri->qstr))
			$list_pages_href .= '?';
		else
			$list_pages_href .= $this->uri->qstr;

		$setup_pages = $this->common->get_paging($pages, $current_page, $total_page, $list_pages_href);

		// 설정 페이지
		$result['setup_pages'] = $setup_pages;

		return $result;
	}

	// 설정 정보($setup_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_setup_list($setup_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$setup_row['setup_no'];
		$list['name'] = get_text($setup_row['setup_name']);
		$list['data1'] = get_text($setup_row['setup_data1']);
		$list['data2'] = get_text($setup_row['setup_data2']);
		$list['data3'] = get_text($setup_row['setup_data3']);
		$list['data4'] = get_text($setup_row['setup_data4']);
		$list['data5'] = get_text($setup_row['setup_data5']);
		$list['activated'] = (int)$setup_row['setup_activated'];
 		$list['status'] = ($list['activated'] == 1) ? '활성화' : '비활성화';

		$list['modified_datetime'] = substr($setup_row['setup_modified_datetime'],0,10);

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($setup_row);

		return $list;
	}

	// get_setup_list 의 alias
	public function get_setup_view($setup_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_setup_list($setup_row, $qstr, $view_href, $mode);
	}

	public function get_view()
	{
		$result = array();

		// 설정 순번 설정
		$setup_no = $this->router->idx;

		// 설정 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($setup_no) || $setup_no == 0)
		{
			alert( '설정 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$setup_row = array();
		$setup_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->setup_table}` WHERE setup_no = '{$setup_no}'; ");
		$setup_view = $this->get_setup_view($setup_row, urldecode($this->uri->qstr), '', 'view');

		unset($setup_row);

		if (!isset($setup_view['no']) || $setup_view['no'] == 0)
		{
			alert( '설정 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['setup_view'] = $setup_view;

		unset($setup_view);

		return $result;
	}

	public function get_update()
	{
		// 수정 모드 설정
		$this->uri->wmode = 'u';

		// 글쓰기 토큰 설정
		$this->uri->token = $this->common->get_csrf_token('update_setup');

		$result = array();

		// 설정 순번 설정
		$setup_no = $this->router->idx;

		// 설정 정보가 없을 경우 해당 게시판 목록으로 이동
		if (!isset($setup_no) || $setup_no == 0)
		{
			alert( '설정 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$setup_row = array();
		$setup_row = $this->db->sql_fetch(" SELECT * FROM  `{$this->setup_table}` WHERE setup_no = '{$setup_no}'; ");
		$setup_view = $this->get_setup_view($setup_row, urldecode($this->uri->qstr), '', 'view');

		unset($setup_row);

		if (!isset($setup_view['no']) || $setup_view['no'] == 0)
		{
			alert( '설정 정보가 존재하지 않습니다.', $this->router->link_href('lists') );
			return;
		}

		$result['setup_view'] = $setup_view;
		unset($setup_view);

		return $result;
	}

	// POST 업데이트
	public function post_update()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'update_setup';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 설정 순번 설정
		$setup_no = $this->router->idx;

		$update = array();

		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

		// default redirection
		$bSuccessUpload = is_uploaded_file($_FILES['data1']['tmp_name']);
		$update['data1']  = $this->file_upload($bSuccessUpload,  $_FILES['data1']['tmp_name'],  $_FILES['data1']['name']);

		$bSuccessUpload = is_uploaded_file($_FILES['data2']['tmp_name']);
		$update['data2']  = $this->file_upload($bSuccessUpload,  $_FILES['data2']['tmp_name'],  $_FILES['data2']['name']);

		$bSuccessUpload = is_uploaded_file($_FILES['data3']['tmp_name']);
		$update['data3']  = $this->file_upload($bSuccessUpload,  $_FILES['data3']['tmp_name'],  $_FILES['data3']['name']);

		$bSuccessUpload = is_uploaded_file($_FILES['data4']['tmp_name']);
		$update['data4']  = $this->file_upload($bSuccessUpload,  $_FILES['data4']['tmp_name'],  $_FILES['data4']['name']);

		$bSuccessUpload = is_uploaded_file($_FILES['data5']['tmp_name']);
		$update['data5']  = $this->file_upload($bSuccessUpload,  $_FILES['data5']['tmp_name'],  $_FILES['data5']['name']);

		$update['org_data1']  = $this->common->get_post_data('org_data1', 255);
		$update['org_data2']  = $this->common->get_post_data('org_data2', 255);
		$update['org_data3']  = $this->common->get_post_data('org_data3', 255);
		$update['org_data4']  = $this->common->get_post_data('org_data4', 255);
		$update['org_data5']  = $this->common->get_post_data('org_data5', 255);
		$update['activated']  = (int)$this->common->get_post_data('activated', 1);

		$this->setup_update($update, $setup_no);

		// 성공
		$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' =>  $setup_no.urldecode($this->uri->qstr), 'result' => 'ok' );

		return $result_data;
	}

	private function setup_update($update_row, $setup_no)
	{
		$setup_created_datetime = CFX_TIME_YMDHIS;

		$sql_setup_data1 = "";
		if ($update_row['data1'] != '')
		{
			$savefile1 = CFX_DATA_IMAGES_PATH.DIRECTORY_SEPARATOR.$update_row['org_data1'];
			 unlink($savefile1);

			$sql_setup_data1 = ", `setup_data1` = '{$update_row['data1']}'";
		}

		$sql_setup_data2 = "";
		if ($update_row['data2'] != '')
		{
			$savefile2 = CFX_DATA_IMAGES_PATH.DIRECTORY_SEPARATOR.$update_row['org_data2'];
			 unlink($savefile2);

			$sql_setup_data2 = ", `setup_data2` = '{$update_row['data2']}'";
		}

		$sql_setup_data3 = "";
		if ($update_row['data3'] != '')
		{
			$savefile3 = CFX_DATA_IMAGES_PATH.DIRECTORY_SEPARATOR.$update_row['org_data3'];
			 unlink($savefile3);

			$sql_setup_data3 = ", `setup_data3` = '{$update_row['data3']}'";
		}

		$sql_setup_data4 = "";
		if ($update_row['data4'] != '')
		{
			$savefile4 = CFX_DATA_IMAGES_PATH.DIRECTORY_SEPARATOR.$update_row['org_data4'];
			 unlink($savefile4);

			$sql_setup_data4 = ", `setup_data4` = '{$update_row['data4']}'";
		}

		$sql_setup_data5 = "";
		if ($update_row['data5'] != '')
		{
			$savefile5 = CFX_DATA_IMAGES_PATH.DIRECTORY_SEPARATOR.$update_row['org_data5'];
			 unlink($savefile5);

			$sql_setup_data5 = ", `setup_data5` = '{$update_row['data5']}'";
		}

		$sql = " UPDATE `{$this->setup_table}`
					SET `setup_activated` = '{$update_row['activated']}'
					{$sql_setup_data1}
					{$sql_setup_data2}
					{$sql_setup_data3}
					{$sql_setup_data4}
					{$sql_setup_data5}
					, `setup_modified_datetime` = '{$setup_created_datetime}'
					WHERE `setup_no` = '{$setup_no}';
				";

		$this->db->sql_query($sql);
	}

	private function file_upload($bSuccessUpload, $tempfile, $name)
	{
		$result = "";

		// SUCCESSFUL
		if ($bSuccessUpload)
		{
			$timg = @getimagesize($tempfile);
			// image type
			if (!preg_match("/\.({$this->config->view['image_extension']})$/i", $name))
			{
				// echo "<script type='text/javascript'>alert('JPG, GIF, PNG 형태의 이미지 파일만 등록 가능합니다.');</script>;";
				return;
			}
			else
			{
				shuffle($chars_array);
				$shuffle = implode('', $chars_array);

				// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
				$file_name = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.$this->common->replace_filename($name);

				// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
				@mkdir(CFX_DATA_IMAGES_PATH, CFX_DIR_PERMISSION);
				@chmod(CFX_DATA_IMAGES_PATH, CFX_DIR_PERMISSION);

				$savefile = CFX_DATA_IMAGES_PATH.DIRECTORY_SEPARATOR.$file_name;
				$savefile_url = CFX_DATA_IMAGES_URL.DIRECTORY_SEPARATOR.$file_name;

				@move_uploaded_file($tempfile, $savefile);
				$filesize = filesize($savefile);

				// 올라간 파일의 퍼미션을 변경합니다.
				chmod($savefile, CFX_FILE_PERMISSION);

				$result  = $file_name;
			}
		}

		return $result;
	}
}
?>