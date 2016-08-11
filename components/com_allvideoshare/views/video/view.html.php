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
require_once( JPATH_ROOT.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'player.php' );

class AllVideoShareViewVideo extends AllVideoShareView {

    function display($tpl = null) {
	    $mainframe = JFactory::getApplication();
		$model = $this->getModel();
		
		$config = $model->getconfig();
		$this->assignRef('config', $config);
		
		// Adds parameter handling
		$params = $mainframe->getParams();
		$this->assignRef('params',	$params);
		
		$player_width = $params->get('avs_player_width', -1);		
		$player_height = $params->get('avs_player_height', -1);
		
		$rows = $params->get('no_of_rows', $config[0]->rows);
		$this->assignRef('rows', $rows);
		
		$cols = $params->get('no_of_cols', $config[0]->cols);
		$this->assignRef('cols', $cols);
		
		$thumb_width = $params->get('avs_thumb_width', $config[0]->thumb_width);
		$this->assignRef('thumb_width', $thumb_width);
		
		$thumb_height = $params->get('avs_thumb_height', $config[0]->thumb_height);
		$this->assignRef('thumb_height', $thumb_height);
		
		$video = $model->getvideo();
		$this->assignRef('video', $video);
		
		$userobj = JFactory::getUser();	
		$user = $userobj->get('username');
		$this->assignRef('user', $user);
		
		$custom = new AllVideoShareModelPlayer($player_width, $player_height);
		$this->assignRef('custom', $custom);
		
		$player = $custom->buildPlayer($video->id, $config[0]->playerid);
		$this->assignRef('player', $player);
		
		$videos = $model->getvideos( $config[0]->rows * $config[0]->cols, $video->category, $video->id );
		$this->assignRef('videos', $videos);
		
		$pagination = $model->getpagination();
		$this->assignRef('pagination', $pagination);		
		
		//Custom Meta
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
		
		// Adds Breadcrumbs 
		$this->generateBreadcrumbs( $video );
				
        parent::display($tpl);
    }
	
	function generateBreadcrumbs( $video ) {
		$mainframe = JFactory::getApplication();
		jimport( 'joomla.application.pathway' );
		$breadcrumbs = $mainframe->getPathway();
		$crumbs = array();
		$Itemid =  JRequest::getInt('Itemid');
		$orderby = '';
		if($orderby = JRequest::getCmd('orderby')) {
			$orderby = '&orderby=' . $orderby;
		}			
		$index = 0;	
		
		$db = JFactory::getDBO();		
		$query = 'SELECT * FROM #__allvideoshare_categories WHERE name = '. $db->quote( $video->category );
		$db->setquery($query);
		$row = $db->loadObject();		
		
		if ($row && !$row->parent == 0) {
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
		
		if($row) {
        	$crumbs[$index][0] = $row->name;		
			$crumbs[$index][1] = JRoute::_('index.php?option=com_allvideoshare&Itemid='.$Itemid.'&view=category'.$orderby.'&slg='.$row->slug);
			$index++;
		}
		
		$crumbs[$index][0] = $video->title;
		$crumbs[$index][1] = JRoute::_('index.php?option=com_allvideoshare&Itemid='.$Itemid.'&view=video'.$orderby.'&slg='.$video->slug);		

		for ($i=0, $n=count($crumbs); $i < $n; $i++) {
			$breadcrumbs->addItem($crumbs[$i][0], $crumbs[$i][1]);
		}
		return;		
    }
	
}