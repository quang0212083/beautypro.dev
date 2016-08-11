<?php 

/*
 * @version		$Id: default.php 3.0 2012-10-10 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

/**********************************************************************/
$cols               = $this->cols;
$thumb_width        = $this->thumb_width;
$thumb_height       = $this->thumb_height;
$show_subcategories = $this->show_subcategories;
/**********************************************************************/

$videos     = $this->videos;
$categories = $this->categories;
$header     = ( substr(JVERSION,0,3) != '1.5' ) ? 'page_heading' : 'page_title';
$vidlink    = 'index.php?option=com_hdwplayer&view=video&wid=';
$catlink    = 'index.php?option=com_hdwplayer&view=category&wid=';
$qs         = JRequest::getVar('orderby') ? '&orderby=' . JRequest::getVar('orderby') : '';
$qs        .= JRequest::getVar('Itemid')  ? '&Itemid=' . JRequest::getVar('Itemid') : '';
$row        = 0;
$column     = 0;

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . "components/com_hdwplayer/css/hdwplayer.css",'text/css',"screen");

?>

<?php if ($this->params->get('show_'.$header, 1)) : ?>
	<h2> <?php echo $this->category->name; ?> </h2>
<?php endif; ?>

<div id="hdwplayer_gallery">
  <?php if(count($videos)) {
  	for ($i = 0, $n = count($videos); $i < $n; $i++) {   
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
  <div class="hdwplayer_thumb" style="width:<?php echo $thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($vidlink.$videos[$i]->id.$qs); ?>"'>
  	<img class="arrow" src="<?php echo JURI::root(); ?>components/com_hdwplayer/assets/play.gif" border="0" style="margin-left:<?php echo ($thumb_width / 2) - 15; ?>px; margin-top:<?php echo ($thumb_height / 2) - 13; ?>px;" /> 
    <img class="image" src="<?php echo $videos[$i]->thumb; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" title="<?php echo JText::_('Click to View').' : '.$videos[$i]->title; ?>" border="0" style="height:<?php echo $thumb_height; ?>px" />
   	<span class="title"><?php echo $videos[$i]->title; ?></span>
    <span class="views"><strong><?php echo JText::_('Views'); ?> : </strong><?php echo $videos[$i]->views; ?></span>
  </div>
  <?php } ?> 
  <div style="clear:both"></div>
  <div id="hdwplayer_pagination"><?php echo $this->pagination->getPagesLinks(); ?></div>
  <?php } ?> 
  
  <!-- Sub Categories -->
  <?php if( count($categories) && $show_subcategories ) : ?>
  <h2><?php echo JText::_('Sub Categories'); ?></h2>
  <div class="hdwplayer_gallery">
    <?php 
	$row  	= 0;
	$column = 0;
  	for ($i = 0, $n = count($categories); $i < $n; $i++) {   
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
    <div class="hdwplayer_thumb" style="width:<?php echo $thumb_width; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($catlink.$categories[$i]->id.$qs); ?>"'>
    	<img class="arrow" src="<?php echo JURI::root(); ?>components/com_hdwplayer/assets/play.gif" border="0" style="margin-left:<?php echo ($thumb_width / 2) - 15; ?>px; margin-top:<?php echo ($thumb_height / 2) - 13; ?>px;" />
        <img class="image" src="<?php echo $categories[$i]->image; ?>" width="<?php echo $thumb_width; ?>" height="<?php echo $thumb_height; ?>" title="<?php echo JText::_('Click to View').' : '.$categories[$i]->name; ?>" border="0" />
        <span class="name"><?php echo $categories[$i]->name; ?></span>
    </div>
    <?php } ?>
    <div style="clear:both"></div>
  </div>
  <?php endif; ?>
  <?php if(!count($videos) && !count($categories)) echo JText::_('Item not Found.'); ?>  
</div>