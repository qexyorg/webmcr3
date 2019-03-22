<div class="user-id" data-id="{{ item.id }}">
    <div class="window wrapper">
        <div class="user-header">

            {% if __PERMISSION__.admin_users_edit or __PERMISSION__.admin_users_remove or __PERMISSION__.admin_users_public %}
                <div class="actions">
                    <div class="dropdown">
                        <a href="#" class="dropdown-trigger"><i class="fa fa-ellipsis-h"></i></a>

                        <div class="dropdown-target">
                            <ul class="dropdown-target-wrapper">
                                {% if __PERMISSION__.admin_users_ban %}
                                    <li class="dropdown-item-wrapper">
                                        {% if item.group_id|int==__CONFIG__.changegroup.ban|int %}
                                            <button class="dropdown-item item-ban btn-clear" data-href="{{ __META__.site_url }}admin/users/ban/{{ item.id }}/0">Разблокировать</button>
                                        {% else %}
                                            <button class="dropdown-item item-ban btn-clear" data-href="{{ __META__.site_url }}admin/users/ban/{{ item.id }}/1">Заблокировать</button>
                                        {% endif %}
                                    </li>
                                {% endif %}

                                {% if __PERMISSION__.admin_users_banip %}
                                    <li class="dropdown-item-wrapper">
                                        <button class="dropdown-item btn-clear item-banip modal-trigger" data-modal-id="banip" data-ip="{{ item.ip_create }}">Заблокировать по IP</button>
                                    </li>
                                {% endif %}

                                {#{% if __PERMISSION__.admin_users_clear %}
                                    <li class="dropdown-item-wrapper">
                                        <button title="Удаляет все оставленные пользователем данные на сайте" class="dropdown-item item-clear btn-clear" data-href="{{ __META__.site_url }}admin/users/clear/{{ item.id }}">Очистить</button>
                                    </li>
                                {% endif %}#}

                                {% if __PERMISSION__.admin_users_edit %}
                                    <li class="dropdown-item-wrapper">
                                        <a class="dropdown-item item-edit" href="{{ __META__.site_url }}admin/users/edit/{{ item.id }}">Редактировать</a>
                                    </li>
                                {% endif %}

                                {% if __PERMISSION__.admin_users_remove %}
                                    <li class="dropdown-item-wrapper">
                                        <button class="dropdown-item item-remove btn-clear" data-href="{{ __META__.site_url }}admin/users/remove/{{ item.id }}">Удалить</button>
                                    </li>
                                {% endif %}
                            </ul>
                        </div>
                    </div>
                </div>
            {% endif %}

            <a href="{{ __META__.site_url }}user/{{ item.login }}" target="_blank" class="login">{{ item.login }}</a>

            <a href="{{ __META__.site_url }}user/{{ item.login }}" target="_blank" class="avatar window" style="background-image: url('{{ item.avatar|avatar }}');"></a>
        </div>

        <div class="user-body">
            <ul class="user-info">
                <li>
                    <div class="name">Группа</div>
                    <div class="value">— <span title="{{ item.group_title }} - {{ item.group_text }}">{{ item.group_title }}</span></div>
                </li>

                <li>
                    <div class="name">E-Mail</div>
                    <div class="value">— <span title="{{ item.email }}">{{ item.email }}</span></div>
                </li>

                <li>
                    <div class="name">Дата регистрации</div>
                    <div class="value">— <span title="Обновлено {{ item.date_update|dateToFormat }}">{{ item.date_create|dateToFormat }}</span></div>
                </li>

                <li>
                    <div class="name">IP адрес</div>
                    <div class="value">— <span title="Последний IP: {{ item.ip_update }}">{{ item.ip_create }}</span></div>
                </li>
            </ul>
        </div>
    </div>
</div>

{#
<div class="new-id" data-id="{{ item.id }}">
    <div class="wrapper window">

        <div class="id">#{{ item.id }}</div>

        {% if __PERMISSION__.admin_news_tags_edit or __PERMISSION__.admin_news_tags_remove %}
            <div class="actions">
                <div class="dropdown">
                    <a href="#" class="dropdown-trigger"><i class="fa fa-ellipsis-h"></i></a>

                    <div class="dropdown-target">
                        <ul class="dropdown-target-wrapper">
                            {% if __PERMISSION__.admin_news_tags_edit %}
                                <li class="dropdown-item-wrapper">
                                    <a class="dropdown-item item-edit" href="{{ __META__.site_url }}admin/news/tags/edit/{{ item.id }}">Редактировать</a>
                                </li>
                            {% endif %}

                            {% if __PERMISSION__.admin_news_tags_remove %}
                                <li class="dropdown-item-wrapper">
                                    <button class="dropdown-item item-remove btn-clear" data-href="{{ __META__.site_url }}admin/news/tags/remove/{{ item.id }}">Удалить</button>
                                </li>
                            {% endif %}
                        </ul>
                    </div>
                </div>
            </div>
        {% endif %}

        <div class="view">
            <a class="title" title="{{ item.title }}" target="_blank" href="{{ __META__.site_url }}news/tag-{{ item.name }}">{{ item.title }}</a>

            <div class="text" title="{{ item.text }}">— {{ item.text }}</div>

            <button class="btn-clear show-info"><i class="fa fa-info-circle"></i></button>
        </div>

        <div class="info">
            <div class="info-block">
                <div class="name">Автор - <a href="{{ __META__.site_url }}user/{{ item.user_login_create }}/">{{ item.user_login_create }}</a></div>
                <div class="subtext" title="Добавлено {{ item.date_create|dateToFormat }}">Добавлено {{ item.date_create|dateToFormat }}</div>
            </div>


            <div class="info-block pt-8">
                <div class="name">Редактор - <a href="{{ __META__.site_url }}user/{{ item.user_login_update }}/">{{ item.user_login_update }}</a></div>
                <div class="subtext" title="Обновлено {{ item.date_update|dateToFormat }}">Обновлено {{ item.date_update|dateToFormat }}</div>
            </div>

            <button class="btn-clear hide-info"><i class="fa fa-chevron-circle-left"></i></button>
        </div>

    </div>
</div>#}
