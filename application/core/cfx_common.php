<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Common {

	public function __construct() 
	{
		$this->initialize();
	}

	public function initialize()
	{
	}

	/*************************************************************************
	**
	**  세션관련 처리 함수 모음
	**
	*************************************************************************/

	// 세션변수 생성
	public function set_session($session_name, $value)
	{
		if (PHP_VERSION < '5.3.0')
			session_register($session_name);
		// PHP 버전별 차이를 없애기 위한 방법
		$$session_name = $_SESSION[$session_name] = $value;
	}

	// 세션변수값 얻음
	public function get_session($session_name)
	{
		return isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';
	}

	// 세션변수값 삭제
	public function clear_session($session_name)
	{
		unset($_SESSION[$session_name]);
		return;
	}

	// 토큰값이 존재하는지 확인
	public function is_exist_token($token_name)
	{
		if ($this->get_session('ss_'.$token_name.'_token'))
			return TRUE;
		else
			return FALSE;
	}

	// 토큰값을 생성
	public function set_token($token_name)
	{
		$this->set_session('ss_'.$token_name.'_token', TRUE);
	}

	// 토큰값을 삭제
	public function clear_token($token_name)
	{
		$this->set_session('ss_'.$token_name.'_token', FALSE);
	}

	// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
	public function get_csrf_token($token_name)
	{
		$token = md5(uniqid(rand(), TRUE));
		$this->set_session('ss_csrf_'.$token_name.'_token', $token);

		return $token;
	}

	// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
	public function check_csrf_token($token_name, $url = '')
	{
		if (empty($token_name))
			return FALSE;

		$token = $this->get_session('ss_csrf_'.$token_name.'_token');
		$this->set_session('ss_csrf_'.$token_name.'_token', '');

		if (!$token || !isset($_REQUEST['token']) || $token != $_REQUEST['token'])
			return FALSE;
		else
			return TRUE;
	}

	public function get_data($name, $length=255, $strip_tags=TRUE)
	{
		if (!isset($_GET[$name]) || empty($_GET[$name]))
			return '';

		$data = trim($_GET[$name]);
		$data = substr($data,0,$length);
		$data = ($strip_tags) ? trim(strip_tags($data)) : trim($data);
		$data = preg_replace('#[\\\]+$#', '', $data);

		return $data;
	}

	public function get_post_json_data($name, $length=255, $strip_tags=TRUE)
	{
		$post_data = file_get_contents('php://input');

		if (!isset($post_data) || empty($post_data))
			return '';

		$post_data = json_decode($post_data);

		if (!isset($post_data->$name) || empty($post_data->$name) )
			return '';			

		$data = trim($post_data->$name);
		$data = substr($data,0,$length);
		$data = ($strip_tags) ? trim(strip_tags($data)) : trim($data);
		$data = preg_replace('#[\\\]+$#', '', $data);

		return $data;
	}

	public function get_post_data($name, $length=255, $strip_tags=TRUE)
	{
		if (!isset($_POST[$name]) || empty($_POST[$name]))
			return '';

		$data = trim($_POST[$name]);
		$data = substr($data,0,$length);
		$data = ($strip_tags) ? trim(strip_tags($data)) : trim($data);
		$data = preg_replace('#[\\\]+$#', '', $data);

		return $data;
	}

	public function get_post_array_data($name, $length=255, $strip_tags=TRUE)
	{
		$result = array();

		if (!empty($_POST[$name]) && array_filter($_POST[$name]))
		{
			foreach($_POST[$name] as $item)
			{
				$data = trim($item);

				if (isset($data) && !empty($data))
				{
					$data = substr($data,0,$length);
					$data = ($strip_tags) ? trim(strip_tags($data)) : trim($data);
					$data = preg_replace('#[\\\]+$#', '', $data);

					array_push($result, $data);
				}
			}

			return implode(", ", $result);			
		}

		return '';
	}



	public function get_post_url_link($url)
	{
		$link = urldecode($url);

		// 다른 변수들을 넘겨주기 위함
		if (preg_match("/\?/", $link))
			$split= "&amp;";
		else
			$split= "?";

		// $_POST 배열변수에서 아래의 이름을 가지지 않은 것만 넘김
		foreach ($_POST as $key=>$value)
		{
			if ($key != 'id' && $key != 'pw' && $key != 'url' && $key != 'token' && $key != 'submit')
			{
				$link .= "$split$key=$value";
				$split = "&amp;";
			}
		}

		return $link;
	}

	/*************************************************************************
	**
	**  홈페이지 관련 함수 모음
	**
	*************************************************************************/

	// url에 http:// 를 붙인다
	public function auto_http($url)
	{
		if (!trim($url)) return;

		if (!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
			$url = "http://" . $url;

		return $url;
	}

	// 텍스트에서 자동으로 링크를 생성한다.
	public function url_auto_link($str)
	{
		$str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"), $str);
		$str = preg_replace("/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET=\"_blank\">\\2</A>", $str);
		$str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET=\"_blank\">\\2</A>", $str);
		$str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href=\"mailto:\\0\">\\0</a>", $str);
		$str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"), array("&nbsp;", "&lt;", "&gt;", "&#039;"), $str);

		return $str;
	}

	// 유튜브 주소 형식을 암베딩 방식으로 수정한다.
	public function auto_youtube_embed_url($url)
	{
		$shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
		$longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

		$youtube_id = '';

		if (preg_match($longUrlRegex, $url, $matches))
		{
			$youtube_id = $matches[count($matches) - 1];
		}

		if (preg_match($shortUrlRegex, $url, $matches))
		{
			$youtube_id = $matches[count($matches) - 1];
		}

		return ($youtube_id == '') ? '' : 'https://www.youtube.com/embed/'.$youtube_id;
	}

	// 이메일 주소 추출
	public function get_email_address($email)
	{
		preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", trim($email), $matches);

		return $matches[0];
	}

	// 이메일 주소 형식이 맞는지
	public function valid_email_address($email)
	{
		if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", trim($email)))
			return '이메일 주소가 형식에 맞지 않습니다.';
		else
			return '';
	}

	// 휴대전화 형식이 맞는지
	public function valid_hp_number($hp)
	{
		$hp = preg_replace("/[^0-9]/", "", trim($hp));

		if (!$hp) {
			return '휴대전화 번호를 입력해 주십시오.';
		} else {
			if (preg_match("/^01[0-9]{8,9}$/", $hp))
				return '';
			else
				return '휴대전화 번호를 올바르게 입력해 주십시오.';
		}
	}

	// 이메일 주소 형식이 맞는지
	public function is_valid_email_address($email)
	{
		if (!preg_match("/([0-9a-zA-Z_-]+)@([0-9a-zA-Z_-]+)\.([0-9a-zA-Z_-]+)/", trim($email)))
			return FALSE;
		else
			return TRUE;
	}

	// 휴대전화 형식이 맞는지
	public function is_valid_hp_number($hp)
	{
		$hp = preg_replace("/[^0-9]/", "", trim($hp));

		if (empty($hp)) {
			return FALSE;
		} else {
			if (preg_match("/^01[0-9]{8,9}$/", $hp))
				return TRUE;
			else
				return FALSE;
		}
	}

	// 휴대전화 번호의 숫자만 취한 후 중간에 하이픈(-)을 넣는다.
	public function hyphen_hp_number($hp)
	{
		$hp = preg_replace("/[^0-9]/", "", trim($hp));
		return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $hp);
	}

	// 파일명에서 특수문자 제거
	public function get_safe_filename($name)
	{
		$pattern = '/["\'<>=#&!%\\\\(\)\*\+\?]/';
		$name = preg_replace($pattern, '', $name);

		return $name;
	}

	// 파일의 용량을 구한다.
	public function get_filesize($size)
	{
		if ($size >= 1048576)
		{
			$size = number_format($size/1048576, 1) . "M";
		}
		else if ($size >= 1024)
		{
			$size = number_format($size/1024, 1) . "K";
		}
		else
		{
			$size = number_format($size, 0) . "byte";
		}

		return $size;
	}

	// 파일명 치환
	public function replace_filename($name)
	{
		@session_start();
		$ss_id = session_id();

		$path_parts = pathinfo($name);
		$extension = strtolower($path_parts['extension']);
		$fileName = $path_parts['filename'];
		$usec = get_microtime();

		return sha1($ss_id.$_SERVER['REMOTE_ADDR'].$usec).'.'.$extension;
	}

	// 검색결과를 볼드 태그로 변환한다.
	public function search_font($stext, $search_str)
	{
		// 문자앞에 \ 를 붙입니다.
		$src = array('/', '|');
		$dst = array('\/', '\|');

		if (empty(trim($stext))) return $search_str;

		// 검색어 전체를 공란으로 나눈다
		$s = explode(' ', $stext);

		// "/(검색1|검색2)/i" 와 같은 패턴을 만듬
		$pattern = '';
		$bar = '';
		for ($m=0; $m<count($s); $m++) {
			if (empty(trim($s[$m]))) continue;
			$tmp_str = quotemeta($s[$m]);
			$tmp_str = str_replace($src, $dst, $tmp_str);
			$pattern .= $bar . $tmp_str . "(?![^<]*>)";
			$bar = "|";
		}

		// 지정된 검색 폰트의 색상, 배경색상으로 대체
		$replace = "<b class=\"search_word\">\\1</b>";

		return preg_replace("/($pattern)/i", $replace, $search_str);
	}

	// 한 페이지에 보여줄 행, 현재페이지, 총페이지수, URL
	public function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
	{
		
		$url = preg_replace('#page=[0-9]*#', '', $url) . '&amp;page=';
		$url = str_replace("?&amp;", "?", $url);
		$str = '';
		if ($cur_page > 1)
			$str .= '<a href="'.$url.'1'.$add.'" class="ctrl first  btn"">FIRST</a>'.PHP_EOL;

		$start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
		$end_page = $start_page + $write_pages - 1;

		if ($end_page >= $total_page) $end_page = $total_page;

		if ($start_page > 1)
			$str .= '<a href="'.$url.($start_page-1).$add.'" class="ctrl prev btn">PREV</a>'.PHP_EOL;

		if ($total_page > 1)
		{
			for ($k=$start_page;$k<=$end_page;$k++)
			{
				if ($cur_page != $k)
					$str .= '<a href="'.$url.$k.$add.'" class="number num">'.$k.'</a>'.PHP_EOL;
				else
					$str .= '<a class="number selected curr">'.$k.'</a>'.PHP_EOL;
			}
		}

		if ($total_page > $end_page)
			$str .= '<a href="'.$url.($end_page+1).$add.'" class="ctrl next btn">NEXT</a>'.PHP_EOL;

		if ($cur_page < $total_page)
			$str .= '<a href="'.$url.$total_page.$add.'" class="ctrl end btn">LAST</a>'.PHP_EOL;

		if ($str)
			return "<span class=\"paging\">{$str}</span>";
		else
			return "";
	}

	// 제목을 변환
	public function conv_subject($subject, $len, $suffix='')
	{
		return get_text(cut_str($subject, $len, $suffix));
	}

	// 내용을 변환
	public function conv_content($content, $html, $filter=TRUE)
	{
		global $config;

		if ($html)
		{
			$source = array();
			$target = array();

			$source[] = "//";
			$target[] = "";

			if ($html == 2) { // 자동 줄바꿈
				$source[] = "/\n/";
				$target[] = "<br/>";
			}

			// 테이블 태그의 개수를 세어 테이블이 깨지지 않도록 한다.
			$table_begin_count = substr_count(strtolower($content), "<table");
			$table_end_count = substr_count(strtolower($content), "</table");
			for ($i=$table_end_count; $i<$table_begin_count; $i++)
			{
				$content .= "</table>";
			}

			$content = preg_replace($source, $target, $content);

			if ($filter) {
				$content = $this->html_purifier($content);
			}
		}
		else // text 이면
		{
			// & 처리 : &amp; &nbsp; 등의 코드를 정상 출력함
			$content = html_symbol($content);

			// 공백 처리
			$content = preg_replace("/  /", "&nbsp; ", $content);
			$content = str_replace("  ", "&nbsp; ", $content);
			$content = str_replace("\n ", "\n&nbsp;", $content);
			$content = $this->url_auto_link($content);

			$content = get_text($content, 1);

			if ($filter) {
				$content = $this->html_purifier($content);
			}
		}

		return $content;
	}

	// #태그를 분리하여 링크를 생성한다.
	public function conv_tags($url, $str)
	{
		$regex = "/#+([a-zA-Z0-9가-힣_]+)/";
		$str = preg_replace($regex, '<a href="'.$url.'/?sfield=tags&amp;stext=$1" class="tags">$1</a>', urldecode($str));

		return($str);
	}

	// http://htmlpurifier.org/
	// Standards-Compliant HTML Filtering
	// Safe  : HTML Purifier defeats XSS with an audited whitelist
	// Clean : HTML Purifier ensures standards-compliant output
	// Open  : HTML Purifier is open-source and highly customizable
	public function html_purifier($html)
	{
		$f = file(CFX_PLUGINS_HTMLFILTER_PATH.'/safeiframe.txt');
		$domains = array();
		foreach ($f as $domain) {
			// 첫행이 # 이면 주석 처리
			if (!preg_match("/^#/", $domain)) {
				$domain = trim($domain);
				if ($domain)
					array_push($domains, $domain);
			}
		}
		// 내 도메인도 추가
		array_push($domains, $_SERVER['HTTP_HOST'].'/');
		$safeiframe = implode('|', $domains);

		include_once(CFX_PLUGINS_HTMLFILTER_PATH.'/HTMLPurifier.standalone.php');
		$config = HTMLPurifier_Config::createDefault();
		// data/cache 디렉토리에 CSS, HTML, URI 디렉토리 등을 만든다.
		$config->set('Cache.SerializerPath', CFX_DATA_PATH.'/cache');
		$config->set('Core.Encoding', 'utf-8');
		$config->set('HTML.TidyLevel', 'heavy');
		$config->set('HTML.SafeEmbed', FALSE);
		$config->set('HTML.SafeObject', FALSE);
		$config->set('Output.FlashCompat', FALSE);
		$config->set('HTML.SafeIframe', TRUE);
		$config->set('URI.SafeIframeRegexp','%^(https?:)?//('.$safeiframe.')%');
		$config->set('Attr.AllowedFrameTargets', array('_blank'));
		// 인터넷 주소를 자동으로 링크로 바꿔주는 기능 
		$config->set('AutoFormat.Linkify', TRUE); 
		// 이미지 크기 제한 해제 (한국에서 많이 쓰는 웹툰이나 짤방과 호환성 유지를 위해) 
		$config->set('HTML.MaxImgLength', NULL); 
		$config->set('CSS.MaxImgLength', NULL); 

		$purifier = new HTMLPurifier($config);

		return $purifier->purify($html);
	}
}

?>