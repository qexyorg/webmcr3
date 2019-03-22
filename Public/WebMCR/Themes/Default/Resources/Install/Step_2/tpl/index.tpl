<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Install/Step_2/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Install/Step_2/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Install/Step_2/js/script.js?1"></script>
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
						<li class="active"><a href="{{__META__.site_url}}install/step_2">Шаг 2</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container install">

		<div class="header">
			<div class="title">Шаг 2</div>
			<div class="text">
				Укажите настройки сайта.<br>
				Все указанные настройки можно будет переопределить в панели управления администратора
			</div>
		</div>

		<form method="POST" action="{{ __META__.site_url }}install/step_2/submit" class="body window">

			<div class="block-id">
				<div class="block-left">
					<div class="title">Название сайта</div>

					<div class="text">
						Введите название сайта. Отображается в шапке и заголовке страницы
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="sitename" value="{{ __META__.sitename }}" placeholder="Введите название сайта">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Описание сайта</div>

					<div class="text">
						Введите описание сайта. Используется поисковыми системами
					</div>
				</div>

				<div class="block-right">
					<textarea name="sitedesc" class="m-0" placeholder="Введите описание сайта">{{ __META__.sitedesc }}</textarea>
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Ключевые слова сайта</div>

					<div class="text">
						Введите описание сайта. Используется поисковыми системами
					</div>
				</div>

				<div class="block-right">
					<input type="text" class="m-0" name="sitekeys" value="{{ __META__.sitekeys }}" placeholder="Введите ключевые слова сайта">
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Шаблон</div>

					<div class="text">
						Выберите используемый шаблон сайта
					</div>
				</div>

				<div class="block-right">
					<select name="theme">
                        {% for theme in themes %}
							<option value="{{ theme }}">{{ theme }}</option>
                        {% endfor %}
					</select>
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Алгоритм хэширования паролей</div>

					<div class="text">
						Выберите алгоритм, который будет использовать WebMCR
						для создания хэшей пароля при регистрации, авторизации
						и сбросе
					</div>
				</div>

				<div class="block-right">
					<select class="m-0" name="algo">
                        {% for algo in algos %}
							<option value="{{ algo }}" {% if algo|upper == 'MD5' %}selected{% endif %}>{{ algo|upper }}</option>
                        {% endfor %}
					</select>
				</div>
			</div>

			<div class="block-id">
				<div class="block-left">
					<div class="title">Совместимость</div>

					<div class="text">
						Выберите логику работы с пользователями, которую будет использовать
						WebMCR для взаимодействия с пользователями
					</div>
				</div>

				<div class="block-right">
					<select class="m-0" name="logic">
                        {% for logic in logics %}
							<option value="{{ logic }}">{{ logic }}</option>
                        {% endfor %}
					</select>
				</div>
			</div>

			<div class="d-grid grid-gap-20 grid-template-columns-2-1fr align-items-center">
				<div class="block-left"></div>

				<div class="block-right text-right">
					<button class="btn btn-light-green text-upper" data-next="{{ __META__.site_url }}install/step_3" id="submit-step-2">Продолжить</button>
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