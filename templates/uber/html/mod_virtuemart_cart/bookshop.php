<?php // no direct access
defined('_JEXEC') or die('Restricted access');

//dump ($cart,'mod cart');
// Ajax is displayed in vm_cart_products
// ALL THE DISPLAY IS Done by Ajax using "hiddencontainer" ?>

<!-- Virtuemart 2 Ajax Card -->
<div class="vmCartModule <?php echo $params->get('moduleclass_sfx'); ?>" id="vmCartModule">

<div class="total_products btn btn-primary">
  <?php if($data->totalProduct == 0) : 
	   echo 'Cart empty';
  elseif ($data->totalProduct == 1) : 
    echo $data->totalProduct.' product';
  else:
    echo $data->totalProduct.' products';
  endif; ?>
</div>

<div id="page-mask" class="hide">
</div>

<div class="vmCartList hide">
	<div class="cart-head">
		<h3>Your Bag</h3>
		<a id="hideCart" href="#"><i class="fa fa-close"></i></a>
	</div>

	<?php if($data->totalProduct == 0) : ?>
	<div class="alert alert-success alert-cart-empty">
		Cart empty
	</div>
	<?php endif; ?>

	<?php if ($show_product_list) {	?>
		<div id="hiddencontainer" style=" display: none; ">
			<div class="vmcontainer">
				<div class="product_row">
					<span class="product_name"></span>
                    <?php if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) { ?>
                        <div class="subtotal_with_tax" style="float: right;"></div>
                    <?php } ?>
                    <span class="quantity"></span>
				    <div class="customProductData"></div><br>
				</div>
			</div>
		</div>

		<div class="vm_cart_products">
			<?php
				foreach ($data->products as $product) {
					?><div class="vmcontainer"><div class="product_row">
						<span class="product_name"><?php echo  $product['product_name'] ?></span>
					<?php if ($show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) { ?>
					  <div class="subtotal_with_tax"><?php echo $product['subtotal_with_tax'] ?></div>
					<?php } ?>

					<span class="quantity"><?php echo $product['thumbnail'] ?><?php echo  $product['quantity'] ?></span>

					<?php if ( !empty($product['customProductData']) ) { ?>
						<div class="customProductData"><?php echo $product['customProductData'] ?></div>
					<?php } ?>
				</div></div>
			<?php }
			?>
		</div>
	<?php } ?>	

	<?php if ($data->totalProduct and $show_price and $currencyDisplay->_priceConfig['salesPrice'][0]) { ?>
	<div class="total">
	<?php echo $data->billTotal; ?>
	</div>
	<?php } ?>

	<div class="show_cart">
		<?php if ($data->totalProduct) echo  $data->cart_show; ?>
	</div>

	<div style="clear:both;"></div>

	<div class="payments_signin_button" ></div>
	<noscript><?php echo vmText::_('MOD_VIRTUEMART_CART_AJAX_CART_PLZ_JAVASCRIPT') ?></noscript>
</div>

</div>

<script type="text/javascript">
if (typeof Virtuemart === "undefined")
	Virtuemart = {};

jQuery(function($) {
	Virtuemart.customUpdateVirtueMartCartModuleJA = function(el, options){
		var base 	= this;
		var $this	= jQuery(this);
		base.$el 	= jQuery(".vmCartModule");

		base.options 	= jQuery.extend({}, Virtuemart.customUpdateVirtueMartCartModuleJA.defaults, options);
			
		base.init = function(){
			jQuery.ajaxSetup({ cache: false })
			jQuery.getJSON(window.vmSiteurl + "index.php?option=com_virtuemart&nosef=1&view=cart&task=viewJS&format=json" + window.vmLang,
				function (datas, textStatus) {
					base.$el.each(function( index ,  module ) {
						if (datas.totalProduct > 0) {
							jQuery(module).find(".vm_cart_products").html("");
							jQuery.each(datas.products, function (key, val) {
								jQuery(module).find("#hiddencontainer .vmcontainer .product_row").clone().appendTo( jQuery(module).find(".vm_cart_products") );
								jQuery.each(val, function (key, val) {
									jQuery(module).find(".vm_cart_products ." + key).last().html(val);
								});
							});
						}
						if (jQuery('.alert-cart-empty').size()) {
							jQuery('.alert-cart-empty').remove();
						}
						jQuery(module).find(".show_cart").html(datas.cart_show);
						jQuery(module).find(".total_products").html(datas.totalProductTxt);
						jQuery(module).find(".total").html(datas.billTotal);
					});
				}
			);			
		};
		base.init();
	};
	// Definition Of Defaults
	Virtuemart.customUpdateVirtueMartCartModuleJA.defaults = {
		name1: 'value1'
	};

});
jQuery(document).ready(function( $ ) {
	jQuery(document).off("updateVirtueMartCartModule","body",Virtuemart.customUpdateVirtueMartCartModule);
	jQuery(document).on("updateVirtueMartCartModule","body",Virtuemart.customUpdateVirtueMartCartModuleJA);
});
</script>