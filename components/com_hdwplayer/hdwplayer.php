<?php

/*
 * @version		$Id: webplayer.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('HdwplayerController', JPATH_COMPONENT_ADMINISTRATOR.'/controllers/controller.php');
JLoader::register('HdwplayerView', JPATH_COMPONENT_ADMINISTRATOR.'/views/view.php');
JLoader::register('HdwplayerModel', JPATH_COMPONENT_ADMINISTRATOR.'/models/model.php');
JLoader::register('HdwplayerUtility', JPATH_COMPONENT_ADMINISTRATOR.'/libs/utility.php');

// Define constants for all pages
if(!defined('DS')) { define('DS',DIRECTORY_SEPARATOR); }
define( 'COM_HDWPLAYER_BASEURL', JURI::root().'index.php?option=com_hdwplayer' );
define( 'UPLOAD_DIR', 'media'.DS.'com_hdwplayer'.DS );
define( 'HDWPLAYER_UPLOAD_BASE', JPATH_ROOT.DS.UPLOAD_DIR );
define( 'HDWPLAYER_UPLOAD_BASEURL', JURI::root().str_replace( DS, '/', UPLOAD_DIR ) );
if (version_compare(JVERSION, '1.6.0', '<')) {
	define( 'HDWPLAYER_JVERSION', '' );
} else {
	define( 'HDWPLAYER_JVERSION', '3.0' );
}

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Initialize the controller
$controller = new HdwplayerControllerDefault( );

// Perform the Request task
if(JRequest::getCmd('view') == 'category' && !JRequest::getCmd('wid')){
	JRequest::setVar('view', 'categories');
} else if(JRequest::getCmd('view') == 'video' && !JRequest::getCmd('wid')){
	JRequest::setVar('view', 'videos');
}

$controller->execute(JRequest::getCmd('view'));
$controller->redirect();

?>