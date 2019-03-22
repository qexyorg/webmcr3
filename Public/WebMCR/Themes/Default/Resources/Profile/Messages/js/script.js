$(function(){
	$('body').on('click', '.message-list .message-id .remove-message', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		var message = that.closest('.message-id');

		qx.confirm('Вы уверены, что хотите удалить выбранное сообщение?', 'Подтвердите удаление', function(){
			var params = {
				'token': meta.token,
				'link_id': message.attr('data-id')
			};

			qx.load_elements(meta.site_url+'profile/message/remove', params, function(data){
				if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

				//qx.notify(data.text);

				message.fadeOut('fast', function(){
					$(this).remove();

					var list = $('.message-list');

					if(!$('.message-list > .message-id.body').length){
						list.html('<div class="message-none">Нет доступных сообщений</div>');
					}

					var num_block = $('.messages .messages-header .messages-num');

					var num = parseInt(num_block.text())-1;

					num_block.text(num);

					if(num<=0){
						$('.messages .pagination-block').remove();
					}
				});

			}, false, function(data){
				console.log(data);

				that.prop('disabled', false);
			});
		}, function(){
			that.prop('disabled', false);
		});
	}).on('click', '.message-list > .message-id > .actions .lock, .message-list > .message-id > .actions .unlock', function(e){
		e.preventDefault();

		var that = $(this);

		that.prop('disabled', true);

		var message = that.closest('.message-id');

		var value = (that.hasClass('unlock')) ? 0 : 1;

		var params = {
			'token': meta.token,
			'link_id': message.attr('data-id'),
			'value': value
		};

		qx.load_elements(meta.site_url+'profile/message/lock', params, function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			var li = that.closest('li');

			if(value){
				li.html('<button title="Открыть" class="unlock btn btn-h20"><i class="fa fa-unlock"></i></button>');
			}else{
				li.html('<button title="Закрыть" class="lock btn btn-h20"><i class="fa fa-lock"></i></button>');
			}

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	});
});