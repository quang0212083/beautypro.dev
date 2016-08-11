(function($){
	$(document).ready(function(){
		$('.style-2 .item-title').next().hide();
		$('.style-2 .item-row:first-child .item-title').next().show();
		$('.style-2 .item-title').click(function(){
			$(this).next().slideToggle();
		});
  });
})(jQuery);