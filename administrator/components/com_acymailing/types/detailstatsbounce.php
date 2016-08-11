<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.9.3
 * @author	acyba.com
 * @copyright	(C) 2009-2015 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class detailstatsbounceType{
	function detailstatsbounceType(){

		$query = 'SELECT DISTINCT bouncerule FROM '.acymailing_table('userstats') .' WHERE bouncerule IS NOT NULL';
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$bouncerules = $db->loadObjectList();
		$this->values = array();
		$this->values[] = JHTML::_('select.option', 0, JText::_('ALL_RULES') );
		foreach($bouncerules as $oneRule){
			$this->values[] = JHTML::_('select.option', $oneRule->bouncerule, $oneRule->bouncerule);
		}
	}

	function display($map,$value){
		return JHTML::_('select.genericlist', $this->values, $map, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $value );
	}
}
