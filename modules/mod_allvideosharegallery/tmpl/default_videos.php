<?php 

/*
 * @version		$Id: default_videos.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$morelink = ( $items['catslg'] != "0" ) ? 'index.php?option=com_allvideoshare&view=category&slg='.$items['catslg'] : 'index.php?option=com_allvideoshare&view=video';
$morelink .= '&orderby='.$items['orderby'];
$link = ( $items['link'] != '' ) ? $items['link'] : 'index.php?option=com_allvideoshare&view=video';
$qs = (!strpos($link, '?')) ? '?' : '&';
$videos = $items['data'];
$more = $items['more'];
$count = $items['columns'] * $items['rows'];
if(count($videos) <= $count) {
	$more = 0;
	$count = count($videos);
}
$row = 0;
$column = 0;
$isResponsive = ($responsive == 1) ? ' avs_responsive' : '';

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_allvideoshare/css/allvideoshare.css?r=".rand(), 'text/css', "screen");
$document->addStyleSheet( JURI::root()."components/com_allvideoshare/css/allvideoshareupdate.css", 'text/css', "screen");
?>

<div id="avs_gallery" class="avs_gallery<?php echo $moduleclass_sfx . $isResponsive; ?>">
  <?php 
  	if(!count($videos)) echo JText::_('ITEM_NOT_FOUND');
  	for ($i=0, $n=$count; $i < $n; $i++) {   
		$clear = ''; 
    	if($column >= $items['columns']) {
			$clear = '<div class="avs_clear"></div>';
			$column = 0;
			$row++;		
		}
		$column++;
		echo $clear;
  ?>
  <div class="avs_thumb" style="width:<?php echo $items['thumb_width']; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.$qs.'slg='.$videos[$i]->slug.'&orderby='.$items['orderby']); ?>"'>
    <div class="avs_thumb_inner">
  		<div class="avs_img_container">
    		<img class="arrow" src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/play.png" border="0" /> 
    		<img class="image" src="<?php echo $videos[$i]->thumb; ?>" style="width:<?php echo $items['thumb_width']; ?>px; height:<?php echo $items['thumb_height']; ?>px;" title="<?php echo JText::_('CLICK_TO_VIEW') . ' : ' . $videos[$i]->title; ?>" border="0" />
    	</div> 
    	<span class="title"><?php echo $videos[$i]->title; ?></span> 
    	<span class="views"><strong><?php echo JText::_('VIEWS'); ?> : </strong><?php echo $videos[$i]->views; ?></span>
    </div>
  </div>
  <?php } ?>
  <div style="clear:both"></div>
</div>
<?php if($more == 1) { ?>
	<div class="avsmore"><a href="<?php echo JRoute::_($morelink); ?>"><?php echo JText::_('MORE'); ?></a></div>
<?php }