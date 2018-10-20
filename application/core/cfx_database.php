<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Database {

	public $link = NULL;

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
	    $this->sql_connect() or die('MySQL Connect Error!!!');
	    $this->sql_select_db() or die('MySQL DB Error!!!');
	    $this->sql_set_charset(CFX_CHARSET);

	    if (defined('CFX_MYSQL_SET_MODE') && CFX_MYSQL_SET_MODE) $this>sql_query("SET SESSION sql_mode = ''");
	    if (defined(CFX_TIMEZONE)) $this->sql_query(" set time_zone = '".CFX_TIMEZONE."'");

	}

	// DB 연결
	public function sql_connect()
	{
		$link = NULL;

	    if(function_exists('mysqli_connect') && CFX_MYSQLI_USE) {

	        $link = mysqli_connect(CFX_MYSQL_HOST, CFX_MYSQL_USER, CFX_MYSQL_PASSWORD, CFX_MYSQL_DB);

	        // 연결 오류 발생 시 스크립트 종료
	        if (mysqli_connect_errno()) {
	            die('Connect Error: '.mysqli_connect_error());
	        }
	    } else {
	        $link = mysql_connect($host, $user, $pass);
	    }

	    $this->link = $link;

	    return $link;
	}

	// DB 선택
	public function sql_select_db()
	{
		$link = $this->link;

	    if(function_exists('mysqli_select_db') && CFX_MYSQLI_USE)
	        return @mysqli_select_db($link, CFX_MYSQL_DB);
	    else
	        return @mysql_select_db(CFX_MYSQL_DB, $link);
	}

	public function sql_set_charset($charset)
	{
	    $link = $this->link;

	    if(function_exists('mysqli_set_charset') && CFX_MYSQLI_USE)
	        mysqli_set_charset($link, $charset);
	    else
	        mysql_query(" set names {$charset} ", $link);
	}

	// mysqli_query 와 mysqli_error 를 한꺼번에 처리
	// mysql connect resource 지정 - 명랑폐인님 제안
	public function sql_query($sql, $error=CFX_DISPLAY_SQL_ERROR)
	{
	    $link = $this->link;

	    // Blind SQL Injection 취약점 해결
	    $sql = trim($sql);
	    // union의 사용을 허락하지 않습니다.
	    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
	    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
	    // `information_schema` DB로의 접근을 허락하지 않습니다.
	    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

	    if(function_exists('mysqli_query') && CFX_MYSQLI_USE) {
	        if ($error) {
	            $result = @mysqli_query($link, $sql) or die("<p>$sql<p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
	        } else {
	            $result = @mysqli_query($link, $sql);
	        }
	    } else {
	        if ($error) {
	            $result = @mysql_query($sql, $link) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
	        } else {
	            $result = @mysql_query($sql, $link);
	        }
	    }

	    return $result;
	}

	// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
	public function sql_fetch($sql, $error=CFX_DISPLAY_SQL_ERROR)
	{
	    $link = $this->link;

	    $result = $this->sql_query($sql, $error, $link);

	    $row = $this->sql_fetch_array($result);

	    return $row;
	}


	// 결과값에서 한행 연관배열(이름으로)로 얻는다.
	public function sql_fetch_array($result)
	{
	    if(function_exists('mysqli_fetch_assoc') && CFX_MYSQLI_USE)
	        $row = @mysqli_fetch_assoc($result);
	    else
	        $row = @mysql_fetch_assoc($result);

	    return $row;
	}

	// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
	// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
	// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
	public function sql_free_result($result)
	{
	    if(function_exists('mysqli_free_result') && CFX_MYSQLI_USE)
	        return mysqli_free_result($result);
	    else
	        return mysql_free_result($result);
	}

	public function sql_insert_id($link=null)
	{
	    $link = $this->link;

	    if(function_exists('mysqli_insert_id') && CFX_MYSQLI_USE)
	        return mysqli_insert_id($link);
	    else
	        return mysql_insert_id($link);
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
		$row = $this->sql_fetch(" SELECT password('$value') AS pass ");

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