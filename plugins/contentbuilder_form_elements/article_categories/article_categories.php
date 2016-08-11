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
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

if(!class_exists('CBFormElementAfterValidation') && !JFile::exists(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder_classes.php'))
{
    class CBFormElementAfterValidation{}
}
else if(!class_exists('CBFormElementAfterValidation'))
{
    require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder_classes.php');
}

/**
 * If this class is used then BOTH methods need to be defined, wether if only one is used!
 */
class CBArtcatAfter extends CBFormElementAfterValidation{
    
    private $field = null;
    private $form = null;
    private $catid = 0;
    
    function __construct($form, $field, $catid) {
        $this->form = $form;
        $this->catid = $catid;
        $this->field = $field;
    }
    
    function onSaveRecord($record_id){
        
    }
    
    function onSaveArticle($article_id){
        
        jimport('joomla.version');
        $version = new JVersion();
        
        if(!isset($this->field['options']) || !isset($this->field['options']->startcat)){
            return;
        }
        
        // checkup if the category is within the desired partial tree
        
        $options = $this->field['options'];
        $cats = array();
            
        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
            jimport('joomla.application.categories');
            $categories = JCategories::getInstance('content');
            $show = $options->startcat;
            if($options->startcat == -1){
                $show = 'root';
            }
            $subCategories = $categories->get($show, true);
            if(is_object($subCategories)){
               $subCategories = $subCategories->getChildren(true);
               foreach($subCategories As $subCategory){
                  $cats[] = $subCategory->id;  
               }
            }
        }else{
            $show = '';
            if($options->startcat > -1){
                $show = ' And section = '.$options->startcat;
            }
            $db = JFactory::getDBO();
            $query = 'SELECT id, title' .
                            ' FROM #__categories' .
                            ' WHERE access <= '.intval(JFactory::getUser()->get('id')).' And published = 1'.$show.
                            ' ORDER BY ordering';
            $db->setQuery($query);
            $cat_list = $db->loadObjectList();
            foreach($cat_list As $cat){
                $cats[] = $cat->id;
            }
        }
        
        // if not inside the partial tree, skip updating the article's catid
        if(!in_array(intval($this->catid), $cats)){
            return;
        }
        
        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
            $db = JFactory::getDBO();
            $db->setQuery("Update #__content Set catid = " . intval($this->catid) ." Where id = " . intval($article_id));
            $db->query();
        }else{
            $db = JFactory::getDBO();
            $query = 'SELECT section' .
                            ' FROM #__categories' .
                            ' WHERE id = '.intval($this->catid).' And access <= '.intval(JFactory::getUser()->get('id')).' And published = 1'.
                            ' ORDER BY ordering';
            $db->setQuery($query);
            $section = $db->loadResult();
            
            if(is_numeric($section)){
                $db = JFactory::getDBO();
                $db->setQuery("Update #__content Set sectionid = ".$section.", catid = " . intval($this->catid) ." Where id = " . intval($article_id));
                $db->query();
            }
        }
    }
}


class plgContentbuilder_form_elementsArticle_categories extends JPlugin
{
        function __construct( &$subject, $params )
        {
            parent::__construct($subject, $params);
        
            $lang = JFactory::getLanguage();
            $lang->load('plg_contentbuilder_form_elements_article_categories', JPATH_ADMINISTRATOR);
        }
        
        /**
         * Displays settings in the form element options window
         * 
         * The members of the $options stdClass should be defaulted before doing anything as they might not exist yet!
         * 
         * @param stdClass $options
         * @return array associative with indices 'element_type' (string), 'has_hint' (boolean), 'show_init_code_settings' (boolean), 'show_validation_settings' (boolean), 'settings' (string)
         */
        function onSettingsDisplay($options){
            $start_cat = isset($options->startcat) ? $options->startcat : 0;
            $level_reduce = isset($options->level_reduce) && intval($options->level_reduce) > 0 ? intval($options->level_reduce) : 1;
            
            $settings = array();
            $settings['element_type'] = JText::_('COM_CONTENTBUILDER_TYPE_ARTICLE_CATEGORIES');
            $settings['has_hint'] = true;
            $settings['show_init_code_settings'] = true;
            $settings['show_validation_settings'] = true;
            
            $settings['settings'] = '<table class="admintable" width="100%"><tr><td width="100" align="left" class="key">';
            $settings['settings'] .= '<label for="artcat_start">'.JText::_('COM_CONTENTBUILDER_ARTICLE_CATEGORIES_START_CATEGORY').'</label></td><td align="left">';
            
            ob_start();
            $categories = $this->getCategories();
            jimport('joomla.version');
            $version = new JVersion();
            if (version_compare($version->getShortVersion(), '1.6', '>=')) {
            ?>
                <select id="artcat_start" name="artcat_start">
                    <option value="-1"><?php echo JText::_('COM_CONTENTBUILDER_ARTICLE_CATEGORIES_SHOWALL'); ?></option>
                   <?php
                    foreach ($categories As $category) {
                   ?>
                   <option <?php echo $start_cat == $category->value ? ' selected="selected"' : ''?>value="<?php echo $category->value; ?>"><?php echo htmlentities($category->text, ENT_QUOTES, 'UTF-8'); ?></option>
                   <?php
                   } 
                   ?>
                </select> 
            <?php
            } else {
            ?>
            <!-- Joomla 1.5 begin -->
            <select id="artcat_start" name="artcat_start">
                <option value="-1"><?php echo JText::_('COM_CONTENTBUILDER_ARTICLE_CATEGORIES_SHOWALL'); ?></option>
                <?php
                foreach ($categories As $section) {
                    ?>
                    <option <?php echo $start_cat == $section['id'] ? ' selected="selected"' : ''?>value="<?php echo $section['id'];?>"><?php echo $section['title']; ?></option>
                        <?php
                        foreach ($section['categories'] As $category) {
                            ?>
                            <option value="<?php echo $section['id'];?>"> - <?php echo htmlentities($category['title'], ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php
                        }
                        ?>
                    <?php
                }
                ?>
            </select>
            <!-- Joomla 1.5 end -->
            <?php
            }
            
            $c = ob_get_contents();
            ob_end_clean();
            
            $settings['settings'] .= $c;
            $settings['settings'] .= '</td></tr>';
            $settings['settings'] .= '<tr><td width="100" align="left" class="key">';
            if (version_compare($version->getShortVersion(), '1.6', '>=')) {
                $settings['settings'] .= '<label for="artcat_level_reduce">'.JText::_('COM_CONTENTBUILDER_ARTICLE_CATEGORIES_LEVEL_REDUCE').'</label>';
                $settings['settings'] .= '</td><td>';
                $settings['settings'] .= '<input  id="artcat_level_reduce" name="artcat_level_reduce" type="text" value="'.$level_reduce.'"/>';
            }
            $settings['settings'] .= '</td></tr>';
            $settings['settings'] .= '</table>';
            
            return $settings;
        }
        
        /**
         *
         * Handles the storage from onSettingsDisplay
         * 
         * @return type array with indices 'options' (stdClass), 'default_value' (string)
         */
        function onSettingsStore(){
            $options = new stdClass();
            $options->startcat = JRequest::getInt('artcat_start',0);
            $options->level_reduce = JRequest::getInt('artcat_level_reduce',1);
            return array('options' => $options, 'default_value' => '');
        }
        
        /**
         * Renders the elements in the form.
         * 
         * @param array $item
         * @param array $element
         * @param stdClass $options
         * @param array $failed_values
         * @param array $result
         * @param boolean $hasRecords
         * @return string 
         */
        function onRenderElement($item, $element, $options, $failed_values, $result, $hasRecords){
            
            jimport('joomla.version');
            $version = new JVersion();
            
            if(!isset($options->startcat)){
                if (version_compare($version->getShortVersion(), '1.6', '>=')) {
                    $options->startcat = 1;
                }else{
                    $options->startcat = 0;
                }
            }
            
            if(!isset($options->level_reduce) || intval($options->level_reduce) < 0){
                $options->level_reduce = 1;
            }
            
            if(!isset($options->length)){
               $options->length = '';
            }
            
            $cats = array();
            
            if (version_compare($version->getShortVersion(), '1.6', '>=')) {
                jimport('joomla.application.categories');
                $categories = JCategories::getInstance('content');
                $show = $options->startcat;
                if($options->startcat == -1){
                    $show = 'root';
                    $options->level_reduce = 0;
                }
                $subCategories = $categories->get($show, true);
                if(is_object($subCategories)){
                   $subCategories = $subCategories->getChildren(true);
                   foreach($subCategories As $subCategory){
                      $cats[$subCategory->id] = str_repeat('-', $subCategory->level - intval($options->level_reduce)) . ' ' . htmlentities($subCategory->title, ENT_QUOTES, 'UTF-8');  
                   }
                }
            }else{
                $show = '';
                if($options->startcat > -1){
                    $show = ' And section = '.$options->startcat;
                }
                $db = JFactory::getDBO();
                $query = 'SELECT id, title' .
                                ' FROM #__categories' .
                                ' WHERE access <= '.intval(JFactory::getUser()->get('id')).' And published = 1 '.$show.
                                ' ORDER BY ordering';
                $db->setQuery($query);
                $cat_list = $db->loadObjectList();
                foreach($cat_list As $cat){
                    $cats[$cat->id] = htmlentities($cat->title, ENT_QUOTES, 'UTF-8');
                }
            }
            
            $the_item = '<div class="cbFormField cbSelectField"><select id="cb_'.$item['id'].'" '.($options->length ? 'style="width:'.$options->length.';" ' : '').'name="cb_'.$item['id'].'[]">';
                               
            foreach($cats As $key => $value){
                $the_item .= '<option '.(intval($item['value']) == intval($key) ? 'selected="selected" ' : '').'value="'.intval($key).'">'.$value.'</option>';
            }
            
            $the_item .= '</select></div>';
            
            return $the_item;
        }
        
        /**
         * After submitting and the validation has been successfull, this can be used to take actions right after validation.
         * 
         * If you need to alter records/articles, you may pass an instance of the class "CBFormElementAfterValidation" as return value.
         * 
         * Once it will be returned, it MUST provide the methods onSaveRecord(int record_id) and onSaveArticle(int article_id), wether you use only one of them or both.
         * 
         * These methods will then be called after record/article creation/update.
         * 
         * @param array $field
         * @param array $fields
         * @param int $record_id
         * @param object $form
         * @param array $value
         * @return CBFormElementAfterValidation 
         */
        function onAfterValidationSuccess( $field, $fields, $record_id, $form, $value ){
            return new CBArtcatAfter($form, $field, implode('',$value));
        }
        
        /*
         * HELPER METHODS
         */
        
        function getCategories($startcat = 0){
            
            jimport('joomla.version');
            $version = new JVersion();

            if (version_compare($version->getShortVersion(), '1.6', '>=')) {

                return $this->getOptions($startcat);

            } else {

                // Joomla 1.5 begin
                // get sections and categories
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
                return $sections;
                // Joomla 1.5 end
            }
        }
        
        private function getOptions($startcat = 1) {
            
            if($startcat < 1){
                $startcat = 1;
            }
            
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

            if (isset($row) && !isset($options[0])) {
                if ($row->parent_id == '1') {
                    $parent = new stdClass();
                    $parent->text = JText::_('JGLOBAL_ROOT_PARENT');
                    array_unshift($options, $parent);
                }
            }

            return $options;
        }
}
