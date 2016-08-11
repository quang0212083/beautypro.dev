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

class AllVideoShareViewLicensing extends AllVideoShareView {

    function display($tpl = null) {
	    $model = $this->getModel();
		
		$data = $model->getdata();
		$this->assignRef('data', $data);
		
		$logoposition_options[] = JHTML::_('select.option', 'topleft', JText::_('TOP_LEFT'));
		$logoposition_options[] = JHTML::_('select.option', 'topright', JText::_('TOP_RIGHT'));
		$logoposition_options[] = JHTML::_('select.option', 'bottomleft', JText::_('BOTTOM_LEFT'));
		$logoposition_options[] = JHTML::_('select.option', 'bottomright', JText::_('BOTTOM_RIGHT'));
		$logoposition = JHTML::_('select.genericlist', $logoposition_options, 'logoposition', '', 'value', 'text', $data->logoposition);
		$this->assignRef('logoposition', $logoposition);

		JToolBarHelper::title(JText::_('ALL_VIDEO_SHARE'), 'allvideoshare');
		JToolBarHelper::save('save', JText::_('SAVE'));
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Help', 'Help', 'Help', 'http://allvideoshare.mrvinoth.com/licensing-the-extension', 600, 400 );
		
		JSubMenuHelper::addEntry(JText::_('DASHBOARD'), 'index.php?option=com_allvideoshare');
		JSubMenuHelper::addEntry(JText::_('PLAYERS'), 'index.php?option=com_allvideoshare&view=players');
		JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_allvideoshare&view=categories');	
		JSubMenuHelper::addEntry(JText::_('VIDEOS'), 'index.php?option=com_allvideoshare&view=videos');
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval');
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts');
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config');
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing', true);	
		
        parent::display($tpl);
    }
	
}