<!DOCTYPE HTML>
<html>
<head>
	<title>{{pagename}} | {{__META__.sitename}}</title>

	{{include('header.tpl')}}

	<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

	<link href="{{__META__.theme_url}}css/comments.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}js/comments.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/Messages/View/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/Messages/View/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/Messages/View/js/script.js?1"></script>
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
					<div class="message-view">
						<div class="message-subject">
							<div class="block-left">
								<div class="subject">{{message.subject}}</div>

								<div class="text">{{message.text_html|raw}}</div>
							</div>

							<div class="block-right">
								<div class="read">
									{% if message.is_read|boolean %}
										<i class="fa fa-check-square-o cursor-help" title="Прочитано"></i>
									{% else %}
										<i class="fa fa-square-o cursor-help" title="Не прочитано"></i>
									{% endif %}
								</div>

								<div class="author">
									<a href="{{ __META__.site_url }}user/{{ message.user_login_create }}">{{ message.user_login_create }}</a>
								</div>

								<div class="avatar-block">
									<a href="{{ __META__.site_url }}user/{{ message.user_login_create }}" class="avatar" style="background-image: url({{ message.user_avatar_create|avatar }});"></a>
								</div>

								<div class="date">{{ message.date_create|dateToFormat }}</div>
							</div>
						</div>

						<div class="message-reply">
							<div class="comments-list" id="comment-block">
                                {% if message.is_close|boolean %}
									<div class="comment-access"><i class="fa fa-lock"></i> Тема закрыта</div>
                                {% elseif __PERMISSION__.profile_messages_reply_add %}
                                    {{include('Resources/Profile/Messages/View/tpl/reply-form.tpl')}}
								{% else %}
									<div class="comment-access">У вас недостаточно прав для добавления ответов</div>
                                {% endif %}

                                {% if __PERMISSION__.profile_messages_reply_view %}
                                    {% if not reply_list %}
										<div class="comments-none">Нет доступных ответов</div>
                                    {% else %}
										<div class="comment-num pt-24 text-upper">Ответы: <span class="comments_num_element">{{reply_num}}</span></div>

										<div class="comments-block">
                                            {% for reply in reply_list %}
                                                {{include('Resources/Profile/Messages/View/tpl/reply-id.tpl')}}
                                            {% endfor %}
										</div>
                                    {% endif %}

                                    {% if reply_pagination %}
										<div class="pagination-block pb-0 pt-16 text-center">
											<ul class="pagination">
                                                {% for page in reply_pagination %}
													<li class="{% if page.selected %}active{% endif %}">
														<a title="{{page.title}}" data-page="{{page.page}}" href="{{page.url}}">{{page.text}}</a>
													</li>
                                                {% endfor %}
											</ul>
										</div>
                                    {% endif %}
                                {% else %}
									<div class="comment-access pb-16">У вас недостаточно прав для просмотра ответов</div>
                                {% endif %}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{{include('footer.tpl')}}
</body>
</html>