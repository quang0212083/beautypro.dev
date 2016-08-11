<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<?php 
	$selectedOption  = $displayData->selected;
	$options   		 = $displayData->options;
	$optionDetails   = $displayData->optionDetails;
	$attributeId	 = $displayData->attribute->productattribute_id; 
?>
<div>
	<select id="pc-attr-<?php echo $attributeId?>" name="attributes[<?php echo $attributeId?>]" onChange="paycart.product.selector.onChange(this)">
		<?php //build option html
		foreach ($options as $optionId):?>
			<?php $selected 		   = ''; ?>
			<?php if(!empty($selectedOption) && $selectedOption == $optionId):?>
				<?php $selected            = 'selected="selected"';?>
			<?php endif;?>
			<option value="<?php echo $optionId?>" <?php echo $selected?>><?php echo $optionDetails[$optionId]['title']?></option>
		<?php endforeach;?>	
	</select>
</div>
<?php 