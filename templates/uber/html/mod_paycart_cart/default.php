<?php
/**
 * @package     Paycart.Site
 * @subpackage  mod_paycart_cart
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * 
 * @author mMnaishTrivedi
 */

defined('_JEXEC') or die;


// load bootsrap, font-awesome
$config = PaycartFactory::getConfig();
$load = array('jquery', 'rb', 'font-awesome');
if($config->get('template_load_bootstrap', false)){
	$load[] = 'bootstrap';
}
Rb_HelperTemplate::loadMedia($load);
Rb_HelperTemplate::loadSetupEnv();
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/paycart.js');

$product_count =0 ;
?>
<div id="pc-mod-cart-<?php echo $module->id;?>" class="clearfix pc-mod-cart pc-mod-cart<?php echo $class_sfx;?>">
	<a href='<?php echo JRoute::_('index.php?option=com_paycart&view=cart&task=display');?>' class="mod-cart-btn">
		<span class=" pc-mod-cart-badge pc-mod-cart-badge-product-counter ">
			<?php echo $product_count; ?>
		</span>
	</a>
	<div class=" mod-cart-list-item">
		
	</div>
</div>		
		
<script>
(function($){
	var modPaycartCart<?php echo $module->id;?> = {};
	modPaycartCart<?php echo $module->id;?>.update = {
		onSuccess : function(response_data){
			// hide all stuff
			$('#pc-mod-cart-<?php echo $module->id;?> .pc-mod-cart-text').hide();
			// take action 
			$('#pc-mod-cart-<?php echo $module->id;?> .pc-mod-cart-badge-product-counter').html(response_data['products_count']);
		},
		onError : function(response_data){
			console.log ( {" response contain error :  " : response_data } );
		},
		do : function(event){
			var request 	= [];
			request['success_callback']	=	modPaycartCart<?php echo $module->id;?>.update.onSuccess;
  			request['url'] = 'index.php?option=com_paycart&view=cart&task=getProductCount&format=json';
			paycart.request(request);
		},
	};
			 
	// bind event 
	$(document).on( "onPaycartCartUpdateproduct", modPaycartCart<?php echo $module->id;?>.update.do);
			
	// on Document ready 
	$(document).ready(function(){
		modPaycartCart<?php echo $module->id;?>.update.do();
	});
			 	
})(paycart.jQuery);
</script>
			
		