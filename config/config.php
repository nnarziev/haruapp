<?php
/********************
    상수 선언 
********************/

define('CFX_VERSION', '1.1.0');

if (PHP_VERSION >= '5.1.0') {
    date_default_timezone_set("Asia/Seoul");
}

define('CFX_TIMEZONE', 'Asia/Seoul');

// 디버그 모드 여부를 설정합니다.
define('CFX_DEBUG_MODE', FALSE);

/********************
    세션키 상수
********************/
define('CFX_SS_MEMBER_ID', 'ss_member_id');
define('CFX_SS_MEMBER_NAME', 'ss_member_name');
define('CFX_SS_MEMBER_KEY', 'ss_member_key');

/********************
    로그인 상수
********************/
define('CFX_USE_MEMBER_ID', TRUE);
define('CFX_USE_MEMBER_EMAIL', FALSE);
define('CFX_LOGIN_MAX_ATTEMPTS', 5);
define('CFX_LOGIN_ATTEMPTS_EXPIRE', 86400);

/********************
    파일 상수
********************/
define('CFX_COMMONFUNC_FILE',  'cfx_common_func.php');
define('CFX_DBCONFIG_FILE',  'dbconfig.php');
define('CFX_CONTROLLER_FILE',  'cfx_controller.php');
define('CFX_MODEL_FILE',  'cfx_model.php');

/********************
    경로 상수
********************/
define('CFX_PREFIX', 'CFX_');

define('CFX_DOMAIN', '');
define('CFX_REDIRECT_DOMAIN', 'https://www.haruapp.net');
define('CFX_HTTPS_DOMAIN', 'https://www.haruapp.net');

define('CFX_COOKIE_DOMAIN',  '.haruapp.net');
define('CFX_COOKIE_SECURE',  0);

// 베이스 컨트롤러 설정
define('CFX_BASE_CONTROLLER', 'home');

define('CFX_APPLICATION_DIR',  'application');
define('CFX_CONFIG_DIR',  'config');
define('CFX_CORE_DIR',  'core');
define('CFX_FUNCTIONS_DIR',  'functions');
define('CFX_LIBRARIES_DIR',  'libraries');
define('CFX_EXTEND_DIR',  'extend');
define('CFX_MODELS_DIR',  'models');

define('CFX_ASSETS_DIR',  'assets');
define('CFX_CSS_DIR',  'css');
define('CFX_FONTS_DIR',  'fonts');
define('CFX_IMAGES_DIR',  'images');
define('CFX_JS_DIR',  'js');

define('CFX_VIEWS_DIR',  'views');

define('CFX_ADMIN_DIR',  'adm');
define('CFX_MOBILE_DIR',  'mobile');
define('CFX_CONTROLLERS_DIR',  'controllers');

define('CFX_BOARD_DIR',  'board');

define('CFX_DATA_DIR',  'data');
define('CFX_CACHE_DIR',  'cache');
define('CFX_FILES_DIR',  'files');
define('CFX_THUMBS_DIR',  'thumbs');

define('CFX_SESSION_DIR',  'session');

define('CFX_PLUGINS_DIR',  'plugins');

define('CFX_EDITOR_DIR',  'ckeditor');
define('CFX_HTMLFILTER_DIR',  'htmlpurifier');
define('CFX_PHPMAILER_DIR',  'PHPMailer');
define('CFX_PHPCAPTCHA_DIR',  'PHPCaptcha');

// URL은 브라우저상에서의 경로 (도메인으로 부터의)
if (CFX_DOMAIN) {
    define('CFX_URL', CFX_DOMAIN);
} else {
    if (isset($cfx_apppath['url']))
        define('CFX_URL', $cfx_apppath['url']);
    else
        define('CFX_URL', '');
}

// 로그인시 리다이렉트 URL 설정
if (CFX_REDIRECT_DOMAIN) {
    define('CFX_REDIRECT_URL', CFX_REDIRECT_DOMAIN);
} else {
    define('CFX_REDIRECT_URL', CFX_URL);
}

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
if (isset($cfx_apppath['path'])) {
    define('CFX_APPPATH', $cfx_apppath['path']);
} else {
    define('CFX_APPPATH', '');
}

define('CFX_CSS_URL',           CFX_URL.DIRECTORY_SEPARATOR.CFX_ASSETS_DIR.DIRECTORY_SEPARATOR.CFX_CSS_DIR);
define('CFX_FONTS_URL',         CFX_URL.DIRECTORY_SEPARATOR.CFX_ASSETS_DIR.DIRECTORY_SEPARATOR.CFX_FONTS_DIR);
define('CFX_IMAGES_URL',        CFX_URL.DIRECTORY_SEPARATOR.CFX_ASSETS_DIR.DIRECTORY_SEPARATOR.CFX_IMAGES_DIR);
define('CFX_JS_URL',            CFX_URL.DIRECTORY_SEPARATOR.CFX_ASSETS_DIR.DIRECTORY_SEPARATOR.CFX_JS_DIR);

define('CFX_DATA_URL',          CFX_URL.DIRECTORY_SEPARATOR.CFX_DATA_DIR);
define('CFX_DATA_CACHE_URL',   CFX_DATA_URL.DIRECTORY_SEPARATOR.CFX_CACHE_DIR);
define('CFX_DATA_IMAGES_URL',   CFX_DATA_URL.DIRECTORY_SEPARATOR.CFX_IMAGES_DIR);
define('CFX_DATA_FILES_URL',    CFX_DATA_URL.DIRECTORY_SEPARATOR.CFX_FILES_DIR);
define('CFX_DATA_THUMBS_URL',   CFX_DATA_URL.DIRECTORY_SEPARATOR.CFX_THUMBS_DIR);

define('CFX_PLUGINS_EDITOR_URL',CFX_URL.DIRECTORY_SEPARATOR.CFX_PLUGINS_DIR.DIRECTORY_SEPARATOR.CFX_EDITOR_DIR);

// PATH 는 서버상에서의 절대경로
define('CFX_APPLICATION_PATH',  CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_APPLICATION_DIR);
define('CFX_CONFIG_PATH',       CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_CONFIG_DIR);

define('CFX_CORE_PATH',         CFX_APPLICATION_PATH.DIRECTORY_SEPARATOR.CFX_CORE_DIR);
define('CFX_FUNCTIONS_PATH',    CFX_APPLICATION_PATH.DIRECTORY_SEPARATOR.CFX_FUNCTIONS_DIR);
define('CFX_LIBRARIES_PATH',    CFX_APPLICATION_PATH.DIRECTORY_SEPARATOR.CFX_LIBRARIES_DIR);
define('CFX_EXTEND_PATH',       CFX_APPLICATION_PATH.DIRECTORY_SEPARATOR.CFX_EXTEND_DIR);

define('CFX_MODELS_PATH',       CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_MODELS_DIR);
define('CFX_VIEWS_PATH',        CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_VIEWS_DIR);
define('CFX_MOBILE_VIEWS_PATH', CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_MOBILE_DIR.DIRECTORY_SEPARATOR.CFX_VIEWS_DIR);
define('CFX_CONTROLLERS_PATH',  CFX_APPPATH.DIRECTORY_SEPARATOR.'/'.CFX_CONTROLLERS_DIR);

define('CFX_PLUGINS_PHPMAILER_PATH',   CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_PLUGINS_DIR.DIRECTORY_SEPARATOR.CFX_PHPMAILER_DIR);
define('CFX_PLUGINS_HTMLFILTER_PATH',   CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_PLUGINS_DIR.DIRECTORY_SEPARATOR.CFX_HTMLFILTER_DIR);
define('CFX_PLUGINS_PHPCAPTCHA_PATH',   CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_PLUGINS_DIR.DIRECTORY_SEPARATOR.CFX_PHPCAPTCHA_DIR);

define('CFX_DATA_PATH',         CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_DATA_DIR);
define('CFX_DATA_CACHE_PATH',  CFX_DATA_PATH.DIRECTORY_SEPARATOR.CFX_CACHE_DIR);
define('CFX_DATA_IMAGES_PATH',  CFX_DATA_PATH.DIRECTORY_SEPARATOR.CFX_IMAGES_DIR);
define('CFX_DATA_FILES_PATH',   CFX_DATA_PATH.DIRECTORY_SEPARATOR.CFX_FILES_DIR);
define('CFX_DATA_THUMBS_PATH',  CFX_DATA_PATH.DIRECTORY_SEPARATOR.CFX_THUMBS_DIR);

define('CFX_SESSION_PATH',      CFX_DATA_PATH.DIRECTORY_SEPARATOR.CFX_SESSION_DIR);

// 항수 파일
define('CFX_INCLUDE_COMMONFUNC', CFX_FUNCTIONS_PATH.DIRECTORY_SEPARATOR.CFX_COMMONFUNC_FILE);

// DB 컨넥션정보 파일
define('CFX_INCLUDE_DBCONFIG',  CFX_CONFIG_PATH.DIRECTORY_SEPARATOR.CFX_DBCONFIG_FILE);

// 컨트롤러
define('CFX_CORE_CONTROLLER',   CFX_CORE_PATH.DIRECTORY_SEPARATOR.CFX_CONTROLLER_FILE);

// 모델
define('CFX_CORE_MODEL',        CFX_CORE_PATH.DIRECTORY_SEPARATOR.CFX_MODEL_FILE);

// 관리자 설정
define('CFX_ADMIN_URL',         CFX_URL.DIRECTORY_SEPARATOR.CFX_ADMIN_DIR);

// 로그인 URL 설정
define('CFX_LOGIN_URL',         CFX_URL.DIRECTORY_SEPARATOR.'auth'.DIRECTORY_SEPARATOR.'login');

// 관리자 경로 설정
define('CFX_ADMIN_PATH',        CFX_APPPATH.DIRECTORY_SEPARATOR.CFX_ADMIN_DIR);
define('CFX_ADMIN_MODELS_PATH', CFX_ADMIN_PATH.DIRECTORY_SEPARATOR.CFX_MODELS_DIR);
define('CFX_ADMIN_VIEWS_PATH',  CFX_ADMIN_PATH.DIRECTORY_SEPARATOR.CFX_VIEWS_DIR);
define('CFX_ADMIN_MOBILE_VIEWS_PATH',  CFX_ADMIN_PATH.DIRECTORY_SEPARATOR.CFX_MOBILE_DIR.DIRECTORY_SEPARATOR.CFX_VIEWS_DIR);
define('CFX_ADMIN_CONTROLLERS_PATH',     CFX_ADMIN_PATH.DIRECTORY_SEPARATOR.CFX_CONTROLLERS_DIR);

//==============================================================================
// 사용기기 설정
//------------------------------------------------------------------------------

// 모바일 사용여부를 설정합니다.
define('CFX_USE_MOBILE', FALSE);
define('CFX_USE_MOBILE_ADMIN', FALSE);

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('CFX_MOBILE_AGENT',   'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony');

/********************
    시간 상수
********************/
// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
define('CFX_SERVER_TIME',    time());
define('CFX_TIME_YMDHIS',    date('Y-m-d H:i:s', CFX_SERVER_TIME));
define('CFX_TIME_YMD',       substr(CFX_TIME_YMDHIS, 0, 10));
define('CFX_TIME_HIS',       substr(CFX_TIME_YMDHIS, 11, 8));

// 퍼미션
define('CFX_DIR_PERMISSION',  0755); // 디렉토리 생성시 퍼미션
define('CFX_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

/********************
    기타 상수
********************/

// 최고관리자 페이지 접근권한 레벨 설정
define('CFX_SUPER_ADMIN_LEVEL', 9);

// 메니저 페이지 접근권한 레벨 설정
define('CFX_MANAGER_LEVEL', 6);

// 관리자 페이지 접근권한 레벨 설정
define('CFX_ADMIN_PAGE_ACCESS_LEVEL', 6);

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
define('CFX_STRING_ENCRYPT_FUNCTION', 'sql_password');

// SQL 에러를 표시할 것인지 지정
// 에러를 표시하려면 TRUE 로 변경
define('CFX_DISPLAY_SQL_ERROR', TRUE);

// escape string 처리 함수 지정
// addslashes 로 변경 가능
define('CFX_ESCAPE_FUNCTION', 'sql_escape_string');

// 썸네일 jpg Quality 설정
define('CFX_THUMB_JPG_QUALITY', 90);

// 썸네일 png Compress 설정
define('CFX_THUMB_PNG_COMPRESS', 5);

define('CFX_CHARSET', 'utf8');

// MySQLi 사용여부를 설정합니다.
define('CFX_MYSQLI_USE', true);
?>