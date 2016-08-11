<?php

/*
 * @version		$Id: utils.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(version_compare(JVERSION, '3.0', '<')) {
	jimport('joomla.html.pane');
}

class AllVideoShareUtils {
	
	public static function ListBoolean($name, $selected = 1, $disabled = 0) {		
		$options[] = JHTML::_('select.option', 1, JText::_('ALL_VIDEO_SHARE_YES'));
		if(!$disabled) $options[] = JHTML::_('select.option', 0, JText::_('ALL_VIDEO_SHARE_NO'));
		
		return JHTML::_('select.genericlist', $options, $name, '', 'value', 'text', $selected);		
	}
	
}