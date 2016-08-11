<?php

/*
 * @version		$Id: categories.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

if(substr(JVERSION,0,3) != '1.5') {
	jimport('joomla.form.formfield');
	
	class JFormFieldCategories extends JFormField {

		var	$type = 'categories';

		function getInput() {
			return JElementCategories::fetchElement($this->name, $this->value, $this->element, $this->options['control']);
		}
	}
}

jimport('joomla.html.parameter.element');
class JElementCategories extends JElement {
	var	$_name = 'categories';

	function fetchElement($name, $value, &$node, $control_name) {
		$db = JFactory::getDBO();

		$query = 'SELECT * FROM #__allvideoshare_categories';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();
		if( $mitems ) {
			foreach ( $mitems as $v ) {
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$items = array();
		
		foreach ( $list as $item ) {
			$item->treename = JString::str_ireplace('&#160;', ' -', $item->treename);
			$items[] = JHTML::_('select.option',  $item->slug, $item->treename );
		}
		
		array_unshift($items, JHTML::_('select.option', '0', '- '.JText::_('DISPLAY_ALL_CATEGORIES').' -', 'value', 'text'));
		
		return JHTML::_('select.genericlist',  $items, ''.$control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value );

	}
}