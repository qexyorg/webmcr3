$(function(){
	$('body').on('click', '.groups > .group-list > .group-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Вы уверены, что хотите удалить выбранную группу?', 'Подтвердите удаление', function(){
			qx.load_elements(that.attr('data-href'), {}, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				that.closest('.group-id').fadeOut('fast', function(){
					$(this).remove();
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});

	}).on('click', '.groups > .group-list > .group-id .show-info', function(e){
		e.preventDefault();

		var that = $(this);

		var permission = that.closest('.group-id');

		permission.find('.view').fadeOut('fast', function(){
			permission.find('.info').fadeIn('fast');
		});
	}).on('click', '.groups > .group-list > .group-id .hide-info', function(e){
		e.preventDefault();

		var that = $(this);

		var group = that.closest('.group-id');

		group.find('.info').fadeOut('fast', function(){
			group.find('.view').fadeIn('fast');
		});
	});
});