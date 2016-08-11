<?php

/*
 * @version		$Id: categories.php 2.3.0 2014-06-21 $
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

class AllVideoShareModelCategories extends AllVideoShareModel {
	
	var $data = array();
	
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
		
		$this->buildData($this->getparentid(), $spcr = '');		
		if(!$limit) {
			return $this->data;
		} else {
			return array_slice($this->data, $limitstart, $limit);
		}
	}
	
	function getparentid() {
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		
		if ($filter_category > -1) {
			$db = JFactory::getDBO();
			$query = "SELECT parent FROM #__allvideoshare_categories WHERE id=" . $filter_category;
			$db->setQuery($query);
			$output = $db->loadRow();
			
			return $output[0];
		}
		
		return 0;			
	}
	
	function buildData($parent, $spcr = '') {		 
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = JFactory::getDBO();		 
		 
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$setparent = 1;
		 
		$query = "SELECT * FROM #__allvideoshare_categories";	
				
		$where = array();		 
				 
		if ($filter_state > - 1) {
			$where[] = "published={$filter_state}";
			$setparent = 0;			
		}
		
		if ($filter_category > -1) {
			$tree = $this->getCategoryTree( $filter_category );
	    	$where[] = 'id IN ('.implode(',', $tree).')';
		 }
		 
		if ( $search ) {
			$escaped = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->escape( $search, true ) : $db->getEscaped( $search, true );
			$where[] = 'LOWER(name) LIKE '.$db->Quote( '%'.$escaped.'%', false );
			$setparent = 0;	
		}
		
		if ( $setparent ) {
			$where[] = "parent=$parent";
		}	

		$where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );		 
		$query .= $where;		 
		$query .= " ORDER BY ordering ASC"; 
		 	    
        $db->setQuery( $query );
   		$cats= $db->loadObjectList();
		
		$c = 0;
		$count = count($cats);

        if($count){		
            foreach($cats as $cat){
				$cat->up = ($c == 0) ? 0 : 1;
                $cat->down = ($c + 1 == $count) ? 0 : 1;
				$cat->spcr = $spcr."<sup>L</sup>&nbsp;&nbsp;";
		
				$this->data[] = $cat;
				$c++;
				if ( $setparent ) {
                	$this->buildData( $cat->id, $spcr."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" );
				}
            }
        }
	}	
	
	function getpagination() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $total = $this->gettotal();
		 $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		 $limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
     
    	 jimport( 'joomla.html.pagination' );
		 $pageNav = new JPagination($total, $limitstart, $limit);
         return($pageNav);
	}
	
	function gettotal() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		 $filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		 $search = JString::strtolower($search);
		 
         $db = JFactory::getDBO();
         $query = "SELECT COUNT(*) FROM #__allvideoshare_categories";
		 $where = array();
		 
		 if ($filter_state > -1) {
			$where[] = "published={$filter_state}";
		 }

		if ($filter_category > -1) {
			$tree = $this->getCategoryTree( $filter_category );
	    	$where[] = 'id IN ('.implode(',', $tree).')';
		 }
		 
		 if ( $search ) {
		 	$escaped = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->escape( $search, true ) : $db->getEscaped( $search, true );
			$where[] = 'LOWER(name) LIKE '.$db->Quote( '%'.$escaped.'%', false );
		 }

		 $where = ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );		 
		 $query .= $where;
		 
         $db->setQuery( $query );
         $output = $db->loadResult();
         return($output);
	}
	
	function getlists() {
		 $mainframe = JFactory::getApplication();	
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 $filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state','filter_state',-1,'int' );
		 $filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', -1, 'int');
		 $search = $mainframe->getUserStateFromRequest($option.$view.'search','search','','string');
		 $search = JString::strtolower ( $search );
     
    	 $lists = array ();
		 $lists['search'] = $search;
            
		 $filter_state_options[] = JHTML::_('select.option', -1, JText::_('SELECT_PUBLISHING_STATE'));
		 $filter_state_options[] = JHTML::_('select.option', 1, JText::_('PUBLISHED'));
		 $filter_state_options[] = JHTML::_('select.option', 0, JText::_('UNPUBLISHED'));
		 $lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);
		 
		 $category_options[] = JHTML::_('select.option', -1, JText::_('SELECT_BY_CATEGORY'));
		 $categories = $this->getcategories();
		 foreach ( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);			
			$category_options[] = JHTML::_('select.option', $item->id, $item->treename );
		 }
		 $lists['categories'] = JHTML::_('select.genericlist', $category_options, 'filter_category', 'onchange="this.form.submit();"', 'value', 'text', $filter_category);
		 
         return($lists);
	}
	
	function getcategories( $name = '' ) {
        $db = JFactory::getDBO();
		$query = 'SELECT * FROM #__allvideoshare_categories';
		if($name) {
			$query .= ' WHERE name!=' . $db->quote( $name );
		}
		$query .= ' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		
		$children = array();
		if( $mitems ) {
			foreach( $mitems as $v ) {
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
        $row = JTable::getInstance('Categories', 'AllVideoShareTable');
        $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
        $id  = $cid[0];
        $row->load($id);

        return $row;
	}
	
    function save() {
		$mainframe = JFactory::getApplication();
	  	$row = JTable::getInstance('Categories', 'AllVideoShareTable');
	  	$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      	$id = $cid[0];
      	$row->load($id);
	
      	if(!$row->bind(JRequest::get('post'))) {
			JError::raiseError(500, $row->getError() );
	  	}
	  
	   	jimport( 'joomla.filter.output' );
	  	//$row->name = JRequest::getVar('name', '', 'post', 'string', JREQUEST_ALLOWHTML);
		$row->name = JString::trim($row->name);
	  	if(!$row->slug) $row->slug = $row->name;
		$row->slug = JFilterOutput::stringURLSafe($row->slug);
	  
	  	if($row->type == 'upload') {
			$dir = JFilterOutput::stringURLSafe( $row->name );	
			if(!JFolder::exists(ALLVIDEOSHARE_UPLOAD_BASE . $dir . DS)) {
				JFolder::create(ALLVIDEOSHARE_UPLOAD_BASE . $dir . DS);
			}
		
	  		$row->thumb = AllVideoShareUpload::doUpload('upload_thumb', $dir);
	  	}
	  
	    if(!$row->thumb && !JRequest::getCmd('upload_thumb')) {
			$row->thumb = 'http://img.youtube.com/vi/default.jpg';
		}
		 
		$row->reorder( "parent=$row->parent" );
		
	  	if(!$row->store()) {
			JError::raiseError(500, $row->getError() );
	  	}

	  	switch (JRequest::getCmd('task')) {
        	case 'apply':
            	$msg  = JText::_('CHANGES_SAVED');
             	$link = 'index.php?option=com_allvideoshare&view=categories&task=edit&'. AllVideoShareFallback::getToken() .'=1&'.'cid[]='.$row->id;
             	break;
        	case 'save':
        	default:
              	$msg  = JText::_('SAVED');
              	$link = 'index.php?option=com_allvideoshare&view=categories';
              	break;
      	}

	  	$mainframe->redirect($link, $msg, 'message');
	}

    function cancel() {
		$mainframe = JFactory::getApplication();
		 
		$link = 'index.php?option=com_allvideoshare&view=categories';
	    $mainframe->redirect($link);
    }
	
	function delete() {
		$mainframe = JFactory::getApplication();
        $cid = JRequest::getVar( 'cid', array(), '', 'array' );
        $db = JFactory::getDBO();
        $cids = implode( ',', $this->getCategoryTree($cid) );
		
        if(count($cid)) {
            $query = "DELETE FROM #__allvideoshare_categories WHERE id IN ( $cids )";
            $db->setQuery( $query );
            if (!$db->query()) {
                echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
            }
        }
		
        $mainframe->redirect( 'index.php?option=com_allvideoshare&view=categories' );	
	}

    function publish() {
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
        $publish = ( JRequest::getCmd('task') == 'publish' ) ? 1 : 0;
			
        $reviewTable = JTable::getInstance('Categories', 'AllVideoShareTable');
        $reviewTable->publish($cid, $publish);
        $mainframe->redirect( 'index.php?option=com_allvideoshare&view=categories' );	        
    }

	function order($id, $inc) {
		$mainframe  = JFactory::getApplication();
		
    	$row = JTable::getInstance('Categories', 'AllVideoShareTable');
		$row->load( $id );
		$row->move( $inc, 'parent = '.$row->parent );
		
		$mainframe->redirect( 'index.php?option=com_allvideoshare&view=categories', JText::_('NEW_ORDERING_SAVED'), 'message' );
    }
	
    function saveorder() {
		$mainframe = JFactory::getApplication();

		$db	= JFactory::getDBO();
		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
		$total = count( $cid );
		$order = JRequest::getVar( 'order', array(0), '', 'array' );
		JArrayHelper::toInteger($order, array(0));
		 
		$row = JTable::getInstance('Categories', 'AllVideoShareTable');
		$groupings = array();
		for( $i=0; $i < $total; $i++ ) {
			$row->load( (int) $cid[$i] );
			$groupings[] = $row->parent;
 			if ($row->ordering != $order[$i]) {
				$row->ordering  = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg() );
				}
			}
		} 
		$groupings = array_unique($groupings);
	
		foreach ($groupings as $group) {
			$row->reorder('parent = "'.$group.'"');
		}
 
		$mainframe->redirect('index.php?option=com_allvideoshare&view=categories', JText::_('NEW_ORDERING_SAVED'), 'message');                
    }
	
	function getCategoryTree( $ids ) {
		$db = JFactory::getDBO();			
		$ids = (array) $ids;
		JArrayHelper::toInteger($ids);
		$catid  = array_unique($ids);
		sort($ids);
		
		$array = $ids;				
		while(count($array)){
			$query = "SELECT id FROM #__allvideoshare_categories 
					WHERE parent IN (".implode(',', $array).") 
					AND id NOT IN (".implode(',', $array).") ";
			$db->setQuery($query);
			$array = (ALLVIDEOSHARE_JVERSION == '3.0') ? $db->loadColumn() : $db->loadResultArray();
			$ids = array_merge($ids, $array);
		}
		JArrayHelper::toInteger($ids);
		$ids = array_unique($ids);			
			
		return $ids;
	}

}