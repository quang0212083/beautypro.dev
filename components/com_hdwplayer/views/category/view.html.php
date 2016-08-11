<?php

/*
 * @version		$Id: view.html.php 3.0 2012-10-10 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access 
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class HdwplayerViewCategory extends HdwplayerView {

	function display($tpl = null) {
		$mainframe = JFactory::getApplication();
		$model     = $this->getModel();

		$settings  = $model->getsettings();
		
		$params = $mainframe->getParams();
		$this->assignRef('params',	$params);
		
		$rows = $params->get('no_of_rows', $settings->rows);		
		$this->assignRef('rows', $rows);
		
		$cols = $params->get('no_of_cols', $settings->cols);
		$this->assignRef('cols', $cols);
		
		$thumb_width = $params->get('thumb_width', $settings->thumbwidth);
		$this->assignRef('thumb_width', $thumb_width);
		
		$thumb_height = $params->get('thumb_height', $settings->thumbheight);
		$this->assignRef('thumb_height', $thumb_height);
		
		$show_subcategories = $params->get('show_subcategories', $settings->subcategories);		
		$this->assignRef('show_subcategories', $show_subcategories);
		
		if(!$category = $model->getcategory()) {
			echo JText::_('Item not found.');
			
			return;
		} else {
			$this->assignRef('category', $category);
		}
		
		$videos = $model->getvideos($category->name, $rows * $cols);
		$this->assignRef('videos', $videos);
		
		$categories = $model->getsubcategories($rows * $cols);
		$this->assignRef('categories', $categories);
		
		$pagination = $model->getpagination($category->name);
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
		
		// Add Breadcrumbs 
		$this->generateBreadcrumbs($category);
				
        parent::display($tpl);
    }
	
	function generateBreadcrumbs($row=null) {
		$mainframe = JFactory::getApplication();
		$db =  JFactory::getDBO();

		jimport( 'joomla.application.pathway' );
		$breadcrumbs = $mainframe->getPathway();
		
		$crumbs = array();
		$Itemid = JRequest::getVar('Itemid');
		$orderby = '';
		if(JRequest::getVar('orderby')) {
			$orderby .= '&orderby=' . JRequest::getVar('orderby');
		}	
		$index  = 0;		
		
		if (!$row->parent == 0) {
			$query = 'SELECT * FROM #__hdwplayer_category WHERE id='.$row->parent;
			$db->setquery($query);
			$parent_category = $db->loadObject();				
					
			if ($parent_category->parent != 0) {
				$query = 'SELECT * FROM #__hdwplayer_category WHERE id='.$parent_category->parent;
				$db->setquery($query);
				$top_category = $db->loadObject();
				
				$crumbs[$index][0] = $top_category->name;
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hdwplayer&Itemid='.$Itemid.'&view=category'.$orderby.'&wid='.$top_category->id);
				$index++;	
			}
			
			$crumbs[$index][0] = $parent_category->name;
			$crumbs[$index][1] = JRoute::_('index.php?option=com_hdwplayer&Itemid='.$Itemid.'&view=category'.$orderby.'&wid='.$parent_category->id);
			$index++;
		}		
		
        $crumbs[$index][0] = $row->name;
		$crumbs[$index][1] = JRoute::_('index.php?option=com_hdwplayer&Itemid='.$Itemid.'&view=category'.$orderby.'&wid='.$row->id);	

		for ($i=0, $n=count($crumbs); $i < $n; $i++) {
			$breadcrumbs->addItem($crumbs[$i][0], $crumbs[$i][1]);
		}
		return;
    }
	
}

?>