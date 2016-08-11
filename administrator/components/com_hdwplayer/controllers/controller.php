<?php

/*
 * @version		$Id: controller.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

if (version_compare(JVERSION, '3.0', 'ge')) {

    class HdwplayerController extends JControllerLegacy {
	
        public function display($cachable = false, $urlparams = array()) {
            parent::display($cachable, $urlparams);
        }
		
    }

} else if (version_compare(JVERSION, '2.5', 'ge')) {

    class HdwplayerController extends JController {
	
        public function display($cachable = false, $urlparams = false) {
            parent::display($cachable, $urlparams);
        }

    }

} else {

    class HdwplayerController extends JController {
	
        public function display($cachable = false) {
            parent::display($cachable);
        }

    }

}

?>