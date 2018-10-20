<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Harutoday_Controller extends CFX_Controller {

	public function __construct(){
		parent::__construct();

		$this->initialize();
	}

	public function initialize()
	{
		$this->load->library('Member_Uri', 'uri'); // 멤버 URI 라이브러리
		$this->load->library('Member', 'member'); // 멤버 라이브러리
		$this->load->library('Profile', 'profile'); // 프로필 라이브러리

		$this->load_router();

		return;
	}

	//-----------------------------------------------------------------------------------------
	// 액션에 대응하는 메소드
	//-----------------------------------------------------------------------------------------

	// 인덱스
	public function index()
	{
		$this->router->redirect_link_href('lists');

		return;
	}

	// 리스트 조회
	public function lists()
	{
		$this->set_title('오늘하루'); // 타이틀 설정

		$this->load->model('harutoday'); // 모델 로드
		
		$result = array();
		$result = $this->harutoday_model->get_lists();	// 메소드 호출

		$view_data = array();
		$view_data['list'] = $result;
		unset($result);

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = [];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME, '/adm/assets/css/common.css?v='.CFX_SERVER_TIME];

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('Lists', 'base', '', $view_data); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드

		return;
	}

	// 내용 조회
	public function view()
	{
		$this->set_title('오늘하루'); // 타이틀 설정

		$this->load->model('harutoday'); // 모델 로드
		
		$result = array();
		$result = $this->harutoday_model->get_view();	// 메소드 호출

		$view_data = array();
		$view_data['view'] = $result;
		unset($result);

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = [];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME, '/adm/assets/css/common.css?v='.CFX_SERVER_TIME];

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('View', 'base', '', $view_data); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드

		return;
	}

	// 수정
	public function update()
	{
		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 하루카드 업데이트 처리
			$this->load->model('harutoday'); // 모델 로드			
			$result = $this->harutoday_model->post_update();	// 메소드 호출

 			if ($result['menu'] == 'update_harutoday_join_member')
			{
				if ($result['code'] == 'redirect')
				{			
					$this->router->redirect_link_href('view', DIRECTORY_SEPARATOR.$result['field']);
				}

				return;
			}

		} else {
			$this->set_title('오늘하루'); // 타이틀 설정

			$this->load->model('harutoday'); // 모델 로드

			$result = array();
			$result = $this->harutoday_model->get_update();	// 메소드 호출

			$view_data = array();
			$view_data['update'] = $result;
			unset($result);

			// 뷰 데이터 설정
			$header_data = array();
			$header_data['javascripts'] = [];
			$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME, '/adm/assets/css/common.css?v='.CFX_SERVER_TIME];

			$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
			$this->load->view('Update', 'base', '', $view_data); // 뷰 로드
			$this->load->common_view('page-home-footer'); // 하단 로드
		}
	}

	//-----------------------------------------------------------------------------------------

	// 관리자 로그인 상태 체크
	public function login_admin_check()
	{
		// 관리자가 아니라면 이동
		if ($this->auth->is_admin() === FALSE && $this->auth->is_manager() === FALSE) {
			$this->router->redirect(CFX_URL);
			return;
		}
	}

	// 라우터 로드
	public function load_router()
	{
		parent::load_router();

		// 메소드 찾기
		if (isset($this->routes[1]) && $this->routes[1]) {
			$method = preg_replace('/[^a-z0-9_]/i', '', trim($this->routes[1]));
			$method = strtolower(substr($method, 0, 20));
		} else {
			$method = 'index';
		}

		if (!method_exists($this, $method))
			$method = 'index';

		// 회원 member_no 값
		$idx = 0;
		if (isset($this->routes[2]) && $this->routes[2] && is_numeric(trim($this->routes[2])) ) {
			$idx = (int)$this->routes[2];
		}

		// 라우터에 현재 메소드 값을 설정
		$this->router->method = $method;
		$this->router->idx = $idx;

		// 관리자 로그인 상태 체크
		$this->login_admin_check();

		// 링크 설정 로드
		$this->set_link_href();

		// 모델 패스 설정
		$this->router->set_model_path(CFX_ADMIN_MODELS_PATH);

		// 뷰 패스 값을 설정
		$this->router->set_view_path(CFX_ADMIN_VIEWS_PATH);

		// 해당 메소드를 호출
		if (method_exists($this, $method))
			$this->$method();
		else
			$this->router->redirect_link_href('error404');
	}

	// 링크 설정 로드
	public function set_link_href()
	{
		$link_href = array();

		$class = strtolower($this->router->class);
		$method = strtolower($this->router->method);
		$idx = strtolower($this->router->idx);

		$link_href['lists']	= CFX_ADMIN_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'lists';
		$link_href['view'] = CFX_ADMIN_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'view';
		$link_href['update'] = CFX_ADMIN_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'update'.DIRECTORY_SEPARATOR.$idx;

		// 라우터에 링크 설정 값을 설정
		$this->router->set_link_href($link_href);

		return TRUE;
	}
}

?>