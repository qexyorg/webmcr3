<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Comments/css/comments.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Comments/js/comments.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Subscribes/css/Subscribes.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Subscribes/js/Subscribes.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Users/View/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Users/View/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Users/View/js/script.js?1"></script>
	</head>

	<body>

    {% set user_id = user.id %}
    {% set user_avatar = user.avatar|avatar~'?'~random(99999) %}
    {% set navbar_menu_active = '' %}
    {{ include('navbar.tpl') }}

		<div class="container user">
			<div class="header">

				{{ include('Resources/Users/tpl/stats-block.tpl') }}

				<div class="content-block">

					<div class="left-side">
						<div class="window block-id">
							<ul>
								<li>
									<i class="fa fa-users"></i>
									<span class="name">Группа</span>
									<span class="value cursor-help" title="{{ group.text }}">— {{ group.title }}</span>
								</li>

                                {% if user.birthday %}
									<li>
										<i class="fa fa-birthday-cake"></i>
										<span class="name">День рождения</span>
										<span class="value">— {{ user.birthday|date('d.m.Y') }}</span>
									</li>
                                {% endif %}

                                {% if user.about %}
									<li>
										<i class="fa fa-commenting"></i>
										<span class="name">О себе</span>
										<span class="value">— {{ user.about }}</span>
									</li>
                                {% endif %}
							</ul>
						</div>

						<div class="window block-id mt-20">
							<a href="{{ __META__.site_url }}users/"><i class="fa fa-angle-left mr-4"></i> Все пользователи</a>
						</div>
					</div>

					<div class="user-content">
						<div class="window wrapper m-0">
							<div class="info-header">
								<div>
									<div class="user-login">{{user.login}}</div>

									<div class="user-names">
                                        {% if user.firstname or user.lastname %}
                                            {{user.firstname}} {{user.lastname}} •
                                        {% endif %}

                                        {{user.gender|gender}}

                                        {% if user.birthday %}
                                            {% set user_age = user.birthday|age %}
											• {{ user_age ~ ' ' ~ case(user_age, ' год', ' года', ' лет') }}
                                        {% endif %}
									</div>
								</div>
								<div class="text-right">
									{% if user.id != __USER__.id and __PERMISSION__.users_subscribe %}
										{% set WIDGET_SUBSCRIBES = subscribes('users', user.id) %}
										{{include('Resources/Subscribes/tpl/subscribes.tpl')}}
									{% endif %}

                                    {% if user.id != __USER__.id and __PERMISSION__.profile_messages_send %}
										<a href="{{ __META__.site_url }}profile/messages/new/{{user.login}}/" class="btn" title="Личное сообщение"><i class="fa fa-envelope-open"></i></a>
                                    {% endif %}
								</div>
							</div>

                            {% set WIDGET_COMMENTS = comments('users', user.id, __CONFIG__.pagination.users.comments, 'user/'~user.login~'/page-{PAGE}') %}
                            {{include('Resources/Comments/tpl/comments.tpl')}}
						</div>
					</div>
				</div>
			</div>
		</div>

		{{include('footer.tpl')}}
	</body>
</html>