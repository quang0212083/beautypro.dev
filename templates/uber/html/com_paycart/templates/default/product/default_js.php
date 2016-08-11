<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<script type="text/javascript">
(function($){
	//Specific to Safari bowser, it diplays cached data when back button is clicked
	// so reloading on back button click
	$(window).bind("pageshow", function(event) {
	    if (event.originalEvent.persisted) {
	        window.location.reload() 
	    }
	});
	
	$(document).ready(function(){

		$("#pc-screenshots-carousel").owlCarousel({ 
			lazyLoad : true, singleItem:true, 
			autoHeight : true, pagination:true 
		});

		$('div.accordion-body').on('shown', function () {
			$(this).parent("div").find(".fa-plus-square").removeClass("fa-plus-square").addClass("fa-minus-square");
		});

		$('div.accordion-body').on('hidden', function () {
			$(this).parent("div").find(".fa-minus-square").removeClass("fa-minus-square").addClass("fa-plus-square")
		});
	});
	
	paycart.product = {};
	paycart.product.selector = {};
	
	paycart.product.selector.onChange= function(value){
		var baseAttrId = <?php echo $baseAttrId; ?>;
		if(baseAttrId && value.id == 'pc-attr-' + baseAttrId){
			$('.pc-product-base-attribute').val(baseAttrId);
		}
		$('.pc-product-selector').submit();
	},

	//@PCTODO :: Should be move in paycart.js file so other extension can utilize it
	// and trigger must be hard coded,  not as a callback success 
	paycart.product.addtocart = function(productId) { 
		paycart.ajax.go(
							'index.php?option=com_paycart&view=cart&task=addProduct&product_id='+productId,
							{'spinner_selector' : '#paycart-ajax-spinner'},
							function(){
								paycart.event.cart.updateproduct();
								paycart.product.changeButtonText();								
							});

	},

	paycart.product.changeButtonText = function(){
		$('.pc-btn-addtocart').html("<?php echo JText::_('COM_PAYCART_CART_VIEW')." &nbsp;&nbsp; <i class='fa fa-chevron-right'></i>";?>");
		$('.pc-btn-addtocart').attr('onClick','rb.url.redirect("<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=display'); ?>"); return false;');
		$('.pc-btn-buynow').replaceWith("<h3 class='pc-message text-info'><?php echo JText::_('COM_PAYCART_PRODUCT_ADDED_TO_CART')?></h3>");
	}

})(paycart.jQuery);
</script>
