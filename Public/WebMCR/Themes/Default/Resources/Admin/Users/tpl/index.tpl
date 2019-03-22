<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/Users/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/Users/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/Users/js/script.js?1"></script>
	</head>

	<body>

   		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'users' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="users">
					<div class="user-list">


						{% for item in list %}
                            {{include('Resources/Admin/Users/tpl/item-id.tpl')}}
						{% endfor %}
					</div>

                    {% if pagination %}
						<div class="pagination-block window p-4 mt-20 text-center">
							<ul class="pagination">
                                {% for page in pagination %}
									<li class="{% if page.selected %}active{% endif %}">
										<a title="{{page.title}}" data-page="{{page.page}}" href="{{page.url}}">{{page.text}}</a>
									</li>
                                {% endfor %}
							</ul>
						</div>
                    {% endif %}
				</div>
			</div>
		</div>

		<div class="modal" data-target-id="banip">
			<div class="wrapper">
				<div class="modal-container window">
					<div class="title">Блокировка по IP адресу</div>
					<form method="post" class="body" action="{{ __META__.site_url }}admin/users/banip/">
						<div class="input-block">
							<label for="ip">IP адрес</label>
							<input type="text" id="ip" name="ip" placeholder="Введите IP адрес">
						</div>

						<div class="input-block">
							<label for="reason">Причина</label>
							<input type="text" id="reason" name="reason" placeholder="Введите причину блокировки">
						</div>

						<div class="text-right">
							<button class="btn mr-4" id="banip-trigger" type="submit">Заблокировать</button>
							<button class="btn btn-transparent close-modal" type="button">Отмена</button>
						</div>
					</form>
				</div>
			</div>

			<div class="wrapper-fix"></div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>