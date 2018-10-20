<?php

/**
 * CFX
 *
 * @package	CFX
 * @author	Cafein Team
 * @since	Version 1.1.0
 * @filesource
 */

/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/

define('ENVIRONMENT', isset($_SERVER['CFX_ENV']) ? $_SERVER['CFX_ENV'] : 'development');

// 에러 리포팅
switch (ENVIRONMENT)
{
	case 'development':
		error_reporting(-1);
		ini_set('display_errors', 1);
	break;
	case 'production':
		ini_set('display_errors', 0);
		if (version_compare(PHP_VERSION, '5.3', '>='))
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		else
			error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
	break;

	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1); // EXIT_ERROR
}

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================

function cfx_apppath()
{
    $app_path = explode('/adm', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
    $result['path'] =$app_path[0];
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $root = str_replace($document_root, '', $result['path']);
    $port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(str_replace($document_root, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$cfx_apppath = cfx_apppath();

if (file_exists($cfx_apppath['path'].'/config/config.php'))
    require_once($cfx_apppath['path'].'/config/config.php');   // 설정 파일

if (file_exists($cfx_apppath['path'].'/config/schemaconfig.php'))
    require_once($cfx_apppath['path'].'/config/schemaconfig.php'); // 스키마 설정 파일

unset($cfx_apppath);

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
	
    if(defined('CFX_ESCAPE_PATTERN') && defined('CFX_ESCAPE_REPLACE')) {
        $pattern = CFX_ESCAPE_PATTERN;
        $replace = CFX_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    return call_user_func('addslashes', $str);
}

function &is_loaded($class = '')
{
    static $_is_loaded = array();

    if ($class !== '')
    {
        $_is_loaded[strtolower($class)] = $class;
    }

    return $_is_loaded;
}

// 클래스 인스턴스를 로드한다.
function &load_class($class, $param = NULL)
{
    $e503 = TRUE;

    static $_classes = array();

    $class = ucfirst($class);

    // 클래스가 존재하면 반환한다.
    if (isset($_classes[$class]))
    {
        return $_classes[$class];
    }

    $name = FALSE;

    if (file_exists(CFX_CORE_PATH.DIRECTORY_SEPARATOR.strtolower(CFX_PREFIX.$class).'.php'))
    {
        $name = CFX_PREFIX.$class;

        if (class_exists($name, FALSE) === FALSE)
        {
            require_once(CFX_CORE_PATH.DIRECTORY_SEPARATOR.strtolower(CFX_PREFIX.$class).'.php');

            $e503 = FALSE;
        }
    }

    // Did we find the class?
    if ($e503 === TRUE)
    {
        echo 'Unable to locate the specified class: '.$class;
        exit();
    }

    // Keep track of what we just loaded
    is_loaded($class);

    $_classes[$class] = isset($param)
        ? new $name($param)
        : new $name();

    return $_classes[$class];
}

//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if (get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(CFX_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(CFX_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(CFX_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(CFX_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

//==============================================================================
// 데이터베이스
//------------------------------------------------------------------------------

if (file_exists(CFX_INCLUDE_DBCONFIG)) {
    require_once(CFX_INCLUDE_DBCONFIG);   // 데이터베이스 설정
} else {
    echo CFX_INCLUDE_DBCONFIG;
    echo '<p>404 Not Found (Database)<p>';

    // data 디렉토리에 파일 생성 가능한지 검사.
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        if (!(is_readable(CFX_DATA_PATH) && is_writeable(CFX_DATA_PATH) && is_executable(CFX_DATA_PATH)))
        {
?>
        <div>
            <p>
                <?php echo CFX_DATA_DIR ?> 디렉토리의 퍼미션을 707로 변경하여 주십시오.<br /><br />
                chmod 707 <?php echo CFX_DATA_DIR ?> 또는 chmod uo+rwx <?php echo CFX_DATA_DIR ?><br /><br />
                위 명령 실행후 브라우저를 새로고침 하십시오.
            </p>
        </div>
<?php
        }
        else
        {
            // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
            @mkdir(CFX_DATA_PATH.DIRECTORY_SEPARATOR.'session', CFX_DIR_PERMISSION);
            @chmod(CFX_DATA_PATH.DIRECTORY_SEPARATOR.'session', CFX_DIR_PERMISSION);
            @mkdir(CFX_DATA_CACHE_PATH, CFX_DIR_PERMISSION);
            @chmod(CFX_DATA_CACHE_PATH, CFX_DIR_PERMISSION);
            @mkdir(CFX_DATA_IMAGES_PATH, CFX_DIR_PERMISSION);
            @chmod(CFX_DATA_IMAGES_PATH, CFX_DIR_PERMISSION);
            @mkdir(CFX_DATA_FILES_PATH, CFX_DIR_PERMISSION);
            @chmod(CFX_DATA_FILES_PATH, CFX_DIR_PERMISSION);
            @mkdir(CFX_DATA_THUMBS_PATH, CFX_DIR_PERMISSION);
            @chmod(CFX_DATA_THUMBS_PATH, CFX_DIR_PERMISSION);  
        }
    }

    exit();
}

//==============================================================================
// 공통함수
//------------------------------------------------------------------------------

 // 공통 함수 (정리가 필요함)
if (file_exists(CFX_INCLUDE_COMMONFUNC)) {
    require_once(CFX_INCLUDE_COMMONFUNC);
}

//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0);   // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags","");       // 링크에 PHPSESSID가 따라다니는것을 무력화함

if (CFX_USE_SESSION_REDIS)
{
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path', CFX_REDIS_DBPATH);
} else {
    session_save_path(CFX_SESSION_PATH);    
}

if (isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, "/");
ini_set("session.cookie_domain", CFX_COOKIE_DOMAIN);
ini_set("session.cookie_secure", CFX_COOKIE_SECURE);

if ( !session_id() )
    @session_start();

// [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if (isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
{
    session_unset(); // 모든 세션변수를 언레지스터 시켜줌
    session_destroy(); // 세션해제함

    goto_url(CFX_URL);
}

//==============================================================================
// 라우터
//------------------------------------------------------------------------------

// 라우팅 클래스 로드
$RTR =& load_class('Router');

function &get_instance()
{
    return CFX_Controller::get_instance();
}

//==============================================================================
// 컨트롤러
//------------------------------------------------------------------------------

// 코어 컨트롤러 클래스 인클루드
if (file_exists(CFX_CORE_CONTROLLER))
    include_once(CFX_CORE_CONTROLLER);

//==============================================================================
// 모델
//------------------------------------------------------------------------------

// 코어 모델 클래스 인클루드
if (file_exists(CFX_CORE_MODEL))
    include_once(CFX_CORE_MODEL);

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);  // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
?>