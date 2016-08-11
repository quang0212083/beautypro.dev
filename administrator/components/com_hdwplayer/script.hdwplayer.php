<?php

/*
 * @version		$Id: script.webplayer.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class Com_HdwplayerInstallerScript {

	function postflight($type, $parent) {
		$db = JFactory::getDBO();
        $status = new stdClass;
        $status->modules = array();
        $status->plugins = array();
        $src = $parent->getParent()->getPath('source');
        $manifest = $parent->getParent()->manifest;
		
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module) {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            $path = $src.'/modules/'.$name;
            $installer = new JInstaller;
            $result = $installer->install($path);
            if ($result) {
                $root = JPATH_SITE;
                if (JFile::exists($root.'/modules/'.$name.'/'.$name.'.xml')) {
                    JFile::delete($root.'/modules/'.$name.'/'.$name.'.xml');
                }
                JFile::move($root.'/modules/'.$name.'/'.$name.'.j3.xml', $root.'/modules/'.$name.'/'.$name.'.xml');
            }
            $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
        }
		
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin) {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $path = $src.'/plugins/'.$name;
            $installer = new JInstaller;
            $result = $installer->install($path);
            if ($result) {
                if (JFile::exists(JPATH_SITE.'/plugins/'.$group.'/'.$name.'/'.$name.'.xml')) {
                    JFile::delete(JPATH_SITE.'/plugins/'.$group.'/'.$name.'/'.$name.'.xml');
                }
                JFile::move(JPATH_SITE.'/plugins/'.$group.'/'.$name.'/'.$name.'.j3.xml', JPATH_SITE.'/plugins/'.$group.'/'.$name.'/'.$name.'.xml');
            }
            $query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($name)." AND folder=".$db->Quote($group);
            $db->setQuery($query);
            $db->query();
            $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
        }		

		// Database modifications [start]
		$query = "SELECT COUNT(*) FROM #__hdwplayer_settings";
		$db->setQuery($query);
		$num = $db->loadResult();

		if ($num==0) {
			$query = "INSERT INTO `#__hdwplayer_videos` (`id`, `title`, `type`, `streamer`, `video`, `preview`, `thumb`, `category`, `published`) VALUES (NULL, 'Sample Video', 'Direct URL', '', 'http://hdwplayer.com/videos/300.mp4', '', '', 'none', '1')";
			$db->setQuery($query);
			$db->Query();
	
			$query = "INSERT INTO `#__hdwplayer_settings` (`id`, `width`, `height`, `licensekey`, `logo`, `logoposition`, `logoalpha`, `logotarget`, `skinmode`, `stretchtype`, `buffertime`, `volumelevel`, `autoplay`, `playlistautoplay`, `ffmpeg`, `flvtool2`) VALUES (NULL, '640', '360', 'Your Commercial Key Here', '', 'topleft', '35', 'http://hdwplayer.com', 'static', 'fill', '3', '50', '0', '0', '/usr/bin/ffmpeg/', '/usr/bin/flvtool2/')";
			$db->setQuery($query);
			$db->Query();
	
			$query = "INSERT INTO `#__hdwplayer_skin` (`id`, `controlbar`, `playpause`, `progressbar`, `timer`, `share`, `volume`, `fullscreen`, `playdock`, `videogallery`) VALUES (NULL, '1', '1', '1', '1', '1', '1', '1', '1', '1')";
			$db->setQuery($query);
			$db->Query();			
		}
		
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_hdwplayer/admin.hdwplayer.php')) {
			JFile::delete(JPATH_ADMINISTRATOR . '/components/com_hdwplayer/admin.hdwplayer.php');
		}
	
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_hdwplayer/toolbar.hdwplayer.html.php')) {
			JFile::delete(JPATH_ADMINISTRATOR . '/components/com_hdwplayer/toolbar.hdwplayer.html.php');
		}
	
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_hdwplayer/toolbar.hdwplayer.php')) {
			JFile::delete(JPATH_ADMINISTRATOR . '/components/com_hdwplayer/toolbar.hdwplayer.php');
		}
	
		if (JFile::exists(JPATH_ROOT . '/components/com_hdwplayer/views/default/tmpl/default.inc.php')) {
			JFile::delete(JPATH_ROOT . '/components/com_hdwplayer/views/default/tmpl/default.inc.php');
		}
	
		$this->installationResults($status);
	}
	
	public function update($type) {
	 	$db = JFactory::getDBO();				
		$fields_settings = $db->getTableColumns('#__hdwplayer_settings');
		$fields_videos   = $db->getTableColumns('#__hdwplayer_videos');
		$fields_category = $db->getTableColumns('#__hdwplayer_category');
		
		if (!array_key_exists('playlistopen', $fields_settings)) {
			$query = "ALTER TABLE #__hdwplayer_settings ADD `playlistopen` TINYINT(4) NOT NULL, ADD `ffmpeg` VARCHAR(255) NOT NULL DEFAULT '/usr/bin/ffmpeg/', ADD `flvtool2` VARCHAR(255) NOT NULL DEFAULT '/usr/bin/flvtool2/' AFTER `playlistautoplay`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('hdvideo', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `hdvideo` VARCHAR(255) NOT NULL AFTER `video`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('ordering', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `ordering` INT(5) NOT NULL DEFAULT '1' AFTER `category`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('token', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `token` VARCHAR(255) NOT NULL AFTER `thumb`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('type', $fields_category)) {
			$query = "ALTER TABLE #__hdwplayer_category ADD `type` VARCHAR(255) NOT NULL DEFAULT 'Url', ADD `image` VARCHAR(255) NOT NULL AFTER `name`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('featured', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `featured` TINYINT(4) NOT NULL, ADD `views` int(5) NOT NULL AFTER `category`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('user', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `user` VARCHAR(255) NOT NULL DEFAULT 'Admin', ADD `tags` VARCHAR(255) NOT NULL AFTER `featured`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('dvr', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `dvr` TINYINT(4) NOT NULL AFTER `streamer`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('playlistrandom', $fields_settings)) {
			$query = "ALTER TABLE #__hdwplayer_settings ADD `playlistrandom` TINYINT(4) NOT NULL AFTER `playlistopen`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('title', $fields_settings)) {
			$query = "ALTER TABLE #__hdwplayer_settings ADD `title` TINYINT(4) NOT NULL DEFAULT '1', ADD `description` TINYINT(4) NOT NULL DEFAULT '1' AFTER `height`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('qtfaststart', $fields_settings)) {
			$query = "ALTER TABLE #__hdwplayer_settings ADD `qtfaststart` VARCHAR(255) NOT NULL DEFAULT '/usr/bin/qt-faststart/', ADD `rows` INT(5) NOT NULL DEFAULT '3', ADD `cols` INT(5) NOT NULL DEFAULT '3', ADD `thumbwidth` INT(5) NOT NULL DEFAULT '145', ADD `thumbheight` INT(5) NOT NULL DEFAULT '80', ADD `subcategories` TINYINT(4) NOT NULL DEFAULT '1', ADD `relatedvideos` TINYINT(4) NOT NULL DEFAULT '1' AFTER `flvtool2`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('description', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `description` TEXT NOT NULL DEFAULT '' AFTER `title`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('metadescription', $fields_videos)) {
			$query = "ALTER TABLE #__hdwplayer_videos ADD `metadescription` TEXT NOT NULL DEFAULT '' AFTER `tags`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('parent', $fields_category)) {
			$query = "ALTER TABLE #__hdwplayer_category ADD `parent` INT(10) NOT NULL DEFAULT '0', ADD `ordering` INT(5) NOT NULL DEFAULT '0' AFTER `name`";
			$db->setQuery($query);
			$db->query();
		}

		if (!array_key_exists('metakeywords', $fields_category)) {
			$query = "ALTER TABLE #__hdwplayer_category ADD `metakeywords` TEXT NOT NULL DEFAULT '', ADD `metadescription` TEXT NOT NULL DEFAULT '' AFTER `image`";
			$db->setQuery($query);
			$db->query();
		}
	}
	
	public function uninstall($parent) {
        $db = JFactory::getDBO();
        $status = new stdClass;
        $status->modules = array();
        $status->plugins = array();
        $manifest = $parent->getParent()->manifest;
		
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin) {
            $name  = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($name)." AND folder = ".$db->Quote($group);
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions)) {
                foreach ($extensions as $id) {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
            }
            
        }
		
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module) {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            $db = JFactory::getDBO();
            $query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($name)."";
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions)) {
                foreach ($extensions as $id) {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('module', $id);
                }
                $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
            }
            
        }
        $this->uninstallationResults($status);
    }
	 
	private function installationResults($status) {
	?>
    	<style type="text/css">
			table {
				max-width: 100%;
				background-color: transparent;
				border-collapse: collapse;
				border-spacing: 0;
			}
			.table {
				width: 100%;
				margin-bottom: 18px;
				color:#333 !important;
			}
			.table th, .table td {
				padding: 10px 8px !important;
				line-height: 18px;
				text-align: left;
				vertical-align: top;
				border-top: 1px solid #ddd;
			}
			.table th {
				font-weight: bold;
			}
			.table thead th {
				vertical-align: bottom;
			}
			.table thead:first-child tr:first-child th, .table thead:first-child tr:first-child td {
				border-top: 0;
			}
			.table tbody + tbody {
				border-top: 2px solid #ddd;
			}
			.table-striped tbody tr:nth-child(odd) td, .table-striped tbody tr:nth-child(odd) th {
				background-color: #f9f9f9;
			}
			.table tbody tr td {
				font-style:italic;
			}
		</style>
  		<table class="table table-striped">
    		<thead>
      			<tr>
        			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
        			<th width="30%"><?php echo JText::_('Status'); ?></th>
      			</tr>
    		</thead>
    		<tfoot>
      			<tr>
        			<td colspan="3"></td>
      			</tr>
    		</tfoot>
    		<tbody>
      			<tr>
        			<td class="key" colspan="2"><?php echo 'Hdwplayer '.JText::_('Component'); ?></td>
        			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
      			</tr>
      			<?php if (count($status->modules)) : ?>
      				<tr>
        				<th><?php echo JText::_('Module'); ?></th>
        				<th><?php echo JText::_('Client'); ?></th>
        				<th></th>
      				</tr>
      				<?php foreach ($status->modules as $module) : ?>
      					<tr>
        					<td class="key"><?php echo $module['name']; ?></td>
        					<td class="key"><?php echo ucfirst($module['client']); ?></td>
        					<td><strong><?php echo ($module['result'])?JText::_('Installed'):JText::_('Not installed'); ?></strong></td>
      					</tr>
      				<?php endforeach;?>
      			<?php endif;?>
      			<?php if (count($status->plugins)) : ?>
      				<tr>
        				<th><?php echo JText::_('Plugin'); ?></th>
        				<th><?php echo JText::_('Group'); ?></th>
        				<th></th>
      				</tr>
      				<?php foreach ($status->plugins as $plugin) : ?>
      					<tr>
        					<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
        					<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
        					<td><strong><?php echo ($plugin['result'])?JText::_('Installed'):JText::_('Not installed'); ?></strong></td>
      					</tr>
      				<?php endforeach; ?>
      			<?php endif; ?>
    		</tbody>
  		</table>
	<?php 
	}
	
	private function uninstallationResults($status) {
 	?>
    	<style type="text/css">
			table {
				max-width: 100%;
				background-color: transparent;
				border-collapse: collapse;
				border-spacing: 0;
			}
			.table {
				width: 100%;
				margin-bottom: 18px;
				color:#333  !important;
			}
			.table th, .table td {
				padding: 8px !important;
				line-height: 18px;
				text-align: left;
				vertical-align: top;
				border-top: 1px solid #ddd;
			}
			.table th {
				font-weight: bold;
			}
			.table thead th {
				vertical-align: bottom;
			}
			.table thead:first-child tr:first-child th, .table thead:first-child tr:first-child td {
				border-top: 0;
			}
			.table tbody + tbody {
				border-top: 2px solid #ddd;
			}
			.table-striped tbody tr:nth-child(odd) td, .table-striped tbody tr:nth-child(odd) th {
				background-color: #f9f9f9;
			}
			.table tbody tr td {
				font-style:italic;
			}
		</style>
  		<table class="adminlist table table-striped">
    		<thead>
      			<tr>
        			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
        			<th width="30%"><?php echo JText::_('Status'); ?></th>
      			</tr>
    		</thead>
    		<tfoot>
      			<tr>
        			<td colspan="3"></td>
      			</tr>
    		</tfoot>
    		<tbody>
      			<tr>
        			<td class="key" colspan="2"><?php echo 'Hdwplayer '.JText::_('Component'); ?></td>
        			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
      			</tr>
      			<?php if (count($status->modules)) : ?>
      				<tr>
        				<th><?php echo JText::_('Module'); ?></th>
        				<th><?php echo JText::_('Client'); ?></th>
        				<th></th>
      				</tr>
      				<?php foreach ($status->modules as $module) : ?>
      					<tr>
        					<td class="key"><?php echo $module['name']; ?></td>
        					<td class="key"><?php echo ucfirst($module['client']); ?></td>
        					<td><strong><?php echo ($module['result'])?JText::_('Removed'):JText::_('Not removed'); ?></strong></td>
      					</tr>
      				<?php endforeach;?>
      			<?php endif;?>
      			<?php if (count($status->plugins)) : ?>
      				<tr>
        				<th><?php echo JText::_('Plugin'); ?></th>
        				<th><?php echo JText::_('Group'); ?></th>
        				<th></th>
      				</tr>
      				<?php foreach ($status->plugins as $plugin) : ?>
      					<tr>
        					<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
        					<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
        					<td><strong><?php echo ($plugin['result'])?JText::_('Removed'):JText::_('Not removed'); ?></strong></td>
      					</tr>
      				<?php endforeach; ?>
      			<?php endif; ?>
    		</tbody>
  		</table>
	<?php
    }	
}
?>