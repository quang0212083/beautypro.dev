<?php

/*
 * @version		$Id: category.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class HdwplayerModelCategory extends HdwplayerModel {

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
	
	function getcategory() {
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_category WHERE published=1 AND id=" . $db->Quote( JRequest::getCmd('wid') );
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return $output ? $output[0] : '';
	}
	
	function getvideos($category, $rc) {
		 $mainframe  = JFactory::getApplication();	
		 $limit      = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $rc, 'int');
		 $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		 
		 // In case limit has been changed, adjust it
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);
		 
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_videos WHERE published=1 AND category=" . $db->Quote($category);
		 switch(JRequest::getCmd('orderby')) {
		 	case 'latest' :
		 		$query .= ' ORDER BY id DESC';
				break;
			case 'popular' :
				$query .= ' ORDER BY views DESC';
				break;
			case 'featured' :
				$query .= ' AND featured=1 ORDER BY ordering';
				break;
			case 'random' :
				$query .= ' ORDER BY RAND()';
				break;
			default :
				$query .= " ORDER BY ordering";
		 }
         $db->setQuery( $query, $limitstart, $limit );
         $output = $db->loadObjectList();
         return($output);
	}
	
	function getpagination($category) {
    	 jimport( 'joomla.html.pagination' );
		 $pageNav    = new JPagination($this->gettotal($category), $this->getState('limitstart'), $this->getState('limit'));
         return($pageNav);
	}
	
	function gettotal($category) {
         $db    = JFactory::getDBO();
         $query = "SELECT COUNT(*) FROM #__hdwplayer_videos WHERE published=1 AND category=" . $db->quote( $category );	 
         $db->setQuery( $query );
         $output = $db->loadResult();
         return($output);
	}
	
	function getsubcategories($rc) {
    	$db = JFactory::getDBO();
        $query = "SELECT * FROM #__hdwplayer_category WHERE published=1 AND parent=" . $db->quote( JRequest::getCmd("wid") );
        switch(JRequest::getCmd('orderby')) {
		 	case 'latest' :
		 		$query .= ' ORDER BY id DESC';
				break;
			case 'random' :
				$query .= ' ORDER BY RAND()';
				break;
			default :
				$query .= " ORDER BY ordering";
		}
		$query .= " LIMIT $rc";
        $db->setQuery( $query );
        $output = $db->loadObjectList();
        
		return($output);
	}
	
}

?>