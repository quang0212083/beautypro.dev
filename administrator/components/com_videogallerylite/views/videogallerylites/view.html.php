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

class VideogalleryliteViewVideogallerylites extends JViewLegacy
{
	
	protected $items;
	protected $pagination;
        protected $gallery;
        protected $other;
       

	public function display($tpl = null)
	{
		try
		{
			
			$this->items = $this->get('Items');
                        $this ->gallery = $this->get('Gallery');
                        $this->other=$this->get('Other');
			$this->pagination = $this->get('Pagination');
                      
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

		JToolBarHelper::title(JText::_('COM_VIDEOGALLERYLITE'),  JText::_('COM_VIDEOGALLERYLITE'));
             	JToolBarHelper::addNew('videogallerylites.add');
                JToolBarHelper::divider();
		JToolBarHelper::editList('videogallerylite.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'videogallerylites.delete');
	}
}
