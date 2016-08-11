<?php

/*
 * @version		$Id: categories.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'controllers'.DS.'controller.php' );

class AllVideoShareControllerCategories extends AllVideoShareController {

   function __construct() {
        parent::__construct();
    }
	
	function categories() {
	    $document = JFactory::getDocument();
		$vType = $document->getType();
		
	    $view = $this->getView('categories', $vType);		
        $model = $this->getModel('categories');		
        $view->setModel($model, true);
		
		$view->setLayout('default');
		$view->display();
	}
	
	function add() {
		if(JRequest::checkToken( 'get' )) {
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JRequest::checkToken() or die( 'Invalid Token' );
		}
		
		JRequest::setVar( 'hidemainmenu', 1 );
		
		$document = JFactory::getDocument();
		$vType = $document->getType();
		
	    $view = $this->getView('categories' , $vType);
        $model = $this->getModel('categories');		
        $view->setModel($model, true);
		
		$view->setLayout('add');
		$view->add();
	}
	
	function edit()	{
		if(JRequest::checkToken( 'get' )) {
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JRequest::checkToken() or die( 'Invalid Token' );
		}
		
		JRequest::setVar( 'hidemainmenu', 1 );
		
		$document = JFactory::getDocument();
		$vType = $document->getType();
		
	    $view = $this->getView('categories' , $vType);
        $model = $this->getModel('categories');		
        $view->setModel($model, true);
		
		$view->setLayout('edit');
		$view->edit();
	}
		
	function delete() {
		if(JRequest::checkToken( 'get' )) {
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JRequest::checkToken() or die( 'Invalid Token' );
		}
		
		$model = &$this->getModel('categories');
	 	$model->delete();
	}
	
	function publish() {
		if(JRequest::checkToken( 'get' )) {
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JRequest::checkToken() or die( 'Invalid Token' );
		}
		
		$model = $this->getModel('categories');
        $model->publish();
    }
	
    function unpublish() {
        $this->publish();
    }	
	
	function save()	{
		if(JRequest::checkToken( 'get' )) {
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JRequest::checkToken() or die( 'Invalid Token' );
		}
		
		$model = $this->getModel('categories');
	  	$model->save();
	}
	
	function apply() {
		$this->save();
	}
	
	function cancel() {
		if(JRequest::checkToken( 'get' )) {
			JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
		} else {
			JRequest::checkToken() or die( 'Invalid Token' );
		}
		
		$model = $this->getModel('categories');
	    $model->cancel();
	}
	
	function saveorder() {
		$model = $this->getModel('categories');
	  	$model->saveorder();		
	}
	
	function orderup() {
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id = $cid[0];
		$model = $this->getModel('categories');
		$model->order($id, -1);
	}
	
	function orderdown() {	    
		$cid = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$id = $cid[0];
		$model = $this->getModel('categories');
		$model->order($id, 1);
	}	
		
}