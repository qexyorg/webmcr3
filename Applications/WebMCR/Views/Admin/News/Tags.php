<?php

namespace App\WebMCR\Views\Admin\News;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\News\Tags as TagsModel;
use Framework\Components\Secure\CSRF;

class Tags extends View {

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_news_tags_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new TagsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/News/Tags/tpl/index.tpl', [
			'pagename' => 'Теги новостей | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function addItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_news_tags_add', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		echo $this->getTemplater()->render('Resources/Admin/News/Tags/Add/tpl/index.tpl', [
			'pagename' => 'Добавление | Теги новостей | Панель управления',
		]);

		exit;
	}

	public function addItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_tags_add', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new TagsModel();

		$add = $model->addSubmit($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($add['text'], $add['title'], $add['type'], $add)
			->execute();
	}

	public function editItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_news_tags_edit', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect("{$config['meta']['site_url']}admin/news/tags/")->execute();
		}

		$model = new TagsModel();

		$item = $model->getItem(RouterHelper::getParam('id'));

		if(is_null($item)){
			Alerts::set()->message('Страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}admin/news/tags/")->execute();
		}

		echo $this->getTemplater()->render('Resources/Admin/News/Tags/Edit/tpl/index.tpl', [
			'pagename' => 'Редактирование | Теги новостей | Панель управления',
			'item' => $item
		]);

		exit;
	}

	public function editItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_tags_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new TagsModel();

		$edit = $model->editSubmit(RouterHelper::getParam('id'), $_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($edit['text'], $edit['title'], $edit['type'], $edit)
			->execute();
	}

	public function removeItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_tags_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new TagsModel();

		if(!$model->remove(RouterHelper::getParam('id'))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления тега новости', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Тег новости был успешно удален', 'Поздравляем!', true)
			->execute();
	}
}

?>