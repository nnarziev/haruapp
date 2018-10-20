<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Board_Uri_lib {

    protected $url = '';
    protected $urlencode = '';

	private $querystring = array();

	public 	$wmode = '';
	public  $token = '';
	public  $no = '';
	public 	$qstr = '';
	public 	$page = '';
	public 	$page_rows = '';
	public 	$scategory = '';
	public 	$sfield = '';
	public 	$stext = '';
	public 	$ssort = '';
	public 	$sorder = '';
	public 	$sop = '';
	public 	$spart = '';
	public 	$sdisplay = '';

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
        // URL ENCODING
        $urlencode = '';
        if (isset($_REQUEST['url'])) {
            $url = strip_tags(trim($_REQUEST['url']));
            $urlencode = urlencode($url);
        } else {
            $url = '';
            $urlencode = urlencode($_SERVER['REQUEST_URI']);
            if (CFX_DOMAIN) {
                $p = @parse_url(CFX_DOMAIN);
                $urlencode = CFX_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path']).DIRECTORY_SEPARATOR, "", $urlencode));
            }
        }

        $this->url = $url;
        $this->urlencode = $urlencode;

		if (isset($_REQUEST['wmode']) && $_REQUEST['wmode']) {
		    $wmode = trim($_REQUEST['wmode']);
		    $wmode = preg_match("/^(u)$/i", $wmode) ? $wmode : '';
		    $this->wmode = substr($wmode, 0, 2);
		} else {
		    $this->wmode = '';
		}

		if (isset($_REQUEST['token']) && $_REQUEST['token']) {
		    $this->token = trim($_REQUEST['token']);
		} else {
		    $this->token = '';
		}

		if (isset($_REQUEST['no']) && $_REQUEST['no']) { // 파일번호 페이지
		    $this->no = (int)$_REQUEST['no'];
		} else {
		    $this->no = 0;
		}

		// QUERY_STRING
		$qstr = '';

		if (isset($_REQUEST['page']) && $_REQUEST['page']) { // 리스트 페이지
		    $page = (int)$_REQUEST['page'];

		    if ($page > 1)
		        $qstr .= '&amp;page=' . urlencode($page);
		} else {
		    $page = 0;
		}

		if ($page <= 0) $page = 1;

		if (isset($_REQUEST['page_rows']) && $_REQUEST['page_rows']) { // 리스트 페이지 사이즈
		    $page_rows = (int)$_REQUEST['page_rows'];

		    if ($page_rows)
		        $qstr .= '&amp;page_rows=' . urlencode($page_rows);
		} else {
		    $page_rows = 0;
		}

		if (isset($_REQUEST['scategory']) && $_REQUEST['scategory'])  { // search category (카테고리)
		    $scategory = clean_xss_tags(trim($_REQUEST['scategory']));
		    if ($scategory) {
		        $scategory = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $scategory);
		        $qstr .= '&amp;scategory=' . urlencode($scategory);
		    }
		} else {
		    $scategory = '';
		}

		if (isset($_REQUEST['sfield']) && $_REQUEST['sfield'])  {

		    $sfield = trim($_REQUEST['sfield']);
		    $sfield = preg_match("/^(subject|content|tags|subject content)$/i", $sfield ) ? $sfield  : '';

		    if ($sfield)
		        $qstr .= '&amp;sfield=' . urlencode($sfield); // search field (검색 필드)
		} else {
		    $sfield = '';
		}

		if (isset($_REQUEST['stext']) && $_REQUEST['stext'])  { // search text (검색어)
		    $stext = get_search_string(trim($_REQUEST['stext']));
		    if ($stext)
		        $qstr .= '&amp;stext=' . urlencode(cut_str($stext, 20, ''));
		} else {
		    $stext = '';
		}

		$stext = get_text(stripslashes($stext));

		if (isset($_REQUEST['ssort']) && $_REQUEST['ssort'])  {
		    $ssort = trim($_REQUEST['ssort']);
		    $ssort = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $ssort);
		    if ($ssort)
		        $qstr .= '&amp;ssort=' . urlencode($ssort); // search sort (검색 정렬 필드)
		} else {
		    $ssort = '';
		}

		if (isset($_REQUEST['sorder']) && $_REQUEST['sorder'])  { // search order (검색 오름, 내림차순)
		    $sorder = trim($_REQUEST['sorder']);
		    $sorder = preg_match("/^(asc|desc)$/i", $sorder) ? $sorder : '';
		    if ($sorder)
		        $qstr .= '&amp;sorder=' . urlencode($sorder);
		} else {
		    $sorder = 'desc';
		}

		if (isset($_REQUEST['sop']) && $_REQUEST['sop'])  { // search operator (검색 or, and 오퍼레이터)
		    $sop = trim($_REQUEST['sop']);
		    $sop = preg_match("/^(or|and)$/i", $sop) ? $sop : '';
		    if ($sop)
		        $qstr .= '&amp;sop=' . urlencode($sop);
		} else {
		    $sop = '';
		}

		$sop = strtolower($sop);
		if ($sop != 'and' && $sop != 'or')
		    $sop = 'and';

		if (isset($_REQUEST['spart']) && $_REQUEST['spart'])  { // search part (검색 파트[구간])
		    $spart = (int)$spart;
		    if ($spart)
		        $qstr .= '&amp;spart=' . urlencode($spart);
		} else {
		    $spart = '';
		}

		if (isset($_REQUEST['sdisplay']) && $_REQUEST['sdisplay'])  { // search display (노출)
		    $sdisplay = clean_xss_tags(trim($_REQUEST['sdisplay']));
		    if ($sdisplay) {
		        $sdisplay = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $sdisplay);
		        $qstr .= '&amp;sdisplay=' . urlencode($sdisplay);
		    }
		} else {
		    $sdisplay = '';
		}

		if ($qstr != '') {
		    $qstr = '?'.$qstr;
		    $qstr = str_replace("?&amp;", "?", $qstr);
		}

		$this->qstr = $qstr;
		$this->page = $page;
		$this->page_rows = $page_rows;
		$this->scategory = $scategory;
		$this->sfield = $sfield;
		$this->stext = $stext;
		$this->ssort = $ssort;
		$this->sorder = $sorder;
		$this->sop = $sop;
		$this->spart = $spart;
		$this->sdisplay = $sdisplay;
    }

	public function URL()
	{
		return $this->url;
	}

	public function URLEncode()
	{
		return $this->urlencode;
	}

	// 검색 필드 셀렉트박스 옵션을 얻는다.
	public function select_search_field_option($sfield='')
	{
		$select_search_option = '';
		$search_fields = explode('|', 'subject|content|tags'); // 구분자가 | 로 되어 있음

		for ($i=0; $i<count($search_fields); $i++) {
		    $current_field = trim($search_fields[$i]);
		    if (empty($current_field)) continue;

		    $select_search_option.= '<option value="'.$current_field.'"';

		    if ($current_field == $sfield) { // 현재 선택된 검색필드라면
		        $select_search_option.= ' selected';
		    }
		    
		    if ($current_field == 'subject')
		        $field_name = '제목';
		    else if ($current_field == 'content')
		        $field_name = '내용';
		    else if ($current_field == 'tags')
		        $field_name = '태그';

		    $select_search_option.= '>'.$field_name.'</option>'.PHP_EOL;
		}

		return $select_search_option;
	}

	// 카테고리 검색용 셀렉트박스 옵션을 얻는다.
	public function select_category_option($scategory='', $board_category_list, $category_href = '')
	{
		$select_category_option = '';

		if (!empty($category_href))
		{
			$select_category_option.= '<option value="'.$category_href.'"';

			if (empty(trim($scategory)))
				$select_category_option.= ' selected';

			$select_category_option.= '>ALL</option>'.PHP_EOL;			
		}

		$categories = explode('|', $board_category_list); // 구분자가 | 로 되어 있음

		for ($i=0; $i<count($categories); $i++)
		{
			$current_category = trim($categories[$i]);
			if (empty($current_category)) continue;

			if (empty($category_href))
				$select_category_option.= '<option value="'.$categories[$i].'"';	
			else
				$select_category_option.= '<option value="'.$category_href."?scategory=".urlencode($current_category).'"';				

			if ($current_category == $scategory) { // 현재 선택된 카테고리라면
				$select_category_option.= ' selected';
			}

			$category_name = strtoupper($current_category);
			$select_category_option.= '>'.$category_name.'</option>'.PHP_EOL;
		}

		return $select_category_option;
	}

	// 발행 셀렉트박스 옵션을 얻는다.
	public function select_display_option($sdisplay, $display_href)
	{
		$select_display_option = '';
		$select_display_option.= '<option value="'.$display_href.'"';

		if (empty(trim($sdisplay)))
			$select_display_option.= ' selected';

		$select_display_option.= '>ALL</option>'.PHP_EOL;

		$displays = explode('|', 'Y|N'); // 구분자가 | 로 되어 있음

		for ($i=0; $i<count($displays); $i++)
		{
			$current_display = trim($displays[$i]);
			if (empty($current_display)) continue;

			$select_display_option.= '<option value="'.$display_href."?sdisplay=".urlencode($current_display).'"';

			if ($current_display == $sdisplay) { // 현재 선택된 디스플레이라면
				$select_display_option.= ' selected';
			}

			$display_name = ($current_display == 'Y') ? '발행' : '미발행';
			$select_display_option.= '>'.$display_name.'</option>'.PHP_EOL;
		}

		return $select_display_option;
	}

	// 페이지 목록노출 셀렉트박스 옵션을 얻는다.
	public function select_page_rows_option($page_rows, $list_page_rows, $list_page_rows_array, $page_rows_href)
	{
		$select_page_rows_option = '';

		$page_rows_list = explode('|', $list_page_rows_array); // 구분자가 | 로 되어 있음

		for ($i=0; $i<count($page_rows_list); $i++)
		{
			$current_page_row = trim($page_rows_list[$i]);
			if (empty($current_page_row)) continue;

			if ($current_page_row == $list_page_rows)
				$select_page_rows_option.= '<option value="'.$page_rows_href.'"';
			else
				$select_page_rows_option.= '<option value="'.$page_rows_href."?page_rows=".urlencode($current_page_row).'"';

			if ($current_page_row == $page_rows) { // 현재 선택된 페이지 목록이라면
				$select_page_rows_option.= ' selected';
			}

			$page_rows_name = strtoupper($current_page_row);
			$select_page_rows_option.= '>'.$page_rows_name.'</option>'.PHP_EOL;
		}

		return $select_page_rows_option;
	}
}

?>