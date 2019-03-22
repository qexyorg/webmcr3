$(function(){
	$('body').on('click', '.search-news [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		var search = form.find('input[name="search"]').val();

		search = $.trim(search);

		if(search!=''){
			window.location.href = form.attr('action')+search;
		}
	});
});