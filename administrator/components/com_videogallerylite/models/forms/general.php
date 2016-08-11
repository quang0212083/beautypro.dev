<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 


defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');

class VideogalleryliteModelGeneral extends JModelAdmin {

    public function getTable($type = 'General', $prefix = 'VideogalleryliteTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm(
                $this->option . '.general', 'general', array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState($this->option . '.editgeneral.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_params');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    function save($data) {
        $data = JRequest::get('post');
        $db = JFactory::getDbo();
        echo $data['params[gallery_title_font_size]'];
    }

    function apply1() {
        $data = JRequest::get('post');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name');
        $query->from('#__huge_it_videogallery_params');
        $db->setQuery($query);
        $results = $db->loadAssocList();
        foreach ($results as $results_) {
            $id = $results_['id'];
            $name = $results_['name'];
            if (isset($data['params'][$name]) && $data['params'][$name] != "") {
                $data['params'][$name] = $data['params'][$name];
            } else {
                $data['params'][$name] = "off";
            }
            $query = $db->getQuery(true);
            $query->update('#__huge_it_videogallery_params')->set('value="' . $data['params'][$name] . '"')->where('id=' . $id);
            $db->setQuery($query);
            $db->execute();
        }
    }

}
