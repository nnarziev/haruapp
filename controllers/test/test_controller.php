<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Test_Controller extends CFX_Controller {

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
	public function index()
	{
		$this->moduletest();

		return;
	}

	// 하루 (Haru) 시작하기 모듈검사
	public function moduletest()
	{
		// 인증패스 로그인 페이지로
		$this->require_auth_pass(TRUE);

		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 하루 (Haru) 시작하기 모듈검사 처리
			$this->load->model('Moduletest'); // 모델 로드			
			$result = $this->moduletest_model->post_moduletest();	// 메소드 호출

			if ($result == 'Y') {
				$this->router->redirect_link_href('moduletest_result');
			} else {
				$this->router->redirect_link_href('moduletest');
			}
		}
		else
		{
			$this->moduletest_form();
		}

		return;
	}

	// 하루 (Haru) 시작하기 모듈검사
	public function moduletest_form()
	{
		$this->set_title('하루 (Haru) 시작하기 모듈검사'); // 타이틀 설정

		$this->load->model('Moduletest'); // 모델 로드

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = ['/assets/lib/jquery.barrating.js'];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME, '/assets/lib/bars-1to10.css'];

		$view_data = array();
		$result = $this->moduletest_model->moduletest(); // 메소드 호출
		$view_data['mtq_case'] = $result['mtq_case'];
		$view_data['mta_case'] = $result['mta_case'];
		unset($result);

		if ($view_data['mta_case']['mta_sent'] == 'Y') {
			$this->router->redirect_link_href('moduletest_result');
			return;
		}

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('moduletest', 'base', '', $view_data); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드
	}

	// 하루 (Haru) 시작하기 모듈검사 결과
	public function moduletest_result()
	{
		// 인증패스 로그인 페이지로
		$this->require_auth_pass(TRUE);

		$this->set_title('하루 (Haru) 시작하기 모듈검사 결과'); // 타이틀 설정

		$this->load->model('Moduletest'); // 모델 로드

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = ['/assets/lib/tempgauge/jquery.tempgauge.js'];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME];

		$view_data = array();
		$result = $this->moduletest_model->moduletest(); // 메소드 호출
		$view_data['mta_case'] = $result['mta_case'];
		unset($result);

		if (intval($view_data['mta_case']['mta_point1']) > intval($view_data['mta_case']['mta_point2']) * 2 AND intval($view_data['mta_case']['mta_point1']) > intval($view_data['mta_case']['mta_point3']) * 2 )
		{
			$view_data['mta_target'] = "우울/불안";
			$view_data['mta_code'] = "1";
		}
		elseif (intval($view_data['mta_case']['mta_point2']) * 2 > intval($view_data['mta_case']['mta_point1']) AND intval($view_data['mta_case']['mta_point2']) > intval($view_data['mta_case']['mta_point3']) )
		{
			$view_data['mta_target'] = "수면";
			$view_data['mta_code'] = "2";
		}
		elseif (intval($view_data['mta_case']['mta_point3']) * 2 > intval($view_data['mta_case']['mta_point1']) AND intval($view_data['mta_case']['mta_point3']) > intval($view_data['mta_case']['mta_point2']) )
		{
			$view_data['mta_target'] = "통증";
			$view_data['mta_code'] = "3";
		}
		else
		{
			$view_data['mta_target'] = "동일점수 비교불가";
			$view_data['mta_code'] = "0";
		}

		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('moduletest_result', 'base', '', $view_data); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드

		return;
	}

	// 하루 (Haru) 심각도검사 (우울/불안)
	public function severitytest1()
	{
		// 인증패스 로그인 페이지로
		$this->require_auth_pass(TRUE);

		// POST 상태 여부를 체크함
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			// 하루 (Haru) 심각도검사 (우울/불안) 처리
			$this->load->model('Severitytest'); // 모델 로드			
			$result = $this->severitytest_model->post_severitytest1();	// 메소드 호출

			if ($result == 'Y') {
				$this->router->redirect_link_href('severitytest1_result');
			} else {
				$this->router->redirect_link_href('severitytest1');
			}
		}
		else
		{
			$this->set_title('하루 (Haru) 심각도검사'); // 타이틀 설정

			$this->load->model('Severitytest'); // 모델 로드

			// 뷰 데이터 설정
			$header_data = array();
			$header_data['javascripts'] = ['/assets/lib/slider-pips/jquery-ui-slider-pips.js'];
			$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME, '/assets/lib/slider-pips/jquery-ui-slider-pips.css'];

			$view_data = array();
			$result = $this->severitytest_model->severitytest1(); // 메소드 호출
			$view_data['stq1_case'] = $result['stq1_case'];
			$view_data['sta1_case'] = $result['sta1_case'];
			unset($result);

			if ($view_data['sta1_case']['sta1_sent'] == 'Y') {
				$this->router->redirect_link_href('severitytest1_result');
				return;
			}

			$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
			$this->load->view('severitytest1', 'base', '', $view_data); // 뷰 로드
			$this->load->common_view('page-home-footer'); // 하단 로드
		}

		return;
	}

	// 하루 (Haru) 심각도검사 결과 (우울/불안)
	public function severitytest1_result()
	{
		// 인증패스 로그인 페이지로
		$this->require_auth_pass(TRUE);

		$this->set_title('하루 (Haru) 심각도검사 결과'); // 타이틀 설정

		$this->load->model('Severitytest'); // 모델 로드

		// 뷰 데이터 설정
		$header_data = array();
		$header_data['javascripts'] = [];
		$header_data['stylesheets'] = ['/assets/css/custom.css?v='.CFX_SERVER_TIME];

		$view_data = array();
		$result = $this->severitytest_model->severitytest1(); // 메소드 호출
		$view_data['sta1_case'] = $result['sta1_case'];
		unset($result);

		if (intval($view_data['sta1_case']['sta1_point1']) <= 5)
		{
			$view_data['sta1_code1'] = "우울하지 않음";
			$view_data['sta1_progressbar1'] = "progress-bar-info";
			$view_data['sta1_label1'] = "label-info";
		}
		elseif (intval($view_data['sta1_case']['sta1_point1']) > 5 AND intval($view_data['sta1_case']['sta1_point1']) <= 10)
		{
			$view_data['sta1_code1'] = "가벼운 우울";
			$view_data['sta1_progressbar1'] = "progress-bar-default";
			$view_data['sta1_label1'] = "label-default";
		}
		elseif (intval($view_data['sta1_case']['sta1_point1']) > 10 AND intval($view_data['sta1_case']['sta1_point1']) <= 15)
		{
			$view_data['sta1_code1'] = "중간 정도 우울";
			$view_data['sta1_progressbar1'] = "progress-bar-success";
			$view_data['sta1_label1'] = "label-success";
		}
		elseif (intval($view_data['sta1_case']['sta1_point1']) > 15 AND intval($view_data['sta1_case']['sta1_point1']) <= 20)
		{
			$view_data['sta1_code1'] = "약간 심각한 우울";
			$view_data['sta1_progressbar1'] = "progress-bar-warning";
			$view_data['sta1_label1'] = "label-warning";
		}
		elseif (intval($view_data['sta1_case']['sta1_point1']) > 20)
		{
			$view_data['sta1_code1'] = "심각한 우울";
			$view_data['sta1_progressbar1'] = "progress-bar-danger";
			$view_data['sta1_label1'] = "label-danger";
		}

		if (intval($view_data['sta1_case']['sta1_point2']) <= 5)
		{
			$view_data['sta1_code2'] = "우울하지 않음";
			$view_data['sta1_progressbar2'] = "progress-bar-info";
			$view_data['sta1_label2'] = "label-info";
		}
		elseif (intval($view_data['sta1_case']['sta1_point2']) > 5 AND intval($view_data['sta1_case']['sta1_point2']) <= 10)
		{
			$view_data['sta1_code2'] = "가벼운 우울";
			$view_data['sta1_progressbar2'] = "progress-bar-default";
			$view_data['sta1_label2'] = "label-default";
		}
		elseif (intval($view_data['sta1_case']['sta1_point2']) > 10 AND intval($view_data['sta1_case']['sta1_point2']) <= 15)
		{
			$view_data['sta1_code2'] = "중간 정도 우울";
			$view_data['sta1_progressbar2'] = "progress-bar-success";
			$view_data['sta1_label2'] = "label-success";
		}
		elseif (intval($view_data['sta1_case']['sta1_point2']) > 20)
		{
			$view_data['sta1_code2'] = "심각한 우울";
			$view_data['sta1_progressbar2'] = "progress-bar-danger";
			$view_data['sta1_label2'] = "label-danger";
		}

		if (intval($view_data['sta1_case']['sta1_point1']) > 10 OR intval($view_data['sta1_case']['sta1_point2']) > 10 )
		{
			$view_data['sta1_target'] = "오늘하루";
		} else {
			$view_data['sta1_target'] = "하루카드";
		}


		$this->load->common_view('page-home-header', 'base', $header_data); // 상단 로드
		$this->load->view('severitytest1_result', 'base', '', $view_data); // 뷰 로드
		$this->load->common_view('page-home-footer'); // 하단 로드

		return;
	}

	// 인증패스 요청
	public function require_auth_pass($auth_pass=TRUE)
	{
		if ($this->auth->is_member() === FALSE) {
			if ($auth_pass === TRUE) {
				$this->router->redirect(CFX_LOGIN_URL);
				return;
			}
		} else {
			if ($auth_pass === FALSE) {
				$this->router->redirect(CFX_URL);				
				return;
			}
		}
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
			$this->router->redirect_link_href('moduletest');
	}

	// 링크 설정 로드
	private function set_link_href()
	{
		$link_href = array();

		$class = strtolower($this->router->class);
		$method = strtolower($this->router->method);
		$target = strtolower($this->router->target);

		$link_href['index']	= CFX_URL.DIRECTORY_SEPARATOR.$class;
		$link_href['moduletest'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'moduletest';
		$link_href['moduletest_result'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'moduletest_result';
		$link_href['severitytest1'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'severitytest1';
		$link_href['severitytest1_result'] = CFX_URL.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.'severitytest1_result';

		// 라우터에 링크 설정 값을 설정
		$this->router->set_link_href($link_href);

		return TRUE;
	}
}

?>
