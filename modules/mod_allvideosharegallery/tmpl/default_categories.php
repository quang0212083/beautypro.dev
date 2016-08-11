<?php 

/*
 * @version		$Id: default_categories.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$link = 'index.php?option=com_allvideoshare&view=category';
$link .= '&orderby='.$items['orderby'];
$categories = $items['data'];
$more = $items['more'];
$count = $items['columns'] * $items['rows'];
if(count($categories) <= $count) {
	$more = 0;
    $count = count($categories);
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
  	if(!count($categories)) echo JText::_('ITEM_NOT_FOUND');
  	for ($i=0, $n=$count; $i < $n; $i++) { 
		$clear = '';  
  		if($column >= $items['columns']) {
			$clear  = '<div class="avs_clear"></div>';
			$column = 0;
			$row++;		
		}
		$column++;
		echo $clear;
  ?>
  <div class="avs_thumb" style="width:<?php echo $items['thumb_width']; ?>px;" onclick='javascript:location.href="<?php echo JRoute::_($link.'&slg='.$categories[$i]->slug); ?>"'>
  	<div class="avs_thumb_inner">
  		<div class="avs_img_container">
   			<img class="arrow" src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/play.png" border="0" />
    		<img class="image" src="<?php echo $categories[$i]->thumb; ?>" style="width:<?php echo $items['thumb_width']; ?>px; height:<?php echo $items['thumb_height']; ?>px;" title="<?php echo JText::_('CLICK_TO_VIEW') . ' : ' . $categories[$i]->name; ?>" border="0" />
    	</div>
    	<span class="name"><?php echo $categories[$i]->name; ?></span>
    </div>
  </div>
  <?php } ?>
  <div style="clear:both"></div>
</div>
<?php if($more == 1) { ?>
<div class="avsmore"><a href="<?php echo JRoute::_($link); ?>"><?php echo JText::_('MORE'); ?></a></div>
<?php }