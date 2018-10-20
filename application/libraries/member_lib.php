<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Member_lib {

	public $db;
	public $config;
	public $common;

	public $member_table = CFX_MEMBER_TABLE;
	public $view = array();

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->db = $CFX->db;
		$this->config = $CFX->config;
		$this->common = $CFX->common;
	}

	// 회원 정보 삭제
	public function delete_member_by_no($member_no)
	{
		if ( empty($member_no) || !is_numeric(trim($member_no)) )
			return;

		$this->db->sql_query(" DELETE FROM `{$this->member_table}` WHERE member_no = '{$member_no}' ");
		return;
	}

	// 회원 정보를 얻는다.
	public function get_member_by_no($member_no, $fields='*')
	{
		if ( empty($member_no) || !is_numeric(trim($member_no)) )
			return;

		return $this->db->sql_fetch(" SELECT {$fields} FROM `{$this->member_table}` WHERE member_no = '{$member_no}'; ");
	}

	// 회원 정보를 얻는다.
	public function get_member($member_id)
	{
		$member_id = strtolower($member_id);
		return $this->db->sql_fetch(" SELECT * FROM `{$this->member_table}` WHERE LOWER(member_id) = '{$member_id}'; ");
	}

	public function get_member_view_by_no($member_no, $qstr='', $view_href='', $mode = 'view')
	{
		if ( empty($member_no) || !is_numeric(trim($member_no)) )
			return;

		return $this->get_member_view($this->get_member_by_no($member_no), $qstr, $view_href, $mode);
	}

	public function get_member_view_by_id($member_id, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_member_view($this->get_member($member_id), $qstr, $view_href, $mode);
	}

	// get_member_list 의 alias
	public function get_member_view($member_row, $qstr='', $view_href='', $mode = 'view')
	{
		return $this->get_member_list($member_row, $qstr, $view_href, $mode);
	}

	// 회원 정보($member_row)를 출력하기 위하여 $list로 가공된 정보를 복사 및 가공
	public function get_member_list($member_row, $qstr='', $view_href='', $mode = 'list')
	{
		$list = array();

		// 게시물 아이디
		$list['no'] = (int)$member_row['member_no'];

		$list['id'] = get_text($member_row['member_id']);
		$list['name'] = get_text($member_row['member_name']);
		$list['email'] = get_text($member_row['member_email']);
		$list['pw'] = get_text($member_row['member_pw']);
		$list['level'] = (int)$member_row['member_level'];
		$list['group'] = (int)$member_row['member_group'];
		$list['group_label'] = (int)$member_row['member_group_label'];

		if ($list['level'] == 1) {
			$list['level_name'] = '일반인';
		} else if ($list['level'] == 3) {
			$list['level_name'] = '참여자';
		} else if ($list['level'] == 6) {
			$list['level_name'] = '담당자';
		} else if ($list['level'] == 9) {
			$list['level_name'] = '관리자';
		}

		// 당일인 경우 시간으로 표시함
		$list['created_datetime'] = substr($member_row['member_created_datetime'],0,10);
		$list['created_datetime2'] = $member_row['member_created_datetime'];
		if ($list['created_datetime'] == CFX_TIME_YMD)
			$list['created_datetime2'] = substr($list['created_datetime2'],11,5);
		else
			$list['created_datetime2'] = substr($list['created_datetime2'],5,5);

		$list['ip'] = get_text($member_row['member_ip']);
		$list['hp'] = get_text($member_row['member_hp']);
		// $list['tel'] = get_text($member_row['member_tel']);
		// $list['gender'] = substr($member_row['member_gender'],0,1);
		// $list['birth'] = substr($member_row['member_birth'],0,10);
		// $list['zipcode'] = get_text($member_row['member_zipcode']);
		// $list['address1'] = get_text($member_row['member_address1']);
		// $list['address2'] = get_text($member_row['member_address2']);
		// $list['address3'] = get_text($member_row['member_address3']);
		$list['login_ip'] = get_text($member_row['member_login_ip']);
		$list['login_datetime'] = substr($member_row['member_login_datetime'],0,10);
		$list['left_datetime'] = $member_row['member_left_date'];
 		$list['status'] = ($member_row['member_left_date'] !== '') ? '탈퇴' : '등록';
 		$list['activated'] = $member_row['member_activated'];

 		if (!empty($view_href))
		    $list['view_href'] = $view_href.DIRECTORY_SEPARATOR.$list['no'].$qstr;

		unset($member_row);

		return $list;
	}

	// 회원 아이디가 존재하는지
	public function exist_member_id($regist_member_id)
	{
	    $regist_member_id = trim($regist_member_id);
	    if ($regist_member_id == '') return '';

		$sql = " SELECT count(*) AS cnt FROM `{$this->member_table}` WHERE member_id = '{$regist_member_id}'; ";
		$row = $this->db->sql_fetch($sql);

		if ($row['cnt'])
			return '이미 등록중인 아이디 입니다.';
		else
			return '';
	}

	// 이메일 중복체크
	public function exist_member_email($regist_member_email, $regist_member_id)
	{
	    $regist_member_email = trim($regist_member_email);
	    if ($regist_member_email == '') return '';

	    $row = $this->db->sql_fetch(" SELECT count(*) AS cnt FROM `{$this->member_table}` WHERE member_email = '{$regist_member_email}' AND member_id <> '{$regist_member_id}'; ");

	    if ($row['cnt'])
	        return '이미 사용중인 이메일 주소입니다.';
	    else
	        return '';
	}

	// 휴대전화 중복 체크
	public function exist_member_hp($regist_member_hp, $regist_member_id)
	{
	    $regist_member_hp = trim($regist_member_hp);
	    if ($regist_member_hp == '') return '';

		$regist_member_hp = $this->common->hyphen_hp_number($regist_member_hp);

		$sql = " SELECT count(*) AS cnt FROM `{$this->member_table}` WHERE member_hp = '{$regist_member_hp}' AND member_id <> '{$regist_member_id}'; ";
		$row = $this->db->sql_fetch($sql);

		if ($row['cnt'])
			return '이미 사용 중인 휴대전화 번호입니다. ('.$regist_member_hp.')';
		else
			return '';
	}

	// 검색 구문을 얻는다.
	public function get_sql_search($sgroup, $sfield, $stext, $sop='and')
	{
		$str = '';

		if ($sgroup) {
			$str = " mb.member_group = '{$sgroup}' ";  
		}

		$stext = trim(stripslashes(strip_tags($stext)));

		if (empty($stext))
		{
			if ($sgroup)
				return $str;
			else
				return '';
		}

		if ($str)
			$str .= " and ";

		// 쿼리의 속도를 높이기 위하여 ( ) 는 최소화 한다.
		$op1 = "";

		// 검색어를 구분자로 나눈다. 여기서는 공백
		$s = array();
		$s = explode(' ', $stext);

		// 검색필드를 구분자로 나눈다. 여기서는 +
		$tmp = array();
		$tmp = explode(',', trim($sfield));
		$field = explode('+', $tmp[0]);

		$str .= '(';

		for ($i=0; $i<count($s); $i++) {
			// 검색어
			$search_str = trim($s[$i]);
			if (empty($search_str)) continue;

			$str .= $op1;
			$str .= '(';

			$op2 = '';
			for ($k=0; $k<count($field); $k++) { // 필드의 수만큼 다중 필드 검색 가능 (필드1+필드2...)
				// SQL Injection 방지
				// 필드값에 a-z A-Z 0-9 _ , | 이외의 값이 있다면 검색필드를 subject 로 설정한다.
				$field[$k] = preg_match("/^[\w\,\|]+$/", $field[$k]) ? $field[$k] : 'id';

				$str .= $op2;

				switch ($field[$k]) {
					case "id" :
						$str .= " mb.member_{$field[$k]} = '$s[$i]' ";
						break;
					case "ip" :
					case "pw" :
						$str .= "1=0"; // 항상 거짓
						break;
					// LIKE 보다 INSTR 속도가 빠름
					default :
						if (preg_match("/[a-zA-Z]/", $search_str))
							$str .= "INSTR(LOWER(mb.member_{$field[$k]}), LOWER('$search_str'))";
						else
							$str .= "INSTR(mb.member_{$field[$k]}, '$search_str')";
						break;
				}
				$op2 = ' or ';
			}
			$str .= ')';
			$op1 = " {$sop} ";
		}
		$str .= ' ) ';

		return $str;
	}
}

?>