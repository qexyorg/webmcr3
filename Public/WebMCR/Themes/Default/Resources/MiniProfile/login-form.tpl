<form class="form" id="loginForm" method="POST">
	<div class="login-wrapper">
		<input type="text" placeholder="Логин или E-Mail" name="login">
		<input type="password" name="password" placeholder="Пароль">
		<div class="row">
			<div class="col-w-50">
				<div class="input-block">
					<label class="col-white">
						<input name="remember" value="1" type="checkbox" checked>
						Запомнить меня
					</label>
				</div>
			</div>
			<div class="col-w-50">
				<div class="input-block">
					<label class="text-right cursor-default"><a class="col-white restore-trigger" href="#">Забыли пароль?</a></label>
				</div>
			</div>
		</div>

		<div class="row">
            {% if __CONFIG__.register.enable %}
				<div class="col-w-50">
					<button type="submit" class="btn block text-upper btn-green login-run">Войти</button>
				</div>
				<div class="col-w-50">
					<button type="button" class="btn block text-upper register-trigger">Регистрация</button>
				</div>
			{% else %}
				<div class="col-w-100">
					<button type="submit" class="btn block text-upper btn-green login-run">Войти</button>
				</div>
			{% endif %}

		</div>
	</div>

    {% if __CONFIG__.register.enable %}
		<div class="register-wrapper hide">
			<input type="text" placeholder="E-Mail" name="email">

			{% if __CONFIG__.register.captcha %}
				<center>
					<div class="g-recaptcha-target" id="register-rc" data-sitekey="{{__CAPTCHA__.recaptcha.public}}"></div>
				</center>
			{% endif %}

			<div class="input-block">
				<label class="col-white">
					<input type="checkbox" name="tos" value="1" checked>
					Я принимаю условия <a href="/tos">соглашения</a>
				</label>
			</div>

			<div class="row">
				<div class="col-w-50">
					<button type="submit" class="btn block btn-green text-upper register-run">Зарегистрироваться</button>
				</div>
				<div class="col-w-50">
					<button type="button" class="btn block text-upper login-trigger">Вход</button>
				</div>
			</div>
		</div>
	{% endif %}

	<div class="restore-wrapper hide">
		<input type="text" placeholder="E-Mail" name="email">

        {% if __CONFIG__.register.captcha %}
			<center>
				<div class="g-recaptcha-target" id="restore-rc" data-sitekey="{{__CAPTCHA__.recaptcha.public}}"></div>
			</center>
		{% endif %}

		<div class="row">
			<div class="col-w-50">
				<button type="submit" class="btn block text-upper btn-green restore-run">Восстановить</button>
			</div>
			<div class="col-w-50">
				<button type="button" class="btn block text-upper login-trigger">Вход</button>
			</div>
		</div>
	</div>

</form>