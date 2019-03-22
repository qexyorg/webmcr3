$(function(){
	$('body').on('click', '.news > .new-list > .new-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Вы уверены, что хотите удалить выбранную новость?', 'Подтвердите удаление', function(){
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

	}).on('click', '.news > .new-list > .new-id .item-public', function(e){
		e.preventDefault();

		var that = $(this);

		var news = that.closest('.new-id');

		var id = news.attr('data-id');

		that.prop('disabled', true);

		qx.load_elements(that.attr('data-href'), {}, function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			if(data.value==1){
				that.replaceWith('<button class="dropdown-item item-public btn-clear" data-href="'+meta.site_url+'admin/statics/public/'+id+'/0">Скрыть</button>');
				news.find('.ispublic').html('<i class="fa fa fa-eye" title="Опубликовано"></i>');
			}else{
				that.replaceWith('<button class="dropdown-item item-public btn-clear" data-href="'+meta.site_url+'admin/statics/public/'+id+'/1">Опубликовать</button>');
				news.find('.ispublic').html('<i class="fa fa fa-eye-slash" title="Снято с публикации"></i>');
			}

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	});
});