<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/Settings/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/Settings/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/Settings/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'settings' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<form method="POST" action="{{ __META__.site_url }}admin/settings/save" class="settings window w-70 mx-auto">
					<div class="tabs">
						<ul class="tab-links">
							<li class="active" data-id="main"><a href="#">Основные</a></li>

							<li data-id="sync"><a href="#">Взаимодействие</a></li>

							<li data-id="money"><a href="#">Экономика</a></li>

							<li data-id="nav"><a href="#">Навигация</a></li>

							<li data-id="mail"><a href="#">Почта</a></li>

							<li data-id="security"><a href="#">Безопасность</a></li>
						</ul>

						<div class="tab-list">
                            {{include('Resources/Admin/Settings/tpl/tab-main.tpl')}}

                            {{include('Resources/Admin/Settings/tpl/tab-sync.tpl')}}

                            {{include('Resources/Admin/Settings/tpl/tab-money.tpl')}}

                            {{include('Resources/Admin/Settings/tpl/tab-nav.tpl')}}

                            {{include('Resources/Admin/Settings/tpl/tab-mail.tpl')}}

                            {{include('Resources/Admin/Settings/tpl/tab-security.tpl')}}

							<div class="text-center">
								<button class="btn btn-light-green" type="submit">Сохранить</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>