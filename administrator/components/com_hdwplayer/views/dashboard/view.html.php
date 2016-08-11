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

class HdwplayerViewDashboard extends HdwplayerView {

    function display($tpl = null) {
		$model = $this->getModel();
		
		$data = $model->getdata();
		$this->assignRef('data', $data);
		
		JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_hdwplayer', true);
		JSubMenuHelper::addEntry(JText::_('General Settings'), 'index.php?option=com_hdwplayer&view=settings');
		JSubMenuHelper::addEntry(JText::_('Videos'), 'index.php?option=com_hdwplayer&view=videos');
		JSubMenuHelper::addEntry(JText::_('Category'), 'index.php?option=com_hdwplayer&view=category');
		
		JToolBarHelper::title(JText::_('Hdwplayer'), 'hdwplayer');		
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