<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 


defined('_JEXEC') or die;
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	</th>
        <th width="1%" class="nowrap">
		<?php echo JText::_('ID'); ?>
	</th>
	<th>
		<?php echo JText::_('Name'); ?>
	</th>
	   <th>
		<?php echo JText::_('Shortcodes'); ?>
	</th>
        <th>
		<?php echo JText::_('Images(count)'); ?>
	</th>

</tr>
