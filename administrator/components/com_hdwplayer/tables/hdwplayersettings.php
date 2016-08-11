C<?php

/*
 * @version		$Id: webplayersettings.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

class TableHdwplayerSettings extends JTable {

	var $id               = null;
	var $width            = null;
    var $height           = null;
	var $title            = 0;
	var $description      = 0;
    var $licensekey       = null;
    var $logo             = null;
    var $logoposition     = null;
    var $logoalpha        = null;
    var $logotarget       = null;
    var $skinmode         = null;
    var $stretchtype      = null;
    var $buffertime       = null;
    var $volumelevel      = null;
    var $autoplay         = 0;
    var $playlistautoplay = 0;
	var $playlistopen     = 0;
	var $playlistrandom   = 0;
	var $ffmpeg           = null;
    var $flvtool2         = null;
	var $qtfaststart      = null;
	var $rows             = null;
	var $cols             = null;
	var $thumbwidth       = null;
	var $thumbheight      = null;
	var $subcategories    = null;
	var $relatedvideos    = null;	

	function __construct(& $db) {
		parent::__construct('#__hdwplayer_settings', 'id', $db);
	}

	function check() {
		return true;
	}

}

?>