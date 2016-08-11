<?php

/*
 * @version		$Id: default.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
$data = $this->data;
$approval = $this->approval;
if(HDWPLAYER_JVERSION == '3.0') {
	$checkAll = '<input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" />';
} else {
	$checkAll = '<input type="checkbox" name="toggle" value="" onClick="checkAll(' . count( $data ) . ');" />';
}

?>

<div id="hdwplayer">
  <form action="index.php?option=com_hdwplayer&view=videos" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <table style="padding-bottom:10px;">
      <tr>
        <td align="left" width="100%"><?php echo JText::_('<strong>Filter:</strong>'); ?>
          <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" title="<?php echo JText::_('Filter by title'); ?>"/>
          <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
          <button onclick="document.getElementById('search').value=''; this.form.getElementById('filter_state').value='-1'; this.form.getElementById('filter_category').value='- Select category -';this.form.submit();"> 
		  	<?php echo JText::_('Reset'); ?>
          </button>
          <label style="margin-left:15px">
          	<strong>
            	You have <font color="#FF0000"><?php echo $approval; ?> videos</font> (uploaded by your users) waiting for your approval.
            </strong>
          </label>
        </td>
        <td nowrap="nowrap">
			<?php echo $this->lists['categories']; ?> <?php echo $this->lists['state']; ?> <?php if(HDWPLAYER_JVERSION == '3.0') echo $this->pagination->getLimitBox(); ?>
        </td>
      </tr>
    </table>
    <table class="adminlist table table-striped">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="5%"><?php echo $checkAll; ?></th>
          <th style="padding-left:10px; text-align:left"><?php echo JText::_('Video Title'); ?></th>
          <th width="15%" style="padding-left:10px; text-align:left"><?php echo JText::_('Category'); ?></th>
          <th width="12%"><?php echo JText::_('Position'); ?>&nbsp;&nbsp;<?php echo JHTML::_('grid.order',  $data ); ?></th>
          <th width="8%"><?php echo JText::_('User'); ?></th>
          <th width="8%"><?php echo JText::_('Featured'); ?></th>
          <th width="8%"><?php echo JText::_('Views'); ?></th>
          <th width="8%" class="title" nowrap="nowrap"><?php echo JText::_('Published'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
		$k = 0;
		for ($i=0, $n=count($data); $i < $n; $i++) {
			$row = $data[$i];

			$k          = $i % 2;
			$link 		= JRoute::_( 'index.php?option=com_hdwplayer&view=videos&task=edit&'. HdwplayerUtility::getToken() .'=1&'.'cid[]='. $row->id );
			$checked 	= JHTML::_('grid.id', $i, $row->id );
			$published 	= JHTML::_('grid.published', $row, $i );
			$featured   = ($row->featured == 1) ? 'Yes' : 'No';
      ?>
        <tr class="<?php echo "row$k"; ?>">
          <td align="center"><?php echo ($this->limitstart + $i + 1); ?></td>
          <td align="center"><?php echo $checked; ?> </td>
          <td style="padding-left:10px; text-align:left"><a href="<?php echo $link; ?>"> <?php echo $row->title;?> </a> </td>
          <td style="padding-left:10px; text-align:left"><?php echo $row->category; ?> </td>          
          <td class="order">
          	<span><?php echo $this->pagination->orderUpIcon( $i, ($row->category == @$data[$i-1]->category), 'orderup', 'Move Up'); ?></span>
            <span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->category == @$data[$i+1]->category), 'orderdown', 'Move Down'); ?></span>
            <input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align:center;" />
          </td>
          <td align="center"><?php echo $row->user; ?> </td>
          <td align="center"><?php echo $featured; ?> </td>
          <td align="center"><?php echo $row->views; ?> </td>
          <td align="center"><?php echo $published; ?> </td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
        </tr>
      </tfoot>
    </table>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>