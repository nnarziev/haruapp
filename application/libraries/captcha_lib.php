<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

include_once(CFX_PLUGINS_PHPCAPTCHA_PATH.'/CaptchaBuilderInterface.php');
include_once(CFX_PLUGINS_PHPCAPTCHA_PATH.'/PhraseBuilderInterface.php');
include_once(CFX_PLUGINS_PHPCAPTCHA_PATH.'/CaptchaBuilder.php');
include_once(CFX_PLUGINS_PHPCAPTCHA_PATH.'/PhraseBuilder.php');

class Captcha_lib
{
	private $common;

	private $is_captcha = FALSE;
	private $count = 0;

	public function __construct(){
		$this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->common = $CFX->common;

		$this->count = (int)$this->common->get_session("ss_captcha_count");
	}

	public function is_captcha()
	{
		if ($this->count >= CFX_LOGIN_MAX_ATTEMPTS) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function increase_count()
	{
		if ($this->count >= CFX_LOGIN_MAX_ATTEMPTS) {
			return FALSE;
		} else {	
			$this->count = (int)$this->common->get_session("ss_captcha_count");
			$this->common->set_session("ss_captcha_count", $this->count + 1);
			return TRUE;
		}
	}

	public function set_captcha()
	{
		$this->count = CFX_LOGIN_MAX_ATTEMPTS;
		$this->common->set_session("ss_captcha_count", $this->count);
	}

	public function create_captcha_key()
	{
		$builder = CaptchaBuilder::create()->build();
		$this->common->set_session('ss_captcha_key', $builder->getPhrase());

		header('Content-type: image/jpeg');
		$builder->output();
	}

	public function check_captcha_key($is_login = TRUE)
	{
		$captcha_key = $this->common->get_post_data('captcha_key', 6);

		if (!empty($captcha_key) && $this->common->get_session('ss_captcha_key') == $captcha_key)
		{
			$this->common->set_session('ss_captcha_count', FALSE);
			$this->common->set_session('ss_captcha_key', FALSE);
			$this->common->set_session('ss_captcha_save', FALSE);

			$captcha_file = $this->common->get_session('ss_captcha_file');

			if (file_exists(CFX_DATA_CACHE_PATH.DIRECTORY_SEPARATOR.$captcha_file))
			{
				@unlink(CFX_DATA_CACHE_PATH.DIRECTORY_SEPARATOR.$captcha_file);
			}

			$this->common->set_session('ss_captcha_file', FALSE);
			
			return TRUE;
		}
		else
		{
			if ($is_login)
				$this->increase_count();

			return FALSE;
		}
	}

	public function audio_captcha_key()
	{
		$number = $this->common->get_session("ss_captcha_key");

		if (empty($number))
			return;

		if ($number == $this->common->get_session("ss_captcha_save"))
			return;

		$mp3s = array();
		for ($i=0;$i<strlen($number);$i++)
		{
			$file = CFX_PLUGINS_PHPCAPTCHA_PATH.DIRECTORY_SEPARATOR.'mp3'.DIRECTORY_SEPARATOR.$number[$i].'.mp3';
			$mp3s[] = $file;
		}

		$ip = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
		$mp3_file = 'captcha-'.$ip.'_'.CFX_SERVER_TIME.'.mp3';

		$contents = '';
		foreach ($mp3s as $mp3) {
			$contents .= file_get_contents($mp3);
		}

		file_put_contents(CFX_DATA_CACHE_PATH.DIRECTORY_SEPARATOR.$mp3_file, $contents);

		$this->common->set_session('ss_captcha_file', $mp3_file);

		// 지난 캡챠 파일 삭제
		if (rand(0,99) == 0) {
			foreach (glob(CFX_DATA_CACHE_PATH.DIRECTORY_SEPARATOR.'captcha-*.mp3') as $file) {
				if (filemtime($file) + 86400 < CFX_SERVER_TIME) {
					@unlink($file);
				}
			}
		}

		$this->common->set_session("ss_captcha_save", $number);

		return CFX_DATA_CACHE_URL.DIRECTORY_SEPARATOR.$mp3_file;
	}
}
?>