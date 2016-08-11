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

class AllVideoShareViewApproval extends AllVideoShareView {

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
		
		$approval = $model->getapproval();
		$this->assignRef('approval', $approval);
		
		$pagination = $model->getpagination();
		$this->assignRef('pagination', $pagination);
		
		$lists = $model->getlists();
		$this->assignRef('lists', $lists);
		
		JToolBarHelper::title(JText::_('ALL_VIDEO_SHARE'), 'allvideoshare');
		JToolBarHelper::publishList('publish', JText::_('PUBLISH'));
        JToolBarHelper::unpublishList('unpublish', JText::_('UNPUBLISH'));
        JToolBarHelper::deleteList(JText::_('ARE_YOU_SURE_WANT_TO_DELETE_SELECTED_ITEMS'),'delete', JText::_('DELETE'));
		JToolBarHelper::editList('edit', JText::_('EDIT'));
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Help', 'Help', 'Help', 'http://allvideoshare.mrvinoth.com/adding-a-video', 600, 400 );
		
		JSubMenuHelper::addEntry(JText::_('DASHBOARD'), 'index.php?option=com_allvideoshare');	
		JSubMenuHelper::addEntry(JText::_('PLAYERS'), 'index.php?option=com_allvideoshare&view=players');	
		JSubMenuHelper::addEntry(JText::_('CATEGORIES'), 'index.php?option=com_allvideoshare&view=categories');		
		JSubMenuHelper::addEntry(JText::_('VIDEOS'), 'index.php?option=com_allvideoshare&view=videos');
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval', true);
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts');
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config');
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing');	
		
        parent::display($tpl);
    }
	
	function edit($tpl = null) {
	    $model = $this->getModel();
		
		$data = $model->getrow();
		$this->assignRef('data', $data);
		
		$type_options[] = JHTML::_('select.option', 'url', JText::_('DIRECT_URL'));
		$type_options[] = JHTML::_('select.option', 'upload', JText::_('GENERAL_UPLOAD'));
		$type_options[] = JHTML::_('select.option', 'youtube', JText::_('YOUTUBE'));
		$type_options[] = JHTML::_('select.option', 'rtmp', JText::_('RTMP_STREAMING'));
		$type_options[] = JHTML::_('select.option', 'lighttpd', JText::_('LIGHTTPD'));
		$type_options[] = JHTML::_('select.option', 'highwinds', JText::_('HIGHWINDS'));
		$type_options[] = JHTML::_('select.option', 'bitgravity', JText::_('BITGRAVITY'));
		$type_options[] = JHTML::_('select.option', 'thirdparty', JText::_('THIRD_PARTY_EMBEDCODE'));		
		$type = JHTML::_('select.genericlist', $type_options, 'type', 'onchange="javascript:changeType(this.options[this.selectedIndex].value);"', 'value', 'text', $data->type);
		$this->assignRef('type', $type);
		
		$category_options[] = JHTML::_('select.option', '', JText::_('SELECT_A_CATEGORY'));
		$categories = $model->getcategories();		 
		foreach ( $categories as $item ) {
			$item->treename = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
		}
		$category = JHTML::_('select.genericlist', $category_options, 'category', '', 'value', 'text', $data->category);
		$this->assignRef('category', $category);
		
		$access_options[] = JHTML::_('select.option', 'public', JText::_('PUBLIC'));
		$access_options[] = JHTML::_('select.option', 'registered', JText::_('REGISTERED'));
		$access = JHTML::_('select.genericlist', $access_options, 'access', '', 'value', 'text', $data->access);		 
		$this->assignRef('access', $access);
		
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
		JSubMenuHelper::addEntry(JText::_('APPROVAL_QUEUE'), 'index.php?option=com_allvideoshare&view=approval', true);
		JSubMenuHelper::addEntry(JText::_('ADVERTISEMENTS'), 'index.php?option=com_allvideoshare&view=adverts');
		JSubMenuHelper::addEntry(JText::_('GENERAL_CONFIGURATION'), 'index.php?option=com_allvideoshare&view=config');
		JSubMenuHelper::addEntry(JText::_('LICENSING'), 'index.php?option=com_allvideoshare&view=licensing');
		
        parent::display($tpl);
    }
	
}

?>