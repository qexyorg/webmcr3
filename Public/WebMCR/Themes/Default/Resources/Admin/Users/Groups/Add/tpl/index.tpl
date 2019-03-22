<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/Users/Groups/Add/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/Users/Groups/Add/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/Users/Groups/Add/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'groups' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="groups-add">
					<div class="title">Добавление группы</div>

					<form action="{{ __META__.site_url }}admin/groups/add/submit" method="POST" class="add-form window">
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

							<div class="permissions scroll-styled">
								{% for permission in permissions %}
									<div class="permission-id" data-id="{{ permission.id }}" data-type="{{ permission.type }}">
										<div class="title">{{ permission.title }}</div>
										<div class="value">
											{% if permission.type=='boolean' %}
												<select class="m-0" name="permissions[{{ permission.id }}]">
													<option value="0">Нет</option>
													<option value="1" {% if permission.default=='true' %}selected{% endif %}>Да</option>
												</select>
											{% elseif permission.type=='integer' or permission.type=='float' %}
												<input class="m-0" type="number" name="permissions[{{ permission.id }}]" value="{{ permission.default }}">
											{% else %}
												<input class="m-0" type="text" name="permissions[{{ permission.id }}]" value="{{ permission.default }}">
											{% endif %}
										</div>
									</div>
								{% endfor %}
							</div>

							<div class="text-center pt-16">
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