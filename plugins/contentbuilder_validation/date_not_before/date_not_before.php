<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder_helpers.php');

class plgContentbuilder_validationDate_not_before extends JPlugin
{
        function __construct( &$subject, $params )
        {
            parent::__construct($subject, $params);
        }
        
        function onValidate($field, $fields, $record_id, $form, $value){
            
            $lang = JFactory::getLanguage();
            $lang->load('plg_contentbuilder_validation_date_not_before', JPATH_ADMINISTRATOR);

            foreach($fields As $other_field){
                if(isset($other_field['name']) && isset($other_field['value']) && isset($field['name']) && $field['name'].'_later' == $other_field['name']){
                 
                    if(is_array($value)){
                       return JText::_('COM_CONTENTBUILDER_VALIDATION_DATE_NOT_BEFORE_GROUPS');
                    }
                    
                    $other_value = $other_field['value'];
                    $other_value = contentbuilder_convert_date($other_value, $other_field['options']->transfer_format, 'YYYY-MM-DD');
                    $value = contentbuilder_convert_date($value, $field['options']->transfer_format, 'YYYY-MM-DD');
                    
                    if(is_array($other_value)){
                        return JText::_('COM_CONTENTBUILDER_VALIDATION_DATE_NOT_BEFORE_GROUPS');
                    }
                    
                    $value = preg_replace("/[^0-9]/",'',$value);
                    $other_value = preg_replace("/[^0-9]/",'',$other_value);
                    
                    if($other_value < $value){
                        return JText::_('COM_CONTENTBUILDER_VALIDATION_DATE_NOT_BEFORE') . ': ' . $other_field['label'] . ' (' . $other_field['value'] . ')';
                    }
                    
                    return '';
                }
            }
            
            return '';
        }
}
