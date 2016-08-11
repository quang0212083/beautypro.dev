if(typeof jQuery != 'undefined'){
	window._jQuery = jQuery.noConflict(true);
	if(!window.jQuery){
		window.jQuery = window._jQuery;
		window._jQuery = null;
	}

	jQuery.noConflict();
}