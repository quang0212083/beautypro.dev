<?php

/*
 * @version		$Id: utility.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class HdwplayerUtility {

	public static function getToken() {
	
		if (version_compare(JVERSION, '1.6.0', '<')) {
			return JUtility::getToken();
		} else {
			return JSession::getFormToken();
		}
		
	}

}

?>