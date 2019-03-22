<div class="new-id" data-id="{{ item.id }}">
    <div class="window wrapper">
        <a href="{{ __META__.site_url }}news/{{ item.name }}.{{ item.id }}" target="_blank" class="title">
            <span class="ispublic">
                {% if item.status|int==1 %}
                    <i class="fa fa fa-eye" title="Опубликовано"></i>
                {% else %}
                    <i class="fa fa fa-eye-slash" title="Снято с публикации"></i>
                {% endif %}
            </span>

            <span title="{{ item.title }}">{{ item.title }}</span>
        </a>

        {% if __PERMISSION__.admin_news_edit or __PERMISSION__.admin_news_remove or __PERMISSION__.admin_news_public %}
            <div class="actions">
                <div class="dropdown">
                    <a href="#" class="dropdown-trigger"><i class="fa fa-ellipsis-h"></i></a>

                    <div class="dropdown-target">
                        <ul class="dropdown-target-wrapper">
                            {% if __PERMISSION__.admin_news_edit %}
                                <li class="dropdown-item-wrapper">
                                    <a class="dropdown-item item-edit" href="{{ __META__.site_url }}admin/news/edit/{{ item.id }}">Редактировать</a>
                                </li>
                            {% endif %}

                            {% if __PERMISSION__.admin_news_public %}
                                <li class="dropdown-item-wrapper">
                                    {% if item.status|int==1 %}
                                        <button class="dropdown-item item-public btn-clear" data-href="{{ __META__.site_url }}admin/news/public/{{ item.id }}/0">Скрыть</button>
                                    {% else %}
                                        <button class="dropdown-item item-public btn-clear" data-href="{{ __META__.site_url }}admin/news/public/{{ item.id }}/1">Опубликовать</button>
                                    {% endif %}
                                </li>
                            {% endif %}

                            {% if __PERMISSION__.admin_news_remove %}
                                <li class="dropdown-item-wrapper">
                                    <button class="dropdown-item item-remove btn-clear" data-href="{{ __META__.site_url }}admin/news/remove/{{ item.id }}">Удалить</button>
                                </li>
                            {% endif %}
                        </ul>
                    </div>
                </div>
            </div>
        {% endif %}

        {% if item.image is not empty %}
            <a href="{{ __META__.site_url }}news/{{ item.name }}.{{ item.id }}" target="_blank" class="image" style="background-image: url('{{ item.image }}')"></a>
        {% endif %}

        <div class="author">Добавлено пользователем <a href="{{ __META__.site_url }}user/{{ item.user_login_create }}" target="_blank">{{ item.user_login_create }}</a></div>

        <div class="date">Дата создания: {{ item.date_create|dateToFormat }}</div>

        {% if item.date_update|int != item.date_create|int %}
            <div class="updated" title="Обновлено {{ item.date_update|dateToFormat }} пользователем {{ item.user_login_update }}"><i class="fa fa-clock-o"></i></div>
        {% endif %}
    </div>
</div>