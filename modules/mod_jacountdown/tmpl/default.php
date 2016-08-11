<?php
/**
 * ------------------------------------------------------------------------
 * JA CountDown Module for Joomla 2.5 & 3.4
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="ja-countdown <?php echo $jalayout;?> <?php echo $params->get('moduleclass_sfx');?>"<?php echo $stylesheets;?>>

<?php if($custom_titles) : ?>
<h1><?php echo $custom_titles;?></h1>
<?php endif;?>

<?php if($custom_message): ?>
<?php echo $custom_message;?>
<?php endif;?>

<?php 
	require JModuleHelper::getLayoutPath('mod_jacountdown/', $jalayout.'/layout');
?>
</div>