<div class="tab-id" data-id="money">
    <div class="input-block">
        <label for="economy">
            <input type="checkbox" class="toggle-class" data-element="#economy_toggle" data-classname="d-none" id="economy" name="economy" value="1" {% if __CONFIG__.database.economy.enable %}checked{% endif %}> Использовать экономику
        </label>
    </div>

    <div id="economy_toggle" class="{% if not __CONFIG__.database.economy.enable %}d-none{% endif %}">
        <div class="input-block">
            <label for="economy_table">Таблица экономики</label>
            <input placeholder="Введите имя таблицы экономики" type="text" id="economy_table" name="economy_table" value="{{ __CONFIG__.database.economy.table }}">
            <div class="input-helper">При работе с балансом, сайт будет взаимодействовать с данной таблицей</div>
        </div>

        <div class="input-block">
            <label for="login_column">Колонка имени пользователя</label>
            <input placeholder="Введите имя колонки" type="text" id="login_column" name="login_column" value="{{ __CONFIG__.database.economy.login_column }}">
            <div class="input-helper">Колонка имени пользователя в таблице экономики</div>
        </div>

        {% for money_key,money in __MONEY__ %}
            <div class="input-block">
                <label for="money_{{ money_key }}">
                    <input type="checkbox" class="toggle-class" data-element="#money_{{ money_key }}_toggle" data-classname="d-none" id="money_{{ money_key }}" name="money[{{ money_key }}][enable]" {% if money.enable %}checked{% endif %} value="1"> {{ money.name }}
                </label>
            </div>

            <div id="money_{{ money_key }}_toggle" class="{% if not money.enable %}d-none{% endif %} window-style window mb-24">
                <div class="input-block">
                    <label for="money_{{ money_key }}_name">Название валюты</label>
                    <input placeholder="Введите название валюты" type="text" id="money_{{ money_key }}_name" name="money[{{ money_key }}][name]" value="{{ money.name }}">
                    <div class="input-helper">Название валюты выводится в шаблоне</div>
                </div>

                <div class="input-block">
                    <label for="money_{{ money_key }}_key">Уникальное имя</label>
                    <input placeholder="Введите уникальное имя" type="text" id="money_{{ money_key }}_key" name="money[{{ money_key }}][key]" value="{{ money_key }}">
                    <div class="input-helper">Используйте уникальное имя валюты на латинице</div>
                </div>

                <div class="input-block">
                    <label for="money_{{ money_key }}_column">Колонка в таблице экономики</label>
                    <input placeholder="Введите название колонки" type="text" id="money_{{ money_key }}_column" name="money[{{ money_key }}][column]" value="{{ money.column }}">
                    <div class="input-helper">Имя колонки в таблице экономики с которой будет взаимодействовать сайт</div>
                </div>

                <div class="input-block">
                    <label for="money_{{ money_key }}_cur">Сокращение</label>
                    <input placeholder="Введите сокращение" type="text" id="money_{{ money_key }}_cur" name="money[{{ money_key }}][cur]" value="{{ money.cur }}">
                    <div class="input-helper">Сокращение используется рядом со значением при выводе в шаблоне</div>
                </div>
            </div>
        {% endfor %}
    </div>
</div>