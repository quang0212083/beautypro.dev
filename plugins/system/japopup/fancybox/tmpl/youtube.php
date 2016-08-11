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
<a <?php echo $arrData['rel'];?> class="<?php echo $arrData['class']; ?>"  href="#<?php echo $arrData['href']; ?>" title="<?php echo $arrData['title'] ?>" ><?php echo $arrData['content'] ?></a>
<script language="javascript" type="text/javascript">
	/* <![CDATA[ */
	jQuery(document).ready(function() {
		if(!(window.japuQuery || window.jQuery)("a.<?php echo $arrData['class']; ?>").fancybox({
			hideOnContentClick: false,
			<?php if($arrData['onopen'] != "") echo "onStart: ".$arrData['onopen'].","; ?>
			<?php if($arrData['onclose'] != "") echo "onClosed: ".$arrData['onclose'].","; ?>
			zoomSpeedIn: <?php echo $arrData['zoomSpeedIn']; ?>,
			zoomSpeedOut: 1,
			overlayShow: <?php echo $arrData['overlayShow']; ?>,
			overlayOpacity: <?php echo $arrData['overlayOpacity']; ?>,
			centerOnScroll: <?php echo $arrData['centerOnScroll']; ?>,
			width: <?php echo $arrData['frameWidth']; ?>,
			height: <?php echo $arrData['frameHeight']; ?> 
		})) {
			jQuery("a.<?php echo $arrData['class']; ?>").fancybox();
		}
	});
/* ]]> */
</script>
<?php
	if ($arrData['useragent'] == "ie"):
?>
	<div style="display: none; visibility: hidden">
		<div style="background-color:#FFFFFF" id="<?php echo $arrData['href']; ?>">
			<center>
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="<?php echo $arrData['frameWidth']; ?>" height="<?php echo $arrData['frameHeight']; ?>">
					<param name="movie" value="<?php echo $arrData['YoutubeLink']; ?>" />
					<param name="quality" value="high" />
					<param name="allowFullScreen" value="true"/>
					<embed src="<?php echo $arrData['YoutubeLink']; ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?php echo $arrData['frameWidth']; ?>" height="<?php echo $arrData['frameHeight']; ?>"></embed>
				</object>
			</center>
		</div>
	</div>
<?php
	else:
?>
	<div style="display: none; visibility: hidden">
		<div id="<?php echo $arrData['href']; ?>">
			<center>
				<object type="application/x-shockwave-flash"
					data="<?php echo $arrData['YoutubeLink']; ?>"
					width="<?php echo $arrData['frameWidth']; ?>" height="<?php echo $arrData['frameHeight']; ?>" >
					<param name="movie" value="<?php echo $arrData['YoutubeLink']; ?>" />
					<param name="allowFullScreen" value="true" />
					<param name="quality" value="high" />
				</object>
			</center>
		</div>
	</div>
<?php
	endif;
?>