$(function(){
	$('body').on('click', '.toggle-class', function(e){
		e.preventDefault();

		var that = $(this);

		var element = that.attr('data-element');

		var classname = that.attr('data-classname');

		if(classname!==undefined && classname!=='' && element!==undefined && element!==''){
			element = $(element);

			if(element.length){
				element.toggleClass(classname);
			}
		}
	});
});