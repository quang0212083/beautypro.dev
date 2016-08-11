<?php

/*
 * @version		$Id: playlist.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );

class AllVideoShareModelPlayList extends AllVideoShareModel {

	function __construct() {
		parent::__construct();
    }
	
	function buildXml() {
		ob_clean();
		header("content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
		echo '<playlist>'."\n";
		echo $this->buildNodes();
		echo '</playlist>'."\n";
		exit();
	}
	
	function buildNodes() {
		$items = $this->getitems();	
		$link  = $this->getlink();	
		$node  = '';
		
		for ($i = 0, $n = count($items); $i < $n; $i++) {
			$item  = $items[$i];
			
			$node .= '<item>'."\n";
			$node .= '<thumb>'.$item->thumb.'</thumb>'."\n";
			$node .= '<title><![CDATA['.$item->title.']]></title>'."\n";
			$node .= '<link>'.JRoute::_($link.'slg='.$item->slug).'</link>'."\n";
			$node .= '</item>'."\n";
		}
		
		return $node;
	}
	
	function getitems() {
		 $category = $this->getcategory();
		 
         $db       = JFactory::getDBO();
         $query    =  "SELECT * FROM #__allvideoshare_videos WHERE published=1 AND category=" . $db->Quote( $category ) . " AND id!=" . $db->Quote( JRequest::getInt('vid') );
         $db->setQuery( $query );
         $output   = $db->loadObjectList();
         return($output);
	}
	
	function getcategory() {
         $db     = JFactory::getDBO();
         $query  =  "SELECT * FROM #__allvideoshare_videos WHERE id=" . $db->Quote( JRequest::getInt('vid') );
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output[0]->category);
	}
	
	function getlink() {
		 $link   = '';
		 
         $db     = JFactory::getDBO();
         $query  =  "SELECT * FROM #__allvideoshare_players WHERE id=" . $db->Quote( JRequest::getInt('pid') );
         $db->setQuery( $query );
         $output = $db->loadObjectList();
		 
		 if($output[0]->customplayerpage) {
		 	$link = $output[0]->customplayerpage;
		 } else {
		 	$link = 'index.php?option=com_allvideoshare&view=video';
			$link.= JRequest::getInt('Itemid') ? '&Itemid=' . JRequest::getInt('Itemid') : '';
		 }
		 
		 $qs = (!strpos($link, '?')) ? '?' : '&';
		 
		 return($link.$qs);
	}

}