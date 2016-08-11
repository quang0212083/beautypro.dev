<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
*/

/**
 * @PCTODO: List of Populated Variables
 * 
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>

<script>


	(function($) {

		paycart.cart = {};

	    paycart.cart.product = {};

	    paycart.cart.product = {

	    	    updateQuantity : function(productId)
	    	    {
    	    		var link  = 'index.php?option=com_paycart&task=updateQuantity&view=cart';
    	    		var quantity = $('.pc-cart-quantity-'+productId).val();
    	    		var data  = {'product_id': productId, 'quantity': quantity, 'spinner_selector' : '#paycart-ajax-spinner'};
    	    		paycart.ajax.go(link,data);
    	    	},

    	    	error : function(data){
    				var response     = $.parseJSON(data);
    				var prevQuantity = response.prevQuantity;
    				var allowedQuantity = response.allowedQuantity;
    				var productId 	 = response.productId;
    				var message      = response.message;
    				$('.pc-cart-quantity-error-'+productId).text(message);
    				$('.pc-cart-quantity-'+productId).val(prevQuantity);
    	    	},
    	    	
    	        remove : function(productId)
    	        {
    	      		var link  = 'index.php?option=com_paycart&task=removeProduct&view=cart';
    	      	    var data  = {'product_id': productId, 'spinner_selector' : 	'#paycart-ajax-spinner'};
    	      	    paycart.ajax.go(link,data);
    	      	}
	       };
	 	
				
	})(paycart.jQuery);


</script>