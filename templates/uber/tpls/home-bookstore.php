<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */


defined('_JEXEC') or die;
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>"
	  class='<jdoc:include type="pageclass" />'>

<head>
	<jdoc:include type="head" />
	<?php $this->loadBlock('head') ?>
  <?php $this->addCss('layouts/bookstore') ?>
</head>

<body class="home-bookstore">

<div class="t3-wrapper"> <!-- Need this wrapper for off-canvas menu. Remove if you don't use of-canvas -->

  <?php $this->loadBlock('header') ?>

  <?php $this->loadBlock('home') ?>
  
  <div id="t3-mainbody" class="t3-mainbody">

    <!-- MAIN CONTENT -->
    
    <?php if($this->hasMessage()) : ?>
    <div class="container">
  	 <jdoc:include type="message" />
  	</div>
  	<?php endif ?>

    <?php $this->loadBlock('content') ?>
  </div>

  <?php $this->loadBlock('footer') ?>

</div>

</body>

</html>

<script>
(function($) {
  $(document).ready(function() {
      $(".uber-footer .total_products").click(function(e){
        $(".vmCartList").removeClass('hide').addClass('show');
        $("#page-mask").removeClass('hide').addClass('show');
      });

      $("#hideCart").click(function(e) {
        $(".vmCartList").removeClass('show').addClass('hide');
        $("#page-mask").removeClass('show').addClass('hide');
      });

      $("#page-mask").click(function(e) {
        $(".vmCartList").removeClass('show').addClass('hide');
        $("#page-mask").removeClass('show').addClass('hide');
      });
  });

})(jQuery);
</script>