<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Install/Step_1/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Install/Step_1/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Install/Step_1/js/script.js?1"></script>
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
						<li class="active"><a href="{{__META__.site_url}}install/step_1">Шаг 1</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container install">

		<div class="header">
			<div class="title">Шаг 1</div>
			<div class="text">
				Введите необходимые настройки базы данных.<br>
				Если желаете проверить соединение, нажмите кнопку "Проверить соединение", внизу страницы.<br>
				После указания всех настроек и проверок, нажмите кнопку "Продолжить"
			</div>
		</div>

		<form method="POST" action="{{ __META__.site_url }}install/step_1/submit" class="body window">
			<div class="block-id">
				<div class="block-left">
					<div class="title">Драйвер баз данных</div>

					<div class="text">
						Выберите тип используемой базы данных
					</div>
				</div>

				<div class="block-right">
					<select class="m-0" name="engine">
						<option value="mysqli">MySQLi</option>
						<option value="postgres">PostgreSQL</option>
						<option value="mysql">MySQL (Устаревшее)</option>
					</select>
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Сервер баз данных</div>

					<div class="text">
						Введите адрес сервера баз данных. Оставьте значение по умолчанию, если сайт и база на одном сервере.
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="host" value="localhost" placeholder="Введите адрес сервера баз данных">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Порт сервера баз данных</div>

					<div class="text">
						Введите порт сервера баз данных. По умолчанию для MySQL/MySQLi - 3306, для PostgreSQL - 5432
					</div>
				</div>

				<div class="block-right">
					<input type="number" class="m-0" name="port" value="3306" placeholder="Введите порт сервера баз данных">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Кодировка баз данных</div>

					<div class="text">
						Введите кодировку соединения с сервером баз данных
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="charset" value="utf8mb4" placeholder="Введите кодировку">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Имя базы данных</div>

					<div class="text">
						Введите имя базы данных, которая будет использоваться движком WebMCR
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="database" value="webmcr" placeholder="Введите имя базы данных">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Пользователь баз данных</div>

					<div class="text">
						Введите имя пользователя базами данных
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="user" value="root" placeholder="Введите имя пользователя">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Пароль пользователя базами данных</div>

					<div class="text">
						Введите пароль пользователя, используемый для соединения с базой данных
					</div>
				</div>

				<div class="block-right">
					<input type="password" class="m-0" name="password" placeholder="Введите пароль пользователя баз данных">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Очистить базу перед созданием таблиц WebMCR</div>

					<div class="text">
						<b class="col-red">Внимание!</b> Если вы ранее устанавливали CMS WebMCR, все ее данные будут стерты
					</div>
				</div>

				<div class="block-right">
					<select name="clear">
						<option value="0">Нет</option>
						<option value="1">Да</option>
					</select>
				</div>
			</div>

			<div class="d-grid grid-gap-20 grid-template-columns-2-1fr align-items-center">
				<div class="block-left">
					<button data-action="{{ __META__.site_url }}install/dbconnect" class="btn text-upper" id="check-submit">Проверить соединение</button>
				</div>

				<div class="block-right text-right">
					<button class="btn btn-light-green text-upper" data-next="{{ __META__.site_url }}install/step_2" id="submit-step-1">Продолжить</button>
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