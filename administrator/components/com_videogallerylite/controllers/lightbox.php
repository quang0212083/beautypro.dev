<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/
defined('_JEXEC') or die('Access Restricted'); ?>
<?php
jimport('joomla.application.component.controllerform');

class VideogalleryliteControllerLightbox extends JControllerForm
{
       function save($key = null, $urlVar = null) {
            $model = $this->getModel('Lightbox');
            $item=$model->save($key);
            $this->setRedirect(JRoute::_('index.php?option=com_videogallerylite&view=videogallerylites', false),JText::_('COM_VIDEOGALLERYLITE_SETTINGS'));
    }

     function  cancel($key = NULL){
               $this->setRedirect(
            JRoute::_('index.php?option=com_videogallerylite&view=videogallerylites', false));
        }
        
        function apply1(){
             $model = $this->getModel('Lightbox');
             $item=$model->save('');
           
               $this->setRedirect(JRoute::_('index.php?option=com_videogallerylite&view=lightbox', false), JText::_('COM_VIDEOGALLERYLITE_SETTINGS'));
        }
}
