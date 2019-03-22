<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
		<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>
		<script src="{{__META__.theme_url}}js/miniprofile.js?1"></script>
		<link href="{{__META__.theme_url}}css/miniprofile.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}css/likes.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}js/likes.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Comments/css/comments.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Comments/js/comments.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/News/View/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/News/View/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/News/View/js/script.js?1"></script>
	</head>

<body>

{% set navbar_menu_active = 'news' %}
{{ include('navbar.tpl') }}

	<div class="container main">
		<div class="block-middle">
			<div class="block-left">
				<div class="news-view window mb-0">
					<div class="news-header">
						<div class="title" title="{{new.title}}">{{new.title}}</div>
						<div class="actions">
							<ul>

									<li title="Симпатии">
                                        {% if __PERMISSION__.news_like %}
										<a href="#" class="like-trigger {% if new.is_liked is not null %}active{% endif %}" data-likes-url="{{__META__.site_url}}news/{{new.id}}/like">
											<i class="fa fa-heart"></i>
											<span class="like-num">{{new.likes}}</span>
										</a>
										{% else %}
											<a href="#" class="preventDefault cursor-default">
												<i class="fa fa-heart"></i>
												<span class="like-num">{{new.likes}}</span>
											</a>
                                        {% endif %}
									</li>
								<li title="Комментарии"><a href="{{__META__.site_url}}news/{{new.name}}.{{new.id}}#comment-block"><i class="fa fa-comment-o"></i> <span class="widget-comment-num" data-type="news">{{new.comments}}</span></a></li>
								<li title="Просмотры"><a href="#" class="view-trigger {% if new.is_view is not null %}active{% endif %}"><i class="fa fa-eye"></i> <span>{{new.views}}</span></a></li>
								<li title="Поделиться"><a href="#"><i class="fa fa-share-alt"></i></a></li>
							</ul>
						</div>
					</div>

					{% if new.image %}
						<div class="image">
							<div class="wrapper" style="background-image: url('{{new.image}}');"></div>
						</div>
					{% endif %}

					{% if new.text_short_html is not empty %}
					<div class="text-short">{{new.text_short_html|raw}}</div>
					{% endif %}

					{% if new.text_html is not empty %}
						<div class="text">{{new.text_html|raw}}</div>
					{% endif %}

					<div class="tags">
						<ul>
							{% for tag in news_tags %}
								<li><a href="{{ __META__.site_url }}news/tags-{{ tag.name }}" title="{{ tag.text }}">{{ tag.title }}</a></li>
							{% endfor %}
						</ul>
					</div>

					<div class="news-footer">
						<div class="dates">
							Опубликовано {{new.date_create|dateToFormat}}
							{% if new.date_update != new.date_create %}
							<i class="col-gray" title="Обновлено {{new.date_update|dateToFormat}}"></i>
							{% endif %}
						</div>
						<div class="author">
							Автор:
							{% if __PERMISSION__.user %}
								<a href="{{__META__.site_url}}user/{{new.user_login_create}}">{{new.user_login_create}}</a>
							{% else %}
                                {{new.user_login_create}}
							{% endif %}
						</div>
					</div>

                    {% set WIDGET_COMMENTS = comments('news', new.id, __CONFIG__.pagination.news.comments, 'news/'~new.name~'.'~new.id~'/comments/page-{PAGE}') %}
                    {{include('Resources/Comments/tpl/comments.tpl')}}
				</div>
			</div>

			<div class="block-right">
				{% if(__USERCORE__.isAuth()) %}
					{{include('Resources/MiniProfile/mini-profile.tpl')}}
				{% else %}
					{{include('Resources/MiniProfile/login-form.tpl')}}
				{% endif %}
			</div>
		</div>
	</div>

	{{include('footer.tpl')}}
</body>
</html>