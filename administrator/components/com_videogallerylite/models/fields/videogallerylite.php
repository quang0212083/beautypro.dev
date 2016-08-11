<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/
defined('_JEXEC') or die;
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldVideogallerylite extends JFormFieldList
{
   
    protected $type = 'Videogallerylite';
    protected function getOptions() 
    {
       
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name')
                ->from('#__huge_itgallery_gallerys');
        $db->setQuery($query);
        $messages = $db->loadObjectList();
        $options = array();

        if ($messages)
        {
            foreach($messages as $message) 
            {
                $options[] = JHtml::_('select.option', $message->id, $message->name);
            }
        }
        $options = array_merge(parent::getOptions(), $options);
       
        return $options;
    }
}
