<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );?>	

<div class="pc-checkout-state row-fluid clearfix">
	<?php echo $this->loadTemplate('steps');?>
</div>
		
<div class="pc-checkout-login row-fluid">
	
	<h3><?php echo JText::_('COM_PAYCART_LOGIN');?></h3>

	<fieldset class="radio input-lg pc-whitespace">
		<input type="radio" id="paycart_cart_login_emailcheckout_1" name="paycart_cart_login[emailcheckout]"  value="1" checked="checked">
		<label for="paycart_cart_login_emailcheckout_1" class="radio"><?php echo JText::_('COM_PAYCART_CART_CONTINUE_AS_GUEST');?></label>
			
		<input type="radio" id="paycart_cart_login_emailcheckout_0"  name="paycart_cart_login[emailcheckout]" value="0">
		<label for="paycart_cart_login_emailcheckout_0" class="radio"><?php echo JText::_('COM_PAYCART_CART_EXISTING_CUSTOMER');?></label>			
	</fieldset>
	<br/>
	<fieldset>
		<div>
			<span class="pc-error" for="paycart_cart_login" id="paycart_cart_login">&nbsp;</span> <?php //@PCTODO : improve it?>
		</div>
		<div class="control-group" data-pc-selector="pc-emailcheckout">
			<div class="control-label">
	 			<label id="paycart_cart_login_email-lbl" for="paycart_cart_login_email" class="required" aria-invalid="false">
	 				<?php echo JText::_('COM_PAYCART_EMAIL');?>
	 			</label>
	 		</div>
	 		<div class="controls">
				<input type="email" name="paycart_cart_login[email]" id="paycart_cart_login_email" class="input-block-level validate-email" required="" value = "<?php echo @$buyer->email; ?>" data-pc-selector="paycart_cart_login_email"/>
				<span class="pc-error" for="paycart_cart_login_email"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_INVALID_EMAIL_ADDRESS');?></span>
			</div>			
		 </div>
		<div class="control-group" data-pc-selector="pc-logincheckout">
			<div class="control-label">
	 			<label id="paycart_cart_login_username-lbl" for="paycart_cart_login_username" class="required" aria-invalid="false">
	 				<?php echo JText::_('COM_PAYCART_USERNAME').' / '.JText::_('COM_PAYCART_EMAIL');?>
	 			</label>
	 		</div>
	 		<div class="controls">
				<input type="email" name="paycart_cart_login[username]" id="paycart_cart_login_username" class="input-block-level" required="" value = "<?php echo @$buyer->email; ?>" data-pc-selector="paycart_cart_login_username"/>
				<span class="pc-error" for="paycart_cart_login_username"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_REQUIRED');?></span>
			</div>
		 </div>
		 <div class="control-group" data-pc-selector="pc-logincheckout">
		 	<div class="control-label">
	 			<label id="paycart_cart_login_password-lbl" for="paycart_cart_login_password" class="required" aria-invalid="false"><?php echo JText::_('COM_PAYCART_PASSWORD');?></label>
	 		</div>				
			<div class="controls">
				<input type="password" name="paycart_cart_login[password]" id="paycart_cart_login_password" required class="input-block-level">
				<span class="pc-error" for="paycart_cart_login_password"><?php echo JText::_('COM_PAYCART_VALIDATION_ERROR_PASSWORD_REQUIRED');?></span>
			</div>
		</div>
 	</fieldset>
 	
	<button type="button" onClick="paycart.cart.login.do();" class="pc-whitespace btn btn-block btn-large btn-primary">
			<?php echo JText::_('COM_PAYCART_CONTINUE');?> <i class="fa fa-angle-double-right"></i> 
	</button>
	
	<input	type="hidden"	name='step_name' value='login' />
 </div>	 
 
<script>
			
	(function($){
		$(document).ready(function(){
			paycart.cart.login.init();
		});
	})(paycart.jQuery);
	
</script>
<?php

