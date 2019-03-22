<div class="tab-id active" data-id="main">
    <div class="input-block">
        <label for="sitename">Название сайта</label>
        <input placeholder="Введите название сайта" type="text" id="sitename" name="sitename" value="{{ __META__.sitename }}">
        <div class="input-helper">Название сайта используется в названиях страниц</div>
    </div>

    <div class="input-block">
        <label for="sitedesc">Мета описание сайта</label>
        <textarea name="sitedesc" placeholder="Введите мета описание сайта" maxlength="255" id="sitedesc">{{ __META__.sitedesc }}</textarea>
        <div class="input-helper">Используется для поисковых роботов и на фронтальной странице сайта</div>
    </div>

    <div class="input-block">
        <label for="sitekeys">Ключевые слова</label>
        <input placeholder="Введите ключевые слова через запятую" type="text" maxlength="255" id="sitekeys" name="sitekeys" value="{{ __META__.sitekeys }}">
        <div class="input-helper">Используется для поисковых роботов</div>
    </div>

    <div class="input-block">
        <label for="theme">Шаблон сайта</label>
        <select name="theme" id="theme">
            {% for theme in themes %}
                <option value="{{ theme }}" {% if theme==__META__.theme %}selected{% endif %}>{{ theme }}</option>
            {% endfor %}
        </select>
        <div class="input-helper">По умолчанию, шаблоны хранятся в директории {{ __META__.site_url }}Applications/WebMCR/Public</div>
    </div>

    <div class="input-block">
        <label for="site_url">Корневой URL сайта</label>
        <input placeholder="Введите корневой URL сайта" maxlength="64" type="text" id="site_url" name="site_url" value="{{ __META__.site_url }}">
        <div class="input-helper">Используется для вывода адресов ссылок и элементов шаблона</div>
    </div>

    <div class="input-block">
        <label for="full_site_url">Полный адрес сайта</label>
        <input placeholder="Введите полный адрес сайта" type="text" maxlength="128" id="full_site_url" name="full_site_url" value="{{ __META__.full_site_url }}">
        <div class="input-helper">Используется для вывода адресов ссылок и элементов шаблона</div>
    </div>

    <div class="input-block">
        <label for="cache_version_css">Версия кэша CSS стилей</label>
        <input placeholder="Введите версию стилей" type="text" id="cache_version_css" name="cache_version_css" value="{{ __META__.cache_version_css }}">
        <div class="input-helper">Помогает исправить проблемы с отображением шаблона после его изменения</div>
    </div>

    <div class="input-block">
        <label for="cache_version_js">Версия кэша Javascript</label>
        <input placeholder="Введите версию скриптов" type="text" id="cache_version_js" name="cache_version_js" value="{{ __META__.cache_version_js }}">
        <div class="input-helper">Помогает исправить проблемы с отображением шаблона после его изменения</div>
    </div>
</div>