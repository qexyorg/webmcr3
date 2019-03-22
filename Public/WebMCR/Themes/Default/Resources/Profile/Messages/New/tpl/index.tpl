<!DOCTYPE HTML>
<html>
<head>
	<title>{{pagename}} | {{__META__.sitename}}</title>

	{{include('header.tpl')}}

	<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

	<link href="{{__META__.theme_url}}css/autocomplete.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}js/autocomplete.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/Messages/New/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/Messages/New/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/Messages/New/js/script.js?1"></script>
</head>

<body>

{% set user_id = __USER__.id %}
{% set user_avatar = __USER__.avatar|avatar~'?'~random(99999) %}
{% set navbar_menu_active = 'profile' %}
{{ include('navbar.tpl') }}

<div class="container profile">
	<div class="header">

        {{ include('Resources/Users/tpl/stats-block.tpl') }}

		<div class="content-block">
			{% set profile_menu_active = 'mail' %}
			{{include('Resources/Profile/tpl/menu.tpl')}}

			<div class="profile-content">
				<div class="window wrapper m-0">
					<form method="post" class="message-new" action="{{ __META__.site_url }}profile/messages/new/create">

						<div class="input-block">
							<label for="to">Кому <b class="col-red" title="Обязательное поле">*</b></label>
							<input type="text" name="to" id="to" value="{{ login }}" class="autocomplete" required data-url="{{ __META__.site_url }}users/autocomplete/json" placeholder="Начните вводить">
						</div>

						<div class="input-block">
							<label for="subject">Тема <b class="col-red" title="Обязательное поле">*</b></label>
							<input type="text" name="subject" id="subject" placeholder="Тема сообщения" required>
						</div>

						<div class="input-block">
							<label for="text">Сообщение <b class="col-red" title="Обязательное поле">*</b></label>
							<textarea name="text" id="text" placeholder="Текст сообшения" required></textarea>
						</div>

						<div class="text-center">
							<button type="submit" class="btn btn-light-green">Отправить сообщение</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

{{include('footer.tpl')}}
</body>
</html>