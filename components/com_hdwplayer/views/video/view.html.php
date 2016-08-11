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

class HdwplayerViewVideo extends HdwplayerView {

	function display($tpl = null) {
		$mainframe = JFactory::getApplication();
		$model 	   = $this->getModel();		
		
		$settings = $model->getsettings();
		$this->assignRef('settings', $settings);
		
		$params = $mainframe->getParams();
		$this->assignRef('params',	$params);
		
		$player_width  = $params->get('player_width', $settings->width);
		$this->assignRef('player_width', $player_width);
		
		$player_height = $params->get('player_height', $settings->height);
		$this->assignRef('player_height', $player_height);
		
		$autostart = $params->get('player_autostart', $settings->autoplay);
		$this->assignRef('autostart', $autostart);
		
		$playlist_autostart = $params->get('player_playlist_autostart', $settings->playlistautoplay);
		$this->assignRef('playlist_autostart', $playlist_autostart);
		
		$show_title = $params->get('show_title', $settings->title);
		$this->assignRef('show_title', $show_title);
		
		$show_description = $params->get('show_description', $settings->description);
		$this->assignRef('show_description', $show_description);
		
		$show_relatedvideos = $params->get('show_relatedvideos', $settings->relatedvideos);
		$this->assignRef('show_relatedvideos', $show_relatedvideos);
		
		$rows = $params->get('no_of_rows', $settings->rows);
		$this->assignRef('rows', $rows);
		
		$cols = $params->get('no_of_cols', $settings->cols);
		$this->assignRef('cols', $cols);
		
		$thumb_width = $params->get('thumb_width', $settings->thumbwidth);
		$this->assignRef('thumb_width', $thumb_width);
		
		$thumb_height = $params->get('thumb_height', $settings->thumbheight);
		$this->assignRef('thumb_height', $thumb_height);		
		
		$id = JRequest::getCmd('wid');
		$category = $model->getcategory($id);
		$this->assignRef('category', $category);
		
		$relatedvideos = $model->getrelatedvideos($category, $id, $rows * $cols);
		$this->assignRef('relatedvideos', $relatedvideos);
		
		$pagination = $model->getpagination($category, $id);
		$this->assignRef('pagination', $pagination);		
				
        parent::display($tpl);
    }
	
	function generateMetaTags( $params, $video ) {
		$doc = JFactory::getDocument();
		$doc->setTitle($doc->getTitle() . ' - ' . $video->title);
		$doc->setMetaData( 'keywords' , $video->tags );
		$doc->setDescription( $video->metadescription );
		
		if(substr(JVERSION,0,3) != '1.5') {
			if ($video->metadescription == '' && $params->get('menu-meta_description')) {
				$doc->setDescription($params->get('menu-meta_description'));
			}

			if ($video->tags == '' && $params->get('menu-meta_keywords')) {
				$doc->setMetadata('keywords', $params->get('menu-meta_keywords'));
			}

			if ($params->get('robots')) {
				$doc->setMetadata('robots', $params->get('robots'));
			}
		}	
	}
	
	function generateBreadcrumbs( $params, $video ) {
		$mainframe = JFactory::getApplication();
		jimport( 'joomla.application.pathway' );
		$breadcrumbs = $mainframe->getPathway();
		$crumbs = array();
		$Itemid =  JRequest::getVar('Itemid');
		$orderby = '';
		if(JRequest::getVar('orderby')) {
			$orderby .= '&orderby=' . JRequest::getVar('orderby');
		}		
		$index  = 0;	
		
		if($video->category != 'none') {
			$db =  JFactory::getDBO();		
			$query = 'SELECT * FROM #__hdwplayer_category WHERE name='. $db->quote( $video->category );
			$db->setquery($query);
			$row = $db->loadObject();		
			
			if ($row && !$row->parent == 0) {
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
			
			if($row) {
        		$crumbs[$index][0] = $row->name;		
				$crumbs[$index][1] = JRoute::_('index.php?option=com_hdwplayer&Itemid='.$Itemid.'&view=category'.$orderby.'&wid='.$row->id);
				$index++;
			}
		}
		
		$crumbs[$index][0] = $video->title;
		$crumbs[$index][1] = JRoute::_('index.php?option=com_hdwplayer&Itemid='.$Itemid.'&view=video'.$orderby.'&wid='.$video->id);		

		for ($i=0, $n=count($crumbs); $i < $n; $i++) {
			$breadcrumbs->addItem($crumbs[$i][0], $crumbs[$i][1]);
		}
		return;		
    }
	
}

?>