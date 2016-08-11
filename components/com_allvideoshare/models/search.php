<?php

/*
 * @version		$Id: search.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );

class AllVideoShareModelSearch extends AllVideoShareModel {
	
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
	
	function getsearch($rc) {
		 $mainframe = JFactory::getApplication();		  
		 $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $rc, 'int');
		 $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		 $search = $mainframe->getUserStateFromRequest('avssearch', 'avssearch', '', 'string');	
		 $search = JString::strtolower($search);	
		 
		 // In case limit has been changed, adjust it
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);
		 	
         $db = JFactory::getDBO();
		 $escaped = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->escape( $search, true ) : $db->getEscaped( $search, true );
		 $searchWord = $db->Quote( '%'.$escaped.'%', false );
         $query = "SELECT * FROM #__allvideoshare_videos WHERE published=1 AND (title LIKE $searchWord OR category LIKE $searchWord OR tags LIKE $searchWord)";
		 $query .= " ORDER BY ordering";
         $db->setQuery($query);
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