<?php 
/**
 * @package  Video Gallery Lite
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     http://www.huge-it.com/
 **/

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');

class VideogalleryliteModelLightbox extends JModelAdmin {

    public function getTable($type = 'Lightbox', $prefix = 'VideogalleryliteTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        
        $form = $this->loadForm(
                $this->option . '.lightbox', 'lightbox', array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
       
        $data = JFactory::getApplication()->getUserState($this->option . '.editlightbox.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    function save($data) {
        
        
    }

}
