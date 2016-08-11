<?php

/*
 * @version		$Id: approval.php 2.3.0 2014-06-21 $
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

class AllVideoShareModelApproval extends AllVideoShareModel {

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
		 $filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', '', 'string');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower($search);
		 
	     $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_videos";
		 $where = array();		 
		 $where[] = "published=0";
		 
		 if ($filter_category && $filter_category != JText::_('SELECT_BY_CATEGORY')) {
			$where[] = 'category='.$db->Quote($filter_category);
		 }
		
		 if ( $search ) {
		 	$escaped = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->escape( $search, true ) : $db->getEscaped( $search, true );
			$where[] = 'LOWER(title) LIKE '.$db->Quote( '%'.$escaped.'%', false );
		 }

		 $where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );		 
		 $query .= $where;
		 
         $db->setQuery( $query, $limitstart, $limit );
         $output = $db->loadObjectList();
		 
         return($output);
	}
	
	function gettotal() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', '', 'string');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower($search);
		 
         $db = JFactory::getDBO();
         $query = "SELECT COUNT(*) FROM #__allvideoshare_videos";
		 $where = array();
		 $where[] = "published=0";

		 if ($filter_category && $filter_category != JText::_('SELECT_BY_CATEGORY')) {
			$where[] = 'category='.$db->Quote($filter_category);
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
		 
		 $filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', '', 'string');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search','search','','string');
		 $search = JString::strtolower ( $search );
     
    	 $lists = array();
		 $lists['search'] = $search;
		 
		 $category_options[] = JHTML::_('select.option', '', JText::_('SELECT_BY_CATEGORY'));
		 $categories = $this->getcategories();		 
		 foreach ( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
		 }
		 $lists['categories'] = JHTML::_('select.genericlist', $category_options, 'filter_category', 'onchange="this.form.submit();"', 'value', 'text', $filter_category);
		 
         return($lists);
	}
	
	function getapproval() {
         $db = JFactory::getDBO();
         $query  = "SELECT COUNT(*) FROM #__allvideoshare_videos WHERE published=0";
         $db->setQuery( $query );
         $output = $db->loadResult();
         return($output);
	}
	
	function getcategories() {
         $db = JFactory::getDBO();
		 $query = 'SELECT * FROM #__allvideoshare_categories';
		 $db->setQuery( $query );
		 $mitems = $db->loadObjectList();
		
		 $children = array();
		 if ( $mitems ) {
			foreach ( $mitems as $v ) {
				$v->title = $v->name;
				$v->parent_id = $v->parent;
				$pt = $v->parent;				
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		 }
		
		 $list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );		
		 return $list;
	}
	
	function getrow() {
     	 $db = JFactory::getDBO();
         $row = JTable::getInstance('Videos', 'AllVideoShareTable');
         $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
         $id = $cid[0];
         $row->load($id);

         return $row;
	}
	
	function save() {
		 $mainframe = JFactory::getApplication();
	  	 $row = JTable::getInstance('Videos', 'AllVideoShareTable');
	  	 $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      	 $id = $cid[0];
      	 $row->load($id);
	
      	 if(!$row->bind(JRequest::get('post'))) {
		 	JError::raiseError(500, $row->getError() );
	  	 }
	  
	   	 jimport( 'joomla.filter.output' );
	  	 $row->title = JRequest::getVar('title', '', 'post', 'string', JREQUEST_ALLOWHTML);
		 $row->title = JString::trim($row->title);
	  	 if(!$row->slug) $row->slug = $row->title;
		 $row->slug = JFilterOutput::stringURLSafe($row->slug);
	  	 $row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);
		 $row->thirdparty = JRequest::getVar('thirdparty', '', 'post', 'string', JREQUEST_ALLOWRAW);
	  
	  	 if($row->type == 'upload') {
		 	if(!JFolder::exists(ALLVIDEOSHARE_UPLOAD_BASE)) {
				JFolder::create(ALLVIDEOSHARE_UPLOAD_BASE);
			}
		
			$row->video = AllVideoShareUpload::doUpload('upload_video');
			$row->hd = AllVideoShareUpload::doUpload('upload_hd');
	  		$row->thumb = AllVideoShareUpload::doUpload('upload_thumb');
			$row->preview = AllVideoShareUpload::doUpload('upload_preview');
	  	 }
		 
		 if($row->type == 'youtube') {
	      	parse_str( parse_url( $row->video, PHP_URL_QUERY ), $youtubeID );
			$v = @$youtubeID['v'] ? $youtubeID['v'] : '';
		 	$row->video = 'http://www.youtube.com/watch?v=' . $v;
			if(!$row->thumb) {
          		$row->thumb = 'http://img.youtube.com/vi/'.$v.'/default.jpg';
			}
			if(!$row->preview) {
		 		$row->preview = 'http://img.youtube.com/vi/'.$v.'/0.jpg';
			}
	     }
		 
		 if($row->type != 'upload' && $row->type != 'youtube') {
			$row->video = JRequest::getVar('video', '', 'post', 'string', JREQUEST_ALLOWHTML);
			$row->hd = JRequest::getVar('hd', '', 'post', 'string', JREQUEST_ALLOWHTML);
		 }
		 
		 if($row->type == 'rtmp') {
			$row->streamer = JRequest::getVar('streamer', '', 'post', 'string', JREQUEST_ALLOWHTML);
		 }
	  
	  	 if(!$row->thumb && !JRequest::getCmd('upload_thumb')) {
			$row->thumb = 'http://img.youtube.com/vi/default.jpg';
		 }
		 
		 $row->reorder( "category='" . $row->category . "'" );
		 
	  	 if(!$row->store()) {
			JError::raiseError(500, $row->getError() );
	  	 }

      	 $msg = JText::_('ADDED_TO_VIDEOS_SECTION');
         $link = 'index.php?option=com_allvideoshare&view=approval';

	  	 $mainframe->redirect($link, $msg, 'message');
	}
	
	function cancel() {
		 $mainframe = JFactory::getApplication();
		 
		 $link = 'index.php?option=com_allvideoshare&view=approval';
	     $mainframe->redirect($link);
	}	

	function delete() {
		$mainframe = JFactory::getApplication();
        $cid = JRequest::getVar( 'cid', array(), '', 'array' );
        $db = JFactory::getDBO();
        $cids = implode( ',', $cid );
        if(count($cid)) {
            $query = "DELETE FROM #__allvideoshare_videos WHERE id IN ( $cids )";
            $db->setQuery( $query );
            if (!$db->query()) {
                echo "<script> alert('".$db->getErrorMsg()."');window.history.go(-1); </script>\n";
            }
        }
		
        $mainframe->redirect( 'index.php?option=com_allvideoshare&view=approval' );
	}
	
	function publish() {
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
        $publish = ( JRequest::getCmd('task') == 'publish' ) ? 1 : 0;
			
        $reviewTable = JTable::getInstance('Videos', 'AllVideoShareTable');
        $reviewTable->publish($cid, $publish);
        $mainframe->redirect( 'index.php?option=com_allvideoshare&view=approval' );
    }
		
}