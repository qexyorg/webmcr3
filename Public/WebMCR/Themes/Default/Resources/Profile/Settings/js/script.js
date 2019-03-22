$(function(){
	$('body').on('click', 'form.settings [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		var form = that.closest('form');

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			that.prop('disabled', false);

			if(!data.type){ return qx.notify(data.text, data.title); }

			qx.notify(data.text);

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	});
});