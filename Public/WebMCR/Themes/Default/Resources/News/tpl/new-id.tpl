<div class="news-id window" data-id="{{new.id}}">
    <div class="news-header">
        <a href="{{__META__.site_url}}news/{{new.name}}.{{new.id}}" class="title {% if new.is_view is not null %}viewed{% endif %}" title="{{new.title}}">{{new.title}}</a>
        <div class="actions">
            <ul>

                <li title="Симпатии">
                    {% if __PERMISSION__.news_like %}
                        <a href="#" class="like-trigger {% if new.is_liked is not null %}active{% endif %}" data-likes-url="{{__META__.site_url}}news/{{new.id}}/like">
                            <i class="fa fa-heart"></i>
                            <span class="like-num">{{new.likes}}</span>
                        </a>
                    {% else %}
                        <a href="#" class="preventDefault cursor-default">
                            <i class="fa fa-heart"></i>
                            <span class="like-num">{{new.likes}}</span>
                        </a>
                    {% endif %}
                </li>

                <li title="Комментарии"><a href="{{__META__.site_url}}news/{{new.name}}.{{new.id}}#comment-block"><i class="fa fa-comment-o"></i><span>{{new.comments}}</span></a></li>

                <li title="Просмотры"><a href="#" class="view-trigger {% if new.is_view is not null %}active{% endif %}"><i class="fa fa-eye"></i><span>{{new.views}}</span></a></li>

                {#<li title="Поделиться"><a href="#"><i class="fa fa-share-alt"></i></a></li>#}
            </ul>
        </div>
    </div>

    {% if new.image %}
        <div class="image">
            <a href="{{__META__.site_url}}news/{{new.name}}.{{new.id}}" class="wrapper" style="background-image: url('{{new.image}}');"></a>
        </div>
    {% endif %}

    {% if new.text_short_html is not empty %}
        <div class="text-short">{{new.text_short_html|raw}}</div>
    {% endif %}

    <div class="news-footer">
        <div class="dates">
            Опубликовано {{new.date_create|dateToFormat}}
            {% if new.date_update != new.date_create %}
                <i class="col-gray" title="Обновлено {{new.date_update|dateToFormat}}"></i>
            {% endif %}
        </div>
        <div class="author">
            Автор:
            {% if __PERMISSION__.user %}
                <a href="{{__META__.site_url}}user/{{new.user_login_create}}">{{new.user_login_create}}</a>
            {% else %}
                {{new.user_login_create}}
            {% endif %}
        </div>
    </div>
</div>