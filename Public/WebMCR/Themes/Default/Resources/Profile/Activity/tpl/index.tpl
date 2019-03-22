<!DOCTYPE HTML>
<html>
<head>
	<title>{{pagename}} | {{__META__.sitename}}</title>

	{{include('header.tpl')}}

	<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Profile/Activity/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Profile/Activity/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Profile/Activity/js/script.js?1"></script>
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

			{% set profile_menu_active = 'activity' %}
			{{include('Resources/Profile/tpl/menu.tpl')}}

			<div class="profile-content">
				<div class="window wrapper m-0">
					<div class="content-tab active" data-id="activity">
						<div class="activity">

							<div class="activity-list">
                                {% if(not activity) %}
									<div class="activity-none">Нет доступной активности</div>
                                {% else %}
                                    {% for item in activity %}
										<div class="activity-id" data-id="{{item.id}}">
											<div class="block-left">
												<div class="icon-block">
													{% if __CONFIG__.logger.items[item.name].class is defined %}
														<i class="{{ __CONFIG__.logger.items[item.name].class }}"></i>
													{% else %}
														<i class="fa fa-question"></i>
													{% endif %}
												</div>
											</div>

											<div class="block-right">
                                                {% if __CONFIG__.logger.items[item.name].title is defined %}
													<div class="title">{{ __CONFIG__.logger.items[item.name].title }}</div>
                                                {% else %}
													<div class="title">{{ item.title }}</div>
                                                {% endif %}

												<div class="content">
                                                    <div class="text">{{ item.text|raw }}</div>
													<div class="date">— {{ item.date|dateToFormat }}</div>
												</div>
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
				</div>
			</div>
		</div>
	</div>
</div>

{{include('footer.tpl')}}
</body>
</html>