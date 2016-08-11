<?php

/*
 * @version		$Id: view.html.php 3.0 2012-10-10 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access 
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class HdwplayerViewDefault extends HdwplayerView {

	function display($tpl = null) {
		$mainframe = JFactory::getApplication();
		$model 	   = $this->getModel();
		
		$settings = $model->getsettings();
		$this->assignRef('settings', $settings);
		
		$params = $mainframe->getParams();
		$this->assignRef('params',	$params);
		
		$player_width  = $params->get('player_width', $settings->width);
		$this->assignRef('player_width', $player_width);
		
		$player_height = $params->get('player_height', $settings->height);
		$this->assignRef('player_height', $player_height);
		
		$show_title = $params->get('show_title', $settings->title);
		$this->assignRef('show_title', $show_title);
		
		$show_description = $params->get('show_description', $settings->description);
		$this->assignRef('show_description', $show_description);
		
		if(substr(JVERSION,0,3) != '1.5') {
			$doc = JFactory::getDocument();
			if ($params->get('menu-meta_description')) {
				$doc->setDescription($params->get('menu-meta_description'));
			}

			if ($params->get('menu-meta_keywords')) {
				$doc->setMetadata('keywords', $params->get('menu-meta_keywords'));
			}

			if ($params->get('robots')) {
				$doc->setMetadata('robots', $params->get('robots'));
			}
		}
				
        parent::display($tpl);
    }
	
}

?>