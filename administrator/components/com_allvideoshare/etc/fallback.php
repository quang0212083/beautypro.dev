<?php

/*
 * @version		$Id: fallback.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if(version_compare(JVERSION, '3.0', '<')) {
	jimport('joomla.html.pane');
}

class AllVideoShareFallback {

	public static function startTabs() {
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$options = array(
    			'onActive' => 'function(title, description){
        			description.setStyle("display", "block");
        			title.addClass("open").removeClass("closed");
    			}',
    			'onBackground' => 'function(title, description){
        			description.setStyle("display", "none");
        			title.addClass("closed").removeClass("open");
    			}',
    			'startOffset' => 0,
    			'useCookie' => true
			);

			echo JHtml::_('tabs.start', 'tab_group_id', $options);
		} else {			
			$pane = JPane::getInstance('tabs');	
			echo $pane->startPane('content-pane');
		}
	}
	
	public static function initPanel($title, $id, $end = false) {
		if(version_compare(JVERSION, '3.0', 'ge')) {
			echo JHtml::_('tabs.panel', $title, $id);
		} else {
			$pane = JPane::getInstance('tabs');	
			if($end == true) {
				echo $pane->endPanel();
			}
			echo $pane->startPanel($title, $id);
		}
	}
	
	public static function endTabs() {
		if(version_compare(JVERSION, '3.0', 'ge')) {
			echo JHtml::_('tabs.end');
		} else {
			$pane = JPane::getInstance('tabs');
			echo $pane->endPanel(); 
			echo $pane->endPane(); 
		}
	}
	
	public static function getToken() {	
		if(version_compare(JVERSION, '1.6.0', '<')) {
			return JUtility::getToken();
		} else {
			return JSession::getFormToken();
		}	
	}
	
	public static function checkAll($data = '') {
		if(version_compare(JVERSION, '3.0', 'ge')) {
			return '<input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" />';
		} else {
			return '<input type="checkbox" name="toggle" value="" onClick="checkAll(' . count($data) . ');" />';
		}
	}
	
	public static function getEditor($name = '', $value = '') {
		if(version_compare(JVERSION, '1.6.0', '<')) {
			return '<textarea name="'.$name.'" rows="6" cols="50" >'.$value.'</textarea>';
		} else {
			$editor = JFactory::getEditor();
			$params = array('mode'=> 'advanced');
			return $editor->display($name, $value, '350', '175', '20', '20', 1, null, null, null, $params);
		}	
	}
	
	public static function safeString($value = '') {
		$value = JString::trim($value);
		return htmlspecialchars($value);
	}
	
}