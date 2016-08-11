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
<a rel="<?php echo $arrData['rel'];?>" class="thickbox" title="<?php echo $arrData['title'];?>" href="<?php echo $arrData['href'];?>" ><?php echo $arrData['content'];?></a>
<div style="display:none" id="<?php echo $arrData['YouTubeID'];?>">
<center><object type="application/x-shockwave-flash"
	data="<?php echo $arrData['YouTubeLink']; ?>"
	width="<?php echo $arrData['frameWidth']; ?>" height="<?php echo $arrData['frameHeight']; ?>">
	<param name="movie" value="<?php echo $arrData['YouTubeLink']; ?>" />
	<param name="allowFullScreen" value="true" />
	<param name="quality" value="high"/>
</object></center></div>