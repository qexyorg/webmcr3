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

		<link href="{{__META__.theme_url}}css/comments.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}js/comments.js?1"></script>

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
								<li title="Симпатии"><a href="#" class="like-trigger {% if new.is_liked is not null %}active{% endif %}" data-likes-url="{{__META__.site_url}}news/{{new.id}}/like"><i class="fa fa-heart"></i> <span class="like-num">{{new.likes}}</span></a></li>
								<li title="Комментарии"><a href="{{__META__.site_url}}news/{{new.name}}.{{new.id}}#comment-block"><i class="fa fa-comment-o"></i> <span class="comments_num_element">{{new.comments}}</span></a></li>
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

					<div class="news-footer">
						<div class="dates">
							Опубликовано {{new.date_create|dateToFormat}}
							{% if new.date_update != new.date_create %}
							<i class="col-gray" title="Обновлено {{new.date_update|dateToFormat}}"></i>
							{% endif %}
						</div>
						<div class="author">
							Автор: <a href="{{__META__.site_url}}user/{{new.user_login_create}}">{{new.user_login_create}}</a>
						</div>
					</div>

					<div class="comments-list" id="comment-block">
						{% if __PERMISSION__.news_comment_add %}
							{{include('Resources/News/View/tpl/comment-form.tpl')}}
						{% else %}
							<div class="comment-access">У вас недостаточно прав для добавления комментариев</div>
						{% endif %}

						{% if __PERMISSION__.news_comments_view %}
							{% if not comments %}
								<div class="comments-none">Нет доступных комментариев</div>
							{% else %}
								<div class="comment-num pt-24 text-upper">Комментарии: <span class="comments_num_element">{{comments_num}}</span></div>

								<div class="comments-block">
									{% for comment in comments %}
										{{include('Resources/News/View/tpl/comment-id.tpl')}}
									{% endfor %}
								</div>
							{% endif %}

							{% if pagination %}
								<div class="pagination-block pb-0 pt-16 text-center">
									<ul class="pagination">
										{% for page in pagination %}
										<li class="{% if page.selected %}active{% endif %}">
											<a title="{{page.title}}" data-page="{{page.page}}" href="{{page.url}}">{{page.text}}</a>
										</li>
										{% endfor %}
									</ul>
								</div>
							{% endif %}
						{% else %}
							<div class="comment-access pb-16">У вас недостаточно прав для просмотра комментариев</div>
						{% endif %}
					</div>
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