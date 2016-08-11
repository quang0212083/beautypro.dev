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

class modhdwplayeruploadHelper {

    public static function getItems( $params ) {
		$itm = array();
		$itm["ffmpeg"] = $params->get('ffmpeg');
		$itm["youtube"] = $params->get('youtube');
		$itm["dailymotion"] = $params->get('dailymotion');
		$itm["vimeo"] = $params->get('vimeo');
		$itm["rtmp"] = $params->get('rtmp');
		$itm["smil"] = $params->get('smil');
		$itm["lighttpd"] = $params->get('lighttpd');
		$itm["bitgravity"] = $params->get('bitgravity');
        return $itm;
    }
	
	public static function getvideos() {
    	 $db = JFactory::getDBO();		 
		 $user = JFactory::getUser();		 
		 $currUser = $user->get('username');		 
		 $query = "SELECT * FROM #__hdwplayer_videos WHERE user='$currUser'";
		 $query .= " ORDER BY category,ordering";
    	 $db->setQuery ( $query );
    	 $output   = $db->loadObjectList();
         return($output);
	}
	
	public static function getcategories() {
         $db = JFactory::getDBO();
		 $query = 'SELECT * FROM #__hdwplayer_category';
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
	
	public static function getrow($uid) {
		 $db = JFactory::getDBO();
         $query = "SELECT * FROM #__hdwplayer_videos WHERE id=$uid";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output[0]);
	}
	
}

?>