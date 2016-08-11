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
<div class='pc-checkout-wrapper clearfix '>	
	
	<span for="pc-checkout-payment-error" class="pc-error" id="pc-checkout-payment-error"></span>
	<span for="pc-checkout-payment-processing-error" class="pc-error" id="pc-checkout-payment-processing-error"></span>

	<div class="pc-checkout-payment" id="accordion-parent" >
		<div class="row-fluid ">
			<h3><strong><?php echo JText::_('COM_PAYCART_PAYMENT');	?></strong></h3>
		</div>
		
		<div class="row">
			<!-- 	Payment Selection Part		-->
			<div class="col-sm-4">			
				<p><strong><?php echo JText::_('COM_PAYCART_PAYABLE_AMOUNT')?></strong></p>
				<h4 class="text-error"><?php echo $formatter->amount($cart_total, true, $currency_id); ?></h4>
				<!-- Payment Gateway Selection				-->
				<div class="form-group">
					<label class="control-label"> <?php echo JText::_('COM_PAYCART_PAYMENT_METHOD'); ?></label>
					<select name="payment_menthod" id="pc-checkout-payment-gateway" class ="form-control" onchange="paycart.cart.gatewaySelection.onChangePaymentgateway()">
						<?php foreach ($payment_gateway as $gateway_id => $gateway_details) : ?>
							<option value=<?php echo $gateway_id; ?> > <?php echo $gateway_details->title; ?> </option>
						<?php endforeach;?>
					</select>
					<span class="pc-error" for="pc-checkout-payment-gateway"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>					
				</div>				
			</div>

			<!--	Payment gateway html display here-->
			<div class="col-sm-8">
				<div class="row-fluid payment-form-html-div ">
					<form class="payment-form-html" id="payment-form-html" method="post">
						<?php echo $payment_gateway_html; ?>
					</form>
				</div>
			</div>			
		</div>
	 </div>	 
</div>

<script>
	(function($){
		$(document).ready(function(){		
			paycart.cart.getPaymentForm($('#pc-checkout-payment-gateway').val());
		});
	})(paycart.jQuery);
</script>
<?php

