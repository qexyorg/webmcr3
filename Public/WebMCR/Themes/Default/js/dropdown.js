$(function(){
	$('body').on('click', '.dropdown .dropdown-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var dropdown = that.closest('.dropdown');

		var target = dropdown.children('.dropdown-target');

		$('.dropdown-target').not(target).removeClass('active');

		target.toggleClass('active');
	});

	$('html').on('click', 'body', function(e){
		if($(e.target).closest('.dropdown').length<=0){
			$('.dropdown-target').removeClass('active');
		}
	});
});