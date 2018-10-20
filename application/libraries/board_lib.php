<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Board_Lib {

	public $db;
	public $config;
	public $common;

	public $table_name = CFX_BOARD_TABLE;

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->db = $CFX->db;
		$this->config = $CFX->config;
		$this->common = $CFX->common; 
	}

	// 게시판 정보를 얻는다.
	public function get_board($board_table)
	{
		if (!empty(trim($board_table)))
		{
			$row = $this->db->sql_fetch(" SELECT * FROM `{$this->table_name}` WHERE board_table = TRIM('{$board_table}'); ");
			$row['publish_table'] = CFX_PUBLISH_TABLE_PREFIX.$board_table;

			return $row;
		}

		return;
	}

	// 게시판 정보를 얻는다.
	public function exist_board($board_table)
	{
		if (!empty(trim($board_table)))
		{
			$row = $this->db->sql_fetch(" SELECT COUNT(DISTINCT `board_table`) AS `cnt` FROM `{$this->table_name}` WHERE board_table = TRIM('{$board_table}'); ");

			if ($row['cnt'])
				return TRUE;
		}

		return FALSE;
	}

	// 게시판 테이블 행을 얻는다.
	public function get_publish($publish_table, $publish_idx)
	{
		if (isset($publish_idx) && $publish_idx)
		{
			return $this->db->sql_fetch(" SELECT * FROM `{$publish_table}` WHERE publish_idx = '{$publish_idx}'; ");
		} 

		return; 
	}

	// 검색 구문을 얻는다.
	function get_sql_search($scategory, $sfield, $stext, $sdisplay='Y', $sop='and')
	{
		$str = '';
		if ($scategory) {
			$str = " publish_category = '{$scategory}' ";  
		}

		if ($sdisplay)
		{
			if ($str)
				$str .= " and ";

			$str .= " publish_display = '{$sdisplay}' ";
		}

		$stext = trim(stripslashes(strip_tags($stext)));

		if (empty($stext))
		{
			if ($scategory || $sdisplay)
				return $str;
			else
				return '';
		}

		if ($str)
			$str .= " and ";

		// 쿼리의 속도를 높이기 위하여 ( ) 는 최소화 한다.
		$op1 = "";

		// 검색어를 구분자로 나눈다. 여기서는 공백
		$s = array();
		$s = explode(' ', $stext);

		// 검색필드를 구분자로 나눈다. 여기서는 +
		$tmp = array();
		$tmp = explode(',', trim($sfield));
		$field = explode('+', $tmp[0]);
		$not_comment = '';
	
		if (!empty($tmp[1]))
			$not_comment = $tmp[1];

		$str .= '(';

		for ($i=0; $i<count($s); $i++) {
			// 검색어
			$search_str = trim($s[$i]);
			if (empty($search_str)) continue;

			$str .= $op1;
			$str .= '(';

			$op2 = '';
			for ($k=0; $k<count($field); $k++) { // 필드의 수만큼 다중 필드 검색 가능 (필드1+필드2...)
				// SQL Injection 방지
				// 필드값에 a-z A-Z 0-9 _ , | 이외의 값이 있다면 검색필드를 subject 로 설정한다.
				$field[$k] = preg_match("/^[\w\,\|]+$/", $field[$k]) ? $field[$k] : 'subject';

				$str .= $op2;

				switch ($field[$k]) {
					case "member_id" :
					case "member_name" :
						$str .= " publish_{$field[$k]} = '$s[$i]' ";
						break;
					// 번호는 해당 검색어에 -1 을 곱함
					case "num" :
						$str .= "publish_{$field[$k]} = ".((-1)*$s[$i]);
						break;
					case "ip" :
					case "password" :
						$str .= "1=0"; // 항상 거짓
						break;
					// LIKE 보다 INSTR 속도가 빠름
					default :
						if (preg_match("/[a-zA-Z]/", $search_str))
							$str .= "INSTR(LOWER(publish_{$field[$k]}), LOWER('$search_str'))";
						else
							$str .= "INSTR(publish_{$field[$k]}, '$search_str')";
						break;
				}
				$op2 = ' or ';
			}
			$str .= ')';
			$op1 = " {$sop} ";
		}
		$str .= ' ) ';

		if ($not_comment)
			$str .= " and publish_is_comment = '0' ";

		return $str;
	}

	// 게시판의 공지사항을 , 로 구분하여 업데이트 한다.
	public function board_notice($board_notice, $publish_idx, $insert=FALSE)
	{
		$notice_array = explode(",", trim($board_notice));

		if ($insert && in_array($publish_idx, $notice_array))
			return $board_notice;

		$notice_array = array_merge(array($publish_idx), $notice_array);
		$notice_array = array_unique($notice_array);

		foreach ($notice_array as $key=>$value) {
			if (empty(trim($value)))
				unset($notice_array[$key]);
		}

		if (empty($insert)) {
			foreach ($notice_array as $key=>$value) {
				if ((int)$value == (int)$publish_idx)
					unset($notice_array[$key]);
			}
		}

		return implode(",", $notice_array);
	}

	// 에디터 이미지 얻기
	public function get_editor_image($contents, $view=TRUE)
	{
	    if (empty(trim($contents)))
	        return FALSE;

	    // $contents 중 img 태그 추출
	    if ($view)
	        $pattern = "/<img([^>]*)>/iS";
	    else
	        $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
	    preg_match_all($pattern, $contents, $matchs);

	    return $matchs;
	}

	// 게시판 썸네일 삭제
	public function delete_board_thumbnail($board_table, $file_path, $file)
	{
	    if(!$$board_table || !$file)
	        return;

	    $fn = preg_replace("/\.[^\.]+$/i", "", basename($file));
	    $files = glob($file_path.DIRECTORY_SEPARATOR.$board_table.'/thumb-'.$fn.'*');
	    if (is_array($files)) {
	        foreach ($files as $filename)
	            unlink($filename);
	    }
	}

	// 에디터 썸네일 삭제
	public function delete_editor_thumbnail($contents)
	{
	    if (empty(trim($contents)))
	        return;

	    // $contents 중 img 태그 추출
	    $matchs = $this->get_editor_image($contents);

	    if (empty(trim($matchs)))
	        return;

	    for ($i=0; $i<count($matchs[1]); $i++) {
	        // 이미지 path 구함
	        $imgurl = @parse_url($matchs[1][$i]);
	        $srcfile = $_SERVER['DOCUMENT_ROOT'].$imgurl['path'];

	        $filename = preg_replace("/\.[^\.]+$/i", "", basename($srcfile));
	        $filepath = dirname($srcfile);
	        $files = glob($filepath.'/thumb-'.$filename.'*');
	        if (is_array($files)) {
	            foreach($files as $filename)
	                unlink($filename);
	        }
	    }
	}

	// 게시물 정보($publish_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_list($publish_row, $board_view, $qstr, $lists_href, $view_href, $download_href, $mode='list', $is_mobile = FALSE)
	{
		$list = array();
		
		// 게시물 아이디
		$list['publish_idx'] = $publish_row['publish_idx'];

		$board_notice = array_map('trim', explode(',', $board_view['board_notice']));
		$list['is_notice'] = in_array($publish_row['publish_idx'], $board_notice);

		// 카테고리명
		$list['category'] = $publish_row['publish_category'];

		// 제목
		$list['subject'] = $this->common->conv_subject($publish_row['publish_subject'], $board_view['board_subject_len'], '…');

		if ($mode == 'list')
		{
			$html = 0;
			$list['summary'] = $this->common->conv_content($publish_row['publish_summary'], $html);
		}
		else if ($mode == 'view')
		{
			$html = 2;
			$list['summary'] = $this->common->conv_content($publish_row['publish_summary'], $html);
			$list['content'] = $this->common->conv_content($publish_row['publish_content'], $html);
		}


	    if ($publish_row['publish_comment'])
	        $list['comment_cnt'] = "<span class=\"comment_cnt\">".$publish_row['publish_comment']."</span>";
	    else
		    $list['comment_cnt'] = '';

	    // 당일인 경우 시간으로 표시함
	    $list['datetime'] = substr($publish_row['publish_datetime'],0,10);
	    $list['datetime2'] = $publish_row['publish_datetime'];
	    if ($list['datetime'] == CFX_TIME_YMD)
	        $list['datetime2'] = substr($list['datetime2'],11,5);
	    else
	        $list['datetime2'] = substr($list['datetime2'],5,5);

	    $list['last'] = substr($publish_row['publish_last'],0,10);
	    $list['last2'] = $publish_row['publish_last'];
	    if ($list['last'] == CFX_TIME_YMD)
	        $list['last2'] = substr($list['last2'],11,5);
	    else
	        $list['last2'] = substr($list['last2'],5,5);

	    $list['member_id'] = get_text($publish_row['publish_member_id']);
	    $list['member_name'] = get_text($publish_row['publish_member_name']);
	    $list['member_email'] = get_text($publish_row['publish_member_email']);

	    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['publish_idx'].$qstr;
	    $list['comment_href'] = $list['view_href'];

	    $list['is_new'] = FALSE; 
	    if ($publish_row['publish_datetime'] >= date("Y-m-d H:i:s", CFX_SERVER_TIME - (24 * $board_view['board_newday'] * 3600)) )
	        $list['is_new'] = TRUE;

	    // 링크
	    $list['link'] = $this->common->url_auto_link($this->common->auto_http(get_text($publish_row["publish_link"])));

	    // 비디오
	    $list['video'] = $this->common->auto_youtube_embed_url(get_text($publish_row["publish_video"]));

	    // 태그
	    $list['tags'] = $this->common->conv_tags($lists_href, get_text($publish_row['publish_tags']));

	    // 썸네일 파일
	    if ($publish_row['publish_thumb'])
	    {
	        $list['thumb'] = $this->get_thumb($publish_row['publish_idx'], $board_view, $download_href, $is_mobile);
	    } else {
	        $list['thumb']['count'] = 0;
	    }

		// 파일
		if ($publish_row['publish_file'])
		{
			$list['file'] = $this->get_file($publish_row['publish_idx'], $board_view, $download_href, $is_mobile);
		} else {
	        $list['file']['count'] = 0;			
		}

		// 조회수
		$list['hit'] = $publish_row['publish_hit'];

	    // 발행 여부
	    $list['display'] = ($publish_row['publish_display'] == 'Y') ? '발행' : '미발행';

		unset($publish_row);

	    return $list;
	}

	// 게시물 정보($publish_row)를 출력하기 위하여 $view로 가공된 정보를 복사 및 가공
	public function get_view($publish_row, $board_view, $qstr, $lists_href, $view_href, $download_href, $is_mobile = FALSE)
	{
		return $this->get_list($publish_row, $board_view, $qstr, $lists_href, $view_href, $download_href, 'view', $is_mobile);
	}

	// 게시글에 첨부된 파일을 얻는다. (배열로 반환)
	public function get_file($publish_idx, $board_view, $download_href, $is_mobile=FALSE)
	{
		$board_table = $board_view['board_table'];
		$board_image_width = ($is_mobile) ? $board_view['board_m_image_width'] : $board_view['board_image_width'];
		$board_image_height = ($is_mobile) ? $board_view['board_m_image_height'] : $board_view['board_image_height'];

		$file['count'] = 0;
		$sql = " SELECT * FROM `".CFX_BOARD_FILE_TABLE."` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' ORDER BY board_file_no; ";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetch_array($result))
		{
			$no = $row['board_file_no'];
			$file[$no]['href'] = $download_href.DIRECTORY_SEPARATOR.$publish_idx."?no={$no}";
			$file[$no]['download'] = $row['board_file_download'];
			$file[$no]['path'] = CFX_DATA_FILES_URL.DIRECTORY_SEPARATOR.$board_table;
			$file[$no]['filesize'] = $this->common->get_filesize($row['board_file_size']);
			$file[$no]['datetime'] = $row['board_file_datetime'];
			$file[$no]['source'] = addslashes($row['board_file_source']);
			$file[$no]['row_content'] = $row['board_file_content'];
			$file[$no]['content'] = get_text($row['board_file_content']);
			$file[$no]['view'] = $this->view_file_link($board_table, $board_image_width, $board_image_height, $row['board_file_name'], $row['board_file_width'], $row['board_file_height'], $file[$no]['content']);
			$file[$no]['filename'] = $row['board_file_name'];
			$file[$no]['image_width'] = $row['board_file_width'] ? $row['board_file_width'] : 640;
			$file[$no]['image_height'] = $row['board_file_height'] ? $row['board_file_height'] : 480;
			$file[$no]['image_type'] = $row['board_file_type'];
			$file['count']++;
		}

	    return $file;
	}

	// 게시글에 첨부된 썸네일을 얻는다. (배열로 반환)
	public function get_thumb($publish_idx, $board_view, $download_href, $is_mobile=FALSE)
	{
		$board_table = $board_view['board_table'];
		$board_thumb_width = ($is_mobile) ? $board_view['board_m_thumb_width'] : $board_view['board_thumb_width'];
		$board_thumb_height = ($is_mobile) ? $board_view['board_m_thumb_height'] : $board_view['board_thumb_height'];

		$thumb['count'] = 0;
		$sql = " SELECT * FROM `".CFX_BOARD_THUMB_TABLE."` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' ORDER BY board_thumb_no; ";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetch_array($result))
		{
			$no = $row['board_thumb_no'];
			$thumb[$no]['href'] = $download_href.DIRECTORY_SEPARATOR.$publish_idx."?no={$no}";
			$thumb[$no]['download'] = $row['board_thumb_download'];
			$thumb[$no]['path'] = CFX_DATA_THUMBS_URL.DIRECTORY_SEPARATOR.$board_table;
			$thumb[$no]['filesize'] = $this->common->get_filesize($row['board_thumb_size']);
			$thumb[$no]['datetime'] = $row['board_thumb_datetime'];
			$thumb[$no]['source'] = addslashes($row['board_thumb_source']);
			$thumb[$no]['row_content'] = $row['board_thumb_content'];
			$thumb[$no]['content'] = get_text($row['board_thumb_content']);
			$thumb[$no]['view'] = $this->view_thumb_link($board_table, $board_thumb_width, $board_thumb_height, $row['board_thumb_name'], $row['board_thumb_width'], $row['board_thumb_height'], $thumb[$no]['source']);
			$thumb[$no]['filename'] = $row['board_thumb_name'];
			$thumb[$no]['image_width'] = $row['board_thumb_width'] ? $row['board_thumb_width'] : 640;
			$thumb[$no]['image_height'] = $row['board_thumb_height'] ? $row['board_thumb_height'] : 480;
			$thumb[$no]['image_type'] = $row['board_thumb_type'];
			$thumb['count']++;
		}

	    return $thumb;
	}

	// 파일을 보이게 하는 링크 (이미지, 플래쉬, 동영상)
	public function view_file_link($board_table, $board_image_width, $board_image_height, $file, $url=CFX_DATA_FILES_URL, $width, $height, $content='')
	{
	    if (empty(trim($file))) return;

	    // 파일의 폭이 게시판설정의 이미지폭 보다 크다면 게시판설정 폭으로 맞추고 비율에 따라 높이를 계산
	    if ($width > $board_image_width && $board_image_height)
	    {
	        $rate = $board_image_width / $width;
	        $width = $board_image_width;
	        $height = (int)($height * $rate);
	    }

	    // 폭이 있는 경우 폭과 높이의 속성을 주고, 없으면 자동 계산되도록 코드를 만들지 않는다.
	    if (!empty(trim($width)))
	        $attr = ' width="'.$width.'" height="'.$height.'" ';
	    else
	        $attr = '';

	    if (preg_match("/\.({$this->config->view['image_extension']})$/i", $file))
	    {
	        $img = '<img src="'.$url.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.urlencode($file).'" alt="'.$content.'" '.$attr.'>';

	        return $img;
	    }
	}

	// 썸네일을 보이게 하는 링크 (이미지)
	public function view_thumb_link($board_table, $board_image_width, $board_image_height, $thumb, $width, $height, $content='')
	{
		return $this->view_file_link($board_table, $board_image_width, $board_image_height, $thumb, CFX_DATA_THUMBS_URL, $width, $height, $content);
	}

	// 게시판의 다음글 번호를 얻는다.
	public function get_next_num($table)
	{
		// 가장 큰 번호를 얻어
		$sql = " select max(publish_num) as max_publish_num from {$table} ";
		$row = $this->db->sql_fetch($sql);
		// 가장 큰 번호에 1을 더해서 넘겨줌
		return (int)($row['max_publish_num'] + 1);
	}

	// 게시판의 다음 디스플레이 순서를 얻는다.
	public function get_next_display_order($table)
	{
		// 가장 큰 번호를 얻어
		$sql = " select max(publish_display_order) as max_publish_display_order from {$table} ";
		$row = $this->db->sql_fetch($sql);
		// 가장 큰 번호에 1을 더해서 넘겨줌
		return (int)($row['max_publish_display_order'] + 1);
	}

	public function get_uniqid()
	{
		$this->db->sql_query(" LOCK TABLE `".CFX_UNIQID_TABLE."` WRITE ");

		while (1)
		{
			// 년월일시분초에 100분의 1초 두자리를 추가함 (1/100 초 앞에 자리가 모자르면 0으로 채움)
			$key = date('ymdHis', time()) . str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT);

			$result = $this->db->sql_query(" insert into `".CFX_UNIQID_TABLE."` set uq_id = '$key', uq_ip = '{$_SERVER['REMOTE_ADDR']}', uq_datetime = '".CFX_TIME_YMDHIS."' ", FALSE);

			if ($result) break; // 쿼리가 정상이면 빠진다.

			// insert 하지 못했으면 일정시간 쉰다음 다시 유일키를 만든다.
			usleep(10000); // 100분의 1초를 쉰다
		}

		$this->db->sql_query(" UNLOCK TABLES ");

		return $key;
	}

	public function upload_thumb($board_view, $wmode, $is_admin, $publish_table, $publish_idx, $download_href, $is_mobile=FALSE)
	{
		$board_thumb_table = CFX_BOARD_THUMB_TABLE;
		$board_table = $board_view['board_table'];

		// 썸네일 파일개수 체크
		$thumb_count = 0;
		$upload_thumb_count = count($_FILES['thumb']['name']);

		for ($i=0; $i<$upload_thumb_count; $i++)
		{
			if ($_FILES['thumb']['name'][$i] && is_uploaded_file($_FILES['thumb']['tmp_name'][$i]))
				$thumb_count++;
		}

		if ($wmode == 'u')
		{
			$thumb = $this->get_thumb($publish_idx, $board_view, $download_href, $is_mobile);
			if ($thumb_count && (int)$thumb['count'] > $board_view['board_upload_file_count'])
			{
				alert('기존 파일을 삭제하신 후 첨부파일을 '.number_format($board_view['board_upload_file_count']).'개 이하로 업로드 해주십시오.');
				return;
			}
		}
		else
		{
			if ($thumb_count > $board_view['board_upload_file_count'])
			{
				alert('첨부파일을 '.number_format($board_view['board_upload_file_count']).'개 이하로 업로드 해주십시오.');
				return;
			}
		}

		// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
		@mkdir(CFX_DATA_THUMBS_PATH.DIRECTORY_SEPARATOR.$board_table, CFX_DIR_PERMISSION);
		@chmod(CFX_DATA_THUMBS_PATH.DIRECTORY_SEPARATOR.$board_table, CFX_DIR_PERMISSION);

		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

		// 가변 파일 업로드
		$thumb_upload_msg = '';
		$thumb_upload = array();
		$thumb_content = array();

		for ($i=0; $i<count($_FILES['thumb']['name']); $i++)
		{
			$thumb_upload[$i]['filename'] = '';
			$thumb_upload[$i]['source']   = '';
			$thumb_upload[$i]['filesize'] = 0;
			$thumb_upload[$i]['image']    = array();
			$thumb_upload[$i]['image'][0] = '';
			$thumb_upload[$i]['image'][1] = '';
			$thumb_upload[$i]['image'][2] = '';

			// 삭제에 체크가 되어있다면 파일을 삭제합니다.
			if (isset($_POST['thumb_del'][$i]) && $_POST['thumb_del'][$i])
			{
				$thumb_upload[$i]['file_del'] = TRUE;

				$row = $this->db->sql_fetch(" SELECT board_thumb_name FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_thumb_no = '{$i}' ");

				@unlink(CFX_DATA_THUMBS_PATH.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.$row['board_thumb_name']);
				// 썸네일삭제
				if (preg_match("/\.({$this->config->view['image_extension']})$/i", $row['board_thumb_name']))
					$this->delete_board_thumbnail($board_table, $row['board_thumb_name']);
			}
			else
			{
				$thumb_upload[$i]['file_del'] = FALSE;
			}

			if (isset($_POST['thumb_content'][$i]) && $_POST['thumb_content'][$i])
			{
				$thumb_content[$i] = $_POST['thumb_content'][$i];
				$thumb_content[$i] = substr(trim($_POST['thumb_content'][$i]),0, 65536);
				$thumb_content[$i] = preg_replace("#[\\\]+$#", "", $thumb_content[$i]);
			}
			else
			{
				$thumb_content[$i] = '';
			}

			$tmp_file  = $_FILES['thumb']['tmp_name'][$i];
			$filesize  = $_FILES['thumb']['size'][$i];
			$filename  = $_FILES['thumb']['name'][$i];
			$filename  = $this->common->get_safe_filename($filename);

		    // 서버에 설정된 값보다 큰파일을 업로드 한다면
			if ($filename)
			{
				$upload_max_filesize = ini_get('upload_max_filesize');

				if ($_FILES['thumb']['error'][$i] == 1)
				{
					$thumb_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
					continue;
				}
				else if ($_FILES['thumb']['error'][$i] != 0)
				{
					$thumb_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
					continue;
				}
			}

		    if (is_uploaded_file($tmp_file))
		    {
		        // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
		        if ($is_admin === FALSE && $filesize > $board_view['board_upload_size'])
		        {
		            $thumb_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($board_view['board_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
		            continue;
		        }

				if ($thumb_upload_msg)
				{
					alert($thumb_upload_msg);
					return;
				}

		        //=================================================================\
		        // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
		        // 에러메세지는 출력하지 않는다.
		        //-----------------------------------------------------------------
		        $timg = @getimagesize($tmp_file);
		        // image type
		        if ( preg_match("/\.({$this->config->view['image_extension']})$/i", $filename) ||
		             preg_match("/\.({$this->config->view['flash_extension']})$/i", $filename) )
		        {
		            if ($timg['2'] < 1 || $timg['2'] > 16)
		                continue;
		        }
		        //=================================================================

		        $thumb_upload[$i]['image'] = $timg;

		        // 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
		        if ($wmode == 'u')
		        {
		            // 존재하는 파일이 있다면 삭제합니다.
		            $row = $this->db->sql_fetch(" SELECT board_thumb_name FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_thumb_no = '{$i}' ");
		            @unlink(CFX_DATA_THUMBS_PATH.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.$row['board_thumb_name']);

		            // 이미지파일이면 썸네일삭제
		            if (preg_match("/\.({$this->config->view['image_extension']})$/i", $row['board_thumb_name'])) {
		                $this->delete_board_thumbnail($board_table, $row['board_thumb_name']);
		            }
		        }

		        // 프로그램 원래 파일명
		        $thumb_upload[$i]['source'] = $filename;
		        $thumb_upload[$i]['filesize'] = $filesize;

		        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
		        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

		        shuffle($chars_array);
		        $shuffle = implode('', $chars_array);

		        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
		        $thumb_upload[$i]['filename'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.$this->common->replace_filename($filename);

		        $dest_file = CFX_DATA_THUMBS_PATH.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.$thumb_upload[$i]['filename'];

		        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
		        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['thumb']['error'][$i]);

		        // 올라간 파일의 퍼미션을 변경합니다.
		        chmod($dest_file, CFX_FILE_PERMISSION);
		    }    
		}

		// 나중에 테이블에 저장하는 이유는 $publish_idx 값을 저장해야 하기 때문입니다.
		for ($i=0; $i<count($thumb_upload); $i++)
		{
		    if (!get_magic_quotes_gpc())
		    {
		        $thumb_upload[$i]['source'] = addslashes($thumb_upload[$i]['source']);
		    }

		    $row = $this->db->sql_fetch(" SELECT count(*) AS cnt FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_thumb_no = '{$i}' ");

		    if ($row['cnt'])
		    {
		        // 삭제에 체크가 있거나 파일이 있다면 업데이트를 합니다.
		        // 그렇지 않다면 내용만 업데이트 합니다.
		        if ($thumb_upload[$i]['file_del'] || $thumb_upload[$i]['filename'])
		        {
		            $sql = " UPDATE `{$board_thumb_table}`
		                        SET board_thumb_source = '{$thumb_upload[$i]['source']}',
		                            board_thumb_name = '{$thumb_upload[$i]['filename']}',
		                            board_thumb_content = '{$thumb_content[$i]}',
		                            board_thumb_size = '{$thumb_upload[$i]['filesize']}',
		                            board_thumb_width = '{$thumb_upload[$i]['image']['0']}',
		                            board_thumb_height = '{$thumb_upload[$i]['image']['1']}',
		                            board_thumb_type = '{$thumb_upload[$i]['image']['2']}',
		                            board_thumb_datetime = '".CFX_TIME_YMDHIS."'
		                      WHERE board_table = '{$board_table}'
		                            and publish_idx = '{$publish_idx}'
		                            and board_thumb_no = '{$i}' ";
		            $this->db->sql_query($sql);
		        }
		        else
		        {
		            $sql = " UPDATE `{$board_thumb_table}`
		                        SET board_thumb_content = '{$thumb_content[$i]}'
		                        WHERE board_table = '{$board_table}'
		                            AND publish_idx = '{$publish_idx}'
		                            AND board_thumb_no = '{$i}' ";
		            $this->db->sql_query($sql);
		        }
		    }
		    else
		    {
		        $sql = " INSERT INTO `{$board_thumb_table}`
		                    SET board_table = '{$board_table}',
		                         publish_idx = '{$publish_idx}',
		                         board_thumb_no = '{$i}',
		                         board_thumb_source = '{$thumb_upload[$i]['source']}',
		                         board_thumb_name = '{$thumb_upload[$i]['filename']}',
		                         board_thumb_content = '{$thumb_content[$i]}',
		                         board_thumb_download = 0,
		                         board_thumb_size = '{$thumb_upload[$i]['filesize']}',
		                         board_thumb_width = '{$thumb_upload[$i]['image']['0']}',
		                         board_thumb_height = '{$thumb_upload[$i]['image']['1']}',
		                         board_thumb_type = '{$thumb_upload[$i]['image']['2']}',
		                         board_thumb_datetime = '".CFX_TIME_YMDHIS."' ";
		        $this->db->sql_query($sql);
		    }
		}

		// 업로드된 파일 내용에서 가장 큰 번호를 얻어 거꾸로 확인해 가면서
		// 파일 정보가 없다면 테이블의 내용을 삭제합니다.
		$row = $this->db->sql_fetch(" SELECT max(board_thumb_no) AS max_thumb_no FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' ");

		for ($i=(int)$row['max_thumb_no']; $i>=0; $i--)
		{
		    $row2 = $this->db->sql_fetch(" SELECT board_thumb_name FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_thumb_no = '{$i}' ");

		    // 정보가 있다면 빠집니다.
		    if ($row2['board_thumb_name']) break;

		    // 그렇지 않다면 정보를 삭제합니다.
		    $this->db->sql_query(" DELETE FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_thumb_no = '{$i}' ");
		}

		// 파일의 개수를 게시물에 업데이트 한다.
		$row = $this->db->sql_fetch(" SELECT count(*) AS cnt FROM `{$board_thumb_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' ");

		$this->db->sql_query(" UPDATE {$publish_table} SET publish_thumb = '{$row['cnt']}' WHERE publish_idx = '{$publish_idx}' ");

		return;
	}

	public function upload_file($board_view, $wmode, $is_admin, $publish_table, $publish_idx, $download_href, $is_mobile=FALSE)
	{
		$board_file_table = CFX_BOARD_FILE_TABLE;
		$board_table = $board_view['board_table'];

		// 파일개수 체크
		$file_count = 0;
		$upload_file_count = count($_FILES['file']['name']);

		for ($i=0; $i<$upload_file_count; $i++)
		{
		    if ($_FILES['file']['name'][$i] && is_uploaded_file($_FILES['file']['tmp_name'][$i]))
		        $file_count++;
		}

		if ($wmode == 'u')
		{
			$file = $this->get_file($publish_idx, $board_view, $download_href, $is_mobile);
			if ($file_count && (int)$file['count'] > $board_view['board_upload_file_count'])
			{
				alert('기존 파일을 삭제하신 후 첨부파일을 '.number_format($board_view['board_upload_file_count']).'개 이하로 업로드 해주십시오.');
				return;
			}
		}
		else
		{
			if ($file_count > $board_view['board_upload_file_count'])
			{
				alert('첨부파일을 '.number_format($board_view['board_upload_file_count']).'개 이하로 업로드 해주십시오.');
				return;
			}
		}

		// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
		@mkdir(CFX_DATA_FILES_PATH.DIRECTORY_SEPARATOR.$board_table, CFX_DIR_PERMISSION);
		@chmod(CFX_DATA_FILES_PATH.DIRECTORY_SEPARATOR.$board_table, CFX_DIR_PERMISSION);

		$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

		// 가변 파일 업로드
		$file_upload_msg = '';
		$file_upload = array();
		$file_content = array();

		for ($i=0; $i<count($_FILES['file']['name']); $i++)
		{
		    $file_upload[$i]['filename'] = '';
		    $file_upload[$i]['source']   = '';
		    $file_upload[$i]['filesize'] = 0;
		    $file_upload[$i]['image']    = array();
		    $file_upload[$i]['image'][0] = '';
		    $file_upload[$i]['image'][1] = '';
		    $file_upload[$i]['image'][2] = '';

		    // 삭제에 체크가 되어있다면 파일을 삭제합니다.
		    if (isset($_POST['file_del'][$i]) && $_POST['file_del'][$i])
		    {
				$file_upload[$i]['file_del'] = TRUE;

				$row = $this->db->sql_fetch(" SELECT board_file_name FROM `{$board_file_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_file_no = '{$i}' ");
				@unlink(CFX_DATA_FILES_PATH.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.$row['board_file_name']);

				// 썸네일삭제
				if (preg_match("/\.({$this->config->view['image_extension']})$/i", $row['board_file_name']))
					$this->delete_board_thumbnail($board_table, $row['board_file_name']);
		    }
		    else
		    {
		        $file_upload[$i]['file_del'] = FALSE;
		    }

			if (isset($_POST['file_content'][$i]) && $_POST['file_content'][$i])
			{
				$file_content[$i] = $_POST['file_content'][$i];
				$file_content[$i] = substr(trim($_POST['file_content'][$i]),0, 65536);
				$file_content[$i] = preg_replace("#[\\\]+$#", "", $file_content[$i]);
			}
			else
			{
				$file_content[$i] = '';
			}

		    $tmp_file  = $_FILES['file']['tmp_name'][$i];
		    $filesize  = $_FILES['file']['size'][$i];
		    $filename  = $_FILES['file']['name'][$i];
		    $filename  = $this->common->get_safe_filename($filename);

		    // 서버에 설정된 값보다 큰파일을 업로드 한다면
		    if ($filename)
		    {
				$upload_max_filesize = ini_get('upload_max_filesize');

		        if ($_FILES['file']['error'][$i] == 1)
		        {
		            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
		            continue;
		        }
		        else if ($_FILES['file']['error'][$i] != 0)
		        {
		            $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
		            continue;
		        }
		    }

		    if (is_uploaded_file($tmp_file))
		    {
		        // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
		        if ($is_admin === FALSE && $filesize > $board_view['board_upload_size']) {
		            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($board_view['board_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
		            continue;
		        }

				if ($file_upload_msg) {
					alert($file_upload_msgf);
					return;
				}

		        //=================================================================\
		        // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
		        // 에러메세지는 출력하지 않는다.
		        //-----------------------------------------------------------------
		        $timg = @getimagesize($tmp_file);
		        // image type
		        if ( preg_match("/\.({$this->config->view['image_extension']})$/i", $filename) ||
		             preg_match("/\.({$this->config->view['flash_extension']})$/i", $filename) )
		        {
		            if ($timg['2'] < 1 || $timg['2'] > 16)
		                continue;
		        }
		        //=================================================================

		        $file_upload[$i]['image'] = $timg;

		        // 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
		        if ($wmode == 'u') {
		            // 존재하는 파일이 있다면 삭제합니다.
		            $row = $this->db->sql_fetch(" SELECT board_file_name FROM `{$board_file_table}` WHERE board_table = '$board_table' AND publish_idx = '$publish_idx' AND board_file_no = '$i' ");
		            @unlink(CFX_DATA_FILES_PATH.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.$row['board_file_name']);

		            // 이미지파일이면 썸네일삭제
		            if (preg_match("/\.({$this->config->view['image_extension']})$/i", $row['board_file_name']))
		                $this->delete_board_thumbnail($board_table, $row['board_file_name']);
		        }

		        // 프로그램 원래 파일명
		        $file_upload[$i]['source'] = $filename;
		        $file_upload[$i]['filesize'] = $filesize;

		        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
		        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

		        shuffle($chars_array);
		        $shuffle = implode('', $chars_array);

		        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다.
		        $file_upload[$i]['filename'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.$this->common->replace_filename($filename);

		        $dest_file = CFX_DATA_FILES_PATH.DIRECTORY_SEPARATOR.$board_table.DIRECTORY_SEPARATOR.$file_upload[$i]['filename'];

		        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
		        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['file']['error'][$i]);

		        // 올라간 파일의 퍼미션을 변경합니다.
		        chmod($dest_file, CFX_FILE_PERMISSION);
		    }    
		}

		// 나중에 테이블에 저장하는 이유는 $publish_idx 값을 저장해야 하기 때문입니다.
		for ($i=0; $i<count($file_upload); $i++)
		{
		    if (!get_magic_quotes_gpc()) {
		        $file_upload[$i]['source'] = addslashes($file_upload[$i]['source']);
		    }

		    $row = $this->db->sql_fetch(" SELECT count(*) AS cnt FROM `{$board_file_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_file_no = '{$i}' ");

		    if ($row['cnt'])
		    {
		        // 삭제에 체크가 있거나 파일이 있다면 업데이트를 합니다.
		        // 그렇지 않다면 내용만 업데이트 합니다.
		        if ($file_upload[$i]['file_del'] || $file_upload[$i]['filename'])
		        {
		            $sql = " UPDATE `{$board_file_table}`
		                        SET board_file_source = '{$file_upload[$i]['source']}',
		                            board_file_name = '{$file_upload[$i]['filename']}',
		                            board_file_content = '{$file_content[$i]}',
		                            board_file_size = '{$file_upload[$i]['filesize']}',
		                            board_file_width = '{$file_upload[$i]['image']['0']}',
		                            board_file_height = '{$file_upload[$i]['image']['1']}',
		                            board_file_type = '{$file_upload[$i]['image']['2']}',
		                            board_file_datetime = '".CFX_TIME_YMDHIS."'
		                      WHERE board_table = '{$board_table}'
		                            AND publish_idx = '{$publish_idx}'
		                            AND board_file_no = '{$i}' ";
		            $this->db->sql_query($sql);
		        }
		        else
		        {
		            $sql = " UPDATE `{$board_file_table}`
		                        SET board_file_content = '{$file_content[$i]}'
		                        WHERE board_table = '{$board_table}'
		                            AND publish_idx = '{$publish_idx}'
		                            AND board_file_no = '{$i}' ";
		            $this->db->sql_query($sql);
		        }
		    }
		    else
		    {
				$sql = " INSERT INTO `{$board_file_table}`
							SET board_table = '{$board_table}',
							publish_idx = '{$publish_idx}',
							board_file_no = '{$i}',
							board_file_source = '{$file_upload[$i]['source']}',
							board_file_name = '{$file_upload[$i]['filename']}',
							board_file_content = '{$file_content[$i]}',
							board_file_download = 0,
							board_file_size = '{$file_upload[$i]['filesize']}',
							board_file_width = '{$file_upload[$i]['image']['0']}',
							board_file_height = '{$file_upload[$i]['image']['1']}',
							board_file_type = '{$file_upload[$i]['image']['2']}',
							board_file_datetime = '".CFX_TIME_YMDHIS."' ";
		        $this->db->sql_query($sql);
		    }
		}

		// 업로드된 파일 내용에서 가장 큰 번호를 얻어 거꾸로 확인해 가면서
		// 파일 정보가 없다면 테이블의 내용을 삭제합니다.
		$row = $this->db->sql_fetch(" SELECT max(board_file_no) AS max_file_no FROM `{$board_file_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' ");

		for ($i=(int)$row['max_file_no']; $i>=0; $i--)
		{
		    $row2 = $this->db->sql_fetch(" SELECT board_file_name FROM `{$board_file_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_file_no = '{$i}' ");

		    // 정보가 있다면 빠집니다.
		    if ($row2['board_file_name']) break;

		    // 그렇지 않다면 정보를 삭제합니다.
		    $this->db->sql_query(" DELETE FROM `{$board_file_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' AND board_file_no = '{$i}' ");
		}

		// 파일의 개수를 게시물에 업데이트 한다.
		$row = $this->db->sql_fetch(" SELECT count(*) AS cnt FROM `{$board_file_table}` WHERE board_table = '{$board_table}' AND publish_idx = '{$publish_idx}' ");
		$this->db->sql_query(" UPDATE {$publish_table} SET publish_file = '{$row['cnt']}' WHERE publish_idx = '{$publish_idx}' ");

		return;
	}
}

?>