<?php

/*
 * @version		$Id: model.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

if(version_compare(JVERSION, '3.0', 'ge')) {

    class AllVideoShareModel extends JModelLegacy {
	
        public static function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path, $prefix);
        }

    }

} else if(version_compare(JVERSION, '2.5', 'ge')) {

    class AllVideoShareModel extends JModel {
	
        public static function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path, $prefix);
        }

    }

} else {

    class AllVideoShareModel extends JModel {
	
        public function addIncludePath($path = '', $prefix = '') {
            return parent::addIncludePath($path);
        }

    }

}