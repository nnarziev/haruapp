<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Harucard_Model extends CFX_Model
{
	public $code = '';
	public $base_img_path = '';

	public function __construct()
	{
		parent::__construct();

		$this->initialize();

		$this->base_img_path = "/assets/content/harucard/";
	}

	public function initialize()
	{
		$CFX =& get_instance();
	}

	// 회기
	public function get_harucard_view($code, $idx)
	{
		$code = strtolower($code);

		$harucard_row = array();

		if ($code != '') {
			$harucard_row = $this->db->sql_fetch(" SELECT * FROM `cfx_harucard` WHERE hc_activated = '1' AND LOWER(hc_code) = '{$code}'; ");
		} else if ($idx != '') {
			$harucard_row = $this->db->sql_fetch(" SELECT * FROM `cfx_harucard` WHERE hc_activated = '1' AND hc_no = '{$idx}'; ");
		}

		$result = array();
		$result['hc_no'] = intval($harucard_row['hc_no']);
		$result['hc_code'] = $harucard_row['hc_code'];
		$result['hc_type'] = $harucard_row['hc_type'];
		$result['hc_category'] = $harucard_row['hc_category'];
		$result['hc_category_name'] = $harucard_row['hc_category_name'];
		$result['hc_title'] = $harucard_row['hc_title'];
		$result['hc_strip_title'] = strip_tags($harucard_row['hc_title'], '<br>');
		$result['hc_view_title'] = strip_tags($harucard_row['hc_title'], '<br>');
		$result['hc_top_class'] = $harucard_row['hc_top_class'];
		$result['hc_image_class'] = $harucard_row['hc_image_class'];
		$result['hc_image'] = explode('||', $harucard_row['hc_image'] );
		$result['hc_content_class'] = explode('||', $harucard_row['hc_content_class'] );
		$result['hc_content'] = explode('||', $harucard_row['hc_content'] );
		$result['hc_url_class'] = explode('||', $harucard_row['hc_url_class'] );
		$result['hc_url_link'] = explode('||', $harucard_row['hc_url_link'] );
		$result['hc_url_title'] = explode('||', $harucard_row['hc_url_title'] );
		$result['hc_dot_class'] = $harucard_row['hc_dot_class'];
		$result['hc_activated'] = intval($harucard_row['hc_activated']);

		unset($harucard_row);

		return $result;
	}
}

?>