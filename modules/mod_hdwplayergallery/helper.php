<?php

/*
 * @version		$Id: helper.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined('_JEXEC') or die('Restricted access');

class modhdwplayergalleryHelper {

    public static function getItems( $params, $catid ) {
		$itm  = array();
		$itm["link"] = $params->get('link');
		$itm["type"] = $params->get('type');
		$itm["rows"] = $params->get('rows');
		$itm["columns"] = $params->get('columns');
		$itm["thumbwidth"] = $params->get('thumbwidth');
		$itm["thumbheight"] = $params->get('thumbheight');				
		$limit = $itm["rows"] * $itm["columns"];
		
		if( (substr(JVERSION,0,3) == '1.5') ) {
			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');	
		} else {
			$limitstart = JRequest::getVar(strtolower( $params->get('type') ).'limitstart', 0, '', 'int');	
		}		
		
		if(($limitstart + $limit) > $params->get('limit') && $params->get('limit') != '') {
			$limit = $params->get('limit') - $limitstart;
		}	
		
		$db = JFactory::getDBO();	
		
		if($catid) {
			$category = $catid;
		} else {
			$category = (array) $params->get('categories');
			$category = implode('","', $category);
		}
				
		if($itm['type'] == 'Category' && !JRequest::getCmd('catid')) {			
			$query  = "SELECT * FROM #__hdwplayer_category WHERE published=1";
			$query .= ($category != '') ? ' AND name IN ("'.$category.'")' : '';
		} else {
			$query = modhdwplayergalleryHelper::buildQuery('videos', $category, $itm["type"]);       		
		}
		
		$db->setQuery( $query, $limitstart, $limit );
       	$output = $db->loadObjectList();
		$itm["gallery"] = $output;
		
        return $itm;
    }
	
	public static function getPagination( $params, $catid ) {
		$db = JFactory::getDBO();	
		 
		if($catid) {
			$category = $catid;
		} else {
			$category = (array) $params->get('categories');
			$category = implode('","', $category);
		}
		 
		if($params->get('type') == 'Category' && !JRequest::getCmd('catid')) {			
			$query  = "SELECT COUNT(id) FROM #__hdwplayer_category WHERE published=1";
			$query .= ($category != '') ? ' AND name IN ("'.$category.'")' : '';
		} else {
			$query = modhdwplayergalleryHelper::buildQuery('count', $category, $params->get('type'));       		
		}
		
		$db->setQuery( $query );
       	$output = $db->loadResult();
		$total = $output;

		if($total > $params->get('limit') && $params->get('limit') != '') {
			$total = $params->get('limit');
		}
		 
		$rows = $params->get('rows');
		$columns = $params->get('columns');		
		$limit = $rows * $columns;
		 
		jimport( 'joomla.html.pagination' );
		 
		if( (substr(JVERSION,0,3) == '1.5') ) {
			$limitstart = JRequest::getVar('limitstart', 0, '', 'int');	
			$pageNav = new JPagination($total, $limitstart, $limit);
		} else {
			$limitstart = JRequest::getVar(strtolower( $params->get('type') ).'limitstart', 0, '', 'int');		 	
		 	$pageNav = new JPagination($total, $limitstart, $limit, strtolower( $params->get('type') ));
		}	 
		 
        return($pageNav);
  	}
	
	public static function buildQuery($type, $category, $orderby) {
		if($type == 'count') {
			$query = "SELECT COUNT(id) FROM #__hdwplayer_videos WHERE published=1";
		} else {
			$query = "SELECT * FROM #__hdwplayer_videos WHERE published=1";
		}		
		$query .= ($category != '') ? ' AND category IN ("'.$category.'")' : '';
		if(JRequest::getCmd('wid')) $query .= ' AND id!='.JRequest::getCmd('wid');
		
		switch($orderby) {
			case 'Latest' :
				$query .= ' ORDER BY id DESC';
				break;
			case 'Popular' :
				$query .= ' ORDER BY views DESC';
				break;
			case 'Featured' :
				$query .= " AND featured=1";
				break;
			default :
				$query .= ' ORDER BY category,ordering';				
		}		
		
		return $query;
	}
	
	public static function googleads() {
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_googleads";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
		 
         return($output[0]);
	}
	
	public static function getCategory() {
		 if($id = JRequest::getCmd('catid')) {
		 	$db     = JFactory::getDBO();
         	$query  = "SELECT name FROM #__hdwplayer_category WHERE id=" . $db->quote( $id );
         	$db->setQuery( $query );
         	$output = $db->loadObjectList();
		 
         	return($output[0]->name);
		}
		
		return 0;
	}
	
}

?>