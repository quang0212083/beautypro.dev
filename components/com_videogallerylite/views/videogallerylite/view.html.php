<?php 
/**
 * @package Videogallery Lite
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
        protected $gen_params;
	protected $videogall_id ;
	protected $params;
        
        protected $port_params;

	public function display($tpl = null)
	{
            try {

                $this->item = $this->get('Item');
                $this->port_params = $this->get('GalleryParams');
                $this->gen_params = $this->get('GeneralParams');
                $app = JFactory::getApplication();
                $this->params = $app->getParams();
                $this -> videogall_id = $this->get('VideogalleryId');
                $this->_prepareDocument();
                parent::display($tpl);
            }
            catch (Exception $e) {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_PORTFOLIO_ERROR_OCCURRED'), 'error');
                    JLog::add($e->getMessage(), JLog::ERROR, 'com_videogallerylite');
            }
	}
	
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;
                $menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_VIDEOGALLERYLITE_DEFAULT_PAGE_TITLE'));
		}

		
		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item;
		}

		
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}
