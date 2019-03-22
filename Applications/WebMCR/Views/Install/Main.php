<?php

namespace App\WebMCR\Views\Install;

use App\WebMCR\Models\Admin\Settings;
use App\WebMCR\Models\Install\Install;
use Framework\Alonity\View\View;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Secure\CSRF;

class Main extends View {

	public function index(){

		$install = new Install();

		echo $install->addAccessFilter($this->getTemplater())->render('Resources/Install/tpl/index.tpl', [
			'pagename' => 'Начало установки',
			'version' => (version_compare(PHP_VERSION, $install::VERSION_COMPARE) >= 0),
			'access' => $install->needAccess(),
		]);

		exit;
	}

	public function start(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$start = $install->start();

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $start['type'], $start)
			->execute();
	}

	public function install_step_1(){
		$install = new Install();

		$init = $install->isInstallSession('step_1');

		if($init!==false){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->redirect($init)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		echo $this->getTemplater()->render('Resources/Install/Step_1/tpl/index.tpl', [
			'pagename' => 'Шаг 1 | Установка',
		]);

		exit;
	}

	public function install_step_2(){

		$install = new Install();

		$init = $install->isInstallSession('step_2');

		if($init!==false){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->redirect($init)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		$algos = hash_algos();
		$algos[] = 'salted_md5';
		$algos[] = 'double_md5';
		$algos[] = 'salted_sha1';
		$algos[] = 'salted_sha256';
		$algos[] = 'salted_sha512';
		$algos[] = 'salted_crc32';
		$algos[] = 'blowfish';

		$settins = new Settings();

		echo $this->getTemplater()->render('Resources/Install/Step_2/tpl/index.tpl', [
			'pagename' => 'Шаг 2 | Установка',
			'algos' => $algos,
			'themes' => $settins->getThemes(),
			'logics' => $settins->getLogics()
		]);

		exit;
	}

	public function install_step_3(){
		$install = new Install();

		$init = $install->isInstallSession('step_3');

		if($init!==false){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->redirect($init)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		echo $this->getTemplater()->render('Resources/Install/Step_3/tpl/index.tpl', [
			'pagename' => 'Шаг 3 | Установка',
		]);

		exit;
	}

	public function install_finish(){
		$install = new Install();

		$init = $install->isInstallSession('finish');

		if($init!==false){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->redirect($init)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		echo $this->getTemplater()->render('Resources/Install/Finish/tpl/index.tpl', [
			'pagename' => 'Окончание установки | Установка',
		]);

		exit;
	}

	public function install_step_1_submit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$init = $install->isInstallSession('step_1');

		if($init!==false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		$step = $install->step_1($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $step['type'], $step)
			->execute();
	}

	public function install_step_2_submit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$init = $install->isInstallSession('step_2');

		if($init!==false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		$step = $install->step_2($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $step['type'], $step)
			->execute();
	}

	public function install_step_3_submit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$init = $install->isInstallSession('step_3');

		if($init!==false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		$step = $install->step_3($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $step['type'], $step)
			->execute();
	}

	public function checkConnectDB(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$start = $install->checkConnectDB($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $start['type'], $start)
			->execute();
	}

	public function reinstall(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$reinstall = $install->reinstall();

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $reinstall['type'], $reinstall)
			->execute();
	}

	public function install_disable(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$init = $install->isInstallSession('finish');

		if($init!==false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		$disable = $install->disable();

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $disable['type'], $disable)
			->execute();
	}

	public function install_remove(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		$install = new Install();

		$init = $install->isInstallSession('finish');

		if($init!==false){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Выбранный этап установки не запущен', 'Внимание!')
				->execute();
		}

		$remove = $install->remove();

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $remove['type'], $remove)
			->execute();
	}
}

?>