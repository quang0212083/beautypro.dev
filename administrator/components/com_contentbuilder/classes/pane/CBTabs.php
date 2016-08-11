<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

jimport('joomla.version');
$version = new JVersion();

if (version_compare($version->getShortVersion(), '3.0', '>=')) {
    
    require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'pane'.DS.'CBJNewTabs.php');

} else {
    
    class CBTabs  {
           
        public static function getInstance($type, $options = array()){
            jimport('joomla.html.pane');
            return JPane::getInstance($type, $options);
        }
    }
}