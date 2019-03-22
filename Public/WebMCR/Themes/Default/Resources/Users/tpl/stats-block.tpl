<div class="stats-block">

    <div class="avatar-block">
        <div class="avatar {% if __USER__.id==user_id %}avatar-target-bg{% endif %}" style="background-image: url({{ user_avatar }})"></div>
    </div>

    <ul>
        <li>
            <div class="stat-item">
                <div class="value subscribes-target" data-subscribe-value="{{ user_id }}">{{stats.subscribers|int}}</div>
                <div class="text">Подписчиков</div>
            </div>
        </li>

        <li>
            <div class="stat-item">
                <div class="value">{{stats.likes|int}}</div>
                <div class="text">Симпатий</div>
            </div>
        </li>

        <li>
            <div class="stat-item">
                <div class="value">{{stats.publications|int}}</div>
                <div class="text">Публикаций</div>
            </div>
        </li>

        <li>
            <div class="stat-item">
                <div class="value">{{stats.comments|int}}</div>
                <div class="text">Комментариев</div>
            </div>
        </li>
    </ul>
</div>