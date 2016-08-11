<?php

/*
 * @version		$Id: webplayercategory.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableHdwplayerCategory extends JTable {

	var $id              = null;
	var $name            = null;
	var $parent          = null;
	var $ordering        = null;
	var $type            = null;
	var $image           = null;
	var $metakeywords    = null;
	var $metadescription = null;
	var $published       = 0;

	function __construct(& $db) {
		parent::__construct('#__hdwplayer_category', 'id', $db);
	}

	function check() {
		return true;
	}
	
}

?>