<!DOCTYPE HTML>
<html>
<head>
	<title>{{pagename}} | {{__META__.sitename}}</title>

	{{include('header.tpl')}}

	<script src="{{__META__.theme_url}}js/datepicker.js?1"></script>
	<link href="{{__META__.theme_url}}css/datepicker.css?1" rel="stylesheet">

	<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/Settings/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/Settings/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/Settings/js/script.js?1"></script>
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

			{% set profile_menu_active = 'settings' %}
			{{include('Resources/Profile/tpl/menu.tpl')}}

			<div class="profile-content">
				<div class="window wrapper p-0 m-0">
					<div class="content-tab active" data-id="settings">
						<form method="post" action="{{ __META__.site_url }}profile/settings/save" class="settings">

							<div class="section">
								<div class="section-name">Личные данные</div>

								<div class="section-row">
									<label for="firstname" class="setting-name">Имя</label>
									<div class="setting-option">
										<input type="text" id="firstname" name="firstname" class="m-0" value="{{ __USER__.firstname }}" placeholder="Введите ваше имя">
									</div>
								</div>

								<div class="section-row">
									<label for="lastname" class="setting-name">Фамилия</label>
									<div class="setting-option">
										<input type="text" id="lastname" name="lastname" class="m-0" value="{{ __USER__.lastname }}" placeholder="Введите вашу фамилию">
									</div>
								</div>

								<div class="section-row">
									<label for="birthday" class="setting-name">
										Дата рождения
										<span class="subname">Изменить дату можно с помощью окна выбора даты, появляющегося по клику на поле ввода</span>
									</label>
									<div class="setting-option">
										<input type="text" id="birthday" name="birthday" class="m-0 datepicker" value="{{ __USER__.birthday|date("d.m.Y") }}" maxlength="10" placeholder="Введите дату рождения">
									</div>
								</div>

								<div class="section-row">
									<label for="gender" class="setting-name">Пол</label>
									<div class="setting-option">
										<select name="gender" class="m-0" id="gender">
											<option value="0">Мужской</option>
											<option value="1" {% if __USER__.gender|boolean %}selected{% endif %}>Женский</option>
										</select>
									</div>
								</div>

								<div class="section-row">
									<label for="about" class="setting-name">О себе</label>
									<div class="setting-option">
										<textarea name="about" id="about" placeholder="Введите информацию о себе">{{ __USER__.about }}</textarea>
									</div>
								</div>
							</div>

							<div class="section">
								<div class="section-name">Настройки безопастности</div>

								<div class="section-row">
									<label for="login" class="setting-name">
										Логин
										<span class="subname">После изменения логина, вам будет отправлено подтверждение на текущий E-Mail адрес</span>
									</label>
									<div class="setting-option">
										<input type="text" id="login" name="login" class="m-0" value="{{ __USER__.login }}" placeholder="Введите новый логин">
									</div>
								</div>

								<div class="section-row">
									<label for="password" class="setting-name">
										Пароль
										<span class="subname">
											Используйте сложные пароли состоящие не менее чем из 6
											символов и символов разной раскладки. После изменения, Вам
											будет отправлено подтверждение на текущий E-Mail адрес
										</span>
									</label>
									<div class="setting-option">
										<input type="password" id="password" name="password" class="m-0" placeholder="Введите новый пароль">
									</div>
								</div>

								<div class="section-row">
									<label for="email" class="setting-name">
										E-Mail
										<span class="subname">После изменения E-Mail адреса, вам будет отправлено подтверждение на текущий E-Mail адрес</span>
									</label>
									<div class="setting-option">
										<input type="email" id="email" name="email" class="m-0" value="{{ __USER__.email }}" placeholder="Введите новый E-Mail адрес">
									</div>
								</div>

								<div class="p-16 text-center">
									<button type="submit" class="btn btn-light-green text-upper w-25">Сохранить</button>
								</div>

							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{{include('footer.tpl')}}
</body>
</html>