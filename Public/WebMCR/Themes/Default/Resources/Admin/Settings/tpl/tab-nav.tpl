<div class="tab-id" data-id="nav">
    <div class="input-block">
        <label for="news_list">Новостей</label>
        <input placeholder="Введите целое положительное число" type="number" id="news_list" name="news_list" value="{{ __CONFIG__.pagination.news.list }}">
        <div class="input-helper">Кол-во новостей, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="news_comments">Комментариев в новостях</label>
        <input placeholder="Введите целое положительное число" type="number" id="news_comments" name="news_comments" value="{{ __CONFIG__.pagination.news.comments }}">
        <div class="input-helper">Кол-во комментариев в новостях, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="profile_comments">Комментариев в профиле</label>
        <input placeholder="Введите целое положительное число" type="number" id="profile_comments" name="profile_comments" value="{{ __CONFIG__.pagination.profile.comments }}">
        <div class="input-helper">Кол-во комментариев в профиле, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="profile_messages">Сообщений в профиле</label>
        <input placeholder="Введите целое положительное число" type="number" id="profile_messages" name="profile_messages" value="{{ __CONFIG__.pagination.profile.messages }}">
        <div class="input-helper">Кол-во сообщений в профиле, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="profile_reply">Ответов в сообщениях профиля</label>
        <input placeholder="Введите целое положительное число" type="number" id="profile_reply" name="profile_reply" value="{{ __CONFIG__.pagination.profile.reply }}">
        <div class="input-helper">Кол-во ответов в сообщениях, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="profile_activity">Активность в профиле</label>
        <input placeholder="Введите целое положительное число" type="number" id="profile_activity" name="profile_activity" value="{{ __CONFIG__.pagination.profile.activity }}">
        <div class="input-helper">Кол-во элементов активности в профиле, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="users_list">Пользователей</label>
        <input placeholder="Введите целое положительное число" type="number" id="users_list" name="users_list" value="{{ __CONFIG__.pagination.users.list }}">
        <div class="input-helper">Кол-во пользователей, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="users_comments">Комментариев в пользователях</label>
        <input placeholder="Введите целое положительное число" type="number" id="users_comments" name="users_comments" value="{{ __CONFIG__.pagination.users.comments }}">
        <div class="input-helper">Кол-во комментариев на странице пользователя, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_statics">[ПУ] Статических страниц</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_statics" name="admin_statics" value="{{ __CONFIG__.pagination.admin.statics }}">
        <div class="input-helper">Кол-во статических страниц в панели управления, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_logs">[ПУ] Логов</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_logs" name="admin_logs" value="{{ __CONFIG__.pagination.admin.logs }}">
        <div class="input-helper">Кол-во логов в панели управления, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_news">[ПУ] Новостей</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_news" name="admin_news" value="{{ __CONFIG__.pagination.admin.news }}">
        <div class="input-helper">Кол-во новостей в панели управления, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_news_tags">[ПУ] Тегов новостей</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_news_tags" name="admin_news_tags" value="{{ __CONFIG__.pagination.admin.news_tags }}">
        <div class="input-helper">Кол-во тегов новостей в панели управления, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_users">[ПУ] Пользователей</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_users" name="admin_users" value="{{ __CONFIG__.pagination.admin.users }}">
        <div class="input-helper">Кол-во пользователей в панели управления, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_user_groups">[ПУ] Групп пользователей</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_user_groups" name="admin_user_groups" value="{{ __CONFIG__.pagination.admin.user_groups }}">
        <div class="input-helper">Кол-во групп пользователей в панели управления, выводимых на одну страницу</div>
    </div>

    <div class="input-block">
        <label for="admin_permissions">[ПУ] Привилегий</label>
        <input placeholder="Введите целое положительное число" type="number" id="admin_permissions" name="admin_permissions" value="{{ __CONFIG__.pagination.admin.permissions }}">
        <div class="input-helper">Кол-во привилегий в панели управления, выводимых на одну страницу</div>
    </div>
</div>