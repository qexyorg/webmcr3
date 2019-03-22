$(function(){
	$('body').on('click', '.permissions-edit [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			form.find('.form-wrapper').fadeOut('fast', function(){
				$(this).remove();

				form.find('.form-success').fadeIn('fast', function(){
					setTimeout(function(){
						window.location.href = meta.site_url+'admin/permissions/';
					}, 1200);
				});
			});

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	});
});