$(function(){
	$('body').on('change', '.input-file-uploader', function(){
		var that = $(this);

		var input = that.attr('data-input');

		var bg = that.attr('data-bg');

		var block = that.attr('data-block');

		var form = that.closest('form');

		qx.load_elements(meta.site_url+'uploader/', qx.getFormValues(form), function(data){

			form[0].reset();

			if(!data.type){ return qx.notify(data.text, data.title); }

			if(input!==undefined){
				$(input).val(data.url);
			}

			if(block!==undefined){
				$(block).html(data.url);
			}

			if(bg!==undefined){
				$(bg).css({'background-image':'url("'+data.url+'?'+Math.random()+'")'});
			}

		}, false, function(data){
			console.log(data);
		});
	});
});