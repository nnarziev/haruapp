<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Loader {

	public $router;

	private $_cfx_classes = array();
	private $_cfx_models = array();

	public function __construct()
	{
		$CFX =& get_instance();

		$this->router = $CFX->router;

		// $this->_load_router();
		$this->_cfx_classes =& is_loaded();
	}

	public function library($library, $object_name='', $param = NULL)
	{
		if (empty($library))
		{
			return $this;
		}

		$object_name = strtolower($object_name);

		$this->_load_class($library, CFX_LIBRARIES_PATH, $object_name, $param);

		return $this;
	}

	public function admin_model($model, $path=CFX_ADMIN_MODELS_PATH, $param = NULL)
	{
		return model($model, $path, $param);
	}

	public function model($model, $param = NULL)
	{
		if (empty($model))
		{
			return $this;
		}

		$model_path = $this->router->model_path();
		$object_name = strtolower($model.'_model');

		$this->_load_class($model, $model_path, $object_name, $param);

		return $this;
	}

	public function common_view($view_name, $view_skin='base', $view_data='')
	{
		$this->view($view_name, $view_skin, 'common', $view_data);
	}

	public function view($view_name, $view_skin='base', $class='', $view_data='')
	{
		$CFX =& get_instance();
		
		ob_start();

		$view_path = $this->router->view_path();
		$class_name = (empty($class)) ? strtolower($this->router->class) : strtolower($class);
		$view_skin = strtolower($view_skin);
		$view_name = strtolower($view_name);

		$view_data = $this->_prepare_view_vars($view_data);

		if (file_exists($view_path.DIRECTORY_SEPARATOR.$view_skin.DIRECTORY_SEPARATOR.$class_name.DIRECTORY_SEPARATOR.$view_name.'.php'))
		{
			include($view_path.DIRECTORY_SEPARATOR.$view_skin.DIRECTORY_SEPARATOR.$class_name.DIRECTORY_SEPARATOR.$view_name.'.php');
		}


		if (ob_get_length())
			ob_end_flush();

		return;
	}

	protected function _prepare_view_vars($vars)
	{
		if ( ! is_array($vars))
		{
			$vars = is_object($vars)
				? get_object_vars($vars)
				: array();
		}

		return $vars;
	}


	// 클래스 인스턴스를 로드한다.
	private function _load_class($class, $path=CFX_LIBRARIES_PATH, $object_name='', $param = NULL)
	{
	    $e503 = TRUE;

		if (empty($class))
		{
			return;
		}

		$class = ucfirst($class);

	    $class_name = FALSE;

	    if ($path == CFX_LIBRARIES_PATH)
		{
		    if (file_exists($path.DIRECTORY_SEPARATOR.strtolower($class).'_lib.php'))
		    {
		    	$class_name = $class.'_Lib';
		        if (class_exists($class_name, FALSE) === FALSE)
		        {
		            require_once($path.DIRECTORY_SEPARATOR.strtolower($class).'_lib.php');

		            $e503 = FALSE;
		        }
		    }
		}
		else if ($path == CFX_MODELS_PATH || $path == CFX_ADMIN_MODELS_PATH)
		{			
		    if (file_exists($path.DIRECTORY_SEPARATOR.strtolower($this->router->class).DIRECTORY_SEPARATOR.strtolower($class).'_model.php'))
		    {
		    	$class_name = $class.'_Model';
		        if (class_exists($class_name, FALSE) === FALSE)
		        {
		            require_once($path.DIRECTORY_SEPARATOR.strtolower($this->router->class).DIRECTORY_SEPARATOR.strtolower($class).'_model.php');

		            $e503 = FALSE;
		        }
		    }
		}

	    // Did we find the class?
	    if ($e503 === TRUE)
	    {
			return;
	    }

	    if (empty($object_name))
		    $object_name = $class;

		// Don't overwrite existing properties
		$CFX =& get_instance();
		if (isset($CFX->$object_name))
		{
			if ($CFX->$object_name instanceof $class_name)
				return;
		}

		// Save the class name and object name
		$this->_cfx_classes[$class] = $class;

		// Instantiate the class
		$CFX->$object_name = isset($param)
			? new $class_name($param)
			: new $class_name();	
	}

	public function is_loaded($class)
	{
		echo "is_loaded()".PHP_EOL;
		return array_search($class, $this->_cfx_classes, TRUE);
	}
}

?>