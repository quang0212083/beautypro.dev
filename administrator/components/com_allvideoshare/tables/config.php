<?php

/*
 * @version		$Id: config.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class AllVideoShareTableConfig extends JTable {

	var $id = null;	
	var $responsive = 0;
	var $rows = null;
	var $cols = null;
	var $thumb_width = null;
	var $thumb_height = null;
	var $playerid = null;
	var $layout = null;
	var $relatedvideoslimit = null;
	var $title = 0;
	var $description = 0;
	var $category = 0;
	var $views = 0;
	var $search = 0;
	var $comments_type = null;
	var $fbappid = null;
	var $comments_posts = null;
	var $comments_width = null;
	var $comments_color = null;
	var $auto_approval = 0;
	var $type_youtube = 0;
	var $type_rtmp = 0;
	var $type_lighttpd = 0;
	var $type_highwinds = 0;
	var $type_bitgravity = 0;
	var $type_thirdparty = 0;
	var $css = null;

	function __construct(& $db) {
		parent::__construct('#__allvideoshare_config', 'id', $db);
	}

	function check() {
		return true;
	}
	
}