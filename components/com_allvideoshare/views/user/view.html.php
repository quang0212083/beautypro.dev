<?php

/*
 * @version		$Id: view.html.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'views'.DS.'view.php' );

class AllVideoShareViewUser extends AllVideoShareView {

    function display($tpl = null) {
	    $mainframe = JFactory::getApplication();
		$model = $this->getModel();
		
		$config = $model->getconfig();
		$this->assignRef('config', $config);
		
		$userobj = JFactory::getUser();	
		$user = $userobj->get('username');
		$this->assignRef('user', $user);
		 
		$videos = $model->getvideos( $user );
		$this->assignRef('videos', $videos);
		
		$pagination = $model->getpagination( $user );
		$this->assignRef('pagination', $pagination);
		
		$video = $model->getrow();
		$this->assignRef('video', $video);
		
		$cat = $video ? $video->category : '';
		
		$category_options[] = JHTML::_('select.option', '', JText::_('SELECT_A_CATEGORY'));
		$categories = $model->getcategories();		 
		foreach ( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
		}
		$category = JHTML::_('select.genericlist', $category_options, 'category', '', 'value', 'text', $cat);
		$this->assignRef('category', $category);
		
		// Adds parameter handling
		$params = $mainframe->getParams();
		$this->assignRef('params',	$params);
		
		if(substr(JVERSION,0,3) != '1.5') {
			$doc = JFactory::getDocument();
			if ($params->get('menu-meta_description')) {
				$doc->setDescription($params->get('menu-meta_description'));
			}

			if ($params->get('menu-meta_keywords')) {
				$doc->setMetadata('keywords', $params->get('menu-meta_keywords'));
			}

			if ($params->get('robots')) {
				$doc->setMetadata('robots', $params->get('robots'));
			}
		}
				
        parent::display($tpl);
    }
	
}