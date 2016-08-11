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

class plgContentbuilder_themesJoomla3 extends JPlugin
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
            $out = '<ul class="category list-striped list-condensed">'."\n";
            $names = $form->getElementNames();
            foreach($names As $reference_id => $name){
                $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
                $result = $db->loadAssoc();
                if( is_array($result) ){
                    if($result['type'] != 'hidden'){
                        $out .= '{hide-if-empty '.$name.'}'."\n\n";
                        $out .= '<li class="cat-list-row0" ><strong class="list-title">{'.$name.':label}</strong><div>{'.$name.':value}</div></li>'."\n\n";
                        $out .= '{/hide}'."\n\n";
                    }
                }
            }
            $out .= '</ul>'."\n";
            return $out;
        }
        
        /**
         * Return the sample html code for editables here (triggered in view admin, after checking "SAMPLE"
         * 
         * @return string
         */
        function getEditableTemplateSample($contentbuilder_form_id, $form){
            $db = JFactory::getDBO();
            $out = ''."\n";
            $names = $form->getElementNames();
            $hidden = array();
            foreach($names As $reference_id => $name){
                $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And editable = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
                $result = $db->loadAssoc();
                if( is_array($result) ){
                    if($result['type'] != 'hidden'){
                        if($result['type'] == 'checkboxgroup') {
                        
                            $out .= '<div class="control-group form-inline"><div class="control-label">{'.$name.':label}</div> <div class="controls"><fieldset class="checkbox">{'.$name.':item}</fieldset></div>';
                        
                        } else if($result['type'] == 'radiogroup') {
                        
                            $out .= '<div class="control-group form-inline"><div class="control-label">{'.$name.':label}</div> <div class="controls"><fieldset class="radio">{'.$name.':item}</fieldset></div>';
                        
                        } else {
                            $out .= '<div class="control-group form-inline"><div class="control-label">{'.$name.':label}</div> 
                                <div class="controls">{'.$name.':item}</div></div>'."\n";
                        }
                    } else {
                        $hidden[] = '{'.$name.':item}'."\n";
                    }
                }
            }
            $out .= ''."\n";
            foreach($hidden As $hid){
                $out .= $hid;
            }
            return $out;
        }
}
