<?php

namespace App\WebMCR\Models\Mail;

use Framework\Alonity\DI\DI;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Send {
	private $message = '^_^';
	private $subject = 'WebMCR';
	private $addresses = [];
	private $reply = [];
	private $attach = [];
	private $error = null;

	/**
	 * @param $message string
	 *
	 * @return $this
	 */
	public function message($message){
		$this->message = $message;

		return $this;
	}

	/**
	 * @param $subject string
	 *
	 * @return $this
	 */
	public function subject($subject){
		$this->subject = $subject;

		return $this;
	}

	/**
	 * @param $email string
	 * @param $name string|null
	 *
	 * @return $this
	 */
	public function address($email, $name=''){
		$this->addresses[$email] = $name;

		return $this;
	}

	/**
	 * @param $email string
	 * @param $name string|null
	 *
	 * @return $this
	 */
	public function reply($email, $name=''){
		$this->reply[$email] = $name;

		return $this;
	}

	/**
	 * @param $filename string
	 * @param $name string|null
	 *
	 * @return $this
	 */
	public function attach($filename, $name=''){
		$this->attach[$filename] = $name;

		return $this;
	}

	/**
	 * @return PHPMailer()
	 */
	private function getInstance(){
		if(DI::has('Mail')){
			return DI::get('Mail');
		}

		return DI::set('Mail', new PHPMailer());
	}

	public function getError(){
		return $this->error;
	}

	/**
	 * @return boolean
	*/
	public function execute(){
		$mail = $this->getInstance();

		if(empty($this->addresses)){
			$this->error = 'Addresses is not set';
			return false;
		}

		foreach($this->addresses as $k => $v){
			$mail->addAddress($k, $v);
		}

		if(!empty($this->reply)){
			foreach($this->reply as $k => $v){
				$mail->addReplyTo($k, $v);
			}
		}

		if(!empty($this->attach)){
			foreach($this->attach as $k => $v){
				$mail->addAttachment($k, $v);
			}
		}

		$mail->isHTML(true);

		$mail->Subject = $this->subject;
		$mail->Body = $this->message;
		$mail->AltBody = strip_tags($this->message);

		try {
			$mail->send();
		}catch (Exception $e){
			$this->error = $e;

			return false;
		}

		return true;
	}
}

?>