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

//load assests that are required before loading related templates
Rb_Html::stylesheet(PAYCART_PATH_CORE_MEDIA.'/slider.css');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/bootstrap-slider.js');
Rb_Html::script(PAYCART_PATH_CORE_MEDIA.'/salvattore.js');

echo $this->loadTemplate('filter_js');
echo $this->loadTemplate('filter_css');
?>
<script type="text/javascript">
(function($){	
	
	$(document).ready(function(){
		paycart.product.filter.init('<?php echo isset($searchWord)?urlencode($searchWord):'';?>','<?php echo (isset($filters) && !empty($filters))?json_encode($filters):'';?>');
	});
})(paycart.jQuery);
</script>

<div id="pc-product-search-content">
	
<!-- ================================
	       Here comes the html 
	 ================================ -->
	 	 
</div>

 <?php echo  Rb_HelperTemplate::renderLayout('paycart_spinner'); 