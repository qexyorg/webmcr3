$(function(){
	$('body').on('click', '#disable-install, #remove-install', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.load_elements(that.attr('data-action'), {}, function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			qx.notify(data.text, data.title);

			$('#install-actions').fadeOut('fast', function(){
				$('#after-install').fadeIn('fast');
			});

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	});
});
