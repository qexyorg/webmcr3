$(function(){
	$('body').on('click', '.news-add [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			form.find('.form-wrapper, .form-bottom').fadeOut('fast', function(){
				$(this).remove();

				form.find('.form-success').fadeIn('fast', function(){
					setTimeout(function(){
						window.location.href = meta.site_url+'admin/news/';
					}, 1200);
				});
			});

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	}).on('input', '.news-add #title', function(){
		$('.news-add #name').val(qx.translate($(this).val()));
	}).on('input', '.news-add #image', function(){
		var val = $(this).val();

		if(val==''){
			val = meta.theme_url+'img/no-image-large.png';
		}

		$('#preview').css('background-image', 'url("'+val+'")');
	}).on('change', '.form-bottom .tags .tag-selector', function(e){
		e.preventDefault();

		var that = $(this);

		var li = that.closest('li');

		if(that.prop('checked')){
			li.addClass('active');
		}else{
			li.removeClass('active');
		}
	});
});