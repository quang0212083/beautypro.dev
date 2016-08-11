<?php 

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

if(!$this->user) {
	echo JText::_('YOU_NEED_TO_REGISTER_TO_VIEW_THIS_PAGE');
	return;
}

$config = $this->config;
$videos = $this->videos;
$header = ( substr(JVERSION,0,3) != '1.5' ) ? 'page_heading' : 'page_title';
$editlink = 'index.php?option=com_allvideoshare&view=user&task=editvideo&'. AllVideoShareFallback::getToken() .'=1&'.'cid[]=';
$dellink = 'index.php?option=com_allvideoshare&view=user&task=deletevideo&'. AllVideoShareFallback::getToken() .'=1&'.'cid[]=';
$qs = JRequest::getInt('Itemid') ? '&Itemid=' . JRequest::getInt('Itemid') : '';

$document = JFactory::getDocument();
$document->addStyleSheet( JRoute::_("index.php?option=com_allvideoshare&view=css"),'text/css',"screen");
$document->addStyleSheet( JURI::root()."components/com_allvideoshare/css/allvideoshareupdate.css",'text/css',"screen");

?>
<?php if($this->params->get('show_'.$header, 1)) : ?>
	<h2> <?php echo $this->escape($this->params->get($header)); ?> </h2>
<?php 
	endif;
	AllVideoShareFallback::startTabs();
	AllVideoShareFallback::initPanel(JText::_('MY_VIDEOS'), 'myvideos');
?>
<div class="avs_user<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
  <table cellpadding="0px" cellspacing="1px" border="0" width="100%">
    <thead>
      <tr>
        <th width="5%" align="center">#</th>
        <th align="left"><?php echo JText::_('TITLE'); ?></th>
        <th width="25%" align="left"><?php echo JText::_('CATEGORY'); ?></th>
        <th width="8%" align="center"><?php echo JText::_('EDIT'); ?></th>
        <th width="8%" align="center"><?php echo JText::_('DELETE'); ?></th>
        <th width="8%" align="center"><?php echo JText::_('APPROVED'); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
		$k = 0;
		for ($i=0, $n=count($videos); $i < $n; $i++) {
			$row = $videos[$i];
			$k = $i % 2;
			$link = JRoute::_( $editlink.$row->id.$qs );
			$delete = JRoute::_( $dellink.$row->id.$qs );
			$published = ($row->published == 1) ? JText::_('Yes') : JText::_('No');
	  ?>
      <tr class="<?php echo "row$k"; ?>">
        <td align="center"><?php echo $i+1;?> </td>
        <td align="left"><a href="<?php echo $link; ?>"> <?php echo $row->title;?></a></td>
        <td align="left"><?php echo $row->category; ?> </td>
        <td align="center"><a href="<?php echo $link; ?>"><img src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/edit.jpg"  border="0" /></a></td>
        <td align="center"><a href="<?php echo $delete; ?>"><img src="<?php echo JURI::root(); ?>components/com_allvideoshare/assets/delete.jpg"  border="0" /></a></td>
        <td align="center"><?php echo $published; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div id="avs_pagination<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>"><?php echo $this->pagination->getPagesLinks(); ?></div>
<?php
	AllVideoShareFallback::initPanel(JText::_('ADD_NEW_VIDEO'), 'addnew', true);
    echo $this->loadTemplate('add');
	AllVideoShareFallback::endTabs();