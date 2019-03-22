$(function(){
	$('body').on('click', '.like-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var url = that.attr('data-likes-url');

		var num_block = that.find('.like-num');

		var num = parseInt(num_block.text());

		if(isNaN(num)){ num = 0; }

		qx.load_elements(url, {'token':meta.token}, function(data){

			if(!data.type){ return qx.notify(data.text, data.title); }

			num = (that.hasClass('active')) ? num-1 : num+1;

			that.toggleClass('active');

			num_block.text(num);

		}, false, function(data){
			console.log(data);
		});
	}).on('click', '.view-trigger', function(e){
		e.preventDefault();
	});
});