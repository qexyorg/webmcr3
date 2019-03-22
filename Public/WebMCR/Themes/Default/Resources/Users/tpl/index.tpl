<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
		<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>
		<script src="{{__META__.theme_url}}js/miniprofile.js?1"></script>
		<link href="{{__META__.theme_url}}css/miniprofile.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}css/side-block.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Users/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Users/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Users/js/script.js?1"></script>
	</head>

	<body>

    {{ include('navbar.tpl') }}

	<div class="container main">
		<div class="block-middle">
			<div class="block-left">
				<div class="user-list">

					{% if(not users) %}
						<div class="user-none">Нет доступных пользователей</div>
					{% else %}
						{% for user in users %}
							{% set userlink = __META__.site_url~'user/'~user.login %}
							<div class="user-id window" data-id="{{user.id}}">
								<div class="avatar-block">
									<a href="{{ userlink }}" class="avatar window {% if user.id==__USER__.id %}avatar-target-bg{% endif %}" style="background-image: url('{{user.avatar|avatar}}?{{random(99999)}}');"></a>
								</div>

								<div class="info-block">
									<a href="{{ userlink }}" class="login">{{ user.login }}</a>
									<div class="group"><span title="{{ user.group_text }}">{{ user.group_title }}</span></div>
								</div>

								<div class="stats-block">
									<ul>
										<li>
											<div class="value"><i class="fa fa-bullhorn"></i> {{ user.subscribers|int }}</div>
											<div class="text">Подписчиков</div>
										</li>

										<li>
											<div class="value"><i class="fa fa-heart"></i> {{ user.likes|int }}</div>
											<div class="text">Симпатий</div>
										</li>

										<li>
											<div class="value"><i class="fa fa-comments"></i> {{ user.comments|int }}</div>
											<div class="text">Комментариев</div>
										</li>
									</ul>
								</div>

								<div class="arrow-block">
									<div class="d-none short-info-hidden">{% set user = user|merge({ avatar: user.avatar|avatar, gender: user.gender|gender, birthday: user.birthday|date('d.m.Y') }) %}{{ user|json_encode()|raw }}</div>
									<a href="#" class="show-short-info"><i class="fa fa-chevron-right"></i></a>
								</div>
							</div>
						{% endfor %}

						{% if pagination %}
							<div class="pagination-block window p-4 text-center">
								<ul class="pagination">
									{% for page in pagination %}
										<li class="{% if page.selected %}active{% endif %}">
											<a title="{{page.title}}" data-page="{{page.page}}" href="{{page.url}}">{{page.text}}</a>
										</li>
									{% endfor %}
								</ul>
							</div>
						{% endif %}
					{% endif %}
				</div>
			</div>

			<div class="block-right">

				<div class="side-block pb-16" data-user-id="{{ __USER__.id }}" data-id="0" id="user-sub-info">
					<div class="wrapper window">
						<a href="#" id="user-sub-close"><i class="fa fa-eye-slash"></i></a>

						<div class="avatar-block">
							<a href="#" class="avatar window"></a>
						</div>

						<div class="info-block">
							<a href="#" class="login"></a>
							<div class="name"></div>

							<div class="group"><span title=""></span></div>

							<div class="gender"></div>

							<div class="birthday"></div>

							<div class="about"></div>
						</div>
					</div>
				</div>

				{% if(__USERCORE__.isAuth()) %}
					{{include('Resources/MiniProfile/mini-profile.tpl')}}
				{% else %}
					{{include('Resources/MiniProfile/login-form.tpl')}}
				{% endif %}

				{% if __PERMISSION__.users_search %}
					<div class="side-block pt-16">
						<form method="get" class="wrapper window search-form" action="{{ __META__.site_url }}users/" data-form-separator="/" data-form-route="1">
							<div class="block-name">
								Поиск
							</div>

							<input type="search" name="search" value="{{ search }}" pattern="^[a-z0-9_\.]{1,}$" data-pattern-text="Необходимо заполнить запрос поиска" placeholder="Введите запрос">

							<button type="submit" class="btn block text-upper">Найти</button>
						</form>
					</div>
				{% endif %}
			</div>
		</div>
	</div>

	{{include('footer.tpl')}}
</body>
</html>