<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );
?>
<div class="pc-checkout-state row-fluid clearfix">
	<?php echo $this->loadTemplate('steps');?>
</div>

	 <div class="pc-checkout-address row">
	 <div class="col-sm-6">
		 	<div class="pc-checkout-billing ">
		 		<h4><?php echo JText::_('COM_PAYCART_ADDRESS_BILLING'); ?></h4>
				<label class="checkbox">&nbsp;</label>
		 		<div class="pc-checkout-billing-html">
				 	<?php
				 		// load billing address template
				 		echo $this->loadTemplate('address_billing'); 
				 	?>
				 </div>
			</div>
		</div>
		<div class="col-sm-6">
		<!--	Shipping Address	-->
		<div class="pc-checkout-shipping clearfix">
		
			<h3><?php echo JText::_('COM_PAYCART_ADDRESS_SHIPPING'); ?></h3>

			<label class="checkbox">
				<input 	id='billing_to_shipping' type="checkbox" 
						<?php echo ($billing_to_shipping)? 'checked="checked"' : ''?>		
						name="paycart_cart_address[billing_to_shipping]"
						onClick="return paycart.cart.address.onBillingToShipping();"
						value='true'
				/><?php echo JText::_('COM_PAYCART_SAME_ADDRESS_TEXT'); ?>
					
			</label>
			
			<div class="pc-checkout-shipping-html">
			 	<?php
					// load shipping address template
			 		echo $this->loadTemplate('address_shipping');
			 	?>
		 	</div>
		 	
		</div>
		</div>
		<hr>
		<!--	Continue Checkout	-->
		<div class="clearfix">
			<button type="button" onClick="paycart.cart.address.onContinue();" 
					class="pc-whitespace btn btn-large btn-primary">
				<?php echo JText::_('COM_PAYCART_BUTTON_CONTINUE'); ?> <i class="fa fa-angle-double-right"></i>
			</button>
		</div>
			
		<input	type="hidden"	name='step_name' value='address' />
				
	</div>
		
	<script>
			
		(function($) {
			$(document).ready(function(){			
				paycart.cart.address.init();
			});			
		})(paycart.jQuery);
	
	</script>	 

<?php

