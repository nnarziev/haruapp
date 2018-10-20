<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Auth
{
	public $db;
	public $common;

	public $member_table = CFX_MEMBER_TABLE;
	public $login_attempts_table = CFX_LOGIN_ATTEMPTS_TABLE;

	public $view = array();

	public function __construct()
	{
		if (ENVIRONMENT == 'development' && !($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'))
			echo '<!--'.get_class($this).'::initialize()-->'.PHP_EOL;

		$this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->db = $CFX->db;
		$this->common = $CFX->common;

		// 로그인 상태 체크
		$this->login_status_check();
	}

	public function login_status_check()
	{
		$member_id = $this->get_session_member_id();

		// 로그인중이라면
		if (!empty($member_id))
		{
			$this->view = $this->get_auth_view($member_id);

			// 오늘 처음 로그인 이라면
			if ($this->view['login_datetime'] != CFX_TIME_YMD)
			{
				// 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
				// 해당 회원의 접근일시와 IP 를 저장
				$this->update_login_auth($this->view['login_datetime']);
			}
		}

		// 비회원의 경우 회원레벨을 가장 낮게 설정
		if ($this->is_member() === FALSE)
		{
			$this->view['id'] = '';
			$this->view['level'] = 1;
		}
	}

	// 회원아이디 세션값을 반환한다.
	public function get_session_member_id()
	{
		// 로그인중이라면
		if (isset($_SESSION[CFX_SS_MEMBER_ID]) && $_SESSION[CFX_SS_MEMBER_ID])
			return $_SESSION[CFX_SS_MEMBER_ID];
		else
			return '';
	}

	// 회원이름 세션값을 얻어온다.
	public function get_session_member_name()
	{
		return $this->common->get_session(CFX_SS_MEMBER_NAME);
	}

	// 회원 세션값을 설정한다.
	public function set_session_member($member_id, $member_name, $member_datetime)
	{
		// 회원아이디 세션 생성
		$this->common->set_session(CFX_SS_MEMBER_ID, $member_id);
		// 회원이름 세션 생성
		$this->common->set_session(CFX_SS_MEMBER_NAME, $member_name);
		// FLASH XSS 공격에 대응하기 위하여 회원의 고유키를 생성해 놓는다. 관리자에서 검사함.
		$this->common->set_session(CFX_SS_MEMBER_KEY, md5($member_datetime.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']));
	}

	// 로그인 정보를 반환한다.
	public function get_auth_view($login)
	{
		if (CFX_USE_MEMBER_ID AND CFX_USE_MEMBER_EMAIL)
			$get_login_func = 'get_member_by_login';
		else if (CFX_USE_MEMBER_ID)
			$get_login_func = 'get_member_by_id';
		else
			$get_login_func = 'get_member_by_email';

		$member_row = array();
		if (!is_null($member_row = $this->$get_login_func($login)))
		{
			return $member_row;
		}

		return NULL;
	}

	// 회원 정보를 업데이트 한다.
	private function update_login_auth($login_datetime)
	{
		// 오늘 처음 로그인 이라면
		if (substr($login_datetime, 0, 10) != CFX_TIME_YMD)
		{
			// 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
			// 해당 회원의 접근일시와 IP 를 저장
			$sql = " UPDATE `{$this->member_table}`
						SET ".MEMBER_LOGIN_DATETIME." = '".CFX_TIME_YMDHIS."',
							".MEMBER_LOGIN_IP." = '{$_SERVER['REMOTE_ADDR']}'
						WHERE ".MEMBER_ID." = '{$this->view['id']}'; ";

			$this->db->sql_query($sql);
		}
	}

	// 회원인가?
	public function is_member()
	{
		if (isset($this->view['id']) && $this->view['id'])
			return TRUE;
		else
			return FALSE;
	}

	// 전체관리자인가?
	public function is_admin()
	{
		if ($this->is_member() && $this->view['level'] >= CFX_SUPER_ADMIN_LEVEL)
			return TRUE;
		else
			return FALSE;
	}

	// 관리자인가?
	public function is_manager()
	{
		if ($this->is_member() && $this->view['level'] >= CFX_MANAGER_LEVEL)
			return TRUE;
		else
			return FALSE;
	}

	// 로그인을 한다.
	public function login($login, $password)
	{
		$result_data = array();

		if ( (strlen($login) > 0) && (strlen($password) > 0) )
		{
			if (CFX_USE_MEMBER_ID && CFX_USE_MEMBER_EMAIL)
				$get_login_func = 'get_member_by_login';
			else if (CFX_USE_MEMBER_ID)
				$get_login_func = 'get_member_by_id';
			else
				$get_login_func = 'get_member_by_email';

			if (!is_null($member_row = $this->$get_login_func($login)))
			{
				if ($this->check_password($password, $member_row['pw']) === TRUE)
				{

					// 탈퇴한 아이디인가?
					if ($member_row['left_date'] && $member_row['left_date'] <= date("Ymd", CFX_SERVER_TIME))
					{
						$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $member_row['left_date']);

						$result_data['result'] = FALSE;
						$result_data['error'] = "* 탈퇴한 아이디이므로 접근하실 수 없습니다.\n탈퇴일 : {$date}";

						$this->increase_login_attempt($login);
						return $result_data;
					}

					$this->clear_login_attempts($login);

					// 회원아이디 세션 생성
					$this->set_session_member($member_row['id'], $member_row['name'], $member_row['created_datetime']);

					// 로그인 상태 체크
					$this->login_status_check();

					$member_row['pw'] = '';
					$result_data['result'] = TRUE;
					$result_data['data'] = $member_row;
					$result_data['error'] = "";

					return $result_data;
				}
			}

			$this->increase_login_attempt($login);

			$result_data['result'] = FALSE;
			$result_data['error'] = "* 등록된 아이디가 아니거나 비밀번호가 틀립니다.\n* 비밀번호는 대소문자를 구분합니다.";

			return $result_data;
		}

		$this->increase_login_attempt($login);

		$result_data['result'] = FALSE;
		$result_data['error'] = "* 아이디나 비밀번호가 공백이면 안됩니다.";

		return $result_data;
	}

	// 로그아웃을 한다.
	public function logout()
	{
		session_unset(); // 모든 세션변수를 언레지스터 시켜줌
		session_destroy(); // 세션해제함
	}

	/**
	 * Get number of attempts to login occured from given IP-address or login
	 *
	 * @param	string
	 * @param	string
	 * @return	int
	 */
	private function get_attempts_num($ip_address, $login = '')
	{
		$login = strtolower($login);

		if (strlen($login) > 0)
			$sql_login = "OR LOWER(login_attempts_id) = '{$login}'";
		else
			$sql_login = "";

		$sql = " SELECT count(login_attempts_no) AS cnt
					FROM `{$this->login_attempts_table}`
					WHERE login_attempts_ip = '{$ip_address}' {$sql_login}; ";

		$row = $this->db->sql_fetch($sql);

		if (isset($row) && count($row) > 0)
			return $row['cnt'];
		else
			return 0;
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 *
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	private function increase_attempt($ip_address, $login)
	{
		$login = strtolower($login);

		$sql = " INSERT INTO `{$this->login_attempts_table}`
					SET login_attempts_ip = '{$ip_address}',
						login_attempts_id = '{$login}',
						login_attempts_timestamp = null; ";

		$this->db->sql_query($sql);
	}

	/**
	 * Check if login attempts exceeded max login attempts (specified in config)
	 *
	 * @param	string
	 * @return	bool
	 */
	public function is_max_login_attempts_exceeded($login = '')
	{
		return ($this->get_attempts_num($_SERVER['REMOTE_ADDR'], $login) >= CFX_LOGIN_MAX_ATTEMPTS);
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login)
	{
		if (!$this->is_max_login_attempts_exceeded($login))
		{
			$this->increase_attempt($_SERVER['REMOTE_ADDR'], $login);
		}
	}

	/**
	 * Clear all attempt records for given IP-address and login.
	 * Also purge obsolete login attempts (to keep DB clear).
	 *
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	private function clear_attempts($ip_address, $login, $expire_period = 86400)
	{
		$login = strtolower($login);
		$time = time() - $expire_period;

		$sql = " DELETE FROM `{$this->login_attempts_table}`
					WHERE login_attempts_ip = '{$ip_address}'
						AND login_attempts_id = '{$login}'
						OR UNIX_TIMESTAMP(login_attempts_timestamp) < {$time}; ";

		$this->db->sql_query($sql);
	}

	/**
	 * Clear all attempt records for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function clear_login_attempts($login)
	{
		$this->clear_attempts($_SERVER['REMOTE_ADDR'], $login, CFX_LOGIN_ATTEMPTS_EXPIRE);
	}

	/**
	 * Get user record by login (id or email)
	 *
	 * @param	string
	 * @return	object
	 */
	public function get_member_by_login($login, $fields='*')
	{
		$login = strtolower($login);
		$sql = " SELECT {$fields}
					FROM `{$this->member_table}`
					WHERE LOWER(".MEMBER_ID.")= '{$login}' OR LOWER(".MEMBER_EMAIL.") = '{$login}'; ";

		$member_row = $this->db->sql_fetch($sql);

		if (isset($member_row) && count($member_row) > 0)
		{
			// 로그인 정보
			$result = array();
			$result['no'] = $member_row[MEMBER_NO];
			$result['id'] = get_text($member_row[MEMBER_ID]);
			$result['key'] = get_text($member_row[MEMBER_KEY]);
			$result['pw'] = get_text($member_row[MEMBER_PW]);
			$result['name'] = get_text($member_row[MEMBER_NAME]);
			$result['email'] = get_text($member_row[MEMBER_EMAIL]);
			$result['level'] = (int)$member_row[MEMBER_LEVEL];
			$result['login_datetime'] = substr($member_row[MEMBER_LOGIN_DATETIME],0,10);
			$result['created_datetime'] = $member_row[MEMBER_CREATED_DATETIME];
			$result['left_date'] = substr($member_row[MEMBER_LEFT_DATE],0,8);

			unset($member_row);

			return $result;
		}
		else
			return NULL;
	}

	/**
	 * Get user record by id (id)
	 *
	 * @param	string
	 * @return	object
	 */
	public function get_member_by_id($login, $fields='*')
	{
		$login = strtolower($login);
		$sql = " SELECT {$fields}
					FROM `{$this->member_table}`
					WHERE LOWER(".MEMBER_ID.")= '{$login}'; ";

		$member_row = $this->db->sql_fetch($sql);

		if (isset($member_row) && count($member_row) > 0)
		{
			// 로그인 정보
			$result = array();
			$result['no'] = $member_row[MEMBER_NO];
			$result['id'] = get_text($member_row[MEMBER_ID]);
			$result['key'] = get_text($member_row[MEMBER_KEY]);
			$result['pw'] = get_text($member_row[MEMBER_PW]);
			$result['name'] = get_text($member_row[MEMBER_NAME]);
			$result['email'] = get_text($member_row[MEMBER_EMAIL]);
			$result['level'] = (int)$member_row[MEMBER_LEVEL];
			$result['login_datetime'] = substr($member_row[MEMBER_LOGIN_DATETIME],0,10);
			$result['created_datetime'] = $member_row[MEMBER_CREATED_DATETIME];
			$result['left_date'] = substr($member_row[MEMBER_LEFT_DATE],0,8);

			unset($member_row);

			return $result;
		}
		else
			return NULL;
	}

	/**
	 * Get user record by id (id)
	 *
	 * @param	string
	 * @return	object
	 */
	public function get_member_by_email($login, $fields='*')
	{
		$login = strtolower($login);
		$sql = " SELECT {$fields}
					FROM `{$this->member_table}`
					WHERE LOWER(".MEMBER_EMAIL.")= '{$login}'; ";

		$member_row = $this->db->sql_fetch($sql);

		if (isset($member_row) && count($member_row) > 0)
		{
			// 로그인 정보
			$result = array();
			$result['no'] = $member_row[MEMBER_NO];
			$result['id'] = get_text($member_row[MEMBER_ID]);
			$result['pw'] = get_text($member_row[MEMBER_PW]);
			$result['key'] = get_text($member_row[MEMBER_KEY]);
			$result['name'] = get_text($member_row[MEMBER_NAME]);
			$result['email'] = get_text($member_row[MEMBER_EMAIL]);
			$result['level'] = (int)$member_row[MEMBER_LEVEL];
			$result['login_datetime'] = substr($member_row[MEMBER_LOGIN_DATETIME],0,10);
			$result['created_datetime'] = $member_row[MEMBER_CREATED_DATETIME];
			$result['left_date'] = substr($member_row[MEMBER_LEFT_DATE],0,8);

			unset($member_row);

			return $result;
		}
		else
			return NULL;
	}

	/**
	 * Get password
	 *
	 * @param	string
	 * @return	string
	 */
	public function sql_password($value)
	{
		// mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
		// mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
		$row = $this->db->sql_fetch(" SELECT password('$value') AS pass ");

		return $row['pass'];
	}

	/**
	 * Get encrypt password
	 *
	 * @param	string
	 * @return	string
	 */
	public function get_encrypt_string($value)
	{
		if (defined('CFX_STRING_ENCRYPT_FUNCTION') && CFX_STRING_ENCRYPT_FUNCTION)
		{
			$method = CFX_STRING_ENCRYPT_FUNCTION;

			if (method_exists($this, $method))
				return $this->$method($value);
		}

		return $this->sql_password($value);
	}

	/**
	 * Get encrypt password
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function check_password($pass, $hash)
	{
		$password = $this->get_encrypt_string($pass);

		return ($password === $hash);
	}
}

?>