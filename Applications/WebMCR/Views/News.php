<?php

namespace App\WebMCR\Views;

use Framework\Alonity\Router\RouterHelper;
use Framework\Alonity\View\View;
use App\WebMCR\Models\News as NewsModel;
use Framework\Components\Alerts\Alerts;
use Framework\Components\Permissions\Permissions;
use Framework\Components\Secure\CSRF;

class News extends View {

	public function index(){

		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('news_list', true)){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->message('У Вас недостаточно прав для просмотра новостей', 'Доступ запрещен!')
				->redirect("{$config['meta']['site_url']}403/")
				->execute();
		}

		$model = new NewsModel();

		$params = RouterHelper::getParams();

		$pagination = $model->getPagination($params)->execute();

		echo $this->getTemplater()->render('Resources/News/tpl/index.tpl', [
			'pagename' => 'Новости',
			'news_count' => $model->getNewsCount($params),
			'news' => $model->getNews($params),
			'pagination' => $pagination,
			'tags' => $model->getTags(),
			'search' => RouterHelper::getParam('search')
		]);
	}

	public function view(){

		$config = RouterHelper::getAppConfig();

		if(!Permissions::equal('news_view', true)){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->message('У Вас недостаточно прав для просмотра новости', 'Доступ запрещен!')
				->redirect("{$config['meta']['site_url']}403/")
				->execute();
		}

		$new_id = RouterHelper::getParam('id');

		$model = new NewsModel();

		$new = $model->getNewsByID($new_id);

		if(is_null($new)){
			Alerts::set()->logic(Alerts::HTTP_LOGIC)
				->message('Запрашиваемая страница не найдена', 'Ошибка 404')
				->redirect("{$config['meta']['site_url']}404/")
				->execute();
		}

		$model->updateViews($new_id);

		echo $this->getTemplater()->render('Resources/News/View/tpl/index.tpl', [
			'pagename' => "{$new['title']} | Новости",
			'new' => $new,
			'tags' => $model->getTags(),
			'news_tags' => $model->getTagsByNewsID($new_id),
		]);
	}

	public function newsLike(){

		if(!CSRF::isValidToken(@$_POST['token'])){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('Произошла ошибка валидации отправляемых данных. Обновите страницу и повторите попытку', 'Ошибка!')
				->execute();
		}

		if(!Permissions::equal('news_like', true)){
			Alerts::set()->logic(Alerts::JSON_LOGIC)
				->message('У вас недостаточно прав для оценивания новостей', 'Недостаточно прав')
				->execute();
		}

		$model = new NewsModel();

		$like = $model->newsLikeJson(RouterHelper::getParam('new_id'));

		Alerts::set()->logic(Alerts::JSON_LOGIC)
			->message('', '', $like['type'], $like)
			->execute();
	}
}

?>