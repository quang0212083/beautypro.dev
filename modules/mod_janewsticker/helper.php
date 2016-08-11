<?php
/**
 * ------------------------------------------------------------------------
 * JA Newsticker Module for J3x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

/**
 * JA News Sticker module allows display of article's title from sections or categories. \
 * You can configure the setttings in the right pane. Mutiple options for animations are also added, choose any one.
 * If you are using this module on Teline III template, * then the default module position is "headlines".
 **/
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE . '/components/com_content/helpers/route.php';
jimport("joomla.application.component.model");

if (version_compare(JVERSION, '3.0', 'ge'))
{
	JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models');
	//$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
}
else if (version_compare(JVERSION, '2.5', 'ge'))
{
	JModel::addIncludePath(JPATH_SITE . '/components/com_content/models');
   	//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
}
else
{
	JModel::addIncludePath(JPATH_SITE . '/components/com_content/models');
	//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
}
if (file_exists(JPATH_SITE .'/components/com_k2/helpers/route.php')) {
    require_once (JPATH_SITE . '/components/com_k2/helpers/route.php');
}

/**
 * modjanewstickerHelper class.
 */
class modjanewstickerHelper
{


    /**
     * get listing items from rss link or from list of categories.
     *
     * @param JParameter $params
     * @return array
     */
    public static function getList($params)
    {
        $rows = array();
        $using_mode = strtolower($params->get('using_mode', "catids"));
        $exclude_arr = array("rss", "external");
        if (!in_array($using_mode, $exclude_arr)) {
            // check cache was endable ?
            if ($params->get('enable_cache')) {
                $cache = JFactory::getCache();
                $cache->setCaching(true);
                $cache->setLifeTime($params->get('cache_time', 30) * 60);
                $rows = $cache->get(array((new modjanewstickerHelper()), 'getArticles'), array($params));
            } else {
                if ($using_mode == 'com_k2') {
                	if(modjanewstickerHelper::checkComponent('com_k2')){
						$rows = modjanewstickerHelper::getK2Items($params);
					}else{
						$rows = null;
					}
                } else {
                    $rows = modjanewstickerHelper::getArticles($params);
                }
            }
        } elseif ($using_mode == 'external') {
            $rows = modjanewstickerHelper::parseExternalLinks($params->get("external_link", ""), $params);
        } else {
            $rows = modjanewstickerHelper::dataFromRSS($params);

        }

        return $rows;
    }
	/**
	* Check component k2
	*/
	public static function checkComponent($component)
    {
        $db = JFactory::getDBO();
        $query = " SELECT Count(*) FROM #__extensions as e WHERE e.element ='$component' and e.enabled=1";
        $db->setQuery($query);
	    return $db->loadResult();
    }

    /**
     * get list k2 items follow setting configuration.
     *
     * @param JParameter $param
     * @return array
     */
    public static function getK2Items($params)
    {
        global $mainframe;

        $catsid = $params->get('k2catsid');
        $catids = array();
        if (!is_array($catsid)) {
            $catids[] = $catsid;
        } else {
            $catids = $catsid;
        }

        JArrayHelper::toInteger($catids);
        if ($catids) {
            if ($catids && count($catids) > 0) {
                foreach ($catids as $k => $catid) {
                    if (!$catid)
                        unset($catids[$k]);
                }
            }
        }

        jimport('joomla.filesystem.file');

        $user = JFactory::getUser();
        $app = JFactory::getApplication();
        $aid = $user->get('aid') ? $user->get('aid') : 1;
        $db = JFactory::getDBO();

        $jnow = JFactory::getDate();
        //$now = $jnow->toMySQL();
        if (version_compare(JVERSION, '3.0', 'ge'))
				{
					$now = $jnow->toSql();
				}
			else if (version_compare(JVERSION, '2.5', 'ge'))
			{
				$now = $jnow->toMySQL();
			}
			else
			{
				$now = $jnow->toMySQL();
			}
			
        $nullDate = $db->getNullDate();

		$query 	= "SELECT i.*, c.name AS categoryname,c.id AS categoryid, c.alias AS categoryalias, c.params AS categoryparams";
		$query .= "\n FROM #__k2_items as i LEFT JOIN #__k2_categories c ON c.id = i.catid";
		$query .= "\n WHERE i.published = 1 AND i.access <= {$aid} AND i.trash = 0 AND c.published = 1 AND c.access <= {$aid} AND c.trash = 0";
		$query .= "\n AND ( i.publish_up = " . $db->Quote($nullDate) . " OR i.publish_up <= " . $db->Quote($now) . " )";
		$query .= "\n AND ( i.publish_down = " . $db->Quote($nullDate) . " OR i.publish_down >= " . $db->Quote($now) . " )";

		if ($catids) {
            $catids_new = $catids;
            foreach ($catids as $k => $catid) {
                $subcatids = modjanewstickerHelper::getK2CategoryChildren($catid, true);
                if ($subcatids) {
                    $catids_new = array_merge($catids_new, array_diff($subcatids, $catids_new));
                }
            }
            $catids = implode(',', $catids_new);
            $query .= "\n AND i.catid IN ($catids) ";
        }
        
        // language filter
		$lang = JFactory::getLanguage();
		$languages = JLanguageHelper::getLanguages('lang_code');
		$languageTag = $lang->getTag();
		if ($app->getLanguageFilter()) {
			$query .= " AND i.language IN ('{$languageTag}','*') ";
		}

        // Set ordering
        $query .= ' ORDER BY ' . 'i.' . $params->get('sort_order_field', 'created') . ' ' . $params->get('sort_order', 'DESC');
		
		if ($params->get('max_items', 5)==0) {
			$query = str_replace("i.published = 1", "i.published = 10", $query);
		}
		
        $db->setQuery($query, 0, (int) $params->get('max_items', 5));
        $items = $db->loadObjectList();

        return $items;
    }


	/**
     *
     * Get K2 category children
     * @param int $catid
     * @param boolean $clear if true return array which is removed value construction
     * @return array
     */
    public static function getK2CategoryChildren($catid, $clear = false) {

		static $array = array();
		if ($clear)
		$array = array();
		$user = JFactory::getUser();
		$aid = $user->get('aid') ? $user->get('aid') : 1;
		$catid = (int) $catid;
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$catid} AND published=1 AND trash=0 AND access<={$aid} ORDER BY ordering ";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			array_push($array, $row->id);
			if (modjanewstickerHelper::hasK2Children($row->id)) {
				modjanewstickerHelper::getK2CategoryChildren($row->id);
			}
		}
		return $array;
	}


	/**
	 *
	 * Check category has children
	 * @param int $id
	 * @return boolean
	 */
	public static function hasK2Children($id) {

		$user = JFactory::getUser();
		$aid = $user->get('aid') ? $user->get('aid') : 1;
		$id = (int) $id;
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE parent={$id} AND published=1 AND trash=0 AND access<={$aid} ";
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		if (count($rows)) {
			return true;
		} else {
			return false;
		}
	}


    /**
     * get articles from list of categories and follow up owner paramer.
     *
     * @param JParameter $params.
     * @return array list of articles
     */
    public static function getArticles($params)
    {
		if ($params->get('max_items', 5) == 0) {
			return $rows = array();
		}
        $obj = JAStArticles::getInstance();
        $obj->setOrder($params->get('sort_order_field', 'created'), $params->get('sort_order', 'DESC'));
        $obj->setLimit($params->get('max_items', 5));
        $rows = $obj->getListArticles($params);

        return $rows;
    }


    /**
     * get  list of items from rss list and process caching.
     *
     * @param JParameter $params.
     * @return array list of articles
     */
    public static function dataFromRSS($params)
    {
        $data = array();
        if (trim($params->get('rss_link')) == '') {
            return $data;
        }
        $rssUrl = $params->get('rss_link');

        //  get RSS parsed object
        $options = array();
        $options['rssUrl'] = $rssUrl;
        if ($params->get('enable_cache')) {
            $options['cache_time'] = $params->get('cache_time', '30');
            $options['cache_time'] *= 60;
        } else {
            $options['cache_time'] = null;
        }

        
		if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$rssDoc = JFactory::getFeedParser($rssUrl,$options['cache_time']);
		}
		else if (version_compare(JVERSION, '2.5', 'ge'))
		{
			$rssDoc = JFactory::getXMLparser('RSS', $options);
		}
		else
		{
			$rssDoc = JFactory::getXMLparser('RSS', $options);
		}

        if ($rssDoc != false) {
            $items = $rssDoc->get_items();
            if ($items != null) {
                $tmp = array();
                foreach ($items as $item) {
                    $obj = new stdClass();
                    $obj->title = $item->get_title();
                    $obj->link = $item->get_link();
                    $obj->introtext = $item->get_description();
                    $tmp[] = $obj;
                }
                $data = $tmp;
            }
        }
        return $data;
    }


    /**
     *get list external links
     *@param string content
     *@return array list of external links
     */
    public static function parseExternalLinks($content, $params)
    {

        $regex = '#\[link ([^\]]*)\]([^\[]*)\[/link\]#m';
        preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
        $maxItems = $params->get('max_items', 5);
        $linkArray = array();
        if (!empty($matches)) {
            $i = 0;
            foreach ($matches as $match) {
                $params = modjanewstickerHelper::parseParams($match[1]);
                if (is_array($params)) {
                    if ($maxItems > 0 && $i == $maxItems)
                        break;
                    $url = isset($params['url']) ? trim($params['url']) : '';
                    $title = isset($params['title']) ? trim($params['title']) : 'janewsticker';
                    $obj = new stdClass();
                    $obj->title = $title;
                    $obj->link = $url;
                    $obj->introtext = str_replace("\n", "<br />", trim($match[2]));
                    $linkArray[] = $obj;
                    $i++;
                }
            }
        }
        return $linkArray;
    }


    /**
     * trim string with max specify
     *
     * @param string $title
     * @param integer $max.
     */
    public static function trimString($title, $max = 60)
    {
        if (strlen($title) > $max) {
            return substr($title, 0, $max);
        }
        return $title . '...';
    }


    /**
     * get description of item: trim string with max chars.
     *
     * @param string $introtext
     * @param string $separator
     * @param string $descMaxChars
     * @return string
     */
    function getDescription($introtext, $separator, $descMaxChars = 60)
    {
        return $separator . ' ' . modJAHeadLinesHelper::trimString(strip_tags($introtext), $descMaxChars);
    }


    /**
     * detect and get link with each resource
     *
     * @param string $item
     * @param bool $useRSS.
     * @return string.
     */
    public static function getLink($item, $useRSS = false)
    {
        if ($useRSS) {
            return $item->link;
        } else {
            if (!isset($item->slug) && !isset($item->catslug) && isset($item->categoryalias)) {
                return urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id . ':' . urlencode($item->alias), $item->catid . ':' . urlencode($item->categoryalias))));
            } else {
                return JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
            }
        }
    }


    /**
     * get size follow up animation type.
     *
     * @param JPramater $params
     * @return integer.
     */
    function getSize($params)
    {
        $mode = $params->get('animation_type');

        if ($mode == 'vertical') {
            return $params->get('height', 30);
        }
        return $params->get('width', 500);
    }


    /**
     * get parameters from configuration string.
     *
     * @param string $string;
     * @return array.
     */
    public static function parseParams($string)
    {
        $string = html_entity_decode($string, ENT_QUOTES);
        $regex = "/\s*([^=\s]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
        $params = null;
        if (preg_match_all($regex, $string, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $key = $matches[1][$i];
                $value = $matches[3][$i] ? $matches[3][$i] : ($matches[4][$i] ? $matches[4][$i] : $matches[5][$i]);
                $params[$key] = $value;
            }
        }
        return $params;
    }
}
?>