<?php

/*
 * @version		$Id: player.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );
require_once( JPATH_ROOT.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'ismobile.php' );

class AllVideoShareModelPlayer extends AllVideoShareModel {

    var $width, $height, $responsive, $video;

    function __construct($width = -1, $height = -1) {
		$this->width = $width;
		$this->height = $height;
		 
		parent::__construct();
    }
	
	function buildPlayer( $videoid = 1, $playerid = 1, $autodetect = 0 ) {
		 $option = JRequest::getCmd('option');
		 $view = JRequest::getCmd('view');
		 
		 if($option == 'com_allvideoshare' && $view == 'category') {
		 	$autodetect = 0;
		 }
		 
		 if(JRequest::getVar('slg') && $autodetect == 1) {
		 	$video = $this->getvideobyslug();
		 } else {
		 	$video = $this->getvideobyid( $videoid );
		 }
		 
		 if(!$video) return;
		 
		 $player = $this->getplayerbyid( $playerid );
		 
		 if($this->width == -1) $this->width = $player->width;
		 if($this->height == -1) $this->height = $player->height;
		 $this->responsive = $this->isResponsive();		 
		 $this->video = $video;
		 
		 if($video->type == 'thirdparty') {
		    $__unit  = ($this->responsive == 1) ? '' : 'px';
		 	$result  = '<div style="width:' . $this->width . $__unit . '; height:' . $this->height . $__unit . ';">';
		 	$result .= $video->thirdparty;
			$result .= '</div>';
		 } else {		    
			if(preg_match('/msie 10/i', $_SERVER['HTTP_USER_AGENT']) || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0') !== false) {
		    	$result = $this->gethtmlplayer( $video );
			} else {
				$flashvars = 'base='.JURI::root().'&amp;vid=' . $video->id . '&amp;pid=' . $playerid . '&amp;sef=' . $this->avs_rewrite_enabled();
				if( $lang = JRequest::getCmd('lang', '') ) {
					$flashvars .= '&amp;lang=' . $lang;
				}
				$detect = new IsMobile();			
		    	$result = $detect->isMobile() ? $this->gethtmlplayer( $video ) : $this->getflashplayer( $player, $video, $flashvars );	
			}
		 }		
		 
		 $this->updateviews( $video->slug );
		 
		 return $result;
	}
	
	function avs_rewrite_enabled() {
    	$url = 'index.php?option=com_allvideoshare&view=config';
    	return (JRoute::_($url) != $url) ? 1 : 0;
	}
	
	function isResponsive() {
         $db = JFactory::getDBO();
         $db->setQuery( 'SELECT responsive FROM #__allvideoshare_config' );
		 $result = $db->loadResult();
		 
		 if($result == 1)	{
		 	$this->width = $this->height = '100%';
		 }
		 
		 return $result;
	}
	
	function getflashplayer( $player, $video, $flashvars ) {
		 $this->buildCustomMeta( $player, $video );
		 
		 $className = $this->responsive ? 'avs_player_responsive' : 'avs_player';
		 $result  = '<div class="'.$className.'">';
		 $result .= '<object id="player" name="player" width="' . $this->width . '" height="' . $this->height . '">';
    	 $result .= '<param name="movie" value="' . JURI::root() . 'components/com_allvideoshare/player.swf?random=' . rand() . '" />';
    	 $result .= '<param name="wmode" value="opaque" />';
    	 $result .= '<param name="allowfullscreen" value="true" />';
    	 $result .= '<param name="allowscriptaccess" value="always" />';
    	 $result .= '<param name="flashvars" value="' . $flashvars . '" />';
    	 $result .= '<object type="application/x-shockwave-flash" data="' . JURI::root() . 'components/com_allvideoshare/player.swf?random=' . rand() . '" width="' . $this->width . '" height="' . $this->height . '">';
      	 $result .= '<param name="movie" value="' . JURI::root() . 'components/com_allvideoshare/player.swf?random=' . rand() . '" />';
      	 $result .= '<param name="wmode" value="opaque" />';
      	 $result .= '<param name="allowfullscreen" value="true" />';
      	 $result .= '<param name="allowscriptaccess" value="always" />';
      	 $result .= '<param name="flashvars" value="' . $flashvars . '" />';
    	 $result .= '</object>';
  	 	 $result .= '</object>';
		 $result .= '</div>';
		 
		 return $result;
	}
	
	function gethtmlplayer( $video ) {
		 if($video->type == 'youtube') {
	     	$url_string = parse_url($video->video, PHP_URL_QUERY);
  	    	parse_str($url_string, $args);
			$className = $this->responsive ? 'avs_player_responsive' : 'avs_player';
			$result  = '<div class="'.$className.'">';
	    	$result .= '<iframe title="YouTube Video Player" width="'.$this->width.'" height="'.$this->height.'" ';
			$result .= 'src="http://www.youtube.com/embed/'.$args['v'].'" frameborder="0" allowfullscreen></iframe>';
			$result .= '</div>';
		 } else {
		 	$attrs  = ' onclick="this.play();" controls';
			$attrs .= $video->preview ? ' poster="' . $video->preview . '"' : '';
			$attrs .= $this->responsive ? '' : ' width="' . $this->width . '" height="' . $this->height . '"';
			
	    	$result  = '<video'.$attrs.'>';
  	    	$result .= '<source src="'.$video->video.'" />';
			$result .= '</video>';
         }
		 
		 return $result;
	}
	
	function getvideobyid( $id ) {
         $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_videos WHERE id=" . $db->Quote( $id );
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return $output ? $output[0] : false;
	}
	
	function getvideobyslug() {		 
         $db = JFactory::getDBO();
		 $slug = str_replace(":", "-", JRequest::getVar('slg'));
         $query = "SELECT * FROM #__allvideoshare_videos WHERE slug=" . $db->Quote( $slug );
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return $output ? $output[0] : false;
	}
	
	function getplayerbyid( $id ) {
         $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_players WHERE id=" . $db->Quote( $id );
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output[0]);
	}
	
	function buildCustomMeta( $player, $video ) {
		 $swf = JURI::root().'components/com_allvideoshare/player.swf?autoStart=true&random=' . rand();
		 $swf .= '&base='.urlencode( JURI::root() ).'&vid=' . $video->id . '&pid=' . $player->id;
		 
		 $doc = JFactory::getDocument();
         $doc->addCustomTag( '<meta property="og:video" content="'.$swf.'" />' );
		 $doc->addCustomTag( '<meta property="og:video:type" content="application/x-shockwave-flash" />' );
		 $doc->addCustomTag( '<meta property="og:video:width" content="560" />' );
         $doc->addCustomTag( '<meta property="og:video:height" content="340" />' );
         $doc->addCustomTag( '<meta property="og:title" content="'.$video->title.'" />' );
         $doc->addCustomTag( '<meta property="og:image" content="'.$video->thumb.'" />' );		 
	}
	
	function updateviews( $slug ) {
		$session = JFactory::getSession();
		$db = JFactory::getDBO();
		$avs_arr = array();
		$ses_arr = array();
		
		if($session->get('avs_arr')) {
			$ses_arr = $session->get('avs_arr');
		}
		
		if(!in_array($slug, $ses_arr)) {
	    	$avs_arr = $ses_arr;
		    $avs_arr[] = $slug;
				
		 	$mainframe = JFactory::getApplication();	     	    
		 	$query = "SELECT views FROM #__allvideoshare_videos WHERE slug=".$db->Quote( $slug );
    	 	$db->setQuery ( $query );
    	 	$output = $db->loadObjectList();
		 
			if($output) {
				$count = $output[0]->views + 1;
			} else {
				$count = 1;
			}
	 
		 	$query = "UPDATE #__allvideoshare_videos SET views=".$count." WHERE slug=".$db->Quote( $slug );
    	 	$db->setQuery ( $query );
		 	$db->query();
		 
		 	$session->set('avs_arr', $avs_arr);
		}
	}
		
}