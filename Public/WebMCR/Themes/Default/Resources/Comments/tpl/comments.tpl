<div class="comments-list comment-block" data-type="{{ WIDGET_COMMENTS.type }}" data-value="{{ WIDGET_COMMENTS.value }}">
    {% if WIDGET_COMMENTS.permissions.add %}
        {{include('Resources/Comments/tpl/comment-form.tpl')}}
    {% else %}
        <div class="comment-access">У вас недостаточно прав для добавления комментариев</div>
    {% endif %}

    {% if WIDGET_COMMENTS.permissions.view %}
        {% if not WIDGET_COMMENTS.list %}
            <div class="comments-none">Нет доступных комментариев</div>
        {% else %}
            <div class="comment-num pt-24 text-upper">Комментарии: <span class="comments_num_element widget-comment-num" data-type="{{ WIDGET_COMMENTS.type }}">{{ WIDGET_COMMENTS.count }}</span></div>

            <div class="comments-block">
                {% for comment in WIDGET_COMMENTS.list %}
                    {{include('Resources/Comments/tpl/comment-id.tpl')}}
                {% endfor %}
            </div>
        {% endif %}

        {% if WIDGET_COMMENTS.pagination is not empty %}
            <div class="pagination-block pb-0 pt-16 text-center">
                <ul class="pagination">
                    {% for page in WIDGET_COMMENTS.pagination %}
                        <li class="{% if page.selected %}active{% endif %}">
                            <a title="{{page.title}}" data-page="{{page.page}}" href="{{page.url}}">{{page.text}}</a>
                        </li>
                    {% endfor %}
                </ul>
            </div>
        {% endif %}
    {% else %}
        <div class="comment-access pb-16">У вас недостаточно прав для просмотра комментариев</div>
    {% endif %}
</div>