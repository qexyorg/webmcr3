<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/News/Tags/Add/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/News/Tags/Add/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/News/Tags/Add/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'news_tags' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="tags-add">
					<div class="title">Добавление тега</div>

					<form action="{{ __META__.site_url }}admin/news/tags/add/submit" method="POST" class="add-form window">
						<div class="form-wrapper">
							<div class="input-block">
								<label for="title" class="text-bold">Название <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
								<input type="text" id="title" name="title" placeholder="Введите название" required>
							</div>

							<div class="input-block">
								<label for="name" class="text-bold">Уникальное имя</label>
								<input type="text" id="name" name="name" placeholder="Введите имя">
							</div>

							<div class="input-block">
								<label for="text" class="text-bold">Описание</label>
								<input type="text" id="text" name="text" placeholder="Введите описание">
							</div>

							<div class="text-center">
								<button type="submit" class="btn">Добавить</button>
							</div>
						</div>

						<div class="form-success">
							<i class="fa fa-check"></i>
						</div>
					</form>
				</div>
			</div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>