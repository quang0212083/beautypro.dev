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

class HdwplayerViewCategories extends HdwplayerView {

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
		
		$categories = $model->getcategories($rows * $cols);
		$this->assignRef('categories', $categories);
		
		$pagination = $model->getpagination();
		$this->assignRef('pagination', $pagination);
				
        parent::display($tpl);
    }
	
}

?>