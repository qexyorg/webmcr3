<ul class="admin-menu">
	<li class="{% if admin_menu_active=='main' %}active{% endif %}" data-name="main">
		<a class="menu-item" href="{{ __META__.site_url }}admin/">
			<div class="icon"><i class="fa fa-home"></i></div>
			<div class="name">Главная</div>
		</a>
	</li>

	{% if __PERMISSION__.admin_statics_index %}
		<li class="{% if admin_menu_active=='statics' %}active{% endif %}" data-name="statics">
			<a class="menu-item" href="{{ __META__.site_url }}admin/statics/">
				<div class="icon"><i class="fa fa-file"></i></div>
				<div class="name">Статические страницы</div>
			</a>
		</li>
	{% endif %}

    {% if __PERMISSION__.admin_news_index %}
		<li class="{% if admin_menu_active=='news' %}active{% endif %}" data-name="news">
			<a class="menu-item" href="{{ __META__.site_url }}admin/news/">
				<div class="icon"><i class="fa fa-newspaper-o"></i></div>
				<div class="name">Новости</div>
			</a>
		</li>
	{% endif %}

    {% if __PERMISSION__.admin_news_tags_index %}
		<li class="{% if admin_menu_active=='news_tags' %}active{% endif %}" data-name="news_tags">
			<a class="menu-item" href="{{ __META__.site_url }}admin/news/tags">
				<div class="icon"><i class="fa fa-tags"></i></div>
				<div class="name">Теги новостей</div>
			</a>
		</li>
    {% endif %}

    {% if __PERMISSION__.admin_users_index %}
		<li class="{% if admin_menu_active=='users' %}active{% endif %}" data-name="users">
			<a class="menu-item" href="{{ __META__.site_url }}admin/users">
				<div class="icon"><i class="fa fa-user"></i></div>
				<div class="name">Пользователи</div>
			</a>
		</li>
    {% endif %}

    {% if __PERMISSION__.admin_groups_index %}
		<li class="{% if admin_menu_active=='groups' %}active{% endif %}" data-name="groups">
			<a class="menu-item" href="{{ __META__.site_url }}admin/groups">
				<div class="icon"><i class="fa fa-users"></i></div>
				<div class="name">Группы пользователей</div>
			</a>
		</li>
    {% endif %}

    {% if __PERMISSION__.admin_permissions_index %}
		<li class="{% if admin_menu_active=='permissions' %}active{% endif %}" data-name="permissions">
			<a class="menu-item" href="{{ __META__.site_url }}admin/permissions">
				<div class="icon"><i class="fa fa-unlock-alt"></i></div>
				<div class="name">Привилегии</div>
			</a>
		</li>
    {% endif %}

    {% if __PERMISSION__.admin_logs_index %}
		<li class="{% if admin_menu_active=='logs' %}active{% endif %}" data-name="logs">
			<a class="menu-item" href="{{ __META__.site_url }}admin/logs">
				<div class="icon"><i class="fa fa-list-ol"></i></div>
				<div class="name">Логи</div>
			</a>
		</li>
    {% endif %}

    {% if __PERMISSION__.admin_extensions_index %}
		<li class="{% if admin_menu_active=='extensions' %}active{% endif %}" data-name="extensions">
			<a class="menu-item" href="{{ __META__.site_url }}admin/extensions/">
				<div class="icon"><i class="fa fa-puzzle-piece"></i></div>
				<div class="name">Дополнения</div>
			</a>
		</li>
    {% endif %}

    {% if __PERMISSION__.admin_settings %}
		<li class="{% if admin_menu_active=='settings' %}active{% endif %}" data-name="settings">
			<a class="menu-item" href="{{ __META__.site_url }}admin/settings">
				<div class="icon"><i class="fa fa-cogs"></i></div>
				<div class="name">Настройки</div>
			</a>
		</li>
    {% endif %}
</ul>