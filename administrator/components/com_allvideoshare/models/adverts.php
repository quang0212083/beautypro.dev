<?php

/*
 * @version		$Id: adverts.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );
require_once(JPATH_COMPONENT.DS.'etc'.DS.'upload.php');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class AllVideoShareModelAdverts extends AllVideoShareModel {

    function __construct() {
		parent::__construct();
    }
	
	function getdata() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		 $limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		 $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower($search);
		 
	     $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_adverts";
		 $where = array();
		 
		 if ($filter_state > -1) {
			$where[] = "published={$filter_state}";
		 }		 
		
		 if ( $search ) {
		 	$escaped = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->escape( $search, true ) : $db->getEscaped( $search, true );
			$where[] = 'LOWER(title) LIKE '.$db->Quote( '%'.$escaped.'%', false );
		 }

		 $where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		 
		 $query .= $where;
		 $query .= " ORDER BY id DESC";
         $db->setQuery( $query, $limitstart, $limit );
         $output = $db->loadObjectList();
		 
         return($output);
	}
	
	function gettotal() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower($search);
		 
         $db = JFactory::getDBO();
         $query = "SELECT COUNT(*) FROM #__allvideoshare_adverts";
		 $where = array();
		 
		 if ($filter_state > -1) {
			$where[] = "published={$filter_state}";
		 }
		 
		 if ( $search ) {
		 	$escaped = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->escape( $search, true ) : $db->getEscaped( $search, true );
			$where[] = 'LOWER(title) LIKE '.$db->Quote( '%'.$escaped.'%', false );
		 }

		 $where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		 $query .= $where;

         $db->setQuery( $query );
         $output = $db->loadResult();
         return($output);
	}
	
	function getpagination() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $total = $this->gettotal();
		 $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		 $limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
     
    	 jimport( 'joomla.html.pagination' );
		 $pageNav = new JPagination($total, $limitstart, $limit);
         return($pageNav);
	}
	
	function getlists() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int' );
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search','search','','string');
		 $search = JString::strtolower ( $search );
     
    	 $lists = array ();
		 $lists['search'] = $search;
            
		 $filter_state_options[] = JHTML::_('select.option', -1, JText::_('SELECT_PUBLISHING_STATE'));
		 $filter_state_options[] = JHTML::_('select.option', 1, JText::_('PUBLISHED'));
		 $filter_state_options[] = JHTML::_('select.option', 0, JText::_('UNPUBLISHED'));
		 $lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);
		 
         return($lists);
	}
	
	function getrow() {
     	 $db = JFactory::getDBO();
         $row = JTable::getInstance('Adverts', 'AllVideoShareTable');
         $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
         $id = $cid[0];
         $row->load($id);

         return $row;
	}
	
	function save() {
		 $mainframe = JFactory::getApplication();
	  	 $row = JTable::getInstance('Adverts', 'AllVideoShareTable');
	  	 $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      	 $id = $cid[0];
      	 $row->load($id);
	
      	 if(!$row->bind(JRequest::get('post'))) {
		 	JError::raiseError(500, $row->getError() );
	  	 }
	  
	   	 jimport( 'joomla.filter.output' );
		 $row->title = AllVideoShareFallback::safeString($row->title);
	  
	  	 if($row->method == 'upload') {		
			$dir = 'adverts';	
		 	if(!JFolder::exists(ALLVIDEOSHARE_UPLOAD_BASE . $dir . DS)) {
				JFolder::create(ALLVIDEOSHARE_UPLOAD_BASE . $dir . DS);
			}
		
			$row->video = AllVideoShareUpload::doUpload('upload_video', $dir);
	  	 } else {
			$row->video = AllVideoShareFallback::safeString($row->video);
		 }
		
	  	 if(!$row->store()){
			JError::raiseError(500, $row->getError() );
	  	 }

	  	 switch (JRequest::getCmd('task')) {
        	case 'apply':
            	$msg  = JText::_('CHANGES_SAVED');
             	$link = 'index.php?option=com_allvideoshare&view=adverts&task=edit&'. AllVideoShareFallback::getToken() .'=1&'.'cid[]='.$row->id;				
             	break;
        	case 'save':
        	default:
				$msg  = JText::_('SAVED');
             	$link = 'index.php?option=com_allvideoshare&view=adverts';
              	break;
      	 }
		 
		 $mainframe->redirect($link, $msg, 'message'); 	 
	}
	
	function cancel() {
		 $mainframe = JFactory::getApplication();
		 
		 $link = 'index.php?option=com_allvideoshare&view=adverts';
	     $mainframe->redirect($link);
	}	

	function delete() {
		 $mainframe = JFactory::getApplication();
         $cid = JRequest::getVar( 'cid', array(), '', 'array' );
         $db = JFactory::getDBO();
         $cids = implode( ',', $cid );
         if(count($cid)) {
            $query = "DELETE FROM #__allvideoshare_adverts WHERE id IN ( $cids )";
            $db->setQuery( $query );
            if (!$db->query()) {
                echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            }
         }
		
         $mainframe->redirect( 'index.php?option=com_allvideoshare&view=adverts' );
	}
	
	function publish() {
		 $mainframe = JFactory::getApplication();
		 $cid = JRequest::getVar( 'cid', array(), '', 'array' );
         $publish = ( JRequest::getCmd('task') == 'publish' ) ? 1 : 0;
			
         $reviewTable = JTable::getInstance('Adverts', 'AllVideoShareTable');
         $reviewTable->publish($cid, $publish);
         $mainframe->redirect( 'index.php?option=com_allvideoshare&view=adverts' );
    }
	
}