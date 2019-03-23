<?php

namespace App\WebMCR\Models\Admin;

use App\WebMCR\Models\Logger;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\Admin\Users\Groups;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\File\File;
use Framework\Components\Filters\_Input;
use Framework\Components\Path;

class Settings {

	public function getThemes(){
		$cache = Cache::getOnce(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$app = RouterHelper::getApp();

		$path = RouterHelper::getRoot().dirname($app['default_public_dir']);

		$scan = scandir($path);

		$filter = [];

		foreach($scan as $file){
			if($file=='.' || $file=='..'){
				continue;
			}

			if(!is_dir("{$path}/{$file}")){
				continue;
			}

			$filter[] = $file;
		}

		return Cache::setOnce(__METHOD__, $filter);
	}

	public function themeExists($name){

		if(!preg_match('/^[\w]+$/i', $name)){
			return false;
		}

		$app = RouterHelper::getApp();

		$path = RouterHelper::getRoot().dirname($app['default_public_dir']);

		$dirname = "{$path}/{$name}";

		return (file_exists($dirname) && is_dir($dirname));
	}

	public function getLogicName(){
		$config = RouterHelper::getAppConfig();

		return basename($config['userLogic']);
	}

	public function getLogics(){
		$cache = Cache::getOnce(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$path = Path::app("/Models/User/Logic");

		$scan = scandir($path);

		$filter = [];

		foreach($scan as $file){
			if($file=='.' || $file=='..'){
				continue;
			}

			if(!is_file("{$path}/{$file}")){
				continue;
			}

			$pathinfo = pathinfo($file);

			if($pathinfo['extension']!='php'){
				continue;
			}

			$filter[] = $pathinfo['filename'];
		}

		return Cache::setOnce(__METHOD__, $filter);
	}

	public function existsLogic($name){

		if(!preg_match('/^[\w]+$/i', $name)){
			return false;
		}

		$filename = Path::app("/Models/User/Logic/{$name}.php");

		return (file_exists($filename) && is_file($filename));
	}

	public function filter_config($value){
		return str_replace(['"', '\''], ['', ''], $value);
	}

	public function filter_mail_list($list){
		$list = str_replace(' ', '', $list);

		$list = explode(',', $list);

		$result = [];

		foreach($list as $val){
			if(!preg_match('/^[a-z0-9\-\.]+$/i', $val)){
				continue;
			}

			$result[] = $val;
		}

		return $result;
	}

	public function filter_valid_money($money){

		$result = [];

		if(empty($money)){
			return $result;
		}

		foreach($money as $k => $v){
			if(!isset($v['enable'])){
				return false;
				break;
			}

			if(!isset($v['key'])){
				return false;
				break;
			}

			if(!isset($v['name'])){
				return false;
				break;
			}

			if(!isset($v['column'])){
				return false;
				break;
			}

			if(!isset($v['cur'])){
				return false;
				break;
			}

			$k = $this->filter_config($v['key']);

			$result[$k]['enable'] = (intval($v['enable'])>0);

			$result[$k]['name'] = $this->filter_config($v['name']);

			$result[$k]['column'] = $this->filter_config($v['column']);

			$result[$k]['cur'] = $this->filter_config($v['cur']);
		}

		return $result;
	}

	public function save($params){

		$filter = new _Input($params);

		$filter->add('sitename', _Input::TYPE_STRING, 1, 64)
			->add('sitedesc', _Input::TYPE_STRING, 1, 255)
			->add('sitekeys', _Input::TYPE_STRING, 1, 255)
			->add('theme', _Input::TYPE_STRING, 1, 64, true, 'Default')
			->add('site_url', _Input::TYPE_STRING, 1, 64, true, '/')
			->add('full_site_url', _Input::TYPE_STRING, 1, 128, true, 'http://'.$_SERVER['SERVER_NAME'])
			->add('cache_version_css', _Input::TYPE_STRING, 1, 32)
			->add('cache_version_js', _Input::TYPE_STRING, 1, 32)
			->add('logic', _Input::TYPE_STRING, 1, 64, true, 'DefaultLogic')
			->add('bangroup', _Input::TYPE_INTEGER, 1, 10, true, 3)
			->add('unbangroup', _Input::TYPE_INTEGER, 1, 10, true, 1)
			->add('removegroup', _Input::TYPE_INTEGER, 1, 10, true, 1)
			->add('economy', _Input::TYPE_INTEGER, 1, 1, false, 0)
			->add('economy_table', _Input::TYPE_SIMPLE_STRING, 1, 64, true)
			->add('login_column', _Input::TYPE_SIMPLE_STRING, 1, 64, true)
			->add('news_list', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('news_comments', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('profile_comments', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('profile_messages', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('profile_reply', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('profile_activity', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('users_list', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('users_comments', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_statics', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_logs', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_news', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_news_tags', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_users', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_user_groups', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('admin_permissions', _Input::TYPE_POSITIVE_INTEGER, 1, 10, true, 10)
			->add('mail_smtp', _Input::TYPE_INTEGER, 1, 1, false, 0)
			->add('mail_smtp_host', _Input::TYPE_STRING, 1, 128)
			->add('mail_smtp_username', _Input::TYPE_STRING, 1, 128)
			->add('mail_smtp_password', _Input::TYPE_STRING, 1, 128)
			->add('mail_smtp_secure', _Input::TYPE_STRING, 1, 3, true, 'ssl')
			->add('mail_port', _Input::TYPE_POSITIVE_INTEGER, 1, 6, false, 465)
			->add('mail_from', _Input::TYPE_STRING, 1, 128)
			->add('mail_from_name', _Input::TYPE_STRING, 1, 64)
			->add('restore_expire', _Input::TYPE_INTEGER, 1, 10, false, 0)
			->add('register_enable', _Input::TYPE_INTEGER, 1, 1, false, 0)
			->add('register_captcha', _Input::TYPE_INTEGER, 1, 1, false, 0)
			->add('captcha_recaptcha_public', _Input::TYPE_STRING, 1, 128)
			->add('captcha_recaptcha_private', _Input::TYPE_STRING, 1, 128)
			->add('mail_blacklist', _Input::TYPE_STRING, 1, 65565)
			->add('mail_whitelist', _Input::TYPE_STRING, 1, 65565);

		$data = $filter->filter();

		if(!$filter->isValid()){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Форма заполнена неверно | '.$filter->getValidationMessage()
			];
		}

		$data['economy'] = ($data['economy']>0);

		$data['mail_smtp'] = ($data['mail_smtp']>0);

		$config = RouterHelper::getAppConfig();

		if(!$this->themeExists($data['theme'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Шаблон сайта выбран неверно'
			];
		}

		if(!$this->existsLogic($data['logic'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Логика работы с пользователем выбрана неверно'
			];
		}

		$groupsModel = new Groups();

		if(!$groupsModel->existsByID($data['bangroup'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Группа забаненных выбрана неверно'
			];
		}

		if(!$groupsModel->existsByID($data['unbangroup'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Группа разбаненных выбрана неверно'
			];
		}

		if(!$groupsModel->existsByID($data['removegroup'])){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Группа после удаления выбрана неверно'
			];
		}

		if($data['economy']){
			$select = Database::select()
				->columns(["`{$data['login_column']}`"])
				->from($data['economy_table'])
				->limit(1);

			if(!$select->execute()){
				return [
					'type' => false,
					'title' => 'Внимание!',
					'text' => 'Таблица экономики или ее колонка указана неверно'
				];
			}
		}

		$data['money'] = (!isset($params['money']) || !is_array($params['money'])) ? [] : $params['money'];

		$data['money'] = $this->filter_valid_money($data['money']);

		if($data['money']===false){
			return [
				'type' => false,
				'title' => 'Внимание!',
				'text' => 'Настройки экономики указаны неверно'
			];
		}

		$config['meta']['sitename'] = $this->filter_config($data['sitename']);
		$config['meta']['sitedesc'] = $this->filter_config($data['sitedesc']);
		$config['meta']['sitekeys'] = $this->filter_config($data['sitekeys']);
		$config['meta']['theme'] = $data['theme'];
		$config['meta']['site_url'] = $this->filter_config($data['site_url']);
		$config['meta']['full_site_url'] = $this->filter_config($data['full_site_url']);
		$config['meta']['cache_version_css'] = $this->filter_config($data['cache_version_css']);
		$config['meta']['cache_version_js'] = $this->filter_config($data['cache_version_js']);
		$config['meta']['theme_url'] = "{$config['meta']['site_url']}Themes/{$data['theme']}/";

		$config['userLogic'] = 'App\\WebMCR\\Models\\User\\Logic\\'.$data['logic'];
		$config['restore_expire'] = $data['restore_expire'];

		$config['changegroup']['ban'] = $data['bangroup'];
		$config['changegroup']['back'] = $data['unbangroup'];
		$config['changegroup']['remove'] = $data['removegroup'];

		$config['database']['economy']['enable'] = $data['economy'];
		$config['database']['economy']['table'] = $data['economy_table'];
		$config['database']['economy']['login_column'] = $data['login_column'];

		$config['money'] = $data['money'];

		$config['pagination']['news']['list'] = $data['news_list'];
		$config['pagination']['news']['comments'] = $data['news_comments'];
		$config['pagination']['profile']['comments'] = $data['profile_comments'];
		$config['pagination']['profile']['messages'] = $data['profile_messages'];
		$config['pagination']['profile']['reply'] = $data['profile_reply'];
		$config['pagination']['profile']['activity'] = $data['profile_activity'];
		$config['pagination']['users']['list'] = $data['users_list'];
		$config['pagination']['users']['comments'] = $data['users_comments'];
		$config['pagination']['admin']['statics'] = $data['admin_statics'];
		$config['pagination']['admin']['logs'] = $data['admin_logs'];
		$config['pagination']['admin']['news'] = $data['admin_news'];
		$config['pagination']['admin']['news_tags'] = $data['admin_news_tags'];
		$config['pagination']['admin']['users'] = $data['admin_users'];
		$config['pagination']['admin']['user_groups'] = $data['admin_user_groups'];
		$config['pagination']['admin']['permissions'] = $data['admin_permissions'];

		$config['mail']['smtp'] = $data['mail_smtp'];
		$config['mail']['host'] = $this->filter_config($data['mail_smtp_host']);
		$config['mail']['port'] = $data['mail_port'];
		$config['mail']['secure'] = ($data['mail_smtp_secure']=='ssl') ? $data['mail_smtp_secure'] : 'tls';
		$config['mail']['username'] = $this->filter_config($data['mail_smtp_username']);
		$config['mail']['password'] = $this->filter_config($data['mail_smtp_password']);
		$config['mail']['from'] = $this->filter_config($data['mail_from']);
		$config['mail']['from_name'] = $this->filter_config($data['mail_from_name']);

		$config['mail']['blacklist'] = $this->filter_mail_list($data['mail_blacklist']);
		$config['mail']['whitelist'] = $this->filter_mail_list($data['mail_whitelist']);

		$config['register']['enable'] = ($data['register_enable']>0);
		$config['register']['captcha'] = ($data['register_captcha']>0);

		$config['captcha']['recaptcha']['public'] = $this->filter_config($data['captcha_recaptcha_public']);
		$config['captcha']['recaptcha']['private'] = $this->filter_config($data['captcha_recaptcha_private']);

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

		Logger::base('[ПУ] Изменение настроек', "Настройки сайта в панели управления были успешно изменены", __METHOD__, 'admin_settings');

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Настройки сайта успешно сохранены'
		];
	}
}

?>