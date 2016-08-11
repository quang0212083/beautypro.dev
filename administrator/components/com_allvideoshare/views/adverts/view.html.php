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

class AllVideoShareViewAdverts extends AllVideoShareView {

    function display($tpl = null) {
		$mainframe = JFactory::getApplication();	
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
	    $model = $this->getModel();
		
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->assignRef('limitstart', $limitstart);
		
		$data = $model->getdata();
		$this->assignRef('data', $data);
		
		$pagination = $model->getpagination();
		$this->assignRef('pagination', $pagination);
		
		$lists = $model->getlists();
		$this->assignRef('lists', $lists);
		
		JToolBarHelper::title(JText::_('ALL_VIDEO_SHARE'), 'allvideoshare');
		JToolBarHelper::publishList('publish', JText::_('PUBLISH'));
        JToolBarHelper::unpublishList('unpublish', JText::_('UNPUBLISH'));
        JToolBarHelper::deleteList(JText::_('ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_ITEMS'),'delete', JText::_('DELETE'));
        JToolBarHelper::editList('edit', JText::_('EDIT'));
        JToolBarHelper::addNew('add', JText::_('NEW'));
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Help', 'Help', 'Help', 'http://allvideoshare.mrvinoth.com/adding-a-video', 600, 400 );
		
		JSubMenuHelper::addEntry(JText::_('DASHBOARD'), 'index.php?option=com_allvideoshare');	
		JSubMenuHelper::addEntry(JText::_('PLAYERS'), 'index.php?option=com_allvideoshare&view=players');	
		JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_allvideoshare&view=categories');		
		JSubMenuHelper::addEntry(JText::_('VIDEOS'), 'index.php?option=com_allvideoshare&view=videos');
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval');
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts', true);
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config');
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing');	
		
        parent::display($tpl);
    }
	
	function add($tpl = null) {
		$model = $this->getModel();
		
		$type_options[] = JHTML::_('select.option', 'both', JText::_('BOTH'));
		$type_options[] = JHTML::_('select.option', 'preroll', JText::_('PREROLL'));
		$type_options[] = JHTML::_('select.option', 'postroll', JText::_('POSTROLL'));		
		$type = JHTML::_('select.genericlist', $type_options, 'type', '', 'value', 'text', 'both');		 
		$this->assignRef('type', $type);
		
		$method_options[] = JHTML::_('select.option', 'url', JText::_('DIRECT_URL'));
		$method_options[] = JHTML::_('select.option', 'upload', JText::_('GENERAL_UPLOAD'));
		$method = JHTML::_('select.genericlist', $method_options, 'method', 'onchange="javascript:changeMethod(this.options[this.selectedIndex].value);"', 'value', 'text', 'upload');
		$this->assignRef('method', $method);
		
		JToolBarHelper::title(JText::_('ADD_A_NEW_VIDEO'), 'allvideoshare');
		JToolBarHelper::save('save', JText::_('SAVE'));
        JToolBarHelper::apply('apply', JText::_('APPLY'));
        JToolBarHelper::cancel('cancel', JText::_('CANCEL'));
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Help', 'Help', 'Help', 'http://allvideoshare.mrvinoth.com/adding-a-video', 600, 400 );
		
		JSubMenuHelper::addEntry(JText::_('DASHBOARD'), 'index.php?option=com_allvideoshare');	
		JSubMenuHelper::addEntry(JText::_('PLAYERS'), 'index.php?option=com_allvideoshare&view=players');	
		JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_allvideoshare&view=categories');		
		JSubMenuHelper::addEntry(JText::_('VIDEOS'), 'index.php?option=com_allvideoshare&view=videos');
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval');
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts', true);
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config');
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing');
		
        parent::display($tpl);
    }
	
	function edit($tpl = null) {
	    $model = $this->getModel();
		
		$data = $model->getrow();
		$this->assignRef('data', $data);

		$type_options[] = JHTML::_('select.option', 'both', JText::_('BOTH'));
		$type_options[] = JHTML::_('select.option', 'preroll', JText::_('PREROLL'));
		$type_options[] = JHTML::_('select.option', 'postroll', JText::_('POSTROLL'));		
		$type = JHTML::_('select.genericlist', $type_options, 'type', '', 'value', 'text', $data->type);		 
		$this->assignRef('type', $type);
		
		$method_options[] = JHTML::_('select.option', 'url', JText::_('DIRECT_URL'));
		$method_options[] = JHTML::_('select.option', 'upload', JText::_('GENERAL_UPLOAD'));
		$method = JHTML::_('select.genericlist', $method_options, 'method', 'onchange="javascript:changeMethod(this.options[this.selectedIndex].value);"', 'value', 'text', $data->method);
		$this->assignRef('method', $method);
		
		JToolBarHelper::title(JText::_('EDIT_THE_VIDEO'), 'allvideoshare');
		JToolBarHelper::save('save', JText::_('SAVE'));
        JToolBarHelper::apply('apply', JText::_('APPLY'));
        JToolBarHelper::cancel('cancel', JText::_('CANCEL'));
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Help', 'Help', 'Help', 'http://allvideoshare.mrvinoth.com/adding-a-video', 600, 400 );
		
		JSubMenuHelper::addEntry(JText::_('DASHBOARD'), 'index.php?option=com_allvideoshare');	
		JSubMenuHelper::addEntry(JText::_('PLAYERS'), 'index.php?option=com_allvideoshare&view=players');	
		JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_allvideoshare&view=categories');		
		JSubMenuHelper::addEntry(JText::_('VIDEOS'), 'index.php?option=com_allvideoshare&view=videos');
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval');
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts', true);
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config');
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing');
		
        parent::display($tpl);
    }
	
}