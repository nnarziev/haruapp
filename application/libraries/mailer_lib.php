<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

include_once(CFX_PLUGINS_PHPMAILER_PATH.'/PHPMailerAutoload.php');

class Mailer_lib {

	public $config;

	public $smtpsecure = '';
	public $host = '';
	public $port = '';
	public $username = '';
	public $password = '';

	public function __construct()
	{
        $this->initialize();
	}

	public function initialize()
	{
		$CFX =& get_instance();

		$this->config = $CFX->config;

		$this->smtpsecure = $this->config->view['smtp_secure'];
		$this->host = $this->config->view['smtp_host'];
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$this->port = $this->config->view['smtp_port'];
		$this->username = $this->config->view['smtp_username'];
		$this->password = $this->config->view['smtp_password'];
	}

	// 메일 발송
	public function send_mail($mailfrom, $mailfromname, $mailto, $mailtoname, $subject, $content)
	{
		$mail = new PHPMailer();

		$mail->IsSMTP(); // telling the class to use SMTP
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 2;
		//Ask for HTML-friendly debug output
		// $mail->Debugoutput = 'html';

		$mail->CharSet = "utf-8"; // 언어셋 설정
		$mail->Encoding = "base64"; // 인코딩 방법 정의

		$mail->SMTPAuth = true; // enable SMTP authentication
		$mail->SMTPSecure = $this->smtpsecure; // sets the prefix to the servier
		//Set the hostname of the mail server
		$mail->Host = $this->host;
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = $this->port;
		$mail->Username = $this->username;
		$mail->Password = $this->password;

		$mail->SetFrom($this->username, $mailfromname);

		$mail->AddReplyTo($mailfrom, $mailfromname);

		$mail->Subject = $subject;

		$mail->MsgHTML($content);

		$mail->AddAddress($mailto, $mailtoname);

		if (!$mail->Send()) {
			alert("Mailer Error: " . $mail->ErrorInfo);
		}

		return;
	}
}

?>