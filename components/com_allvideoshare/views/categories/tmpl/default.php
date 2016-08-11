<?php

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$config = $this->config;
$categories = $this->categories;
$header = ( substr(JVERSION,0,3) != '1.5' ) ? 'page_heading' : 'page_title';
$link = 'index.php?option=com_allvideoshare&view=category&slg=';
$qs = '';
$qs .= JRequest::getCmd('orderby') ? '&orderby=' . JRequest::getCmd('orderby') : '';
$qs .= JRequest::getInt('Itemid')  ? '&Itemid=' . JRequest::getInt('Itemid') : '';
$row = 0;
$column = 0;
$isResponsive = ($config[0]->responsive == 1) ? 'class="avs_responsive"' : '';

$document = JFactory::getDocument();
$document->addStyleSheet( JRoute::_("index.php?option=com_allvideoshare&view=css"),'text/css',"screen");
$document->addStyleSheet( JURI::root() . "components/com_allvideoshare/css/allvideoshareupdate.css",'text/css',"screen");

?>
<?php if($this->params->get('show_'.$header, 1)) : ?>
	<h2> <?php echo $this->escape($this->params->get($header)); ?> </h2>
<?php endif; ?>
<div id="avs_gallery<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" <?php echo $isResponsive; ?>>
  <?php 
  	if(!count($categories)) echo JText::_('ITEM_NOT_FOUND');
  	for ($i=0, $n=count($categories); $i < $n; $i++) { 
		$clear = '';  
  		if($column >= $this->cols) {
			$clear  = '<div class="avs_clear"></div>';
			$column = 0;
			$row++;		
		}
		$column++;
		echo $clear;
  ?>
  <div class="avs_thumb" style="width:<?php echo $this->thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.$categories[$i]->slug.$qs); ?>"'>
    <div class="avs_thumb_inner">
        <div class="avs_img_container">
  			<img class="arrow" src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/play.png" border="0" /> 
    		<img class="image" src="<?php echo $categories[$i]->thumb; ?>" style="width:<?php echo $this->thumb_width; ?>px; height:<?php echo $this->thumb_height; ?>px;" title="<?php echo JText::_('CLICK_TO_VIEW').' : '.$categories[$i]->name; ?>" border="0" />
    	</div>
    	<span class="name"><?php echo $categories[$i]->name; ?></span>
  	</div> 
  </div>
  <?php } ?>
  <div style="clear:both"></div>
</div>
<div id="avs_pagination<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->pagination->getPagesLinks(); ?></div>