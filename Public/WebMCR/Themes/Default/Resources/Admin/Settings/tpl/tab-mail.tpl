<div class="tab-id" data-id="mail">
    <div class="input-block">
        <label for="mail_smtp">
            <input type="checkbox" class="toggle-class" data-element="#use_smtp" data-classname="d-none" id="mail_smtp" name="mail_smtp" value="1" {% if __CONFIG__.mail.smtp %}checked{% endif %}> Использовать SMTP
        </label>
    </div>

    <div id="use_smtp" class="window window-style mb-24 {% if not __CONFIG__.mail.smtp %}d-none{% endif %}">
        <div class="input-block">
            <label for="mail_smtp_host">SMTP сервер</label>
            <input placeholder="Введите адрес сервера" type="text" id="mail_smtp_host" name="mail_smtp_host" value="{{ __CONFIG__.mail.host }}">
            <div class="input-helper">Адрес SMTP сервера, через который будет происходить отправка почты</div>
        </div>

        <div class="input-block">
            <label for="mail_smtp_username">Имя пользователя SMTP</label>
            <input placeholder="Введите имя пользователя" type="text" id="mail_smtp_username" name="mail_smtp_username" value="{{ __CONFIG__.mail.username }}">
            <div class="input-helper">Имя пользователя SMTP сервера</div>
        </div>

        <div class="input-block">
            <label for="mail_smtp_password">Пароль пользователя SMTP</label>
            <input placeholder="Введите аимя пользователя" type="password" id="mail_smtp_password" name="mail_smtp_password" value="{{ __CONFIG__.mail.password }}">
            <div class="input-helper">Пароль пользователя SMTP сервера</div>
        </div>

        <div class="input-block">
            <label for="mail_smtp_secure">SMTP соединение</label>
            <select name="mail_smtp_secure" id="mail_smtp_secure">
                <option value="tls">TLS</option>
                <option value="ssl" {% if __CONFIG__.mail.secure=='ssl' %}selected{% endif %}>SSL</option>
            </select>
            <div class="input-helper">Защита SMTP соединения</div>
        </div>
    </div>

    <div class="input-block">
        <label for="mail_port">Порт</label>
        <input placeholder="Введите номер порта" type="number" id="mail_port" name="mail_port" value="{{ __CONFIG__.mail.port }}">
        <div class="input-helper">Номер порта, через который будет отправлена почта</div>
    </div>

    <div class="input-block">
        <label for="mail_from">От кого</label>
        <input placeholder="Введите почтовый адрес" type="email" id="mail_from" name="mail_from" value="{{ __CONFIG__.mail.from }}">
        <div class="input-helper">Почтовый адрес, от которого будет отправлено письмо</div>
    </div>

    <div class="input-block">
        <label for="mail_from_name">Имя отправителя</label>
        <input placeholder="Введите имя" type="text" id="mail_from_name" name="mail_from_name" value="{{ __CONFIG__.mail.from_name }}">
        <div class="input-helper">Имя, от которого будет отарвлено письмо. Используйте латиницу</div>
    </div>

</div>