$(function(){
	$('body').on('click', '.statics-add [type="submit"]', function(e){
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
						window.location.href = meta.site_url+'admin/statics/';
					}, 1200);
				});
			});

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	}).on('input', '.statics-add #title', function(){
		$('.statics-add #route').val('static/'+qx.translate($(this).val()));
	});
});