<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die;
 
// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
 
/**
 * CategoryBlock Form Field class for the CategoryBlock component
 */
class JFormFieldCategoryOptionalSmart extends JFormFieldList
{
        /**
         * The field type.
         *
         * @var         string
         */
        protected $type = 'CategoryOptionalSmart';
 
        /**
         * Method to get a list of options for a list input.
         *
         * @return      array           An array of JHtml options.
         */
        protected function getOptions() 
        {
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('id,title');
                $query->from('#__categories');
                $query->where('`extension`="com_content"');
                
                
                $db->setQuery((string)$query);
                $messages = $db->loadObjectList();
                $options = array();
                
                $options[] = JHtml::_('select.option', '0', '- Not set');
                $options[] = JHtml::_('select.option', '-1', '- Smart Category Detection');
                
                if ($messages)
                {
                        foreach($messages as $message) 
                        {
                                $options[] = JHtml::_('select.option', $message->id, $message->title);
                                
                        }
                }
                $options = array_merge(parent::getOptions(), $options);
                return $options;
        }

}
