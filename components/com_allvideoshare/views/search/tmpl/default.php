<?php

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$config = $this->config;
$search = $this->search;
$header = ( substr(JVERSION,0,3) != '1.5' ) ? 'page_heading' : 'page_title';
$link = 'index.php?option=com_allvideoshare&view=video&slg=';
$qs = JRequest::getInt('Itemid') ? '&Itemid=' . JRequest::getInt('Itemid') : '';
$row = 0;
$column = 0;
$isResponsive = ($config[0]->responsive == 1) ? 'class="avs_responsive"' : '';

$document = JFactory::getDocument();
$document->addStyleSheet( JRoute::_("index.php?option=com_allvideoshare&view=css"),'text/css',"screen");
$document->addStyleSheet( JURI::root() . "components/com_allvideoshare/css/allvideoshareupdate.css",'text/css',"screen");

?>
<h2> <?php echo JText::_('SEARCH_RESULTS_FOR').'"'.$this->keyword.'"'; ?> </h2>
<div id="avs_gallery<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" <?php echo $isResponsive; ?>>
  <?php 
  	if(!count($search)) echo JText::_('ITEM_NOT_FOUND');
  	for ($i=0, $n=count($search); $i < $n; $i++) { 
		$clear = '';  
  		if($column >= $this->cols) {
			$clear  = '<div class="avs_clear"></div>';
			$column = 0;
			$row++;		
		}
		$column++;
		echo $clear;
  ?>
  <div class="avs_thumb" style="width:<?php echo $this->thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.$search[$i]->slug.$qs); ?>"'>
  	<div class="avs_thumb_inner">
    	<div class="avs_img_container">
    		<img class="arrow" src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/play.png" border="0" />
    		<img class="image" src="<?php echo $search[$i]->thumb; ?>" style="width:<?php echo $this->thumb_width; ?>px; height:<?php echo $this->thumb_height; ?>px;" title="<?php echo JText::_('CLICK_TO_VIEW').' : '.$search[$i]->title; ?>" border="0" />
    	</div>
    	<span class="title"><?php echo $search[$i]->title; ?></span>
    	<span class="views"><strong><?php echo JText::_('VIEWS'); ?> : </strong><?php echo $search[$i]->views; ?></span>
  	</div>
  </div>
  <?php } ?>
  <div style="clear:both"></div>
  <div id="avs_pagination<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->pagination->getPagesLinks(); ?></div>
</div>