<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Common_Uri_lib {

    protected $url = '';
    protected $urlencode = '';    

	private $querystring = array();

	public 	$wmode = '';
	public  $token = '';
	public 	$qstr = '';
	public 	$page = '';
	public 	$page_rows = '';
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
}

?>