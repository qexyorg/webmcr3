$(function(){
	$('body').on('click', '.settings [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			that.prop('disabled', false);

			qx.notify(data.text, data.title);

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	});
});