<?php
/**
 * @package         Articles Anywhere
 * @version         5.5.10
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class PlgSystemArticlesAnywhereInstallerScript extends PlgSystemArticlesAnywhereInstallerScriptHelper
{
	public $name           = 'ARTICLES_ANYWHERE';
	public $alias          = 'articlesanywhere';
	public $extension_type = 'plugin';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'editors-xtd');
	}

	public function onBeforeInstall()
	{
		if ($this->install_type != 'update')
		{
			return;
		}

		$this->setOldTagCharacters();
	}

	public function onAfterInstall()
	{
		if ($this->install_type != 'update')
		{
			return;
		}

		$this->deleteOldFiles();
	}

	private function setOldTagCharacters()
	{
		$plugin = $this->getPluginParams();

		if (empty($plugin))
		{
			return;
		}

		$params = json_decode($plugin->params);

		if (isset($params->tag_characters_data))
		{
			return;
		}

		// Set tag_characters_data to old (pre v4.2.0) value
		$params->tag_characters_data = '{.}';

		$this->savePluginParams($plugin->extension_id, $params);
	}

	private function getPluginParams()
	{
		$query = $this->db->getQuery(true)
			->select(array('extension_id', 'params'))
			->from($this->db->quoteName('#__extensions'))
			->where($this->db->quoteName('element') . ' = ' . $this->db->quote('articlesanywhere'))
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('plugin'))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote('system'));
		$this->db->setQuery($query, 0, 1);

		return $this->db->loadObject();
	}

	private function savePluginParams($id, $params)
	{
		$params = json_encode($params);

		$query = $this->db->getQuery(true)
			->update('#__extensions')
			->set($this->db->quoteName('params') . ' = ' . $this->db->quote($params))
			->where($this->db->quoteName('extension_id') . ' = ' . (int) $id);
		$this->db->setQuery($query);
		$this->db->execute();

		JFactory::getCache()->clean('_system');
	}

	private function deleteOldFiles()
	{
		JFile::delete(
			array(
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/article.php',
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/articles.php',
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/articlesk2.php',
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/process.php',
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/tag.php',
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/tags.php',
				JPATH_PLUGINS . '/system/articlesanywhere/helpers/tagsk2.php',
			)
		);
	}
}
