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
	<select id="pc-attr-<?php echo $attributeId?>" name="attributes[<?php echo $attributeId?>]" onChange = "paycart.product.selector.onChange(this)">
		<?php $selectedOptionTitle = '';?>
		<?php $selectedHashCode    = '';?>
		<?php //build option html
		foreach ($options as $colorId):?>
			<?php $selected 		   = ''; ?>
			<?php if(!empty($selectedOption) && $selectedOption == $colorId):?>
				<?php $selected            = 'selected="selected"';?>
				<?php $selectedHashCode    = $optionDetails[$colorId]['hash_code'];?>
				<?php $selectedOptionTitle = $optionDetails[$colorId]['title'];?>
			<?php endif;?>
			<option value="<?php echo $colorId?>" <?php echo $selected?>><?php echo $optionDetails[$colorId]['title']?></option>
		<?php endforeach;?>	
	</select>
	<span class="pc-attribute-color" style="background-color:<?php echo $selectedHashCode?>" title="<?php echo $selectedOptionTitle?>"></span>
</div>