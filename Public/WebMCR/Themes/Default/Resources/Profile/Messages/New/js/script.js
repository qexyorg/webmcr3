$(function(){
	$('body').on('click', '.message-new [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			qx.notify(data.text, data.title);

			setTimeout(function(){
				window.location.href = meta.site_url+'profile/messages/'+data.link_id;
			}, 2000);

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	});
});