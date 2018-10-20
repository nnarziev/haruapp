<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

// MySQL DB 정보
define('CFX_MYSQL_HOST', 'localhost');
define('CFX_MYSQL_USER', 'root');
define('CFX_MYSQL_PASSWORD', 'haru-nsl');
define('CFX_MYSQL_DB', 'haruapp');
define('CFX_MYSQL_SET_MODE', FALSE);

// 세션정보 Redis 사용여부
define('CFX_USE_SESSION_REDIS', FALSE);
// Redis DB 정보
define('CFX_REDIS_DBPATH', 'tcp://127.0.0.1:6379?auth=****');
?>
