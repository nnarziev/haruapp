<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Moduletest_Model extends CFX_Model
{
	public $db;
	public $auth;

	public function __construct()
	{
		parent::__construct();

		$this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->db = $CFX->db;
		$this->auth = $CFX->auth;
	}

	// 하루 (Haru) 시작하기 모듈검사
	public function moduletest()
	{
		$result = array();
		$member_id = $this->auth->get_session_member_id();

		$result['mtq_case'] = $this->db->sql_fetch(" SELECT * FROM `cfx_moduletest_question` WHERE 1 = 1 LIMIT 0, 1; ");
		$result['mta_case'] = $this->db->sql_fetch(" SELECT * FROM `cfx_moduletest_answer` WHERE mta_member_id = '{$member_id}' ORDER BY mta_datetime DESC LIMIT 0, 1; ");

		return $result;
	}

	// 하루 (Haru) 시작하기 모듈검사 처리
	public function post_moduletest()
	{
		$member_id = $this->auth->get_session_member_id();

		$mta_id = intval($this->common->get_post_data('mta_id', 50));
		$mta_case1 = intval($this->common->get_post_data('mta_case1', 50));
		$mta_case2 = intval($this->common->get_post_data('mta_case2', 50));
		$mta_case3 = intval($this->common->get_post_data('mta_case3', 50));
		$mta_case4 = intval($this->common->get_post_data('mta_case4', 50));
		$mta_case5 = intval($this->common->get_post_data('mta_case5', 50));
		$mta_case6 = intval($this->common->get_post_data('mta_case6', 50));
		$mta_case7 = intval($this->common->get_post_data('mta_case7', 50));
		$mta_case8 = intval($this->common->get_post_data('mta_case8', 50));
		$mta_sent = $this->common->get_post_data('mta_sent', 1);
		$mta_sent = (!empty($mta_sent)) ? $mta_sent : 'N';

		$mta_point1 = $mta_case1 + $mta_case2 + $mta_case3 + $mta_case4;
		$mta_point2 = $mta_case5 + $mta_case6;
		$mta_point3 = $mta_case7 + $mta_case8;

		$datetime = CFX_TIME_YMDHIS;

		if ($mta_id == 0)
		{
			$sql = " INSERT INTO `cfx_moduletest_answer`
						SET `mta_case1` = '{$mta_case1}',
							`mta_case2` = '{$mta_case2}',
							`mta_case3` = '{$mta_case3}',
							`mta_case4` = '{$mta_case4}',
							`mta_case5` = '{$mta_case5}',
							`mta_case6` = '{$mta_case6}',
							`mta_case7` = '{$mta_case7}',
							`mta_case8` = '{$mta_case8}',
							`mta_point1` = '{$mta_point1}',
							`mta_point2` = '{$mta_point2}',
							`mta_point3` = '{$mta_point3}',
							`mta_sent` = '{$mta_sent}',
							`mta_member_id` = '{$member_id}',
							`mta_datetime` = '{$datetime}';
					";

			$this->db->sql_query($sql);
		} else {
			$sql = " UPDATE `cfx_moduletest_answer`
						SET `mta_case1` = '{$mta_case1}',
							`mta_case2` = '{$mta_case2}',
							`mta_case3` = '{$mta_case3}',
							`mta_case4` = '{$mta_case4}',
							`mta_case5` = '{$mta_case5}',
							`mta_case6` = '{$mta_case6}',
							`mta_case7` = '{$mta_case7}',
							`mta_case8` = '{$mta_case8}',
							`mta_point1` = '{$mta_point1}',
							`mta_point2` = '{$mta_point2}',
							`mta_point3` = '{$mta_point3}',
							`mta_sent` = '{$mta_sent}',
							`mta_datetime` = '{$datetime}'
					WHERE `mta_member_id` = '{$member_id}' AND `mta_id` = '{$mta_id}';
					";

			$this->db->sql_query($sql);			
		}

		return $mta_sent;
	}

	// 하루 (Haru) 시작하기 모듈검사
	public function moduletest_result()
	{
		$member_id = $this->auth->get_session_member_id();

		$result = array();
		$result['mta_case'] = $this->db->sql_fetch(" SELECT * FROM `cfx_moduletest_answer` WHERE mta_member_id = '{$member_id}' AND mta_sent = 'Y' ORDER BY mta_datetime DESC LIMIT 0, 1; ");

		return $result;
	}
}

?>