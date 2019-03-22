$(function(){
	$('body').on('click', '.users > .user-list > .user-id .item-remove', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.confirm('Пользователь и вся оставленная им информация на сайте будет удалена безвозвратно. Вы уверены, что хотите удалить выбранного пользователя?', 'Подтвердите удаление', function(){
			qx.load_elements(that.attr('data-href'), {}, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				that.closest('.user-id').fadeOut('fast', function(){
					$(this).remove();
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});

	}).on('click', '.users > .user-list > .user-id .item-ban', function(e){
		e.preventDefault();

		var that = $(this);

		var user = that.closest('.user-id');

		var id = user.attr('data-id');

		that.prop('disabled', true);

		qx.load_elements(that.attr('data-href'), {}, function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			if(data.value==1){
				that.replaceWith('<button class="dropdown-item item-ban btn-clear" data-href="'+meta.site_url+'admin/users/ban/'+id+'/0">Разблокировать</button>');
			}else{
				that.replaceWith('<button class="dropdown-item item-ban btn-clear" data-href="'+meta.site_url+'admin/users/ban/'+id+'/1">Заблокировать</button>');
			}

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	}).on('click', '.users > .user-list > .user-id .item-banip', function(e){
		e.preventDefault();

		var that = $(this);

		var ip = that.attr('data-ip');

		$('.modal[data-target-id="'+that.attr('data-modal-id')+'"] #ip').val(ip);

	}).on('click', '.users > .user-list > .user-id .item-clear', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		qx.load_elements(that.attr('data-href'), {}, function(data){
			that.prop('disabled', false); qx.notify(data.text, data.title);
		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	}).on('click', '#banip-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		var form = that.closest('form');

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			that.prop('disabled', false);

			if(!data.type){
				return qx.notify(data.text, data.title);
			}

			that.closest('.modal').find('.close-modal').trigger('click');

			qx.notify(data.text, data.title);
		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	});
});