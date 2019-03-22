$(function(){
	$('body').on('click', '.tags > .tag-list > .tag-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Вы уверены, что хотите удалить выбранный тег?', 'Подтвердите удаление', function(){
			qx.load_elements(that.attr('data-href'), {}, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				that.closest('.tag-id').fadeOut('fast', function(){
					$(this).remove();
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});

	}).on('click', '.tags > .tag-list > .tag-id .show-info', function(e){
		e.preventDefault();

		var that = $(this);

		var tag = that.closest('.tag-id');

		tag.find('.view').fadeOut('fast', function(){
			tag.find('.info').fadeIn('fast');
		});
	}).on('click', '.tags > .tag-list > .tag-id .hide-info', function(e){
		e.preventDefault();

		var that = $(this);

		var tag = that.closest('.tag-id');

		tag.find('.info').fadeOut('fast', function(){
			tag.find('.view').fadeIn('fast');
		});
	});
});