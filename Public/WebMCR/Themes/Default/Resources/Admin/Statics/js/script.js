$(function(){
	$('body').on('click', '.statics > .static-list > .static-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Вы уверены, что хотите удалить выбранную статическую страницу?', 'Подтвердите удаление', function(){
			qx.load_elements(that.attr('data-href'), {}, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				that.closest('.static-id').fadeOut('fast', function(){
					$(this).remove();
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});

	}).on('click', '.statics > .static-list > .static-id .item-public', function(e){
		e.preventDefault();

		var that = $(this);

		var item = that.closest('.static-id');

		var id = item.attr('data-id');

		that.prop('disabled', true);

		qx.load_elements(that.attr('data-href'), {}, function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			if(data.value==1){
				that.replaceWith('<button title="Снять с публикации" class="item-public btn btn-clear" data-href="'+meta.site_url+'admin/statics/public/'+id+'/0"><i class="fa fa-eye-slash"></i></button>');
				item.find('.is_public').text('Опубликовано');
			}else{
				that.replaceWith('<button title="Опубликовать" class="item-public btn btn-clear" data-href="'+meta.site_url+'admin/statics/public/'+id+'/1"><i class="fa fa-eye"></i></button>');
				item.find('.is_public').text('Не опубликовано');
			}

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	});
});