<div class="profile-menu">
	<ul>
        {% if __PERMISSION__.profile_avatar_change %}
			<li data-id="avatar" class="{% if profile_menu_active=='avatar' %}active{% endif %}">
				<a href="#" class="profile-menu-trigger change-avatar-trigger">
					<div class="text">Изменить аватар</div>
					<div class="icon"><i class="fa fa-picture-o"></i></div>
				</a>
			</li>
        {% endif %}

		<li data-id="info" class="{% if profile_menu_active=='info' %}active{% endif %}">
			<a href="{{__META__.site_url}}profile/">
				<div class="text">Информация</div>
				<div class="icon"><i class="fa fa-user"></i></div>
			</a>
		</li>

        {% if __PERMISSION__.profile_messages_list %}
			<li data-id="mail" class="{% if profile_menu_active=='mail' %}active{% endif %}">
				<a href="{{__META__.site_url}}profile/messages/">
					<div class="text">Сообщения</div>
					<div class="icon"><i class="fa fa-envelope"></i></div>
				</a>
			</li>
        {% endif %}

        {% if __PERMISSION__.profile_activity_list %}
			<li data-id="activity" class="{% if profile_menu_active=='activity' %}active{% endif %}">
				<a href="{{__META__.site_url}}profile/activity/">
					<div class="text">История активности</div>
					<div class="icon"><i class="fa fa-list"></i></div>
				</a>
			</li>
        {% endif %}

        {% if __PERMISSION__.profile_settings %}
			<li data-id="settings" class="{% if profile_menu_active=='settings' %}active{% endif %}">
				<a href="{{__META__.site_url}}profile/settings/">
					<div class="text">Настройки</div>
					<div class="icon"><i class="fa fa-sliders"></i></div>
				</a>
			</li>
        {% endif %}
	</ul>
</div>