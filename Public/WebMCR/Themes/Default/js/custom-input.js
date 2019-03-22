var input_custom = {
	compile: function(){
		$('.input-file-custom:not([data-init="true"])').each(function(){
			var that = $(this);

			var trigger = that.attr('data-trigger');

			if(trigger===undefined){ return; }

			var id = Math.random();

			that.attr('data-init', 'true').attr('data-input-id', id);

			trigger = $(trigger);

			if(!trigger.length){ return; }

			trigger.attr('data-input-custom', 'true').attr('data-input-id', id);
		});
	}
};

$(function(){
	input_custom.compile();

	$('body').on('click', '[data-input-custom="true"]', function(e){
		e.preventDefault();

		var that = $(this);

		var id = that.attr('data-input-id');

		$('.input-file-custom[data-input-id="'+id+'"]').trigger('click');
	});
});