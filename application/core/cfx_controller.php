<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Controller {

    private static $instance;

    public $routes = array();

    public $router = NULL;
    public $db = NULL;
    public $config = NULL;
    public $common = NULL;
    public $load = NULL;
    public $auth = NULL;

    public $debug_mode = CFX_DEBUG_MODE;

    public function __construct()
    {
        if (ENVIRONMENT == 'development' && !($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'))
            echo '<!--'.get_class($this).'::initialize()-->'.PHP_EOL;

        self::$instance =& $this;

        // 라우터 코어 클래스
        $this->router =& load_class('Router');

        // 데이터베이스 코어 클래스
        $this->db =& load_class('Database');

        // 설정정보 코어 클래스
        $this->config =& load_class('Config');

        // 공통 라이브러리 클래스
        $this->common =& load_class('Common');

        // 라이브러리, 모델, 뷰 관련 로더 
        $this->load =& load_class('Loader');

        // 로그인 인증 클래스
        $this->auth =& load_class('Auth');

        return;
    }

    public function set_title($title)
    {
        $this->title = $title;
    }

    public function title()
    {
        return $this->title;
    }

    protected function load_router()
    {
        $this->routes = $this->router->routes();
    }

    // --------------------------------------------------------------------

    public static function &get_instance()
    {
        return self::$instance;
    }

    public function class_type()
    {
        return "Controller";
    }
}
?>