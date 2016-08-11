<?php

/*
 * @version		$Id: helper.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined('_JEXEC') or die('Restricted access');

class AllVideoShareGalleryHelper {

    public static function getItems( $params ) {
		$itm = array();		
		$itm["type"] = $params->get('type');		
		$itm["rows"] = $params->get('rows');
		$itm["columns"] = $params->get('columns');
		$itm["thumb_width"] = $params->get('thumb_width');
		$itm["thumb_height"] = $params->get('thumb_height');
		$itm["orderby"] = $params->get('orderby');
		$itm["link"] = $params->get('link');
		$itm["more"] = $params->get('more');
		$itm["catslg"] = $params->get('category');
		
		$db = JFactory::getDBO();
		
		if($itm["type"] == 'videos') {
			$itm["category"] = AllVideoShareGalleryHelper::getcategory($params->get('category'));
			
			$query = "SELECT * FROM #__allvideoshare_videos WHERE published=1";
			if($itm["category"]) {
				$query .= " AND category=" . $db->quote( $itm["category"] );
			}
			
			if(JRequest::getCmd('slg')) {
				$query .= " AND slug!=" . $db->Quote(str_replace(":", "-", JRequest::getVar('slg')));
			}
			
			if($itm["orderby"] == 'latest') {
				$query .= ' ORDER BY id DESC';
			} else if($itm["orderby"] == 'popular') {
				$query .= ' ORDER BY views DESC';
			} else if($itm["orderby"] == 'featured') {
				$query .= ' AND featured=1 ORDER BY ordering';
			} else if($itm["orderby"] == 'random') {
				$query .= ' ORDER BY RAND()';
			} else {
				$query .= ' ORDER BY ordering';
			}			
			
		} else {
			$query = "SELECT * FROM #__allvideoshare_categories WHERE published=1 AND parent=0";
			
			if($itm["orderby"] == 'latest') {
				$query .= ' ORDER BY id DESC';
			} else if($itm["orderby"] == 'random') {
				$query .= ' ORDER BY RAND()';
			} else {
				$query .= ' ORDER BY ordering';
			}
		}
		
		$db->setQuery( $query );
       	$output = $db->loadObjectList();
		$itm["data"] = $output;
			
        return $itm;
    }
	
	public static function getcategory( $slug ) {
		if($slug == "0" || $slug == "") return '';
		
        $db = JFactory::getDBO();
		$query = "SELECT name FROM #__allvideoshare_categories WHERE slug=" . $db->quote( $slug );
        $db->setQuery( $query );
        $output = $db->loadObjectList();
        return $output[0]->name;
	}
	
	public static function isResponsive() {
        $db = JFactory::getDBO();
        $db->setQuery( 'SELECT responsive FROM #__allvideoshare_config' );
        return $db->loadResult();
	}
		
}