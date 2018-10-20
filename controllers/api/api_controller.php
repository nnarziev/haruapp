<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Api_Controller extends CFX_Controller {

	function __construct(){
		parent::__construct();

		$this->initialize();
	}

	public function initialize()
	{
		// 관리자 컨트롤러 설정
		$this->load->library('Common_Uri', 'uri'); // 공통 URI 라이브러리

		$this->debug_mode = FALSE;
		header('Content-Type: application/json; charset=UTF-8', true);
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST, PUT');
		header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

		$this->load_router();

		return;
	}

	//-----------------------------------------------------------------------------------------
	// 액션에 대응하는 메소드
	//-----------------------------------------------------------------------------------------

	// 인덱스
	public function index()
	{
		return;
	}
 
	// 로그인
	public function login()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'login')
			{
				$member_id = $this->common->get_post_json_data('member_id', 50);
				$member_pw = $this->common->get_post_json_data('member_pw', 50);

				if (!empty($member_id) && !empty($member_pw))
				{
					$login_result = $this->auth->login($member_id, $member_pw);
					if ($login_result['result'] == FALSE)
					{
						// 비밀번호 오류
						echo $this->result_json('login', 'error', 'request', 'notexist');
						return;
					}
					else
					{
						echo $this->result_json('login', 'ok', 'request', $login_result['data']);
						return;
					}
				}
				else
				{
					echo $this->result_json('login', 'error', 'request', 'invalid');
					return;
				}

			}
			else
			{
				echo $this->result_json('login', 'error', 'request', 'invalid');
				return;		
			}

		} 
		
		echo $this->result_json('login', 'error', 'request', 'other');
		return;	
	}

	// harucard_info
	public function harucard_info()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_info')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);

				$app_name = $this->common->get_post_json_data('app_name', 50);
				$app_os = $this->common->get_post_json_data('app_os', 50);
				$app_version = $this->common->get_post_json_data('app_version', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_info($id, $key, $app_name, $app_os,$app_version);

				echo $this->result_json('harucard_info', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harucard_info', 'error', 'request', 'invalid');
				return;		
			}
		}

		// echo $this->get_today_count('2017-11-15');

		// $result = $this->api_model->harucard_info('test', 'member_5a0b67f8f1fde');

		echo $this->result_json('harucard_info', 'error', 'request', 'other');

		return;	
	}

	// harucard_history
	public function harucard_history()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_history')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_history($id, $key);

				echo $this->result_json('harucard_history', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harucard_history', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harucard_history', 'error', 'request', 'other');

		return;	
	}

	public function harucard_alarm()
	{	
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_alarm')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$rating = intval($this->common->get_post_json_data('rating', 50));

				// $this->load->model('Api'); // 모델 로드
				// $result = $this->api_model->harucard_rating($key);
				echo $this->result_json('harucard_alarm', 'ok', 'request', 'not_ready');
				return;
			}
			else
			{
				echo $this->result_json('harucard_alarm', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harucard_alarm', 'error', 'request', 'other');
		return;	
	}

	public function harucard_rating()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_rating')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$rating = intval($this->common->get_post_json_data('rating', 10));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_rating($id, $key, $rating);
				echo $this->result_json('harucard_rating', 'ok', 'request', $result);

				return;
			}
			else
			{
				echo $this->result_json('harucard_rating', 'error', 'request', 'invalid');

				return;		
			}
		}

		echo $this->result_json('harucard_rating', 'error', 'request', 'other');
		return;	
	}

	// harucard_list
	public function harucard_list()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_list')
			{
				$key = $this->common->get_post_json_data('key', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_list($key);

				echo $this->result_json('harucard_list', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harucard_list', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harucard_list', 'error', 'request', 'other');
		return;	
	}

	// harucard_read
	public function harucard_read()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_read')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$code = $this->common->get_post_json_data('code', 50);
				$num = intval($this->common->get_post_json_data('num', 50));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_read($id, $key, $code, $num);

				echo $this->result_json('harucard_read', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harucard_read', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harucard_read', 'error', 'request', 'other');
		return;	
	}

	// harucard_read
	public function harucard_bookmark()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_bookmark')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$code = $this->common->get_post_json_data('code', 50);
				$num = intval($this->common->get_post_json_data('num', 50));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_bookmark($id, $key, $code, $num);

				echo $this->result_json('harucard_bookmark', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harucard_bookmark', 'error', 'request', 'invalid');
				return;		
			}
		}
/*
				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_bookmark('1', 'test', 'member_5a0b67f8f1fde', 'mahc016', 16);

				echo $this->result_json('harucard_bookmark', 'ok', 'request', $result);
*/
		echo $this->result_json('harucard_bookmark', 'error', 'request', 'other');

		return;	
	}

	// harucard_select_gift
	public function harucard_select_gift()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harucard_select_gift')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$select_count = intval($this->common->get_post_json_data('select_count', 10));
				$select_num = intval($this->common->get_post_json_data('select_num', 10));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harucard_select_gift($id, $key, $select_count, $select_num);

				echo $this->result_json('harucard_select_gift', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harucard_select_gift', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harucard_select_gift', 'error', 'request', 'other');
		return;	
	}

	// harutoday_info
	public function harutoday_info()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_info')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);

				$app_name = $this->common->get_post_json_data('app_name', 50);
				$app_os = $this->common->get_post_json_data('app_os', 50);
				$app_version = $this->common->get_post_json_data('app_version', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_info($id, $key, $app_name, $app_os,$app_version);

				echo $this->result_json('harutoday_info', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_info', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harutoday_info', 'error', 'request', 'other');

		return;	
	}

	// harutoday_history
	public function harutoday_history()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_history')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_history($id, $key);

				echo $this->result_json('harutoday_history', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_history', 'error', 'request', 'invalid');
				return;		
			}
		}
/*
		$this->load->model('Api'); // 모델 로드
		$result = $this->api_model->harutoday_history('test2', 'member_5a66d8217d3f8');

		echo $this->result_json('harutoday_history', 'ok', 'request', $result);
*/

		echo $this->result_json('harutoday_history', 'error', 'request', 'other');

		return;	
	}

	// harutoday_menu
	public function harutoday_menu()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_menu')
			{
				$mcode = $this->common->get_post_json_data('mcode', 50);
				$key = $this->common->get_post_json_data('key', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_menu($mcode, $key);

				echo $this->result_json('harutoday_menu', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_menu', 'error', 'request', 'invalid');
				return;		
			}
		}
/*
		$this->load->model('Api'); // 모델 로드
		$result = $this->api_model->harutoday_menu('mbht', 'member_5a0b67f8f1fde');
		echo $this->result_json('harutoday_menu', 'ok', 'request', $result);
*/
		echo $this->result_json('harutoday_menu', 'error', 'request', 'other');
		return;
	}

	// harutoday_data_list
	public function harutoday_data_list()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_data_list')
			{
				$mcode = $this->common->get_post_json_data('mcode', 10);
				$zcode = $this->common->get_post_json_data('zcode', 10);
				$pcode = $this->common->get_post_json_data('pcode', 10);
				$part_no = intval($this->common->get_post_json_data('part_no', 10));
				$pnum = intval($this->common->get_post_json_data('pnum', 10));
				$key = $this->common->get_post_json_data('key', 50);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_data_list($mcode, $zcode, $pcode, $key, $part_no, $pnum);

				echo $this->result_json('harutoday_data_list', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_data_list', 'error', 'request', 'invalid');
				return;		
			}
		}
/*
		$this->load->model('Api'); // 모델 로드
		$result = $this->api_model->harutoday_data_list('mdht', 'z3', 'p27', 'member_5a6ef701b4dcc', 3, 27);
		echo $this->result_json('harutoday_data_list', 'ok', 'request', $result);
*/
		echo $this->result_json('harutoday_data_list', 'error', 'request', 'other');
		return;
	}

	public function harutoday_rating()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_rating')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$pcount = intval($this->common->get_post_json_data('pcount', 10));
				$rating = intval($this->common->get_post_json_data('rating', 10));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_rating($id, $key, $pcount, $rating);
				echo $this->result_json('harutoday_rating', 'ok', 'request', $result);

				return;
			}
			else
			{
				echo $this->result_json('harutoday_rating', 'error', 'request', 'invalid');

				return;		
			}
		}

		echo $this->result_json('harutoday_rating', 'error', 'request', 'other');
		return;	
	}

	// harutoday_read
	public function harutoday_read()
	{

		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_read')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$code = $this->common->get_post_json_data('code', 50);
				$num = intval($this->common->get_post_json_data('num', 50));
				$quizpoint = intval($this->common->get_post_json_data('quizpoint', 50));
				$zone_num = intval($this->common->get_post_json_data('zone_num', 50));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_read($id, $key, $code, $num, $quizpoint, $zone_num);
				echo $this->result_json('harutoday_read', 'ok', 'request', $result);

				return;
			}
			else
			{
				echo $this->result_json('harutoday_read', 'error', 'request', 'invalid');
				return;		
			}
		}
/*
		$this->load->model('Api'); // 모델 로드
		$result = $this->api_model->harutoday_read('test2', 'member_5a66d8217d3f8', 'mcht', 38, 0, 4);
		echo $this->result_json('harutoday_read', 'ok', 'request', $result);
*/
		echo $this->result_json('harutoday_read', 'error', 'request', 'other');
		return;	
	}

	// harutoday_load_userdata
	public function harutoday_load_userdata()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_load_userdata')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$latest_pnum = intval($this->common->get_post_json_data('latest_pnum', 50));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_load_userdata($id, $key, $latest_pnum);

				echo $this->result_json('harutoday_load_userdata', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_load_userdata', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harutoday_load_userdata', 'error', 'request', 'other');
		return;	
	}

	// harutoday_save_userdata
	public function harutoday_save_userdata()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_save_userdata')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$pcount = intval($this->common->get_post_json_data('pcount', 10));
				$userdata = $this->common->get_post_json_data('userdata', 8000);

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_save_userdata($id, $key, $pcount, $userdata);

				echo $this->result_json('harutoday_save_userdata', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_save_userdata', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harutoday_save_userdata', 'error', 'request', 'other');
		return;	
	}

	// harutoday_select_gift
	public function harutoday_select_gift()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$mode = $this->common->get_post_json_data('mode', 50);

			if (!empty($mode) && $mode == 'harutoday_select_gift')
			{
				$id = $this->common->get_post_json_data('id', 50);
				$key = $this->common->get_post_json_data('key', 50);
				$select_count = intval($this->common->get_post_json_data('select_count', 10));
				$select_num = intval($this->common->get_post_json_data('select_num', 10));

				$this->load->model('Api'); // 모델 로드
				$result = $this->api_model->harutoday_select_gift($id, $key, $select_count, $select_num);

				echo $this->result_json('harutoday_select_gift', 'ok', 'request', $result);
				return;
			}
			else
			{
				echo $this->result_json('harutoday_select_gift', 'error', 'request', 'invalid');
				return;		
			}
		}

		echo $this->result_json('harutoday_select_gift', 'error', 'request', 'other');
		return;	
	}

	private function result_json($mode, $code, $type, $result)
	{
		return json_encode(array('mode' => $mode, 'code' => $code, 'type' => $type, 'result' => $result));
	}

	//-----------------------------------------------------------------------------------------

	// 라우터 로드
	public function load_router()
	{
		parent::load_router();

		// 메소드 찾기
		$method = 'index';
		if (isset($this->routes[1]) && $this->routes[1]) {
			$method = preg_replace('/[^a-z0-9_]/i', '', trim($this->routes[1]));
			$method = strtolower(substr($method, 0, 50));
		} else {
			$method = 'index';
		}

		if (!method_exists($this, $method))
			$method = 'index';

		// 타겟 찾기
		$target = '';
		if (isset($this->routes[2]) && $this->routes[2]) {
			$target = preg_replace('/[^a-z0-9_]/i', '', trim($this->routes[2]));
			$target = strtolower(substr($target, 0, 50));
		} else {
			$target = '';
		}

		// 라우터에 현재 메소드 값을 설정
		$this->router->method = $method;
		$this->router->target = $target;

		// 링크 설정 로드
		$this->set_link_href();

		// 모델 패스 설정
		$this->router->set_model_path(CFX_MODELS_PATH);

		// 뷰 패스 값을 설정
		$this->router->set_view_path(CFX_VIEWS_PATH);

		// 해당 메소드를 호출
		if (method_exists($this, $method))
			$this->$method();
		else
			$this->router->redirect_link_href('login');
	}

	// 링크 설정 로드
	private function set_link_href()
	{
		$link_href = array();

		$class = strtolower($this->router->class);
		$method = strtolower($this->router->method);
		$target = strtolower($this->router->target);

		$link_href['index']	= CFX_URL.DIRECTORY_SEPARATOR.$class;
		$link_href['login']	= CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'login';

		// 라우터에 링크 설정 값을 설정
		$this->router->set_link_href($link_href);

		return TRUE;
	}
}

?>