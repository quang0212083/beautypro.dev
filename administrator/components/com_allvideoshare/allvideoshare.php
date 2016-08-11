<?php

/*
 * @version		$Id: allvideoshare.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Register Libraries
JLoader::register('AllVideoShareFallback', JPATH_COMPONENT.'/etc/fallback.php');
JLoader::register('AllVideoShareUtils', JPATH_COMPONENT.'/etc/utils.php');

JHtml::_('behavior.framework', true);

// CSS
$document = JFactory::getDocument();
if(version_compare(JVERSION, '3.0', 'ge')) {
	$document->addStyleSheet( JURI::base().'components/com_allvideoshare/css/allvideoshare.j3.css?r='.rand() );	
	define('ALLVIDEOSHARE_JVERSION', '3.0');
} else {
	$document->addStyleSheet( JURI::base().'components/com_allvideoshare/css/allvideoshare.css?r='.rand() );
	define('ALLVIDEOSHARE_JVERSION', '');
}

// Define constants for all pages
if(!defined('DS')) { define('DS',DIRECTORY_SEPARATOR); }
define('UPLOAD_DIR', 'media'.DS.'com_allvideoshare'.DS);
define('ALLVIDEOSHARE_UPLOAD_BASE', JPATH_ROOT.DS.UPLOAD_DIR);
define('ALLVIDEOSHARE_UPLOAD_BASEURL', JURI::root().str_replace(DS, '/', UPLOAD_DIR));

// Require the base controller
if(JRequest::getCmd('view') == '') {
	JRequest::setVar('view', 'dashboard');
}
$view = JRequest::getCmd('view');
$controller = JString::strtolower($view);
require_once JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

// Initialize the controller
$className = 'AllVideoShareController'.$controller;
$controller = new $className();

// Perform the Request task
if(JRequest::getCmd('task') == '') {
	JRequest::setVar('task', $view);
}
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();