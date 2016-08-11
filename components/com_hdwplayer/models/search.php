<?php

/*
 * @version		$Id: search.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class HdwplayerModelSearch extends HdwplayerModel {

	function __construct() {
		parent::__construct();
    }
	
	function getsettings() {
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_settings WHERE id=1";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return $output[0];
	}
	
	function getsearch() {
        $db = JFactory::getDBO();	
		$search = JRequest::getVar('hdwplayersearch', '', 'post', 'string');		
        $query = "SELECT * FROM #__hdwplayer_videos WHERE published=1 AND (title LIKE '%$search%' OR category LIKE '%$search%' OR tags LIKE '%$search%')";
        $db->setQuery($query);
        $output = $db->loadObjectList();		
        return($output);
    }
	
}

?>