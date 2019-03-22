<div class="log-id window" data-id="{{ item.id }}">

    <div class="id" title="{{ item.id }}">#{{ item.id }}</div>

    <div class="wrapper">
        <div class="header-block">
            <div class="name" title="Тип">Тип</div>
            <div class="title" title="Название">Название</div>
            <div class="text" title="Описание">Описание</div>
            <div class="ip" title="IP адрес пользователя">IP</div>
            <div class="info" title="Контроллер | URL | Метод">Подробнее</div>
            <div class="user" title="Пользователь">Пользователь</div>
            <div class="date" title="Дата">Дата</div>
            <div class="actions"></div>
        </div>

        <div class="body-block">
            <div class="name" title="{{ item.name }}">{{ item.name }}</div>
            <div class="title" title="{{ item.title }}">{{ item.title }}</div>
            <div class="text" title="{{ item.text }}">{{ item.text }}</div>
            <div class="ip" title="{{ item.ip }}">{{ item.ip }}</div>
            <div class="info" title="{{ item.controller }}{{ item.url }} | {{ item.method }}">
                {{ item.controller }}
                <a href="{{ item.url }}" target="_blank">{{ item.url }}</a><br>
                {{ item.method }}
            </div>
            <div class="user" title="{{ item.login }}">{{ item.login }}</div>
            <div class="date" title="{{ item.date|dateToFormat }}">{{ item.date|dateToFormat }}</div>
            <div class="actions">
                {% if __PERMISSION__.admin_logs_remove %}
                    <div class="dropdown">
                        <a href="#" class="dropdown-trigger"><i class="fa fa-ellipsis-h"></i></a>

                        <div class="dropdown-target">
                            <ul class="dropdown-target-wrapper">

                                {% if __PERMISSION__.admin_logs_remove %}
                                    <li class="dropdown-item-wrapper">
                                        <button class="dropdown-item item-remove btn-clear" data-href="{{ __META__.site_url }}admin/logs/remove/{{ item.id }}">Удалить</button>
                                    </li>
                                {% endif %}
                            </ul>
                        </div>
                    </div>
                {% endif %}
            </div>
        </div>
    </div>
</div>