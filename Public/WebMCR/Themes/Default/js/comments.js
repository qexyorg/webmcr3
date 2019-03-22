$(function(){
	$('body').on('click', '#comment-form .comment-add-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('#comment-form');

		var action = form.find('.add-action-url').val();

		var block = form.closest('#comment-block');

		if(!form.length){
			return;
		}

		that.prop('disabled', true);

		qx.load_elements(action, qx.getFormValues(form), function(data){
			that.prop('disabled', false);

			if(!data.type){ return qx.notify(data.text, data.title); }

			if(data.comments_num <= 1){
				block.find('.comments-none').remove();

				form.after('<div class="comment-num pt-24 text-upper">Комментарии: <span class="comments_num_element">'+data.comments_num+'</span></div><div class="comments-block">'+data.comment+'</div>');

				$('.comments_num_element').text(data.comments_num);
			}else{
				$('.comments_num_element').text(data.comments_num);

				block.find('.comments-block').prepend(data.comment);
			}

			form[0].reset();

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});
	}).on('click', '#comment-block .comment-id .comment-remove-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		qx.confirm('Вы уверены, что хотите удалить выбранный комментарий?', 'Подтвердите удаление', function(){
			var comment = that.closest('.comment-id');

			var comment_id = comment.attr('data-id');

			var block = that.closest('#comment-block');

			var form = $('#comment-form');

			if(!form.length){
				return;
			}

			var action = form.find('.remove-action-url').val().replace('{COMMENT_ID}', comment_id);

			qx.load_elements(action, {'comment_id': comment_id}, function(data){

				if(!data.type){ return qx.notify(data.text, data.title); }

				comment.fadeOut('fast', function(){
					var self = $(this);
					$('.comments_num_element').text(data.num);

					if(data.num<=0){
						block.find('.comment-num, .comments-block').remove();
						form.after('<div class="comments-none">Нет доступных комментариев</div>');
					}
					self.remove();

					var comment_id_block = form.find('input[name="comment_id"]');

					if(comment_id_block.val()==comment_id){
						comment_id_block.val('');
						form.find('textarea').val('');
						form.find('.comment-add-trigger').css('display', 'block');
						form.find('.comment-save-trigger, .comment-cancel-trigger').hide();
					}
				});

			}, false, function(data){
				console.log(data);
			});
		});


	}).on('click', '#comment-block .comment-id .comment-quote-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var comment = that.closest('.comment-id');

		var comment_id = comment.attr('data-id');

		var form = $('#comment-form');

		if(!form.length){
			return;
		}

		var action = form.find('.quote-action-url').val().replace('{COMMENT_ID}', comment_id);

		var params = {
			'token':meta.token,
			'comment_id': comment_id
		};

		qx.load_elements(action, params, function(data){

			if(!data.type){ return qx.notify(data.text, data.title); }

			var textarea = form.find('textarea');

			textarea.val(textarea.val()+'[quote]'+data.comment+'[/quote]\n');
			textarea[0].focus();

		}, false, function(data){
			console.log(data);
		});
	}).on('click', '#comment-block .comment-id .comment-edit-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var comment = that.closest('.comment-id');

		var comment_id = comment.attr('data-id');

		var form = $('#comment-form');

		if(!form.length){
			return;
		}

		var action = form.find('.edit-action-url').val().replace('{COMMENT_ID}', comment_id);

		var params = {
			'token':meta.token,
			'comment_id': comment_id
		};

		qx.load_elements(action, params, function(data){

			if(!data.type){ return qx.notify(data.text, data.title); }

			form.find('.save-action-url').val().replace('{COMMENT_ID}', comment_id);

			var textarea = form.find('textarea');

			form.find('input[name="comment_id"]').val(comment_id);
			form.find('.comment-add-trigger').hide();
			form.find('.comment-save-trigger, .comment-cancel-trigger').css('display', 'block');

			textarea.val(data.comment);
			textarea[0].focus();

		}, false, function(data){
			console.log(data);
		});
	}).on('click', '#comment-form .comment-cancel-trigger', function(e){
		e.preventDefault();

		var form = $('#comment-form');

		var textarea = form.find('textarea');

		form.find('input[name="comment_id"]').val('');
		form.find('.comment-add-trigger').css('display', 'block');
		form.find('.comment-save-trigger, .comment-cancel-trigger').hide();

		textarea.val('');
	}).on('click', '#comment-form .comment-save-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('#comment-form');

		var comment_id = form.find('input[name="comment_id"]').val();

		var comment = $('#comment-block .comment-id[data-id="'+comment_id+'"]');

		var action = form.find('.save-action-url').val().replace('{COMMENT_ID}', comment_id);

		qx.load_elements(action, qx.getFormValues(form), function(data){

			if(!data.type){ return qx.notify(data.text, data.title); }

			form.find('input[name="comment_id"]').val('');
			form.find('button[type="submit"][data-type="add"]').css('display', 'block');
			form.find('button[type="submit"][data-type="save"],button[type="submit"][data-type="cancel"]').hide();

			form.find('textarea').val('');

			comment.replaceWith(data.comment);

		}, false, function(data){
			console.log(data);
		});
	});
});