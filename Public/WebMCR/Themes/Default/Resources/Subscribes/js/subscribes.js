$(function(){
	$('body').on('click', '.subscribe-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		var type = that.attr('data-type');

		var value = that.attr('data-value');

		qx.load_elements(meta.site_url+'subscribes/'+type+'/'+value, {}, function(data){
			that.prop('disabled', false);

			if(!data.type){ return qx.notify(data.text, data.title); }

			if(data.value){
				that.html('<i class="fa fa-bell-slash mr-4"></i> Отписаться').addClass('btn-transparent');
			}else{
				that.html('<i class="fa fa-bell mr-4"></i> Подписаться').removeClass('btn-transparent');
			}

			var target = $('.subscribes-target[data-subscribe-value="'+value+'"]');

			if(target.length){
				var target_value = parseInt(target.text());
				if(isNaN(target_value)){
					target_value = 0;
				}

				target_value = (data.value) ? target_value+1 : target_value-1;

				target.text(target_value);
			}

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	});
});