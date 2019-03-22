<div class="tab-id" data-id="sync">
    <div class="input-block">
        <label for="logic">Логика работы с пользователем</label>
        <select name="logic" id="logic">
            {% for logic in logics %}
                <option value="{{ logic }}" {% if logic==currentLogic %}selected{% endif %}>{{ logic }}</option>
            {% endfor %}
        </select>
        <div class="input-helper">Используется для работы с пользователями и их взаимодействия с внешними ресурсами</div>
    </div>

    <div class="input-block">
        <label for="bangroup">Группа забаненных</label>
        <select name="bangroup" id="bangroup">
            {% for group in groups %}
                <option value="{{ group.id }}" {% if group.id==__CONFIG__.changegroup.ban %}selected{% endif %}>{{ group.title }}</option>
            {% endfor %}
        </select>
        <div class="input-helper">Группа, в которую попадет пользователь, после его блокировки</div>
    </div>

    <div class="input-block">
        <label for="unbangroup">Группа разбаненных</label>
        <select name="unbangroup" id="unbangroup">
            {% for group in groups %}
                <option value="{{ group.id }}" {% if group.id==__CONFIG__.changegroup.back %}selected{% endif %}>{{ group.title }}</option>
            {% endfor %}
        </select>
        <div class="input-helper">Группа, в которую попадет пользователь, после его разблокировки</div>
    </div>

    <div class="input-block">
        <label for="removegroup">Группа после удаления</label>
        <select name="removegroup" id="removegroup">
            {% for group in groups %}
                <option value="{{ group.id }}" {% if group.id==__CONFIG__.changegroup.remove %}selected{% endif %}>{{ group.title }}</option>
            {% endfor %}
        </select>
        <div class="input-helper">Группа, в которую попадут пользователи из удаляемой группы</div>
    </div>
</div>