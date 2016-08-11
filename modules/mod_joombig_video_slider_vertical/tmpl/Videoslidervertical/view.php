<?php
/**
* @title		joombig video slider vertical module
* @website		http://www.joombig.com
* @copyright	Copyright (C) 2013 joombig.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

    // no direct access
    defined('_JEXEC') or die;
?>
<script>
jQuery.noConflict(); 
</script>
<?php
if ($enable_jQuery == 1) {?>
	<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_video_slider_vertical/tmpl/Videoslidervertical/js/jquery.js"></script>
<?php }?>
<script>
	var call_width_vthumb,call_height_vthumb,call_width_hthumb,call_height_hthumb;
	call_width_vthumb = <?php echo $width_vthumb;?>;
	call_height_vthumb = <?php echo $height_vthumb;?>;
	call_width_hthumb = <?php echo $width_hthumb;?>;
	call_height_hthumb = <?php echo $height_hthumb;?>;
	var autoslide,autoplayvideo;
	autoslide = <?php echo $autoslide;?>;
	autoplayvideo = <?php echo $autoplayvideo;?>;
</script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_video_slider_vertical/tmpl/Videoslidervertical/js/joombig.script.js"></script>
<link rel="stylesheet" href="<?php echo $mosConfig_live_site; ?>/modules/mod_joombig_video_slider_vertical/tmpl/Videoslidervertical/css/slidervertical.css" />
<style>
	.joombig-main-video{
		width:<?php echo $width;?>;
		text-align:center;
		margin:0 auto;
	}

	.html5gallery-elem-0 >a{
		display: none !important;
	}
</style>

<div class="joombig-main-video">
<?php if ($video_skin == "1"){?>
	<div class="html5gallery" data-skin="<?php echo $data_skin; ?>">
<?php }
else
{?>
	<div class="html5gallery" data-skin="<?php echo $data_skin; ?>">
<?php } ?>
<?php
$count1 =1;
foreach($data as $index=>$value)
{?>		
	    <!-- Add images to Gallery -->
		<a href="<?php echo $value['link'] ?>"><img src="<?php echo JURI::root().$value['image'] ?>" alt="<?php echo $value['title'] ?>"></a>
<?php
		$count1++ ; 
} ?>	
	</div>

</div>

<script>
jQuery.noConflict(); 
</script>