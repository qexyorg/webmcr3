<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Comments/css/comments.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Comments/js/comments.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Profile/css/style.css?1" rel="stylesheet">

		<link href="{{__META__.theme_url}}Resources/Profile/css/style-responsive.css?1" rel="stylesheet">

		<script src="{{__META__.theme_url}}Resources/Profile/js/script.js?1"></script>
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

					{% set profile_menu_active = 'info' %}
					{{include('Resources/Profile/tpl/menu.tpl')}}

					<div class="profile-content">
						<div class="window wrapper m-0">
							<div class="content-tab active" data-id="info">
								{{include('Resources/Profile/tpl/profile-info.tpl')}}

                                {% set WIDGET_COMMENTS = comments('users', __USER__.id, __CONFIG__.pagination.profile.comments, 'profile/comments/page-{PAGE}', 'profile') %}
                                {{include('Resources/Comments/tpl/comments.tpl')}}
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