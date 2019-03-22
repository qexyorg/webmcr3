<!DOCTYPE HTML>
<html>
<head>
	<title>{{pagename}} | {{__META__.sitename}}</title>

    {{include('header.tpl')}}
	<link href="{{__META__.theme_url}}css/datepicker.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}js/datepicker.js?1"></script>

	<link href="{{__META__.theme_url}}css/form-validator.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}js/form-validator.js?1"></script>

	<script src="{{__META__.theme_url}}js/avatar-uploader.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Admin/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Admin/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Admin/js/script.js?1"></script>

	<link href="{{__META__.theme_url}}Resources/Admin/Users/Edit/css/style.css?1" rel="stylesheet">
	<link href="{{__META__.theme_url}}Resources/Admin/Users/Edit/css/style-responsive.css?1" rel="stylesheet">
	<script src="{{__META__.theme_url}}Resources/Admin/Users/Edit/js/script.js?1"></script>
</head>

<body>

{% set navbar_menu_active = 'admin' %}
{{ include('navbar.tpl') }}

<div class="admin">
	<div class="block-left">
        {% set admin_menu_active = 'users' %}
        {{ include('Resources/Admin/tpl/menu.tpl') }}
	</div>

	<div class="block-right">
		<div class="user-edit">
			<div class="title">Редактирование пользователя</div>

			<form action="{{ __META__.site_url }}admin/users/edit/{{ item.id }}/submit" method="POST" class="edit-form fv-form">
				<div class="form-wrapper d-grid grid-gap-20 grid-template-columns-2-1fr">
					<div class="block-left window">
						<div class="block-top">
							<div class="block-left">
								<a href="#" title="Удалить аватар" id="remove-avatar"><i class="fa fa-times"></i></a>

								<a href="#" class="avatar change-avatar-trigger avatar-target-bg window" data-upload-url="{{ __META__.site_url }}admin/users/avatar" style="background-image: url('{{ item.avatar|avatar }}')">
									<div class="footer-help">Изменить аватар</div>
								</a>

								<input type="hidden" name="avatar" class="avatar-target-bg" value="{{ item.avatar }}">
							</div>

							<div class="block-right">
								<div class="input-block">
									<label for="login" class="text-bold">Логин <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
									<input type="text" id="login" class="fv-input" pattern="^[\w\-]{3,32}$" name="login" value="{{ item.login }}" placeholder="Введите логин" required>
								</div>

								<div class="input-block">
									<label for="email" class="text-bold">E-Mail <i title="Обязательное поле" class="col-red cursor-help">*</i></label>
									<input type="email" id="email" name="email" value="{{ item.email }}" placeholder="Введите E-Mail" required>
								</div>
							</div>
						</div>

                        {% if __PERMISSION__.admin_users_group_change %}
							<div class="input-block">
								<label for="group" class="text-bold">Группа</label>
								<select name="group" id="group">
                                    {% for group in groups %}
										<option value="{{ group.id }}" {% if group.id|int==item.group_id %}selected{% endif %}>{{ group.title }}</option>
                                    {% endfor %}
								</select>
							</div>
                        {% endif %}

						<div class="input-block">
							<label for="password" class="text-bold">Новый пароль</label>
							<input type="password" id="password" minlength="6" name="password" placeholder="Введите новый пароль">
						</div>

						<div class="d-grid grid-gap-16 grid-template-columns-2-1fr pb-16">
							<div>
								<div class="input-block m-0">
									<label for="firstname" class="text-bold">Имя</label>
									<input type="text" pattern="^[\w\s]{1,32}$" id="firstname" value="{{ item.firstname }}" class="m-0" name="firstname" placeholder="Введите имя">
								</div>
							</div>

							<div>
								<div class="input-block m-0">
									<label for="lastname" class="text-bold">Фамилия</label>
									<input type="text" pattern="^[\w\s]{1,32}$" id="lastname" value="{{ item.lastname }}" class="m-0" name="lastname" placeholder="Введите фамилию">
								</div>
							</div>
						</div>

						<div class="input-block">
							<label for="gender" class="text-bold">Пол</label>
							<select name="gender" id="gender">
								<option value="0">Мужской</option>
								<option value="1" {% if item.gender|int %}selected{% endif %}>Женский</option>
							</select>
						</div>

						<div class="input-block">
							<label for="birthday" class="text-bold">Дата рождения</label>
							<input type="text" pattern="^[0-9][0-9]\.[0-9][0-9]\.[0-9][0-9][0-9][0-9]$" id="birthday" class="m-0 datepicker fv-input" data-dp-autoclose="true" name="birthday" value="{{ item.birthday|date('d.m.Y') }}" placeholder="Ввыберите дату рождеемя">
						</div>

						<div class="input-block">
							<label for="about" class="text-bold">О пользователе</label>
							<textarea name="about" id="about" placeholder="Введите информацию о пользователе" class="fv-input" maxlength="65535">{{ item.about }}</textarea>
						</div>

						<div class="text-center">
							<button type="submit" class="btn">Сохранить</button>
						</div>
					</div>

					<div class="block-right window">
						<div class="permissions">
							<div class="title mb-16">Привилегии пользователя</div>

							<div class="permissions-selector">
								<div class="block-left">
									<select id="permission-list" class="m-0">
										<option value="-1" data-type="" data-title="" data-default="">Выберите привилегию</option>
                                        {% for permission in permissions %}
											<option value="{{ permission.id }}" data-title="{{ permission.title }}" data-type="{{ permission.type }}" data-default="{{ permission.default }}">
                                                {{ permission.title }}
											</option>
                                        {% endfor %}
									</select>
								</div>

								<div class="block-center" id="permission-type"><div class="lh-36px text-upper text-center col-gray">Тип привилегии</div></div>

								<div class="block-right">
									<button class="btn block add-perm-trigger"><i class="fa fa-plus"></i></button>
								</div>
							</div>

                            {% if links %}
								<div class="permissions-selected scroll-styled">
                                    {% for link in links %}
										<div class="permission-id" data-id="{{ link.id }}">
											<div class="title">{{ link.title }}</div>
											<div class="value">
                                                {% if link.type=='boolean' %}
													<select class="m-0" name="permissions[{{ link.id }}]">
														<option value="false">Нет</option>
														<option value="true" {% if link.value=='true' %}selected{% endif %}>Да</option>
													</select>
                                                {% elseif link.type=='integer' or link.type=='float' %}
													<input class="m-0" type="number" name="permissions[{{ link.id }}]" value="{{ link.value }}">
                                                {% else %}
													<input class="m-0" type="text" name="permissions[{{ link.id }}]" value="{{ link.value }}">
                                                {% endif %}
											</div>
											<div class="actions">
												<div><button class="btn block permissions-remove"><i class="fa fa-times"></i></button></div>
											</div>
										</div>
                                    {% endfor %}
								</div>
                            {% endif %}
						</div>
					</div>
				</div>

				<div class="form-success window w-50 m-auto">
					<i class="fa fa-check"></i>
				</div>
			</form>
		</div>
	</div>
</div>

{{include('footer.tpl')}}
</body>
</html>