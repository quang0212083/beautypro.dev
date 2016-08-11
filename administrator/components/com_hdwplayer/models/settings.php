<?php

/*
 * @version		$Id: settings.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class HdwplayerModelSettings extends HdwplayerModel {

    function __construct() {
		parent::__construct();
    }
	
	function getsettings() {
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_settings";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output[0]);
	}
	
	function getskin() {
         $db     = JFactory::getDBO();
         $query  = "SELECT * FROM #__hdwplayer_skin";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return($output);
	}
	
	function save() {
	  $row = JTable::getInstance('hdwplayersettings', 'Table');
	  $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      $id  = $cid[0];
      $row->load($id);

      if(!$row->bind(JRequest::get('post'))) {
		JError::raiseError(500, $row->getError() );
	  }
	  
	  $row->logo = $this->upload('logo');
	
	  if(!$row->store()) {
		JError::raiseError(500, $row->getError() );
	  }  
	  
	  $this->saveskin();
	}
	
	function saveskin() {
	  $mainframe = JFactory::getApplication();
	  $row       = JTable::getInstance('hdwplayerskin', 'Table');
	  $cid       = JRequest::getVar( 'cid', array(0), '', 'array' );
      $id        = $cid[0];
      $row->load($id);

      if(!$row->bind(JRequest::get('post'))) {
		JError::raiseError(500, $row->getError() );
	  }
	
	  if(!$row->store()) {
		JError::raiseError(500, $row->getError() );
	  }
	  
	  $msg  = 'Saved';
      $link = 'index.php?option=com_hdwplayer&view=settings';

	  $mainframe->redirect($link, $msg);	  
	}
	
	function upload($filename) {	
	  jimport('joomla.filesystem.folder');
	  jimport('joomla.filesystem.file');
	  
	  if(!JFolder::exists(HDWPLAYER_UPLOAD_BASE)) {
			JFolder::create(HDWPLAYER_UPLOAD_BASE);
	  }
		
	  $file = JFile::makeSafe($_FILES[$filename]['name']);
	  $temp = $_FILES[$filename]['tmp_name'];
	  
      if($file != "") {
     	 if(JFile::upload($temp, HDWPLAYER_UPLOAD_BASE.$file)) {
		 	return HDWPLAYER_UPLOAD_BASEURL.$file;
		 } else {
		 	JError::raiseWarning(21, 'Error Occured While Uploading!');
			return false;
		 }
	  }	
	  	  
	}
	
}

?>