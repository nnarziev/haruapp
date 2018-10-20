<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Profile_lib
{
	public $db;
	public $common;

	public $member_table = CFX_MEMBER_TABLE;
	public $profile_table = CFX_PROFILE_TABLE;

	public $error = '';

	public $view = array();

	// 추가 테이블을 생성하는데 필요한 키값들을 선언함
	public $member_no = 0;
	public $member_key = '';
	public $member_created_datetime = '';

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->db = $CFX->db;
		$this->common = $CFX->common;
	}

	// 회원 아이디 중복체크
	public function is_exist_member_id($member_id)
	{
		return $this->is_exist_member_field('member_id', $member_id);
	}

	// 회원 이메일 중복체크
	public function is_exist_member_email($member_email)
	{
		return $this->is_exist_member_field('member_email', $member_email);
	}

	// 회원 휴대전화 중복체크
	public function is_exist_member_hp($member_hp)
	{
		return $this->is_exist_member_field('member_hp', $member_hp);
	}

	// 회원테이블 데이터 중복체크
	private function is_exist_member_field($field_name, $field_data)
	{
		$field_data = trim($field_data);

		if (empty($field_data))
			return FALSE;

		$sql = " SELECT count(*) AS cnt FROM `{$this->member_table}`
					WHERE {$field_name} = '{$field_data}'; ";

		$row = $this->db->sql_fetch($sql);

		if ($row['cnt'])
			return TRUE;
		else
			return FALSE;
	}

	public function get_profile_view_by_no($member_no, $fields='*')
	{
		if ( empty($member_no) || !is_numeric(trim($member_no)) )
			return;

		$profile_row = array();

		$profile_row = $this->db->sql_fetch(" SELECT {$fields} FROM `{$this->profile_table}` WHERE member_no = '{$member_no}'; ");

		$view = array();

		// 게시물 아이디
		$view['key'] = get_text($profile_row['member_key']);
		$view['id'] = get_text($profile_row['member_id']);
		$view['no'] = (int)$profile_row['member_no'];

		$gender_source = array ('M', 'F');
		$gender_target = array ('남자', '여자');
		$view['gender'] = str_replace($gender_source, $gender_target, $profile_row['profile_gender']);

		$view['birth'] = get_text($profile_row['profile_birth']);
		$view['tel'] =  get_text($profile_row['profile_tel']);
		$view['zipcode'] =  get_text($profile_row['profile_zipcode']);
		$view['address1'] =  get_text($profile_row['profile_address1']);
		$view['address2'] =  get_text($profile_row['profile_address2']);
		$view['address3'] =  get_text($profile_row['profile_address3']);

		unset($profile_row);

		return $view;
	}

	// 회원가입을 등록한다.
	public function profile_regist($regist_row)
	{
		$member_pw = $this->db->get_encrypt_string($regist_row['member_pw']);
		$this->member_created_datetime = CFX_TIME_YMDHIS;
		$this->member_key = uniqid('member_');
		// member_5a05e3f3d68d8

		$sql = " INSERT INTO `{$this->member_table}`
					SET `member_id` = '{$regist_row['member_id']}',
						`member_pw` = '{$member_pw}',
						`member_key`= '{$this->member_key}',						
						`member_email` = '{$regist_row['member_email']}',
						`member_hp` = '{$regist_row['member_hp']}',
						`member_name` = '{$regist_row['member_name']}',
						`member_level` = '3',
						`member_ip` = '{$_SERVER['REMOTE_ADDR']}',
						`member_activated` = '1',
						`member_banned` = '0',
						`member_login_ip` = '{$_SERVER['REMOTE_ADDR']}',
						`member_login_datetime` = '{$this->member_created_datetime}',
						`member_created_datetime` = '{$this->member_created_datetime}';
				";

		$this->db->sql_query($sql);

		// 회원 프로필 테이블에도 등록
		$this->member_no = $this->db->sql_insert_id();

		if (isset($regist_row['profile_gender']))
			$sql_gender = ", `profile_gender` = '{$regist_row['profile_gender']}'";
		else
			$sql_gender = "";

		if (isset($regist_row['profile_birth']))
			$sql_birth = ", `profile_birth` = '{$regist_row['profile_birth']}'";
		else
			$sql_birth = "";

		if (isset($regist_row['profile_tel']))
			$sql_tel = ", `profile_tel` = '{$regist_row['profile_tel']}'";
		else
			$sql_tel = "";

		if (isset($regist_row['profile_zipcode']))
			$sql_zipcode = ", `profile_zipcode` = '{$regist_row['profile_zipcode']}'";
		else
			$sql_zipcode = "";

		if (isset($regist_row['profile_address1']))
			$sql_address1 = ", `profile_address1` = '{$regist_row['profile_address1']}'";
		else
			$sql_address1 = "";

		if (isset($regist_row['profile_address2']))
			$sql_address2 = ", `profile_address2` = '{$regist_row['profile_address2']}'";
		else
			$sql_address2 = "";

		if (isset($regist_row['profile_address3']))
			$sql_address3 = ", `profile_address3` = '{$regist_row['profile_address3']}'";
		else
			$sql_address3 = "";

		$sql = " INSERT INTO `{$this->profile_table}`
					SET `member_id` = '{$regist_row['member_id']}'
						, `member_no` = '{$this->member_no }'
						, `member_key`= '{$this->member_key}'
						{$sql_gender}
						{$sql_birth}
						{$sql_tel}
						{$sql_zipcode}
						{$sql_address1}
						{$sql_address2}
						{$sql_address3};
						";

		$this->db->sql_query($sql);

		return;
	}
}

?>