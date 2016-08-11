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
 	<!-- top-buttons -->
 	<div class="row-fluid">
        <h3>
        	<span class="pull-left"> <?php echo JText::_('COM_PAYCART_CART');?> 
	        	<span >
	        		<?php $string = ($productsCount > 1)?"COM_PAYCART_CART_ITEMS":"COM_PAYCART_CART_ITEM";?>
	        		<?php echo '('.$productsCount.' '.JText::_($string).')'; ?>
	        	</span>
        	</span>
        	<span class="pull-right text-error"><strong> <?php echo JText::_('COM_PAYCART_ESTIMATED_TOTAL').' = '.$formatter->amount($cart->getTotal(),true,$currencyId);?></strong></span>
        </h3>
 	</div>
 	
 	 <br>
 	
 	<div class="clearfix">
		<div class="pull-right">	 			
	       <button class="btn btn-large btn-primary" type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"> </i> <?php echo JText::_('COM_PAYCART_PLACE_ORDER');?></button>
	    </div>
	</div>
 	
 	<hr />

	<!--  products listing  --> 
	<?php foreach($products as $item):?>
	<?php $product = PaycartProduct::getInstance($item->getParticularId());?>
		<div class="row-fluid pc-item">
			
			<div class="pull-left pc-grid-4">
				<h4><img class="thumbnail" src="<?php $media = $product->getCoverMedia(); echo @$media['thumbnail']?>" /></h4>
			</div>
			
			<div class="pull-right pc-grid-8">
				 <h4 class="text-info pc-break-word"><?php echo PaycartHtml::link('index.php?option=com_paycart&view=product&task=display&product_id='.$product->getId(), $product->getTitle()); ?></h4>
				 <p class="pc-item-attribute">				 	
					<?php $postionedAttributes = (array)$product->getPositionedAttributes();?>
					<?php $attributes = $product->getAttributes();?>
					 <div class="pc-product-overview">
			 			<?php if(isset($postionedAttributes['product-overview']) && !empty($postionedAttributes['product-overview'])) : ?>
							<ul class="unstyled muted">
			 				<?php foreach($postionedAttributes['product-overview'] as $attributeId) : ?>
			 					<?php if(isset($attributes[$attributeId]) && !empty($attributes[$attributeId])) :?>
			 						<?php $instance = PaycartProductAttribute::getInstance($attributeId);?>
						 			<li><small><?php echo $instance->getTitle();?>&nbsp;:&nbsp;<?php $options = $instance->getOptions(); echo $options[$attributes[$attributeId]]->title;?></small></li>
								<?php endif?>
			 				<?php endforeach;?>
			 				</ul>
			 			<?php endif;?>
			 		</div>
			 	
					<span><?php echo JText::_("COM_PAYCART_UNIT_PRICE")?> :</span>
					
					<span><?php echo $formatter->amount($item->getUnitPrice(),true,$currencyId); ?></span><br />

					<?php if($item->getDiscount() != 0):?>
			 			<span>- <?php echo JText::_("COM_PAYCART_DISCOUNT")?> </span><span> : <?php echo $formatter->amount(-($item->getDiscount()),true,$currencyId);?></span><br />
			 		<?php endif;?> 
			 		<?php if($item->getTax() > 0):?>
			 			<span>+ <?php echo JText::_("COM_PAYCART_TAX")?></span><span> : <?php echo $formatter->amount($item->getTax(),true,$currencyId);?></span><br />
				 	<?php endif;?>				 	
				 </p>
				 
				<div class="clearfix">
					<div class="pull-left pc-grid-6">
					 	 <label><big><?php echo Jtext::_("COM_PAYCART_QUANTITY")?></big></label>
				 		 <span>
				 		 	<!-- when enter key is presssed then also update quantity -->
 				 		 	<input class="input-mini pc-cart-quantity-<?php echo $product->getId()?>" type="text" min="1" value="<?php echo $item->getQuantity(); ?>" 
				 		 	       onkeydown="if (event.keyCode == 13) return paycart.cart.product.updateQuantity(<?php echo $product->getId();?>); "/>&nbsp;
				 		 	<a href="javascript:void(0);" onClick="paycart.cart.product.updateQuantity(<?php echo $product->getId();?>)"><i class="fa fa-refresh"></i></a>
				 		 </span>
				 		 <div class="pc-grid-12 text-error pc-cart-quantity-error-<?php echo $product->getId()?>"></div>
					</div>
					
					<div class="pull-right text-right">
					 	 <h3>
						 	 <span><?php echo JText::_('COM_PAYCART_PRICE')." = "?></span>
					 		 <span><?php echo $formatter->amount($item->getTotal(),true,$currencyId); ?></span>
					 	</h3>
					</div>
			 	</div> 
			 	
			 	<div class="clearfix">
				 	 <a class="pull-right muted" href="javascript:void(0)" onClick="paycart.cart.product.remove(<?php echo $product->getId();?>)"><i class="fa fa-trash-o fa-lg">&nbsp;</i></a>
			 	</div>
			 	
			</div>
			
		</div>
		<hr />
	<?php endforeach;?>
	
	<?php if(isset($promotionParticular->discount) && !empty($promotionParticular->discount)):?>
		<h5 class="text-right">
			<?php echo JText::_("COM_PAYCART_DISCOUNT")." = ";?> <?php echo $formatter->amount($promotionParticular->discount,true,$currencyId); ?>
		</h5>
	<?php endif;?>
	
	<?php if(isset($dutiesParticular->tax) && !empty($dutiesParticular->tax)):?>
		<h5 class="text-right">
			<?php echo JText::_("COM_PAYCART_TAX")." = ";?><?php echo $formatter->amount($dutiesParticular->tax,true,$currencyId); ?>
		</h5>
	<?php endif;?>
	
	<?php $shipping = 0;?>
	<?php if(!empty($shippingParticulars)):?>
		<?php foreach ($shippingParticulars as $particular):?>
			<?php $shipping += $particular->getTotal();?>
		<?php endforeach;?>
		<?php if(!empty($shipping)):?>
			<h5 class="text-right">
				<?php echo JText::_("COM_PAYCART_CART_DELIVERY_CHARGES")." = ";?><?php echo $formatter->amount($shipping,true,$currencyId); ?>
			</h5>
		<?php endif;?>
	<?php endif;?>
		
	<h3 class="text-right">
		<span class="text-error"><?php echo JText::_('COM_PAYCART_ESTIMATED_TOTAL')." = ";?><strong><?php echo $formatter->amount($cart->getTotal(),true,$currencyId); ?></strong></span>
	</h3>
	 
	 <!--  footer buttons --> 
	 <div class="clearfix">
		<div class="pull-right">	 			
	       <button class="btn btn-large btn-primary" type="button" onclick="rb.url.redirect('<?php echo PaycartRoute::_('index.php?option=com_paycart&view=cart&task=checkout'); ?>'); return false;"><i class="fa fa-shopping-cart"> </i> <?php echo JText::_('COM_PAYCART_PLACE_ORDER');?></button>
	    </div>
	</div>
</div>
<?php else:?>
<div id="pc-cart-products">
 	<div class="row-fluid row-fluid text-center">
 		<h3 class="muted"><?php echo JText::_('COM_PAYCART_CART_EMPTY')?></h3>
 		<div class="row-fluid">
 			<button type="button" class="btn btn-large btn-primary" onclick="rb.url.redirect('<?php echo paycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display');?>'); return false;"> <i class="fa fa-chevron-left"></i> &nbsp; <?php echo JText::_("COM_PAYCART_CONTINUE_SHOPPING");?></button>
 		</div>
 	</div>
</div>

<?php endif;?>
<?php 
