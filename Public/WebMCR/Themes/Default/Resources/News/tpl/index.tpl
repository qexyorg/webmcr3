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

		<link href="{{__META__.theme_url}}css/likes.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}js/likes.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/News/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/News/css/style-responsive.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/News/css/tags.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/News/js/script.js?1"></script>
	</head>

	<body>

    {% set navbar_menu_active = 'news' %}
    {{ include('navbar.tpl') }}

	<div class="container main">
		<div class="block-middle">
			<div class="block-left">
				<div class="news-list">

					{% if(not news) %}
						<div class="news-none">Нет доступных новостей</div>
					{% else %}
						{% for new in news %}
                            {{include('Resources/News/tpl/new-id.tpl')}}
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
				{% if(__USERCORE__.isAuth()) %}
					{{include('Resources/MiniProfile/mini-profile.tpl')}}
				{% else %}
					{{include('Resources/MiniProfile/login-form.tpl')}}
				{% endif %}

				<div class="side-block pt-16">
					<form method="GET" action="{{ __META__.site_url }}news/search-" class="wrapper window search-news">
						<div class="block-name">Поиск</div>

						<input type="search" name="search" value="{{ search }}" placeholder="Введите запрос">

						<button type="submit" class="btn block text-upper">Поиск</button>
					</form>
				</div>

				{% if tags %}
					<div class="side-block">
						<div class="wrapper window">
							<div class="block-name">Теги</div>

							<ul class="tags">
                                {% for tag in tags %}
									<li><a href="{{__META__.site_url}}news/tags-{{tag.name}}" title="{{tag.text}}">{{tag.title}}</a></li>
                                {% endfor %}
							</ul>
						</div>
					</div>
				{% endif %}
			</div>
		</div>
	</div>

	{{include('footer.tpl')}}
</body>
</html>