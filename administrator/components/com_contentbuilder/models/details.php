<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/


// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');

CBCompat::requireModel();

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder.php');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder_helpers.php');

class ContentbuilderModelDetails extends CBModel
{
    private $_record_id = 0;

    private $frontend = false;
    
    private $_show_back_button = true;
    
    private $_menu_item = false;
    
    private $_show_page_heading = true;
    
    private $_menu_filter = array();
    
    private $_menu_filter_order = array();
    
    private $_latest = false;
    
    private $_page_title = '';
    
    private $_page_heading = '';
    
    function  __construct($config)
    {
        parent::__construct();

        $option = 'com_contentbuilder';

        $this->frontend = JFactory::getApplication()->isSite();
        
        // ATTTENTION: ALSO DEFINED IN DETAILS CONTROLLER!
        if($this->frontend && JRequest::getInt('Itemid',0)){
            $this->_menu_item = true;
            
            // try menu item
            jimport('joomla.version');
            $version = new JVersion();

            if(version_compare($version->getShortVersion(), '1.6', '>=')){
                $menu = JFactory::getApplication()->getMenu();
                $item = $menu->getActive();
                if (is_object($item)) {
                    if($item->params->get('record_id', null) !== null){
                        JRequest::setVar('record_id', $item->params->get('record_id', null));
                        $this->_show_back_button = $item->params->get('show_back_button', null);
                    }
                    
                    if($item->params->get('cb_latest', null) !== null){
                        $this->_latest = $item->params->get('cb_latest', null);
                        $this->_show_back_button = $item->params->get('show_back_button', null);
                    }
                    if($item->params->get('show_page_heading', null) !== null){
                        $this->_show_page_heading = $item->params->get('show_page_heading', null);
                    }
                    if($item->params->get('page_title', null) !== null){
                        $this->_page_title = $item->params->get('page_title', null);
                    }
                    if($item->params->get('page_heading', null) !== null){
                        $this->_page_heading = $item->params->get('page_heading', null);
                    }
                }

            }else{
                $params = JComponentHelper::getParams( $option );
                if($params->get('record_id', null)){
                    JRequest::setVar('record_id', $params->get('record_id', null));
                    $this->_show_back_button = $params->get('show_back_button', null);
                }
                
                if($params->get('cb_latest', null)){
                    $this->_latest = $params->get('cb_latest', null);
                    $this->_show_back_button = $params->get('show_back_button', null);
                }
                if($params->get('show_page_heading', null) !== null){
                    $this->_show_page_heading = $params->get('show_page_heading', null);
                }
                if($params->get('page_title', null) !== null){
                    $this->_page_title = $params->get('page_title', null);
                }
            }
        }
        
        $menu_filter = JRequest::getVar('cb_list_filterhidden', null);
        
        if($menu_filter !== null){
            $lines  = explode("\n", $menu_filter);
            foreach($lines As $line){
                $keyval = explode("\t", $line);
                if(count($keyval) == 2){
                    $keyval[1] = str_replace( array("\n","\r"), "", $keyval[1] );
                    $keyval[1] = contentbuilder::execPhpValue($keyval[1]);
                    if($keyval[1] != ''){
                        $this->_menu_filter[$keyval[0]] = explode('|',$keyval[1]);
                    }
                }
            }
        }
        
        $menu_filter_order = JRequest::getVar('cb_list_orderhidden', null);
        
        if($menu_filter_order !== null){
            $lines  = explode("\n", $menu_filter_order);
            foreach($lines As $line){
                $keyval = explode("\t", $line);
                if(count($keyval) == 2){
                    $keyval[1] = str_replace( array("\n","\r"), "", $keyval[1] );
                    if($keyval[1] != ''){
                        $this->_menu_filter_order[$keyval[0]] = intval($keyval[1]);
                    }
                }
            }
        }
        
        @natsort($this->_menu_filter_order);
        
        $this->setIds(JRequest::getInt('id',  0), JRequest::getCmd('record_id',  ''));
    }

    /*
     * MAIN DETAILS AREA
     */

    /**
     *
     * @param int $id
     */
    function setIds($id, $record_id) {
        // Set id and wipe data
        $this->_id = $id;
        $this->_record_id = $record_id;
        $this->_data = null;
    }

    private function _buildQuery(){
        return 'Select SQL_CALC_FOUND_ROWS * From #__contentbuilder_forms Where id = '.intval($this->_id).' And published = 1';
    }

    /**
    * Gets the currencies
    * @return array List of currencies
    */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, 0, 1);

            if(!count($this->_data)){
                JError::raiseError(404, JText::_('COM_CONTENTBUILDER_FORM_NOT_FOUND'));
            }

            foreach($this->_data As $data){
                
                if(!$this->frontend && $data->display_in == 0){
                    JError::raiseError(404, JText::_('COM_CONTENTBUILDER_RECORD_NOT_FOUND'));
                }else if($this->frontend && $data->display_in == 1){
                   JError::raiseError(404, JText::_('COM_CONTENTBUILDER_RECORD_NOT_FOUND'));
                }
                
                $data->form_id = $this->_id;
                $data->record_id = $this->_record_id;
                
                if($data->type && $data->reference_id){
                    
                    $data->form = contentbuilder::getForm($data->type, $data->reference_id);
                    
                    $data->labels = $data->form->getElementLabels();
                    $ids = array();
                    foreach($data->labels As $reference_id => $label){
                        $ids[] = $this->_db->Quote($reference_id);
                    }
                    
                    if(count($ids)){
                        $this->_db->setQuery("Select Distinct `label`, reference_id From #__contentbuilder_elements Where form_id = " . intval($this->_id) . " And reference_id In (" . implode(',', $ids) . ") And published = 1 Order By ordering");
                        $rows = $this->_db->loadAssocList();
                        $ids = array();
                        foreach($rows As $row){
                           $ids[] = $row['reference_id'];
                        }
                    }
                    
                    if( $this->_latest ){
                        
                        $rec = $data->form->getListRecords($ids, '', array(), 0, 1, '', array(), 'desc', 0, false, JFactory::getUser()->get('id', 0), 0, -1, -1, -1, -1, array(), true, null);
                        
                        if(count($rec) > 0){
                            
                            $rec = $rec[0];
                            $rec2 = $data->form->getRecord($rec->colRecord, false, -1, true );
                            
                            $data->record_id = $rec->colRecord;
                            JRequest::setVar('record_id', $data->record_id);
                            $this->_record_id = $data->record_id;
                            
                        } else {
                            
                            JRequest::setVar('cbIsNew', 1);
                            contentbuilder::setPermissions(JRequest::getInt('id',0),0, $this->frontend ? '_fe' : '');
                            $auth = $this->frontend ? contentbuilder::authorizeFe('new') : contentbuilder::authorize('new');
                            
                            if($auth){
                                JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_contentbuilder&controller=edit&latest=1&backtolist='.JRequest::getInt('backtolist',0).'&id='.$this->_id.'&record_id=&limitstart='.JRequest::getInt('limitstart',0).'&filter_order='.JRequest::getVar('filter_order',''), false));
                            } else {
                               JFactory::getApplication()->enqueueMessage(JText::_('COM_CONTENTBUILDER_ADD_ENTRY_FIRST'));
                               JFactory::getApplication()->redirect('index.php', false);
                            }
                        }
                    }
                   
                    $data->show_page_heading = $this->_show_page_heading;
                    if(!$data->form->exists){
                        JError::raiseError(404, JText::_('COM_CONTENTBUILDER_FORM_NOT_FOUND'));
                    }
                    $data->page_title = '';
                    if(JRequest::getInt('cb_prefix_in_title', 1)){
                        if(!$this->_menu_item){
                            $data->page_title = $data->use_view_name_as_title ? $data->name : $data->form->getPageTitle();
                        }else{
                            $data->page_title = $data->use_view_name_as_title ? $data->name : JFactory::getDocument()->getTitle();
                        }
                    }
                    if($this->frontend){
                        $document = JFactory::getDocument();
                        $document->setTitle($data->page_title);
                    }
                    $data->show_back_button = $this->_show_back_button;
                    
                    if(isset($rec2) && count($rec2)){
                        $data->items = $rec2;
                    }else{
                        $data->items = $data->form->getRecord($this->_record_id, $data->published_only, $this->frontend ? ( $data->own_only_fe ? JFactory::getUser()->get('id', 0) : -1 ) : ( $data->own_only ? JFactory::getUser()->get('id', 0) : -1 ), $this->frontend ? $data->show_all_languages_fe : true );
                    }
                    
                    if(count($data->items)){
                        
                        $user = null;
                        
                        if($data->act_as_registration){
                            $meta = $data->form->getRecordMetadata($this->_record_id);
                            $this->_db->setQuery("Select * From #__users Where id = " . $meta->created_id);
                            $user = $this->_db->loadObject();
                        }
                        
                        $label = '';
                        foreach($data->items As $rec){
                            
                            if($rec->recElementId == $data->title_field){
                                
                                if($data->act_as_registration && $user !== null){
                                    
                                    if($data->registration_name_field == $rec->recElementId){
                                         $rec->recValue = $user->name;
                                    }
                                    else 
                                    if($data->registration_username_field == $rec->recElementId){
                                        $item->recValue = $user->username;
                                    }
                                    else 
                                    if($data->registration_email_field == $item->recElementId){
                                        $rec->recValue = $user->email;
                                    }
                                    else 
                                    if($data->registration_email_repeat_field == $rec->recElementId){
                                        $rec->recValue = $user->email;
                                    }
                                }
                                $label = cbinternal($rec->recValue);
                                break;
                            }
                        }
                        
                        $ordered_extra_title = '';
                        foreach($this->_menu_filter_order As $order_key => $order){
                            if(isset($this->_menu_filter[$order_key])){
                                // range test
                                $is_range = strstr(strtolower(implode(',', $this->_menu_filter[$order_key])), '@range') !== false;
                                $is_match = strstr(strtolower(implode(',', $this->_menu_filter[$order_key])), '@match') !== false;
                                if($is_range){
                                   $ex = explode('/', implode(', ', $this->_menu_filter[$order_key]));
                                   if(count($ex) == 3){
                                      $ex2 = explode('to', trim($ex[2]));
                                      $out = '';
                                      $val = $ex2[0];
                                      $val2 = '';
                                      if(isset($ex2[1])){
                                        $val2 = $ex2[1];
                                      }
                                      if(strtolower(trim($ex[1])) == 'date'){
                                          $val = JHTML::_('date', $ex2[0], JText::_('DATE_FORMAT_LC3'));
                                          if(isset($ex2[1])){
                                            $val2 = JHTML::_('date', $ex2[1], JText::_('DATE_FORMAT_LC3'));
                                          }
                                      }
                                      if(count($ex2) == 2){
                                          $out = (trim($ex2[0]) ? JText::_('COM_CONTENTBUILDER_FROM') . ' ' . trim($val) : '') . ' '.JText::_('COM_CONTENTBUILDER_TO').' ' . trim($val2);
                                      }else if(count($ex2) > 0){
                                          $out = JText::_('COM_CONTENTBUILDER_FROM') . ' ' . trim($val);
                                      }
                                      if($out){
                                        $this->_menu_filter[$order_key] = $ex;
                                        $ordered_extra_title .= ' &raquo; ' . htmlentities($data->labels[$order_key], ENT_QUOTES, 'UTF-8'). ': ' . htmlentities($out, ENT_QUOTES, 'UTF-8');
                                      }
                                   }
                                }
                                else if($is_match){
                                    $ex = explode('/', implode(', ', $this->_menu_filter[$order_key]));
                                    if(count($ex) == 2){
                                        $ex2 = explode(';', trim($ex[1]));
                                        $out = '';
                                        $size = count($ex2);
                                        $i = 0;
                                        foreach($ex2 As $val){
                                           if($i + 1 < $size){
                                               $out .= trim($val) . ' ' . JText::_('COM_CONTENTBUILDER_AND') . ' ';
                                           }else{
                                               $out .= trim($val);
                                           }
                                           $i++;
                                        }
                                        if($out){
                                            $this->_menu_filter[$order_key] = $ex;
                                            $ordered_extra_title .= ' &raquo; ' . htmlentities($data->labels[$order_key], ENT_QUOTES, 'UTF-8'). ': ' . htmlentities($out, ENT_QUOTES, 'UTF-8');
                                        }
                                    }
                                }
                                else{
                                    $ordered_extra_title .= ' &raquo; ' . htmlentities($data->labels[$order_key], ENT_QUOTES, 'UTF-8'). ': ' . htmlentities(implode(', ', $this->_menu_filter[$order_key]), ENT_QUOTES, 'UTF-8');
                                }
                            }
                        }

                        $data->page_title .= $ordered_extra_title;
                        
                        // trying first element if no title field given
                        if(!$label){
                            $label = cbinternal($data->items[0]->recValue);
                        }
                        
                        // "buddy quaid hack", should be an option in future versions
                        
                        jimport('joomla.version');
                        $version = new JVersion();

                        if(version_compare($version->getShortVersion(), '1.6', '>=')){
                        
                            if($this->_show_page_heading && $this->_page_title != '' && $this->_page_heading != '' && $this->_page_title == $this->_page_heading){
                                $data->page_title = $this->_page_title;
                            } 
                            else{
                                $data->page_title .= $label ? ( !$data->page_title ? '' : ( !$ordered_extra_title ? ': ' : ' &raquo; ' ) ) . $label : '';
                            }

                            if($this->frontend){
                                $document = JFactory::getDocument();
                                $document->setTitle(html_entity_decode ($data->page_title, ENT_QUOTES, 'UTF-8'));
                            }
                            
                        }else{
                            
                            if($this->_show_page_heading && $this->_page_title != '' && !JRequest::getInt('cb_prefix_in_title', 1)){
                                $data->page_title = $this->_page_title;
                            } 
                            else{
                                $data->page_title .= $label ? ( !$data->page_title ? '' : ( !$ordered_extra_title ? ': ' : ' &raquo; ' ) ) . $label : '';
                            }
                            
                            if($this->frontend){
                                $document = JFactory::getDocument();
                                $document->setTitle(html_entity_decode ($data->page_title, ENT_QUOTES, 'UTF-8'));
                            }
                        }
                    
                        $data->template = contentbuilder::getTemplate($this->_id, $this->_record_id, $data->items, $ids);
                        $metadata = $data->form->getRecordMetadata($this->_record_id);
                        if($metadata instanceof stdClass && $data->metadata){
                            $data->created = $metadata->created ? $metadata->created : '';
                            $data->created_by = $metadata->created_by ? $metadata->created_by : '';
                            $data->modified = $metadata->modified ? $metadata->modified : '';
                            $data->modified_by = $metadata->modified_by ? $metadata->modified_by : '';
                            $data->metadesc = $metadata->metadesc;
                            $data->metakey = $metadata->metakey;
                            $data->author = $metadata->author;
                            $data->rights = $metadata->rights;
                            $data->robots = $metadata->robots;
                            $data->xreference = $metadata->xreference;
                        }else{
                            $data->created = '';
                            $data->created_by = '';
                            $data->modified = '';
                            $data->modified_by = '';
                            $data->metadesc = '';
                            $data->metakey = '';
                            $data->author = '';
                            $data->rights = '';
                            $data->robots = '';
                            $data->xreference = '';
                        }
                    }else{
                        JError::raiseError(404, JText::_('COM_CONTENTBUILDER_RECORD_NOT_FOUND'));
                    }
                }
                return $data;
            }
        }
        return null;
    }
}
