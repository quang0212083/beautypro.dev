<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
/**
 * Script file of CategoryBlock component
 */
class com_CategoryBlockInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        function install($parent) 
        {

            $manifest = $parent->get("manifest");
            $parent = $parent->getParent();
            $source = $parent->getPath("source");
             
            $installer = new JInstaller();
            
            // Install modules
            foreach($manifest->modules->module as $module) {
                $attributes = $module->attributes();
                $mod = $source . DS . $attributes['folder'].DS.$attributes['module'];
                $installer->install($mod);
            }
            
	    // Install plugins
            foreach($manifest->plugins->plugin as $plugin)
	    {
                $attributes = $plugin->attributes();
                $plg = $source . DS . $attributes['folder'].DS.$attributes['plugin'];
                $installer->install($plg);
	    }
			
			
	    $db = JFactory::getDbo();
            $tableExtensions = $this->safe_dbNameQuote("#__extensions");
            $columnElement   = $this->safe_dbNameQuote("element");
            $columnType      = $this->safe_dbNameQuote("type");
            $columnEnabled   = $this->safe_dbNameQuote("enabled");
            
            // Enable plugins
            $db->setQuery(
                "UPDATE 
                    $tableExtensions
                SET
                    $columnEnabled=1
                WHERE
                    $columnElement='categoryblock'
                AND
                    $columnType='plugin'"
            );
            
            $db->query();
			
            
			echo '<p>' . JText::_('COM_CATEGORYBLOCK_INSTALL_TEXT') . '</p>';
			
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
        function uninstall($parent) 
        {
                // $parent is the class calling this method
				$db = JFactory::getDbo();
				
				
		        // Uninstall module
		        
		        $db->setQuery('SELECT extension_id FROM #__extensions WHERE type="module" AND element = "mod_categoryblock" LIMIT 1');
		        $result = $db->loadResult();
		        if ($result)
				{

	                $installer = new JInstaller(); 
	                $installer->uninstall('module', $result);
			        
				}

			

				
                echo '<p>' . JText::_('COM_CATEGORYBLOCK_UNINSTALL_TEXT') . '</p>';
        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        function update($parent) 
        {
                // $parent is the class calling this method
				
				$manifest = $parent->get("manifest");
				$parent = $parent->getParent();
				$source = $parent->getPath("source");
             
	            $installer = new JInstaller();
            
           
	            // Install modules
	            foreach($manifest->modules->module as $module) {
	                $attributes = $module->attributes();
	                $mod = $source . DS . $attributes['folder'].DS.$attributes['module'];
	                $installer->install($mod);
	            }
				
				
				// Install plugins
				foreach($manifest->plugins->plugin as $plugin) {
				    $attributes = $plugin->attributes();
				    $plg = $source . DS . $attributes['folder'].DS.$attributes['plugin'];
				    $installer->install($plg);
				}
			
			
				$db = JFactory::getDbo();
				$tableExtensions = $this->safe_dbNameQuote("#__extensions");
				$columnElement   = $this->safe_dbNameQuote("element");
				$columnType      = $this->safe_dbNameQuote("type");
				$columnEnabled   = $this->safe_dbNameQuote("enabled");
            
				// Enable plugins
				$db->setQuery(
                "UPDATE 
                    $tableExtensions
                SET
                    $columnEnabled=1
                WHERE
                    $columnElement='categoryblock'
                AND
                    $columnType='plugin'"
				);				
				
				
                echo '<p>' . JText::_('COM_CATEGORYBLOCK_UPDATE_TEXT') . '</p>';
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                //echo '<p>' . JText::_('COM_CATEGORYBLOCK_PREFLIGHT_' . $type . '_TEXT') . '</p>';
        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        function postflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                //echo '<p>' . JText::_('COM_CATEGORYBLOCK_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        }
	
	function safe_dbNameQuote($v)
	{
				$db = JFactory::getDbo();
				$v2 = $db->nameQuote($v);
				if($v2=='')
						return '`'.$v.'`';
				else
						return $v2;
	}
		
}
