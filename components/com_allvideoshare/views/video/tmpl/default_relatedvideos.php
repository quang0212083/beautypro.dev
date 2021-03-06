<?php

/*
 * @version		$Id: default_relatedvideos.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$config = $this->config;
$videos = $this->videos;
$catlink = "index.php?option=com_allvideoshare&view=category&slg=";
$vidlink = "index.php?option=com_allvideoshare&view=video&slg=";
$qs = '';
$qs .= JRequest::getCmd('orderby') ? '&orderby=' . JRequest::getCmd('orderby') : '';
$qs .= JRequest::getInt('Itemid')  ? '&Itemid=' . JRequest::getInt('Itemid') : '';
$row = 0;
$column = 0;
?>

<div class="avs_video_related">
  <h2><?php echo JText::_('RELATED_VIDEOS'); ?></h2>
  <div id="avs_gallery<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
    <?php 
  	if(!count($videos)) echo JText::_('ITEM_NOT_FOUND');
  	for ($i=0, $n=count($videos); $i < $n; $i++) {   
		$clear = ''; 
    	if($column >= $this->cols) {
			$clear  = '<div class="avs_clear"></div>';
			$column = 0;
			$row++;		
		}
		$column++;
		echo $clear;
	?>
    <div class="avs_thumb" style="width:<?php echo $this->thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($vidlink.$videos[$i]->slug.$qs); ?>"'>
    	<div class="avs_thumb_inner">
        	<div class="avs_img_container">
        		<img class="arrow" src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/play.png" border="0" />
        		<img class="image" src="<?php echo $videos[$i]->thumb; ?>" style="width:<?php echo $this->thumb_width; ?>px; height:<?php echo $this->thumb_height; ?>px;" title="<?php echo JText::_('CLICK_TO_VIEW').' : '.$videos[$i]->title; ?>" border="0" />
        	</div>
        	<span class="title"><?php echo $videos[$i]->title; ?></span> 
        	<span class="views"><strong><?php echo JText::_('VIEWS'); ?> : </strong><?php echo $videos[$i]->views; ?></span> 
   		</div>
    </div>
    <?php } ?>
    <div style="clear:both"></div>
  </div>
  <div id="avs_pagination<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->pagination->getPagesLinks(); ?></div>
</div>