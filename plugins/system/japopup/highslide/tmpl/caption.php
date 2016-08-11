<?php
/**
 * ------------------------------------------------------------------------
 * JA Popup Plugin for Joomla 25 & 34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div class="highslide-caption" id="<?php echo $arrData['captionID'];?>">
	<a href="#" onclick="return hs.previous(this)" class="control" style="float:left; display: block">
	<strong>Previous</strong><br/>
	<small style="font-weight: normal; text-transform: none">Left arrow key</small>
	</a>
	<a href="#" onclick="return hs.next(this)" class="control"
			style="float:left; display: block; text-align: right; margin-left: 50px">
		<strong>Next</strong><br/>
		<small style="font-weight: normal; text-transform: none">Right arrow key</small>
	</a>&nbsp;&nbsp;&nbsp;
	<a href="#" onclick="return hs.close(this)" class="control"><strong>Close</strong></a>
	<!-- <a href="#" onclick="return false" class="highslide-move control"><strong>Move</strong></a> -->
	<div style="clear:both"></div>
</div>