<!DOCTYPE HTML>
<html>
	<head>
		<title>{{pagename}} | {{__META__.sitename}}</title>

		{{include('header.tpl')}}

		<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

		<link href="{{__META__.theme_url}}Resources/Admin/Users/Permissions/css/style.css?1" rel="stylesheet">
		<link href="{{__META__.theme_url}}Resources/Admin/Users/Permissions/css/style-responsive.css?1" rel="stylesheet">
		<script src="{{__META__.theme_url}}Resources/Admin/Users/Permissions/js/script.js?1"></script>
	</head>

	<body>

		{% set navbar_menu_active = 'admin' %}
		{{ include('navbar.tpl') }}

		<div class="admin">
			<div class="block-left">
				{% set admin_menu_active = 'permissions' %}
                {{ include('Resources/Admin/tpl/menu.tpl') }}
			</div>

			<div class="block-right">
				<div class="permissions">
					<div class="permission-list">
						{% if __PERMISSION__.admin_permissions_add %}
							<div class="permission-add">
								<a href="{{ __META__.site_url }}admin/permissions/add" class="wrapper window">
									<div class="info">
										<div class="icon"><i class="fa fa-unlock-alt"></i></div>
										<div class="text">Добавить привилегию</div>
									</div>
								</a>
							</div>
						{% endif %}

						{% for item in list %}
                            {{include('Resources/Admin/Users/Permissions/tpl/item-id.tpl')}}
						{% endfor %}
					</div>

                    {% if pagination %}
						<div class="pagination-block window p-4 mt-20 text-center">
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

		{{include('footer.tpl')}}
	</body>
</html>