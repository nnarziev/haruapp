<?php
function get_time()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}
$start = get_time();

include_once('./_common.php');

// 현재 컨트롤러명 구하기
$class = $RTR->class;

// 컨트롤이 존재하지 않으면 기본 컨트롤로 설정함.
if (!file_exists(CFX_ADMIN_CONTROLLERS_PATH.DIRECTORY_SEPARATOR.strtolower($class).DIRECTORY_SEPARATOR.strtolower($class).'_controller.php'))
{
	// 베이스 컨트롤로 라우팅 규치 변경
	$RTR->set_base_routes();
	$class = $RTR->class;
}

$class_name = $class.'_Controller';

if (class_exists($class_name, FALSE) === FALSE)
{
	require_once(CFX_ADMIN_CONTROLLERS_PATH.DIRECTORY_SEPARATOR.strtolower($class).DIRECTORY_SEPARATOR.strtolower($class).'_controller.php');
}

$CFX = new $class_name();

if ($CFX->debug_mode === TRUE)
{
	echo PHP_EOL.'<!--'.PHP_EOL;
	$end = get_time();
	$time = $end - $start;
	echo '처리 시간 : '.$time.'초 걸림'.PHP_EOL;
	echo '메모리 사용량 : '.memory_get_usage().'Byte ('.(memory_get_usage() / 1000).'KB)'.PHP_EOL;
	echo '-->'.PHP_EOL;
}

?>