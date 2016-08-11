<?php
/**
* @title		Joombig Menu Tree
* @website		http://www.joombig.com
* @copyright	Copyright (C) 2013 joombig.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$list	= modJoombigMenuTreeHelper::getList($params);
$app	= JFactory::getApplication();
$enable_jQuery	= $params->get('enable_jQuery', 1);
$width_module	= $params->get('width_module', "100%");
$margin	= $params->get('margin', "0");
$menu	= $app->getMenu();
$active	= $menu->getActive();
$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
$path	= isset($active) ? $active->tree : array();
$showAll	= $params->get('showAllChildren');
$class_sfx	= htmlspecialchars($params->get('class_sfx'));
$show_title_directory = $params->get('show_title_directory',"0");
$title_directory = $params->get('title_directory',"Extensions Directory");

if(count($list)) {
	require JModuleHelper::getLayoutPath('mod_joombig_menu_tree', $params->get('layout', 'default'));
}
