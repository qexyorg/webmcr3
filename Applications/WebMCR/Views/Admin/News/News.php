<?php

namespace App\WebMCR\Views\Admin\News;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use App\WebMCR\Models\Admin\News\News as NewsModel;
use App\WebMCR\Models\Admin\News\Tags as Tags;
use Framework\Components\Secure\CSRF;

class News extends View {

	public function index(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_news_index', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$model = new NewsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/Admin/News/tpl/index.tpl', [
			'pagename' => 'Новости | Панель управления',
			'list' => $model->getList($params),
			'pagination' => $pagination,
			'count' => $model->getCount($params),
		]);

		exit;
	}

	public function addItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_news_add', true)){
			Alerts::set()->message('У Вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect($config['meta']['site_url'])->execute();
		}

		$tags = new Tags();

		echo $this->getTemplater()->render('Resources/Admin/News/Add/tpl/index.tpl', [
			'pagename' => 'Добавление | Новости | Панель управления',
			'tags' => $tags->getAll(),
		]);

		exit;
	}

	public function addItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_add', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new NewsModel();

		$add = $model->addSubmit($_POST);

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message($add['text'], $add['title'], $add['type'], $add)
			->execute();
	}

	public function editItem(){
		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('admin_news_edit', true)){
			Alerts::set()->message('У вас недостаточно прав для доступа к выбранному разделу', 'Ошибка доступа!')
				->redirect("{$config['meta']['site_url']}admin/news/")->execute();
		}

		$model = new NewsModel();

		$id = intval(RouterHelper::getParam('id'));

		$item = $model->getItem($id);

		if(is_null($item)){
			Alerts::set()->message('Страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}admin/news/")->execute();
		}

		$tags = new Tags();

		echo $this->getTemplater()->render('Resources/Admin/News/Edit/tpl/index.tpl', [
			'pagename' => 'Редактирование | Новости | Панель управления',
			'item' => $item,
			'tags' => $tags->getAll(),
			'selected_tags' => $model->getSelectedTags($id),
		]);

		exit;
	}

	public function editItemSubmit(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_edit', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new NewsModel();

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

		if(!Permissions::equal('admin_news_remove', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new NewsModel();

		if(!$model->remove(RouterHelper::getParam('id'))){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка удаления новости', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Новость была успешно удалена', 'Поздравляем!', true)
			->execute();
	}

	public function publicItem(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('admin_news_public', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У Вас недостаточно прав для данного действия', 'Ошибка доступа!')
				->execute();
		}

		$model = new NewsModel();

		$value = (intval(RouterHelper::getParam('value'))==1) ? 1 : 0;

		if(!$model->publish(RouterHelper::getParam('id'), $value)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка изменения статуса новости', 'Ошибка')
				->execute();
		}

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('Статус новости успешно изменен', 'Поздравляем!', true, [
				'value' => $value
			])
			->execute();
	}
}

?>