<?php
/**
* @version 1.0
* @package ContentBuilder Image Scale
* @copyright (C) 2011 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

if(!function_exists('cb_b64enc')){
    
    function cb_b64enc($str){
        $base = 'base';
        $sixty_four = '64_encode';
        return call_user_func($base.$sixty_four, $str);
    }

}

if(!function_exists('cb_b64dec')){
    function cb_b64dec($str){
        $base = 'base';
        $sixty_four = '64_decode';
        return call_user_func($base.$sixty_four, $str);
    }
}

jimport( 'joomla.plugin.plugin' );

class plgContentContentbuilder_verify extends JPlugin {

    function __construct( &$subject, $params )
    {
        parent::__construct($subject, $params);
    }

    function getValueByLanguage($value){
        
        $firstval = '';
        $parts = explode('|', $value);
        
        foreach($parts As $part){
            $keyval = explode('___', $part, 2);
            if(count($keyval) == 2){
                if(!$firstval){
                    $firstval = trim($keyval[1]);
                }
                $lang = strtolower(trim($keyval[0]));
                $val  = trim($keyval[1]);
                if($lang && $lang == strtolower(JRequest::getVar('lang',''))){
                    return $val; 
                }
            }
        }
        
        if($firstval){
            return $firstval;
        }
        
        return $value;
    }
    
    /**
     * Joomla 1.5 compatibility
     */
    function onPrepareContent( &$article, &$params, $limitstart = 0 )
    {
        $this->onContentPrepare('', $article, $params, $limitstart);
    }

    function onContentPrepare($context, &$article, &$params, $limitstart = 0) {
        
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        
        if( !$article || !isset($article->text) || !JFile::exists(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder.php'))
        {
            return true;
        }
        
        $db = JFactory::getDBO();
        $matches = array();
        preg_match_all("/\{CBVerify([^}]*)\}/i", $article->text, $matches);

        if(isset($matches[0]) && is_array($matches[0]) && isset($matches[1]) && is_array($matches[1])){

            $i = 0;
            foreach($matches[1] As $match){
                  
                $return_admin = '';
                $return_site = '';
                $plugin = '';
                $verification_name = '';
                $verification_msg = '';
                $image = 'none';
                $image_width = 0;
                $image_height = 0;
                $desc = 'Verify';
                $verify_view = 0;
                $verify_levels = '';
                $require_view = 0;
                $plugin_options = array();
                
                $options = explode(';', trim($match));
                foreach($options As $option){
                    $keyval = explode(':',trim($option), 2);
                    if(count($keyval) == 2){

                        $value = trim($keyval[1]);
                        switch(strtolower(trim($keyval[0]))){
                            case 'plugin':
                                $plugin = $value;
                                break;
                            case 'verification-name':
                                $verification_name = $this->getValueByLanguage($value); // lang
                                break;
                            case 'verification-msg':
                                $verification_msg = $this->getValueByLanguage($value); // lang
                                break;
                            case 'image':
                                $image = $this->getValueByLanguage($value); // lang
                                break;
                            case 'image-width':
                                $image_width = $this->getValueByLanguage($value); // lang
                                break;
                            case 'image-height':
                                $image_height = $this->getValueByLanguage($value); // lang
                                break;
                            case 'desc':
                                $desc = $this->getValueByLanguage($value); // lang
                                break;
                            case 'verify-view':
                                $verify_view = $this->getValueByLanguage($value);
                                break;
                            case 'verify-levels':
                                $vl = explode(',',$this->getValueByLanguage($value));
                                foreach($vl As $l){
                                    if(in_array(strtolower(trim($l)),array('new','edit','view'))){
                                        $verify_levels[] = strtolower(trim($l));
                                    }
                                }
                                $verify_levels = implode(',', $verify_levels);
                                break;
                            case 'require-view':
                                $require_view = $this->getValueByLanguage($value);
                                break;
                            case 'return-admin':
                                $return_admin = $this->getValueByLanguage($value);
                                break;
                            case 'return-site':
                                $return_site = $this->getValueByLanguage($value);
                                break;
                            default:
                                $plugin_options[strtolower(trim($keyval[0]))] = $this->getValueByLanguage($value);
                        }
                    }
                }
                
                if($plugin && $verification_name && $verify_view){
                
                    $plugin_settings = 'return-site='.($return_site ? cb_b64enc($return_site) : '').'&return-admin='.($return_admin ? cb_b64enc($return_admin) : '').'&client='.(JFactory::getApplication()->isSite() ? 0 : 1).'&plugin='.$plugin.'&verification_msg='.urlencode($verification_msg).'&verification_name='.urlencode($verification_name).'&verify_view='.$verify_view.'&verify_levels='.$verify_levels.'&require_view='.$require_view.'&plugin_options='.cb_b64enc($this->buildStr($plugin_options));

                    JFactory::getSession()->clear($plugin.$verification_name, 'com_contentbuilder.verify.'.$plugin.$verification_name);
                    JFactory::getSession()->set($plugin.$verification_name, $plugin_settings, 'com_contentbuilder.verify.'.$plugin.$verification_name);

                    $link = JURI::root(true).'/index.php?option=com_contentbuilder&controller=verify&plugin='.urlencode($plugin).'&verification_name='.urlencode($verification_name).'&format=raw';
                    JPluginHelper::importPlugin('contentbuilder_verify', $plugin);
                    $dispatcher = JDispatcher::getInstance();
                    $viewport_result = $dispatcher->trigger('onViewport', array($link, $plugin_settings));
                    $viewport_result = implode('', $viewport_result);
                    
                    if($viewport_result){
                        $article->text = str_replace($matches[0][$i], $viewport_result, $article->text);
                    } else {
                        $article->text = str_replace($matches[0][$i], '<a class="cb_verification_link" href="'.$link.'">'.($image && $image != 'none' ? '<img class="cb_verification_image" border="0" '.($image_width ? 'width="'.$image_width.'" ' : '').''.($image_height ? 'height="'.$image_height.'" ' : '').'src="'.$image.'" alt="'.$desc.'" title="'.$desc.'"/>' : $desc).'</a>', $article->text);
                    }
                    
                } else {
                    $article->text = str_replace($matches[0][$i], '<span style="color:red;">WARNING: Verify plugin requires the options "plugin", "verification-name" and "verify-view". Please update your content template.</span>', $article->text);
                }
                
                $i++;
            }
        }
        
        return true;
    }
    
    private function buildStr($query_array) {
        $query_string = array();
        foreach ($query_array as $k => $v) {
            $query_string[] = $k.'='.urlencode($v);
        }
        return join('&', $query_string);
    }
}