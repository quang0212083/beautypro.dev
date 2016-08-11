<?php 

/*
 * @version		$Id: default.php 3.0 2012-10-10 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$settings = $this->settings;

/**********************************************************************/
$cols         = $settings->cols;
$thumb_width  = $settings->thumbwidth;
$thumb_height = $settings->thumbheight;
/**********************************************************************/

$videos  = $this->search;
$link    = JURI::root().'index.php?option=com_hdwplayer&view=video&wid=';
$qs      = JRequest::getVar('orderby') ? '&orderby=' . JRequest::getVar('orderby') : '';
$qs     .= JRequest::getVar('Itemid')  ? '&Itemid=' . JRequest::getVar('Itemid') : '';
$row     = 0;
$column  = 0;

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . "components/com_hdwplayer/css/hdwplayer.css",'text/css',"screen");

?>

<div id="hdwplayer_gallery">
  <?php 
  	if(!count($videos)) echo JText::_('Item not found.');
  	for ($i=0, $n=count($videos); $i < $n; $i++) {   
		$clear = ''; 
    	if($column >= $cols) {
			$clear  = '<div style="clear:both;"></div>';
			$column = 0;
			$row++;		
		}
		if(!$videos[$i]->thumb) $videos[$i]->thumb = 'http://img.youtube.com/vi/default.jpg';
		$column++;
		echo $clear;
  ?>
  <div class="hdwplayer_thumb" style="width:<?php echo $thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.$videos[$i]->id.$qs); ?>"'>
   	<img class="arrow" src="<?php echo JURI::root(); ?>components/com_hdwplayer/assets/play.gif" border="0" style="margin-left:<?php echo ($thumb_width / 2) - 15; ?>px; margin-top:<?php echo ($thumb_height / 2) - 13; ?>px;" /> 
    <img class="image" src="<?php echo $videos[$i]->thumb; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" title="<?php echo JText::_('Click to View').' : '.$videos[$i]->title; ?>" border="0" style="height:<?php echo $thumb_height; ?>px" />
    <span class="title"><?php echo $videos[$i]->title; ?></span>
    <span class="views"><strong><?php echo JText::_('Views'); ?> : </strong><?php echo $videos[$i]->views; ?></span>
  </div>
  <?php } ?>
  <div style="clear:both"></div>
</div>