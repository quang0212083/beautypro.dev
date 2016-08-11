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

class HdwplayerViewSearch extends HdwplayerView {

	function display($tpl = null) {
		$model 	= $this->getModel();
		
		$settings = $model->getsettings();
        $this->assignRef('settings', $settings);
		
        $search = $model->getsearch();
        $this->assignRef('search', $search);
				
        parent::display($tpl);
    }
	
}

?>