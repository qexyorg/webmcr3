<!DOCTYPE HTML>
<html>
<head>
	<title>{{pagename}} | {{__META__.sitename}}</title>

	{{include('header.tpl')}}

	<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/Messages/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/Messages/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/Messages/js/script.js?1"></script>
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
					<div class="content-tab active" data-id="mail">
						<div class="messages">
							<div class="messages-header">
								<div class="block-left">
									Всего сообщений: <span class="messages-num">{{messages_num}}</span>
								</div>

								<div class="block-right text-right">
									{% if __PERMISSION__.profile_messages_send %}
										<a href="{{__META__.site_url}}profile/messages/new" class="btn text-upper">Новое сообщение</a>
									{% endif %}
								</div>
							</div>

							<div class="message-list">

                                {% if(not messages) %}
									<div class="message-none">Нет доступных сообщений</div>
                                {% else %}
									<div class="message-id header">
										<div>Автор</div>
										<div>Тема сообщения</div>
										<div class="text-center">Ответов</div>
										<div>Последнее сообщение</div>
										<div>Действие</div>
									</div>

                                    {% for message in messages %}
										<div class="message-id body" data-id="{{message.link_id}}">
											<div title="{{message.user_login_create}}">
												<div class="avatar-block">
													<a href="{{__META__.site_url}}user/{{message.user_login_create}}" style="background-image: url('{{message.user_avatar_create|avatar}}?{{random(99999)}}');" target="_blank" class="window avatar {% if message.user_id_create|int==__USER__.id|int %}avatar-target-bg{% endif %}"></a>
												</div>
											</div>
											<div>
												<div class="subject-block" title="{{message.subject}}">
													<a href="{{__META__.site_url}}profile/messages/{{message.link_id}}">{{message.subject}}</a>
												</div>
												<div class="author">Автор: <a target="_blank" href="{{__META__.site_url}}user/{{message.user_login_create}}">{{message.user_login_create}}</a></div>
											</div>
											<div class="text-center">{{message.reply}}</div>
											<div title="{{message.date_update|dateToFormat}}">{{message.date_update|dateToFormat}}</div>
											<div class="actions">
												<ul>
                                                    {% if __PERMISSION__.profile_messages_lock or (__PERMISSION__.profile_messages_lock_self and __USER__.id|int==message.user_id_create|int) %}
                                                        {% if message.is_close|boolean %}
															<li><button title="Открыть" class="unlock btn btn-h20"><i class="fa fa-unlock"></i></button></li>
                                                        {% else %}
															<li><button title="Закрыть" class="lock btn btn-h20"><i class="fa fa-lock"></i></button></li>
                                                        {% endif %}
													{% endif %}

													{% if __PERMISSION__.profile_messages_remove %}
														<li><button title="Удалить" class="btn btn-h20 remove-message"><i class="fa fa-times"></i></button></li>
													{% endif %}
												</ul>
											</div>
										</div>
                                    {% endfor %}
                                {% endif %}
							</div>

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
						</div>
					</div>

					<div class="content-tab" data-id="example">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

{{include('footer.tpl')}}
</body>
</html>