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
class VideogalleryliteViewGeneral extends JViewLegacy
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
          
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title(JText::_('COM_VIDEOGALLERYLITE_GENERAL').'<div style="float:left;"><img src="'.JURI::root().'media/com_videogallerylite/images/huge.png" width="20px"/></div>',  JText::_('COM_VIDEOGALLERYLITE_GENERAL'));
                JToolBarHelper::save('general.save1');
                JToolBarHelper::apply('general.apply1');
                JToolBarHelper::cancel('general.cancel');
	}

}