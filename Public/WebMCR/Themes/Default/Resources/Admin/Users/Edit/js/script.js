$(function(){
	$('body').on('click', '.user-edit .edit-form [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		that.prop('disabled', true);

		qx.load_elements(form.attr('action'), qx.getFormValues(form), function(data){
			if(!data.type){ that.prop('disabled', false); return qx.notify(data.text, data.title); }

			form.find('.form-wrapper').fadeOut('fast', function(){
				$(this).remove();

				form.find('.form-success').fadeIn('fast', function(){
					setTimeout(function(){
						window.location.href = meta.site_url+'admin/users/';
					}, 1200);
				});
			});

		}, false, function(data){
			console.log(data);

			that.prop('disabled', false);
		});

	}).on('change', '.permissions #permission-list', function(){
		var that = $(this);

		var option = that.find('option:selected');

		var type = option.attr('data-type');
		var value = option.attr('data-default');
		var id = that.val();

		var permtype = $('#permission-type');

		if(id=='-1'){
			permtype.html('<div class="lh-36px text-upper text-center col-gray">Тип привилегии</div>');

			return;
		}

		if(type=='boolean'){
			permtype.html('<select id="permission-type-value" class="m-0""><option value="false">Нет</option><option value="true">Да</option></select>');
		}else if(type=='integer' || type=='float'){
			permtype.html('<input id="permission-type-value" type="number" placeholder="Введите число">');
		}else{
			permtype.html('<input id="permission-type-value" type="text" placeholder="Введите значение">');
		}

		$('#permission-type-value').val(value);

	}).on('click', '.permissions .add-perm-trigger', function(e){
		e.preventDefault();

		var selected = $('#permission-list option:selected');

		var id = selected.attr('value');

		var type = selected.attr('data-type');

		var title = selected.attr('data-title');

		if(id=='-1'){ return; }

		var wrapper = $('.permissions .permissions-selected');

		if(!wrapper.length){
			$('.permissions').append('<div class="permissions-selected scroll-styled"></div>');

			wrapper = $('.permissions .permissions-selected');
		}

		var exists = wrapper.find('.permission-id[data-id="'+id+'"]');

		if(exists.length){

			qx.confirm('Привилегия уже добавлена. Хотите заменить значение?', 'Подтвердите замену', function(){

				var value = $('#permission-type-value').val();

				var input = '<input class="m-0" type="text" name="permissions['+id+']" value="'+value+'">';

				if(type=='boolean'){
					input = '<select class="m-0" name="permissions['+id+']">'
						+'<option value="false">Нет</option>'
						+'<option value="true" '+(value=='true' ? 'selected' : '')+'>Да</option>'
						+'</select>';
				}else if(type=='integer' || type=='float'){
					input = '<input class="m-0" type="number" name="permissions['+id+']" value="'+value+'">';
				}

				exists.replaceWith('<div class="permission-id" data-id="'+id+'">'
					+'<div class="title">'+title+'</div>'
					+'<div class="value">'+input+'</div>'
					+'<div class="actions"><div><button class="btn block permissions-remove"><i class="fa fa-times"></i></button></div></div>'
					+'</div>');
			});
		}else{

			var value = $('#permission-type-value').val();

			var input = '<input class="m-0" type="text" name="permissions['+id+']" value="'+value+'">';

			if(type=='boolean'){
				input = '<select class="m-0" name="permissions['+id+']">'
					+'<option value="false">Нет</option>'
					+'<option value="true" '+(value=='true' ? 'selected' : '')+'>Да</option>'
					+'</select>';
			}else if(type=='integer' || type=='float'){
				input = '<input class="m-0" type="number" name="permissions['+id+']" value="'+value+'">';
			}

			wrapper.append('<div class="permission-id" data-id="'+id+'">'
				+'<div class="title">'+title+'</div>'
				+'<div class="value">'+input+'</div>'
				+'<div class="actions"><div><button class="btn block permissions-remove"><i class="fa fa-times"></i></button></div></div>'
				+'</div>');
		}
	}).on('click', '.permissions .permissions-selected > .permission-id .permissions-remove', function(e){
		e.preventDefault();

		var that = $(this);

		var selected = that.closest('.permissions-selected');

		qx.confirm('Вы уверены, что хотите убрать у пользователя выбранную привилегию?', 'Подтвердите удаление', function(){
			that.closest('.permission-id').fadeOut('fast', function(){
				$(this).remove();

				if(!selected.find('.permission-id').length){
					selected.remove();
				}
			});
		});
	}).on('click', '#remove-avatar', function(e){
		e.preventDefault();

		$(this).closest('div').find('.avatar-target-bg').each(function(){

			var self = $(this);

			var tag = self.prop('tagName');
			if(tag=='INPUT' || tag=='TEXTAREA'){
				self.val('');
			}else{
				console.log(1);
				self.css('background-image', 'url('+meta.site_url+'Uploads/avatars/camera.png)');
			}
		});
	});
});