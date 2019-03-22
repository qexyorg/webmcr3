$(function(){
	$('body').on('click', '#submit-step-1', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			qx.notify(data.text, data.title);

			setTimeout(function(){
				window.location.href = that.attr('data-next');
			}, 2000);

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	}).on('click', '#check-submit', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.load_elements(that.attr('data-action'), qx.getFormValues(that.closest('form')), function(data){
			that.prop('disabled', false);

			qx.notify(data.text, data.title);
		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	});
});
