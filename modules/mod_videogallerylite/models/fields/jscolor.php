<?php 
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

class JFormFieldJSColor extends JFormField {

    protected $type = 'jscolor';

    public function getInput() {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id, name, value');
        $query->from('#__huge_it_videogallery_params');
        $query->where('name="' . $this->element['name'] . '"');
        $db->setQuery($query);
        $results = $db->loadAssocList();

        $query1 = $db->getQuery(true);
        $query1->select('*');
        $query1->from('#__huge_it_videogallery_galleries');
        $db->setQuery($query1);
        $results2 = $db->loadAssocList();
        $type_ = $this->element['type_'];


        $name = $this->element['name'];
        $id = $this->element['id'];
        $this->element['class'] = trim($this->element['class']);
        $for = $this->element['for'] ? ' for="' . (string) $this->element['for'] . '"' : '';
        $class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $name = $this->element['name'] ? 'name="' . (string) $this->element['name'] . '"' : '';
        $id = $this->element['id'] ? 'id="' . (string) $this->element['id'] . '"' : '';
        $value = $this->element['value'] ? 'value="' . (string) $this->element['value'] . '"' : '';
        $checked = $this->element['checked'];

        if ($type_ == "choose_videogallerylite") {
            $html = '<select name="'.$this->name.'">';
            foreach ($results2 as $rowpar) {
                $port_name = $rowpar['name'];
                $port_id = $rowpar['id'];
                if ($this->value == $port_name) {
                    $html.= '<option  value="' . $port_id . '"  selected="selected"  id="' . $port_id . '">' . $port_name . '</option>';
                } else {
                    $html.= '<option  value="' . $port_id . '"  id="' . $port_id . '">' . $port_name . '</option>';
                }
            }
            $html.= '</select>';
            return $html;
        }
        
    }

}
