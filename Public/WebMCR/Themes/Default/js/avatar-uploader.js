$(function(){
	$('body').on('click', '.change-avatar-trigger', function(e){
		e.preventDefault();

		var triggers = $('.change-avatar-trigger');

		if(triggers.length){
			triggers.each(function(){
				var self = $(this);

				var id = self.attr('data-id');

				if(id!==undefined){ return; }

				id = Math.random();

				self.attr('data-id', id);

				$('body').prepend('<form method="post" data-id="'+id+'" class="change-avatar-form" enctype="multipart/form-data">'
						+'<input type="file" class="d-none" name="avatar">'
					+'</form>');
			});
		}

		var that = $(this);

		var id = that.attr('data-id');

		$('.change-avatar-form[data-id="'+id+'"] input[name="avatar"]').trigger('click');
	}).on('change', '.change-avatar-form input[name="avatar"]', function(){
		var that = $(this);

		var form = that.closest('form');

		var id = form.attr('data-id');

		var trigger = $('.change-avatar-trigger[data-id="'+id+'"]');

		var url = trigger.attr('data-upload-url');

		if(url===undefined){ url = meta.site_url+'profile/avatar/change'; }

		qx.load_elements(url, qx.getFormValues(form), function(data){

			form[0].reset();

			if(!data.type){ return qx.notify(data.text, data.title); }

			var to = trigger.attr('data-to');

			var changes = (to===undefined) ? $('.avatar-target-bg') : $(to);

			if(changes.length){
				changes.each(function(){
					var self = $(this);

					var tag = self.prop("tagName");

					if(tag=='INPUT' || tag=='TEXTAREA'){
						self.val(data.avatar);
					}else{
						self.css('background-image', 'url('+data.avatar+'?'+Math.random()+')');
					}
				});
			}

		}, false, function(data){
			console.log(data);
		});
	});
});