<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYINVOICE
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

/**
 * $response_object : 
 * 
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

	<div class="row-fluid">
	
		<div class="span12">
			<?php echo $response_object->html;?>
		</div>
		
		<button type="button" id="paycart-invoice-paynow" class="btn btn-large btn-block btn-primary paycart-invoice-paynow" onClick="return paycart.cart.order();" >
			<?php echo JText::_('COM_PAYCART_CART_PAY_NOW');?>
		</button>
		
	</div>
