<?php

/*
 * @version		$Id: controller.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

if(version_compare(JVERSION, '3.0', 'ge')) {

    class AllVideoShareController extends JControllerLegacy {
	
        public function display($cachable = false, $urlparams = array()) {
            parent::display($cachable, $urlparams);
        }
			
    }

} else if(version_compare(JVERSION, '2.5', 'ge')) {

    class AllVideoShareController extends JController {
	
        public function display($cachable = false, $urlparams = false) {
            parent::display($cachable, $urlparams);
        }

    }

} else {

    class AllVideoShareController extends JController {
	
        public function display($cachable = false) {
            parent::display($cachable);
        }

    }

}