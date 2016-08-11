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

class VideogalleryliteViewVideogallerylite extends JViewLegacy
{
	
	protected $item;
        protected $galleryParams;
	protected $form;
        protected $prop;
        protected $all;

	public function display($tpl = null)
	{
		try
		{     
                     
			$this->form = $this->get('Form');
			$this->item = $this->get('Item');
                        $this->galleryParams = $this->get('Videogallery');
                        $this->prop= $this->get('Propertie');
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
		$isNew = ($this->item->id == 0);
		JToolBarHelper::title( JText::_('COM_VIDEOGALLERYLITE_MANAGER_VIDEOGALLERYLITE'));		
		JToolBarHelper::save('videogallerylite.save2');
                JToolBarHelper::apply('videogallerylite.save');
		JToolBarHelper::cancel('videogallerylite.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
              
	}
}
