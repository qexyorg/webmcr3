<?php

namespace App\WebMCR\Controllers;

use Framework\Alonity\Controller\Controller;

class Profile extends Controller {

	public function index(){
		return parent::getCurrentView()->index();
	}

	public function auth(){
		return parent::getCurrentView()->auth();
	}

	public function logout(){
		return parent::getCurrentView()->logout();
	}

	public function register(){
		return parent::getCurrentView()->register();
	}

	public function restore(){
		return parent::getCurrentView()->restore();
	}

	public function restorePage(){
		return parent::getCurrentView()->restorePage();
	}

	public function restoreComplete(){
		return parent::getCurrentView()->restoreComplete();
	}

	public function avatarChange(){
		return parent::getCurrentView()->avatarChange();
	}

	public function messages(){
		return parent::getCurrentView()->messages();
	}

	public function message(){
		return parent::getCurrentView()->message();
	}

	public function messageRemove(){
		return parent::getCurrentView()->messageRemove();
	}

	public function messageReplyAdd(){
		return parent::getCurrentView()->messageReplyAdd();
	}

	public function messageReplyRemove(){
		return parent::getCurrentView()->messageReplyRemove();
	}

	public function messageReplyQuote(){
		return parent::getCurrentView()->messageReplyQuote();
	}

	public function messageReplyEdit(){
		return parent::getCurrentView()->messageReplyEdit();
	}

	public function messageReplySave(){
		return parent::getCurrentView()->messageReplySave();
	}

	public function messageLock(){
		return parent::getCurrentView()->messageLock();
	}

	public function messageNew(){
		return parent::getCurrentView()->messageNew();
	}

	public function messageNewCreate(){
		return parent::getCurrentView()->messageNewCreate();
	}

	public function activity(){
		return parent::getCurrentView()->activity();
	}

	public function settings(){
		return parent::getCurrentView()->settings();
	}

	public function settingsSave(){
		return parent::getCurrentView()->settingsSave();
	}

	public function settingsSecurityComplete(){
		return parent::getCurrentView()->settingsSecurityComplete();
	}
}

?>