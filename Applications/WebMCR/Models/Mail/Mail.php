<?php

namespace App\WebMCR\Models\Mail;

use Framework\Alonity\DI\DI;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {

	/**
	 * @return Send()
	 */
	public static function send(){
		return new Send();
	}

	/**
	 * @return Params()
	 */
	public static function params(){
		if(DI::has('MailParams')){
			return DI::get('MailParams');
		}

		return DI::set('MailParams', new Params());
	}

	/**
	 * @return PHPMailer()
	*/
	public function getInstance(){
		if(DI::has('Mail')){
			return DI::get('Mail');
		}

		return DI::set('Mail', new PHPMailer());
	}
}

?>