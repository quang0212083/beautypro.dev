<?php

/*
 * @version		$Id: videos.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class AllVideoShareTableVideos extends JTable {

	var $id = null;
	var $title = null;
	var $slug = null;
	var $user = null;
	var $type = null;
	var $streamer = null;
	var $dvr = 0;
	var $token = null;
	var $video = null;
	var $hd = null;
	var $thumb = null;
	var $preview = null;
	var $thirdparty = null;
	var $category = null;
	var $featured = 0;	
	var $description = null;
	var $tags = null;
	var $metadescription = null;
	var $views = null;
	var $access = null;
	var $ordering = null;	
	var $published = 0;	

	function __construct(& $db) {
		parent::__construct('#__allvideoshare_videos', 'id', $db);
	}

	function check() {
		return true;
	}
	
}