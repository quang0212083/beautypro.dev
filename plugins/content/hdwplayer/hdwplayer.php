<?php

/*
 * @version		$Id: webplayer.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentHdwplayer extends JPlugin {

	function plgContentHdwplayer( &$subject, $params ) {
		parent::__construct( $subject, $params );
	}

	function onContentPrepare($context, &$article, &$params, $page=0) {	
		$this->onPrepareContent( $article, $params, $page );
	}

	function onPrepareContent( &$row, &$params, $limitstart ) {
		// simple performance check to determine whether bot should process further
		if ( JString::strpos( $row->text, 'hdwplayer' ) === false ) {
			return true;
		}
		
		// expression to search for
 		$regex = '/{hdwplayer\s*.*?}/i';
		
		// find all instances of plugin and put in $matches
		preg_match_all( $regex, $row->text, $matches );

		// Number of plugins
 		$count = count( $matches[0] );
		
		$this->plgContentProcessPositions( $row, $matches, $count, $regex);
	}
	
	function plgContentProcessPositions ( $row, $matches, $count, $regex) {
 		for ( $i=0; $i < $count; $i++ )	{
 			$load  = str_replace( '{hdwplayer', '', $matches[0][$i] );
 			$load  = str_replace( '}', '', $load );
			$load  = trim( $load );
			$load  = explode(" ",$load);
			$load  = implode("&",$load);
 			
			$modules	= $this->plgContentLoadPosition($load);
			$row->text 	= str_replace($matches[0][$i], $modules, $row->text );
 		}

  		// removes tags without matching module positions
		$row->text = preg_replace( $regex, '', $row->text );
	}
	
	function castAsArray($datas) {
	    $element = array();
		for ($i=0, $n=count($datas); $i < $n; $i++) {
			$row = $datas[$i];
		    $row = explode("=",$row );
            $element[$row[0]] = $row[1];
		}
		return $element;
	}	
	
	function plgContentLoadPosition($load) {		
	    $wid = $width = $height = $video = $type = $category = $htmlNode = $ext = $contents = '';
	    parse_str($load);
		$width      = ($width    == '') ? 640 : $width;
		$height     = ($height   == '') ? 360 : $height;  
		$category   = ($category == '') ? ''  :str_replace(',', '%2C', $category);
		$flashvars  = 'baseJ='.JURI::root().'&';
		$load       = str_replace(',', '%2C', $load);
		$load       = str_replace('autoplay', 'autoStart', $load);
		$flashvars .= $load;
		$flashvars .= JRequest::getCmd('wid') ? '&id='.JRequest::getCmd('wid')  : '' ;
		$flashvars .= (JRequest::getCmd('wid') || $wid == '') ? '' : '&id='.$wid;
		
		
		require JPATH_ROOT.DS.'components'.DS.'com_hdwplayer'.DS.'models'.DS.'embed.php';		
		$document = JFactory::getDocument();
		$document->addScript( JURI::root() . "components/com_hdwplayer/js/hdwplayer.js" );
		
		return $contents;
	}

}
?>