<?php
/**
 * ------------------------------------------------------------------------
 * JA Accordion Module for J25 & J34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 


/**
 * Module Adapter Helper
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.6
 */
class modulesAdapter
{
    /**
     *
     * Modules content
     * @var array
     */
    var $_moduleContent = null;


    /**
     *
     * Get modules from position
     * @param string $position
     * @param object $params
     * @return array
     */
    public function getModulesPosition($position, &$params)
    {
        if($params->get('count', 5)==0) {
			return;
		}
		$list = JModuleHelper::getModules(trim($params->get('modules-position')));
        $oldcount = count($list) < intval($params->get('count', 5)) ? count($list) : intval($params->get('count', 5)) ;
        $count = $oldcount ;
		
        for ($j = 0; $j < $count; $j++) { 
            if ($list[$j]->module != 'mod_jaaccordion') {
				$this->_moduleContent[$j] = new stdClass();
                $this->_moduleContent[$j]->title = $list[$j]->title;
                $this->_moduleContent[$j]->content = JModuleHelper::renderModule($list[$j]);
            } else {
				$count = $count < count($list) ? $count + 1 : $oldcount ;
			}
        }
        return $this->_moduleContent;
    }


    /**
     *
     * Get modules from modules id
     * @param array $moduleids
     * @param object $params
     * @return array
     */
    public function getModulesIds($moduleids, &$params)
    {      
        if (!isset($moduleids) || empty($moduleids)) {          
            return;
        }
		if($params->get('count', 5)==0) {
			return;
		}      
        $list_module = array();
		$list_module = $this->getModuleByIds($moduleids);
		
		$count = count($list_module) < intval($params->get('count', 5)) ? count($list_module) : intval($params->get('count', 5)) ;
		
		for ($i = 0; $i < $count; $i++) { 
            if ($list_module[$i]->module != 'mod_jaaccordion') {               
                if ($list_module[$i]->id) {
                    $this->_moduleContent[$i]->title = $list_module[$i]->title;
                    $this->_moduleContent[$i]->content = JModuleHelper::renderModule($list_module[$i]);
                }
            }

        }
        return $this->_moduleContent;
    }
	
	/**
     *
     * Get modules data
     * @param array $id module ids
     * @return array
     */
    function getModuleByIds($ids)
    {
        static $clean = null;

        if (isset($clean)) {
            return $clean;
        }
		if(! is_array($ids)){
			$ids = array(intval($ids)) ;
		}
		$ids = array_filter($ids) ;
        $Itemid = JRequest::getInt('Itemid');
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        //$groups = implode(',', $user->authorisedLevels());
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
		}
		else if (version_compare(JVERSION, '2.5', 'ge'))
		{
			$groups = implode(',', $user->authorisedLevels());
		}
		else
		{
			$groups = implode(',', $user->authorisedLevels());
		}
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->select('id, title, module, position, content, showtitle, params, mm.menuid');
        $query->from('#__modules AS m');
        $query->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id');
        $query->where('m.published = 1');
        $query->where('m.id IN(' . implode(',', $ids) . ')');
        $query->where('m.module != "mod_jaaccordion"');
        $date = JFactory::getDate();
        //$now = $date->toSql();
	    if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$now = $date->toSql();
			}
		else if (version_compare(JVERSION, '2.5', 'ge'))
		{
			$now = $date->toMySQL();
		}
		else
		{
			$now = $date->toMySQL();
		}
        $nullDate = $db->getNullDate();
        $query->where('(m.publish_up = ' . $db->Quote($nullDate) . ' OR m.publish_up <= ' . $db->Quote($now) . ')');
        $query->where('(m.publish_down = ' . $db->Quote($nullDate) . ' OR m.publish_down >= ' . $db->Quote($now) . ')');

        $clientid = (int) $app->getClientId();

        if (!$user->authorise('core.admin', 1)) {
            $query->where('m.access IN (' . $groups . ')');
        }
        $query->where('m.client_id = ' . $clientid);
        if (isset($Itemid)) {
            $query->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');
        }
        $query->order('position, ordering');

        // Filter by language
        if ($app->isSite() && $app->getLanguageFilter()) {
            $query->where('m.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
        }

        // Set the query
        $db->setQuery($query);

        $cache = JFactory::getCache('com_modules', 'callback');
        $cacheid = md5(serialize(array($Itemid, $groups, $clientid, JFactory::getLanguage()->getTag())));

        $modules = $cache->get(array($db, 'loadObjectList'), null, $cacheid, false);
        if (null === $modules) {
            JError::raiseWarning('SOME_ERROR_CODE', JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $db->getErrorMsg()));
            $return = false;
            return $return;
        }

        // Apply negative selections and eliminate duplicates
        $negId = $Itemid ? -(int) $Itemid : false;
        $dupes = array();
        $clean = array();
        for ($i = 0, $n = count($modules); $i < $n; $i++) {
            $module = &$modules[$i];

            // The module is excluded if there is an explicit prohibition, or if
            // the Itemid is missing or zero and the module is in exclude mode.
            $negHit = ($negId === (int) $module->menuid) || (!$negId && (int) $module->menuid < 0);

            if (isset($dupes[$module->id])) {
                // If this item has been excluded, keep the duplicate flag set,
                // but remove any item from the cleaned array.
                if ($negHit) {
                    unset($clean[$module->id]);
                }
                continue;
            }
            $dupes[$module->id] = true;

            // Only accept modules without explicit exclusions.
            if (!$negHit) {
                //determine if this is a custom module
                $file = $module->module;
                $custom = substr($file, 0, 4) == 'mod_' ? 0 : 1;
                $module->user = $custom;
                // Custom module name is given by the title field, otherwise strip off "com_"
                $module->name = $custom ? $module->title : substr($file, 4);
                $module->style = null;
                $module->position = strtolower($module->position);
                $clean[$module->id] = $module;
            }
        }
        unset($dupes);
        // Return to simple indexing that matches the query order.
        $clean = array_values($clean);

        return $clean;
    }
}
?>