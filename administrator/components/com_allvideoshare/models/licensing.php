<?php

/*
 * @version		$Id: licensing.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );
require_once(JPATH_COMPONENT.DS.'etc'.DS.'upload.php');

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class AllVideoShareModelLicensing extends AllVideoShareModel {

    function __construct() {
		parent::__construct();
    }
	
	function getdata() {
     	 $db = JFactory::getDBO();
         $row = JTable::getInstance('Licensing', 'AllVideoShareTable');
         $row->load(1);

         return $row;
	}
	
	function save()	{
		 $mainframe = JFactory::getApplication();
	  	 $row = JTable::getInstance('Licensing', 'AllVideoShareTable');
	  	 $cid = JRequest::getVar( 'cid', array(0), '', 'array' );
      	 $id = $cid[0];
      	 $row->load($id);
	
      	 if(!$row->bind(JRequest::get('post'))) {
		 	JError::raiseError(500, $row->getError() );
	  	 }
	  
	  	 if($row->type == 'upload') {
		 	if(!JFolder::exists(ALLVIDEOSHARE_UPLOAD_BASE)) {
				JFolder::create(ALLVIDEOSHARE_UPLOAD_BASE);
			}
		
			$row->logo = AllVideoShareUpload::doUpload('upload_logo');
	  	 }
	  
	  	 if(!$row->store()) {
			JError::raiseError(500, $row->getError() );
	  	 }

		 $msg  = JText::_('SAVED');
         $link = 'index.php?option=com_allvideoshare&view=licensing';
		 
		 $mainframe->redirect($link, $msg, 'message'); 	 
	}
	
}