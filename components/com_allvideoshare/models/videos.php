<?php

/*
 * @version		$Id: videos.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );

class AllVideoShareModelVideos extends AllVideoShareModel {

	var $_total;
	
    function __construct() {
		parent::__construct();
    }
	
	function getconfig() {
         $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_config";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output);
	}
	
	function getvideos($rc) {
		 $mainframe = JFactory::getApplication();	
		 $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $rc, 'int');
		 $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		 
		 // In case limit has been changed, adjust it
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);
		 
         $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_videos WHERE published=1";
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
         $db->setQuery( $query );
         $items = $db->loadObjectList();
		 
         $this->_total = count($items);
		 if($this->_total > $rc) {
			return array_slice($items, $limitstart, $limit);
		 } else {
			return $items;
		 }
	}
	
	function getpagination() {
    	 jimport( 'joomla.html.pagination' );
		 $pageNav = new JPagination($this->_total, $this->getState('limitstart'), $this->getState('limit'));
         return($pageNav);
	}
		
}