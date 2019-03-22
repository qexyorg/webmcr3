<div class="tab-id" data-id="security">
    <div class="input-block">
        <label for="restore_expire">Время действия восстановления</label>
        <input placeholder="Введите число" type="number" id="restore_expire" name="restore_expire" value="{{ __CONFIG__.restore_expire }}">
        <div class="input-helper">Время действия токена восстановления в секундах</div>
    </div>

    <div class="input-block">
        <label for="register_enable">
            <input type="checkbox" id="register_enable" class="toggle-class" data-element="#use_register" data-classname="d-none" name="register_enable" value="1" {% if __CONFIG__.register.enable %}checked{% endif %}> Использовать регистрацию
        </label>
    </div>

    <div id="use_register" class="window window-style mb-24 {% if not __CONFIG__.register.enable %}d-none{% endif %}">

        <div class="input-block">
            <label for="register_captcha">
                <input type="checkbox" class="toggle-class" data-element="#use_captcha" data-classname="d-none" id="register_captcha" name="register_captcha" value="1" {% if __CONFIG__.register.captcha %}checked{% endif %}> Использовать капчу при регистрации
            </label>
        </div>

        <div id="use_captcha" class="{% if not __CONFIG__.register.captcha %}d-none{% endif %}">
            <div class="input-block">
                <label for="captcha_recaptcha_public">Публичный ключ reCAPTCHA v2</label>
                <input placeholder="Введите ключ" type="text" id="captcha_recaptcha_public" name="captcha_recaptcha_public" value="{{ __CONFIG__.captcha.recaptcha.public }}">
                <div class="input-helper">Публичный ключ reCAPTCHA v2 можно создать <a href="https://www.google.com/recaptcha/admin/create" target="_blank">тут</a></div>
            </div>

            <div class="input-block">
                <label for="captcha_recaptcha_private">Приватный ключ reCAPTCHA v2</label>
                <input placeholder="Введите ключ" type="text" id="captcha_recaptcha_private" name="captcha_recaptcha_private" value="{{ __CONFIG__.captcha.recaptcha.private }}">
                <div class="input-helper">Приватный ключ reCAPTCHA v2 можно создать <a href="https://www.google.com/recaptcha/admin/create" target="_blank">тут</a></div>
            </div>
        </div>

        <div class="input-block">
            <label for="mail_blacklist">Черный список E-Mail адресов</label>
            <input placeholder="example.ru, tempmail.ru, fastmail.ru..." type="text" id="mail_blacklist" name="mail_blacklist" value="{{ __CONFIG__.mail.blacklist|join(', ') }}">
            <div class="input-helper">Введите запрещенные для регистрации E-Mail адреса через запятую</div>
        </div>

        <div class="input-block">
            <label for="mail_whitelist">Белый список E-Mail адресов</label>
            <input placeholder="example.ru, tempmail.ru, fastmail.ru..." type="text" id="mail_whitelist" name="mail_whitelist" value="{{ __CONFIG__.mail.whitelist|join(', ') }}">
            <div class="input-helper">Введите разрешенные для регистрации E-Mail адреса через запятую</div>
        </div>
    </div>
</div>