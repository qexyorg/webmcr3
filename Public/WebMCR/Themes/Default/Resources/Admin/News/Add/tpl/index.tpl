<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/News/Add/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/News/Add/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/News/Add/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'news' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<form action="{{ __META__.site_url }}admin/news/add/submit" method="POST" enctype="multipart/form-data" class="news-add">
					<div class="title">Добавление новости</div>

					<div class="add-form">
						<div class="form-wrapper">
							<div>
								<div class="window">
									<div class="input-block">
										<label for="title" class="text-bold">Название <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
										<input type="text" id="title" name="title" placeholder="Введите название" required>
									</div>

									<div class="image-preview" id="preview"></div>

									<div class="input-block">
										<label for="image" class="text-bold">Ссылка на изображение</label>
										<div class="input-append">
											<input type="file" name="file" accept=".jpg,.png,.jpeg,.gif" class="input-file-custom input-file-uploader" data-trigger="#uploadimage" data-input="#image" data-bg="#preview">
											<input type="text" id="image" name="image" placeholder="Введите URL изображения">
											<div class="append"><a href="#" id="uploadimage" class="col-gray" title="Загрузить с компьютера"><i class="fa fa-picture-o"></i></a></div>
										</div>
									</div>

									<div class="input-block">
										<label for="name" class="text-bold">Уникальное имя</label>
										<input type="text" id="name" name="name" placeholder="Введите имя">
									</div>
								</div>
							</div>

							<div>
								<div class="window">
									<div class="input-block">
										<label for="text_short" class="text-bold">Краткое описание</label>
										<textarea name="text_short" id="text_short" rows="5" placeholder="Введите краткое описание"></textarea>
									</div>

									<div class="input-block m-0">
										<label for="text" class="text-bold">Полное описание</label>
										<textarea name="text" class="m-0" id="text" rows="16" placeholder="Введите полное описание"></textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="form-success window">
							<i class="fa fa-check"></i>
						</div>
					</div>

					<div class="form-bottom window w-50 mx-auto">
						{% if tags %}
							<div class="title">Теги новости</div>
							<div class="tags" data-input-target="#tags-input">
								<ul>
                                    {% for tag in tags %}
										<li>
											<label title="{{ tag.text }}"><input type="checkbox" class="m-0 tag-selector" name="tags[]" value="{{ tag.id }}"> {{ tag.title }}</label>
										</li>
                                    {% endfor %}
								</ul>
							</div>
						{% endif %}

						<div class="text-center pt-20">
							<button type="submit" class="btn">Добавить новость</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>