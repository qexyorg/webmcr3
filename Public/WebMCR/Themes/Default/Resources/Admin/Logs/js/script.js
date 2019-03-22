$(function(){
	$('body').on('click', '.logs > .log-list > .log-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Вы уверены, что хотите удалить выбранный лог действия пользователя?', 'Подтвердите удаление', function(){
			qx.load_elements(that.attr('data-href'), {}, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				that.closest('.log-id').fadeOut('fast', function(){
					$(this).remove();
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});

	});
});