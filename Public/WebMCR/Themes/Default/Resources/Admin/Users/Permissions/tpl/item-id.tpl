<div class="permission-id" data-id="{{ item.id }}">
    <div class="wrapper window">

        <div class="id">#{{ item.id }}</div>

        {% if __PERMISSION__.admin_permissions_edit or __PERMISSION__.admin_permissions_remove %}
            <div class="actions">
                <div class="dropdown">
                    <a href="#" class="dropdown-trigger"><i class="fa fa-ellipsis-h"></i></a>

                    <div class="dropdown-target">
                        <ul class="dropdown-target-wrapper">
                            {% if __PERMISSION__.admin_permissions_edit %}
                                <li class="dropdown-item-wrapper">
                                    <a class="dropdown-item item-edit" href="{{ __META__.site_url }}admin/permissions/edit/{{ item.id }}">Редактировать</a>
                                </li>
                            {% endif %}

                            {% if not item.system|int and __PERMISSION__.admin_permissions_remove %}
                                <li class="dropdown-item-wrapper">
                                    <button class="dropdown-item item-remove btn-clear" data-href="{{ __META__.site_url }}admin/permissions/remove/{{ item.id }}">Удалить</button>
                                </li>
                            {% endif %}
                        </ul>
                    </div>
                </div>
            </div>
        {% endif %}

        <div class="view">
            <div class="title" title="{{ item.title }}">{{ item.title }}</div>

            <div class="name" title="{{ item.name }}">Уникальное имя: {{ item.name }}</div>

            <div class="type" title="{{ item.type }}">
                Тип значения:
                {% if item.type=='boolean' %}
                    Булевое значение
                {% elseif item.type=='integer' %}
                    Целове число
                {% elseif item.type=='float' %}
                    Число с плавающей запятой
                {% elseif item.type=='string' %}
                    Строка
                {% else %}
                    Не определено
                {% endif %}
            </div>

            <div class="default" title="{{ item.default }}">Значение по умолчанию: {{ item.default }}</div>

            {% if item.system|int %}<div class="system" title="Системная привилегия - удаление и изменение уникального имени привилегии недопустимо"><i class="fa fa-exclamation-triangle"></i></div>{% endif %}

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
</div>