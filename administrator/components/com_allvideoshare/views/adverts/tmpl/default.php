<?php

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$data = $this->data;
?>

<div id="avs" class="avslist">
  <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <table class="avsfilter">
      <tr>
        <td align="left" width="100%">
          <input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" title="<?php echo JText::_('FILTER_BY_TITLE'); ?>"/>
          <button class="btn" onclick="this.form.submit();">
		  	<?php echo JText::_('GO'); ?>
          </button>
          <button class="btn" onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='-1';this.form.submit();">
		  	<?php echo JText::_('RESET'); ?>
          </button>
        </td>
        <td nowrap="nowrap"><?php echo $this->lists['state']; ?></td>
      </tr>
    </table>
    <div class="spacer"></div>
    <table class="adminlist table table-striped">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="5%"><?php echo AllVideoShareFallback::checkAll($data); ?></th>
          <th class="padlft"><?php echo JText::_('TITLE'); ?></th>
           <th width="8%"><?php echo JText::_('ID'); ?></th>
          <th width="8%"><?php echo JText::_('IMPRESSIONS'); ?></th>
          <th width="8%"><?php echo JText::_('CLICKS'); ?></th>
          <th width="8%"><?php echo JText::_('PUBLISHED'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php
		$k = 0;
		for ($i=0, $n=count($data); $i < $n; $i++) {
			$row = $data[$i];

			$k = $i % 2;
			$link = JRoute::_( 'index.php?option=com_allvideoshare&view=adverts&task=edit&'. AllVideoShareFallback::getToken() .'=1&'.'cid[]='. $row->id );
			$checked = JHTML::_('grid.id', $i, $row->id );
			$published = JHTML::_('grid.published', $row, $i );			
		?>
        <tr class="<?php echo "row$k"; ?>">
          <td class="ctr"><?php echo ($this->limitstart + $i + 1); ?> </td>
          <td class="ctr"><?php echo $checked; ?> </td>
          <td class="padlft"><a href="<?php echo $link; ?>"> <?php echo $row->title;?> </a></td>
          <td class="ctr"><?php echo $row->id;?> </td>
          <td class="ctr"><?php echo $row->impressions; ?> </td>
          <td class="ctr"><?php echo $row->clicks; ?> </td>
          <td class="ctr"><?php echo $published; ?> </td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
        </tr>
      </tfoot>
    </table>
    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="option" value="com_allvideoshare" />
    <input type="hidden" name="view" value="adverts" />
    <input type="hidden" name="task" value="" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>