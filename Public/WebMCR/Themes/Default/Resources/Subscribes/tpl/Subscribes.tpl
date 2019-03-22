{% if WIDGET_SUBSCRIBES.permissions.subscribe %}
    <button class="btn text-upper subscribe-trigger {% if WIDGET_SUBSCRIBES.is_subscribe %}btn-transparent{% endif %}" data-type="{{ WIDGET_SUBSCRIBES.type }}" data-value="{{ WIDGET_SUBSCRIBES.value }}">
        {% if WIDGET_SUBSCRIBES.is_subscribe %}
            <i class="fa fa-bell-slash mr-4"></i> Отписаться
        {% else %}
            <i class="fa fa-bell mr-4"></i> Подписаться
        {% endif %}
    </button>
{% endif %}