<?php

/*
 * @version		$Id: view.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

if(version_compare(JVERSION, '3.0', 'ge')) {

    class AllVideoShareView extends JViewLegacy { }

} else {

    class AllVideoShareView extends JView { }
	
}