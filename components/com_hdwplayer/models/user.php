<?php

/*
 * @version		$Id: user.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class HdwplayerModelUser extends HdwplayerModel {

	function __construct() {
		parent::__construct();
    }
	
	function save()	{
	  $mainframe =  JFactory::getApplication();	
	  $session   = JFactory::getSession();
	  $user      = JFactory::getUser();  
	  $row       = JTable::getInstance('hdwplayervideos', 'Table');
	  $cid       =  JRequest::getVar( 'cid', array(0), '', 'array' );
      $id        =  $cid[0];
      $row->load($id);
	  
      if(!$row->bind(JRequest::get('post'))) {
		JError::raiseError(500, $row->getError() );
	  }
	  
	  $row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
	
	  if($row->type == 'General Upload') {
	  	jimport('joomla.filesystem.folder');
	    jimport('joomla.filesystem.file');
		
		if(!JFolder::exists(HDWPLAYER_UPLOAD_BASE)) {
			JFolder::create(HDWPLAYER_UPLOAD_BASE);
		}
		
	  	$row->video   = $this->general_upload('uploadvideo');
		$row->hdvideo = $this->general_upload('uploadhdvideo');
	  	$row->preview = $this->general_upload('uploadpreview');
	  	$row->thumb   = $this->general_upload('uploadthumb');
	  }
	  
	  if($row->type == 'FFMPEG Upload') {
	    jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		if(!JFolder::exists(HDWPLAYER_UPLOAD_BASE)) {
			JFolder::create(HDWPLAYER_UPLOAD_BASE);
		}
		
		$ffmpeg       = $this->ffmpeg_upload('uploadvideo');
		$row->video   = $ffmpeg['video'];
	  	$row->preview = $ffmpeg['preview'];
	  	$row->thumb   = $ffmpeg['thumb'];
	  }
	  
	  if($row->type == 'Youtube Videos') {
	  	$row->video = str_replace ( 'youtu.be/', 'www.youtube.com/watch?v=', $row->video );
	     parse_str( parse_url( $row->video, PHP_URL_QUERY ), $youtubeID );
		 $row->video   = 'http://www.youtube.com/watch?v=' . $youtubeID['v'];
         $row->thumb   = 'http://img.youtube.com/vi/'.$youtubeID['v'].'/default.jpg';
		 $row->preview = 'http://img.youtube.com/vi/'.$youtubeID['v'].'/0.jpg';
	  }
	  
	  
	  if($row->type == 'Vimeo Videos')
	  {
	  	if($row->preview == '' || $row->thumb == ''){
	  		$link = $row->video;
	  		$link = str_replace('http://vimeo.com/', 'http://vimeo.com/api/v2/video/', $link) . '.php';
	  		$html_returned = unserialize(file_get_contents($link));
	  		if($row->thumb == ''){
	  			$row->thumb = $html_returned[0]['thumbnail_medium'];
	  		}
	  		if($row->preview == ''){
	  			$row->preview = $html_returned[0]['thumbnail_large'];
	  		}
	  	}
	  }
	  if($row->type == 'Dailymotion Videos')
	  {
	  	if($row->thumb == '' || $row->preview == ''){
	  		$url = $row->video;
	  		$id = strtok(basename($url), '_');
	  		$ch = curl_init();
	  		curl_setopt($ch, CURLOPT_URL, "https://api.dailymotion.com/video/$id?fields=thumbnail_medium_url,thumbnail_url");
	  		curl_setopt($ch, CURLOPT_HEADER, 0);
	  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	  		$output = curl_exec($ch);
	  		curl_close($ch);
	  		$output = json_decode($output);
	  		if($row->thumb == ''){
	  			$row->thumb = $output->thumbnail_medium_url;
	  		}
	  		if($row->preview == ''){
	  			$row->preview = $output->thumbnail_url;
	  		}
	  	}
	  }
	  
	  $row->user = (string) $user->get('username');
  
  	  if(!$row->thumb && !JRequest::getVar('uploadthumb')) {
		$row->thumb = 'http://img.youtube.com/vi/default.jpg';
	  }
	  
	  $row->reorder( "category='" . $row->category . "'" );
	  
	  if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	  }
	 
	  $task = JRequest::getCmd('task');	  
	  $mainframe->redirect( $session->get('target') );	  
	}
	
	function general_upload($filename) {
	  $file = @JFile::makeSafe($_FILES[$filename]['name']);
	  $temp = @$_FILES[$filename]['tmp_name'];
	  
      if($file != "") {
     	 if(JFile::upload($temp, HDWPLAYER_UPLOAD_BASE.$file)) {
		 	return HDWPLAYER_UPLOAD_BASEURL.$file;
		 } else {
		 	JError::raiseWarning(21, 'Error Occured While Uploading!');
			return false;
		 }
	  }
	  return '';
		
	}
	
	function ffmpeg_upload($filename) {
	  $dat           = array();
	  $file          = @JFile::makeSafe($_FILES[$filename]['name']);
	  $ext           = end(explode(".", $file));	  
	  $temp          = @$_FILES[$filename]['tmp_name'];
	  
	  $inputFile     = HDWPLAYER_UPLOAD_BASE.$file;
      $content       = $this->RemoveExtension($file);
	  $outputFile    = HDWPLAYER_UPLOAD_BASE.$content.'.flv';
	  $outputThumb   = HDWPLAYER_UPLOAD_BASE.$content.'_thumb.jpg';
	  $outputPreview = HDWPLAYER_UPLOAD_BASE.$content.'_preview.jpg';	  
	  $qtFile        = HDWPLAYER_UPLOAD_BASE.$content.'_qt.mp4';
	  $outputHdFile  = HDWPLAYER_UPLOAD_BASE.$content.'.mp4';

	  $exe           = $this->getexe();
	  $ffmpeg        = $exe[0]->ffmpeg;
	  $flvtool2      = $exe[0]->flvtool2;
	  $qtfaststart   = $exe[0]->qtfaststart;
	  
	  if(!file_exists(HDWPLAYER_UPLOAD_BASE.$file)) {
     	 if ( JFile::upload($temp, HDWPLAYER_UPLOAD_BASE.$file)) {
		   
		   	if($ext != '.flv') {
		   		$command  = $ffmpeg." -i ".$inputFile." -sameq -ar 44100 ".$outputFile;
           		exec($command);		   		
			}
			
			if($ext != '.mp4' && $qtfaststart != '') {
				$command  = $ffmpeg . " -i " . $inputFile . " -vcodec libx264 -acodec libfaac -sameq " . $qtFile;
           		exec($command);
				
				$command  = $qtfaststart . " "  . $qtFile .  " " . $outputHdFile;
           		exec($command);
				
				unlink($qtFile);		   		
			}

		   	$command      = $flvtool2 . " -U " . $outputFile;
           	exec($command);
			
			$command      = $ffmpeg." -ss 3 -i ".$outputFile." -f image2 -vframes 1 -s 114x74 ".$outputThumb;
           	exec($command);
			
			$command      = $ffmpeg." -ss 3 -i ".$outputFile." -f image2 -vframes 1 -s 320x240 ".$outputPreview;
           	exec($command);
			
			if(file_exists(HDWPLAYER_UPLOAD_BASE.$content.'.flv')) {
				$dat['video']   = HDWPLAYER_UPLOAD_BASEURL.$content.'.flv';
				$dat['thumb']   = HDWPLAYER_UPLOAD_BASEURL.$content.'_thumb.jpg';
				$dat['preview'] = HDWPLAYER_UPLOAD_BASEURL.$content.'_preview.jpg';
			}
			
			if(file_exists(HDWPLAYER_UPLOAD_BASE.$content.'.mp4')) {
				$dat['hdvideo'] = HDWPLAYER_UPLOAD_BASEURL.$content.'.mp4';				
			}
			
			return $dat;
				   
      	  } else {
		 	JError::raiseWarning(21, 'Error Occured While Uploading!');
			return false;
		  }		 
		}		
	}
	
	function getexe() {
         $db     = JFactory::getDBO();
         $query  = "SELECT ffmpeg, flvtool2, qtfaststart FROM #__hdwplayer_settings";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output);
	}
		
	function RemoveExtension($strName) {  
     	$ext = strrchr($strName, '.');  

     	if($ext !== false) {  
         	$strName = substr($strName, 0, -strlen($ext));  
     	}  
		
     	return $strName;  
	}
	
	function delete() {
	    $mainframe = JFactory::getApplication();
		$session   = JFactory::getSession();
		$uid       = JRequest::getCmd('uid');

        $db        = JFactory::getDBO();
        $query     = "DELETE FROM #__hdwplayer_videos WHERE id=$uid";
        $db->setQuery( $query );
        $db->query();

        $mainframe->redirect( $session->get('target') );
	}
	 
	
}

?>