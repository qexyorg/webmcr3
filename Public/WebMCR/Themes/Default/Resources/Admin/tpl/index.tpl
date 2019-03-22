<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/css/main.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/main-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/main.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'main' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="main-content">
                    {{ include('Resources/Admin/tpl/stats.tpl') }}

					<iframe src="https://webmcr.ru/api/cms/cp/?d={{ __META__.full_site_url }}" style="width:468px;height:60px;margin:auto;margin-top:20px;display:block;" scrolling="no" sandbox="allow-scripts" frameborder="0"></iframe>
				</div>
			</div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>