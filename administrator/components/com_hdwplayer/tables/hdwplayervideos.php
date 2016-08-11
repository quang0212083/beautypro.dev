<?php

/*
 * @version		$Id: webplayervideos.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableHdwplayerVideos extends JTable {

	var $id              = null;
	var $title           = null;
	var $description     = null;
	var $type            = null;
	var $streamer        = null;
	var $dvr             = null;
	var $video           = null;
	var $hdvideo         = null;
	var $preview         = null;
	var $thumb           = null;
	var $token           = null;
	var $category        = null;
	var $featured        = 0;
	var $user            = 'Admin';
	var $tags            = null;
	var $metadescription = null;
	var $views           = null;
	var $ordering        = null;
	var $published       = 0;
	
	function __construct(& $db) {
		parent::__construct('#__hdwplayer_videos', 'id', $db);
	}

	function check() {
		return true;
	}
	
}

?>