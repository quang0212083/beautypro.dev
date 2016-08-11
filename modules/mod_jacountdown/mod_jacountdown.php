<?php
/**
 * ------------------------------------------------------------------------
 * JA CountDown Module for Joomla 2.5 & 3.4
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
$mainframe = JFactory::getApplication();
$document  = JFactory::getDocument();

require_once(dirname(__FILE__).'/asset/behavior.php');
JHTML::_('JABehavior.jquery');//jquery core


$custom_titles 		= $params->get('custom_titles','');
$custom_message 	= $params->get('custom_message','');
$jalayout 			= $params->get('jalayout','layout1');
$startDate  		= strtotime($params->get('jastartDate'));
$endDate    		= strtotime($params->get('jaendDate'));
$secondsColor    	= "#".$params->get('secondsColor','ffdc50');
$minutesColor    	= "#".$params->get('minutesColor','9cdb7d');
$hoursColor    		= "#".$params->get('hoursColor','378cff');
$daysColor    		= "#".$params->get('daysColor','ff6565');
$secondsGlow 		= "#".$params->get('secondsGlow','ffdc50');

$choisebackground = $params->get('choisebackground','bg_images');
switch($choisebackground){
	case 'bg_images':
		$stylesheets = $params->get('backgroundimages','')?' style="background:url(\''.$params->get('backgroundimages','').'\') no-repeat;"':'';
		break;
	case 'bg_color':
		$stylesheets = $params->get('backgroundcolor','')?' style="background:#'.$params->get('backgroundcolor','').';"':'';
		break;
	default:
		$stylesheets='';
		break;
}

$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/'.$module->module.'/tmpl/'.$jalayout.'/css/jacclock.css');
$doc->addScript('modules/'.$module->module.'/tmpl/'.$jalayout.'/js/jacclock.js');
include_once(dirname(__FILE__).'/asset/asset.php');

$doc->addScriptDeclaration('
	var jacdsecondsColor = "'.$secondsColor.'";
	var jacdminutesColor = "'.$minutesColor.'";
	var jacdhoursColor = "'.$hoursColor.'";
	var jacddaysColor = "'.$daysColor.'";
	
	var jacdsecondsGlow = "'.$secondsGlow.'";
	
	var jacdstartDate = "'.$startDate.'";
	var jacdendDate = "'.$endDate.'";
	var jacdnow = "'.strtotime('now').'";
	var jacdseconds = "'.date('s').'";
');

require JModuleHelper::getLayoutPath('mod_jacountdown', $params->get('layout', 'default'));