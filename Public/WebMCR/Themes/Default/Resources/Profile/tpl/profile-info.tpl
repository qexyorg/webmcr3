<div class="info-header">
	<div>
		<div class="profile-login">{{__USER__.login}}</div>
		<div class="profile-names">
            {% if __USER__.firstname or __USER__.lastname %}
                {{__USER__.firstname}} {{__USER__.lastname}} •
            {% endif %}

            {{__USER__.gender|gender}}

            {% if __USER__.birthday %}
                {% set user_age = __USER__.birthday|age %}
				• {{ user_age ~ ' ' ~ case(user_age, ' год', ' года', ' лет') }}
            {% endif %}
		</div>
	</div>
	<div class="text-right">
		{% if(__MONEY__.realmoney.enable) %}
		<div class="profile-balance" title="{{__MONEY__.realmoney.name}}">
			Баланс: {{__BALANCE__.realmoney}} {{__MONEY__.realmoney.cur}}
		</div>
		{% endif %}

		{% if(__MONEY__.gamemoney.enable) %}
		<div class="profile-subbalance" title="{{__MONEY__.gamemoney.name}}">
			{{__MONEY__.gamemoney.name}}: {{__BALANCE__.money}} {{__MONEY__.gamemoney.cur}}
		</div>
		{% endif %}
	</div>
</div>