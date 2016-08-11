<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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

jimport('joomla.version');
$version = new JVersion();
define('CBJOOMLAVERSION', $version->getShortVersion());

class CBCompat {
    
    protected $pane = null;
    
    public function initPane($titles){
        $out = '';
        if(version_compare(CBJOOMLAVERSION, '3.0', '>=')){
            $out = '<ul class="nav nav-tabs">'."\n";
            foreach($titles As $id => $title){

                $out .= '<li><a href="#'.$id.'" id="'.$id.'_tab" data-toggle="tab">'.$title.'</a></li>'."\n";
            }
            $out .= '</ul>'."\n";
        }else{
            if( !$this->pane ){
                $this->pane = JPane::getInstance('tabs', array('startOffset'=>0,'startTransition'=>0));
            }
        }
        return $out;
    }
    
    public function startPane($key){
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            
            if( !$this->pane ){
                
                die('Call CBCompat::initPane first!');
            }
            
            return $this->pane->startPane($key);
            
        } else {
            
            return '<div id="config-document" class="tab-content">'."\n";
        }
    }
    
    public function startPanel($title, $id){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            
            if( !$this->pane ){
                
                die('Call CBCompat::initPane first!');
            }
            
            return $this->pane->startPanel($title, $id);
            
        } else {
            
            $out = '<div id="'.$id.'" class="tab-pane'.($this->pane === null ? ' active' : '').'"><div class="row-fluid"><div class="span12">'."\n";
        
            if( $this->pane === null){
               $this->pane = 1; 
            }
            
            return $out;
        }
    }
    
    public function endPane(){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            
            if( !$this->pane ){
                
                die('Call CBCompat::initPane first!');
            }
            
            return $this->pane->endPane();
            
        } else {
            
            return '</div>';
        }
    }
    
    public function endPanel(){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            
            if( !$this->pane ){
                
                die('Call CBCompat::initPane first!');
            }
            
            return $this->pane->endPanel();
            
        } else {
            
            return '</div></div></div>';
        }
    }
    
    public static function loadColumn(){
       if(version_compare(CBJOOMLAVERSION, '3.0', '>=')){
            return JFactory::getDbo()->loadColumn();
        }else{
            return JFactory::getDbo()->loadResultArray();
        } 
    }
    
    public static function getCheckAll($rows){
        if(version_compare(CBJOOMLAVERSION, '3.0', '>=')){
            return 'Joomla.checkAll(this);';
        }else{
            return 'checkAll('.count($rows).');';
        }
    }
    
    public static function getParams( $attribs ){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            $plugin = JPluginHelper::getPlugin($dir, $plg);
            jimport( 'joomla.html.parameter' );
            return new JParameter($attribs);
        }else{
            $params = new JRegistry;
            $params->loadString($attribs);
            return $params;
        }
    }
    
    public static function getPluginParams( JPlugin $plgObj, $dir, $plg){
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            $plugin = JPluginHelper::getPlugin($dir, $plg);
            jimport( 'joomla.html.parameter' );
            return new JParameter($plugin->params);
        }else{
            return $plgObj->params;
        }
    }
    
    public static function setJoomlaConfig($key, $value){
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            JFactory::getConfig()->setValue($key, $value);
        }else{
            JFactory::getConfig()->set($key, $value);
        }
    }
    
    public static function getJoomlaConfig($key, $value = null){
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            return JFactory::getConfig()->getValue($key, $value);
        }else{
            return JFactory::getConfig()->get(preg_replace("/^config./", '', $key, 1), $value);
        }
    }
    
    public static function toSql(JDate $dateObj){
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            return $dateObj->toMySQL();
        }else{
            return $dateObj->toSql();
        }
    }
    
    public static function getTableFields($tables, $typeOnly = true)
    {
            if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
                return JFactory::getDBO()->getTableFields($tables); 
            }

            $results = array();

            settype($tables, 'array');

            foreach ($tables as $table)
            {
                    $results[$table] = JFactory::getDbo()->getTableColumns($table, $typeOnly);
            }

            return $results;
    }
    
    public static function requireController(){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'controller.php');
        }else{
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'controllerlegacy.php');
        }
    }
    
    public static function requireView(){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'view.php');
        }else{
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'viewlegacy.php');
        }
    }
    
    public static function requireModel(){
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'model.php');
        }else{
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'modellegacy.php');
        }
    }
}
