<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 

defined('_JEXEC') or die;
jimport('joomla.application.component.view');
jimport('joomla.application.component.helper');
?>

<?php
class VideogalleryliteViewFeatured extends JViewLegacy
{
	protected $item;
        protected $form;
        protected $script;

	public function display($tpl = null)
	{
		try
		{
			
			$this->form = $this->get('Form');
			$this->item = $this->get('Item');
                        JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/gallery.style.css');
                        $this->addToolBar();
                        parent::display($tpl);
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	protected function addToolBar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		JToolBarHelper::title(JText::_('COM_FEATURED'), JText::_('COM_FEATURED'));		
		JToolBarHelper::cancel('general.cancel');
	}

}