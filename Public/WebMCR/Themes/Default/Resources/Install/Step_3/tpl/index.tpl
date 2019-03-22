<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Install/Step_3/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Install/Step_3/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Install/Step_3/js/script.js?1"></script>
	</head>

	<body>

	<div class="navbar">
		<div class="container">
			<div class="navbar-wrapper">
				<div class="block-left">
					<ul>
						<li><a class="mobile" href="#"><i class="fa fa-bars"></i></a></li>
						<li><a class="brand" href="{{__META__.site_url}}">{{__META__.sitename}}</a></li>
					</ul>
				</div>

				<div class="block-right">
					<ul>
						<li><a href="{{__META__.site_url}}install">Переустановить</a></li>
						<li class="active"><a href="{{__META__.site_url}}install/step_3">Шаг 3</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container install">

		<div class="header">
			<div class="title">Шаг 3</div>
			<div class="text">
				Введите данные главного администратора.<br>
				Данный пользователь будет наделен всеми доступными привилегиями на сайте
				и будет иметь полный доступ к панели управления WebMCR
			</div>
		</div>

		<form method="POST" action="{{ __META__.site_url }}install/step_3/submit" class="body window">
			<div class="block-id">
				<div class="block-left">
					<div class="title">Логин</div>

					<div class="text">
						Введите логин администратора
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="login" placeholder="Введите логин">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">E-Mail</div>

					<div class="text">
						Введите E-Mail адрес администратора
					</div>
				</div>

				<div class="block-right">
					<input type="email" class="m-0" name="email" placeholder="Введите E-Mail">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Пароль</div>

					<div class="text">
						Введите пароль администратора. <b class="col-red">Внимание!</b> Администратор - это самый главный
						пользователь на сайте, который имеет полный доступ к его управлению,
						по этому выбирайте сложный пароль, состоящий из букв, цифр и знаков.
					</div>
				</div>

				<div class="block-right">
					<input type="password" class="m-0" name="password" placeholder="Введите пароль">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Повторите пароль</div>

					<div class="text">
						Введите пароль администратор еще раз
					</div>
				</div>

				<div class="block-right">
					<input type="password" class="m-0" name="repassword" placeholder="Введите пароль">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Авторизоваться после установки</div>

					<div class="text">
						Произвести авторизацию на сайте сразу после установки.
						Вы будете авторизованы автоматически под созданным администратором
					</div>
				</div>

				<div class="block-right">
					<select name="auth">
						<option value="0">Нет</option>
						<option value="1" selected>Да</option>
					</select>
				</div>
			</div>

			<div class="d-grid grid-gap-20 grid-template-columns-2-1fr align-items-center">
				<div class="block-left"></div>

				<div class="block-right text-right">
					<button class="btn btn-light-green text-upper" data-next="{{ __META__.site_url }}install/finish" id="submit-step-3">Завершить</button>
				</div>
			</div>
		</form>

	</div>

	<footer>
		<div class="container">
			<div class="row">
				<div class="col-w-33">
					<div class="title">Социальные сети</div>
					<ul>
						<li><a href="https://vk.com/webmcr" target="_blank">ВКонтакте</a></li>
						<li><a href="https://twitter.com/webmcr_official" target="_blank">Twitter</a></li>
					</ul>
				</div>

				<div class="col-w-33 text-center">
					<div class="title">Обратная связь</div>
					<ul>
						<li><a href="https://vk.com/qexyorg" target="_blank">Разработчик</a></li>
						<li><a href="mailto:admin@qexy.org" target="_blank">Реклама</a></li>
					</ul>
				</div>

				<div class="col-w-33 text-right">
					<div class="title">Полезные ссылки</div>
					<ul>
						<li><a href="https://webmcr.ru" target="_blank">Официальный сайт</a></li>
						<li><a href="https://github.com/qexyorg/WebMCR" target="_blank">GitHub</a></li>
						<li><a href="https://github.com/qexyorg/WebMCR/wiki" target="_blank">Wiki</a></li>
					</ul>
				</div>
			</div>

			<div class="subinfo"><?/*Проекты с удаленной записью ниже не поддерживаются сообществом и разработчиками*/?>
				Полное или частичное копирование сайта запрещено. WebMCR © <?=date("Y")?> <a href="https://qexy.org" target="_blank">Qexy</a>
			</div>
		</div>
	</footer>

    {{include('alerts.tpl')}}

		<!-- Page scroll top -->
		<a href="body" data-location="0" class="global-scroll scroll-to"><i class="fa fa-arrow-circle-o-up"></i></a>
	</body>
</html>