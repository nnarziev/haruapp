<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Haru_Controller extends CFX_Controller {

	function __construct(){
		parent::__construct();

		$this->initialize();
	}

	public function initialize()
	{
		// 관리자 컨트롤러 설정
		$this->load->library('Common_Uri', 'uri'); // 공통 URI 라이브러리

		$this->load_router();

		return;
	}

	//-----------------------------------------------------------------------------------------
	// 액션에 대응하는 메소드
	//-----------------------------------------------------------------------------------------

	// 인덱스
	public function harucard()
	{
		$this->set_title('하루카드'); // 타이틀 설정

		$code = $this->common->get_data('code', 7);
		$idx = $this->common->get_data('idx', 3);

		$this->load->model('harucard'); // 모델 로드
		$result = $this->harucard_model->get_harucard_view($code, $idx);	// 메소드 호출

		$view_data = array();
		$view_data['harucard_view'] = $result;
		unset($result);

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = [];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME, '/assets/css/harucard.css'];

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('harucard', 'base', '', $view_data); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드
		return;
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
		}

		// 타겟 찾기
		$target = '';
		if (isset($this->routes[2]) && $this->routes[2]) {
			$target = preg_replace('/[^a-z0-9_]/i', '', trim($this->routes[2]));
			$target = strtolower(substr($target, 0, 50));
		}

		// 라우팅 규칙에 class가 없으면 디폴트로 home 설정함.
		if (empty($this->router->class))
			$this->router->class = 'home';

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
			$this->error404();
	}

	// 링크 설정 로드
	public function set_link_href()
	{
		$link_href = array();

		$class = strtolower($this->router->class);
		$method = strtolower($this->router->method);
		$target = strtolower($this->router->target);

		$link_href['index']	= CFX_URL.DIRECTORY_SEPARATOR.$class;
		$link_href['harucard']	= CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'harucard';

		// 라우터에 링크 설정 값을 설정
		$this->router->set_link_href($link_href);

		return TRUE;
	}
}

?>
