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

class PlgSystemArticlesAnywhereHelperItems
{
	var     $helpers              = array();
	var     $params               = null;
	private $aid                  = null;
	private $data                 = null;
	private $content_items        = array();
	private $content_items_to_ids = array();
	private $current_article      = null;
	private $current_article_id   = null;
	private $message              = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = PlgSystemArticlesAnywhereHelpers::getInstance();
		$this->params  = $this->helpers->getParams();

		$this->aid = JFactory::getUser()->getAuthorisedViewLevels();
	}

	public function _($matches, $message)
	{
		$this->message = $message;

		$items = array();
		foreach ($matches as $match)
		{
			$item = $this->getItem($match);

			$items[] = $item;
		}

		$this->setContentItemData($items);

		return $items;
	}

	private function setContentItemData(&$items)
	{
		$this->storeSingleContentItems($items);

		$this->setContentItems($items);
	}

	private function setContentItems(&$items)
	{
		foreach ($items as $item)
		{
			$this->data   = $item;
			$item->output = $this->getHtmlOutput();
		}

		return $items;
	}

	private function getHtmlOutput()
	{
		if (empty($this->data->sets))
		{
			return '';
		}

		if ($this->message != '')
		{
			if ($this->params->place_comments)
			{
				return $this->params->comment_start . $this->params->message_start
				. $this->message
				. $this->params->message_end . $this->params->comment_end;
			}

			return '';
		}

		list($tag_start, $tag_end) = $this->getDataTagCharacters();

		if (empty($this->data->content))
		{
			$this->data->content = $tag_start . 'layout' . $tag_end;
		}

		$total = 0;

		foreach ($this->data->sets as $item)
		{
			$content_items = $this->getContentItemsBySet($item);

			if (!empty($content_items) && is_array($content_items))
			{
				$content_items = array_filter($content_items);
			}

			if (empty($content_items))
			{
				continue;
			}

			foreach ($content_items as $i => $content_item)
			{
				if (empty($content_item))
				{
					unset($content_items[$i]);
					continue;
				}
			}

			$total += count($content_items);

			$item->content_items = $content_items;
		}

		$output = array();

		$count = 1;

		foreach ($this->data->sets as $item)
		{
			if (empty($item->content_items))
			{
				if (!empty($item->empty))
				{
					$output[] =
						$this->data->opening_tags_item
						. $item->empty
						. $this->data->closing_tags_item;
				}

				continue;
			}

			foreach ($item->content_items as $content_item)
			{
				$helper = 'datatags';

				$this->helpers->_($helper)->setNumbers($total, $count++);
				$this->helpers->_($helper)->data->current = ($content_item->id == $this->getCurrentArticleId($item->type));

				$html = $this->getOutput($item, $content_item);

				$output[] =
					$this->data->opening_tags_item
					. $html
					. $this->data->closing_tags_item;
			}
		}

		return $output;
	}

	private function getOutput($item, $content_item, $firstpass = false)
	{
		list($tag_start, $tag_end) = $this->getDataTagCharacters(true);

		if ($firstpass)
		{
			// first pass: search for normal tags and tags around tags
			$regex  = '#' . $tag_start . '(/?[a-z0-9][^' . $tag_end . ']*?|/?[a-z0-9](?:[^' . $tag_start . ']*?' . $tag_start . '[^' . $tag_end . ']*?' . $tag_end . ')*[^' . $tag_end . ']*?)' . $tag_end . '#si';
			$output = $this->data->content;
		}
		else
		{
			// do second pass
			$output = $this->getOutput($item, $content_item, true);

			$regex_close = '#' . $tag_start . '/' . $this->helpers->_('replace')->params->tags . $tag_end . '#si';
			if (preg_match($regex_close, $output))
			{
				return $output;
			}

			// second pass: only search for normal tags
			$regex = '#' . $tag_start . '(/?[a-z0-9][^' . $tag_end . ']*?)' . $tag_end . '#si';
		}

		preg_match_all($regex, $this->data->content, $matches, PREG_SET_ORDER);

		if (empty($matches))
		{
			return $this->data->content;
		}

		if (empty($content_item))
		{
			return '<!-- ' . JText::_('AA_ACCESS_TO_ARTICLE_DENIED') . ' -->';
		}

		$this->prepareContentItem($item, $content_item, $output);

		$helper = 'datatags';

		$this->helpers->_($helper)->handleIfStatements($output, $content_item);

		preg_match_all($regex, $output, $matches, PREG_SET_ORDER);

		if (empty($matches))
		{
			return $output;
		}

		$this->helpers->_($helper)->replaceTags($output, $matches, $content_item);

		return $output;
	}

	private function prepareContentItem($item, $content_item, $output)
	{
		self::addParams(
			$content_item,
			json_decode(
				isset($content_item->attribs)
					? $content_item->attribs
					: $content_item->params
			)
		);

		if (isset($content_item->images))
		{
			self::addParams($content_item, json_decode($content_item->images));
		}

		if (isset($content_item->urls))
		{
			self::addParams($content_item, json_decode($content_item->urls));
		}


		if (strpos($output, 'tag') !== false)
		{
			$method = 'addTags';
			self::$method($content_item);
		}
	}

	public function addTags(&$content_item)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('tags.title'))
			->from($db->quoteName('#__tags', 'tags'))
			->join('LEFT', $db->quoteName('#__contentitem_tag_map', 'xref')
				. ' ON ' . $db->quoteName('xref.tag_id') . ' = ' . $db->quoteName('tags.id'))
			->where($db->quoteName('xref.content_item_id') . ' = ' . (int) $content_item->id)
			->where($db->quoteName('tags.published') . ' = 1');

		$db->setQuery($query);

		$content_item->tags = $db->loadColumn();
	}

	public function addTagsK2(&$content_item)
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('tags.name'))
			->from($db->quoteName('#__k2_tags', 'tags'))
			->join('LEFT', $db->quoteName('#__k2_tags_xref', 'xref')
				. ' ON ' . $db->quoteName('xref.tagID') . ' = ' . $db->quoteName('tags.id'))
			->where($db->quoteName('xref.itemID') . ' = ' . (int) $content_item->id)
			->where($db->quoteName('tags.published') . ' = 1');

		$db->setQuery($query);

		$content_item->tags = $db->loadColumn();
	}

	private function getContentItemsBySet($data)
	{
		if (!empty($data->current))
		{
			return array($this->getCurrentArticle($data));
		}


		if (empty($data->ids))
		{
			return;
		}

		return $this->getSingleContentItems($data);
	}

	private function storeSingleContentItems($items)
	{
		$database_ids = array();

		foreach ($items as $item)
		{
			foreach ($item->sets as $data)
			{
				if (empty($data->id) || !empty($data->current) || isset($data->featured) || !empty($data->is_category) || !empty($data->is_tag))
				{
					continue;
				}

				if (!isset($database_ids[$data->type]))
				{
					$database_ids[$data->type] = array();
				}

				$database_ids[$data->type][] = $data->id;
			}
		}

		$this->storeSingleContentItemsFromDatabase($database_ids);
	}

	private function storeSingleContentItemsFromDatabase($database_ids)
	{
		if (empty($database_ids))
		{
			return;
		}

		foreach ($database_ids as $type => $ids)
		{
			$table = 'content';

			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('a.*')
				->select('CONCAT("' . $type . '_", ' . $db->quoteName('a.id') . ') AS type_id')
				->select($db->quoteName('a.access') . ' IN (' . implode(', ', $this->aid) . ') as has_access')
				->from($db->quoteName('#__' . $table, 'a'));

			$conditions = array();

			$ids = array_unique($ids);
			foreach ($ids as $id)
			{
				if (isset($this->content_items_to_ids[$type . ' ' . $id]))
				{
					continue;
				}

				$where = $db->quoteName('a.title') . ' = ' . $db->quote(RLText::html_entity_decoder($id));
				$where .= ' OR ' . $db->quoteName('a.alias') . ' = ' . $db->quote(RLText::html_entity_decoder($id));

				if (is_numeric($id))
				{
					$where .= ' OR ' . $db->quoteName('a.id') . ' = ' . $id;
				}

				$conditions[] = $where;
			}

			if (empty($conditions))
			{
				continue;
			}

			$query->where('((' . implode(') OR (', $conditions) . '))');

			$temp = (object) array('type' => $type);
			$this->setQueryConditions($query, $temp);

			$db->setQuery($query);

			$content_items       = $db->loadObjectList('type_id');
			$this->content_items = array_merge($this->content_items, $content_items);

			foreach ($content_items as $type_id => $content_item)
			{
				$this->content_items_to_ids[$type . '_' . $content_item->alias] = $type . '_' . $content_item->id;
				$this->content_items_to_ids[$type . '_' . $content_item->title] = $type . '_' . $content_item->id;
			}
		}
	}

	public function getQueryIdLists($ids)
	{
		if (!is_array($ids))
		{
			$ids = array($ids);
		}

		$db = JFactory::getDbo();

		$numeric      = array();
		$not_nummeric = array();
		$likes        = array();

		foreach ($ids as &$id)
		{
			if (is_numeric($id))
			{
				$numeric[] = $db->quote($id);
				continue;
			}

			if (strpos($id, '*') !== false)
			{
				$likes[] = $db->quote(str_replace('*', '%', $id));
				continue;
			}
			$not_nummeric[] = $db->quote($id);
		}

		return array($numeric, $not_nummeric, $likes);
	}

	private function getCategoryIds($item)
	{
		list($ids, $titles, $likes) = $this->getQueryIdLists($item->categories);

		if (empty($titles) && empty($likes))
		{
			return $ids;
		}

		$table = 'content';
		$title = 'title';

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->clear()
			->select($db->quoteName('a.id'))
			->from($db->quoteName('#__' . $table, 'a'));

		if ($table == 'content')
		{
			$query->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'));
		}

		if (!empty($titles))
		{
			$wheres[] = $db->quoteName('a.' . $title) . ' IN (' . implode(',', $titles) . ')';
			$wheres[] = $db->quoteName('a.alias') . ' IN (' . implode(',', $titles) . ')';

			$query->where('(' . implode(' OR ', $wheres) . ')');
		}

		if (!empty($likes))
		{
			$wheres = array();
			foreach ($likes as $like)
			{
				$wheres[] = $db->quoteName('a.' . $title) . ' LIKE ' . $like;
				$wheres[] = $db->quoteName('a.alias') . ' LIKE ' . $like;
			}
			$query->where('(' . implode(' OR ', $wheres) . ')');
		}

		$ignore_language = isset($item->ignore_language) ? $item->ignore_language : $this->params->ignore_language;
		$ignore_state    = isset($item->ignore_state) ? $item->ignore_state : $this->params->ignore_state;
		$ignore_access   = isset($item->ignore_access) ? $item->ignore_access : $this->params->ignore_access;

		if (!$ignore_language)
		{
			$query->where($db->quoteName('a.language') . ' IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		if (!$ignore_state)
		{
			$where = $db->quoteName('a.published') . ' = 1';

			$query->where($where);
		}

		if (!$ignore_access)
		{
			$query->where($db->quoteName('a.access') . ' IN (' . implode(', ', $this->aid) . ')');
		}

		$db->setQuery($query);

		return array_merge($ids, $db->loadColumn());
	}

	private function getCategoryItems($item)
	{
		if (empty($item->categories))
		{
			return false;
		}

		$cat_ids = $this->getCategoryIds($item);

		if (empty($cat_ids))
		{
			return false;
		}

		$limit  = isset($item->limit) ? $item->limit : ((int) $this->params->limit ? $this->params->limit : 1000);
		$offset = isset($item->offset) ? $item->offset : 0;

		$table = 'content';

		$db = JFactory::getDbo();

		$query = $this->getContentItemQuery($item);

		$query->where($db->quoteName('a.catid') . ' IN (' . implode(',', $cat_ids) . ')');

		if (!empty($item->id))
		{
			$this->setWhereConditionsForContentIds($item->id, $query);
		}

		if (!empty($item->is_tag))
		{
			$item_ids   = $this->getTagItemIds($item);
			$item_ids[] = 0;

			$query->where($db->quoteName('a.id') . ' IN (' . implode(',', $item_ids) . ')');
		}

		$db->setQuery($query, (int) $offset, (int) $limit);

		return $db->loadObjectList();
	}

	private function getTagIds($item)
	{
		list($ids, $titles, $likes) = $this->getQueryIdLists($item->tags);

		if (empty($titles) && empty($likes))
		{
			return $ids;
		}

		$table = 'tags';
		$title = 'title';
		$alias = 'alias';

		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->clear()
			->select($db->quoteName('a.id'))
			->from($db->quoteName('#__' . $table, 'a'));

		if (!empty($titles))
		{
			$wheres[] = $db->quoteName('a.' . $title) . ' IN (' . implode(',', $titles) . ')';
			$wheres[] = $db->quoteName('a.' . $alias) . ' IN (' . implode(',', $titles) . ')';

			$query->where('(' . implode(' OR ', $wheres) . ')');
		}

		if (!empty($likes))
		{
			$wheres = array();
			foreach ($likes as $like)
			{
				$wheres[] = $db->quoteName('a.' . $title) . ' LIKE ' . $like;
				$wheres[] = $db->quoteName('a.' . $alias) . ' LIKE ' . $like;
			}
			$query->where('(' . implode(' OR ', $wheres) . ')');
		}

		$ignore_language = isset($item->ignore_language) ? $item->ignore_language : $this->params->ignore_language;
		$ignore_state    = isset($item->ignore_state) ? $item->ignore_state : $this->params->ignore_state;
		$ignore_access   = isset($item->ignore_access) ? $item->ignore_access : $this->params->ignore_access;

		if (!$ignore_language && $item->type != 'k2')
		{
			$query->where($db->quoteName('a.language') . ' IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		if (!$ignore_state)
		{
			$query->where($db->quoteName('a.published') . ' = 1');
		}

		if (!$ignore_access && $item->type != 'k2')
		{
			$query->where($db->quoteName('a.access') . ' IN (' . implode(', ', $this->aid) . ')');
		}

		$db->setQuery($query);

		return array_merge($ids, $db->loadColumn());
	}

	private function getTagItemIds($item, $is_category = false)
	{
		$tag_ids = $this->getTagIds($item);

		if (empty($tag_ids))
		{
			return array();
		}

		$db = JFactory::getDbo();

		$type  = $is_category ? 'com_content.category' : 'com_content.article';
		$query = $db->getQuery(true)
			->select($db->quoteName('content_item_id'))
			->from($db->quoteName('#__contentitem_tag_map'))
			->where($db->quoteName('type_alias') . ' = ' . $db->quote($type))
			->where($db->quoteName('tag_id') . ' IN (' . implode(',', $tag_ids) . ')');
		$db->setQuery($query);

		return $db->loadColumn();
	}

	private function getTagItems($item)
	{
		if (empty($item->tags))
		{
			return false;
		}

		$item_ids = $this->getTagItemIds($item);

		if (empty($item_ids))
		{
			return false;
		}

		$limit  = isset($item->limit) ? $item->limit : ((int) $this->params->limit ? $this->params->limit : 1000);
		$offset = isset($item->offset) ? $item->offset : 0;

		$table = 'content';

		$db = JFactory::getDbo();

		$query = $this->getContentItemQuery($item);

		$query->where($db->quoteName('a.id') . ' IN (' . implode(',', $item_ids) . ')');

		$db->setQuery($query, (int) $offset, (int) $limit);

		return $db->loadObjectList();
	}

	private function getFeaturedItems($item)
	{
		$db = JFactory::getDbo();

		$query = $this->getContentItemQuery($item);

		$db->setQuery($query);

		return $db->loadObjectList();
	}

	private function getSingleContentItems($item)
	{
		$items = array();

		foreach ($item->ids as $id)
		{
			$items[] = $this->getSingleContentItem($item, $id);
		}

		return $items;
	}

	private function getSingleContentItem($item, $id)
	{
		if ($stored = $this->getSingleContentItemStored($item, $id))
		{
			return $stored;
		}

		$db = JFactory::getDbo();

		$query = $this->getContentItemQuery($item);

		$this->setWhereConditionsForContentIds($id, $query);

		$db->setQuery($query, 0, 1);

		return $db->loadObject();
	}

	private function setWhereConditionsForContentIds($id, &$query)
	{
		$db = JFactory::getDbo();

		list($ids, $titles, $likes) = $this->getQueryIdLists($id);

		$title = 'title';

		if (!empty($ids))
		{
			$wheres[] = $db->quoteName('a.' . $title) . ' IN (' . implode(',', $ids) . ')';
			$wheres[] = $db->quoteName('a.alias') . ' IN (' . implode(',', $ids) . ')';
			$wheres[] = $db->quoteName('a.id') . ' IN (' . implode(',', $ids) . ')';

			$query->where('(' . implode(' OR ', $wheres) . ')');
		}

		if (!empty($titles))
		{
			$wheres[] = $db->quoteName('a.' . $title) . ' IN (' . implode(',', $titles) . ')';
			$wheres[] = $db->quoteName('a.alias') . ' IN (' . implode(',', $titles) . ')';

			$query->where('(' . implode(' OR ', $wheres) . ')');
		}

		if (!empty($likes))
		{
			$wheres = array();
			foreach ($likes as $like)
			{
				$wheres[] = $db->quoteName('a.' . $title) . ' LIKE ' . $like;
				$wheres[] = $db->quoteName('a.alias') . ' LIKE ' . $like;
			}
			$query->where('(' . implode(' OR ', $wheres) . ')');
		}
	}

	private function getSingleContentItemStored($item, $id)
	{
		if (!$stored = $this->getSingleItemFromStoredItems($item, $id))
		{
			return false;
		}

		$featured        = isset($item->featured) ? $item->featured : false;
		$ignore_language = isset($item->ignore_language) ? $item->ignore_language : $this->params->ignore_language;
		$ignore_state    = isset($item->ignore_state) ? $item->ignore_state : $this->params->ignore_state;
		$ignore_access   = isset($item->ignore_access) ? $item->ignore_access : $this->params->ignore_access;


		if (!$ignore_language && !in_array($stored->language, array(JFactory::getLanguage()->getTag(), '*')))
		{
			return false;
		}

		if (!$ignore_state)
		{
			$state = 'state';

			if (!$stored->{$state})
			{
				return false;
			}

			$db       = JFactory::getDbo();
			$jnow     = JFactory::getDate();
			$now      = $jnow->toSql();
			$nullDate = $db->getNullDate();

			if (
				$stored->publish_up > $now || ($stored->publish_down != $nullDate && $stored->publish_down < $now)
			)
			{
				return false;
			}
		}

		if (!$ignore_access && !in_array($stored->access, $this->aid))
		{
			return false;
		}

		return $stored;
	}

	private function getSingleItemFromStoredItems($item, $id = 0)
	{
		$id = $id ?: $item->id;

		$type_id = $item->type . '_' . $id;

		if (isset($this->content_items[$id]))
		{
			return $this->content_items[$id];
		}

		if (isset($this->content_items_to_ids[$type_id]))
		{
			return $this->content_items[$this->content_items_to_ids[$type_id]];
		}

		return false;
	}

	private function getContentItemQuery($item)
	{
		$table     = 'content';
		$cat_table = 'categories';
		$cat_title = 'title';

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select(array(
				'a.*',
				$db->quoteName('c.' . $cat_title, 'cat_title'),
				$db->quoteName('c.' . $cat_title, 'cat_name'),
				$db->quoteName('c.alias', 'cat_alias'),
			))
			->select($db->quoteName('a.access') . ' IN (' . implode(', ', $this->aid) . ') as has_access')
			->from($db->quoteName('#__' . $table, 'a'))
			->join('LEFT', $db->quoteName('#__' . $cat_table, 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid'));

		$this->setQueryConditions($query, $item);

		return $query;
	}

	private function setQueryConditions(&$query, $item)
	{
		$db = JFactory::getDbo();

		$ignore_language = isset($item->ignore_language) ? $item->ignore_language : $this->params->ignore_language;
		$ignore_state    = isset($item->ignore_state) ? $item->ignore_state : $this->params->ignore_state;
		$ignore_access   = isset($item->ignore_access) ? $item->ignore_access : $this->params->ignore_access;

		

		if (!$ignore_language)
		{
			$query->where($db->quoteName('a.language') . ' IN (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}

		if (!$ignore_state)
		{
			$jnow     = JFactory::getDate();
			$now      = $jnow->toSql();
			$nullDate = $db->getNullDate();

			$where = $db->quoteName('a.state') . ' = 1';

			$query->where($where)
				->where('( ' . $db->quoteName('a.publish_up') . ' <= ' . $db->quote($now) . ' )')
				->where('( ' . $db->quoteName('a.publish_down') . ' = ' . $db->quote($nullDate)
					. ' OR ' . $db->quoteName('a.publish_down') . ' >= ' . $db->quote($now) . ' )');
		}

		if (!$ignore_access)
		{
			$query->where($db->quoteName('a.access') . ' IN (' . implode(', ', $this->aid) . ')');
		}
		$query->order($db->quoteName('a.ordering') . ' ASC');
	}

	private function getItem($data)
	{
		$sets = $this->getTagValues($data);

		if (empty($sets))
		{
			return null;
		}

		$opening_tags_main = RLTags::removeEmptyHtmlTagPairs(
			$data['opening_tags_before_open']
			. $data['closing_tags_after_open']
		);

		$opening_tags_item = $data['opening_tags_before_content'];

		$closing_tags_item = $data['closing_tags_after_content'];

		$closing_tags_main = RLTags::removeEmptyHtmlTagPairs(
			$data['opening_tags_before_close']
			. $data['closing_tags_after_close']
		);

		return (object) array(
			'original_string'   => $data['0'],
			'tag'               => $data['tag'],
			'content'           => $data['html'],
			'opening_tags_main' => $opening_tags_main,
			'closing_tags_main' => $closing_tags_main,
			'opening_tags_item' => $opening_tags_item,
			'closing_tags_item' => $closing_tags_item,
			'sets'              => $sets,
		);
	}

	private function getTagValues($data)
	{
		$string = RLText::html_entity_decoder($data['id']);

		if (strpos($string, '="') == false)
		{
			$string = $this->convertTagToNewSyntax($string, $data['tag']);
		}

		$parts = array($string);

		$known_boolean_keys = array(
			'ignore_language', 'ignore_access', 'ignore_state',
		);

		list($tag_start, $tag_end) = $this->getDataTagCharacters();

		$sets = array();

		foreach ($parts as $string)
		{
			// Get the values from the tag
			$set = RLTags::getValuesFromString($string, 'id', $known_boolean_keys);

			$key_aliases = array(
				'id'                 => array('ids', 'article', 'articles', 'item', 'items', 'title', 'alias'),
			);

			RLTags::replaceKeyAliases($set, $key_aliases);

			$type = 'joomla';
			$set->type = $type;

			$set->ids = array();


			if (empty($set->id))
			{
				$set->id = 'current';
			}

			$set->ids = array($set->id);
			foreach ($set->ids as $id)
			{
				if (in_array($id, array('current', 'self', $tag_start . 'id' . $tag_end, $tag_start . 'title' . $tag_end, $tag_start . 'alias' . $tag_end), true))
				{
					$set->current = true;
					$id           = '';
				}

				$set->id = $id;
			}

			$sets[] = clone $set;
		}

		return $sets;
	}

	private function getIdsFromString($ids)
	{
		RLTags::protectSpecialChars($ids);
		$ids = explode(',', $ids);

		foreach ($ids as &$id)
		{
			RLTags::unprotectSpecialChars($id);
		}

		return $ids;
	}

	private function getCurrentArticle($item)
	{
		$id = $this->getCurrentArticleId($item->type);

		if (empty($id))
		{
			return;
		}

		$item->id = $id;

		$this->current_article = $this->getSingleContentItem($item, $id);

		return $this->current_article;
	}

	private function getCurrentArticleId($type = 'joomla')
	{
		$this->current_article_id = $this->findCurrentArticleId($type);

		return $this->current_article_id;
	}

	private function findCurrentArticleId($type = 'joomla')
	{
		if (!is_null($this->helpers->_('replace')->current_article_id))
		{
			return $this->helpers->_('replace')->current_article_id;
		}

		if (isset($this->current_article->id))
		{
			return $this->current_article->id;
		}

		if (isset($this->current_article->link) && preg_match('#&(?:amp;)?id=([0-9]*)#', $this->current_article->link, $match))
		{
			return $match['1'];
		}

		if ($type == 'joomla' && $this->params->option == 'com_content' && JFactory::getApplication()->input->get('view') == 'article')
		{
			return JFactory::getApplication()->input->getInt('id', 0);
		}


		return 0;
	}

	private function convertTagToNewSyntax($string, $tag_type)
	{
		RLTags::protectSpecialChars($string);

		if (strpos($string, '|') == false && strpos($string, ':') == false)
		{
			RLTags::unprotectSpecialChars($string);

			return $string;
		}

		RLTags::protectSpecialChars($string);

		$sets = explode('|', $string);

		foreach ($sets as &$set)
		{
			if (strpos($set, ':') == false)
			{
				continue;
			}

			$parts = explode(':', $set);

			$id         = array_pop($parts);
			$attributes = array();
			$id_name = 'id';

			foreach ($parts as $part)
			{
				switch (true)
				{
					case ($part == 'k2'):
						$attributes[] = 'k2="1"';
						break;

					case ($part == 'cat'):
						$id_name = 'category';
						$attributes[] = 'is_category="1"';
						break;

					case ($part == 'tag'):
						$id_name = 'tag';
						$attributes[] = 'is_tag="1"';
						break;

					case (is_numeric($part)):
					case (preg_match('#^([0-9]+)-([0-9]+)$#', $part)):
						$attributes[] = 'limit="' . $part . '"';
						break;

					case ($tag_type == $this->params->article_tag):
						$id = $part . ':' . $id;
						break;

					default:
						$attributes[] = 'ordering="' . trim($part) . '"';
						break;
				}
			}

			array_unshift($attributes, $id_name . '="' . $id . '"');

			$set = implode(' ', $attributes);
		}

		$string = implode(' && ', $sets);

		return $string;
	}

	private function addParams(&$article, $params)
	{
		if (!$params
			|| (!is_object($params) && !is_array($params))
		)
		{
			return;
		}

		foreach ($params as $key => $val)
		{
			if (isset($article->{$key}))
			{
				continue;
			}

			$article->{$key} = $val;
		}
	}

	public function getTagCharacters($quote = false)
	{
		if (!isset($this->params->tag_character_start))
		{
			list($this->params->tag_character_start, $this->params->tag_character_end) = explode('.', $this->params->tag_characters);
		}

		$start = $this->params->tag_character_start;
		$end   = $this->params->tag_character_end;

		if ($quote)
		{
			$start = preg_quote($start, '#');
			$end   = preg_quote($end, '#');
		}

		return array($start, $end);
	}

	public function getDataTagCharacters($quote = false)
	{
		if (!isset($this->params->tag_character_data_start))
		{
			list($this->params->tag_character_data_start, $this->params->tag_character_data_end) = explode('.', $this->params->tag_characters_data);
		}

		$start = $this->params->tag_character_data_start;
		$end   = $this->params->tag_character_data_end;

		if ($quote)
		{
			$start = preg_quote($start, '#');
			$end   = preg_quote($end, '#');
		}

		return array($start, $end);
	}
}
