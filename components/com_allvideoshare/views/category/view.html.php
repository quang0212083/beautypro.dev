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

class AllVideoShareViewCategory extends AllVideoShareView {

    function display($tpl = null) {
	    $mainframe = JFactory::getApplication();
		$model = $this->getModel();
		
		$config = $model->getconfig();
		$this->assignRef('config', $config);	
		
		// Adds parameter handling
		$params = $mainframe->getParams();
		$this->assignRef('params',	$params);	
		
		$rows = $params->get('no_of_rows', $config[0]->rows);
		$this->assignRef('rows', $rows);
		
		$cols = $params->get('no_of_cols', $config[0]->cols);
		$this->assignRef('cols', $cols);
		
		$thumb_width = $params->get('avs_thumb_width', $config[0]->thumb_width);
		$this->assignRef('thumb_width', $thumb_width);
		
		$thumb_height = $params->get('avs_thumb_height', $config[0]->thumb_height);
		$this->assignRef('thumb_height', $thumb_height);
		
		if(!$category = $model->getcategory()) {
			echo JText::_('ITEM_NOT_FOUND');
			return;
		} else {
			$this->assignRef('category', $category);
		}
		
		$userobj = JFactory::getUser();	
		$user = $userobj->get('username');
		$this->assignRef('user', $user);
		
		$categories = $model->getsubcategories( $category->id, $rows * $cols );
		$this->assignRef('categories', $categories);
		
		$videos = $model->getvideos($category->name, $rows * $cols);
		$this->assignRef('videos', $videos);
		
		$pagination = $model->getpagination();
		$this->assignRef('pagination', $pagination);		
		
		//Custom Meta
		$doc = JFactory::getDocument();
		$doc->setTitle($doc->getTitle() . ' - ' . $category->name);
		$doc->setMetaData( 'keywords' , $category->metakeywords );
		$doc->setDescription( $category->metadescription );
		
		if(substr(JVERSION,0,3) != '1.5') {
			if ($category->metadescription == '' && $params->get('menu-meta_description')) {
				$doc->setDescription($params->get('menu-meta_description'));
			}

			if ($category->metakeywords == '' && $params->get('menu-meta_keywords')) {
				$doc->setMetadata('keywords', $params->get('menu-meta_keywords'));
			}

			if ($params->get('robots')) {
				$doc->setMetadata('robots', $params->get('robots'));
			}
		}
        		
		// Adds Breadcrumbs 
		$this->generateBreadcrumbs($category);
				
        parent::display($tpl);
    }
	
	function generateBreadcrumbs($row=null) {
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();

		jimport( 'joomla.application.pathway' );
		$breadcrumbs = $mainframe->getPathway();
		
		$crumbs = array();
		$Itemid =  JRequest::getInt('Itemid');
		$orderby = '';
		if($orderby = JRequest::getCmd('orderby')) {
			$orderby = '&orderby=' . $orderby;
		}		
		$index = 0;		
		
		if (!$row->parent == 0) {
			$query = 'SELECT * FROM #__allvideoshare_categories WHERE id = '.$row->parent;
			$db->setquery($query);
			$parent_category = $db->loadObject();				
					
			if ($parent_category->parent != 0) {
				$query = 'SELECT * FROM #__allvideoshare_categories WHERE id = '.$parent_category->parent;
				$db->setquery($query);
				$top_category = $db->loadObject();
				
				$crumbs[$index][0] = $top_category->name;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_allvideoshare&Itemid='.$Itemid.'&view=category'.$orderby.'&slg='.$top_category->slug);
				$index++;	
			}
			
			$crumbs[$index][0] = $parent_category->name;
			$crumbs[$index][1] = JRoute::_('index.php?option=com_allvideoshare&Itemid='.$Itemid.'&view=category'.$orderby.'&slg='.$parent_category->slug);
			$index++;
		}		
		
        $crumbs[$index][0] = $row->name;
		$crumbs[$index][1] = JRoute::_('index.php?option=com_allvideoshare&Itemid='.$Itemid.'&view=category'.$orderby.'&slg='.$row->slug);	

		for ($i=0, $n=count($crumbs); $i < $n; $i++) {
			$breadcrumbs->addItem($crumbs[$i][0], $crumbs[$i][1]);
		}
		return;
    }
	
}