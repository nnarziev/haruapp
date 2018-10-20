<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Auth_Controller extends CFX_Controller {

	function __construct(){
		parent::__construct();

		$this->initialize();
	}

	public function initialize()
	{
		// 관리자 컨트롤러 설정
		$this->load->library('Common_Uri', 'uri'); // 공통 URI 라이브러리

		// 캡챠 라이브러리 로드
		$this->load->library('Captcha', 'captcha'); // 캡챠 라이브러리

		// 회원 프로필 라이브러리 로드
		$this->load->library('Profile', 'profile'); // 회원 프로필 라이브러리

		$this->load_router();

		return;
	}

	//-----------------------------------------------------------------------------------------
	// 액션에 대응하는 메소드
	//-----------------------------------------------------------------------------------------

	// 인덱스
	public function index()
	{
		$this->router->redirect(CFX_URL);
		return;
	}
 
	// 로그인 & 처리
	public function login()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 로그인 처리
			$this->load->model('Auth'); // 모델 로드			
			$result = $this->auth_model->post_login();	// 메소드 호출
			$this->_show_message($result);

			if ($result['menu'] == 'login')
			{
				if ($result['code'] == 'redirect')
				{
					$this->common->clear_session('auth_message');

					if ($this->auth->is_admin() === TRUE || $this->auth->is_manager() === TRUE)
					{
						$this->router->redirect(CFX_ADMIN_URL);
						return;
					} else {
						$this->router->redirect_link_href('moduletest');
						// $this->router->redirect($result['result']);
						return;
					}
				}
			}

			$this->router->redirect_link_href('login');
			return;
		}
		else
		{
			// 세션이 존재하면 페이지 이동
			if ($this->auth->is_member() === TRUE)
			{
				$url = $this->uri->URL();

				if ( !empty($url) )
				{
					// url 체크
					check_url_host($url);

					$link = $this->common->get_post_url_link($url);

					$this->router->redirect($link);
					return;
				}
				else
				{
					$this->router->redirect(CFX_REDIRECT_URL);
					return;
				}
			}
			else
			{
				$this->login_form();
			}
		}

		return;
	}

	public function login_form()
	{
		// 로그인 폼
		$this->set_title('하루 (Haru) 로그인'); // 타이틀 설정

		// 캡챠 상태 확인
		// $this->captcha->is_captcha();

		// 토큰생성
		$this->uri->token = $this->common->get_csrf_token('login');				

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = ['/assets/lib/login.js?v='.CFX_SERVER_TIME, '/assets/lib/captcha.js'];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME];

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('login'); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드
	}

	// 로그아웃
	public function logout()
	{
		// 로그아웃을 처리함
		$this->auth->logout();

		$url = $this->uri->URL();

		if ( !empty($url) )
		{
			// url 체크
			check_url_host($url);

			$link = $this->common->get_post_url_link($url);

			$this->router->redirect($link);
			return;
		} else {
			$this->router->redirect(CFX_REDIRECT_URL);
			return;
		}

		return;
	}

	// 회원가입 & 처리
	public function regist()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 회원가입 처리
			$this->load->model('Auth'); // 모델 로드			
			$result = $this->auth_model->post_regist();	// 메소드 호출
			$this->_show_message($result);

 			if ($result['menu'] == 'regist')
			{
				if ($result['code'] == 'redirect')
				{
					$this->common->clear_session('auth_message');					
					$this->router->redirect_link_href('welcome');
				}
				else
				{
					$this->router->redirect_link_href('regist');
				}

				return;
			}
		}
		else
		{
			if ($this->auth->is_member() === TRUE)
			{
				// 회원 세션이 존재하면 페이지 이동
				$url = $this->uri->URL();

				if ( !empty($url) )
				{
					// url 체크
					check_url_host($url);

					$link = $this->common->get_post_url_link($url);

					$this->router->redirect($link);
					return;
				}
				else
				{
					$this->router->redirect(CFX_REDIRECT_URL);
					return;
				}
			}
			else
			{
				$this->regist_form();
			}
		}

		return;
	}

	public function regist_form()
	{
		// 회원가입 폼
		$this->set_title('하루 (Haru) 회원가입'); // 타이틀 설정

		// 캡챠 상태 확인
		// $this->captcha->is_captcha();

		// 토큰생성
		$this->uri->token = $this->common->get_csrf_token('regist');				

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = ['/assets/lib/regist.js?v='.CFX_SERVER_TIME, '/assets/lib/captcha.js'];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME];

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('regist'); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드
	}

	public function welcome()
	{
		// 회원가입 폼
		$this->set_title('하루 (Haru) 회원가입 완료'); // 타이틀 설정

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = ['/assets/lib/captcha.js'];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME];

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('welcome'); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드
	}

	// 중복 아이디 체크
	public function dup_idchk()
	{
		$this->debug_mode = FALSE;

		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 중복 아이디 처리
			$this->load->model('Auth'); // 모델 로드			
			$result = $this->auth_model->dup_idchk();	// 메소드 호출
		} else {
			$result = json_encode(TRUE);
		}

		echo $result;
		return;
	}

	// 중복 이메일 체크
	public function dup_emailchk()
	{
		$this->debug_mode = FALSE;

		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 중복 이메일 처리
			$this->load->model('Auth'); // 모델 로드			
			$result = $this->auth_model->dup_emailchk();	// 메소드 호출
		} else {
			$result = json_encode(TRUE);
		}

		echo $result;
		return;
	}

	// 중복 휴대전화 체크
	public function dup_hpchk()
	{
		$this->debug_mode = FALSE;

		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 중복 아이디 처리
			$this->load->model('Auth'); // 모델 로드			
			$result = $this->auth_model->dup_hpchk();	// 메소드 호출
		} else {
			$result = json_encode(TRUE);
		}

		echo $result;
		return;
	}

	// 캡챠 이미지
	public function captcha_image()
	{
		$this->debug_mode = FALSE;

		$this->load->model('Captcha'); // 모델 로드

		$result = $this->captcha_model->captcha_image(); // 메소드 호출

		echo $result;

		return;
	}

	// 캡챠 오디오
	public function captcha_audio()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$this->debug_mode = FALSE;

			$this->load->model('Captcha'); // 모델 로드

			$result = $this->captcha_model->captcha_audio(); // 메소드 호출

			echo $result;
		}

		return;
	}


	//-----------------------------------------------------------------------------------------

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	protected function _show_message($message)
	{
		$this->common->set_session('auth_message', $message);
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
		$link_href['logout'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'logout';
		$link_href['regist'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'regist';
		$link_href['welcome'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'welcome';
		$link_href['moduletest'] = CFX_URL.DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'moduletest';
		$link_href['captcha_image'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'captcha_image';

		// 라우터에 링크 설정 값을 설정
		$this->router->set_link_href($link_href);

		return TRUE;
	}
}

?>