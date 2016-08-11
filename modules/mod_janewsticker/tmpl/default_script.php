<?php
/**
 * ------------------------------------------------------------------------
 * JA Newsticker Module for J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

/**
 * JA News Sticker module allows display of article's title from sections or categories. \
 * You can configure the setttings in the right pane. Mutiple options for animations are also added, choose any one.
 * If you are using this module on Teline III template, * then the default module position is "headlines".
 **/
  // no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
	//<![CDATA[
	var options = { 
		box:$('<?php echo $moduleID; ?>'),
		items: $$('#<?php echo $moduleID; ?> .ja-headlines-item'),
		mode: '<?php echo $animationType ;?>',
		wrapper:$('jahl-wapper-items-<?php echo $moduleID; ?>'),
		buttons:{next: $$('.ja-headelines-next'), previous: $$('.ja-headelines-pre')},
		interval:<?php echo (int)$params->get('animation_interval', 3000);?>,
		fxOptions : { 
			duration: <?php echo $params->get('animation_speed', 500);?>,
			transition: <?php echo $params->get('animation_transition', 'Fx.Transitions.linear'); ?> ,
			wait: false,
			link: 'cancel' 
		}	
	};

	var jahl = new JANewSticker( options );
	//]]>
</script>