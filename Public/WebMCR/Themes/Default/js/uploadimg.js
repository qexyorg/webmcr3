function init_image_uploader(){
	$('.image-uploader').each(function(){
		var that = $(this);

		var name = that.attr('name');

		var id = Math.random();

		var to = that.attr('data-to');

		var to_image = that.attr('data-to-image');

		var data_input = that.attr('data-input');

		if(data_input!==undefined){
			if(!$(data_input).hasClass('image-uploader-trigger')){
				$(data_input).hide().addClass('image-uploader-trigger').attr('data-upload-id', id);
			}
		}else{
			if(!that.hasClass('image-uploader-trigger')){
				that.addClass('image-uploader-trigger').attr('data-upload-id', id);
			}
		}

		if(that.attr('data-upload-id')==undefined){
			if(that.attr('name')!=undefined){
				that.removeAttr('name').attr('data-upload-id', id).attr('data-name', name);
			}else{
				that.attr('data-upload-id', id);
			}
		}


		if(to_image!==undefined){
			if($(to_image).attr('data-upload-id')==undefined){
				$(to_image).attr('data-upload-id', id);
			}
		}

		if(to===undefined){
			if(!$('.image-uploader-inpu[data-upload-id="'+id+'"][name="'+name+'"]').length){
				that.after('<input type="hidden" class="image-uploader-input" data-upload-id="'+id+'" name="'+name+'">');
			}
		}else{
			if(!$(to).hasClass('image-uploader-input')){
				if($(to).attr('name')===undefined){
					$(to).attr('name', name).addClass('image-uploader-input').attr('data-upload-id', id);
				}else{
					$(to).addClass('image-uploader-input').attr('data-upload-id', id);
				}
			}
		}
	});
}

$(function(){
	init_image_uploader();

	$('body').on('click', '.image-uploader', function(){

		var that = $(this);

		var target = $('input[type="file"]'+that.attr('data-input'));

		if(!that.is(target)){
			target.trigger('click');
		}
	}).on('change', '.image-uploader-trigger', function(){

		var that = $(this);

		var id = that.attr('data-upload-id');

		var trigger = $('.image-uploader[data-upload-id="'+id+'"]');

		var trigger_to_image = trigger.attr('data-to-image');

		var params = {
			'token': meta.token,
			'image': that[0].files[0]
		};

		qx.load_elements(meta.site_url+'uploader/image', params, function(data){
			if(!data.type){ return qx.notify(data.text, data.title); }

			if(trigger_to_image!==undefined){

				var to_image = $(trigger_to_image);

				if(to_image.prop("tagName")=='IMG'){
					if(!to_image.hasClass('visible')){
						to_image.attr('src', data.image).addClass('visible');
					}else{
						to_image.attr('src', data.image);
					}
				}else{
					if(!to_image.hasClass('visible')){
						to_image.css('background-image', 'url('+data.image+')').addClass('visible');
					}else{
						to_image.css('background-image', 'url('+data.image+')');
					}
				}
			}

			$('.image-uploader-input[data-upload-id="'+id+'"]').val(data.image).trigger('input');
		}, false, function(data){
			console.log(data);
		});
	}).on('click', '.image-uploader-remover', function(e){
		e.preventDefault();

		var that = $(this);

		var data_for = that.attr('data-for');

		if(data_for===undefined){
			return false;
		}

		var preview = $(data_for);

		if(preview.length<=0){
			return false;
		}

		var id = preview.attr('data-upload-id');

		var def = that.attr('data-default');

		if(def==undefined){
			def = '';
		}

		if(preview.prop("tagName")=='IMG'){
			preview.attr('src', def).removeClass('visible');
		}else{
			if(def==''){
				preview.css('background-image', 'none').removeClass('visible');
			}else{
				preview.css('background-image', 'url('+def+')');
			}
		}

		$('.image-uploader-input[data-upload-id="'+id+'"]').val('').trigger('input');
	});
});