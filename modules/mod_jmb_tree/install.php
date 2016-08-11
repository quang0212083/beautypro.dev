<?php
/**
 * @package    Jmb_Tree
 * @author     Dmitry Rekun <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('_JEXEC') or die;

/**
 * Install script class
 *
 * @since  1.0
 */
class Mod_Jmb_TreeInstallerScript
{
	/**
	 * Method to set message after installation
	 *
	 * @param   string            $route    The type of route: install, uninstall, discover_install, update
	 * @param   JAdapterInstance  $adapter  The class who calls this method
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function postflight($route, JAdapterInstance $adapter)
	{
		if (strtolower($route) == 'install')
		{
			try
			{
				$db = $adapter->getParent()->getDbo();

				$query = $db->getQuery(true)
					->select($db->quoteName('id'))
					->from($db->quoteName('#__modules'))
					->where(
						$db->quoteName('module')
						. ' = '
						. $db->quote(
							strtolower($adapter->get('name'))
						)
					);
				$db->setQuery($query);

				$id = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				return false;
			}

			echo JText::sprintf('MOD_JMB_TREE_INSTALL_TEXT', $id);
		}

		return true;
	}
}
