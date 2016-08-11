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
defined('_JEXEC') or die();

/**
 * List of Populated Variables
 * $displayData : Array of stdclass objects related to products with all their properties, some of those are :-
 * 
 * $displayData->product_id->price
 * $displayData->product_id->title
 * $displayData->product_id->inStock
 * $displayData->product_id->media : It is an array containing data of coverImage of product
 * 									 	 ( array ('optimized'  => LOCATION OF OPTIMIZED IMAGE,
 * 												   'original'  => LOCATION OF ORIGINAL IMAGE,
 * 												   'thumbnail' => LOCATION OF THUMBNAIL IMAGE,
 * 												   'squared'   => LOCATION OF SQUARED IMAGE, 
 * 												  )
 * 										  )
 * 
 */
?>

<?php foreach($displayData->products as $product) : ?>
	<?php $inStock   = $product->inStock;?>  
	<?php $class     = !$inStock?'pc-product-stockout':''?>  
	<div class="pc-product-outer <?php echo isset($displayData->pagination_start)? 'pc-next-'.$displayData->pagination_start:''?>">
		<div class='pc-product thumbnail'>
			<?php $media = $product->media;?>      
			<?php $url   = PaycartRoute::_('index.php?option=com_paycart&view=product&task=display&product_id='.$product->product_id);?>
			<a class="pc-clickable" href="<?php echo $url;?>">
				<div class="pc-product-content">
					<?php if(!empty($class)):?>
						<strong><span class="<?php echo $class;?> text-center"><?php echo strtoupper(JText::_("COM_PAYCART_PRODUCT_IS_OUT_OF_STOCK"));?></span></strong>
					<?php endif;?>  
					<img class="<?php echo !$inStock?'pc-product-stockout-image':'';?>" src="<?php echo isset($media['optimized'])?$media['optimized']:'';?>">
					<p class="pc-product-title pc-break-word"><?php echo $product->title;?></p>
				</div>
			</a>
			<h4><span class="amount"><?php echo $product->price?></span></h4>
		</div>
	</div>
<?php endforeach;?>

