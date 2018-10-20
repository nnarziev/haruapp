<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Router {
	protected $routes = array();
	protected $link_href = array();

	public $set_device = TRUE;
	public $is_mobile = FALSE;
	public $class = '';
	public $method = 'index';
	public $target = '';
	public $idx = 0;

	public $model_path = CFX_MODELS_PATH;
	public $view_path = CFX_VIEWS_PATH;

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
		//==============================================================================
		// Mobile 모바일 설정
		// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
		// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
		// CFX_MOBILE_AGENT : config.php 에서 선언
		//------------------------------------------------------------------------------
		if (CFX_USE_MOBILE && $this->set_device == TRUE)
		{
			if (isset($_REQUEST['device']) && $_REQUEST['device']=='pc')
				$this->is_mobile = FALSE;
			elseif (isset($_REQUEST['device']) && $_REQUEST['device']=='mobile')
				$this->is_mobile = TRUE;
			elseif (isset($_SESSION['ss_is_mobile']))
				$this->is_mobile = $_SESSION['ss_is_mobile'];
			elseif (is_mobile())
				$this->is_mobile = TRUE;
		}
		else
		{
			$this->set_device = FALSE;
		}

		$_SESSION['ss_is_mobile'] = $this->is_mobile;
		define('CFX_IS_MOBILE', $this->is_mobile);

		$routing = explode(DIRECTORY_SEPARATOR, $this->get_current_uri());
		
		foreach($routing as $route)
		{
			if(!empty(trim($route)))
				array_push($this->routes, $route);
		}

		// 클래스 설정
		$class = '';
		if (isset($this->routes[0]) && $this->routes[0]) {
			
			$class = preg_replace('/[^a-z0-9_]/i', '', trim($this->routes[0]));
			$class = strtolower(substr($class, 0, 20));
			
			
		}
		
		if (!empty($class) && $class)
		{
			
			$this->class = ucfirst($class);
			
		}
		else
		{
			
			$this->class = ucfirst(CFX_BASE_CONTROLLER);
		}

		return;
	}

	public function routes()
	{
		return $this->routes;
	}

	public function set_base_routes()
	{
		$this->class = ucfirst(CFX_BASE_CONTROLLER);
	
		if ($this->routes[0] != strtolower(CFX_BASE_CONTROLLER))
			array_unshift($this->routes, strtolower(CFX_BASE_CONTROLLER));
	}

	public function model_path()
	{
		return $this->model_path;
	}

	public function view_path()
	{
		return $this->view_path;
	}

	public function set_model_path($model_path)
	{
		if ($model_path != CFX_MODELS_PATH && $model_path != CFX_ADMIN_MODELS_PATH)
			return;

		$this->model_path = $model_path;
	}

	public function set_view_path($view_path)
	{
		if ($view_path != CFX_VIEWS_PATH && $view_path != CFX_ADMIN_VIEWS_PATH)
			return;

		if ($this->is_mobile && $view_path == CFX_VIEWS_PATH)
		{
			$this->view_path = CFX_MOBILE_VIEWS_PATH;
		} 
		elseif ($this->is_mobile && CFX_USE_MOBILE_ADMIN && $view_path == CFX_ADMIN_VIEWS_PATH)
		{
			$this->view_path = CFX_ADMIN_MOBILE_VIEWS_PATH;
		}
		else
		{
			$this->view_path = $view_path;
		}
	}

	// 현재 Uri를 불러온다. 
	private function get_current_uri()
	{
	    $basepath = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_NAME']), 0, -1)).DIRECTORY_SEPARATOR;
	    $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
	    if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
	    $uri = trim($uri, DIRECTORY_SEPARATOR);

	    return $uri;
	}

	public function set_link_href($link_href)
	{
		if (isset($link_href) && $link_href)
		{
			$this->link_href = $link_href;

			return TRUE;
		}

		return FALSE;
	}

	public function get_link_href()
	{
		echo 'get';
		if (isset($this->link_href) && $this->link_href)
			return $this->link_href;

		return;
	}

	public function link_href($link_name, $qstr='')
	{
		if (isset($this->link_href[$link_name]) && $this->link_href[$link_name])
			return $this->link_href[$link_name].$qstr;
			
		return '';
	}

	// 페이지 리다이렉트
	public function redirect($url = '', $method = 'auto', $code = NULL)
	{
		$url = str_replace("&amp;", "&", $url);
		
		// IIS environment likely? Use 'refresh' for better compatibility
		if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
		{
			$method = 'refresh';
		}
		elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
		{
			if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
			{
				$code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
					? 303	// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
					: 307;
			}
			else
			{
				$code = 302;
			}
		}

		switch ($method)
		{
			case 'refresh':
				header('Refresh:0;url='.$url);
				break;
			default:
				header('Location: '.$url, TRUE, $code);
				break;
		}
		exit;
	}

	public function redirect_link_href($link_name, $qstr='')
	{
		if (isset($this->link_href[$link_name]) && $this->link_href[$link_name])
		{
			$this->redirect($this->link_href[$link_name].$qstr);
		}

		return;
	}

	public function check_post_request()
	{
		if ($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			return FALSE;
		}

		return TRUE;
	}
}

?>
