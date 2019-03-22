<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/Statics/Add/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/Statics/Add/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/Statics/Add/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'statics' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="statics-add">
					<div class="title">Добавление статической страницы</div>

					<form action="{{ __META__.site_url }}admin/statics/add/submit" method="POST" class="add-form window">
						<div class="form-wrapper">
							<div class="input-block">
								<label for="title" class="text-bold">Название страницы <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
								<input type="text" id="title" name="title" placeholder="Введите название" required>
							</div>

							<div class="input-block">
								<label for="route" class="text-bold">Адрес страницы <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
								<input type="text" id="route" name="route" value="static/" placeholder="Введите адрес">
							</div>

							<div class="input-block">
								<label for="text" class="text-bold">Код страницы</label>
								<textarea name="text" id="text" rows="16" class="scroll-styled" placeholder="Введите HTML код страниы">{{ template }}</textarea>
							</div>

							<div class="input-block">
								<label for="permission" class="text-bold">Уровень доступа</label>
								<select name="permission" id="permission">
									<option value="">Доступно всем</option>
                                    {% for permkey,permission in permissions %}
                                        {% if permission.type=='boolean' %}
											<option value="{{ permkey }}">{{ permission.title }}</option>
                                        {% endif %}
                                    {% endfor %}
								</select>
							</div>

							<div class="text-center">
								<button type="submit" class="btn">Создать страницу</button>
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