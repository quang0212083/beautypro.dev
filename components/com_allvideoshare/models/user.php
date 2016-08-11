<?php

/*
 * @version		$Id: user.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );
require_once( JPATH_COMPONENT_ADMINISTRATOR.DS.'etc'.DS.'upload.php' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class AllVideoShareModelUser extends AllVideoShareModel {

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
	
	function getvideos($user) {
		 $mainframe = JFactory::getApplication();
		 $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', 10, 'int');
		 $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		 
		 // In case limit has been changed, adjust it
		 $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
		 $this->setState('limit', $limit);
		 $this->setState('limitstart', $limitstart);
		 
    	 $db = JFactory::getDBO();		 
		 $query = "SELECT * FROM #__allvideoshare_videos WHERE user=" . $db->quote( $user );
		 $query .= " ORDER BY ordering";
    	 $db->setQuery ( $query, $limitstart, $limit );
    	 $output = $db->loadObjectList();
         return($output);
	}
	
	function getpagination( $user ) {
    	 jimport( 'joomla.html.pagination' );
		 $pageNav = new JPagination($this->gettotal( $user ), $this->getState('limitstart'), $this->getState('limit'));
         return($pageNav);
	}
	
	function gettotal( $user ) {
         $db = JFactory::getDBO();
         $query = "SELECT COUNT(*) FROM #__allvideoshare_videos WHERE user=" . $db->quote( $user );
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
		 if( $mitems ) {
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
	
	function savevideo() {
		 $mainframe = JFactory::getApplication();
	  	 $row = JTable::getInstance('Videos', 'AllVideoShareTable');
	  	 $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      	 $id = $cid[0];
      	 $row->load($id);
	
      	 if(!$row->bind(JRequest::get('post'))) {
		 	JError::raiseError(500, $row->getError());
	  	 }	  	 
		 
		 jimport( 'joomla.filter.output' );
		 $row->title = AllVideoShareFallback::safeString($row->title);
	  	 if(!$row->slug) $row->slug = $row->title;		
		 $row->slug = JFilterOutput::stringURLSafe($row->slug);
		 
	  	 $row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWHTML);
		 $row->thirdparty = JRequest::getVar('thirdparty', '', 'post', 'string', JREQUEST_ALLOWRAW);
	  
	  	 if($row->type != 'youtube') {
			$dir = JFilterOutput::stringURLSafe( $row->category );
		 	if(!JFolder::exists(ALLVIDEOSHARE_UPLOAD_BASE . $dir . DS)) {
				JFolder::create(ALLVIDEOSHARE_UPLOAD_BASE . $dir . DS);
			}
		
			if($row->type == 'upload') {
				$row->video = AllVideoShareUpload::doUpload('upload_video', $dir);
				$row->hd = AllVideoShareUpload::doUpload('upload_hd', $dir);
			}
			
			if($row->type != 'upload') {
				$row->video = AllVideoShareFallback::safeString($row->video);
				$row->hd = AllVideoShareFallback::safeString($row->hd);
			}
			
			if($row->type == 'rtmp') {
				$row->streamer = AllVideoShareFallback::safeString($row->streamer);
			}
			
	  		$row->thumb = AllVideoShareUpload::doUpload('upload_thumb', $dir);
			$row->preview = AllVideoShareUpload::doUpload('upload_preview', $dir);
	  	 }
	  	 
		 if($row->type == 'youtube') {
	      	$v = $this->getYouTubeVideoId($row->video);
		 	$row->video = 'http://www.youtube.com/watch?v=' . $v;
			if(!$row->thumb) {
          		$row->thumb = 'http://img.youtube.com/vi/'.$v.'/default.jpg';
			}
			if(!$row->preview) {
		 		$row->preview = 'http://img.youtube.com/vi/'.$v.'/0.jpg';
			}
	     }
	  
	  	 if(!$row->thumb && !JRequest::getCmd('upload_thumb')) {
			$row->thumb = 'http://img.youtube.com/vi/default.jpg';
		 }		
		 
		 $row->reorder( "category='" . $row->category . "'" );
		 
	  	 if(!$row->store()){
			JError::raiseError(500, $row->getError() );
	  	 }

		 $itemId = '';
		 if(JRequest::getInt('Itemid')) {
		 	$itemId = '&Itemid=' . JRequest::getInt('Itemid');
		 }
		 $link = JRoute::_( 'index.php?option=com_allvideoshare&view=user' . $itemId, false );
		 
		 $mainframe->redirect($link, JText::_('SAVED'));	 
	}
	
	function getYouTubeVideoId($url) {
    	$video_id = false;
    	$url = parse_url($url);
    	if(strcasecmp($url['host'], 'youtu.be') === 0) {
        	$video_id = substr($url['path'], 1);
    	} else if(strcasecmp($url['host'], 'www.youtube.com') === 0) {
        	if(isset($url['query'])) {
           		parse_str($url['query'], $url['query']);
            	if(isset($url['query']['v'])) {
               		$video_id = $url['query']['v'];
            	}
        	}
			
        	if($video_id == false) {
            	$url['path'] = explode('/', substr($url['path'], 1));
            	if(in_array($url['path'][0], array('e', 'embed', 'v'))) {
                	$video_id = $url['path'][1];
            	}
        	}
    	}
		
    	return $video_id;
	}
	
	function deletevideo() {
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
		
         $itemId = '';
		 if(JRequest::getInt('Itemid')) {
		 	$itemId = '&Itemid=' . JRequest::getInt('Itemid');
		 }
		 $link = JRoute::_( 'index.php?option=com_allvideoshare&view=user' . $itemId, false );
		 
		 $mainframe->redirect($link ); 
	}
		
}