<?php

/**
 * @package     BreezingCommerce
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');
$version = new JVersion();

if (version_compare($version->getShortVersion(), '1.6', '<')) {

    jimport( 'joomla.html.parameter.element' );

    class JElementCategories extends JElement {

        function fetchElement($name, $value, $node, $control_name) {
            $class = $node->attributes('class') ? $node->attributes('class') : "text_area";
            $db = JFactory::getDBO();
            $db->setQuery("Select `title`, `id` From #__sections Where published = 1 Order By ordering");
            $sections = $db->loadAssocList();

            $i = 0;
            foreach($sections As $section){
                if(!isset($sections['categories'])){
                    $sections[$i]['categories'] = array();
                }
                $db->setQuery("Select `title`, `id` From #__categories Where section = {$section['id']} And published = 1 Order By ordering");
                $cats = $db->loadAssocList();
                foreach($cats As $cat){
                    if($cat){
                        $sections[$i]['categories'][] = $cat;
                    }
                }
                if(!count($cats)){
                   unset($sections[$i]);
                }
                $i++;
            }
            $sections = array_merge(array(), $sections);
            
            $out = '<select style="max-width: 200px;" name="' . $control_name . '[' . $name . ']" id="' . $control_name . $name .'" class="' . $class . '">' . "\n";
            
            $section_selected = 0;
            $category_selected = 0;
            $seccats = explode(':',$value);
            
            if(count($seccats) == 2){
                $section_selected = $seccats[0];
                $category_selected = $seccats[1];
            }
            
            $out .= '<option value="-2">'.JText::_('COM_CONTENTBUILDER_INHERIT').'</option>'."\n";
            $out .= '<option value="0:0">'.JText::_('COM_CONTENTBUILDER_UNCATEGORIZED').'</option>'."\n";
            
            foreach ($sections As $section) {
                
                $out .= '<optgroup label="'.$section['title'].'">'."\n";
                   
                    foreach ($section['categories'] As $category) {
                        
                        $out .= '<option '.($section_selected == $section['id'] && $category_selected == $category['id'] ? ' selected="selected"' : '').'value="'.$section['id'].':'.$category['id'].'">'.htmlentities($category['title'], ENT_QUOTES, 'UTF-8').'</option>'."\n";
                        
                    }
                    
                $out .= '</optgroup>'."\n";
                
            }
            $out .= '</select>' . "\n";
            
            return $out;
        }

    }

} else {

    jimport('joomla.html.html');
    jimport('joomla.form.formfield');

    class JFormFieldCategories extends JFormField {

        protected $type = 'Forms';

        protected function getInput() {
            $class = $this->element['class'] ? $this->element['class'] : "text_area";
            
            // Initialise variables.
            $options = array();

            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('a.id AS value, a.title AS text, a.level');
            $query->from('#__categories AS a');
            $query->join('LEFT', '`#__categories` AS b ON a.lft > b.lft AND a.rgt < b.rgt');

            // Filter by the type
            $query->where('(a.extension = ' . $db->quote('com_content') . ' OR a.parent_id = 0)');

            $query->where('a.published IN (0,1)');
            $query->group('a.id');
            $query->order('a.lft ASC');

            // Get the options.
            $db->setQuery($query);

            $options = $db->loadObjectList();

            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }

            // Pad the option text with spaces using depth level as a multiplier.
            for ($i = 0, $n = count($options); $i < $n; $i++) {
                // Translate ROOT
                if ($options[$i]->level == 0) {
                    $options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
                }

                $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
            }

            // Initialise variables.
            $user = JFactory::getUser();

            if (empty($id)) {
                // New item, only have to check core.create.
                foreach ($options as $i => $option) {
                    // Unset the option if the user isn't authorised for it.
                    if (!$user->authorise('core.create', 'com_content' . '.category.' . $option->value)) {
                        unset($options[$i]);
                    }
                }
            } else {
                // Existing item is a bit more complex. Need to account for core.edit and core.edit.own.
                foreach ($options as $i => $option) {
                    // Unset the option if the user isn't authorised for it.
                    if (!$user->authorise('core.edit', $extension . '.category.' . $option->value)) {
                        // As a backup, check core.edit.own
                        if (!$user->authorise('core.edit.own', $extension . '.category.' . $option->value)) {
                            // No core.edit nor core.edit.own - bounce this one
                            unset($options[$i]);
                        } else {
                            // TODO I've got a funny feeling we need to check core.create here.
                            // Maybe you can only get the list of categories you are allowed to create in?
                            // Need to think about that. If so, this is the place to do the check.
                        }
                    }
                }
            }


            if (isset($row) && !isset($options[0])) {
                if ($row->parent_id == '1') {
                    $parent = new stdClass();
                    $parent->text = JText::_('JGLOBAL_ROOT_PARENT');
                    array_unshift($options, $parent);
                }
            }

            // Merge any additional options in the XML definition.
            //$options = array_merge(parent::getOptions(), $options);
            
            $out = '<select style="max-width: 200px;" name="' . $this->name . '" id="' . $this->id . '" class="' . $this->element['class'] . '">' . "\n";
            
            $out .= '<option value="-2">'.JText::_('COM_CONTENTBUILDER_INHERIT').'</option>'."\n";
            
            foreach ($options As $category) {
                $out .= '<option '.($this->value == $category->value  ? ' selected="selected"' : '').'value="'.$category->value.'">'.htmlentities($category->text, ENT_QUOTES, 'UTF-8').'</option>'."\n";
            }
            $out .= '</select>' . "\n";
            
            return $out;
        }
    }
}