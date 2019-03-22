<?php

namespace App\WebMCR\Models\Admin;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\Logger;
use App\WebMCR\Models\User\User;
use Framework\Components\Cache\Cache;
use Framework\Components\Database\Database;
use Framework\Components\File\File;
use Framework\Components\Filters\_Input;
use Framework\Components\Pagination\Pagination;
use Framework\Components\Path;

class Statics {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function getCount($params){
		$cache = Cache::getOnce([__METHOD__, $params]);

		if(!is_null($cache)){ return $cache; }

		$result = 0;

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('statics');

		if(!$select->execute()){
			return Cache::setOnce([__METHOD__, $params], $result);
		}

		$array = $select->getArray();

		if(empty($array)){
			return Cache::setOnce([__METHOD__, $params], $result);
		}

		$result = intval(@$array[0][0]);

		return Cache::setOnce([__METHOD__, $params], $result);
	}

	public function getPagination($params){

		$cache = Cache::getOnce(__METHOD__);
		if(!is_null($cache)){ return $cache; }

		$config = RouterHelper::getAppConfig();

		$pagination = new Pagination();

		$pagination->setCount($this->getCount($params))
			->setCurrentPage(@$params['page_id'])
			->setLimit($config['pagination']['admin']['statics'])
			->setType(Pagination::TYPE_SHORT)
			->setNext(true)->setNextNext(true)
			->setPrev(true)->setPrevPrev(true)
			->setUrl($config['meta']['site_url'].'admin/statics/page-{PAGE}');

		return Cache::setOnce(__METHOD__, $pagination);
	}

	public function getList($params){

		$pagination = $this->getPagination($params);

		$result = [];

		$select = Database::select()
			->columns(['`s`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['s' => 'statics'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`s`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`s`.`user_id_update`"])
			->order(['`s`.`id`' => 'DESC'])
			->limit($pagination->getLimit())
			->offset($pagination->getStart());

		if(!$select->execute() || $select->getNum()<=0){
			return $result;
		}

		return $select->getAssoc();
	}

	public function getItem($id){
		$id = intval($id);

		$cache = Cache::getOnce([__METHOD__, $id]);
		if(!is_null($cache)){ return $cache; }

		$select = Database::select()
			->columns(['`s`.*',
				'`uc`.`login`' => '`user_login_create`', '`uc`.`avatar`' => '`user_avatar_create`',
				'`uu`.`login`' => '`user_login_update`', '`uu`.`avatar`' => '`user_avatar_update`'])
			->from(['s' => 'statics'])
			->leftjoin('users', 'uc', ["`uc`.`id`=`s`.`user_id_create`"])
			->leftjoin('users', 'uu', ["`uu`.`id`=`s`.`user_id_update`"])
			->where(["`s`.`id`='?'"], [$id]);

		if(!$select->execute() || $select->getNum()<=0){
			return Cache::setOnce([__METHOD__, $id], null);
		}

		$ar = $select->getAssoc();

		if(empty($ar)){ return Cache::setOnce([__METHOD__, $id], null); }

		return Cache::setOnce([__METHOD__, $id], $ar[0]);
	}

	public function existsByRoute($route, $id=null){

		$route = $this->filterRoute($route);

		$where = ["`route`='?'"];
		$where_values = [$route];

		if(!is_null($id)){
			$where[] = "`id`!='?'";
			$where_values[] = intval($id);
		}

		$select = Database::select()
			->columns(['COUNT(*)'])
			->from('statics')
			->where($where, $where_values);

		if(!$select->execute()){
			return false;
		}

		$array = $select->getArray();

		if(empty($array) || !isset($array[0][0])){
			return false;
		}

		return (intval($array[0][0])<=0) ? false : true;
	}

	public function remove($id){
		$id = intval($id);

		$delete = Database::delete()
			->from('statics')
			->where(["`id`='?'"], [$id]);

		Logger::base('Удаление статической страницы', "Произведено удаление статической страницы #{$id}", __METHOD__, 'admin_statics_remove');

		return $delete->execute();
	}

	public function publish($id, $value=0){
		$id = intval($id);

		$value = (intval($value)==1) ? 1 : 0;

		$delete = Database::update()
			->table('statics')
			->set(['`status`' => $value, '`user_id_update`' => $this->getUser()->getID(), '`date_update`' => time()])
			->where(["`id`='?'"], [$id]);

		if($value){
			Logger::base('Публикация статической страницы', "Публикация статической страницы #{$id}", __METHOD__, 'admin_statics_publish');
		}else{
			Logger::base('Скрытие статической страницы', "Скрытие из публичного просмотра статической страницы #{$id}", __METHOD__, 'admin_statics_unpublish');
		}

		return $delete->execute();
	}

	private function filterRoute($route){
		$route = preg_replace('/^[\/]+|[\/]+$/i', '', $route);

		return "{$route}/";
	}

	public function addSubmit($params){

		$filter = new _Input($params);

		$filter->add('title', _Input::TYPE_STRING, 1, 64, true)
			->add('route', _Input::TYPE_STRING, 1, 255)
			->add('text', _Input::TYPE_STRING, 1, 65536)
			->add('permission', _Input::TYPE_STRING, 1, 64);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия страницы'
			];
		}

		$data['route'] = $this->filterRoute($data['route']);

		if($this->existsByRoute($data['route'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Адрес уже используется'
			];
		}

		$config = RouterHelper::getAppConfig();

		if(RouterHelper::getRouteByURL($config['meta']['site_url'].$data['route'])!==false){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Адрес страницы уже используется. Выберите другой'
			];
		}

		$_user = $this->getUser();

		$permissions = $_user->getGroupPermissions(0, true, $_user::USER_COMPLETE_BY_NAME_FULL);

		if(!empty($data['permission']) && !isset($permissions[$data['permission']])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Привилегия указана неверно'
			];
		}

		$page_id = $this->insertPage($data['title'], $data['route'], $data['text'], $data['permission']);

		if(!$page_id){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		if(!$this->generateRoute($data['route'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Страница была создана. Однако не удалось обновить файл маршрутов'
			];
		}

		Logger::base('Добавление статической страницы', "Добавление статической страницы #{$page_id}", __METHOD__, 'admin_statics_add');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Статическая страница успешно добавлена',
			'page_id' => $page_id
		];
	}

	public function generateRoute($route, $old=null){

		$name = md5($route);

		$dir = Path::app();

		$statics = RouterHelper::getRoutesFile("{$dir}/Routes/Statics.php");

		if(!is_null($old)){
			$oldname = md5($old);
			if(isset($statics["static_{$oldname}"])){ unset($statics["static_{$oldname}"]); }
		}

		$statics["static_{$name}"] = [
			'pattern' => '/'.$route,
			'controller' => 'Statics',
		];

		$file = File::config()
			->setPath("{$dir}/Routes")
			->setData($statics)
			->build()
			->setInfo('Alonity routes %NAME% | Updated: %DATE% %TIME%')
			->name('Statics');

		return $file->execute();
	}

	public function insertPage($title, $route, $text, $permission, $status=0){

		$user_id = $this->getUser()->getID();
		$time = time();

		$insert = Database::insert()
			->into('statics')
			->columns(['route', 'title', 'text', 'permission', 'status', 'user_id_create', 'user_id_update', 'date_create', 'date_update'])
			->values([$route, $title, $text, $permission, $status, $user_id, $user_id, $time, $time]);

		if(!$insert->execute()){
			return false;
		}

		return $insert->getLastID();
	}

	public function editSubmit($item_id, $params){
		$item_id = intval($item_id);

		$item = $this->getItem($item_id);

		if(is_null($item)){
			return [
				'type' => false,
				'title' => 'Ошибка 404',
				'text' => 'Страница не найдена'
			];
		}

		$filter = new _Input($params);

		$filter->add('title', _Input::TYPE_STRING, 1, 64, true)
			->add('route', _Input::TYPE_STRING, 1, 255)
			->add('text', _Input::TYPE_STRING, 1, 65536)
			->add('permission', _Input::TYPE_STRING, 1, 64);

		$data = $filter->filter();

		if(empty($data['title'])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Не заполнено поле названия страницы'
			];
		}

		$data['route'] = $this->filterRoute($data['route']);

		$_user = $this->getUser();

		$permissions = $_user->getGroupPermissions(0, true, $_user::USER_COMPLETE_BY_NAME_FULL);

		if(!empty($data['permission']) && !isset($permissions[$data['permission']])){
			return ['type' => false, 'title' => 'Внимание!',
				'text' => 'Привилегия указана неверно'
			];
		}

		if($item['route'] != $data['route']){
			if($this->existsByRoute($data['route'])){
				return ['type' => false, 'title' => 'Внимание!',
					'text' => 'Адрес уже используется'
				];
			}

			if(RouterHelper::getRouteByURL($data['route'])!==false){
				return ['type' => false, 'title' => 'Внимание!',
					'text' => 'Адрес страницы уже используется. Выберите другой'
				];
			}
		}

		if(!$this->updatePage($item_id, $data['title'], $data['route'], $data['text'], $data['permission'])){
			return ['type' => false, 'title' => 'Ошибка!',
				'text' => 'Произошла ошибка баз данных. Обратитесь к администрации'
			];
		}

		if($item['route'] != $data['route']){
			if(!$this->generateRoute($data['route'], $item['route'])){
				return ['type' => false, 'title' => 'Внимание!',
					'text' => 'Страница была создана. Однако не удалось обновить файл маршрутов'
				];
			}
		}

		Logger::base('Изменение статической страницы', "Изменение статической страницы #{$item_id}", __METHOD__, 'admin_statics_edit');

		return ['type' => true, 'title' => 'Поздравляем!',
			'text' => 'Статическая страница успешно сохранена',
		];
	}

	public function updatePage($id, $title, $route, $text, $permission){
		$id = intval($id);

		$user_id = $this->getUser()->getID();
		$time = time();

		$update = Database::update()
			->table('statics')
			->set(['`title`' => $title, '`route`' => $route, '`text`' => $text,
					'`permission`' => $permission, '`user_id_update`' => $user_id, '`date_update`' => $time])
			->where(["`id`='?'"], [$id]);

		return $update->execute();
	}
}

?>