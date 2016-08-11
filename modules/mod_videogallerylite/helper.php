<?php 
/**
 * @package Video Gallery Lite
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     http://www.huge-it.com/
 **/
?>
<?php defined('_JEXEC') or die('Restricted access');
class modVideogalleryliteHelper {
        public static  function getModule(){
         $db = JFactory::getDBO();
         $query = $db->getQuery(true);
         $query->select('params');
         $query->from('#__modules');
         $query->where('module = "mod_videogallerylite"');
         $db->setQuery($query);
         $results = $db->loadObjectList();
         $exp = explode(":", $results[0]->params);
         $exp2 = explode(",", $exp[1]);      
         return $exp2;
}
}

?>