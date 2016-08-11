<?php

/*
 * @version		$Id: helper.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined('_JEXEC') or die('Restricted access');

class AllVideoSharePlayerHelper {   
	
	public static function getVideoID( $params ) {		
		$videoid = $params->get('videoid');	
		
		if($videoid != 'latest' && $videoid != 'popular') {
			return $videoid;
		}
		
        $db = JFactory::getDBO();
		$query = "SELECT id FROM #__allvideoshare_videos WHERE published=1";
		switch( $videoid ) {
		 	case 'latest' :
		 		$query .= ' ORDER BY id DESC LIMIT 1';
				break;
			case 'popular' :
				$query .= ' ORDER BY views DESC LIMIT 1';
				break;
		}
        $db->setQuery( $query );
        $output = $db->loadObjectList();
		
		if(count($output)) {
        	return $output[0]->id;
		} else {
			return 0;
		}
	}	
		
}