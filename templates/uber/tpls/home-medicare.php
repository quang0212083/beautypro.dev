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
  <?php $this->addCss('layouts/medicare') ?>
</head>

<body class="medicare">

<div class="t3-wrapper"> <!-- Need this wrapper for off-canvas menu. Remove if you don't use of-canvas -->
<?php $this->loadBlock('topbar') ?>
  <?php $this->loadBlock('header') ?>

  <?php $this->loadBlock('home') ?>
  
  <?php $this->loadBlock('mainbody') ?>

  <?php if ($this->countModules('home-bottom')) : ?>
    <!-- HOME POSITION -->
    <div class="wrap sections-wrap <?php $this->_c('home-bottom') ?>">
      <jdoc:include type="modules" name="<?php $this->_p('home-bottom') ?>" style="T3section" />
    </div>
  <?php endif ?>
  <?php $this->loadBlock('footer') ?>

</div>

</body>

</html>