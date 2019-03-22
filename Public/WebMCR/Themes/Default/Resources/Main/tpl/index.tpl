<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<script>
            {% if __CONFIG__.register.captcha %}
			var captcha_enable = true;
            {% else %}
			var captcha_enable = false;
            {% endif %}
		</script>

        {% if __CONFIG__.register.captcha %}
			<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
		{% endif %}
		<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>
		<script src="{{__META__.theme_url}}js/miniprofile.js?1"></script>
		<link href="{{__META__.theme_url}}css/miniprofile.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Main/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Main/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Main/js/script.js?1"></script>
	</head>

	<body>

    {% set navbar_menu_active = 'main' %}
    {{ include('navbar.tpl') }}

		<div class="header">
			<div class="container">
			</div>
		</div>

		<div class="container main">
			<div class="block-middle">
				<div class="block-left">
					<div class="text">
						<h3>{{__META__.sitename}}</h3>
						<p>{{__META__.sitedesc|raw}}</p>
					</div>
				</div>

				<div class="block-right">
					{% if(__USERCORE__.isAuth()) %}
						{{include('Resources/MiniProfile/mini-profile.tpl')}}
					{% else %}
						{{include('Resources/MiniProfile/login-form.tpl')}}
					{% endif %}
				</div>
			</div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>