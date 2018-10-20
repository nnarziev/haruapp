<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

/*************************************************************************
**
**  일반 함수 모음
**
*************************************************************************/

// 마이크로 타임을 얻어 계산 형식으로 만듦
function get_microtime() 
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

// 메타태그를 이용한 URL 이동
// header("location:URL") 을 대체
function goto_url($url)
{
    $url = str_replace("&amp;", "&", $url);

    if (!headers_sent())
        header('Location: '.$url);
    else {
        echo '<script>';
        echo 'location.replace("'.$url.'");';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
    }
    exit;
}

// 경고메세지를 경고창으로
function alert($msg='', $url='', $error=TRUE, $post=FALSE)
{
    if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

    $url = clean_xss_tags($url);

    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) 
        $referer = $_SERVER['HTTP_REFERER']; 
    else 
        $referer = '';

    if (!$url) $url = clean_xss_tags($referer);

    $url = preg_replace("/[\<\>\'\"\\\'\\\"\(\)]/", "", $url);

    // url 체크
    check_url_host($url);

    $msg = strip_tags($msg);
    $url = str_replace('&amp;', '&', $url);

    echo '<script>';
    echo '   alert("'.$msg.'");';
    if ($url) {
        echo '  document.location.replace("'.$url.'");';
    } else {
        echo '  history.back();';
    }
    echo '</script>';

    echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다.

    exit();
}

// 베이스 도메인 찾기
function get_base_domain($url) { 
    $matches = array(); 
    preg_match('/[^\.]+\.([^\.]{4}|[^\.]{3}|(co|or|pe|ac)\.[^\.]{2}|[^\.]{2})$/i', $url, $matches); 
    return $matches[0]; 
}

// 동일한 host url 인지
function check_url_host($url, $msg='', $return_url=CFX_URL)
{
    if (!$msg)
        $msg = 'url에 타 도메인을 지정할 수 없습니다.';

    $p = @parse_url($url);
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);

    if (stripos($url, 'http:') !== FALSE || stripos($url, 'https:') !== FALSE) {
        if(!isset($p['scheme']) || !$p['scheme'] || !isset($p['host']) || !$p['host'])
        {
            alert('url 정보가 올바르지 않습니다.', $return_url);
            exit();
        }
    }

    if ((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host'])) {
        // 1차 도메인이 같을 경우 리다이렉트 허용
        if (get_base_domain($p['host']) != get_base_domain($host)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("url에 타 도메인을 지정할 수 없습니다.");'.PHP_EOL;
            echo 'document.location.href = "'.$return_url.'";'.PHP_EOL;
            echo '</script>'.PHP_EOL;
            echo '<noscript>'.PHP_EOL;
            echo '<p>'.$msg.'</p>'.PHP_EOL;
            echo '<p><a href="'.$return_url.'">돌아가기</a></p>'.PHP_EOL;
            echo '</noscript>'.PHP_EOL;
            exit;
        }
    }
}

// 동일한 host url 인지 메세지를 반환한다.
function check_url_host_msg($url)
{
    $p = @parse_url($url);
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);

    if (stripos($url, 'http:') !== FALSE || stripos($url, 'https:') !== FALSE) {
        if (!isset($p['scheme']) || !$p['scheme'] || !isset($p['host']) || !$p['host'])
        {
            return 'url 정보가 올바르지 않습니다.';
        }
    }

    if ((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host'])) {
        // 1차 도메인이 같을 경우 리다이렉트 허용
        if (get_base_domain($p['host']) != get_base_domain($host)) {
            return 'url에 타 도메인을 지정할 수 없습니다.';
        }
    }

    return '';
}

// $dir 을 포함하여 https 또는 http 주소를 반환한다.
function https_url($dir, $https=true)
{
    if ($https) {
        if (CFX_HTTPS_DOMAIN) {
            $url = CFX_HTTPS_DOMAIN.DIRECTORY_SEPARATOR.$dir;
        } else {
            $url = CFX_URL.DIRECTORY_SEPARATOR.$dir;
        }
    } else {
        if (CFX_DOMAIN) {
            $url = CFX_DOMAIN.DIRECTORY_SEPARATOR.$dir;
        } else {
            $url = CFX_URL.DIRECTORY_SEPARATOR.$dir;
        }
    }

    return $url;
}

// HTML 마지막 처리
function html_end()
{
    $buffer = ob_get_contents();
    ob_end_clean();

    return $buffer;
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
    global $cfx;

    setcookie(md5($cookie_name), base64_encode($value), CFX_SERVER_TIME + $expire, '/', CFX_COOKIE_DOMAIN);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
    $cookie = md5($cookie_name);
    if (array_key_exists($cookie, $_COOKIE))
        return base64_decode($_COOKIE[$cookie]);
    else
        return "";
}

// 클라이언트 IP address 얻음
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/*************************************************************************
**
**  폼 입력값 처리 함수 모음
**
*************************************************************************/

function form_error($name)
{
    $error = isset($_SESSION['auth_message']) ? $_SESSION['auth_message'] : '';

    if ( !empty($error) )
    {
        if ($error['field'] == $name) {
            unset($_SESSION['auth_message']);
            return $error['result'];
        }
    }

    return;
}

// XSS 관련 태그 제거
function clean_xss_tags($str)
{
    $str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);

    return $str;
}

function cut_str($str, $len, $suffix="…")
{
    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    $str_len = count($arr_str);

    if ($str_len >= $len) {
        $slice_str = array_slice($arr_str, 0, $len);
        $str = join("", $slice_str);

        return $str . ($str_len > $len ? $suffix : '');
    } else {
        $str = join("", $arr_str);
        return $str;
    }
}

// TEXT 형식으로 변환
function get_text($str, $html=0, $restore=FALSE)
{
    $source[] = "<";
    $target[] = "&lt;";
    $source[] = ">";
    $target[] = "&gt;";
    $source[] = "\"";
    $target[] = "&#034;";
    $source[] = "\'";
    $target[] = "&#039;";

    if($restore)
        $str = str_replace($target, $source, $str);

// TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if ($html == 0) {
        $str = html_symbol($str);
    }

    if ($html) {
        $source[] = "\n";
        $target[] = "<br/>";
    }

    return str_replace($source, $target, $str);
}

// 검색어 특수문자 제거
function get_search_string($stx)
{
    $stx_pattern = array();
    $stx_pattern[] = '#\.*/+#';
    $stx_pattern[] = '#\\\*#';
    $stx_pattern[] = '#\.{2,}#';
    $stx_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';

    $stx_replace = array();
    $stx_replace[] = '';
    $stx_replace[] = '';
    $stx_replace[] = '.';
    $stx_replace[] = '';

    $stx = preg_replace($stx_pattern, $stx_replace, $stx);

    return $stx;
}

// 문장에서 쓰레기 값을 정리한다.
function generate_slug($phrase, $maxLength)
{
    $result = strtolower($phrase);

    $result = preg_replace("/[^a-z0-9가-힣\s-]/", "", $result);
    $result = trim(preg_replace("/[\s-]+/", " ", $result));
    $result = trim(substr($result, 0, $maxLength));
    $result = preg_replace("/\s/", "-", $result);

    return $result;
}

// HTML SYMBOL 변환
// &nbsp; &amp; &middot; 등을 정상으로 출력
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

function is_mobile()
{
    return preg_match('/'.CFX_MOBILE_AGENT.'/i', $_SERVER['HTTP_USER_AGENT']);
}

function result_json($code, $msg='', $url='')
{   
    $result_json = array();
    $result_json['code'] = $code;
    $result_json['msg'] = $msg;
    $result_json['url'] = $url;

    echo json_encode($result_json);
    exit();     
}
?>