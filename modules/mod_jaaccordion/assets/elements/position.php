<?php
/**
 * ------------------------------------------------------------------------
 * JA Accordion Module for J25 & J34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
/**
 * JA Position Param Helper
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldPosition extends JFormField
{
    /**
     * Element name
     *
     * @access	protected
     * @var		string
     */
    var $_name = 'Position';


    /**
     *
     * process input params
     * @return string element param
     */
    protected function getInput()
    {
        $options = $this->getPositions();
        
        // Initialize variables.
        //$options = array();
        $attr = '';
        
        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
        $attr .= $this->multiple ? ' multiple="multiple"' : '';
        
        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
        
        $arrOpt = array();
        for ($i = 0; $i < count($options); $i++) {
            $arrOpt[$i]['text'] = $arrOpt[$i]['value'] = $options[$i]->position;
        }
        array_unshift($arrOpt, JHTML::_('select.option', '', '- ' . JText::_('Select position') . ' -', 'value', 'text'));
        return JHtml::_('select.genericlist', $arrOpt, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
    }


    /**
     * 
     * Get Postions of Moudle
     * @return array $options 
     */
    function getPositions()
    {
        $db = JFactory::getDBO();
        
        $query = 'SELECT DISTINCT position' . ' FROM #__modules AS a' . ' WHERE a.position != "" AND a.client_id = 0' . ' ORDER BY a.position';
        $db->setQuery($query);
        $db->getQuery();
        $options = $db->loadObjectList();
        
        return $options;
    }

}