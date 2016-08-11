<?php

/*
 * @version		$Id: default.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div style="float:left; width:52%; padding-left:5px;">
	<?php echo $this->loadTemplate('left'); ?>
  	<div class="clr"></div>
</div>
<div class="dashboard_right" style="float:right; width:46%; color:#333333;">
	<?php echo $this->loadTemplate('right'); ?>
</div>
<div class="clr"></div>