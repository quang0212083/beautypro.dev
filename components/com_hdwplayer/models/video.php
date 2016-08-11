<?php

/*
 * @version		$Id: video.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class HdwplayerModelVideo extends HdwplayerModel {

	function __construct() {
		parent::__construct();
    }
	
	function getsettings() {
         $db    = JFactory::getDBO();
         $query = "SELECT * FROM #__hdwplayer_settings WHERE id=1";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output[0]);
	}
	
	function googleads() {
         $db    = JFactory::getDBO();
         $query = "SELECT * FROM #__hdwplayer_googleads WHERE id=1";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output[0]);
	}
	
	function getcategory($id) {
		$db    = JFactory::getDBO();
        $query = "SELECT category FROM #__hdwplayer_videos WHERE id=" . $db->quote( $id );
        $db->setQuery( $query );
        $output = $db->loadObjectList();

        return $output[0]->category;
	}
	
	function getrelatedvideos($category, $id, $rc) {		 
		 $mainframe  = JFactory::getApplication();
		 $limit      = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $rc, 'int');
		 $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		 
		 // In case limit has been changed, adjust it
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);	 
		 
         $db     = JFactory::getDBO();
         $query  =  "SELECT * FROM #__hdwplayer_videos WHERE published=1";
		 if($category) {
		 	$query .= " AND category=" . $db->Quote( $category );
		 }
		 $query .= " AND id!=" . $db->quote( $id );
		 
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
	
	function getpagination( $category, $id ) {
    	 jimport( 'joomla.html.pagination' );
		 $pageNav = new JPagination($this->gettotal( $category, $id ), $this->getState('limitstart'), $this->getState('limit'));
         return($pageNav);
	}
	
	function gettotal( $category, $id ) {
         $db     = JFactory::getDBO();
         $query  = "SELECT COUNT(*) FROM #__hdwplayer_videos WHERE published=1";
		 if($category) {
		 	$query .= " AND category=" . $db->Quote( $category );
		 }		 
		 if(JRequest::getCmd('orderby') == 'featured') {
		 	$query .= ' AND featured=1';
		 }
		 $query .= " AND id!=" . $db->quote( $id );
         $db->setQuery( $query );
         $output = $db->loadResult();
         return($output);
	}
	
}

?>