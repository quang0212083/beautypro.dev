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

// No direct access
defined('_JEXEC') or die();

/**
 * Radio List Element
 *
 * @package  JA Accordion Element
 */
class JFormFieldModules extends JFormField
{
    /**
     * Element name
     *
     * @access    protected
     * @var        string
     */
    protected $type = 'Modules';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     */
    function getInput()
    {
        $db = JFactory::getDBO();
        $query = "SELECT e.extension_id, a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out,
                    a.checked_out_time, a.published, a.access, a.ordering, a.publish_up, a.publish_down,
                    l.title AS language_title,uc.name AS editor,ag.title AS access_level,
                    MIN(mm.menuid) AS pages,e.name AS name
                    FROM `#__modules` AS a
                    LEFT JOIN `#__languages` AS l ON l.lang_code = a.language
                    LEFT JOIN #__users AS uc ON uc.id=a.checked_out
                    LEFT JOIN #__viewlevels AS ag ON ag.id = a.access
                    LEFT JOIN #__modules_menu AS mm ON mm.moduleid = a.id
                    LEFT JOIN #__extensions AS e ON e.element = a.module
                    WHERE a.published = 1 AND a.client_id = 0 AND a.module != 'mod_jaaccordion'
                    GROUP BY a.id";
        $db->setQuery($query);
        $groups = $db->loadObjectList();

        $groupHTML = array();
        if ($groups && count($groups)) {
            foreach ($groups as $v => $t) {
                $groupHTML[] = JHTML::_('select.option', $t->id, $t->title);
            }
        }
        $lists = JHTML::_('select.genericlist', $groupHTML, "{$this->name}[]", ' multiple="multiple"  size="10" ', 'value', 'text', $this->value);

        return $lists;
    }
}