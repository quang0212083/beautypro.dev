<?php

defined('_JEXEC') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldVideogallerylite extends JFormFieldList {

    protected $type = 'Videogallerylite';

    protected function getOptions() {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('id, name')
                ->from('#__huge_it_videogallery_galleries');
        $db->setQuery($query);
        $messages = $db->loadObjectList();


        $options = array();

        if ($messages) {
            foreach ($messages as $message) {
                $options[] = JHtml::_('select.option', $message->id, $message->name);
            }
        }
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
