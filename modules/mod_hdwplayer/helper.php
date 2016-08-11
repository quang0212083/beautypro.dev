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

class modhdwplayerHelper {

    public static function getItems( $params ) {
		$itm = array();
		$itm["width"]  = $params->get('width');
		$itm["height"] = $params->get('height');
		
		$category = (array) $params->get('categories');
		$numeric = false;
		
		foreach ($category as $element) {
    		if (is_numeric($element)) {
        		$numeric = true;
				break;
    		} else {
        		$numeric = false;
    		}
		}
		
		if($numeric) {				
			$category = implode(" OR ", $category);
			$db = JFactory::getDBO();		
			$query = "SELECT name FROM #__hdwplayer_category WHERE id=".$category;
       		$db->setQuery( $query );
       		$output =  $db->loadObjectList();
		
			$row = array();
			for ($i=0, $n=count($output); $i < $n; $i++) {
				$row[$i] = $output[$i]->name;
			}
			
			$itm["categories"] = $row;						
		} else {		
		    $itm["categories"] = $category;			
		}
	
		$itm["autoStart"] = $params->get('autoplay');		
		$itm["skinMode"] = $params->get('skinmode');
		$itm["playListAutoStart"] = $params->get('playlistautostart');
		$itm["playListOpen"] = $params->get('playlistopen');
		$itm["playListRandom"] = $params->get('playlistrandom');
		$itm["buffer"] = $params->get('buffer');
		$itm["volumeLevel"] = $params->get('volumelevel');
		$itm["stretch"] = $params->get('stretch');		
		$itm["controlBar"] = $params->get('controlbar');
		$itm["playPauseDock"] = $params->get('playpausedock');
		$itm["progressBar"] = $params->get('progressbar');
		$itm["timerDock"] = $params->get('timerdock');
		$itm["shareDock"] = $params->get('sharedock');
		$itm["volumeDock"] = $params->get('volumedock');
		$itm["fullScreenDock"] = $params->get('fullscreendock');
		$itm["playDock"] = $params->get('playdock');
		$itm["playList"] = $params->get('playlist');		
		$itm["showtitle"] = $params->get('showtitle');
		$itm["showdescription"] = $params->get('showdescription');
		$itm["autodetect"] = $params->get('autodetect');
		
        return $itm;
    }
}

?>