<?php
/**
 * ------------------------------------------------------------------------
 * JA Newsticker Module for J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lang = jFactory::getLanguage();
$basepath = JURI::root(true).'/modules/' . $module->module . '/assets/';

$doc->addStyleSheet($basepath.'style.css');
//load override css
$templatepath = 'templates/'.$app->getTemplate().'/css/'.$module->module.'.css';
if(file_exists(JPATH_SITE . '/' . $templatepath)) {
	$doc->addStyleSheet(JURI::root(true).'/'.$templatepath);
}
if($lang->isRTL() == 1){
	$doc->addScript($basepath.'script.rtl.js');
}else{
	$doc->addScript($basepath.'script.js');
}
