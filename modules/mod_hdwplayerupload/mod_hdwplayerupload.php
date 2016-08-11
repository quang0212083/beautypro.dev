<?php

/*
 * @version		$Id: mod_webplayerupload.php 3.1 2012-11-28 $
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
 
$userobj = JFactory::getUser();	
$user = $userobj->get('username');
if(!$user) {
	echo JText::_('You need to Register / Login to upload your Videos');
	return;
}

$items = modhdwplayeruploadHelper::getItems( $params );

$category_options[] = JHTML::_('select.option', 'none', JText::_('Uncategorised'));
$categories = modhdwplayeruploadHelper::getcategories();		 
foreach ( $categories as $item ) {
	$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
	$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
}

if(JRequest::getCmd('add')) {
	require (JModuleHelper::getLayoutPath('mod_hdwplayerupload', 'default_add'));
} else if($uid = JRequest::getCmd('uid')) {
	$data = modhdwplayeruploadHelper::getrow($uid);
	require (JModuleHelper::getLayoutPath('mod_hdwplayerupload', 'default_edit'));
} else {
	$data = modhdwplayeruploadHelper::getvideos();
	require (JModuleHelper::getLayoutPath('mod_hdwplayerupload', 'default_videos'));
}

?>