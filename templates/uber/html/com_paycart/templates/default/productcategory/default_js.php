<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
* @author		rimjhim jain
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );?>

<script type="text/javascript">

(function($){
	$(document).ready(function(){
		// setup paycart-wrap size
		var sizeclass = paycart.helper.do_apply_sizeclass('.paycart-wrap');
		
		paycart.jui.defaults();
		
		paycart.helper.do_grid_layout('#pc-categories[data-columns]','.pc-categories-wrapper', '.pc-category', sizeclass);
	
		// also do resize category height = width
		$('.pc-category').height($('.pc-category').width());
		
		// vertical center align
		paycart.helper.do_vertical_center('.vertical-center-wrapper');
		
		// arrange item layout
		paycart.helper.do_grid_layout('#pc-products[data-columns]','.pc-products-wrapper', '.pc-product', sizeclass);
	});
})(paycart.jQuery);

</script>