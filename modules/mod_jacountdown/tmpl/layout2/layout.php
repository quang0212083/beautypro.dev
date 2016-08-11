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
<div class="clock">
	<canvas id="canvas_seconds" width="518px" height="518px"></canvas>
</div>

<div class="timer">
	<p><span class="days">0</span> <?php echo JText::_('JACD_DAYS') ?> <span class="hrs">0</span> <?php  echo JText::_('JACD_HOURS')?> <span class="mins">0</span> <?php echo JText::_('JACD_MINUTES_2') ?><span class="secs">0</span> <?php echo JText::_('JACD_SECONDS') ?></p>
</div>  