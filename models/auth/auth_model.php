<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

define('CURRENT_PART', 1); // 우울불안

class Auth_Model extends CFX_Model
{
	public $uri;
	public $auth;
	public $captcha;
	public $profile;

	public function __construct()
	{
		parent::__construct();

		$this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->uri = $CFX->uri;
		$this->auth = $CFX->auth;
		$this->captcha = $CFX->captcha;
		$this->profile = $CFX->profile;
	}

	// 로그인 POST
	public function post_login()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'login';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		$url = $this->uri->URL();
		$member_id = $this->common->get_post_data('id', 50);
		$member_pw = $this->common->get_post_data('pw', 50);

		// 특정 아이디 허용 초과시 캡챠 설정함
		if ($this->auth->is_max_login_attempts_exceeded($member_id) === TRUE)
		{
			$this->captcha->set_captcha();
		}

		// 캡챠 체크
		if ($this->captcha->is_captcha() && $this->captcha->check_captcha_key() === FALSE)
		{
			// 보안문자 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'captcha_key', 'result' => '* 보안문자 입력값이 올바르지 않습니다.');
			return $result_data;
		}

		$login_result = $this->auth->login($member_id, $member_pw);
		
		if ($login_result['result'] == FALSE)
		{
			// 캡챠 카운트 증가
			$this->captcha->increase_count();

			// 비밀번호 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'pw', 'result' => nl2br($login_result['error']));
			return $result_data;
		}

		if ( !empty($url) )
		{
			$msg = check_url_host_msg($url);

			// url 체크
			if (!empty($msg))
			{
				// url 오류 -> 리다이렉트
				$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => $msg);
				return $result_data;
			}

			$link = $this->common->get_post_url_link($url);

			// 성공 -> 리다이렉트
			$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => '', 'result' => $link );
			return $result_data;
		}
		else
		{
			// 성공 -> 리다이렉트
			$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => '', 'result' => CFX_REDIRECT_URL);
			return $result_data;
		}
	}

	// 회원가입 POST
	public function post_regist()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$menu = 'regist';

		// 토큰 검사
		if ($this->common->check_csrf_token($menu) === FALSE)
		{
			// 접근방식 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 올바른 접근 방식이 아닙니다.');
			return $result_data;
		}

		// 캡챠 체크
		if ($this->captcha->check_captcha_key(FALSE) === FALSE)
		{
			// 보안문자 오류
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'captcha_key', 'result' => '* 보안문자 입력값이 올바르지 않습니다.');
			return $result_data;
		}

		$url = $this->uri->URL();

		$regist = array();		
		$regist['member_id']  = $this->common->get_post_data('member_id', 50);
		$regist['member_pw']  = $this->common->get_post_data('member_pw', 50);
		$regist['member_re_pw']  = $this->common->get_post_data('member_re_pw', 50);
		$regist['member_name'] = $this->common->get_post_data('member_name', 20);
		$regist['member_email']= $this->common->get_post_data('member_email', 250);
		$regist['member_email'] = $this->common->get_email_address($regist['member_email']);
		$regist['member_hp'] = $this->common->get_post_data('member_hp', 20);

		if (empty($regist['member_id']))
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 아이디를 입력하세요.');
			return $result_data;
		}

		if (empty($regist['member_pw']) || empty($regist['member_re_pw']))
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 비밀번호를 입력하세요.');
			return $result_data;
		}

		if ($regist['member_pw'] != $regist['member_re_pw'])
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 비밀번호가 일치하지 않습니다.');
			return $result_data;
		}

		if (empty($regist['member_name']))
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 이름을 입력하세요.');
			return $result_data;
		}

		if (!empty($regist['member_email']) && $this->common->is_valid_email_address($regist['member_email']) === FALSE)
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 이메일 주소가 형식에 맞지 않습니다.');
			return $result_data;
		}

		if (empty($regist['member_hp']))
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 휴대전화를 입력하세요.');
			return $result_data;
		}
		elseif ($this->common->is_valid_hp_number($regist['member_hp']) === FALSE)
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 휴대전화 번호를 올바르게 입력해 주십시오.');
			return $result_data;
		}
 
		if ($this->profile->is_exist_member_id($regist['member_id']))
		{
			// 중복 아이디 체크
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 이미 등록된 아이디 입니다.');
			return $result_data;
		}

		if (!empty($regist['member_email']) && $this->profile->is_exist_member_email($regist['member_email']))
		{
			// 중복 이메일 체크
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 이미 등록된 이메일주소 입니다.');
			return $result_data;
		}

		if ($this->profile->is_exist_member_hp($regist['member_hp']))
		{
			// 중복 휴대전화 체크
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 이미 등록된 휴대전화 번호 입니다.');
			return $result_data;
		}

		//
		// 프로필 성별
		$regist['profile_gender'] = $this->common->get_post_data('profile_gender', 1);
		$regist['profile_birth'] = $this->common->get_post_data('profile_birth', 10);

		// 설문조사
		$regist['research_part'] = $this->common->get_post_data('research_part', 1);
		$regist['research_name'] = $this->common->get_post_data('research_name', 50);
		$regist['research_level'] = $this->common->get_post_data('research_level', 1);
		$regist['research_date'] = $this->common->get_post_data('research_date', 10);
		$regist['research_recur'] = $this->common->get_post_data('research_recur', 1);
		$regist['research_care'] = $this->common->get_post_array_data('research_care', 50);
		$regist['research_test1'] = $this->common->get_post_data('research_test1', 1);
		$regist['research_test2'] = $this->common->get_post_data('research_test2', 1);
		$regist['research_test3'] = $this->common->get_post_data('research_test3', 1);
		$regist['research_test4'] = $this->common->get_post_data('research_test4', 1);
		$regist['research_test5'] = $this->common->get_post_data('research_test5', 1);

		if (empty($regist['profile_gender']))
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 성별을 입력하세요.');
			return $result_data;
		}

		if (empty($regist['profile_birth']))
		{
			// 입력 에러
			$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => '* 생년월일을 입력하세요.');
			return $result_data;
		}

		// 회원가입을 등록한다.
		$this->profile->profile_regist($regist);

		// 하루카드 기초 테이블을 등록한다.
		$this->harucard_regist($regist, $this->profile->member_no, $this->profile->member_key, $this->profile->member_created_datetime);

		if ( !empty($url) )
		{
			$msg = check_url_host_msg($url);

			// url 체크
			if (!empty($msg))
			{
				// url 오류 -> 리다이렉트
				$result_data = array('menu' => $menu, 'code' => 'error', 'field' => 'common', 'result' => $msg);
				return $result_data;
			}

			$link = $this->common->get_post_url_link($url);

			// 성공 -> 리다이렉트
			$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => '', 'result' => $link );
			return $result_data;
		}
		else
		{
			// 성공 -> 리다이렉트
			$result_data = array('menu' => $menu, 'code' => 'redirect', 'field' => '', 'result' => CFX_LOGIN_URL);
			return $result_data;
		}
	}

	// 중복 아이디 체크
	public function dup_idchk()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$member_id = $this->common->get_post_data('member_id', 50);

		if ( !empty($member_id) )
		{
			// 중복 아이디 체크
			if ($this->profile->is_exist_member_id($member_id))
				return json_encode(FALSE);
			else
				return json_encode(TRUE);
		} else {
			return json_encode(TRUE);
		}
	}

	// 중복 이메일 체크
	public function dup_emailchk()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$member_email = $this->common->get_post_data('member_email', 250);

		if ( !empty($member_email) )
		{
			// 중복 이메일 체크
			if ($this->profile->is_exist_member_email($member_email))
				return json_encode(FALSE);
			else
				return json_encode(TRUE);
		} else {
			return json_encode(TRUE);
		}
	}

	// 중복 휴대전화 체크
	public function dup_hpchk()
	{
		header('Content-Type: application/json; charset=UTF-8', true);

		$member_hp = $this->common->get_post_data('member_hp', 50);

		if ( !empty($member_hp) )
		{
			// 중복 휴대전화 체크
			if ($this->profile->is_exist_member_hp($member_hp))
				return json_encode(FALSE);
			else
				return json_encode(TRUE);
		} else {
			return json_encode(TRUE);
		}
	}

	private function harucard_regist($regist_row, $member_no, $member_key, $created_datetime)
	{
		// 설문조사
		if (isset($regist_row['research_name']))
			$sql_research_name = ", `research_name` = '{$regist_row['research_name']}'";
		else
			$sql_research_name = "";

		if (isset($regist_row['research_level']))
			$sql_research_level = ", `research_level` = '{$regist_row['research_level']}'";
		else
			$sql_research_level = "";

		if (isset($regist_row['research_date']))
			$sql_research_date = ", `research_date` = '{$regist_row['research_date']}'";
		else
			$sql_research_date = "";

		if (isset($regist_row['research_recur']))
			$sql_research_recur = ", `research_recur` = '{$regist_row['research_recur']}'";
		else
			$sql_research_recur = "";

		if (isset($regist_row['research_care']))
			$sql_research_care = ", `research_care` = '{$regist_row['research_care']}'";
		else
			$sql_research_care = "";

		if (isset($regist_row['research_test1']))
			$sql_research_test1 = ", `research_test1` = '{$regist_row['research_test1']}'";
		else
			$sql_research_test1 = "";

		if (isset($regist_row['research_test2']))
			$sql_research_test2 = ", `research_test2` = '{$regist_row['research_test2']}'";
		else
			$sql_research_test2 = "";

		if (isset($regist_row['research_test3']))
			$sql_research_test3 = ", `research_test3` = '{$regist_row['research_test3']}'";
		else
			$sql_research_test3 = "";

		if (isset($regist_row['research_test4']))
			$sql_research_test4 = ", `research_test4` = '{$regist_row['research_test4']}'";
		else
			$sql_research_test4 = "";

		if (isset($regist_row['research_test5']))
			$sql_research_test5 = ", `research_test5` = '{$regist_row['research_test5']}'";
		else
			$sql_research_test5 = "";

		$sql = " INSERT INTO `cfx_research`
					SET `member_id` = '{$regist_row['member_id']}'
						, `member_no` = '{$member_no}'
						, `member_key`= '{$member_key}'
						, `research_check` = 'N'
						{$sql_research_name}
						{$sql_research_level}
						{$sql_research_date}
						{$sql_research_recur}
						{$sql_research_care}
						{$sql_research_test1}
						{$sql_research_test2}
						{$sql_research_test3}
						{$sql_research_test4}
						{$sql_research_test5};
				";

		$this->db->sql_query($sql);

		// 하루카드 셔플
		$harucard = $this->harucard_shuffle();

		// 우울/불안. 수면, 통증을 선택함.
		$current_part = $regist_row['research_part'];

		$sql = " INSERT INTO `cfx_harucard_join`
					SET `member_id` = '{$regist_row['member_id']}'
						, `member_no` = '{$member_no}'
						, `member_key`= '{$member_key}'
						, `hc_part1` = '{$harucard['part1']}'
						, `hc_part2` = '{$harucard['part2']}'
						, `hc_part3` = '{$harucard['part3']}'
						, `hc_current_part` = '{$current_part}'
						, `hc_activated` = '1'
						, `hc_join1_datetime` = NULL
						, `hc_join2_datetime` = NULL
						, `hc_join3_datetime` = NULL
						, `hc_last_datetime` = '{$created_datetime}'
						, `hc_created_datetime` = '{$created_datetime}';";

		$this->db->sql_query($sql);

		$sql = "INSERT INTO `cfx_harucard_rating` (`member_id`, `member_no`, `member_key`, `hc_part_no`, `hc_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);

		$sql = "INSERT INTO `cfx_harucard_read` (`member_id`, `member_no`, `member_key`, `hc_part_no`, `hc_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);	

		$sql = "INSERT INTO `cfx_harucard_bookmark` (`member_id`, `member_no`, `member_key`, `hc_part_no`, `hc_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);	

		$sql = "INSERT INTO `cfx_harucard_point` (`member_id`, `member_no`, `member_key`, `hc_part_no`, `hc_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_harutoday_join`
					SET `member_id` = '{$regist_row['member_id']}'
						, `member_no` = '{$member_no}'
						, `member_key`= '{$member_key}'
						, `ht_current_part` = '{$current_part}'
						, `ht_activated` = '1'
						, `ht_join1_datetime` = NULL
						, `ht_join2_datetime` = NULL
						, `ht_join3_datetime` = NULL
						, `ht_last_datetime` = '{$created_datetime}'
						, `ht_created_datetime` = '{$created_datetime}';";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_harutoday_rating` (`member_id`, `member_no`, `member_key`, `ht_part_no`, `ht_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_harutoday_read` (`member_id`, `member_no`, `member_key`, `ht_part_no`, `ht_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_harutoday_bookmark` (`member_id`, `member_no`, `member_key`, `ht_part_no`, `ht_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_harutoday_point` (`member_id`, `member_no`, `member_key`, `ht_part_no`, `ht_created_datetime`) VALUES
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '1', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '2', '{$created_datetime}'),
				('{$regist_row['member_id']}', '{$member_no}', '{$member_key}', '3', '{$created_datetime}');";

		$this->db->sql_query($sql);


		$sql = " INSERT INTO `cfx_harutoday_userdata` (`member_id`, `member_key`, `member_no`, `ht_part_no`, `ht_pnum`, `ht_created_datetime`) VALUES ";

		for($j = 1; $j <=3; $j++) {
			for ($i = 1; $i <= 48; $i++) {
				$sql .= " ('{$regist_row['member_id']}', '{$member_key}', '{$member_no}', '{$j}', '{$i}', '{$created_datetime}') ";
				if ($j == 3 && $i == 48) {
					$sql .= ";";
				} else {
					$sql .= ",";
				}
			}
		}

		$this->db->sql_query($sql);

		return;
	}

	private function harucard_shuffle()
	{
		$temp = array(
			  1,   2,   3,   4,   5,   6,   7,   8,   9,  10,
			 11,  12,  13,  14,  15,  16,  17,  18,  19,  20,
			 21,  22,  23,  24,  25,  26,  27,  28,  29,  30,
			 31,  32,  33,  34,  35,  36,  37,  38,  39,  40,
			 41,  42,  43,  44,  45,  46,  47,  48,  49,  50,
			 51,  52,  53,  54,  55,  56,  57,  58,  59,  60,
			 61,  62,  63,  64,  65,  66,  67,  68,  69,  70,
			 71,  72,  73,  74,  75,  76,  77,  78,  79,  80,
			 81,  82,  83,  84,  85,  86,  87,  88,  89,  90,
			 91,  92,  93
		);

		$harucard1 = array(
			                94,  95,  96,  97,  98,  99, 100,
			101, 102, 103, 104, 105, 106, 107, 108, 109, 110,
			111, 112
		);

		$harucard2 = array(
			          113, 114, 115, 116, 117, 118, 119, 120,
			121, 122, 123, 124, 125, 126, 127, 128, 129, 130,
			131
		);

		$harucard3 = array(
			     132, 133, 134, 135, 136, 137, 138, 139, 140,
			141, 142, 143, 144, 145, 146, 147, 148, 149, 150
		);

		shuffle($temp);

		$harucard = array();

		for ($i = 0; $i < 87; $i++)
		{
			if ($i < 29) {
				array_push($harucard1, $temp[$i]);
				if ($i == 28) {
					shuffle($harucard1);
				}
			} elseif ($i >= 29 && $i < 58) {
				array_push($harucard2, $temp[$i]);
				if ($i == 57) {
					shuffle($harucard2);
				}
			} elseif ($i >= 58 && $i < 87) {
				array_push($harucard3, $temp[$i]);
				if ($i == 86) {
					shuffle($harucard3);
				}
			}
		}

		$harucard["part1"] = implode($harucard1, ",");
		$harucard["part2"] = implode($harucard2, ",");
		$harucard["part3"] = implode($harucard3, ",");

		return $harucard;
	}
}

?>