<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Member_Uri_lib {

    protected $url = '';
    protected $urlencode = '';    

	private $querystring = array();

	public 	$wmode = '';
	public  $token = '';
	public 	$qstr = '';
	public 	$page = '';
	public 	$page_rows = '';
	public 	$sgroup = '';
	public 	$sfield = '';
	public 	$stext = '';
	public 	$ssort = '';
	public 	$sorder = '';
	public 	$sop = '';
	public 	$spart = '';

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

		if (isset($_REQUEST['sgroup']) && $_REQUEST['sgroup'])  { // search group (그룹)
		    $sgroup = clean_xss_tags(trim($_REQUEST['sgroup']));
		    if ($sgroup) {
		        $sgroup = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $sgroup);
		        $qstr .= '&amp;sgroup=' . urlencode($sgroup);
		    }
		} else {
		    $sgroup = '';
		}

		if (isset($_REQUEST['sfield']) && $_REQUEST['sfield'])  {

		    $sfield = trim($_REQUEST['sfield']);
		    $sfield = preg_match("/^(id|name|email)$/i", $sfield ) ? $sfield  : '';

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

		if ($qstr != '') {
		    $qstr = '?'.$qstr;
		    $qstr = str_replace("?&amp;", "?", $qstr);
		}

		$this->qstr = $qstr;
		$this->page = $page;
		$this->page_rows = $page_rows;
		$this->sgroup = $sgroup;
		$this->sfield = $sfield;
		$this->stext = $stext;
		$this->ssort = $ssort;
		$this->sorder = $sorder;
		$this->sop = $sop;
		$this->spart = $spart;
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
		$search_fields = explode('|', 'id|name|email'); // 구분자가 | 로 되어 있음

		for ($i=0; $i<count($search_fields); $i++) {
		    $current_field = trim($search_fields[$i]);
		    if (empty($current_field)) continue;

		    $select_search_option.= '<option value="'.$current_field.'"';

		    if ($current_field == $sfield) { // 현재 선택된 검색필드라면
		        $select_search_option.= ' selected';
		    }
		    
		    if ($current_field == 'id')
		        $field_name = '아이디';
		    else if ($current_field == 'name')
		        $field_name = '이름';
		    else if ($current_field == 'email')
		        $field_name = '이메일';

		    $select_search_option.= '>'.$field_name.'</option>'.PHP_EOL;
		}

		return $select_search_option;
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

	// 회원레벨 필드 셀렉트박스 옵션을 얻는다.
	public function select_level_field_option($level_name_fields, $level_value_fields, $level='2')
	{
		$select_level_field_option = '';
		$level_name_fields = explode('|', $level_name_fields); // 구분자가 | 로 되어 있음
		$level_value_fields = explode('|', $level_value_fields); // 구분자가 | 로 되어 있음

		for ($i=0; $i<count($level_value_fields); $i++)
		{
			$current_value_field = trim($level_value_fields[$i]);
			if (empty($current_value_field)) continue;

			$select_level_field_option.= '<option value="'.$current_value_field.'"';

			if ($current_value_field == $level) { // 현재 선택된 검색필드라면
				$select_level_field_option.= ' selected';
			}

			$field_name = trim($level_name_fields[$i]);
			$select_level_field_option.= '>'.$field_name.'</option>'.PHP_EOL;
		}

		return $select_level_field_option;
	}
}

?>