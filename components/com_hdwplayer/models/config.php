<?php

/*
 * @version		$Id: config.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class HdwplayerModelConfig extends HdwplayerModel {

	function __construct() {
		parent::__construct();
    }
	
	function getdata()
    {
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_settings";
         $db->setQuery( $query );
         $config = $db->loadObjectList();
		 
         $query  = "SELECT * FROM #__hdwplayer_skin";
         $db->setQuery( $query );
         $skin   = $db->loadObjectList();
		 
         $this->createXml($config, $skin);
	}
	
	function createXml($config, $skin)
	{
		$br = "\n";	
		ob_clean();
		header("content-type:text/xml;charset=utf-8");
		echo '<?xml version="1.0" encoding="utf-8"?>'.$br;
		echo '<config>'.$br;
		echo $this->configNodes($config[0]);
		echo $this->skinNodes($skin[0]);
		echo '</config>'.$br;
		exit();
	}
	
	function configNodes($datas) {
		$lang         = JRequest::getCmd('lang') ? '&lang='.JRequest::getCmd('lang') : '';
		$category     = JRequest::getString('category');
		$category     = str_replace(',', '%2C', $category);
		$id           = JRequest::getCmd('id');
		$playlistxml  = COM_HDWPLAYER_BASEURL."%26view=playlist%26". HdwplayerUtility::getToken() ."=1".$lang;
		$playlistxml .= ($category != '') ? '%26category='.$category : '';
		$playlistxml .= ($id       != '') ? '%26id='.$id             : '';
		$email        =  COM_HDWPLAYER_BASEURL."%26view=email%26". HdwplayerUtility::getToken() ."=1".$lang;
		$br           = "\n";
		
		
		$node        .= '<skinMode>'.$datas->skinmode.'</skinMode>'.$br;
		$node        .= '<playListXml>'.$playlistxml.'</playListXml>'.$br;
		$node        .= '<playListAutoStart>'.$this->castAsBoolean($datas->playlistautoplay).'</playListAutoStart>'.$br;
		$node        .= '<playListOpen>'.$this->castAsBoolean($datas->playlistopen).'</playListOpen>'.$br;
		$node        .= '<playListRandom>'.$this->castAsBoolean($datas->playlistrandom).'</playListRandom>'.$br;
		$node        .= '<autoStart>'.$this->castAsBoolean($datas->autoplay).'</autoStart>'.$br;
		$node        .= '<stretch>'.$datas->stretchtype.'</stretch>'.$br;
		$node        .= '<buffer>'.$datas->buffertime.'</buffer>'.$br;
		$node        .= '<volumeLevel>'.$datas->volumelevel.'</volumeLevel>'.$br;
		$node        .= '<emailPhp>'.$email.'</emailPhp>'.$br;
		
		return $node;
	}
	
	function skinNodes($datas)
	{
		$br = "\n";
		$node = '<controlBar>'.$this->castAsBoolean($datas->controlbar).'</controlBar>'.$br;
		$node.= '<playPauseDock>'.$this->castAsBoolean($datas->playpause).'</playPauseDock>'.$br;
		$node.= '<progressBar>'.$this->castAsBoolean($datas->progressbar).'</progressBar>'.$br;
		$node.= '<timerDock>'.$this->castAsBoolean($datas->timer).'</timerDock>'.$br;
		$node.= '<shareDock>'.$this->castAsBoolean($datas->share).'</shareDock>'.$br;
		$node.= '<volumeDock>'.$this->castAsBoolean($datas->volume).'</volumeDock>'.$br;
		$node.= '<fullScreenDock>'.$this->castAsBoolean($datas->fullscreen).'</fullScreenDock>'.$br;
		$node.= '<playDock>'.$this->castAsBoolean($datas->playdock).'</playDock>'.$br;
		$node.= '<playList>'.$this->castAsBoolean($datas->videogallery).'</playList>'.$br;
		
		return $node;		
	}
	
	function castAsBoolean($val){
		if($val == 1) {
	    	return 'true';
		} else {
			return 'false';
		}
	}

}

?>