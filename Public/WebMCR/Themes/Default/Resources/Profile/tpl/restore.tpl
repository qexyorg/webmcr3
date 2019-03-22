<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>
	</head>

	<body>

    {% set navbar_menu_active = '' %}
    {{ include('navbar.tpl') }}

		<div class="container restore">
			<form action="{{__META__.site_url}}restore/complete" class="w-40 mx-auto window" method="post">
				<div class="input-block">
					<label for="newpassword">Новый пароль</label>
					<input type="password" id="newpassword" name="newpassword" placeholder="Введите новый пароль" required>
				</div>

				<div class="input-block">
					<label for="repassword">Повторите пароль</label>
					<input type="password" id="repassword" name="repassword" placeholder="Введите пароль еще раз" required>
				</div>

				<input type="hidden" name="restore" value="{{token}}">

				<button type="submit" class="btn block text-upper">Подтвердить</button>
			</form>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>