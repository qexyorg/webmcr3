$(function(){
	$('body').on('click', '.profile .profile-menu > ul > li > .profile-menu-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var active_li = that.closest('ul').children('.active');
		var li = that.closest('li');

		var id = li.attr('data-id');

		if(id===undefined || id==='' || li.hasClass('active')){ return false; }

		var active_tab = $('.profile .profile-content > .wrapper > .content-tab.active');
		var tab = $('.profile .profile-content > .wrapper > .content-tab[data-id="'+id+'"]');

		if(!active_tab.length || !tab.length){ return false; }

		active_li.removeClass('active');
		li.addClass('active');

		active_tab.fadeOut('fast', function(){
			$(this).removeClass('active');

			tab.fadeIn('fast', function(){
				$(this).addClass('active');
			});
		});
	}).on('click', '.restore > form [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			qx.notify(data.text);

			setTimeout(function(){
				window.location.href = meta.site_url;
			}, 2000);

		}, false, function(data){
			console.log(data);

			grecaptcha.reset(widget_id);

			that.prop('disabled', false);
		});
	});
});