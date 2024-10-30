(function($) {
	
	/**
	 * Tab configuration
	 */
	$('.redq-tab-menus .redq-tab-menu-item').click(function(){
		var t = $(this).attr('id');
	
		if(!$(this).hasClass('button-primary')){
			$('.redq-tab-menus .redq-tab-menu-item').removeClass('button-primary');           
			$(this).addClass('button-primary');
		
			$('.redq-tab-content').hide();
			$('#'+ t + 'Content').fadeIn('slow');
		}
	});

})(jQuery);