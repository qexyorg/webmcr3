<div class="mini-profile window p-0">
	<div class="header-block">
		<div class="avatar avatar-target-bg" style="background-image: url({{__USER__.avatar|avatar}}?{{random(99999)}})"></div>
		<div class="menu">
			<ul>
				{% if __PERMISSION__.profile_avatar_change %}
					<li><a href="#" class="change-avatar-trigger" title="Изменить аватар"><i class="fa fa-picture-o"></i></a></li>
				{% endif %}
				<li><a href="{{__META__.site_url}}profile/" title="Личный кабинет"><i class="fa fa-user"></i></a></li>
				<li><a href="{{__META__.site_url}}profile/logout" title="Выйти"><i class="fa fa-sign-out"></i></a></li>
			</ul>
		</div>
	</div>

	<div class="content-block">
		<ul>
			<li>
				<span class="icon" title="Логин пользователя"><i class="fa fa-user"></i></span>
				<span class="text" title="{{__USER__.login}}">{{__USER__.login}}</span>
			</li>
			{% if(__MONEY__.gamemoney.enable) %}
				<li>
					<span class="icon" title="{{__MONEY__.gamemoney.name}}"><i class="fa fa-money"></i></span>
					<span class="text" title="{{__BALANCE__.money}} {{__MONEY__.gamemoney.cur}}">
						{{__BALANCE__.money}} {{__MONEY__.gamemoney.cur}}
					</span>
				</li>
			{% endif %}

			{% if(__MONEY__.realmoney.enable) %}
				<li>
					<span class="icon" title="{{__MONEY__.realmoney.name}}"><i class="fa fa-usd"></i></span>
					<span class="text" title="{{__BALANCE__.realmoney}} {{__MONEY__.realmoney.cur}}">
						{{__BALANCE__.realmoney}} {{__MONEY__.realmoney.cur}}
					</span>
				</li>
			{% endif %}
		</ul>
	</div>
</div>