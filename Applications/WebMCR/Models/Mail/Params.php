<?php

namespace App\WebMCR\Models\Mail;

use Framework\Alonity\DI\DI;
use Framework\Components\Path;
use PHPMailer\PHPMailer\PHPMailer;

class Params {

	/**
	 * @param $value boolean
	 *
	 * @return $this
	*/
	public function setSMTP($value){
		if($value){
			$this->getInstance()->isSMTP();
			$this->getInstance()->SMTPAuth = true;
		}else{
			$this->getInstance()->SMTPAuth = false;
		}

		return $this;
	}

	/**
	 * @return boolean
	*/
	public function getSMTP(){
		return $this->getInstance()->SMTPAuth;
	}

	/**
	 * @param $value string
	 *
	 * @return $this
	 */
	public function setHost($value){
		$this->getInstance()->Host = $value;

		return $this;
	}

	/**
	 * @return string
	*/
	public function getHost(){
		return $this->getInstance()->Host;
	}

	/**
	 * @param $value string
	 *
	 * @return $this
	 */
	public function setUsername($value){
		$this->getInstance()->Username = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername(){
		return $this->getInstance()->Username;
	}

	/**
	 * @param $value string
	 *
	 * @return $this
	 */
	public function setPassword($value){
		$this->getInstance()->Password = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword(){
		return $this->getInstance()->Password;
	}

	/**
	 * @param $value string
	 *
	 * @return $this
	 */
	public function setSecure($value){
		$this->getInstance()->SMTPSecure = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSecure(){
		return $this->getInstance()->SMTPSecure;
	}

	/**
	 * @param $value integer
	 *
	 * @return $this
	 */
	public function setPort($value){
		$this->getInstance()->Port = intval($value);

		return $this;
	}

	/**
	 * @return integer
	 */
	public function getPort(){
		return $this->getInstance()->Port;
	}

	/**
	 * @param $email string
	 * @param $name string
	 *
	 * @return $this
	 */
	public function setFrom($email, $name=''){
		$this->getInstance()->setFrom($email, $name);

		return $this;
	}

	/**
	 * @param $code string
	 *
	 * @return $this
	*/
	public function setLanguage($code){
		$this->getInstance()->setLanguage($code, Path::app('/l10n/PHPMailer/'));

		return $this;
	}

	/**
	 * @param $charset string
	 *
	 * @return $this
	*/
	public function setCharset($charset){
		$this->getInstance()->CharSet = $charset;

		return $this;
	}

	/**
	 * @return string
	*/
	public function getCharset(){
		return $this->getInstance()->CharSet;
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
}

?>