var captchas = {
	list: {},
	init_captchas: function(){
		var self = this;

		$('.g-recaptcha-target').each(function(k){
			var that = $(this);

			var id = that.attr('id');

			self.list[id] = grecaptcha.render(id, {
				sitekey: that.attr('data-sitekey'),
				size: '300px'
			});
		});
	}
};

var onloadCallback = function() {
	captchas.init_captchas();
};

$(function(){
	var main = {
		form: {
			element: $('#loginForm'),

			login: function(){
				this.element.find('.register-wrapper').hide();
				this.element.find('.restore-wrapper').hide();
				this.element.find('.login-wrapper').fadeIn();
			},

			register: function(){
				this.element.find('.login-wrapper').hide();
				this.element.find('.restore-wrapper').hide();
				this.element.find('.register-wrapper').fadeIn();
			},

			restore: function(){
				this.element.find('.register-wrapper').hide();
				this.element.find('.login-wrapper').hide();
				this.element.find('.restore-wrapper').fadeIn();
			}
		}
	};

	$('body').on('click', '#loginForm .login-trigger', function(e){
		e.preventDefault();

		main.form.login();
	}).on('click', '#loginForm .register-trigger', function(e){
		e.preventDefault();

		main.form.register();
	}).on('click', '#loginForm .restore-trigger', function(e){
		e.preventDefault();

		main.form.restore();
	}).on('click', '#loginForm .login-run', function(e){
		e.preventDefault();

		var that = $(this);

		var params = qx.getFormValues($('#loginForm .login-wrapper'));
		params.token = meta.token;

		that.prop('disabled', true);

		qx.load_elements(meta.site_url+'profile/login', params, function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			qx.notify(data.text);

			setTimeout(function(){
				window.location.reload();
			}, 1000);

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	}).on('click', '#loginForm .register-run', function(e){
		e.preventDefault();

		var that = $(this);

		var params = qx.getFormValues($('#loginForm .register-wrapper'));

		if(captcha_enable){
			var widget_id = captchas.list['register-rc'];

			params.token = meta.token;
			params.captcha = grecaptcha.getResponse(widget_id);
		}

		that.prop('disabled', true);

		qx.load_elements(meta.site_url+'profile/register', params, function(data){
			if(!data.type){
				if(captcha_enable) {
					grecaptcha.reset(widget_id);
				}
				that.prop('disabled', false);
				return qx.notify(data.text, data.title);
			}

			qx.notify(data.text);

			$('#loginForm .login-trigger').trigger('click');

		}, false, function(data){
			console.log(data);
			if(captcha_enable) {
				grecaptcha.reset(widget_id);
			}

			that.prop('disabled', false);
		});
	}).on('click', '#loginForm .restore-run', function(e){
		e.preventDefault();

		var that = $(this);

		var params = qx.getFormValues($('#loginForm .restore-wrapper'));

		if(captcha_enable) {
			var widget_id = captchas.list['restore-rc'];

			params.token = meta.token;
			params.captcha = grecaptcha.getResponse(widget_id);
		}

		that.prop('disabled', true);

		qx.load_elements(meta.site_url+'profile/restore', params, function(data){
			if(!data.type){
				if(captcha_enable) {
					grecaptcha.reset(widget_id);
				}
				that.prop('disabled', false);
				return qx.notify(data.text, data.title);
			}

			qx.notify(data.text);

			$('#loginForm .login-trigger').trigger('click');

		}, false, function(data){
			console.log(data);

			if(captcha_enable) {
				grecaptcha.reset(widget_id);
			}

			that.prop('disabled', false);
		});
	});
});