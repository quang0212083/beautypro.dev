<?php

/*
 * @version		$Id: adverts.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class AllVideoShareTableAdverts extends JTable {

	var $id = null;
	var $title = null;
	var $type = null;
	var $method = null;
	var $video = null;
	var $link = null;
	var $impressions = null;
	var $clicks = null;
	var $published = 0;	
	
	function __construct(& $db) {
		parent::__construct('#__allvideoshare_adverts', 'id', $db);
	}

	function check() {
		return true;
	}
	
}