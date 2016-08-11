<?php 

/*
 * @version		$Id: default_videos.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

/**********************************************************************/
$cols         = $items['columns'];
$thumb_width  = $items['thumbwidth'];
$thumb_height = $items['thumbheight'];
/**********************************************************************/

$videos = $items['gallery'];
if($items['link'] != '') {
	$link = $items['link'];	
} else {
   	$link = JURI::root().'index.php?option=com_hdwplayer&view=video';
}
$qs = (!strpos($link, '?')) ? '?' : '&';
$row = 0;
$column = 0;
if(JRequest::getCmd('catid')) $qs .= 'catid='.JRequest::getCmd('catid').'&';

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . "components/com_hdwplayer/css/hdwplayer.css",'text/css',"screen");

?>

<div id="hdwplayer_gallery">
  <?php 
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
  <div class="hdwplayer_thumb" style="width:<?php echo $thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.$qs.'wid='.$videos[$i]->id); ?>"'>
  	<img class="arrow" src="<?php echo JURI::root(); ?>components/com_hdwplayer/assets/play.gif" border="0" style="margin-left:<?php echo ($thumb_width / 2) - 15; ?>px; margin-top:<?php echo ($thumb_height / 2) - 13; ?>px;" />
    <img class="image" src="<?php echo $videos[$i]->thumb; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" title="<?php echo JText::_('Click to View').' : '.$videos[$i]->title; ?>" border="0" style="height:<?php echo $thumb_height; ?>px" />
    <span class="title"><?php echo $videos[$i]->title; ?></span>
    <span class="views"><strong><?php echo JText::_('Views'); ?> : </strong><?php echo $videos[$i]->views; ?></span>
  </div>
  <?php } ?>
  <div style="clear:both"></div>
</div>
<div id="hdwplayer_pagination" style="text-align:center; margin:10px 0px;"><?php echo $pagination->getPagesLinks(); ?></div>