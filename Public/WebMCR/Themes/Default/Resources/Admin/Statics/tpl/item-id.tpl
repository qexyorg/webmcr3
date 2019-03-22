<div class="static-id" data-id="{{ item.id }}">
    <div class="wrapper">
        <div class="id">#{{ item.id }}</div>

        {% if __PERMISSION__.admin_statics_edit %}
            <a title="Редактировать" class="item-edit" href="{{ __META__.site_url }}admin/statics/edit/{{ item.id }}"><i class="fa fa-pencil-square-o"></i></a>
        {% endif %}

        <div class="is_public">{% if item.status|boolean %}Опубликовано{% else %}Не опубликовано{% endif %}</div>

        {% if __PERMISSION__.admin_statics_public %}
            {% if not item.status|boolean %}
                <button title="Опубликовать" class="item-public btn btn-clear" data-href="{{ __META__.site_url }}admin/statics/public/{{ item.id }}/1"><i class="fa fa-eye"></i></button>
            {% else %}
                <button title="Снять с публикации" class="item-public btn btn-clear" data-href="{{ __META__.site_url }}admin/statics/public/{{ item.id }}/0"><i class="fa fa-eye-slash"></i></button>
            {% endif %}
        {% endif %}

        {% if __PERMISSION__.admin_statics_remove %}
            <button title="Удалить" class="item-remove" data-href="{{ __META__.site_url }}admin/statics/remove/{{ item.id }}"><i class="fa fa-trash"></i></button>
        {% endif %}

        <a class="title" target="_blank" href="{{ __META__.site_url }}{{ item.route }}">{{ item.title }}</a>

        <div class="editors">
            <div class="author">
                <div class="title">Создано <i class="fa fa-calendar cursor-help" title="{{ item.date_create|dateToFormat }}"></i></div>

                <div class="login"><a href="{{__META__.site_url}}user/{{ item.user_login_create }}" target="_blank">{{ item.user_login_create }}</a></div>

                <a href="{{__META__.site_url}}user/{{item.user_login_create}}" style="background-image: url('{{item.user_avatar_create|avatar}}?{{random(99999)}}');" target="_blank" class="window avatar {% if item.user_id_create==__USER__.id %}avatar-target-bg{% endif %}"></a>
            </div>

            <div class="author">
                <div class="title">Обновлено <i class="fa fa-calendar cursor-help" title="{{ item.date_update|dateToFormat }}"></i></div>

                <div class="login"><a href="{{__META__.site_url}}user/{{ item.user_login_update }}" target="_blank">{{ item.user_login_update }}</a></div>

                <a href="{{__META__.site_url}}user/{{item.user_login_update}}" style="background-image: url('{{item.user_avatar_update|avatar}}?{{random(99999)}}');" target="_blank" class="window avatar {% if item.user_id_update==__USER__.id %}avatar-target-bg{% endif %}"></a>
            </div>
        </div>
    </div>
</div>