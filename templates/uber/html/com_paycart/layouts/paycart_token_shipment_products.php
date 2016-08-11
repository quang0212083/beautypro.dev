<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Layouts
* @contact		support+paycart@readybytes.in
* @author 		rimjhim jain
*/

/**
 * List of Populated Variables
 * $displayData = have all required data
 * $displayData->products = product details
 *
 */
// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

if(isset($displayData->products)){
	$productDetails = $displayData->products;
}
?>
<?php if(!empty($productDetails)):?>
	<table  border="1">
	  <tr>
		<th><?php echo JText::_('COM_PAYCART_PRODUCT');?></th>
		<th><?php echo JText::_('COM_PAYCART_QUANTITY');?></th>
	  </tr>
	  <?php foreach ($productDetails as $key => $value):?>
	  <tr>
		<td><?php echo PaycartProduct::getInstance($value['product_id'])->getTitle();?></td>
		<td><?php echo $value['quantity']?></td>
	  </tr>
	  <?php endforeach;?>
	</table>
<?php endIf;?>
<?php 
