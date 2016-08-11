<?php

/*
 * @version		$Id: webplayerskin.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableHdwplayerSkin extends JTable {

	var $id           = null;
    var $controlbar   = 0;
    var $playpause    = 0;
    var $progressbar  = 0;
    var $timer        = 0;
    var $share        = 0;
    var $volume       = 0;
    var $fullscreen   = 0;
    var $playdock     = 0;
    var $videogallery = 0;

	function __construct(& $db) {
		parent::__construct('#__hdwplayer_skin', 'id', $db);
	}

	function check() {
		return true;
	}

}

?>