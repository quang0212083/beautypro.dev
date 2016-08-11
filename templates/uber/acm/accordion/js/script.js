(function($){
	$(document).ready(function(){
        //Fix to set multi module accordion in one page
        var countModules = $(".panel-group").length;
        
        if(countModules > 1){
            for(var i = 1; i < countModules; i++){
                $($(".panel-group")[i]).attr('id', newIdMod);
                
                var oldIdMod = $('.panel-group').attr('id');
                var newIdMod = oldIdMod+'-'+i;
                
                var children = $($(".panel-group")[i]).children().children();
                
                for(var j = 0; j < children.length; j++){
                    if (j%2 != 0){
                        var oldItemId = $(children[j]).attr('id');
                        var newItemId = oldItemId+'-'+i;
                        $(children[j]).attr('id', newItemId);
                    } else {
                        var oldItemId = $(children[j+1]).attr('id');
                        var newItemId = oldItemId+'-'+i;
                        
                        $(children[j]).find('a').attr('href', '#'+newItemId);
                        $(children[j]).find('a').attr('data-parent', '#'+newIdMod);
                    }
                }
            }
        }
        
		$(".panel-heading a").click(function(e){
			if ($(this).hasClass('active')) {
				$(this).find('.icon').toggleClass('fa-plus fa-minus');
			} else {
				$(".panel-heading a").find('.icon').addClass('fa-plus').removeClass('fa-minus');
				$(this).find('.icon').addClass('fa-minus').removeClass('fa-plus');
			}

			$(".panel-heading a").removeClass('active');
			$(this).addClass('active');

			e.preventDefault();
		});
  });
})(jQuery);