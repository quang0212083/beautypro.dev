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

class plgContentbuilder_themesBlank extends JPlugin
{
        function __construct( &$subject, $params )
        {
            parent::__construct($subject, $params);
        }
        
        /**
         * Any content template specific JS?
         * Return it here
         * 
         * @return string
         */
        function getContentTemplateJavascript(){
            
            return '';
        }
        
        /**
         * Any editable template specific JS?
         * Return it here
         * 
         * @return string
         */
        function getEditableTemplateJavascript(){
            
            return '';
        }
        
        /**
         * Any list view specific JS?
         * Return it here
         * 
         * @return string
         */
        function getListViewJavascript(){
            
            return '';
        }
        
        /**
         * Any content template specific CSS?
         * Return it here
         * 
         * @return string
         */
        function getContentTemplateCss(){
            
            return '';
        }
        
        /**
         * Any editable template specific CSS?
         * Return it here
         * 
         * @return string
         */
        function getEditableTemplateCss(){
            
            return $this->getContentTemplateCss();
        }
        
        /**
         * Any list view specific CSS?
         * Return it here
         * 
         * @return string
         */
        function getListViewCss(){
            
            return '';
        }
        
        /**
         * Return the sample html code for content here (triggered in view admin, after checking "SAMPLE"
         * 
         * @return string
         */
        function getContentTemplateSample($contentbuilder_form_id, $form){
            $db = JFactory::getDBO();
            $out = '<table border="0" width="100%" class="blanktable_content"><tbody>'."\n";
            $names = $form->getElementNames();
            foreach($names As $reference_id => $name){
                $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
                $result = $db->loadAssoc();
                if( is_array($result) ){
                    if($result['type'] != 'hidden'){
                        $out .= '{hide-if-empty '.$name.'}'."\n\n";
                        $out .= '<tr class="blanktable_content_row"><td width="20%" class="key" valign="top"><label>{'.$name.':label}</label></td><td>{'.$name.':value}</td></tr>'."\n\n";
                        $out .= '{/hide}'."\n\n";
                    }
                }
            }
            $out .= '</tbody></table>'."\n";
            return $out;
        }
        
        /**
         * Return the sample html code for editables here (triggered in view admin, after checking "SAMPLE"
         * 
         * @return string
         */
        function getEditableTemplateSample($contentbuilder_form_id, $form){
            $db = JFactory::getDBO();
            $out = '<table border="0" width="100%" class="blanktable_edit"><tbody>'."\n";
            $names = $form->getElementNames();
            $hidden = array();
            foreach($names As $reference_id => $name){
                $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And editable = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
                $result = $db->loadAssoc();
                if( is_array($result) ){
                    if($result['type'] != 'hidden'){
                        $out .= '<tr class="blanktable_edit_row"><td width="20%" class="key" valign="top">{'.$name.':label}</td><td>{'.$name.':item}</td></tr>'."\n";
                    } else {
                        $hidden[] = '{'.$name.':item}'."\n";
                    }
                }
            }
            $out .= '</tbody></table>'."\n";
            foreach($hidden As $hid){
                $out .= $hid;
            }
            return $out;
        }
}
