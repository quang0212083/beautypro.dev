<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_('behavior.keepalive');

jimport('joomla.filesystem.file');

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');

CBCompat::requireModel();

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder.php');

class ContentbuilderModelForm extends CBModel
{
    private $_form_data = null;
    
    private $_default_list_states = array( 
                                        array('id' => -1, 'action' => '', 'title' => 'State 1', 'color' => '60E309', 'published' => 1),
                                        array('id' => -2, 'action' => '', 'title' => 'State 2', 'color' => 'FCFC00', 'published' => 1),
                                        array('id' => -3, 'action' => '', 'title' => 'State 3', 'color' => 'FC0000', 'published' => 1),
                                        array('id' => -4, 'action' => '', 'title' => 'State 4', 'color' => 'FFFFFF', 'published' => 0),
                                        array('id' => -5, 'action' => '', 'title' => 'State 5', 'color' => 'FFFFFF', 'published' => 0),
                                        array('id' => -6, 'action' => '', 'title' => 'State 6', 'color' => 'FFFFFF', 'published' => 0),
                                        array('id' => -7, 'action' => '', 'title' => 'State 7', 'color' => 'FFFFFF', 'published' => 0),
                                        array('id' => -8, 'action' => '', 'title' => 'State 8', 'color' => 'FFFFFF', 'published' => 0),
                                        array('id' => -9, 'action' => '', 'title' => 'State 9', 'color' => 'FFFFFF', 'published' => 0),
                                        array('id' => -10, 'action' => '', 'title' => 'State 10', 'color' => 'FFFFFF', 'published' => 0)
                                    );
    
    function  __construct($config)
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $option = 'com_contentbuilder';

        $array = JRequest::getVar('cid',  0, '', 'array');
        $this->setId((int)$array[0]);
        if(JRequest::getInt('id',0)>0){
            $this->setId(JRequest::getInt('id',0));
        }

        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $filter_order     = $mainframe->getUserStateFromRequest(  $option.'elements_filter_order', 'filter_order', 'ordering', 'cmd' );
        $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'elements_filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );

        $this->setState('elements_filter_order', $filter_order);
        $this->setState('elements_filter_order_Dir', $filter_order_Dir);

        $filter_state = $mainframe->getUserStateFromRequest( $option.'elements_filter_state', 'filter_state', '', 'word' );
        $this->setState('elements_filter_state', $filter_state);

    }

    function setPublished()
    {
        if (empty( $this->_data )) {
            $this->_db->setQuery( ' Update #__contentbuilder_forms '.
                        '  Set published = 1 Where id = '.$this->_id );
            $this->_db->query();
        }
    }

    function setUnpublished()
    {
       if (empty( $this->_data )) {
           $this->_db->setQuery( ' Update #__contentbuilder_forms '.
                        '  Set published = 0 Where id = '.$this->_id );
           $this->_db->query();
       }
    }

    function setListPublished()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set published = 1 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListLinkable()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set linkable = 1 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListEditable()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set editable = 1 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListListInclude()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set list_include = 1 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListSearchInclude()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set search_include = 1 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }
    
    function setListUnpublished()
    {
       $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set published = 0 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListNotLinkable()
    {
       $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set linkable = 0 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListNotEditable()
    {
       $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set editable = 0 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListNoListInclude()
    {
       $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set list_include = 0 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function setListNoSearchInclude()
    {
       $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);
        if (count($items)) {
            $this->_db->setQuery( ' Update #__contentbuilder_elements '.
                        '  Set search_include = 0 Where form_id = '.$this->_id.' And id In ( '.implode(',', $items) . ')' );
            $this->_db->query();
        }
    }

    function getListStatesActionPlugins(){
        
        jimport('joomla.version');
        
        $db = JFactory::getDBO();
        
        $version = new JVersion();
        
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            
            $db->setQuery("Select `element` From #__extensions Where `folder` = 'contentbuilder_listaction' And `enabled` = 1");
            $res = CBCompat::loadColumn();
            return $res;
            
        } else {
            
            $db->setQuery("Select `element` From #__plugins Where `folder` = 'contentbuilder_listaction' And `published` = 1");
            $res = CBCompat::loadColumn();
            return $res;
        }
        
        return array();
    }
    
    function getThemePlugins(){
        
        jimport('joomla.version');
        
        $db = JFactory::getDBO();
        
        $version = new JVersion();
        
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            
            $db->setQuery("Select `element` From #__extensions Where `folder` = 'contentbuilder_themes' And `enabled` = 1");
            $res = CBCompat::loadColumn();
            
            // put the joomla3 theme on top of the theme stack for joomla3 users
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $i = 0;
                foreach($res As $theme){
                    if($theme == 'joomla3'){
                        unset($res[$i]);
                        $res = array_merge(array('joomla3'), $res);
                        break;
                    }
                    $i++;
                }
            }
            
            return $res;
            
        } else {
            
            $db->setQuery("Select `element` From #__plugins Where `folder` = 'contentbuilder_themes' And `published` = 1");
            $res = CBCompat::loadColumn();
            return $res;
        }
        
        return array();
    }
    
    function getVerificationPlugins(){
        jimport('joomla.version');
        
        $db = JFactory::getDBO();
        
        $version = new JVersion();
        
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            
            $db->setQuery("Select `element` From #__extensions Where `folder` = 'contentbuilder_verify' And `enabled` = 1");
            $res = CBCompat::loadColumn();
            return $res;
            
        } else {
            
            $db->setQuery("Select `element` From #__plugins Where `folder` = 'contentbuilder_verify' And `published` = 1");
            $res = CBCompat::loadColumn();
            return $res;
        }
        
        return array();
    }
    
    
    /*
     * MAIN DETAILS AREA
     */

    /**
     *
     * @param int $id
     */
    function setId($id) {
        // Set id and wipe data
        $this->_id      = $id;
        $this->_data    = null;
    }

    function getForm()
    {
        $query = ' Select * From #__contentbuilder_forms ' .
                '  Where id = ' . $this->_id;
        $this->_db->setQuery($query);
        $data = $this->_db->loadObject();

        if (!$data) {
            $data = new stdClass();
            $data->id = 0;
            $data->type = null;
            $data->reference_id = null;
            $data->name = null;
            $data->tag = null;
            $data->details_template = null;
            $data->details_prepare = null;
            $data->intro_text = null;
            $data->title = null;
            $data->created = null;
            $data->modified = null;
            $data->metadata = true;
            $data->export_xls = null;
            $data->print_button = true;
            $data->created_by = null;
            $data->modified_by = null;
            $data->published = null;
            $data->display_in = null;
            $data->published_only = null;
            $data->show_id_column = true;
            $data->select_column = false;
            $data->edit_button = false;
            $data->list_states = false;
            $data->config = null;
            $data->editable_prepare = null;
            $data->editable_template = null;
            $data->use_view_name_as_title = false;
            $data->list_states = $this->_default_list_states;
            $data->own_only = false;
            $data->own_only_fe = false;
            $data->list_state = false;
            $data->list_publish = false;
            $data->initial_sort_order = -1;
            $data->initial_sort_order2 = -1;
            $data->initial_sort_order3 = -1;
            $data->initial_order_dir = 'desc';
            $data->default_section = 0;
            $data->default_category = 0;
            $data->create_articles = 1;
            $data->title_field = 0;
            $data->delete_articles = 1;
            $data->edit_by_type = 0;
            $data->email_notifications = 1;
            $data->email_update_notifications = 0;
            $data->limited_article_options = 1;
            $data->limited_article_options_fe = 1;
            $data->upload_directory = JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'upload';
            $data->protect_upload_directory = 1;
            $data->limit_add = 0;
            $data->limit_edit = 0;
            $data->verification_required_view = 0;
            $data->verification_days_view = 0;
            $data->verification_required_new = 0;
            $data->verification_days_new = 0;
            $data->verification_required_edit = 0;
            $data->verification_days_edit = 0;
            $data->verification_url_new = '';
            $data->verification_url_view = '';
            $data->verification_url_edit = '';
            $data->default_lang_code = '*';
            $data->default_lang_code_ignore = 0;
            $data->show_all_languages_fe = 1;
            $data->list_language = 0;
            $data->default_publish_up_days = 0;
            $data->default_publish_down_days = 0;
            $data->default_access = 0;
            $data->default_featured = 0;
            $data->list_article = 0;
            $data->list_author = 0;
            $data->list_rating = 0;
            $data->email_template = '';
            $data->email_subject = '';
            $data->email_alternative_from = '';
            $data->email_alternative_fromname = '';
            $data->email_recipients = '';
            $data->email_recipients_attach_uploads = '';
            $data->email_html = '';
            
            $data->email_admin_template = '';
            $data->email_admin_subject = '';
            $data->email_admin_alternative_from = '';
            $data->email_admin_alternative_fromname = '';
            $data->email_admin_recipients = '';
            $data->email_admin_recipients_attach_uploads = '';
            $data->email_admin_html = '';
            
            $data->act_as_registration = 0;
            $data->registration_username_field = '';
            $data->registration_password_field = '';
            $data->registration_password_repeat_field = '';
            $data->registration_email_field = '';
            $data->registration_email_repeat_field = '';
            $data->registration_name_field = '';
            
            $data->auto_publish = 0;
            
            $data->force_login = 0;
            $data->force_url = '';
            
            $data->registration_bypass_plugin = '';
            $data->registration_bypass_plugin_params = '';
            $data->registration_bypass_verification_name = '';
            $data->registration_bypass_verify_view = '';
            
            $data->theme_plugin = '';
            
            $data->rating_slots = 5;
            
            $data->rand_date_update = '0000-00-00 00:00:00';
            
            $data->rand_update = '86400';
            
            $data->article_record_impact_publish = 0;
            $data->article_record_impact_language = 0;
            
            $data->allow_external_filter = 0;
            
            $data->show_filter = 1;
    
            $data->show_records_per_page = 1;
    
            $data->initial_list_limit = 20;
            
            $data->save_button_title = '';
    
            $data->apply_button_title = '';
            
            $data->filter_exact_match = 0;
        
            $data->ordering = 0;
        }

        $data->forms = array();
        $data->types = contentbuilder::getTypes();

        if($data->type){
            $data->forms = contentbuilder::getForms($data->type);
        }

        $data->form = null;
        if($data->type && $data->reference_id){
            $data->form = contentbuilder::getForm($data->type, $data->reference_id);
            if(!$data->form->exists){
                JFactory::getApplication()->redirect('index.php?option=com_contentbuilder&controller=forms&limitstart='.$this->getState('limitstart',0), JText::_('COM_CONTENTBUILDER_FORM_NOT_FOUND'), 'error');
            }
            if(isset($data->form->properties) && isset($data->form->properties->name)){
                $data->type_name = $data->form->properties->name;
            }else{
                $data->type_name = '';
            }
            $data->title = $data->form->getPageTitle();
            if(is_object($data->form)){
                contentbuilder::synchElements($data->id, $data->form);
                $elements_table = $this->getTable('elements');
                $elements_table->reorder('form_id='.$data->id);
            }
        }
        
        $db = JFactory::getDBO();
        $db->setQuery("Select * From #__contentbuilder_list_states Where form_id = " . $this->_id . " Order By id");
        $list_states = $db->loadAssocList();

        if (count($list_states)) {
            $data->list_states = $list_states;
        } else {
            $data->list_states = $this->_default_list_states;
        }

        $data->language_codes = contentbuilder::getLanguageCodes();
        
        $version = new JVersion();

        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
            
            $data->sectioncategories = $this->getOptions();
            $data->accesslevels = array();
            
        } else {
            
            $db->setQuery("Select * From #__groups");
            $data->accesslevels = $db->loadAssocList();
            
            // Joomla 1.5 begin
            // get sections and categories
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
            $data->sectioncategories = $sections;
            // Joomla 1.5 end
        }
        
        $this->_form_data = $data;

        return $data;
    }

    private function getOptions() {
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
    
    private function buildOrderBy() {
        $mainframe = JFactory::getApplication();
        $option = 'com_contentbuilder';

        $orderby = '';
        $filter_order     = $this->getState('elements_filter_order');
        $filter_order_Dir = $this->getState('elements_filter_order_Dir');

        /* Error handling is never a bad thing*/
        if(!empty($filter_order) && !empty($filter_order_Dir) ) {
            $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir . ' , ordering ';
        } else {
           $orderby = ' ORDER BY ordering ';
        }

        return $orderby;
    }


    function _buildQuery()
    {
        $filter_state = '';
        if($this->getState('elements_filter_state') == 'P' || $this->getState('elements_filter_state') == 'U')
        {
            $published = 0;
            if($this->getState('elements_filter_state') == 'P')
            {
                $published = 1;
            }

            $filter_state .= ' And published = ' . $published;
        }

        return "Select * From #__contentbuilder_elements Where form_id = " . $this->_id . $filter_state . $this->buildOrderBy();
    }

    function getData()
    {
        $this->_db->setQuery($this->_buildQuery(), $this->getState('limitstart'), $this->getState('limit'));
        $entries = $this->_db->loadObjectList();
        return $entries;
    }
    
    function getAllElements()
    {
        $this->_db->setQuery($this->_buildQuery());
        $entries = $this->_db->loadObjectList();
        return $entries;
    }

    function store()
    {
        $db = JFactory::getDBO();
        $row = $this->getTable();
        $form = $this->getForm();
        $form_id = 0;
        
        $data                      = JRequest::get( 'post' );
        $data['details_template']  = JRequest::getVar( 'details_template', '', 'POST', 'STRING', JREQUEST_ALLOWRAW );
        $data['editable_template'] = JRequest::getVar( 'editable_template', '', 'POST', 'STRING', JREQUEST_ALLOWRAW );
        $data['details_prepare']   = JRequest::getVar( 'details_prepare', '', 'POST', 'STRING', JREQUEST_ALLOWRAW );
        $data['editable_prepare']  = JRequest::getVar( 'editable_prepare', '', 'POST', 'STRING', JREQUEST_ALLOWRAW );
        $data['intro_text']        = JRequest::getVar( 'intro_text', '', 'POST', 'STRING', JREQUEST_ALLOWHTML );
        $data['editable']          = JRequest::getVar( 'editable', '', 'POST', 'STRING', JREQUEST_ALLOWHTML );
        $data['email_admin_template']  = JRequest::getVar( 'email_admin_template', '', 'POST', 'STRING', JREQUEST_ALLOWRAW );
        $data['email_template']    = JRequest::getVar( 'email_template', '', 'POST', 'STRING', JREQUEST_ALLOWRAW );
        
        #### SETTINGS
        $data['create_articles'] = JRequest::getInt('create_articles', 0);
        
        $data['protect_upload_directory'] = JRequest::getInt('protect_upload_directory', 0);
        
        //$data['upload_directory'] = JPATH_SITE . DS . 'media/contentbuilder/upload';
        //$data['protect_upload_directory'] = 1;
        
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        
        // determine if it contains a replacement
        $tokens = '';
                
        $upl_ex = explode('|',$data['upload_directory']);
        $data['upload_directory'] = $upl_ex[0];

        $is_relative = strpos(strtolower($data['upload_directory']), '{cbsite}') === 0;
        
        $tmp_upload_directory = $data['upload_directory'];
        $upload_directory = $is_relative ? str_replace(array('{CBSite}','{cbsite}'), JPATH_SITE, $data['upload_directory']) : $data['upload_directory'];
        $data['upload_directory'] = $upload_directory;
        
        $protect = $data['protect_upload_directory'];
        
        // if not exissting, we create the fallback directory
        if(!JFolder::exists($upload_directory)){
                    
            if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder')){
                JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder');
                JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'index.html', $def = '');
            }

            if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'upload')){
                JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'upload');
                JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'upload' . DS . 'index.html', $def = '');

                if($protect){
                    JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'upload' . DS . '.htaccess', $def = 'deny from all');
                }
            }
            
            $data['upload_directory'] = JPATH_SITE . DS . 'media'.DS.'contentbuilder'.DS.'upload';
            
            if($is_relative){
                $tmp_upload_directory = '{CBSite}' . DS . 'media'.DS.'contentbuilder'.DS.'upload';
            }
            
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENTBUILDER_FALLBACK_UPLOAD_CREATED') . ' ('.DS.'media'.DS.'contentbuilder'.DS.'upload'.')', 'warning');
        }
        
        if( isset($upl_ex[1]) ){
            $tokens = '|'.$upl_ex[1];
        }
        
        if($data['protect_upload_directory'] && JFolder::exists(contentbuilder::makeSafeFolder($data['upload_directory']))){
            if(!JFile::exists(contentbuilder::makeSafeFolder($data['upload_directory']) . DS . 'index.html')) JFile::write(contentbuilder::makeSafeFolder($data['upload_directory']) . DS . 'index.html', $def = '');
        }
        
        if($data['protect_upload_directory'] && JFolder::exists(contentbuilder::makeSafeFolder($data['upload_directory']))){
            
            if(!JFile::exists(contentbuilder::makeSafeFolder($data['upload_directory']) . DS . '.htaccess')) JFile::write(contentbuilder::makeSafeFolder($data['upload_directory']) . DS . '.htaccess', $def = 'deny from all');
        
        }else {
        
            if(JFile::exists(contentbuilder::makeSafeFolder($data['upload_directory']) . DS . '.htaccess')) JFile::delete(contentbuilder::makeSafeFolder($data['upload_directory']) . DS . '.htaccess');
        
        }
        
        // reverting back to possibly including cbsite replacement
        $data['upload_directory'] = $tmp_upload_directory . $tokens;
        
        #### USERS
        $data['verification_required_view'] = JRequest::getInt('verification_required_view', 0);
        $data['verification_required_new'] = JRequest::getInt('verification_required_new', 0);
        $data['verification_required_edit'] = JRequest::getInt('verification_required_edit', 0);
        
        #### MISC
        $data['show_all_languages_fe'] = JRequest::getInt('show_all_languages_fe', 0);
        
        if(!$data['show_all_languages_fe'] && !$data['default_lang_code_ignore']){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENTBUILDER_LANGUAGE_WARNING'), 'warning');
        }
        
        #### PERMISSIONS
        
        $gmap = array();
        $config = array();
        $config['permissions'] = array();
        $config['permissions_fe'] = array();
        $config['own'] = array();
        $config['own_fe'] = array();
        
        // backend
        
        if (isset($data['own']) && isset($data['own']['view']) && intval($data['own']['view']) == 1) {
            $config['own']['view'] = true;
        }
        if (isset($data['own']) && isset($data['own']['edit']) && intval($data['own']['edit']) == 1) {
            $config['own']['edit'] = true;
        }
        if (isset($data['own']) && isset($data['own']['delete']) && intval($data['own']['delete']) == 1) {
            $config['own']['delete'] = true;
        }
        if (isset($data['own']) && isset($data['own']['state']) && intval($data['own']['state']) == 1) {
            $config['own']['state'] = true;
        }
        if (isset($data['own']) && isset($data['own']['publish']) && intval($data['own']['publish']) == 1) {
            $config['own']['publish'] = true;
        }
        if (isset($data['own']) && isset($data['own']['fullarticle']) && intval($data['own']['fullarticle']) == 1) {
            $config['own']['fullarticle'] = true;
        }
        if (isset($data['own']) && isset($data['own']['listaccess']) && intval($data['own']['listaccess']) == 1) {
            $config['own']['listaccess'] = true;
        }
        if (isset($data['own']) && isset($data['own']['new']) && intval($data['own']['new']) == 1) {
            $config['own']['new'] = true;
        }
        if (isset($data['own']) && isset($data['own']['language']) && intval($data['own']['language']) == 1) {
            $config['own']['language'] = true;
        }
        if (isset($data['own']) && isset($data['own']['rating']) && intval($data['own']['rating']) == 1) {
            $config['own']['rating'] = true;
        }
        
        // frontend
        
        if (isset($data['own_fe']) && isset($data['own_fe']['view']) && intval($data['own_fe']['view']) == 1) {
            $config['own_fe']['view'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['edit']) && intval($data['own_fe']['edit']) == 1) {
            $config['own_fe']['edit'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['delete']) && intval($data['own_fe']['delete']) == 1) {
            $config['own_fe']['delete'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['state']) && intval($data['own_fe']['state']) == 1) {
            $config['own_fe']['state'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['publish']) && intval($data['own_fe']['publish']) == 1) {
            $config['own_fe']['publish'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['fullarticle']) && intval($data['own_fe']['fullarticle']) == 1) {
            $config['own_fe']['fullarticle'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['listaccess']) && intval($data['own_fe']['listaccess']) == 1) {
            $config['own_fe']['listaccess'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['new']) && intval($data['own_fe']['new']) == 1) {
            $config['own_fe']['new'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['language']) && intval($data['own_fe']['language']) == 1) {
            $config['own_fe']['language'] = true;
        }
        if (isset($data['own_fe']) && isset($data['own_fe']['rating']) && intval($data['own_fe']['rating']) == 1) {
            $config['own_fe']['rating'] = true;
        }
        
        jimport('joomla.version');
        $version = new JVersion();
        if (version_compare($version->getShortVersion(), '1.6', '<')) {
            $acl = JFactory::getACL();
            $gmap = $acl->get_group_children_tree( null, 'USERS', false );
        }else{
            $db = JFactory::getDbo();
            $query = 'SELECT CONCAT( REPEAT(\'..\', COUNT(parent.id) - 1), node.title) as text, node.id as value'
                    . ' FROM #__usergroups AS node, #__usergroups AS parent'
                    . ' WHERE node.lft BETWEEN parent.lft AND parent.rgt'
                    . ' GROUP BY node.id'
                    . ' ORDER BY node.lft';
            $db->setQuery($query);
            $gmap = $db->loadObjectList();
        }
        
        foreach ($gmap As $entry) {
            
            // backend
            
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['listaccess']) && intval($data['perms'][$entry->value]['listaccess']) == 1) {
                $config['permissions'][$entry->value]['listaccess'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['view']) && intval($data['perms'][$entry->value]['view']) == 1) {
                $config['permissions'][$entry->value]['view'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['new']) && intval($data['perms'][$entry->value]['new']) == 1) {
                $config['permissions'][$entry->value]['new'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['edit']) && intval($data['perms'][$entry->value]['edit']) == 1) {
                $config['permissions'][$entry->value]['edit'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['delete']) && intval($data['perms'][$entry->value]['delete']) == 1) {
                $config['permissions'][$entry->value]['delete'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['state']) && intval($data['perms'][$entry->value]['state']) == 1) {
                $config['permissions'][$entry->value]['state'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['publish']) && intval($data['perms'][$entry->value]['publish']) == 1) {
                $config['permissions'][$entry->value]['publish'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['fullarticle']) && intval($data['perms'][$entry->value]['fullarticle']) == 1) {
                $config['permissions'][$entry->value]['fullarticle'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['language']) && intval($data['perms'][$entry->value]['language']) == 1) {
                $config['permissions'][$entry->value]['language'] = true;
            }
            if (isset($data['perms'][$entry->value]) && isset($data['perms'][$entry->value]['rating']) && intval($data['perms'][$entry->value]['rating']) == 1) {
                $config['permissions'][$entry->value]['rating'] = true;
            }
            
            
            // frontend
            
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['listaccess']) && intval($data['perms_fe'][$entry->value]['listaccess']) == 1) {
                $config['permissions_fe'][$entry->value]['listaccess'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['view']) && intval($data['perms_fe'][$entry->value]['view']) == 1) {
                $config['permissions_fe'][$entry->value]['view'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['new']) && intval($data['perms_fe'][$entry->value]['new']) == 1) {
                $config['permissions_fe'][$entry->value]['new'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['edit']) && intval($data['perms_fe'][$entry->value]['edit']) == 1) {
                $config['permissions_fe'][$entry->value]['edit'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['delete']) && intval($data['perms_fe'][$entry->value]['delete']) == 1) {
                $config['permissions_fe'][$entry->value]['delete'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['state']) && intval($data['perms_fe'][$entry->value]['state']) == 1) {
                $config['permissions_fe'][$entry->value]['state'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['publish']) && intval($data['perms_fe'][$entry->value]['publish']) == 1) {
                $config['permissions_fe'][$entry->value]['publish'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['fullarticle']) && intval($data['perms_fe'][$entry->value]['fullarticle']) == 1) {
                $config['permissions_fe'][$entry->value]['fullarticle'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['language']) && intval($data['perms_fe'][$entry->value]['language']) == 1) {
                $config['permissions_fe'][$entry->value]['language'] = true;
            }
            if (isset($data['perms_fe'][$entry->value]) && isset($data['perms_fe'][$entry->value]['rating']) && intval($data['perms_fe'][$entry->value]['rating']) == 1) {
                $config['permissions_fe'][$entry->value]['rating'] = true;
            }
        }
        
        // remove perms
        if(isset($data['perms'])){
            unset($data['perms']);
        }
        if(isset($data['perms_fe'])){
            unset($data['perms_fe']);
        }
        if(isset($data['own'])){
            unset($data['own']);
        }
        if(isset($data['own_fe'])){
            unset($data['own_fe']);
        }
        
        ### PERMISSIONS END
        
        $list_states = $data['list_states'];
        unset($data['list_states']);
        
        $version = new JVersion();

        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
        
            $data['default_category'] = JRequest::getInt('sectioncategories',0);
        
        } else {
        
            // Joomla 1.5 begin
            $sectioncategory = explode(':',JRequest::getVar('sectioncategories',''));
            $data['default_section'] = intval($sectioncategory[0]);
            $data['default_category'] = intval(isset($sectioncategory[1]) ? $sectioncategory[1] : 0) ;
            // Joomla 1.5 end
        }
        
        $data['edit_by_type'] = JRequest::getInt('edit_by_type',0);
        if($data['edit_by_type'] && $data['type'] == 'com_breezingforms'){
            if(isset($data['type_name'])){
                $data['editable_template'] = "{BreezingForms: ".$data['type_name']."}";
            }
        }
        
        $data['act_as_registration'] = JRequest::getInt('act_as_registration',0);
        if($data['edit_by_type'] && $data['act_as_registration']){
            $data['act_as_registration'] = 0;
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENTBUILDER_ACT_AS_REGISTRATION_WARNING'), 'warning');
        }
        
        if($data['act_as_registration'] && ( 
                !$data['registration_name_field'] ||
                !$data['registration_username_field'] ||
                !$data['registration_email_field'] ||
                !$data['registration_email_repeat_field'] ||
                !$data['registration_password_field'] ||
                !$data['registration_password_repeat_field']
        ) ){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENTBUILDER_ACT_AS_REGISTRATION_MISSING_FIELDS_WARNING'), 'warning');
        }
        
        $data['email_notifications'] = JRequest::getInt('email_notifications',0);
        
        $data['limited_article_options'] = JRequest::getInt('limited_article_options',0);
        $data['limited_article_options_fe'] = JRequest::getInt('limited_article_options_fe',0);
        
        $data['own_only'] = JRequest::getInt('own_only',0);
        $data['own_only_fe'] = JRequest::getInt('own_only_fe',0);
        
        $data['config'] = cb_b64enc(serialize($config));
        
        contentbuilder::createBackendMenuItem($form->id, $form->name, JRequest::getInt('display_in',0));

        if(JRequest::getBool('create_sample',false)){
            $data['details_template'] .= contentbuilder::createDetailsSample($form->id, $form->form, $data['theme_plugin']);
        }
        
        if(JRequest::getBool('create_editable_sample',false)){
            $data['editable_template'] .= contentbuilder::createEditableSample($form->id, $form->form, $data['theme_plugin']);
        }
        
        if(JRequest::getBool('email_admin_create_sample',false)){
            $data['email_admin_template'] .= contentbuilder::createEmailSample($form->id, $form->form, JRequest::getBool('email_admin_html', false));
        }
        
        if(JRequest::getBool('email_create_sample',false)){
            $data['email_template'] .= contentbuilder::createEmailSample($form->id, $form->form, JRequest::getBool('email_html', false));
        }

        if(!JRequest::getBool('show_filter',false)){
            $data['show_filter'] = 0;
        }
        
        if(!JRequest::getBool('show_records_per_page',false)){
            $data['show_records_per_page'] = 0;
        }
        
        if(!JRequest::getBool('metadata',false)){
            $data['metadata'] = 0;
        }

        if(!JRequest::getBool('export_xls',false)){
            $data['export_xls'] = 0;
        }

        if(!JRequest::getBool('print_button',false)){
            $data['print_button'] = 0;
        }
        
        if(JRequest::getVar('tag','') == ''){
            $data['tag'] = 'default';
        }

        if($form->form){
            $data['title'] = $form->form->getPageTitle();
        }
        
        $last_update = JFactory::getDate();
        $last_update = CBCompat::toSql($last_update);
        $data['last_update'] = $last_update;
        
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (!$row->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $form_id = 0;
        $storeRes = $row->store();

        if (!$storeRes) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        else
        {
            if( intval($data['id']) != 0 )
            {
                $form_id = intval($data['id']);
                foreach($list_states As $state_id => $item){
                    if(intval($state_id)){
                        $db->setQuery("Update #__contentbuilder_list_states Set published = ".$db->Quote(isset($item['published']) && $item['published'] ? 1 : 0).", `title` = ".$db->Quote(stripslashes(strip_tags($item['title']))).", color = ".$db->Quote(stripslashes(strip_tags($item['color']))).", action = ".$db->Quote($item['action'])." Where form_id = $form_id And id = " . intval($state_id));
                        $db->query();
                    }
                }
                
                // FALLBACK IF SOMEHOW REMOVED FROM DATABASE
                if(count($list_states) < count($this->_default_list_states)){
                    $add_count = count($this->_default_list_states) - count($list_states);
                    for($i = 0; $i <= $add_count; $i++){
                        $db->setQuery("Insert Into #__contentbuilder_list_states (form_id,`title`,color,action) Values ($form_id,".$db->Quote('State').",".$db->Quote('FFFFFF').",".$db->Quote('').")");
                        $db->query();
                    }
                }
            }
            else
            {
                $form_id = $this->_db->insertid();
                foreach($list_states As $item){
                    $db->setQuery("Insert Into #__contentbuilder_list_states (form_id,`title`,color,action, published) Values ($form_id,".$db->Quote(stripslashes(strip_tags($item['title']))).",".$db->Quote($item['color']).",".$db->Quote($item['action']).",".$db->Quote(isset($item['published']) && $item['published'] ? 1 : 0).")");
                    $db->query();
                }
                
                // FALLBACK IF SOMEHOW REMOVED FROM DATABASE
                if(count($list_states) < count($this->_default_list_states)){
                    $add_count = count($this->_default_list_states) - count($list_states);
                    for($i = 0; $i <= $add_count; $i++){
                        $db->setQuery("Insert Into #__contentbuilder_list_states (form_id,`title`,color,action) Values ($form_id,".$db->Quote('State').",".$db->Quote('FFFFFF').",".$db->Quote('').")");
                        $db->query();
                    }
                }
            }
            
            // is the list states empty?
            $db->setQuery("Select id From #__contentbuilder_list_states Where form_id = " . $form_id . " Limit 1");
            $has_states = $db->loadResult();
            if(!$has_states){
                $add_count = count($this->_default_list_states);
                for ($i = 0; $i <= $add_count; $i++) {
                    $db->setQuery("Insert Into #__contentbuilder_list_states (form_id,`title`,color,action) Values ($form_id," . $db->Quote('State') . "," . $db->Quote('FFFFFF') . "," . $db->Quote('') . ")");
                    $db->query();
                }
            }
        }

        $row->reorder();

        $item_wrapper = JRequest::getVar( 'itemWrapper', '', 'POST', 'ARRAY', JREQUEST_ALLOWRAW );
        $wordwrap = JRequest::getVar( 'itemWordwrap', array(), 'post', 'array' );
        $labels = JRequest::getVar( 'itemLabels', array(), 'post', 'array' );
        $order_types = JRequest::getVar( 'itemOrderTypes', array(), 'post', 'array' );
        JArrayHelper::toInteger($wordwrap);

        foreach($item_wrapper As $elementId => $value){
            $this->_db->setQuery("Update #__contentbuilder_elements Set `order_type` = ".$this->_db->Quote($order_types[$elementId]).", `label`= ".$this->_db->Quote($labels[$elementId]).", `wordwrap` = ".$this->_db->Quote($wordwrap[$elementId]).", `item_wrapper` =  ".$this->_db->Quote(trim($value))." Where form_id = $form_id And id = " . $elementId);
            $this->_db->query();
        }

        return $form_id;
    }

    function delete()
    {
        jimport( 'joomla.database.table' );
        jimport( 'joomla.event.dispatcher' );
        jimport('joomla.version');
                 
        $is15 = true;
        $version = new JVersion();
        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
           $is15 = false; 
        }
        
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        JArrayHelper::toInteger($cids);
        $row = $this->getTable();

        foreach($cids as $cid) {
   
            $this->_db->setQuery("Select article.article_id From #__contentbuilder_articles As article, #__contentbuilder_forms As form Where form.delete_articles > 0 And form.id = article.form_id And article.form_id = ".intval($cid));
            $articles = CBCompat::loadColumn();
            if( count($articles) ){
                $article_items = array();
                foreach($articles As $article){
                    $article_items[] = $this->_db->Quote('com_content.article.'.$article);
                    $dispatcher = JDispatcher::getInstance();
                    $table = JTable::getInstance('content');
                    // Trigger the onContentBeforeDelete event.
                    if(!$is15 && $table->load($article)){
                        $dispatcher->trigger('onContentBeforeDelete', array('com_content.article', $table));
                    }
                    $this->_db->setQuery("Delete From #__content Where id = ".intval($article));
                    $this->_db->query();
                    // Trigger the onContentAfterDelete event.
                    $table->reset();
                    if(!$is15){
                        $dispatcher->trigger('onContentAfterDelete', array('com_content.article', $table));
                    }
                }
                $this->_db->setQuery("Delete From #__assets Where `name` In (".implode(',', $article_items).")");
                $this->_db->query();
            }

            
            $this->_db->setQuery("
                Delete
                    `elements`.*
                From
                    #__contentbuilder_elements As `elements`
                Where
                    `elements`.form_id = " . $cid);

            $this->_db->query();
            
            $this->_db->setQuery("
                Delete
                    `states`.*
                From
                    #__contentbuilder_list_states As `states`
                Where
                    `states`.form_id = " . $cid);

            $this->_db->query();
            
            $this->_db->setQuery("
                Delete
                    `records`.*
                From
                    #__contentbuilder_list_records As `records`
                Where
                    `records`.form_id = " . $cid);

            $this->_db->query();
            
            $this->_db->setQuery("
                Delete
                    `access`.*
                From
                    #__contentbuilder_resource_access As `access`
                Where
                    `access`.form_id = " . $cid);

            $this->_db->query();
            
            $this->_db->setQuery("
                Delete
                    `users`.*
                From
                    #__contentbuilder_users As `users`
                Where
                    `users`.form_id = " . $cid);

            $this->_db->query();
            
            $this->_db->setQuery("
                Delete
                    `users`.*
                From
                    #__contentbuilder_registered_users As `users`
                Where
                    `users`.form_id = " . $cid);

            $this->_db->query();

            $this->getTable('elements')->reorder('form_id = ' . $cid);

            jimport('joomla.version');
            $version = new JVersion();

            if(version_compare($version->getShortVersion(), '1.6', '<')){
                $this->_db->setQuery("Delete From #__components Where admin_menu_link = 'option=com_contentbuilder&controller=list&id=".intval($cid)."'");
                $this->_db->query();
                $this->_db->setQuery("Select count(id) From #__components Where admin_menu_link Like 'option=com_contentbuilder&controller=list&id=%'");
                $amount = $this->_db->loadResult();
                if(!$amount){
                    $this->_db->setQuery("Delete From #__components Where admin_menu_link = 'option=com_contentbuilder&viewcontainer=true'");
                    $this->_db->query();
                }
            }else{
                $this->_db->setQuery("Delete From #__menu Where `link` = 'index.php?option=com_contentbuilder&controller=list&id=".intval($cid)."'");
                $this->_db->query();
                $this->_db->setQuery("Select count(id) From #__menu Where `link` Like 'index.php?option=com_contentbuilder&controller=list&id=%'");
                $amount = $this->_db->loadResult();
                if(!$amount){
                    $this->_db->setQuery("Delete From #__menu Where `link` = 'index.php?option=com_contentbuilder&viewcontainer=true'");
                    $this->_db->query();
                }
            }

            if (!$row->delete( $cid )) {
                $this->setError( $row->getErrorMsg() );
                return false;
            }
        }

        $row->reorder();

        /*
        $this->_db->setQuery("Select `reference_id` From #__contentbuilder_forms");
        $references = $this->_db->loadResultArray();

        $cnt = count($references);
        if ($cnt) {
            $new_items = array();
            for ($i = 0; $i < $cnt; $i++) {
                $new_items[] = $this->_db->Quote($references[$i]);
            }
            $this->_db->setQuery("Delete From #__contentbuilder_records Where `reference_id` Not In (" . implode(',',$new_items) . ") ");
            $this->_db->query();
        }else{
            $this->_db->setQuery("Delete From #__contentbuilder_records");
            $this->_db->query();
        }*/
        
        // article deletion if required
        $this->_db->setQuery("Select `id` From #__contentbuilder_forms");
        
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            $references = $this->_db->loadColumn();
        }else{
            $references = $this->_db->loadResultArray();
        }
        
        $cnt = count($references);
        if ($cnt) {
            $new_items = array();
            for ($i = 0; $i < $cnt; $i++) {
                $new_items[] = $this->_db->Quote($references[$i]);
            }
            $this->_db->setQuery("Delete From #__contentbuilder_articles Where `form_id` Not In (" . implode(',',$new_items) . ") ");
            $this->_db->query();
        }else{
            $this->_db->setQuery("Delete From #__contentbuilder_articles");
            $this->_db->query();
        }
        
        return true;
    }

    function listDelete()
    {
        $cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
        JArrayHelper::toInteger($cids);
        foreach($cids as $cid) {
            $this->_db->setQuery("
                Delete
                    `elements`.*
                From
                    #__contentbuilder_elements As `elements`
                Where
                    `elements`.id = " . $cid);

            $this->_db->query();
            $this->getTable('elements')->reorder('form_id = ' . $this->_id);
        }
    }

    function move($direction) {

      $db = JFactory::getDBO();
      $mainframe = JFactory::getApplication();

      $row = $this->getTable('form');

      if (!$row->load($this->_id)) {
         $this->setError($db->getErrorMsg());
         return false;
      }

      if (!$row->move( $direction )) {
         $this->setError($db->getErrorMsg());
         return false;
      }

      return true;
   }

   function listMove($direction) {
      $mainframe = JFactory::getApplication();
      $items = JRequest::getVar( 'cid', array(), 'post', 'array' );
      JArrayHelper::toInteger($items);

      if(count($items)){
          $db = JFactory::getDBO();
          $row = $this->getTable('elements');

          if (!$row->load($items[0])) {
             $this->setError($db->getErrorMsg());
             return false;
          }

          if (!$row->move( $direction, 'form_id='.$this->_id )) {
             $this->setError($db->getErrorMsg());
             return false;
          }
      }

      return true;
   }

   function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }
    
    function listSaveOrder()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);

        $total		= count( $items );
        $row		= $this->getTable('elements');
        $groupings	= array();

        $order		= JRequest::getVar( 'order', array(), 'post', 'array' );
        JArrayHelper::toInteger($order);

        // update ordering values
        for( $i=0; $i < $total; $i++ ) {
            $row->load( $items[$i] );
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    $this->setError($row->getError());
                    return false;
                }
            } // if
        } // for


        $row->reorder("form_id = " . $this->_id);
    }
}
