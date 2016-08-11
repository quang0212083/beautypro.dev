<?php

/*
 * @version		$Id: categories.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

class JElementCategories extends JElement {

		var  $_name = 'Categories';
 
        function fetchElement($name, $value, &$node, $control_name) {		
				$db    =& JFactory::getDBO();

        		$query = 'SELECT a.id, a.name'
        		. ' FROM #__hdwplayer_category AS a'
        		. ' WHERE a.published = 1'
        		. ' ORDER BY a.id';
		
        		$db->setQuery( $query );
        		$options = $db->loadObjectList();
				
                // Base name of the HTML control.
                $ctrl  = $control_name .'['. $name .']';
  
                // Construct the various argument calls that are supported.
                $attribs       = ' ';
                if ($v = $node->attributes( 'size' )) {
                        $attribs       .= 'size="'.$v.'"';
                }
                if ($v = $node->attributes( 'class' )) {
                        $attribs       .= 'class="'.$v.'"';
                } else {
                        $attribs       .= 'class="inputbox"';
                }
                if ($m = $node->attributes( 'multiple' ))
                {
                        $attribs       .= ' multiple="multiple"';
                        $ctrl          .= '[]';
                }
 
                // Render the HTML SELECT list.
                return JHTML::_('select.genericlist', $options, $ctrl, $attribs, 'name', 'name', $value, $control_name.$name );
        }
		
}

?>