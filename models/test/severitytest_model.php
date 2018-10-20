<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Severitytest_Model extends CFX_Model
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
	public function severitytest1()
	{
		$member_id = $this->auth->get_session_member_id();

		$result = array();
		$result['stq1_case'] = $this->db->sql_fetch(" SELECT * FROM `cfx_severitytest_question1` WHERE 1=1 LIMIT 0, 1; ");
		$result['sta1_case'] = $this->db->sql_fetch(" SELECT * FROM `cfx_severitytest_answer1` WHERE sta1_member_id = '{$member_id}' ORDER BY sta1_datetime DESC LIMIT 0, 1; ");

		return $result;
	}

	// 하루 (Haru) 시작하기 모듈검사 처리
	public function post_severitytest1()
	{
		$member_id = $this->auth->get_session_member_id();

		$sta1_id = intval($this->common->get_post_data('sta1_id', 50));

		$sta1_case1 = intval($this->common->get_post_data('sta1_case1', 50));
		$sta1_case2 = intval($this->common->get_post_data('sta1_case2', 50));
		$sta1_case3 = intval($this->common->get_post_data('sta1_case3', 50));
		$sta1_case4 = intval($this->common->get_post_data('sta1_case4', 50));
		$sta1_case5 = intval($this->common->get_post_data('sta1_case5', 50));
		$sta1_case6 = intval($this->common->get_post_data('sta1_case6', 50));
		$sta1_case7 = intval($this->common->get_post_data('sta1_case7', 50));
		$sta1_case8 = intval($this->common->get_post_data('sta1_case8', 50));
		$sta1_case9 = intval($this->common->get_post_data('sta1_case9', 50));

		$sta1_case10 = intval($this->common->get_post_data('sta1_case10', 50));
		$sta1_case11 = intval($this->common->get_post_data('sta1_case11', 50));
		$sta1_case12 = intval($this->common->get_post_data('sta1_case12', 50));
		$sta1_case13 = intval($this->common->get_post_data('sta1_case13', 50));
		$sta1_case14 = intval($this->common->get_post_data('sta1_case14', 50));
		$sta1_case15 = intval($this->common->get_post_data('sta1_case15', 50));
		$sta1_case16 = intval($this->common->get_post_data('sta1_case16', 50));

		$sta1_sent = $this->common->get_post_data('sta1_sent', 1);
		$sta1_sent = (!empty($sta1_sent)) ? $sta1_sent : 'N';

		$sta1_point1 = $sta1_case1 + $sta1_case2 + $sta1_case3 + $sta1_case4 + $sta1_case5 + $sta1_case6 + $sta1_case7 + $sta1_case8 + $sta1_case9;
		$sta1_point2 = $sta1_case10 + $sta1_case11 + $sta1_case12 + $sta1_case13 + $sta1_case14 + $sta1_case15 + $sta1_case16;

		$datetime = CFX_TIME_YMDHIS;

		if ($sta1_id == 0)
		{
			$sql = " INSERT INTO `cfx_severitytest_answer1`
						SET `sta1_case1` = '{$sta1_case1}',
							`sta1_case2` = '{$sta1_case2}',
							`sta1_case3` = '{$sta1_case3}',
							`sta1_case4` = '{$sta1_case4}',
							`sta1_case5` = '{$sta1_case5}',
							`sta1_case6` = '{$sta1_case6}',
							`sta1_case7` = '{$sta1_case7}',
							`sta1_case8` = '{$sta1_case8}',
							`sta1_case9` = '{$sta1_case9}',
							`sta1_case10` = '{$sta1_case10}',
							`sta1_case11` = '{$sta1_case11}',
							`sta1_case12` = '{$sta1_case12}',
							`sta1_case13` = '{$sta1_case13}',
							`sta1_case14` = '{$sta1_case14}',
							`sta1_case15` = '{$sta1_case15}',
							`sta1_case16` = '{$sta1_case16}',
							`sta1_point1` = '{$sta1_point1}',
							`sta1_point2` = '{$sta1_point2}',
							`sta1_sent` = '{$sta1_sent}',
							`sta1_member_id` = '{$member_id}',
							`sta1_datetime` = '{$datetime}';
					";

			$this->db->sql_query($sql);
		} else {
			$sql = " UPDATE `cfx_severitytest_answer1`
						SET `sta1_case1` = '{$sta1_case1}',
							`sta1_case2` = '{$sta1_case2}',
							`sta1_case3` = '{$sta1_case3}',
							`sta1_case4` = '{$sta1_case4}',
							`sta1_case5` = '{$sta1_case5}',
							`sta1_case6` = '{$sta1_case6}',
							`sta1_case7` = '{$sta1_case7}',
							`sta1_case8` = '{$sta1_case8}',
							`sta1_case9` = '{$sta1_case9}',
							`sta1_case10` = '{$sta1_case10}',
							`sta1_case11` = '{$sta1_case11}',
							`sta1_case12` = '{$sta1_case12}',
							`sta1_case13` = '{$sta1_case13}',
							`sta1_case14` = '{$sta1_case14}',
							`sta1_case15` = '{$sta1_case15}',
							`sta1_case16` = '{$sta1_case16}',
							`sta1_point1` = '{$sta1_point1}',
							`sta1_point2` = '{$sta1_point2}',
							`sta1_sent` = '{$sta1_sent}',
							`sta1_datetime` = '{$datetime}'
					WHERE `sta1_member_id` = '{$member_id}' AND `sta1_id` = '{$sta1_id}';
					";

			$this->db->sql_query($sql);			
		}

		return $sta1_sent;
	}

	// 하루 (Haru) 시작하기 모듈검사
	public function severitytest1_result()
	{
		$member_id = $this->auth->get_session_member_id();

		$result = array();
		$result['sta1_case'] = $this->db->sql_fetch(" SELECT * FROM `cfx_severitytest_answer1` WHERE sta1_member_id = '{$member_id}' AND sta1_sent = 'Y' ORDER BY sta1_datetime DESC LIMIT 0, 1; ");

		return $result;
	}
}

?>