<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Install/Finish/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Install/Finish/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Install/Finish/js/script.js?1"></script>
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
						<li class="active"><a href="{{__META__.site_url}}install/finish">Окончание установки</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="container install">

		<div class="header">
			<div class="title">Поздравляем!</div>
			<div class="text">
				Установка произведена успешно.<br>
				Ознакомьтесь с информацией ниже и произведите отключение установщика
			</div>
		</div>

		<div class="body window">
			<p class="m-0">Благодарим Вас за установку CMS WebMCR</p>

			<div class="py-20" id="install-actions">
				<div>
					Нажмите одну из кнопок ниже, чтобы отключить установщик.<br>
					<b class="col-red">Внимание!</b> Отключение установщика очень важный процесс, который защищает сайт от повторной установки
				</div>

				<div class="pt-20 text-center">
					<button data-action="{{ __META__.site_url }}install/disable" class="btn" id="disable-install">Отключить установщик</button>
					<button data-action="{{ __META__.site_url }}install/remove" class="btn" id="remove-install">Удалить установщик</button>
				</div>
			</div>

			<div class="py-20" id="after-install">
				<div>Выберите дальнейшее действие</div>

				<div class="text-center pt-20">
					<a href="{{ __META__.site_url }}" class="btn">Перейти на главную сайта</a>
					<a href="{{ __META__.site_url }}" class="btn">Перейти в панель управления</a>
				</div>
			</div>

			<iframe src="https://metrics.qexy.org/?d={{ __META__.full_site_url }}" style="width:0;height:0;margin:auto;margin-top:20px;display:block;" scrolling="no" sandbox="allow-scripts" frameborder="0"></iframe>


			<div class="text-center">
				<a href="#" class="toggle-class d-inline-block py-20 preventDefault" data-element="#donate" data-classname="d-none">Пожертвования</a>
			</div>

			<div class="bg-light-green p-16 d-none" id="donate">
				<p class="m-0">
					WebMCR - это некоммерческий проект с открытым исходным кодом. Вся разработка идет на энтузиазме,
					а ваши пожертвования помогают нам не останавливаться на достигнутом и продолжать его развитие.
				</p>

				<ul class="px-20 pt-20">
					<li>Qiwi: +79006560410</li>
					<li>Visa: 4693 9575 5730 3588</li>
					<li>MasterCard: 5381 1050 1529 9451</li>
					<li>Yandex Money: 410011196980492</li>
					<li>WebMoney: R168618671783</li>
				</ul>
			</div>
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
