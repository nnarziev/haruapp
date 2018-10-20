<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Api_Model extends CFX_Model
{
	public function __construct()
	{
        parent::__construct();

        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Request-Method: GET, POST, PUT, DELETE, OPTIONS');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 3600');
		header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Content-Range, Content-Disposition, Content-Description');
		header('Content-Type: application/json; charset=UTF-8', true);
	}

	private function haru_checkdate($date, $count) 
	{ 
		$result_date = '';
		
		$timestamp = strtotime($date); 
		$step = $plus = 0; 
		while ( $step < $count ) 
		{ 
			list($d,$w) = explode(' ', date('Y-m-d w', $timestamp)); 
			$timestamp+= 86400; 
			$step++; 

			if ( $w == 0 || $w == 6) { 
				$plus++; $step--; 
			} else {
				$result_date = $d;
			}
		} 

		return $result_date;
	}


	private function haru_countdate($start, $today) 
	{ 
		$start_date = strtotime(substr($start, 0, 10));
		$today_date = strtotime(substr($today, 0, 10));

		$count = 1;
		while ($start_date <= $today_date) 
		{ 
			list($current_day, $w) = explode(' ', date('Y-m-d w', $start_date)); 
			$start_date+= 86400; 

			if ( $w != 0 && $w != 6) { 
				$count++;
			}
		}

		return $count;
	}

	private function get_today_count($start) 
	{ 
		$start_datetime = strtotime(substr($start, 0, 10));
		$today_datetime = strtotime(date("Y-m-d"));

		$count = 0;
		while ($start_datetime <= $today_datetime) 
		{ 
			list($d, $w) = explode(' ', date('Y-m-d w', $start_datetime)); 
			$start_datetime+= 86400; 

			if ( $w != 0 && $w != 6) { 
				$count++;
			}
		}

		if ($count > 48)
			$count = 48;

		return $count;
	}

	private function haru_targetdate_list($date, $count) 
	{ 
		$result_date = array();
		
		$timestamp = strtotime(substr($date, 0, 10)); 
		$step = $plus = 0; 
		while ( $step < $count ) 
		{ 
			list($d,$w) = explode(' ',date('Y-m-d w', $timestamp)); 
			$timestamp+= 86400; 
			$step++; 

			if ( $w == 0 || $w == 6) { 
				$plus++; $step--; 
			} else {
				array_push($result_date, $d);
			}
		} 

		return $result_date;
	}

	public function harucard_list($key)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		if ($update_check == 1 ||  is_null($date_expire)) {
			// 조건이 NULL 이면 시작일을 현재시간으로 업데이트 한다.
			$sql = " UPDATE `cfx_harucard_join` SET `hc_join{$part}_datetime` =  '{$datetime}' WHERE member_key = '{$key}'; ";
			$this->db->sql_query($sql);

			$date_expire = $datetime;
		}

		// date count
		if ($part == 1)
			$date_expire = $member_result['hc_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['hc_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['hc_join3_datetime'];
		else {
			$date_expire = $member_result['hc_created_datetime'];
			$update_check = 0; // PASS
		}

		if ($update_check == 1 ||  is_null($date_expire)) {
			// 조건이 NULL 이면 시작일을 현재시간으로 업데이트 한다.
			$sql = " UPDATE `cfx_harucard_join` SET `hc_join{$part}_datetime` =  '{$datetime}' WHERE member_key = '{$key}'; ";
			$this->db->sql_query($sql);

			$date_expire = $datetime;
		}

		$today_count = $this->get_today_count($date_expire);

		//
		$hc_part = 'hc_part'. $part;

		$sql = " SELECT * FROM `cfx_harucard` WHERE hc_activated = '1' AND hc_no IN ({$member_result[$hc_part]}) ORDER BY FIELD (hc_no, {$member_result[$hc_part]}) LIMIT 0, {$today_count};";

		$result = $this->db->sql_query($sql);

		$i = 0;
		$list = array();

		// 하루카드 날짜를 구함
		$targetdate_list = $this->haru_targetdate_list($date_expire, $today_count);

		while ($harucard_row = $this->db->sql_fetch_array($result))
		{
			$list[$i] = $this->get_harucard_list($i, $harucard_row, 'list', $targetdate_list[$i]);

			$i++;
		}

		$data = array();
		$data["items"] = $list;

		unset($list);
	
		return $data;
	}

	public function harucard_history($id, $key)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['hc_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['hc_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['hc_join3_datetime'];
		else
			$date_expire = $member_result['hc_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$point_items = array();
		$point_record_items = array();

		$bonus_items = array();
		$bonus_record_items = array();

		$sql = " SELECT * FROM `cfx_harucard_point` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$point_result = $this->db->sql_fetch($sql);

		$total_point = 0;
		for ($i = 1; $i <=48; $i++)
		{
			$point_items[$i] = (int) $point_result['hc_point'.$i];
			$point_record_items[$i] = $point_result['hc_point_record'.$i];
			$total_point += $point_items[$i];
		}

		for ($i = 1; $i <=9; $i++)
		{
			$bonus_items[$i] = (int) $point_result['hc_bonus'.$i];
			$bonus_record_items[$i] =  $point_result['hc_bonus_record'.$i];
			$total_point += $bonus_items[$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_rating_date"] = substr($point_result['hc_last_datetime'], 0, 10);
		$data["point_items"] = $point_items;
		$data["point_record_items"] = $point_record_items;
		$data["bonus_items"] = $bonus_items;
		$data["bonus_record_items"] = $bonus_record_items;
		$data["total_point"] = $total_point;

		return $data;
	}

	public function harucard_read($id, $key, $code, $num)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['hc_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['hc_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['hc_join3_datetime'];
		else
			$date_expire = $member_result['hc_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$sql = " UPDATE `cfx_harucard_read`
					SET  `hc_last_datetime` = '{$datetime}'
					    ,`hc_read{$num}` = '1'
				 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);

		$total_point = 0;

		$point_items = array();
		$sql = " SELECT * FROM `cfx_harucard_point` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$point_result = $this->db->sql_fetch($sql);

		$point = 0;

		// 오늘 읽어야 점수 부여
		if ($today_count == $num && $point_result['hc_point'.$num] == 0)
		{
			$sql = " UPDATE `cfx_harucard_point`
						SET  `hc_last_datetime` = '{$datetime}'
						    ,`hc_point{$num}` = '20'
						    ,`hc_point_record{$num}` = '{$datetime}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";

			$this->db->sql_query($sql);

			$point = 20;
			$point_result['hc_point'.$num] = 20;
		}

		for ($i = 1; $i <=48; $i++)
		{
			$point_items[$i] = (int) $point_result['hc_point'.$i];
			$total_point += $point_items[$i];
		}

		if ($today_count == 5)
		{
			$week1_bonus = 50;

			for ($i = 1; $i <=5; $i++) {
				if ($point_items[$i] == 0) {
					$week1_bonus = 0;
					break;
				}
			}

			$total_point += $week1_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus1` = '{$week1_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 10) {
			$week2_bonus = 50;

			for ($i = 6; $i <=10; $i++) {
				if ($point_items[$i] == 0) {
					$week2_bonus = 0;
					break;
				}
			}

			$total_point += $week2_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus2` = '{$week2_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 15) {
			$week3_bonus = 50;

			for ($i = 11; $i <=15; $i++) {
				if ($point_items[$i] == 0) {
					$week3_bonus = 0;
					break;
				}
			}

			$total_point += $week3_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus3` = '{$week3_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 20) {
			$week4_bonus = 50;

			for ($i = 16; $i <=20; $i++) {
				if ($point_items[$i] == 0) {
					$week4_bonus = 0;
					break;
				}
			}

			$total_point += $week4_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus4` = '{$week4_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 25) {
			$week5_bonus = 50;

			for ($i = 21; $i <=25; $i++) {
				if ($point_items[$i] == 0) {
					$week5_bonus = 0;
					break;
				}
			}

			$total_point += $week5_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus5` = '{$week5_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 30) {
			$week6_bonus = 50;

			for ($i = 26; $i <=30; $i++) {
				if ($point_items[$i] == 0) {
					$week6_bonus = 0;
					break;
				}
			}

			$total_point += $week6_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus6` = '{$week6_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 35) {
			$week7_bonus = 50;

			for ($i = 31; $i <=35; $i++) {
				if ($point_items[$i] == 0) {
					$week7_bonus = 0;
					break;
				}
			}

			$total_point += $week7_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonu7` = '{$week7_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 40) {
			$week8_bonus = 50;

			for ($i = 36; $i <=40; $i++) {
				if ($point_items[$i] == 0) {
					$week8_bonus = 0;
					break;
				}
			}

			$total_point += $week8_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus8` = '{$week8_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 45) {
			$week9_bonus = 50;

			for ($i = 41; $i <=45; $i++) {
				if ($point_items[$i] == 0) {
					$week9_bonus = 0;
					break;
				}
			}

			$total_point += $week9_bonus;

			$sql = " UPDATE `cfx_harucard_read`
						SET  `hc_last_datetime` = '{$datetime}',
							 `hc_bonus9` = '{$week9_bonus}'
					 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);
		}

		$read_items = array();
		$sql = " SELECT * FROM `cfx_harucard_read` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$read_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$read_items[$i] = $read_result['hc_read'.$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_read_date"] = substr($read_result['hc_last_datetime'], 0, 10);
		$data["read_items"] = $read_items;
		$data["total_point"] = (string)$total_point;
		$data["num"] = $num;
		$data["point"] = $point;

		return $data;
	}

	public function harucard_rating($id, $key, $rating)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['hc_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['hc_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['hc_join3_datetime'];
		else
			$date_expire = $member_result['hc_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$sql = " UPDATE `cfx_harucard_rating`
					SET  `hc_last_datetime` = '{$datetime}'
					    ,`hc_rating{$today_count}` = '{$rating}'
				 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);

		$rating = array();
		$sql = " SELECT * FROM `cfx_harucard_rating` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$rating_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$rating[$i] = $rating_result['hc_rating'.$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_rating_date"] = substr($rating_result['hc_last_datetime'], 0, 10);
		$data["rating_items"] = $rating;

		return $data;
	}

	public function harucard_bookmark($id, $key, $code, $num)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['hc_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['hc_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['hc_join3_datetime'];
		else
			$date_expire = $member_result['hc_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$sql = " SELECT * FROM `cfx_harucard_bookmark` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$bookmark_result = $this->db->sql_fetch($sql);

		$bookmark_value = ($bookmark_result["hc_bookmark{$num}"] == 0) ? 1 : 0;

		$sql = " UPDATE `cfx_harucard_bookmark`
					SET  `hc_last_datetime` = '{$datetime}'
					    ,`hc_bookmark{$num}` = '{$bookmark_value}'
				 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);

		$bookmark = array();

		$sql = " SELECT * FROM `cfx_harucard_bookmark` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$bookmark_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$bookmark[$i] = $bookmark_result['hc_bookmark'.$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_bookmark_date"] = substr($bookmark_result['hc_last_datetime'], 0, 10);
		$data["num"] = $num;
		$data["bookmark_items"] = $bookmark;
		$data["bookmark"] = $bookmark_value;

		return $data;
	}

	public function harucard_info($id, $key, $app_name='', $app_os='', $app_version='')
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		// 조건이 NULL인지 체크한다.
		$sql = " SELECT COUNT(`member_id`) AS cnt FROM `cfx_harucard_point` WHERE `hc_last_datetime` IS NULL AND `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$check_row = $this->db->sql_fetch($sql);
		$update_check = $check_row['cnt'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['hc_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['hc_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['hc_join3_datetime'];
		else {
			$date_expire = $member_result['hc_created_datetime'];
		}

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$bookmark_items = array();
		$sql = " SELECT * FROM `cfx_harucard_bookmark` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$bookmark_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$bookmark_items[$i] = $bookmark_result['hc_bookmark'.$i];
		}

		$rating_items = array();
		$sql = " SELECT * FROM `cfx_harucard_rating` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$rating_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$rating_items[$i] = $rating_result['hc_rating'.$i];
		}

		$read_items = array();
		$sql = " SELECT * FROM `cfx_harucard_read` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$read_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$read_items[$i] = $read_result['hc_read'.$i];
		}

		$point_items = array();
		$sql = " SELECT * FROM `cfx_harucard_point` WHERE `hc_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$point_result = $this->db->sql_fetch($sql);

		$total_point = 0;
		for ($i = 1; $i <=48; $i++)
		{
			$total_point += $point_result['hc_point'.$i];
		}

		for ($i = 1; $i <=9; $i++)
		{
			$total_point += $point_result['hc_bonus'.$i];
		}

		$rating_graph_num = array();
		$rating_graph_data = array();

		$rating_graph_full_num = array();
		$rating_graph_full_data = array();

		$step = 0;
		$max_step = $today_count;
		if ($today_count <= 48)
		{
			if ($today_count > 4)
				$step = $today_count - 4;
			else
				$step = 1;
		} else {
			$step = 44;
			$max_step = 48;
		}

		$i = 0;
		for ($step; $step <= $max_step; $step++)
		{
			$rating_graph_num[$i] = "{$step}회기";
			$rating_graph_data[$i] = $rating_result["hc_rating{$step}"];
			$i++;
		}

		$i = 0;
		for ($step = 1; $step <= $max_step; $step++)
		{
			$rating_graph_full_num[$i] = "{$step}회기";
			$rating_graph_full_data[$i] = $rating_result["hc_rating{$step}"];
			$i++;
		}

		$gift_items = array();
		$sql = " SELECT * FROM `cfx_setup` WHERE `setup_no` = 1;";
		$gift_result = $this->db->sql_fetch($sql);

		$gift_items['activated'] = $gift_result['setup_activated'];
		$gift_items['data1'] = CFX_URL.'/data/images/'.$gift_result['setup_data1'];
		$gift_items['data2'] = CFX_URL.'/data/images/'.$gift_result['setup_data2'];
		$gift_items['data3'] = CFX_URL.'/data/images/'.$gift_result['setup_data3'];
		$gift_items['data4'] = CFX_URL.'/data/images/'.$gift_result['setup_data4'];
		$gift_items['data5'] = CFX_URL.'/data/images/'.$gift_result['setup_data5'];

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_read_date"] = substr($read_result['hc_last_datetime'], 0, 10);
		$data["last_rating_date"] = substr($rating_result['hc_last_datetime'], 0, 10);
		$data["last_bookmark_date"] = substr($bookmark_result['hc_last_datetime'], 0, 10);
		$data["last_point_date"] = substr($point_result['hc_last_datetime'], 0, 10);
		$data["read_items"] = $read_items;
		$data["rating_items"] = $rating_items;
		$data["total_point"] = (string)$total_point;
		$data["bookmark_items"] = $bookmark_items;
		$data["rating_graph_num_items"] = $rating_graph_num;
		$data["rating_graph_data_items"] = $rating_graph_data;
		$data["rating_graph_full_num_items"] = $rating_graph_full_num;
		$data["rating_graph_full_data_items"] = $rating_graph_full_data;
		$data["gift_items"] = $gift_items;

		$data["part"] = $part;

		unset($read_items);
		unset($rating_items);
		unset($bookmark_items);
		unset($rating_graph_num);
		unset($rating_graph_data);
		unset($rating_graph_full_num);
		unset($rating_graph_full_data);
		unset($gift_items);

		return $data;
	}

	private function get_harucard($code)
	{
		$code = strtolower($code);
		return $this->db->sql_fetch(" SELECT * FROM `cfx_harucard` WHERE hc_activated = '1' AND LOWER(hc_code) = '{$code}'; ");
	}

	private function get_harucard_view($harucard_row)
	{
		return $this->get_harucard_list($harucard_row, 'view');
	}

	private function get_harucard_list($num, $harucard_row, $mode = 'list', $targetdate='')
	{
		// 하루카드 정보
		$list = array();
		$list['num'] = $num + 1;
		$list['hc_targetdate'] = $targetdate;
		$day_num = date('w', strtotime($targetdate));
		if ($day_num == 1) {
			$list['hc_targetday'] = '월';
		} else if ($day_num == 2) {
			$list['hc_targetday'] = '화';
		} else if ($day_num == 3) {
			$list['hc_targetday'] = '수';
		} else if ($day_num == 4) {
			$list['hc_targetday'] = '목';
		} else if ($day_num == 5) {
			$list['hc_targetday'] = '금';
		} else if ($day_num == 6) {
			$list['hc_targetday'] = '토';
		} else if ($day_num == 7) {
			$list['hc_targetday'] = '일';
		}
		$list['hc_no'] = intval($harucard_row['hc_no']);
		$list['hc_code'] = $harucard_row['hc_code'];
		$list['hc_type'] = $harucard_row['hc_type'];
		$list['hc_category'] = $harucard_row['hc_category'];
		$list['hc_category_name'] = $harucard_row['hc_category_name'];
		$list['hc_title'] = ($mode == 'list') ? strip_tags($harucard_row['hc_title']) : strip_tags($harucard_row['hc_title'], '<br>');
		$list['hc_view_title'] = strip_tags($harucard_row['hc_title'], '<br>');
		$list['hc_strip_title'] = strip_tags($harucard_row['hc_title'], '<br>');
		$list['hc_top_class'] = $harucard_row['hc_top_class'];
		$list['hc_image_class'] = $harucard_row['hc_image_class'];
		$list['hc_image'] = explode('||', $harucard_row['hc_image'] );
		$list['hc_content_class'] = explode('||', $harucard_row['hc_content_class'] );
		$list['hc_content'] = explode('||', $harucard_row['hc_content'] );
		$list['hc_url_class'] = explode('||', $harucard_row['hc_url_class'] );
		$list['hc_url_link'] = explode('||', $harucard_row['hc_url_link'] );
		$list['hc_url_title'] = explode('||', $harucard_row['hc_url_title'] );
		$list['hc_dot_class'] = $harucard_row['hc_dot_class'];
		$list['hc_activated'] = intval($harucard_row['hc_activated']);

		unset($harucard_row);

		return $list;
	}

	public function harucard_select_gift($id, $key, $select_count, $select_num)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harucard_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['hc_current_part'];

		$sql = " UPDATE `cfx_harucard_point`
					SET  `hc_gift_record{$select_count}` = '{$datetime}'
					    ,`hc_gift{$select_count}` = '{$select_num}'
				 WHERE `hc_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_gift`
					SET `gift_program` = '하루카드'
					, `member_id` =  {$id}
					, `member_key` =  {$key}
					, `gift_count` =  {$select_count}
					, `gift_num` =  {$select_num}
					, `gift_created_datetime` = '{$datetime}'
				";

		$data = array();
		$data["member_key"] = $key;
		$data["datetime"] = $datetime;
	}

	public function harutoday_info($id, $key, $app_name='', $app_os='', $app_version='')
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];


		$sql = " SELECT * FROM `cfx_harutoday_config` WHERE 1=1; ";
		$config_result = $this->db->sql_fetch($sql);

		$config_items = array();
		
		if ($app_os == 'android') {
			$config_items['config_ver'] = $config_result['ht_ver_android_module'.$part];
			$config_items['config_link'] = $config_result['ht_link_android_module'.$part];
		} else if ($app_os == 'ios') {
			$config_items['config_ver'] = $config_result['ht_ver_ios_module'.$part];
			$config_items['config_link'] = $config_result['ht_link_ios_module'.$part];
		}

		// date count
		if ($part == 1) {
			$date_expire = $member_result['ht_join1_datetime'];
		} else if ($part == 2) {
			$date_expire = $member_result['ht_join2_datetime'];
		} else if ($part == 3) {
			$date_expire = $member_result['ht_join3_datetime'];
		} else {
			$date_expire = $member_result['ht_created_datetime'];
		}

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$bookmark_items = array();
		$sql = " SELECT * FROM `cfx_harutoday_bookmark` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$bookmark_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$bookmark_items[$i] = $bookmark_result['ht_bookmark'.$i];
		}

		$rating_items = array();
		$sql = " SELECT * FROM `cfx_harutoday_rating` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$rating_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$rating_items[$i] = $rating_result['ht_rating'.$i];
		}

		$read_items = array();
		$sql = " SELECT * FROM `cfx_harutoday_read` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$read_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$read_items[$i] = $read_result['ht_read'.$i];
		}

		$point_items = array();
		$sql = " SELECT * FROM `cfx_harutoday_point` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$point_result = $this->db->sql_fetch($sql);

		$total_point = 0;
		for ($i = 1; $i <=48; $i++)
		{
			$total_point += (int) $point_result['ht_point'.$i];
		}

		for ($i = 1; $i <=9; $i++)
		{
			$total_point += (int) $point_result['ht_bonus'.$i];
		}

		for ($i = 1; $i <=48; $i++)
		{
			$total_point += (int) $point_result['ht_quiz'.$i];
		}

		for ($i = 1; $i <=5; $i++)
		{
			$total_point += (int) $point_result['ht_zone'.$i];
		}

		$rating_graph_num = array();
		$rating_graph_data = array();

		$rating_graph_full_num = array();
		$rating_graph_full_data = array();

		$step = 0;
		$max_step = $today_count;
		if ($today_count <= 48)
		{
			if ($today_count > 4)
				$step = $today_count - 4;
			else
				$step = 1;
		} else {
			$step = 44;
			$max_step = 48;
		}

		$i = 0;
		for ($step; $step <= $max_step; $step++)
		{
			$rating_graph_num[$i] = "{$step}회기";
			$rating_graph_data[$i] = $rating_result["ht_rating{$step}"];
			$i++;
		}

		$i = 0;
		for ($step = 1; $step <= $max_step; $step++)
		{
			$rating_graph_full_num[$i] = "{$step}회기";
			$rating_graph_full_data[$i] = $rating_result["ht_rating{$step}"];
			$i++;
		}

		$gift_items = array();
		$sql = " SELECT * FROM `cfx_setup` WHERE `setup_no` = 1;";
		$gift_result = $this->db->sql_fetch($sql);

		$gift_items['activated'] = $gift_result['setup_activated'];
		$gift_items['data1'] = CFX_URL.'/data/images/'.$gift_result['setup_data1'];
		$gift_items['data2'] = CFX_URL.'/data/images/'.$gift_result['setup_data2'];
		$gift_items['data3'] = CFX_URL.'/data/images/'.$gift_result['setup_data3'];
		$gift_items['data4'] = CFX_URL.'/data/images/'.$gift_result['setup_data4'];
		$gift_items['data5'] = CFX_URL.'/data/images/'.$gift_result['setup_data5'];
		$gift_items['gift_record1'] = $point_result['ht_gift_record1'];
		$gift_items['gift_record2'] = $point_result['ht_gift_record1'];
		$gift_items['gift_record3'] = $point_result['ht_gift_record1'];
		$gift_items['gift_record4'] = $point_result['ht_gift_record1'];
		$gift_items['gift_record5'] = $point_result['ht_gift_record1'];

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 10);
		$date_result["test_datetext"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_read_date"] = substr($read_result['ht_last_datetime'], 0, 10);
		$data["last_rating_date"] = substr($rating_result['ht_last_datetime'], 0, 10);
		$data["last_bookmark_date"] = substr($bookmark_result['ht_last_datetime'], 0, 10);
		$data["last_point_date"] = substr($point_result['ht_last_datetime'], 0, 10);
		$data["read_items"] = $read_items;
		$data["rating_items"] = $rating_items;
		$data["total_point"] = (string)$total_point;
		$data["bookmark_items"] = $bookmark_items;
		$data["rating_graph_num_items"] = $rating_graph_num;
		$data["rating_graph_data_items"] = $rating_graph_data;
		$data["rating_graph_full_num_items"] = $rating_graph_full_num;
		$data["rating_graph_full_data_items"] = $rating_graph_full_data;
		$data["config_ver"] = $config_items['config_ver'];
		$data["config_link"] = $config_items['config_link'];
		$data["gift_items"] = $gift_items;

		$data["part"] = $part;

		unset($read_items);
		unset($rating_items);
		unset($bookmark_items);
		unset($rating_graph_num);
		unset($rating_graph_data);
		unset($rating_graph_full_num);
		unset($rating_graph_full_data);
		unset($config_items);
		unset($gift_items);

		return $data;
	}

	public function harutoday_history($id, $key)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['ht_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['ht_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['ht_join3_datetime'];
		else
			$date_expire = $member_result['ht_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$point_items = array();
		$point_record_items = array();

		$bonus_items = array();
		$bonus_record_items = array();

		$zone_items = array();
		$zone_record_items = array();

		$quiz_items = array();
		$quiz_record_items = array();

		$sql = " SELECT * FROM `cfx_harutoday_point` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$point_result = $this->db->sql_fetch($sql);

		$total_point = 0;
		for ($i = 1; $i <=48; $i++)
		{
			$point_items[$i] = (int) $point_result['ht_point'.$i];
			$point_record_items[$i] = ($point_result['ht_point_record'.$i] === null) ? '' : $point_result['ht_point_record'.$i];
			$total_point += $point_items[$i];
		}

		for ($i = 1; $i <=48; $i++)
		{
			if ($i == 5) {
				$bonus_items[$i] = (int) $point_result['ht_bonus1'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record1'] === null) ? '' : $point_result['ht_bonus_record1'];
			} else if ($i == 10) {
				$bonus_items[$i] = (int) $point_result['ht_bonus2'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record2'] === null) ? '' : $point_result['ht_bonus_record2'];
			} else if ($i == 15) {
				$bonus_items[$i] = (int) $point_result['ht_bonus3'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record3'] === null) ? '' : $point_result['ht_bonus_record3'];
			} else if ($i == 20) {
				$bonus_items[$i] = (int) $point_result['ht_bonus4'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record4'] === null) ? '' : $point_result['ht_bonus_record4'];
			} else if ($i == 25) {
				$bonus_items[$i] = (int) $point_result['ht_bonus5'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record5'] === null) ? '' : $point_result['ht_bonus_record5'];
			} else if ($i == 30) {
				$bonus_items[$i] = (int) $point_result['ht_bonus6'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record6'] === null) ? '' : $point_result['ht_bonus_record6'];
			} else if ($i == 35) {
				$bonus_items[$i] = (int) $point_result['ht_bonus7'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record7'] === null) ? '' : $point_result['ht_bonus_record7'];
			} else if ($i == 40) {
				$bonus_items[$i] = (int) $point_result['ht_bonus8'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record8'] === null) ? '' : $point_result['ht_bonus_record8'];
			} else if ($i == 45) {
				$bonus_items[$i] = (int) $point_result['ht_bonus9'];
				$bonus_record_items[$i] = ($point_result['ht_bonus_record9'] === null) ? '' : $point_result['ht_bonus_record9'];
			} else {
				$bonus_items[$i] = 0;
				$bonus_record_items[$i] =  '';
			}

			$total_point += $bonus_items[$i];
		}

		for ($i = 1; $i <=48; $i++)
		{
			$quiz_items[$i] = $point_result['ht_quiz'.$i];
			$quiz_record_items[$i] =  ($point_result['ht_quiz_record'.$i] === null) ? '' : $point_result['ht_quiz_record'.$i];
			$total_point += $quiz_items[$i];
		}

		for ($i = 1; $i <=48; $i++)
		{
			if ($part == 1) {
				if ($i == 6) {
					$zone_items[$i] = (int) $point_result['ht_zone1'];
					$zone_record_items[$i] = ($point_result['ht_zone_record1'] === null) ? '' : $point_result['ht_zone_record1'];
				} else if ($i == 13) {
					$zone_items[$i] = (int) $point_result['ht_zone2'];
					$zone_record_items[$i] = ($point_result['ht_zone_record2'] === null) ? '' : $point_result['ht_zone_record2'];
				} else if ($i == 24) {
					$zone_items[$i] = (int) $point_result['ht_zone3'];
					$zone_record_items[$i] = ($point_result['ht_zone_record3'] === null) ? '' : $point_result['ht_zone_record3'];
				} else if ($i == 38) {
					$zone_items[$i] = (int) $point_result['ht_zone4'];
					$zone_record_items[$i] = ($point_result['ht_zone_record4'] === null) ? '' : $point_result['ht_zone_record4'];
				} else if ($i == 48) {
					$zone_items[$i] = (int) $point_result['ht_zone5'];
					$zone_record_items[$i] = ($point_result['ht_zone_record5'] === null) ? '' : $point_result['ht_zone_record5'];
				} else {
					$zone_items[$i] = 0;
					$zone_record_items[$i] = "";
				}
			} else if ($part == 2) {
				if ($i == 7) {
					$zone_items[$i] = (int) $point_result['ht_zone1'];
					$zone_record_items[$i] = ($point_result['ht_zone_record1'] === null) ? '' : $point_result['ht_zone_record1'];
				} else if ($i == 16) {
					$zone_items[$i] = (int) $point_result['ht_zone2'];
					$zone_record_items[$i] = ($point_result['ht_zone_record2'] === null) ? '' : $point_result['ht_zone_record2'];
				} else if ($i == 26) {
					$zone_items[$i] = (int) $point_result['ht_zone3'];
					$zone_record_items[$i] = ($point_result['ht_zone_record3'] === null) ? '' : $point_result['ht_zone_record3'];
				} else if ($i == 38) {
					$zone_items[$i] = (int) $point_result['ht_zone4'];
					$zone_record_items[$i] = ($point_result['ht_zone_record4'] === null) ? '' : $point_result['ht_zone_record4'];
				} else if ($i == 48) {
					$zone_items[$i] = (int) $point_result['ht_zone5'];
					$zone_record_items[$i] = ($point_result['ht_zone_record5'] === null) ? '' : $point_result['ht_zone_record5'];
				} else {
					$zone_items[$i] = 0;
					$zone_record_items[$i] = "";
				}
			} else if ($part == 3) {
				if ($i == 7) {
					$zone_items[$i] = (int) $point_result['ht_zone1'];
					$zone_record_items[$i] = ($point_result['ht_zone_record1'] === null) ? '' : $point_result['ht_zone_record1'];
				} else if ($i == 18) {
					$zone_items[$i] = (int) $point_result['ht_zone2'];
					$zone_record_items[$i] = ($point_result['ht_zone_record2'] === null) ? '' : $point_result['ht_zone_record2'];
				} else if ($i == 27) {
					$zone_items[$i] = (int) $point_result['ht_zone3'];
					$zone_record_items[$i] = ($point_result['ht_zone_record3'] === null) ? '' : $point_result['ht_zone_record3'];
				} else if ($i == 38) {
					$zone_items[$i] = (int) $point_result['ht_zone4'];
					$zone_record_items[$i] = ($point_result['ht_zone_record4'] === null) ? '' : $point_result['ht_zone_record4'];
				} else if ($i == 48) {
					$zone_items[$i] = (int) $point_result['ht_zone5'];
					$zone_record_items[$i] = ($point_result['ht_zone_record5'] === null) ? '' : $point_result['ht_zone_record5'];
				} else {
					$zone_items[$i] = 0;
					$zone_record_items[$i] = "";
				}
			} 

			$total_point += $zone_items[$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_rating_date"] = substr($point_result['ht_last_datetime'], 0, 10);
		$data["point_items"] = $point_items;
		$data["point_record_items"] = $point_record_items;
		$data["bonus_items"] = $bonus_items;
		$data["bonus_record_items"] = $bonus_record_items;
		$data["quiz_items"] = $quiz_items;
		$data["quiz_record_items"] = $quiz_record_items;
		$data["zone_items"] = $zone_items;
		$data["zone_record_items"] = $zone_record_items;
		$data["total_point"] = $total_point;

		return $data;
	}

	public function harutoday_menu($mcode, $key)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		// 조건이 NULL인지 체크한다.
		$sql = " SELECT COUNT(`member_id`) AS cnt FROM `cfx_harutoday_point` WHERE `ht_last_datetime` IS NULL AND `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$check_row = $this->db->sql_fetch($sql);
		$update_check = $check_row['cnt'];

		// date count
		if ($part == 1) {
			$date_expire = $member_result['ht_join1_datetime'];
		} else if ($part == 2) {
			$date_expire = $member_result['ht_join2_datetime'];
		} else if ($part == 3) {
			$date_expire = $member_result['ht_join3_datetime'];
		}	else {
			$date_expire = $member_result['ht_created_datetime'];
			$update_check = 0;
		}

		if ($update_check == 1 || is_null($date_expire)) {
			// 조건이 NULL 이면 시작일을 현재시간으로 업데이트 한다.
			$sql = " UPDATE `cfx_harutoday_join` SET `ht_join{$part}_datetime` =  '{$datetime}' WHERE member_key = '{$key}'; ";
			$this->db->sql_query($sql);

			$date_expire = $datetime;
		}

		$today_count = $this->get_today_count($date_expire);

		$sql = " SELECT * FROM `cfx_harutoday_menu` WHERE ht_activated = '1' AND ht_mcode = '{$mcode}' ORDER BY ht_pnum ASC LIMIT 0, {$today_count};";

		$harutoday_menu_result = $this->db->sql_query($sql);

		$harutoday_menu_list = array();

		// 오늘하루 날짜를 구함
		$targetdate_list = $this->haru_targetdate_list($date_expire, $today_count);

		$i = 0;
		while ($harutoday_menu_row = $this->db->sql_fetch_array($harutoday_menu_result))
		{
			$harutoday_menu_list[$i] = $this->get_harutoday_menu($i, $harutoday_menu_row, 'list', $targetdate_list[$i]);
			$i++;
		}

		$data = array();
		$data["harutoday_menu_items"] = $harutoday_menu_list;

		unset($harutoday_menu_list);
	
		return $data;
	}

	private function get_harutoday_menu($num, $harutoday_menu_row, $mode = 'list', $targetdate='')
	{
		// 하루카드 정보
		$list = array();
		$list['num'] = $num + 1;
		$list['ht_targetdate'] = $targetdate;
		$day_num = date('w', strtotime($targetdate));
		if ($day_num == 1) {
			$list['ht_targetday'] = '월';
		} else if ($day_num == 2) {
			$list['ht_targetday'] = '화';
		} else if ($day_num == 3) {
			$list['ht_targetday'] = '수';
		} else if ($day_num == 4) {
			$list['ht_targetday'] = '목';
		} else if ($day_num == 5) {
			$list['ht_targetday'] = '금';
		} else if ($day_num == 6) {
			$list['ht_targetday'] = '토';
		} else if ($day_num == 7) {
			$list['ht_targetday'] = '일';
		}
		$list['ht_no'] = intval($harutoday_menu_row['ht_no']);
		$list['ht_mcode'] = $harutoday_menu_row['ht_mcode'];
		$list['ht_zcode'] = $harutoday_menu_row['ht_zcode'];
		$list['ht_pcode'] = $harutoday_menu_row['ht_pcode'];
		$list['ht_pnum'] = intval($harutoday_menu_row['ht_pnum']);
		$list['ht_mtitle'] = $harutoday_menu_row['ht_mtitle'];
		$list['ht_ztitle'] = $harutoday_menu_row['ht_ztitle'];
		$list['ht_ptitle'] = $harutoday_menu_row['ht_ptitle'];
		$list['ht_count'] = intval($harutoday_menu_row['ht_count']);
		$list['ht_activated'] = intval($harutoday_menu_row['ht_activated']);

		unset($harutoday_menu_row);

		return $list;
	}

	public function harutoday_data_list($mcode, $zcode, $pcode, $key, $part_no = 1, $pnum = 1)
	{
		$sql = " SELECT * FROM `cfx_harutoday_data` WHERE `ht_activated` = '1' AND `ht_mcode` = '{$mcode}' AND `ht_zcode` = '{$zcode}'  AND `ht_pcode` = '{$pcode}' ORDER BY `ht_snum` ASC;";

		$harutoday_data_result = $this->db->sql_query($sql);

		$i = 0;
		$harutoday_data_list = array();
		while ($harutoday_data_row = $this->db->sql_fetch_array($harutoday_data_result))
		{
			$harutoday_data_list[$i] = $this->get_harutoday_data_list($i, $harutoday_data_row, 'list');
			$i++;
		}

		$sql = " SELECT * FROM `cfx_harutoday_userdata` WHERE `ht_part_no` = '{$part_no}' AND `ht_pnum` = '{$pnum}' AND `member_key` = '{$key}';";
		$harutoday_userdata_result = $this->db->sql_fetch($sql);

		$harutoday_userdata = $harutoday_userdata_result['ht_userdata'];

		$data = array();
		$data["harutoday_data_items"] = $harutoday_data_list;
		$data["harutoday_userdata"] = $harutoday_userdata;

		unset($harutoday_data_list);
		unset($harutoday_userdata);
	
		return $data;
	}

	private function get_harutoday_data_list($num, $harutoday_data_row, $mode = 'list')
	{
		// 오늘하루 회기 정보
		$list = array();
		$list['ht_num'] = $num + 1;
		$list['ht_no'] = intval($harutoday_data_row['ht_no']);
		$list['ht_code'] = $harutoday_data_row['ht_code'];
		$list['ht_mcode'] = $harutoday_data_row['ht_mcode'];
		$list['ht_zcode'] = $harutoday_data_row['ht_zcode'];
		$list['ht_pcode'] = $harutoday_data_row['ht_pcode'];
		$list['ht_scode'] = $harutoday_data_row['ht_scode'];
		$list['ht_snum'] = intval($harutoday_data_row['ht_snum']);
		$list['ht_tpcode'] = $harutoday_data_row['ht_tpcode'];
		$list['ht_tpclass'] = $harutoday_data_row['ht_tpclass'];
		$list['ht_tpimage'] = $harutoday_data_row['ht_tpimage'];
		$list['ht_image1'] = $harutoday_data_row['ht_image1'];
		$list['ht_image2'] = $harutoday_data_row['ht_image2'];
		$list['ht_userdata'] = $harutoday_data_row['ht_userdata'];
		$list['ht_title'] = $harutoday_data_row['ht_title'];
		$list['ht_text1'] = $harutoday_data_row['ht_text1'];
		$list['ht_text2'] = $harutoday_data_row['ht_text2'];
		$list['ht_text3'] = $harutoday_data_row['ht_text3'];
		$list['ht_text4'] = $harutoday_data_row['ht_text4'];
		$list['ht_text5'] = $harutoday_data_row['ht_text5'];
		$list['ht_audio'] = $harutoday_data_row['ht_audio'];
		$list['ht_time'] = $harutoday_data_row['ht_time'];
		$list['ht_ver'] = intval($harutoday_data_row['ht_ver']);
		$list['ht_activated'] = intval($harutoday_data_row['ht_activated']);
		$list['ht_base_image'] = "https://www.haruapp.net/assets/content/";
		$list['ht_base_audio'] = "https://www.haruapp.net/assets/content/{$harutoday_data_row['ht_mcode']}_audio/";

		unset($harutoday_data_row);

		return $list;
	}

	public function harutoday_rating($id, $key, $pcount, $rating)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['ht_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['ht_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['ht_join3_datetime'];
		else
			$date_expire = $member_result['ht_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		// 오늘날짜와 프로그램 회기날짜 카운트가 같아야만 업데이트
		$is_updated = false;
		if ($today_count == $pcount)
		{
			$is_updated = true;
		}

		// 지난 회기도 업데이트
		$sql = " UPDATE `cfx_harutoday_rating`
					SET  `ht_last_datetime` = '{$datetime}'
					    ,`ht_rating{$pcount}` = '{$rating}'
				 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);


		$rating = array();
		$sql = " SELECT * FROM `cfx_harutoday_rating` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$rating_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$rating[$i] = $rating_result['ht_rating'.$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["pcount"] = $pcount; 
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 10);
		$date_result["test_datetext"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_rating_date"] = substr($rating_result['ht_last_datetime'], 0, 10);
		$data["rating_items"] = $rating;
		$data["is_updated"] = $is_updated;

		return $data;
	}

	public function harutoday_read($id, $key, $code, $num, $quizpoint=0, $zone_num=0)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		// date count
		if ($part == 1)
			$date_expire = $member_result['ht_join1_datetime'];
		else if ($part == 2)
			$date_expire = $member_result['ht_join2_datetime'];
		else if ($part == 3)
			$date_expire = $member_result['ht_join3_datetime'];
		else
			$date_expire = $member_result['ht_created_datetime'];

		$today_count = $this->get_today_count($date_expire);

		// test date
		$test_date = $this->haru_checkdate($date_expire, $today_count);

		$sql = " UPDATE `cfx_harutoday_read`
					SET  `ht_last_datetime` = '{$datetime}'
						,`ht_read_datetime{$num}` = '{$datetime}'
					    ,`ht_read{$num}` = '1'
				 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);


		$total_point = 0;

		$point_items = array();
		$sql = " SELECT * FROM `cfx_harutoday_point` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$point_result = $this->db->sql_fetch($sql);

		$point = 0;

		// 오늘 읽어야 점수 부여
		if ($today_count == $num && $point_result['ht_point'.$num] == 0)
		{
			$update_zone = "";
			if ($zone_num > 0 && $zone_num <= 5) {
				$update_zone = ", `ht_zone{$zone_num}` = 200, `ht_zone_record{$zone_num}` = '{$datetime}' ";
			}

			$sql = " UPDATE `cfx_harutoday_point`
						SET  `ht_last_datetime` = '{$datetime}'
						    ,`ht_point{$num}` = '20'
						    ,`ht_point_record{$num}` = '{$datetime}'
					    	{$update_zone}
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";

			$this->db->sql_query($sql);

			$point = 20;
			$point_result['ht_point'.$num] = $point;
		}

		// 오늘 퀴즈점수 부여
		if ($today_count == $num && $point_result['ht_quiz'.$num] == 0)
		{
			$sql = " UPDATE `cfx_harutoday_point`
						SET  `ht_last_datetime` = '{$datetime}'
						    ,`ht_quiz{$num}` = '{$quizpoint}'
						    ,`ht_quiz_record{$num}` = '{$datetime}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";

			$this->db->sql_query($sql);

			$point_result['ht_quiz'.$num] = $quizpoint;

			// 퀴즈점수 추가
			$point += $quizpoint;
		}

		for ($i = 1; $i <=48; $i++)
		{
			$point_items[$i] = $point_result['ht_point'.$i];
			$total_point += $point_items[$i];
			// 퀴즈점수 추가
			$total_point += $point_result['ht_quiz'.$i];
		}

		if ($today_count == 5)
		{
			$week1_bonus = 20;

			for ($i = 1; $i <=5; $i++) {
				if ($point_items[$i] == 0) {
					$week1_bonus = 0;
					break;
				}
			}

			$total_point += $week1_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus1` = '{$week1_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 10) {
			$week2_bonus = 20;

			for ($i = 6; $i <=10; $i++) {
				if ($point_items[$i] == 0) {
					$week2_bonus = 0;
					break;
				}
			}

			$total_point += $week2_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus2` = '{$week2_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 15) {
			$week3_bonus = 20;

			for ($i = 11; $i <=15; $i++) {
				if ($point_items[$i] == 0) {
					$week3_bonus = 0;
					break;
				}
			}

			$total_point += $week3_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus3` = '{$week3_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 20) {
			$week4_bonus = 20;

			for ($i = 16; $i <=20; $i++) {
				if ($point_items[$i] == 0) {
					$week4_bonus = 0;
					break;
				}
			}

			$total_point += $week4_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus4` = '{$week4_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 25) {
			$week5_bonus = 20;

			for ($i = 21; $i <=25; $i++) {
				if ($point_items[$i] == 0) {
					$week5_bonus = 0;
					break;
				}
			}

			$total_point += $week5_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus5` = '{$week5_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 30) {
			$week6_bonus = 20;

			for ($i = 26; $i <=30; $i++) {
				if ($point_items[$i] == 0) {
					$week6_bonus = 0;
					break;
				}
			}

			$total_point += $week6_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus6` = '{$week6_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 35) {
			$week7_bonus = 20;

			for ($i = 31; $i <=35; $i++) {
				if ($point_items[$i] == 0) {
					$week7_bonus = 0;
					break;
				}
			}

			$total_point += $week7_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus7` = '{$week7_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 40) {
			$week8_bonus = 20;

			for ($i = 36; $i <=40; $i++) {
				if ($point_items[$i] == 0) {
					$week8_bonus = 0;
					break;
				}
			}

			$total_point += $week8_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus8` = '{$week8_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);

		} else if ($today_count == 45) {
			$week9_bonus = 20;

			for ($i = 41; $i <=45; $i++) {
				if ($point_items[$i] == 0) {
					$week9_bonus = 0;
					break;
				}
			}

			$total_point += $week9_bonus;

			$sql = " UPDATE `cfx_harutoday_read`
						SET  `ht_last_datetime` = '{$datetime}',
							 `ht_bonus9` = '{$week9_bonus}'
					 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
					";
			$this->db->sql_query($sql);
		}

		$read_items = array();
		$sql = " SELECT * FROM `cfx_harutoday_read` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}';";
		$read_result = $this->db->sql_fetch($sql);

		for ($i = 1; $i <=48; $i++)
		{
			$read_items[$i] = $read_result['ht_read'.$i];
		}

		$date_result = array();
		$date_result["today_count"] = $today_count;
		$date_result["start_date"] = $date_expire;
		$date_result["today_date"] = substr($datetime, 0, 10);
		$date_result["test_date"] = substr($test_date, 0, 4).'년 '.substr($test_date, 5, 2).'월 '.substr($test_date, 8, 2).'일';

		$data = array();
		$data["member_key"] = $key;
		$data["date_info"] = $date_result;
		$data["last_read_date"] = substr($read_result['ht_last_datetime'], 0, 10);
		$data["read_items"] = $read_items;
		$data["total_point"] = (string)$total_point;
		$data["num"] = $num;
		$data["point"] = $point;

		return $data;
	}

	public function harutoday_load_userdata($id, $key, $latest_pnum)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		$userdata_list = array();
		$sql = " SELECT * FROM `cfx_harutoday_userdata` WHERE `ht_part_no` = '{$part}' AND `member_key` = '{$key}' ORDER BY ht_pnum ASC LIMIT 0, {$latest_pnum} ;";

		$i = 0;
		$userdata_result = $this->db->sql_query($sql);
		while ($userdata_row = $this->db->sql_fetch_array($userdata_result))
		{	
			$userdata_list[$i] = $this->get_harutoday_userdata($i, $userdata_row);
			$i++;
		}

		$data = array();
		$data["member_key"] = $key;
		$data["userdata_count"] = $i;
		$data["userdata_items"] = $userdata_list;

		unset($userdata_list);

		return $data;
	}

	private function get_harutoday_userdata($num, $harutoday_userdata_row)
	{
		// 하루카드 정보
		$list = array();
		$list['num'] = $num + 1;
		$list['part_no'] = intval($harutoday_userdata_row['ht_part_no']);
		$list['pnum'] = intval($harutoday_userdata_row['ht_pnum']);
		$list['userdata'] = $harutoday_userdata_row['ht_userdata'];

		unset($harutoday_userdata_row);

		return $list;
	}

	public function harutoday_save_userdata($id, $key, $pcount, $userdata)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		$sql = " UPDATE `cfx_harutoday_userdata`
					SET  `ht_last_datetime` = '{$datetime}'
					    ,`ht_userdata` = '{$userdata}'
				 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}' AND `ht_pnum` = '{$pcount}';
				";

		$this->db->sql_query($sql);

		$data = array();
		$data["member_key"] = $key;
		$data["datetime"] = $datetime;
	}

	public function harutoday_select_gift($id, $key, $select_count, $select_num)
	{
		$datetime = CFX_TIME_YMDHIS;

		$sql = " SELECT * FROM `cfx_harutoday_join` WHERE member_key = '{$key}'; ";
		$member_result = $this->db->sql_fetch($sql);

		$part = $member_result['ht_current_part'];

		$sql = " UPDATE `cfx_harutoday_point`
					SET  `ht_gift_record{$select_count}` = '{$datetime}'
					    ,`ht_gift{$select_count}` = '{$select_num}'
				 WHERE `ht_part_no` = '{$part}' AND `member_id` = '{$id}' AND `member_key` = '{$key}';
				";

		$this->db->sql_query($sql);

		$sql = " INSERT INTO `cfx_gift`
					SET `gift_program` = '오늘하루'
					, `member_id` =  {$id}
					, `member_key` =  {$key}
					, `gift_count` =  {$select_count}
					, `gift_num` =  {$select_num}
					, `gift_created_datetime` = '{$datetime}'
				";

		$this->db->sql_query($sql);

		$data = array();
		$data["member_key"] = $key;
		$data["datetime"] = $datetime;
	}
}

?>