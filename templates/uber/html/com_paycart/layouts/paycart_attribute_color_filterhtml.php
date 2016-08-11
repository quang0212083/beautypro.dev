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
	$selectedOptions = $displayData->selected;
	$filterOptions   = $displayData->filterOptions;
	$optionDetails   = $displayData->optionDetails;
	$attributeId	 = $displayData->attribute->productattribute_id; 
?>

<?php foreach ($filterOptions as $colorId=>$color):?>
	<?php $selected = ''; ?>
	<?php if(!empty($selectedOptions) && in_array($colorId, $selectedOptions)):?>
			<?php $selected = "checked='checked'"; ?>
	<?php endif;?>
	<?php $disabled = ($color['disabled'])?'disabled':'';?>
	<div>
		<label class="checkbox help-block">
			<input data-pc-result="filter" name="filters[attribute][<?php echo $attributeId?>][<?php echo $colorId?>]" 
				   value="<?php echo $colorId?>" type="checkbox" data-attribute-id="<?php echo $attributeId?>"
			       <?php echo $selected;?>  <?php echo $disabled;?> >
			       <?php echo $optionDetails[$colorId]['title'].' ('.$color['productCount'] .')' ?>
			<span class="pull-right pc-filter-color" style="background-color: <?php echo $optionDetails[$colorId]['hash_code']?>"></span>
		</label>
	</div>
	<?php endforeach;?>
<?php 