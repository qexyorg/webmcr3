<?php

namespace App\WebMCR;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\Router;
use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Cache\Cache;
use Framework\Components\Filters\_Date;
use Framework\Components\Filters\_String;
use Framework\Components\Path;
use Framework\Components\Permissions\Permissions;
use Framework\Components\Secure\CSRF;
use App\WebMCR\Models\Comments;
use App\WebMCR\Models\Logger;
use App\WebMCR\Models\Mail\Mail;
use App\WebMCR\Models\Subscribes;
use App\WebMCR\Models\User\User;
use App\WebMCR\Models\User\UserHelper;
use Twig\TwigFilter;
use Twig\TwigFunction;

class WebMCR {

	/** @return User */
	private function getUser(){
		if(DI::has('User')){
			return DI::get('User');
		}

		return DI::set('User', new User());
	}

	/** @return View */
	private function getView(){
		if(DI::has('View')){
			return DI::get('View');
		}

		return DI::set('View', new View());
	}

	/** @return Comments */
	private function getComments(){
		if(DI::has('Comments')){
			return DI::get('Comments');
		}

		return DI::set('Comments', new Comments());
	}

	/** @return Subscribes */
	private function getSubscribes(){
		if(DI::has('Subscribes')){
			return DI::get('Subscribes');
		}

		return DI::set('Subscribes', new Subscribes());
	}

	private function getMetaEntities(){
		$cache = Cache::getOnce(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$meta = $config['meta'];

		$meta['token'] = CSRF::getToken();

		array_walk_recursive($meta, function(&$value){
			$value = _String::toEntities($value);
		});

		return Cache::setOnce(__METHOD__, json_encode($meta));
	}

	private function initFilters($env){

		$filter_dateToFormat = new TwigFilter('dateToFormat', function($e){
			return _Date::toFormat($e);
		});

		$filter_hideEmail = new TwigFilter('hideEmail', function($e){
			return _String::hideEmail($e);
		});

		$filter_gender = new TwigFilter('gender', function($e){
			return (intval($e)==1) ? 'Женский' : 'Мужской';
		});

		$filter_int = new TwigFilter('int', function($e){
			return intval($e);
		});

		$filter_float = new TwigFilter('float', function($e){
			return floatval($e);
		});

		$filter_boolean = new TwigFilter('boolean', function($e){
			return ($e=='true' || intval($e)==1);
		});

		$filter_age = new TwigFilter('age', function($e){

			$e = time() - intval($e);

			$e = $e / (3600*24*365);

			return intval($e);
		});

		$filter_avatar = new TwigFilter('avatar', function($e){
			$config = RouterHelper::getAppConfig();

			return (empty($e)) ? $config['meta']['site_url'].'Uploads/avatars/camera.png' : $e;
		});

		$env->addFilter($filter_dateToFormat);
		$env->addFilter($filter_avatar);
		$env->addFilter($filter_age);
		$env->addFilter($filter_hideEmail);
		$env->addFilter($filter_gender);
		$env->addFilter($filter_int);
		$env->addFilter($filter_float);
		$env->addFilter($filter_boolean);

		return $env;
	}

	private function initFunction($env){

		$function_case = new TwigFunction('case', function($number, $n1, $n2, $other){
			return _String::toCase($number, $n1, $n2, $other);
		});

		$function_dump = new TwigFunction('dump', function($var){
			return var_export($var, true);
		});

		$function_isBoolean = new TwigFunction('isBoolean', function($var){
			return is_bool($var);
		});

		$function_comments = new TwigFunction('comments', function($type='news', $value=0, $amount=10, $page='page-{PAGE}', $prefix=null, $table=null, $order_by='id', $order='DESC'){
			$comments = $this->getComments();

			if(is_null($prefix)){
				$prefix = $type;
			}

			if(is_null($table)){
				$table = $type;
			}

			$page_id = intval(RouterHelper::getParam('page_id'));

			$amount = intval($amount);

			$amount = ($amount<=0) ? 1 : $amount;

			$page_id = $page_id<=0 ? 1 : $page_id;

			$pagination = $comments->getPagination($page_id, $type, $value, $amount, $page)->execute();

			$count = 0;

			$list = [];

			$user = $this->getUser()->getCurrentUser();

			$group_id = intval($user['group_id']);

			if(!$comments->hasMod($type)){
				if(!$comments->setMod($type, $value, $amount, $prefix, $table, $order_by, $order)){
					return [];
				}
			}

			$mod = $comments->getMod($type);

			if(Permissions::get("{$mod['prefix']}_comments_view", $group_id)){
				$count = $comments->getCommentsCount(["`type`='?'", "`value`='?'"], [$type, $value]);
				$list = $comments->getList($page_id, $type, $value, @$mod['amount'], $page, @$mod['order_by'], @$mod['order']);
			}

			return [
				'count' => $count,
				'list' => $list,
				'pagination' => $pagination,
				'permissions' => $comments->getPermissions(@$mod['prefix']),
				'type' => $type,
				'value' => $value,
				'comment_id_tpl' => @$mod['comment_id_tpl']
			];
		});

		$function_subscribes = new TwigFunction('subscribes', function($type='users', $value=0, $table=null, $prefix=null){
			$subscribes = $this->getSubscribes();

			if(is_null($prefix)){
				$prefix = $type;
			}

			if(is_null($table)){
				$table = $type;
			}

			$user = $this->getUser()->getCurrentUser();

			$group_id = intval($user['group_id']);

			if(!$subscribes->hasMod($type)){
				if(!$subscribes->setMod($type, $value, $table, $prefix)){
					return [];
				}
			}

			$mod = $subscribes->getMod($type);

			$isSubscribe = false;

			if(Permissions::get("{$mod['prefix']}_subscribe", $group_id)){
				$isSubscribe = $subscribes->isSubscribe($type, $value);
			}

			return [
				'permissions' => $subscribes->getPermissions(@$mod['prefix']),
				'type' => $type,
				'value' => $value,
				'is_subscribe' => $isSubscribe
			];
		});

		$env->addFunction($function_case);
		$env->addFunction($function_comments);
		$env->addFunction($function_subscribes);
		$env->addFunction($function_dump);
		$env->addFunction($function_isBoolean);

		return $env;
	}

	private function setOptions(){

		$config = RouterHelper::getAppConfig();

		$_user = $this->getUser();

		$user = $_user->getCurrentUser();

		$group_id = ($user===false) ? 0 : intval($user['group_id']);

		Permissions::setCurrentGroup($group_id);

		Permissions::setPermissions($_user->getUserPermissions(null, true, $_user::USER_COMPLETE_BY_NAME_VALUE));

		$env = $this->getView()->getTemplater();

		$env = $this->initFilters($env);
		$env = $this->initFunction($env);

		$env->addGlobal('__CAPTCHA__', $config['captcha']);
		$env->addGlobal('__USERCORE__', $_user);
		$env->addGlobal('__USER__', $user);
		$env->addGlobal('__BALANCE__', $_user->getBalance());
		$env->addGlobal('__MONEY__', $config['money']);
		$env->addGlobal('__PERMISSION__', Permissions::getAll($group_id));
	}

	private function setTemplater(){

		$cache = Cache::getOnce(__METHOD__);
		if($cache===true){ return $cache; }

		$view = $this->getView();

		$loader = $view->getTemplaterLoader();

		$config = RouterHelper::getAppConfig();

		$app = RouterHelper::getApp();

		//$loader->setPaths(Path::to("/Public/WebMCR/Themes/{$config['meta']['theme']}"));
		$loader->setPaths(Path::to($app['default_public_dir']));

		$env = $this->getView()->getTemplater();

		$env->addGlobal('__ALERTS__', Alerts::get());
		$env->addGlobal('__META__', $config['meta']);
		$env->addGlobal('__META_JSON__', $this->getMetaEntities());
		$env->addGlobal('__CONFIG__', $config);

		try{
			DI::get('ALONITY')->callToTrigger('onInitTemplater', ['templater' => $env]);
		}catch (TriggersException $e){
			exit($e->getMessage());
		}

		return Cache::setOnce(__METHOD__, true);
	}

	public function execute(){

		$config = RouterHelper::getAppConfig();

		Logger::setStatus($config['logger']['enable']);
		Logger::setLoggerData($config['logger']['store']);

		CSRF::setSalt($config['csrfString']);

		$router = new Router();

		if($config['install']){
			if(!preg_match('/^\/install/i', $_SERVER['REQUEST_URI'])){
				Alerts::set()->logic(Alerts::HTTP_LOGIC)
					->redirect($config['meta']['site_url'].'install/')
					->message('Запущен процесс установки движка WebMCR', 'Требуется установка')
					->execute();
			}

			$router->addMultiple(RouterHelper::getRoutesFile(Path::app('/Routes/Install.php')));

			$this->setTemplater();

			return;
		}

		$router->addMultiple(RouterHelper::getRoutesFile(Path::app('/Routes/Public.php')));
		$router->addMultiple(RouterHelper::getRoutesFile(Path::app('/Routes/Admin.php')));
		$router->addMultiple(RouterHelper::getRoutesFile(Path::app('/Routes/Statics.php')));

		Mail::params()
			->setLanguage($config['mail']['lng'])
			->setSMTP($config['mail']['smtp'])
			->setHost($config['mail']['host'])
			->setPort($config['mail']['port'])
			->setSecure($config['mail']['secure'])
			->setUsername($config['mail']['username'])
			->setPassword($config['mail']['password'])
			->setCharset('UTF-8')
			->setFrom($config['mail']['from'], $config['mail']['from_name']);

		//Cache::setOptions(['file' => ['path' => Path::to('/Uploads/cache')]]);

		UserHelper::setUserLogic($config['userLogic']);

		$this->setTemplater();

		$this->setOptions();
	}
}

?>