<?php

namespace App\WebMCR\Models\Install;

use App\WebMCR\Models\Admin\Settings;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Framework\Alonity\Router\RouterHelper;
use Framework\Components\Crypt\Crypt;
use Framework\Components\Database\Database;
use Framework\Components\Database\DatabaseException;
use Framework\Components\File\File;
use Framework\Components\File\FileException;
use Framework\Components\Filters\_Input;
use Framework\Components\Path;
use Twig\TwigFunction;

class Install {

	const VERSION_COMPARE = '7.2.0';

	public function needAccess(){
		return [
			'/tmp' => 'read|write',
			'/Public/WebMCR/Uploads' => 'read|write',
			'/Applications/WebMCR/Routes' => 'read|write',
			'/Application.php' => 'read|write',
			'/Applications/WebMCR/Config.php' => 'read|write',
			'/Applications/WebMCR/Routes/Public.php' => 'read|write',
			'/Applications/WebMCR/Routes/Statics.php' => 'read|write',
			'/Applications/WebMCR/Routes/Admin.php' => 'read|write',
		];
	}

	public function fileAccess($file, $access){
		$filename = RouterHelper::getRoot().$file;

		if(!file_exists($filename)){
			return false;
		}

		if($access=='write'){
			return is_writeable($filename);
		}elseif($access=='read'){
			return is_readable($filename);
		}

		return (is_readable($filename) && is_writeable($filename));
	}

	public function isInstallSession($current=''){

		$session = (isset($_SESSION['install'])) ? $_SESSION['install'] : '';

		$config = RouterHelper::getAppConfig();

		if($session!=$current){
			$url = "{$config['meta']['site_url']}install/";

			switch($session){
				case 'step_1':
				case 'step_3':
				case 'finish': $url = "{$config['meta']['site_url']}install/{$session}"; break;
			}

			return $url;
		}

		return false;
	}

	public function addAccessFilter($templater){
		$isWrite = new TwigFunction('fileAccess', function($file, $access='write'){
			return $this->fileAccess($file, $access);
		});

		$templater->addFunction($isWrite);

		return $templater;
	}

	private function import($filename, $clear=false){
		try{
			Database::import($filename, $clear);
		}catch(DatabaseException $e){
			$error = $e->getMessage();
		}

		return (isset($error)) ? $error : true;
	}

	public function start(){

		if(version_compare(PHP_VERSION, self::VERSION_COMPARE) < 0){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Версия PHP должна быть >= '.self::VERSION_COMPARE
			];
		}

		foreach($this->needAccess() as $k => $v){
			if(!$this->fileAccess($k, $v)){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => "Необходимы права доступа {$v} к файлу/папке {$v}"
				];

				break;
			}
		}

		$_SESSION['install'] = 'step_1';

		return [
			'type' => true,
			'title' => 'Отлично!',
			'text' => 'Процесс установки начат успешно'
		];
	}

	public function checkConnectDB($params){

		$filter = new _Input($params);

		$filter->add('engine', _Input::TYPE_STRING, 1, 16, true, 'mysqli')
			->add('host', _Input::TYPE_STRING, 1, 128, '127.0.0.1')
			->add('port', _Input::TYPE_STRING, 1, 6, true, 3306)
			->add('charset', _Input::TYPE_STRING, 1, 32, 'utf8mb4')
			->add('database', _Input::TYPE_STRING, 1, 64, 'webmcr')
			->add('user', _Input::TYPE_STRING, 1, 64, true, 'root')
			->add('password', _Input::TYPE_STRING, 1, 65535);

		$data = $filter->filter();

		if(!in_array($data['engine'], ['mysqli', 'mysql', 'postgres'])){
			return [
				'type' => false,
				'title' => 'Ошибка',
				'text' => 'Драйвер баз данных выбран неверно'
			];
		}

		Database::setOptions([
			'engine' => $data['engine'],
			$data['engine'] => $data
		]);

		try {
			Database::connect();

			Database::getEngine()->setDB($data['database']);
		} catch (DatabaseException $e) {
			return [
				'type' => false,
				'title' => 'Не удалось соединиться с базой',
				'text' => $e->getMessage()
			];
		}

		return [
			'type' => true,
			'title' => 'Успех!',
			'text' => 'Соединение с базой данных успешно установлено'
		];
	}

	public function step_1($params){

		$test = $this->checkConnectDB($params);

		if(!$test['type']){
			return $test;
		}

		$filter = new _Input($params);

		$filter->add('engine', _Input::TYPE_STRING, 1, 16, true, 'mysqli')
			->add('host', _Input::TYPE_STRING, 1, 128, '127.0.0.1')
			->add('port', _Input::TYPE_STRING, 1, 6, true, 3306)
			->add('charset', _Input::TYPE_STRING, 1, 32, 'utf8mb4')
			->add('database', _Input::TYPE_STRING, 1, 64, 'webmcr')
			->add('user', _Input::TYPE_STRING, 1, 64, true, 'root')
			->add('password', _Input::TYPE_STRING, 1, 65535)
			->add('clear', _Input::TYPE_INTEGER, 1, 1, true, 0);

		$data = $filter->filter();

		$_SESSION['PREDROP'] = ($data['clear']==1);

		$import = $this->import(Path::to('/tmp/import.sql'), $_SESSION['PREDROP']);

		if($import!==true){
			return [
				'type' => false,
				'title' => 'Ошибка импорта! #'.__LINE__,
				'text' => $import
			];
		}

		$config = RouterHelper::getAppConfig();

		$config['database']['driver'] = $data['engine'];
		$config['database']['host'] = $data['host'];
		$config['database']['port'] = $data['port'];
		$config['database']['charset'] = $data['charset'];
		$config['database']['database'] = $data['database'];
		$config['database']['user'] = $data['user'];
		$config['database']['password'] = $data['password'];
		$config['csrfString'] = Crypt::random(32, 32, ['special', 'cyrilic']);

		$save = File::config();

		$save->setInfo('WebMCR 3 Main Config | Updated: %DATE% %TIME%')
			->setPath(RouterHelper::getRootApp())
			->name('Config')
			->build()
			->setData($config);

		if(!$save->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка сохранения настроек баз данных. Проверьте права на запись файла Applications/WebMCR/Config.php'
			];
		}

		$_SESSION['install'] = 'step_2';

		return [
			'type' => true,
			'title' => 'Замечательно!',
			'text' => 'Теперь перейдем к основным настройкам сайта'
		];
	}

	public function step_2($params){

		$filter = new _Input($params);

		$filter->add('sitename', _Input::TYPE_STRING, 1, 64)
			->add('sitedesc', _Input::TYPE_STRING, 1, 255)
			->add('sitekeys', _Input::TYPE_STRING, 1, 255)
			->add('theme', _Input::TYPE_STRING, 1, 64, true, 'Default')
			->add('algo', _Input::TYPE_STRING, 1, 64, true, 'MD5')
			->add('logic', _Input::TYPE_STRING, 1, 64, true, 'DefaultLogic');

		$data = $filter->filter();

		if(!$filter->isValid()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Форма заполнена неверно | '.$filter->getValidationMessage()
			];
		}

		$settings = new Settings();

		if(!$settings->themeExists($data['theme'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Шаблон сайта выбран неверно'
			];
		}

		if(!$settings->existsLogic($data['logic'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Логика работы с пользователем выбрана неверно'
			];
		}

		$config = RouterHelper::getAppConfig();

		$config['meta']['sitename'] = $settings->filter_config($data['sitename']);
		$config['meta']['sitedesc'] = $settings->filter_config($data['sitedesc']);
		$config['meta']['sitekeys'] = $settings->filter_config($data['sitekeys']);
		$config['meta']['theme'] = $data['theme'];
		$config['meta']['full_site_url'] = (UserHelper::isHTTPS()) ? "https://{$_SERVER['HTTP_HOST']}" : "http://{$_SERVER['HTTP_HOST']}";
		$config['userLogic'] = 'App\\WebMCR\\Models\\User\\Logic\\'.$data['logic'];
		$config['password']['algo'] = $data['algo'];

		$save = File::config();

		$save->setInfo('WebMCR 3 Main Config | Updated: %DATE% %TIME%')
			->setPath(RouterHelper::getRootApp())
			->name('Config')
			->build()
			->setData($config);

		if(!$save->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка сохранения настроек сайта. Проверьте права на запись файла Applications/WebMCR/Config.php'
			];
		}

		$app = RouterHelper::getApp();

		$app['default_public_dir'] = "/Public/{$app['app_name']}/Themes/{$data['theme']}";

		$import = $this->import(Path::to("/tmp/{$data['logic']}.sql"), $_SESSION['PREDROP']);

		if($import!==true){
			return [
				'type' => false,
				'title' => 'Ошибка импорта!',
				'text' => $import
			];
		}

		$save = File::config();

		$save->setInfo('Alonity Framework Config | Updated: %DATE% %TIME%')
			->setPath(RouterHelper::getRoot())
			->name('Application')
			->build()
			->setData($app);

		if(!$save->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка сохранения настроек сайта. Проверьте права на запись файла Application.php'
			];
		}

		$_SESSION['install'] = 'step_3';

		return [
			'type' => true,
			'title' => 'Великолепно!',
			'text' => 'Осталось только добавить администратора'
		];
	}

	public function step_3($params){

		$filter = new _Input($params);

		$config = RouterHelper::getAppConfig();

		$filter->add('login', _Input::TYPE_SIMPLE_STRING, 1, 32, true)
			->add('email', _Input::TYPE_EMAIL, 3, 128, true)
			->add('password', _Input::TYPE_STRING, $config['password']['min'], $config['password']['max'], true)
			->add('repassword', _Input::TYPE_STRING, $config['password']['min'], $config['password']['max'], true)
			->add('auth', _Input::TYPE_INTEGER, 1, 1, true, 1);

		$data = $filter->filter();

		if(!$filter->isValid()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Форма заполнена неверно'
			];
		}

		if($data['password']!==$data['repassword']){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Пароли не совпадают'
			];
		}

		Database::setOptions([
			'engine' => $config['database']['driver'],
			$config['database']['driver'] => $config['database']
		]);

		UserHelper::setUserLogic($config['userLogic']);

		$_user = new User();

		$create = $_user->createUser([
			'login' => $data['login'],
			'email' => $data['email'],
			'password' => $data['password'],
			'group_id' => 2,
		]);

		if($create===false){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Произошла ошибка создания пользователя'
			];
		}

		if(!$_user->setAuth($create['user_id'], true)){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Произошла ошибка авторизации пользователя'
			];
		}

		$_SESSION['install'] = 'finish';

		return [
			'type' => true,
			'title' => 'Замечательно!',
			'text' => 'Установка успешно завершена. Теперь следует отключить установщик.'
		];
	}

	public function disable(){
		$config = RouterHelper::getAppConfig();

		$config['install'] = 0;

		$save = File::config();

		$save->setInfo('WebMCR 3 Main Config | Updated: %DATE% %TIME%')
			->setPath(RouterHelper::getRootApp())
			->name('Config')
			->build()
			->setData($config);

		if(!$save->execute()){
			return [
				'type' => false,
				'title' => 'Ошибка!',
				'text' => 'Произошла ошибка сохранения настроек сайта. Проверьте права на запись файла Applications/WebMCR/Config.php'
			];
		}

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Установщик успешно отключен'
		];
	}

	public function remove(){
		$disable = $this->disable();

		if(!$disable['type']){
			return $disable;
		}

		$config = RouterHelper::getAppConfig();

		try {
			File::removeDir('/Applications/WebMCR/Controllers/Install');
			File::removeDir('/Applications/WebMCR/Models/Install');
			File::removeDir('/Applications/WebMCR/Views/Install');
			File::removeDir("/Public/WebMCR/Themes/{$config['meta']['theme']}/Resources/Install");
			File::removeFiles('/Applications/WebMCR/Routes/Install.php');
		}catch(FileException $e){
			$error = $e;
		}

		return [
			'type' => true,
			'title' => 'Установка успешно отключена и удалена',
			'text' => (isset($error)) ? 'Однако произошла ошибка при удалении некоторых файлов установки '.$error : 'Поздравляем!'
		];
	}
}

?>
