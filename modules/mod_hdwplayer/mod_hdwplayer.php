<?php

/*
 * @version		$Id: mod_webplayer.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!defined('DS')) { 
	define('DS',DIRECTORY_SEPARATOR); 
}

// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );
 
$params->def('showtitle', 0);
$params->def('showdscription', 0);
$params->def('autodetect', 1);

$items = modhdwplayerHelper::getItems( $params );

require(JModuleHelper::getLayoutPath('mod_hdwplayer'));

?>