<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class VideoGalleryliteControllerVideogallerylite extends JControllerForm
{
   public function save ($key = null, $urlVar = null) {
        $model = $this->getModel();
        global $option;
        $id_cat = JRequest::getVar('id');
        $table = $model->getTable();
        $post = JRequest::get('post',JREQUEST_ALLOWHTML);      
        $model->save($post);
        $this->setredirect('index.php?option=com_videogallerylite&view=videogallerylite&id='.$id_cat,JText::_('COM_VIDEOGALLERYLITE_SETTINGS'));
}

    function save2(){
        $model = $this->getModel();
        global $option;
        $id_cat = JRequest::getVar('id');
        $table = $model->getTable();
        $post = JRequest::get('post');      
        $model->save($post);
        $this->setredirect('index.php?option=com_videogallerylite&view=videogallerylites',JText::_('COM_VIDEOGALLERYLITE_SETTINGS'));
    }
    function addProject() {
        $model = $this->getModel();    
        $id = $model->saveProject(JRequest::getVar('sel'),JRequest::getVar('parentId') );
        $this->setredirect('index.php?option=com_videogallerylite&view=videogallerylite&layout=edit&id='. $id);

    }
    function deleteProject(){
        $model = $this->getModel();    
        $id = $model->deleteProject();
        $projectId = JRequest::getVar('id');
        $this->setredirect('index.php?option=com_videogallerylite&view=videogallerylite&layout=edit&id='.$projectId);
    }

}