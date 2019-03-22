<?php

namespace App\WebMCR\Models;

use Framework\Alonity\DI\DI;
use Framework\Alonity\Router\RouterHelper;
use App\WebMCR\Models\User\User;
use Framework\Components\File\File;
use Framework\Components\Path;
use Framework\Components\Permissions\Permissions;

class Uploader {

	/** @return User */
	private function getUser(){
		return DI::get('User');
	}

	public function upload($files){

		if(empty($files)){
			return [
				'type' => false,
				'text' => 'Изображение не выбрано'
			];
		}

		$_user = $this->getUser();

		$user = $_user->getCurrentUser();

		$group_id = intval(@$user['group_id']);

		$extensions = Permissions::get('file_uploader_extensions', $group_id);

		if(empty($extensions)){
			return [
				'type' => false,
				'title' => 'Ошибка доступа',
				'text' => 'У Вас недостаточно прав для загрузки выбранного формата файла'
			];
		}

		$extensions = explode(',', $extensions);

		$upload = File::upload()
			->files($files)
			->extensions($extensions)
			->maxFiles(1)
			->minFiles(1)
			->maxFileSize(Permissions::get('file_uploader_max_size', $group_id))
			->setRandomName()
			->setUploadPath(Path::to('/Public/WebMCR/Uploads/files'));

		if(!$upload->execute()){
			return [
				'type' => false,
				'text' => 'Произошла ошибка загрузки файла ('.$upload->getError().')'
			];
		}

		$names = $upload->getNames();

		$config = RouterHelper::getAppConfig();

		return [
			'type' => true,
			'title' => 'Поздравляем!',
			'text' => 'Файл успешно загружен на сервер',
			'url' => "{$config['meta']['site_url']}Uploads/files/{$names[0]}",
		];
	}
}

?>