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

class AllVideoShareViewConfig extends AllVideoShareView {

    function display($tpl = null) {
	    $model = $this->getModel();
		
		$data = $model->getdata();
		$this->assignRef('data', $data);
		
		$players = $model->getplayers();		 
		foreach ( $players as $item ) {
			$playerid_options[] = JHTML::_('select.option', $item->id, $item->name );
		}
		$playerid = JHTML::_('select.genericlist', $playerid_options, 'playerid', '', 'value', 'text', $data->playerid);
		$this->assignRef('playerid', $playerid);
		
		$layout_options[] = JHTML::_('select.option', 'all', JText::_('PLAYER_WITH_COMMENTS_AND_RELATED_VIDEOS'));
		$layout_options[] = JHTML::_('select.option', 'comments', JText::_('PLAYER_WITH_COMMENTS_ONLY'));
		$layout_options[] = JHTML::_('select.option', 'relatedvideos', JText::_('PLAYER_WITH_RELATED_VIDEOS_ONLY'));
		$layout_options[] = JHTML::_('select.option', 'none', JText::_('PLAYER_ONLY'));
		$layout = JHTML::_('select.genericlist', $layout_options, 'layout', 'onchange="javascript:changeType(this.options[this.selectedIndex].value);"', 'value', 'text', $data->layout);
		$this->assignRef('layout', $layout);
		
		$comments_type_options[] = JHTML::_('select.option', 'facebook', JText::_('FACEBOOK_COMMENTS'));
		$comments_type_options[] = JHTML::_('select.option', 'jcomments', JText::_('JCOMMENTS'));
		$comments_type_options[] = JHTML::_('select.option', 'komento', JText::_('KOMENTO'));
		$comments_type = JHTML::_('select.genericlist', $comments_type_options, 'comments_type', 'onchange="javascript:changeComments(this.options[this.selectedIndex].value);"', 'value', 'text', $data->comments_type);
		$this->assignRef('comments_type', $comments_type);
		
		$comments_color_options[] = JHTML::_('select.option', 'light', JText::_('LIGHT'));
		$comments_color_options[] = JHTML::_('select.option', 'dark', JText::_('DARK'));
		$comments_color = JHTML::_('select.genericlist', $comments_color_options, 'comments_color', '', 'value', 'text', $data->comments_color);
		$this->assignRef('comments_color', $comments_color);
		
		JToolBarHelper::title(JText::_('ALL_VIDEO_SHARE'), 'allvideoshare');	
		JToolBarHelper::save('save', JText::_('SAVE'));			
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Help', 'Help', 'Help', 'http://allvideoshare.mrvinoth.com/configuration-settings', 600, 400 );
		
		JSubMenuHelper::addEntry(JText::_('DASHBOARD'), 'index.php?option=com_allvideoshare');	
		JSubMenuHelper::addEntry(JText::_('PLAYERS'), 'index.php?option=com_allvideoshare&view=players');	
		JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_allvideoshare&view=categories');		
		JSubMenuHelper::addEntry(JText::_('VIDEOS'), 'index.php?option=com_allvideoshare&view=videos');
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval');
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts');
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config', true);
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing');	
		
        parent::display($tpl);
    }
	
}