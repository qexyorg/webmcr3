<!DOCTYPE HTML>
<html>
<head>
	<title>Восстановление доступа</title>

	<meta charset="utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

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

		.small { font-size: 11px; }
		.gray { color: #777; }

		.container {
			padding: 20px;
		}

		p {
			display: block;
		}

		.token {
			font-size: 20px;
			padding: 20px;
			text-align: center;
			box-sizing: border-box;
		}
	</style>
</head>

<body>

<div class="container">
	<p>Здравствуйте!</p>

	<p>Был произведен запрос на восстановление доступа к аккаунту <b>{{login}}</b> на сайте <a href="{{__META__.full_site_url}}">{{__META__.sitename}}</a></p>

	<p>Для изменения пароля, перейдите по ссылке, указанный ниже:</p>
	<h3 class="token"><b><a href="{{__META__.full_site_url}}/restore/{{token}}">{{__META__.full_site_url}}/restore/{{token}}</a></b></h3>

	<p class="small">Если это были не Вы, обратитесь к администрации</p>
	<br>
	<br>
	<p class="small gray">С уважением, администрация сайта <a href="{{__META__.full_site_url}}">{{__META__.sitename}}</a></p>

</div>

</body>
</html>