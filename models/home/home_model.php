<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Home_Model extends CFX_Model
{
	public $uri;

	public function __construct()
	{
        parent::__construct();

        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->uri = $CFX->uri;
	}

	// 홈
	public function home()
	{
	}

	// 에러
	public function Error404()
	{
	}
}

?>