<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class Captcha_Model extends CFX_Model
{
	public $captcha;

	public function __construct()
	{
        parent::__construct();

        $this->initialize();

		header('Content-Type: application/json; charset=utf-8', true);
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->captcha = $CFX->captcha;
	}

	// 캡챠
	public function captcha_image()
	{
		return json_encode($this->captcha->create_captcha_key());
	}

	// 캡챠 사운드
	public function captcha_audio()
	{
		return json_encode($this->captcha->audio_captcha_key());
	}
}

?>