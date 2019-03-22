<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/Users/Permissions/Edit/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/Users/Permissions/Edit/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/Users/Permissions/Edit/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'permissions' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="permissions-edit">
					<div class="title">Редактирование привилегии</div>

					<form action="{{ __META__.site_url }}admin/permissions/edit/{{ item.id }}/submit" method="POST" class="edit-form window">
						<div class="form-wrapper">
							<div class="input-block">
								<label for="title" class="text-bold">Название <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
								<input type="text" id="title" name="title" value="{{ item.title }}" placeholder="Введите название" required>
							</div>

                            {% if not item.system|int %}
								<div class="input-block">
									<label for="name" class="text-bold">Уникальное имя</label>
									<input type="text" id="name" name="name" value="{{ item.name }}" placeholder="Введите имя">
								</div>
							{% endif %}

							<div class="input-block">
								<label for="type" class="text-bold">Тип значения</label>
								<select name="type" id="type">
                                    {% for type_key,type in types %}
										<option value="{{ type_key }}" {% if item.type==type_key %}selected{% endif %}>{{ type }}</option>
                                    {% endfor %}
								</select>
							</div>

							<div class="input-block">
								<label for="default" class="text-bold">Значение по умолчанию</label>
								<input type="text" id="default" name="default" value="{{ item.default }}" placeholder="Введите значение">
							</div>

							<div class="text-center">
								<button type="submit" class="btn">Сохранить</button>
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