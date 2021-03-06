$(function(){
	$('body').on('click', '.permissions > .permission-list > .permission-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Вы уверены, что хотите удалить выбранную привилегию?', 'Подтвердите удаление', function(){
			qx.load_elements(that.attr('data-href'), {}, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				that.closest('.permission-id').fadeOut('fast', function(){
					$(this).remove();
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});

	}).on('click', '.permissions > .permission-list > .permission-id .show-info', function(e){
		e.preventDefault();

		var that = $(this);

		var permission = that.closest('.permission-id');

		permission.find('.view').fadeOut('fast', function(){
			permission.find('.info').fadeIn('fast');
		});
	}).on('click', '.permissions > .permission-list > .permission-id .hide-info', function(e){
		e.preventDefault();

		var that = $(this);

		var permission = that.closest('.permission-id');

		permission.find('.info').fadeOut('fast', function(){
			permission.find('.view').fadeIn('fast');
		});
	});
});