<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Config {

	public $db;

	public $view = array();

	public function __construct()
	{
		$this->initialize();
	}

	public function initialize()
	{
        $CFX =& get_instance();

        $this->db = $CFX->db;

        $row = $CFX->db->sql_fetch(" select * from `".CFX_CONFIG_TABLE."`; ");

		$this->view['title'] = $row['config_title'];
		$this->view['admin'] = $row['config_admin'];
		$this->view['common_skin'] = $row['config_common_skin'];
		$this->view['pages'] = $row['config_pages'];
		$this->view['page_rows'] = $row['config_page_rows'];
		$this->view['page_rows_list'] = $row['config_page_rows_list'];
		$this->view['search_part'] = $row['config_search_part'];
		$this->view['image_extension'] = $row['config_image_extension'];
		$this->view['flash_extension'] = $row['config_flash_extension'];
		$this->view['movie_extension'] = $row['config_movie_extension'];
		$this->view['smtp_secure'] = $row['config_smtp_secure'];
		$this->view['smtp_host'] = $row['config_smtp_host'];
		$this->view['smtp_port'] = $row['config_smtp_port'];
		$this->view['smtp_username'] = $row['config_smtp_username'];
		$this->view['smtp_password'] = $row['config_smtp_password'];
	}
}

?>