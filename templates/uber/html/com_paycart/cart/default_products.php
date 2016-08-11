<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Available variables 
 * 
 * @param $products => array of product particulars
 * @param $cart => object of PaycartCart 
 */

$currencyId = $cart->getCurrency();
?>
<?php if(!empty($products)):?>
<div id="pc-cart-products">
<h3 class="page-title text-center">
	<?php echo JText::_('COM_PAYCART_CART');?> 
    <span class="text-muted">
    	<?php $string = ($productsCount > 1)?"COM_PAYCART_CART_ITEMS":"COM_PAYCART_CART_ITEM";?>
    	<?php echo '('.$productsCount.' '.JText::_($string).')'; ?>
	</span>      
	</h3>
 	<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th><?php echo JText::_("COM_PAYCART_UNIT_PRICE")?></th>
				<th><?php echo Jtext::_("COM_PAYCART_QUANTITY")?></big></th>
				<th><span><?php echo JText::_('COM_PAYCART_PRICE')." "?></span></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	<!--  products listing  --> 
	<?php foreach($products as $item):?>
	<?php $product = PaycartProduct::getInstance($item->getParticularId());?>
		<tr>
			<td>
				<img src="<?php $media = $product->getCoverMedia(); echo @$media['thumbnail']?>" />
			</td>
			<td>
				 <p><strong><?php echo PaycartHtml::link('index.php?option=com_paycart&view=product&task=display&product_id='.$product->getId(), $product->getTitle()); ?></strong></p>
				 <p class="pc-item-attribute">				 	
					<?php $postionedAttributes = (array)$product->getPositionedAttributes();?>
					<?php $attributes = $product->getAttributes();?>
					 <div class="pc-product-overview">
			 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>
							<ul class="list-unstyled text-muted">
			 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
			 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
			 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
						 			<li><small><?php echo $instance->getTitle();?>&nbsp;:&nbsp;<?php $options = $instance->getOptions(); echo $options[$attributes[$attributeId]]->title;?></small></li>
								<?php endif?>
			 				<?php endforeach;?>
			 				</ul>
			 			<?php endif;?>
			 		</div>
			 </td>
				<td>	
					<span><?php echo $formatter->amount($item->getUnitPrice(),true,$currencyId); ?></span>

					<?php if($item->getDiscount() != 0):?>
			 			<span>- <?php echo JText::_("COM_PAYCART_DISCOUNT")?> </span><span> : <?php echo $formatter->amount(-($item->getDiscount()),true,$currencyId);?></span><br />
			 		<?php endif;?> 
			 		<?php if($item->getTax() > 0):?>
			 			<span>+ <?php echo JText::_("COM_PAYCART_TAX")?></span><span> : <?php echo $formatter->amount($item->getTax(),true,$currencyId);?></span><br />
				 	<?php endif;?>				 	
				 </p>
			</td>
			<td>
				<div class="form-inline">
					<div class="input-group">				
					<input type="text" class="form-control pc-cart-quantity-<?php echo $product->getId()?>" min="1" value="<?php echo $item->getQuantity(); ?>" 
			 		 	       onkeydown="if (event.keyCode == 13) return paycart.cart.product.updateQuantity(<?php echo $product->getId();?>); ">
				    <div class="input-group-addon"><a href="javascript:void(0);" onClick="paycart.cart.product.updateQuantity(<?php echo $product->getId();?>)"><i class="fa fa-refresh"></i></a></div>
				</div>
				</div>	
				
			</td>
			<td>
				<div class="clearfix">
					<div class="pc-cart-quantity-error-<?php echo $product->getId()?>"></div>
					<span><?php echo $formatter->amount($item->getTotal(),true,$currencyId); ?></span>
			 	</div> 
			</td>
			<td>
				<a class="pull-right text-muted" href="javascript:void(0)" onClick="paycart.cart.product.remove(<?php echo $product->getId();?>)"><i class="fa fa-trash-o fa-lg">&nbsp;</i></a>			
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
	</table>
	</div>
	<hr>

	<?php if(isset($promotionParticular->discount) && !empty($promotionParticular->discount)):?>
		<p class="text-center"><strong>
			<?php echo JText::_("COM_PAYCART_DISCOUNT")." = ";?> <?php echo $formatter->amount($promotionParticular->discount,true,$currencyId); ?>
		</strong></p>
	<?php endif;?>
	
	<?php if(isset($dutiesParticular->tax) && !empty($dutiesParticular->tax)):?>
		<p class="text-center"><strong>
			<?php echo JText::_("COM_PAYCART_TAX")." = ";?><?php echo $formatter->amount($dutiesParticular->tax,true,$currencyId); ?>
		</strong></p>
	<?php endif;?>
	
	<?php $shipping = 0;?>
	<?php if(!empty($shippingParticulars)):?>
		<?php foreach ($shippingParticulars as $particular):?>
			<?php $shipping += $particular->getTotal();?>
		<?php endforeach;?>
		<?php if(!empty($shipping)):?>
			<p class="text-center">
				<?php echo JText::_("COM_PAYCART_CART_DELIVERY_CHARGES")." = ";?><?php echo $formatter->amount($shipping,true,$currencyId); ?>
			</p>
		<?php endif;?>
	<?php endif;?>
		
	<h5 class="text-center">
		<span><?php echo JText::_('COM_PAYCART_ESTIMATED_TOTAL')." = ";?><strong><?php echo $formatter->amount($cart->getTotal(),true,$currencyId); ?></strong></span>
	</h5>
	 <!--  footer buttons --> 
	 	<div class="text-center">
			<button class="btn btn-lg btn-primary " type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"> </i> <?php echo JText::_('COM_PAYCART_PLACE_ORDER');?></button>
		</div>
	</div>

<?php else:?>
<div id="pc-cart-products">
 	<div class="text-center">
 		<h3 class="text-muted"><?php echo JText::_('COM_PAYCART_CART_EMPTY')?></h3>
 		<div class="">
 			<button type="button" class="btn btn-lg btn-primary" onclick="rb.url.redirect('<?php echo paycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>'); return false;"> <i class="fa fa-chevron-left"></i> &nbsp; <?php echo JText::_("COM_PAYCART_CONTINUE_SHOPPING");?></button>
 		</div>
 	</div>
</div>

<?php endif;?>
