<?php 

/*
 * @version		$Id: default_categories.php 3.1 2012-11-28 $
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

$categories = $items['gallery'];
$u = JURI::getInstance();
$link = $u->toString();
$qs = (!strpos($link, '?')) ? '?catid=' : '&catid=';
$row = 0;
$column = 0;

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . "components/com_hdwplayer/css/hdwplayer.css",'text/css',"screen");

?>

<div class="hdwplayer_gallery">
  <?php 
  	for ($i=0, $n=count($categories); $i < $n; $i++) {   
		$clear = ''; 
    	if($column >= $cols) {
			$clear  = '<div style="clear:both;"></div>';
			$column = 0;
			$row++;		
		}
		if(!$categories[$i]->image) $categories[$i]->image = 'http://img.youtube.com/vi/default.jpg';
		$column++;
		echo $clear;
  	?>
  <div class="hdwplayer_thumb" style="width:<?php echo $thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.$qs.$categories[$i]->id); ?>"'>
  	<img class="arrow" src="<?php echo JURI::root(); ?>components/com_hdwplayer/assets/play.gif" border="0" style="margin-left:<?php echo ($thumb_width / 2) - 15; ?>px; margin-top:<?php echo ($thumb_height / 2) - 13; ?>px;" />
    <img class="image" src="<?php echo $categories[$i]->image; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" title="<?php echo JText::_('Click to View').' : '.$categories[$i]->name; ?>" border="0" style="height:<?php echo $thumb_height; ?>px" />
    <span class="name"><?php echo $categories[$i]->name; ?></span>
  </div>
  <?php } ?>
  <div style="clear:both"></div>
</div>
<div id="hdwplayer_pagination" style="text-align:center; margin:10px 0px;"><?php echo $pagination->getPagesLinks(); ?></div>