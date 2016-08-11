<?php

/*
 * @version		$Id: videos.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

class JElementVideos extends JElement {
	var	$_name = 'Videos';

	function fetchElement($name, $value, &$node, $control_name)	{
		$db = JFactory::getDBO();

		$query = 'SELECT a.id, a.title'
		. ' FROM #__allvideoshare_videos AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.title';
		$db->setQuery( $query );
		$options = $db->loadObjectList();
		
		array_unshift($options, JHTML::_('select.option', 'popular', JText::_('POPULAR_VIDEO'), 'id', 'title'));
		array_unshift($options, JHTML::_('select.option', 'latest', JText::_('LATEST_VIDEO'), 'id', 'title'));

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"', 'id', 'title', $value, $control_name.$name );
	}
}