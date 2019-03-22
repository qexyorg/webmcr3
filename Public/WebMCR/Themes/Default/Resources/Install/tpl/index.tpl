<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Install/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Install/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Install/js/script.js?1"></script>
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
						<li class="active"><a href="{{__META__.site_url}}install/">Установка</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container install">

		<div class="header">
			<div class="title">Установка</div>
			<div class="text">
				Добро пожаловать в мастер установки CMS WebMCR.<br>
				Для продолжения, следуйте инструкциям установщика
			</div>
		</div>

		<div class="body window">
			<div class="block-id">
				<div class="block-left">PHP версия <b>>=7.0.0</b></div>

                {% set complete = true %}

				<div class="block-right">
                    {% if version %}
						<span class="col-green">OK</span>
                    {% else %}
                        {% set complete = false %}
						<span class="col-red">ОБНОВИТЕ PHP</span>
                    {% endif %}
				</div>
			</div>

			{% for file,param in access %}
				<div class="block-id">
					<div class="block-left">Права на чтение и запись папки <b>{{ file }}</b></div>

					<div class="block-right">
                        {% if fileAccess(file, param) %}
							<span class="col-green">OK</span>
                        {% else %}
                            {% set complete = false %}
							<span class="col-red">ВЫСТАВИТЕ ПРАВА</span>
                        {% endif %}
					</div>
				</div>
			{% endfor %}

			<div class="py-20 text-center">
                {% if complete %}
					<span class="col-green">Похоже, что всё хорошо. Выберите дальнейшее действие</span>
				{% else %}
					<span class="col-red">Сервер не удовлетворяет все условия установщика. Исправьте ошибки и повторите попытку</span>
				{% endif %}
			</div>

            {% if complete %}
				<div class="pt-20 d-grid grid-gap-20 grid-template-columns-2-1fr align-items-center">
					{#<form action="{{ __META__.site_url }}install/reinstall/start" data-next="{{ __META__.site_url }}install/reinstall/step_1" method="POST" class="block-left">
						<button class="btn text-upper" id="install-reinstall">Переустановка</button>
					</form>#}
					<div class="block-left"></div>

					<form action="{{ __META__.site_url }}install/start" data-next="{{ __META__.site_url }}install/step_1" method="POST" class="block-right text-right">
							<button class="btn btn-light-green text-upper" id="install-start">Начать установку</button>
					</form>
				</div>
            {% endif %}
		</div>

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