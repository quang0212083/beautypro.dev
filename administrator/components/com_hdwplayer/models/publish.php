<?php
/*
 * @version		$Id: publish.php 1.0 2013-08-21 $
 * @package		Joomla
 * @subpackage	com_hdwplayer
 * @copyright   Copyright (C) 2013 HDWPlayer. All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

//No direct acesss
defined('_JEXEC') or die();

// Import Joomla! libraries
jimport('joomla.application.component.model');

class HdwplayerModelpublish extends JModelLegacy {

	function publish($task)
    {
            $mainframe = JFactory::getApplication();
			$cid       = JRequest::getVar( 'cid', array(), '', 'array' );
            $publish   = ( $task == 'publish') ? 1 : 0;

            $task      = JRequest::getvar('task','','get','var');

            if($task=="videos")
            {
                $tblname ="hdwplayervideos";
                $taskname="videos";
            }
            elseif($task=="category")
            {
                $tblname ="hdwplayercategory";
                $taskname="category";
            }
			
            $reviewTable = JTable::getInstance($tblname, 'Table');
            $reviewTable->publish($cid, $publish);
            $mainframe->redirect( 'index.php?option=com_hdwplayer&task='.$taskname);
    }
    
}

?>