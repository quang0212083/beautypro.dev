<?php

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div id="avs">
<?php if(version_compare(JVERSION, '3.0', 'ge')) {  
	echo $this->loadTemplate('left');
} else { ?>
	<div style="float:left; width:55%"> <?php echo $this->loadTemplate('left'); ?>
  		<div class="clear"></div>
	</div>
	<div style="float:right; width:44%;"> <?php echo $this->loadTemplate('right'); ?> </div>
	<div class="clear"></div>
<?php } ?>
</div>