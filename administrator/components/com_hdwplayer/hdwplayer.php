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

JLoader::register('HdwplayerController', JPATH_COMPONENT.'/controllers/controller.php');
JLoader::register('HdwplayerView', JPATH_COMPONENT.'/views/view.php');
JLoader::register('HdwplayerModel', JPATH_COMPONENT.'/models/model.php');
JLoader::register('HdwplayerUtility', JPATH_COMPONENT.'/libs/utility.php');

// Define constants for all pages
if(!defined('DS')) { define('DS',DIRECTORY_SEPARATOR); }
define( 'UPLOAD_DIR', 'media'.DS.'com_hdwplayer'.DS );
define( 'HDWPLAYER_UPLOAD_BASE', JPATH_ROOT.DS.UPLOAD_DIR );
define( 'HDWPLAYER_UPLOAD_BASEURL', JURI::root().str_replace( DS, '/', UPLOAD_DIR ) );
 
// CSS
$document = JFactory::getDocument();
if (version_compare(JVERSION, '3.0', 'ge')) {
	$document->addStyleSheet( JURI::base().'components/com_hdwplayer/css/hdwplayer.j3.css?r=' . rand() );
	define( 'HDWPLAYER_JVERSION', '3.0' );
} else {
	$document->addStyleSheet( JURI::base().'components/com_hdwplayer/css/hdwplayer.css?r=' . rand() );
	define( 'HDWPLAYER_JVERSION', '' );	
}

// Require the base controller
if(JRequest::getCmd('view') == '') {
	JRequest::setVar('view', 'dashboard');
}
$controller = JRequest::getCmd('view');
$controller = JString::strtolower( $controller );
require_once JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

// Initialize the controller
$classname  = 'HdwplayerController'.$controller;
$controller = new $classname();

// Perform the Request task
if(JRequest::getCmd('task') == '') {
	JRequest::setVar('task', JRequest::getCmd('view'));
}
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();

?>