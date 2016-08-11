<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

echo $this->loadTemplate('css');
echo $this->loadTemplate('js');
?>
<div class='pc-account-wrapper row-fluid clearfix'> 
	<div id="pc-account" class ='pc-account span12 clearfix' >
	
		<!-- START HEADER -->
		<div class="pc-account-header hidden-phone">
			<?php echo $this->loadTemplate('header');?>
		</div>
		<!-- START HEADER -->
		
		
		<!-- START BODY -->
		<div class="row-fluid">
			<div class="span3 hidden-phone">
				<?php echo $this->loadTemplate('sidebar');?>
			</div>
			<div class="span9">
				<form class="form-horizontal pc-form-validate" action="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=update&entity=info');?>" method="post" noValidate>
					<fieldset>
						<legend><?php echo JText::_('COM_PAYCART_ACCOUNT_INFORMATION');?></legend>
						<div class="control-group">
							<div class="control-label">
								<label class="" id="jform_spacer-lbl">
									<?php echo JText::_('COM_PAYCART_EMAIL');?>
								</label>
							</div>
							<div class="controls">
								<input type="email" disabled="" value="<?php echo $buyer->getEmail();?>">
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_form_realname">
									<?php echo JText::_('COM_PAYCART_FULL_NAME');?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="paycart_account_form[realname]" id="paycart_account_form_realname" value="<?php echo $buyer->getRealName();?>" required="">
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_form_default_phone">
									<?php echo JText::_('COM_PAYCART_PHONE');?>
								</label>
							</div>
							<div class="controls">
								<input type="text" name="paycart_account_form[default_phone]" id="paycart_account_form_default_phone" value="<?php echo $buyer->getDefaultPhone();?>" required="">
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">								
							</div>
							<div class="controls">
								<button class="btn btn-primary" type="submit"><?php echo JText::_('COM_PAYCART_SAVE_CHANGES');?></button>
							</div>
						</div>
					</fieldset>
				</form>
				
				<form class="form-horizontal pc-form-validate" action="<?php echo JRoute::_('index.php?option=com_paycart&view=account&task=update&entity=password');?>" method="post" noValidate>
					<fieldset>
						<legend><?php echo JText::_('COM_PAYCART_ACCOUNT_CHANGE_PASSWORD');?></legend>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_form_current_password">
									<?php echo JText::_('COM_PAYCART_ACCOUNT_CURRENT_PASSWORD');?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>
							<div class="controls">
								<input type="password" class="validate-password" name="paycart_account_form[current_password]" id="paycart_account_form_current_password" required="">								
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_form_new_password">
									<?php echo JText::_('COM_PAYCART_ACCOUNT_NEW_PASSWORD');?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>
							<div class="controls">
								<input type="password" class="validate-password" name="paycart_account_form[new_password]" id="paycart_account_form_new_password" required="">
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<label class="" for="paycart_account_form_retype_new_password">
									<?php echo JText::_('COM_PAYCART_ACCOUNT_RETYPE_NEW_PASSWORD');?>
									<span class="star">&nbsp;*</span>
								</label>
							</div>
							<div class="controls">
								<input type="password" class="validate-password" name="paycart_account_form[retype_new_password]" id="paycart_account_form_retype_new_password" required="">
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">								
							</div>
							<div class="controls">
								<button class="btn btn-primary" type="submit"><?php echo JText::_('COM_PAYCART_SAVE_CHANGES');?></button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>      	
	</div>
</div>