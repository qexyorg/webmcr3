{% if __ALERTS__ %}
    <div class="a-alert">
        {% for alert in __ALERTS__  %}
            <div class="alert-id">
                <div class="wrapper">
                    <div class="text">
                        {{ alert.text|raw }}
                    </div>

                    <div class="footer-block">
                        <div class="block-left">
                            {% if alert.title %}
                                <div class="title">{{ alert.title|raw }}</div>
                            {% endif %}
                        </div>

                        <div class="block-right">
                            <button class="btn btn-transparent col-white text-upper close-trigger">СКРЫТЬ</button>
                        </div>
                    </div>
                </div>
            </div>
        {% endfor %}
    </div>
{% endif %}