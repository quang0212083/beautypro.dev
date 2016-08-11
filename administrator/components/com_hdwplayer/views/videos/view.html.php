<?php

/*
 * @version		$Id: view.html.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class HdwplayerViewVideos extends HdwplayerView {

    function display($tpl = null) {
		$mainframe  = JFactory::getApplication();	
		$option     = JRequest::getCmd('option');
		$view       = JRequest::getCmd('view');
	    $model      = $this->getModel();
		
		$limit      = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		$this->assignRef('limitstart', $limitstart);
		
        $data  = $model->getvideos();
		$this->assignRef('data', $data);
		
		$category_options[]     = JHTML::_('select.option', 'none', JText::_('None'));
		$categories             = $model->getcategories();		 
		foreach ( $categories as $item ) {
			$item->treename     = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
		}
		$category = JHTML::_('select.genericlist', $category_options, 'category', '', 'value', 'text', '');
		$this->assignRef('category', $category);
		
		$pagination = $model->getpagination();
		$this->assignRef('pagination', $pagination);
		
		$lists = $model->getlists();
		$this->assignRef('lists', $lists);
		
		$approval = $model->getapproval();
		$this->assignRef('approval', $approval);
		
		JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_hdwplayer');		
		JSubMenuHelper::addEntry(JText::_('General Settings'), 'index.php?option=com_hdwplayer&view=settings');
		JSubMenuHelper::addEntry(JText::_('Videos'), 'index.php?option=com_hdwplayer&view=videos', true);
		JSubMenuHelper::addEntry(JText::_('Category'), 'index.php?option=com_hdwplayer&view=category');
		
		JToolBarHelper::title(JText::_('Hdwplayer'), 'hdwplayer');
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList('','delete');
        JToolBarHelper::editList('edit');
        JToolBarHelper::addNew('add');		
		$help =  JToolBar::getInstance('toolbar');
		$help->appendButton( 'Popup', 'help', 'Help', 'http://hdwplayer.com/', 900, 500 );
		JToolBarHelper::custom( '', '', '', '<style>#google_translate_element{margin-top:-19px;}#google_translate_element span{display:inline;}#google_translate_element img{padding:0;}</style>
<div id="google_translate_element"></div><script type="text/javascript">
document.getElementById("toolbar-").getElementsByTagName("button")[0].onclick = function(){};
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: "en", layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, "google_translate_element");
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>',false);
				
		parent::display($tpl);
		
    }
	
	function add($tpl = null) {
	    $model = $this->getModel();
		
		$category_options[]     = JHTML::_('select.option', 'none', JText::_('Uncategorised'));
		$categories             = $model->getcategories();		 
		foreach ( $categories as $item ) {
			$item->treename     = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
		}
		$category = JHTML::_('select.genericlist', $category_options, 'category', '', 'value', 'text', '');
		$this->assignRef('category', $category);
		
		JToolBarHelper::title(JText::_('Hdwplayer'), 'hdwplayer');
        JToolBarHelper::save('save', 'Save');
        JToolBarHelper::apply('apply', 'Apply');
        JToolBarHelper::cancel('cancel');		
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Popup', 'help', 'Help', 'http://hdwplayer.com/', 900, 500 );
		JToolBarHelper::custom( '', '', '', '<style>#google_translate_element{margin-top:-19px;}#google_translate_element span{display:inline;}#google_translate_element img{padding:0;}</style>
<div id="google_translate_element"></div><script type="text/javascript">
document.getElementById("toolbar-").getElementsByTagName("button")[0].onclick = function(){};
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: "en", layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, "google_translate_element");
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>',false);
				
		parent::display($tpl);
		
    }
	
	function edit($tpl = null) {
	    $model = $this->getModel();
		
        $data  = $model->getrow();
		$this->assignRef('data', $data);
		
		$category_options[]     = JHTML::_('select.option', 'none', JText::_('Uncategorised'));
		$categories             = $model->getcategories();		 
		foreach ( $categories as $item ) {
			$item->treename     = JString::str_ireplace('&#160;', '-', $item->treename);
			$category_options[] = JHTML::_('select.option', $item->name, $item->treename );
		}
		$category = JHTML::_('select.genericlist', $category_options, 'category', '', 'value', 'text', $data->category);
		$this->assignRef('category', $category);
		
		JToolBarHelper::title(JText::_('Hdwplayer'), 'hdwplayer');
        JToolBarHelper::save('save', 'Save');
        JToolBarHelper::apply('apply', 'Apply');
        JToolBarHelper::cancel('cancel');		
		$help = JToolBar::getInstance('toolbar');
		$help->appendButton( 'Popup', 'help', 'Help', 'http://hdwplayer.com/', 900, 500 );
		JToolBarHelper::custom( '', '', '', '<style>#google_translate_element{margin-top:-19px;}#google_translate_element span{display:inline;}#google_translate_element img{padding:0;}</style>
<div id="google_translate_element"></div><script type="text/javascript">
document.getElementById("toolbar-").getElementsByTagName("button")[0].onclick = function(){};
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: "en", layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, "google_translate_element");
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>',false);
		
        parent::display($tpl);
    }
	
}

?>