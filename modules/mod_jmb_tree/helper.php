<?php
/**
 * @package    Jmb_Tree
 * @author     Sherza & Dmitry Rekun <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * JMB Tree helper class.
 *
 * @package  Jmb_Tree
 * @since    1.0
 */
abstract class ModJmbTreeHelper
{
	/**
	 * Children only parameter.
	 *
	 * @var  int
	 */
	public static $childrenonly = null;

	/**
	 * Current category.
	 *
	 * @var  int
	 */
	public static $curcat = null;

	/**
	 * End level parameter.
	 *
	 * @var  int
	 */
	public static $endLevel = null;

	/**
	 * Real end level.
	 *
	 * @var  int
	 */
	public static $endLevelReal = null;

	/**
	 * Method to get the list of items.
	 *
	 * @param   Registry  &$params  Additional parameters.
	 *
	 * @return  array  The list of items.
	 */
	public static function &getList(&$params)
	{
		if ($params->get('type', 'category') == 'category')
		{
			$list = self::getCategories($params);
		}
		else
		{
			$list = self::getMenuItems($params);
		}

		return $list;
	}

	/**
	 * Method to get the list of menu items.
	 *
	 * @param   Registry  $params  Additional parameters.
	 *
	 * @return  array  The list of items.
	 */
	private static function getMenuItems($params)
	{
		$menuParamsPre = $params->get('menuitems');
		$menuParams = array();

		foreach ($menuParamsPre as $k => $mpp)
		{
			$menuParams[$k] = $mpp;
		}

		$menutype = $menuParams[0];
		$checkedElems = explode(',', $menuParams[2]);

		$app = JFactory::getApplication();
		$menu = $app->getMenu();

		// Get active menu item
		$base = self::getActive();
		$user = JFactory::getUser();
		$levels = $user->getAuthorisedViewLevels();
		asort($levels);
		$key = 'menu_items' . $params . implode(',', $levels) . '.' . $base->id;
		$cache = JFactory::getCache('mod_jmb_tree', '');

		if (!($links = $cache->get($key)))
		{
			$items = $menu->getItems('menutype', $menutype);

			$excludedMenus = array_map('trim', explode(',', $menuParams[3]));

			$links                 = array();
			$excludedParentsLevels = array();

			self::$childrenonly = $params->get('childrenonly');
			$endLevel           = $params->get('endLevel');
			$endLevelReal       = 0;

			$curItemid         = $app->input->getInt('Itemid', 0);
			$curMenuLevel      = 0;
			$childrenOfCurMenu = false;

			// Level separator
			$levelSeparator = '';

			if ($params->get('use_sep', 1))
			{
				$levelSeparator = $params->get('level_sep', '');

				if (strlen($levelSeparator) > 1 || empty($levelSeparator))
				{
					$levelSeparator = '&nbsp;';
				}
			}

			foreach ($items as $k => $mlink)
			{
				if (self::$childrenonly)
				{
					if ($childrenOfCurMenu
						&& $curMenuLevel
						&& $mlink->level <= $curMenuLevel)
					{
						$childrenOfCurMenu = false;
						$curMenuLevel      = 0;
					}

					if ($mlink->id == $curItemid)
					{
						$childrenOfCurMenu = true;
						$curMenuLevel      = $mlink->id;
					}

					if (!$childrenOfCurMenu || $mlink->id == $curItemid)
					{
						//continue;
					}
				}

				if (!empty($excludedParentsLevels)
					&& isset($items[$k - 1])
					&& ($items[$k - 1]->level >= $mlink->level))
				{
					foreach ($excludedParentsLevels as $j => $lev)
					{
						if ($j >= $mlink->level)
						{
							unset($excludedParentsLevels[$j]);
						}
					}
				}

				$checked  = (in_array('zmenu' . $mlink->id, $checkedElems)) ? true : false;
				$excluded = (in_array($mlink->id, $excludedMenus)) ? true : false;

				if ($checked && !$excluded)
				{
					if (!$endLevelReal)
					{
						$endLevelReal = $mlink->level + $endLevel;
					}

					if (!$endLevel || $mlink->level < $endLevelReal)
					{
						$link       = new stdClass;
						$link->id   = $mlink->id;
						$link->href = $mlink->link;

						$internal = false;
						$external = false;

						switch ($mlink->type)
						{
							case 'separator':
								$link->href = '';
								break;

							case 'url':
								if ((strpos($mlink->link, 'index.php?') === 0) && (strpos($mlink->link, 'Itemid=') === false))
								{
									// If this is an internal Joomla link, ensure the Itemid is set.
									$link->href = $mlink->link . '&Itemid=' . $mlink->id;
									$internal   = true;
								}
								else
								{
									$external = true;
								}
								break;

							case 'alias':
								// If this is an alias use the item id stored in the parameters to make the link.
								$link->href = 'index.php?Itemid=' . $mlink->params->get('aliasoptions');
								break;

							default:
								$router = JFactory::getApplication()->getRouter();

								if ($router->getMode() == JROUTER_MODE_SEF)
								{
									$link->href = 'index.php?Itemid=' . $mlink->id;
								}
								else
								{
									$link->href .= '&Itemid=' . $mlink->id;
									$internal = true;
								}
								break;
						}

						if (strcasecmp(substr($link->href, 0, 4), 'http') && (strpos($link->href, 'index.php?') !== false))
						{
							$link->href = JRoute::_($link->href, false, $mlink->params->get('secure'));
						}
						else
						{
							$link->href = JRoute::_($link->href, false);
						}

						$excludedParentsCount = 0;

						if (!empty($excludedParentsLevels))
						{
							foreach ($excludedParentsLevels as $j => $lev)
							{
								if ($j < $mlink->level)
								{
									$excludedParentsCount++;
								}
							}
						}

						$link->level = ($mlink->level - $excludedParentsCount - $curMenuLevel);

						$link->levelSeparator = '';

						if ($link->level > 1)
						{
							$link->levelSeparator = str_repeat($levelSeparator . ' ', $link->level - 1);
						}

						// Nofollow
						$addNofollow     = $params->get('add_nofollow', '');
						$nofollowExclude = explode(',', $params->get('exclude_nofollow', ''));
						$link->nofollow  = '';

						switch ($addNofollow)
						{
							case 'all':
								$link->nofollow = 'rel="nofollow"';
								break;

							case 'internal':
								if ($internal)
								{
									$link->nofollow = 'rel="nofollow"';
								}
								break;

							case 'external':
								if ($external)
								{
									$link->nofollow = 'rel="nofollow"';
								}
								break;
						}

						if (in_array($link->id, $nofollowExclude))
						{
							$link->nofollow = '';
						}

						$link->text         = htmlspecialchars($mlink->title, ENT_COMPAT, 'UTF-8', false);
						$link->anchor_css   = htmlspecialchars($mlink->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
						$link->anchor_title = htmlspecialchars($mlink->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
						$link->menu_image   = $mlink->params->get('menu_image', '')
							? htmlspecialchars($mlink->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false)
							: '';
						$link->params       = $mlink->params;
						$link->browserNav   = $mlink->browserNav;

						$links[] = $link;
					}
				}

				if ($excluded)
				{
					$excludedParentsLevels[$mlink->level] = true;
				}
			}

			$cache->store($links, $key);
		}

		return $links;
	}

	/**
	 * Method to get the list of categories.
	 *
	 * @param   Registry  $params  Additional parameters.
	 *
	 * @return  array  The list of categories.
	 */
	private static function getCategories($params)
	{
		include_once JPATH_BASE . '/components/com_content/helpers/route.php';

		$cat        = '';
		$cats       = JCategories::getInstance('content', array($cat));
		$catRoot    = $cats->get($cat);
		$categories = $catRoot->getChildren();
		$level      = 0;

		$catParamsPre = $params->get('category');
		$catParams    = array();

		foreach ($catParamsPre as $k => $cpp)
		{
			$catParams[$k] = $cpp;
		}

		$checkedElems = explode(',', $catParams[1]);
		$links        = array();

		$excludedCats = array_map('trim', explode(',', $catParams[2]));

		self::$endLevel = $params->get('endLevel');

		self::$childrenonly = $params->get('childrenonly');

		if (self::$childrenonly)
		{
			self::$curcat = self::getCurrentCategory();
		}

		self::getCatListRecurse($categories, $level, $checkedElems, $links, $excludedCats, $params);

		return $links;
	}

	/**
	 * Method to recurse the list of categories.
	 *
	 * @param   array     $categories          Categories.
	 * @param   int       &$level              Hierarchy level.
	 * @param   array     $checkedElems        Checked elements.
	 * @param   array     &$links              Links.
	 * @param   array     $excludedCats        Excluded categories.
	 * @param   Registry  $params              Additional parameters.
	 * @param   boolean   $childrenOfSelected  Children categories of selected one.
	 *
	 * @return  void
	 */
	private static function getCatListRecurse($categories, &$level, $checkedElems, &$links, $excludedCats, $params, $childrenOfSelected = false)
	{
		$childrenOfSelectedTop = $childrenOfSelected;

		$level++;

		// Level separator
		$levelSeparator = '';

		if ($params->get('use_sep', 1))
		{
			$levelSeparator = $params->get('level_sep', '');

			if (strlen($levelSeparator) > 1 || empty($levelSeparator))
			{
				$levelSeparator = '&nbsp;';
			}
		}

		foreach ($categories as $cat)
		{
			$childrenOfSelected = (!self::$childrenonly || ($childrenOfSelectedTop || ($cat->id == self::$curcat))) ? true : false;

			$childrenCategories = $cat->getChildren();

			$showCatid = (!self::$childrenonly || ($cat->id != self::$curcat && $childrenOfSelected)) ? true : false;

			if ($showCatid)
			{
				$checked = (in_array('zcat' . $cat->id, $checkedElems)) ? true : false;
				$excluded = (in_array($cat->id, $excludedCats)) ? true : false;

				if ($checked && !$excluded)
				{
					if (!self::$endLevelReal)
					{
						self::$endLevelReal = $level + self::$endLevel;
					}

					if (!self::$endLevel || $level < self::$endLevelReal)
					{
						$link = new stdClass;
						$link->id = $cat->id;
						$link->href = JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id), false);

						$link->levelSeparator = '';

						if ($level > 1)
						{
							$link->levelSeparator = str_repeat($levelSeparator . ' ', $level - 1);
						}

						$link->text = $cat->title;
						$link->textNoDef = $cat->title;
						$link->level = $level;
						$links[] = $link;
					}
				}
			}
			else
			{
				$level--;
			}

			if ($childrenCategories)
			{
				if ($excluded)
				{
					$level--;
				}

				self::getCatListRecurse($childrenCategories, $level, $checkedElems, $links, $excludedCats, $params, $childrenOfSelected);

				if ($excluded)
				{
					$level++;
				}
			}

			if (!$showCatid)
			{
				$level++;
			}
		}

		$level--;
	}

	/**
	 * Get active menu item.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	private static function getActive()
	{
		$menu = JFactory::getApplication()->getMenu();
		$lang = JFactory::getLanguage();

		// Look for the home menu
		if (JLanguageMultilang::isEnabled())
		{
			$home = $menu->getDefault($lang->getTag());
		}
		else
		{
			$home  = $menu->getDefault();
		}

		return $menu->getActive() ? $menu->getActive() : $home;
	}

	/**
	 * Method to get current category
	 *
	 * @return  integer
	 *
	 * @since   1.0
	 * @throws  Exception
	 */
	private static function getCurrentCategory()
	{
		$catId = 0;

		if (JFactory::getApplication()->input->get('option') == 'com_content')
		{
			$view = JFactory::getApplication()->input->get('view');

			if ($view == 'category' || $view == 'categories')
			{
				$catId = JFactory::getApplication()->input->get->getInt('id', 0);
			}
			elseif ($view == 'article')
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT catid FROM #__content WHERE id = ' . JFactory::getApplication()->input->get->getInt('id', 0));
				$catId = $db->loadResult();
			}
		}

		return $catId;
	}
}
