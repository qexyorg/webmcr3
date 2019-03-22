<!DOCTYPE HTML>
<html>
<head>
	<title>Подтверждение изменения настроек безопасности</title>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<style>
		* { margin: 0; padding: 0; font-size: 14px; }

		body {
			color: #424242;
		}

		div { box-sizing: border-box; }

		a {
			text-decoration: none;
			color: #03A9F4;
			box-sizing: border-box;
		}

		p {
			display: block;
		}

		.small { font-size: 11px; }
		.gray { color: #777; }

		.container {
			padding: 20px;
		}
	</style>
</head>

<body>

	<div class="container">
		<p>Здравствуйте, {{ login }}!</p>

		<p>Ваши настройки безопасности на сайте <a href="{{__META__.full_site_url}}">{{__META__.sitename}}</a> были изменены</p>
		<p>Если это были Вы, перейдите по ссылке ниже:</p>
		<br>
		<p style="text-align: center;">
			<a href="{{__META__.full_site_url}}/profile/settings/security/{{ token }}">
				<h3>{{__META__.full_site_url}}/profile/settings/security/{{ token }}</h3>
			</a>
		</p>

		<br>
		<br>
		<br>
		<p class="small">Если это были не Вы, обратитесь к администрации</p>
		<br>
		<br>
		<p class="small gray">С уважением, администрация сайта <a href="{{__META__.full_site_url}}">{{__META__.sitename}}</a></p>
	</div>

</body>
</html>