<?php

/*
 * @version		$Id: controller.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class HdwplayerControllerDefault extends HdwplayerController {

	function __construct() {
        if(JRequest::getCmd('view') == '') {
            JRequest::setVar('view', 'playeronly');
        }
        $this->item_type = 'Default';
        parent::__construct();
    }
	
	function playeronly() {
		$document = JFactory::getDocument();
		$vType	  = $document->getType();
	    $view     = $this->getView('default', $vType);
		
        $model    = $this->getModel('default');
        $view->setModel($model, true);

		$view->display();
	}
	
	function categories() {
		$document = JFactory::getDocument();
		$vType	  = $document->getType();
	    $view     = $this->getView('categories', $vType);
		
        $model    = $this->getModel('categories');
        $view->setModel($model, true);

		$view->display();
	}
	
	function category() {
		$document = JFactory::getDocument();
		$vType	  = $document->getType();
	    $view     = $this->getView('category', $vType);
		
        $model    = $this->getModel('category');
        $view->setModel($model, true);

		$view->display();
	}
	
	function videos() {
		$document = JFactory::getDocument();
		$vType	  = $document->getType();
	    $view     = $this->getView('videos', $vType);
		
        $model    = $this->getModel('videos');
        $view->setModel($model, true);

		$view->display();
	}
	
	function video() {
		$document = JFactory::getDocument();
		$vType	  = $document->getType();
	    $view     = $this->getView('video', $vType);
		
        $model    = $this->getModel('video');
        $view->setModel($model, true);

		$view->display();
	}
	
	function config() {
        $model    = $this->getModel('config');
        $model->getdata();
	}
	
	function playlist() {
		JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
	
        $model    = $this->getModel('playlist');
        $model->getdata();
	}
	
	function player() {
		$model    = $this->getModel('player');
        $model->getplayer();
	}
	
	function email() {
		JRequest::checkToken( 'get' ) or die( 'Invalid Token' );
	
		$model    = $this->getModel('email');
        $model->sendMail();
	}
	
	function views()
	{		
        $model = $this->getModel('views');
        $model->addview();
	}	
	
	function add()
	{		
        $model = $this->getModel('user');
        $model->save();
	}
	
	function edit()
	{		
        $this->add();
	}
	
	function delete()
	{		
        $model = $this->getModel('user');
        $model->delete();
	}
	
	function search()
	{		
        $document = JFactory::getDocument();
		$vType	  = $document->getType();
	    $view     = $this->getView('search', $vType);
		
        $model    = $this->getModel('search');
        $view->setModel($model, true);

		$view->display();
	}
	
}

?>