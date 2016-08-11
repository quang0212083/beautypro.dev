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

class PlgSystemArticlesAnywhereHelperDataTags
{
	var $helpers = array();
	var $params  = null;
	var $config  = null;
	var $article = null;
	var $data    = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = PlgSystemArticlesAnywhereHelpers::getInstance();
		$this->params  = $this->helpers->getParams();

		$this->config = JComponentHelper::getParams('com_content');

		$this->data = (object) array(
			'article' => null,
			'current' => true,
		);
		$this->setNumbers(1, 1);
	}

	public function setNumbers($total, $count)
	{
		$this->data->total  = $total;
		$this->data->count  = $count;
		$this->data->even   = ($count % 2) == 0;
		$this->data->uneven = ($count % 2) != 0;
		$this->data->first  = $count == 1;
		$this->data->last   = $count == $total;
	}

	public function handleIfStatements(&$string, &$article)
	{
		list($tag_start, $tag_end) = $this->helpers->_('replace')->getTagCharacters(true);

		preg_match_all(
			'#' . $tag_start . 'if[\: ].*?' . $tag_start . '/if' . $tag_end . '#si',
			$string,
			$matches,
			PREG_SET_ORDER
		);

		if (empty($matches))
		{
			return;
		}

		$this->data->article = $article;

		if (strpos($string, 'text') !== false)
		{
			$article->text = (isset($article->introtext) ? $article->introtext : '')
				. (isset($article->introtext) ? $article->fulltext : '');
		}

		foreach ($matches as $match)
		{
			preg_match_all(
				'#' . $tag_start . '(if|else ?if|else)(?:[\: ](.+?))?' . $tag_end . '(.*?)(?=' . $tag_start . '(?:else|\/if))#si',
				$match['0'],
				$ifs,
				PREG_SET_ORDER
			);

			if (empty($ifs))
			{
				continue;
			}

			$replace = $this->getIfResult($ifs);

			// replace if block with the IF value
			$string = RLText::strReplaceOnce($match['0'], $replace, $string);
		}

		$article = $this->data->article;
	}

	private function getIfResult(&$matches)
	{
		foreach ($matches as $if)
		{
			if (!$this->passIfStatements($if))
			{
				continue;
			}

			return $if['3'];
		}

		return '';
	}

	private function passIfStatements($if)
	{
		$statement = trim($if['2']);

		if (trim($if['1']) == 'else' && $statement == '')
		{
			return true;
		}

		if ($statement == '')
		{
			return false;
		}

		$statement = RLText::html_entity_decoder($statement);
		$statement = str_replace(
			array(' AND ', ' OR '),
			array(' && ', ' || '),
			$statement
		);

		$ands = explode(' && ', $statement);

		$pass = false;
		foreach ($ands as $statement)
		{
			$ors = explode(' || ', $statement);
			foreach ($ors as $statement)
			{
				if ($pass = $this->passIfStatement($statement))
				{
					break;
				}
			}

			if (!$pass)
			{
				break;
			}
		}

		return $pass;
	}

	private function passIfStatement($statement)
	{
		$statement = trim($statement);

		/*
		* In array syntax
		* 'bar' IN foo
		* 'bar' !IN foo
		* 'bar' NOT IN foo
		*/
		if (preg_match('#^[\'"]?(?P<val>.*?)[\'"]?\s+(?P<operator>(?:NOT\s+)?\!?IN)\s+(?P<key>[a-zA-Z0-9-_]+)$#s', $statement, $match))
		{
			$reverse = ($match['operator'] == 'NOT IN' || $match['operator'] == '!NOT');

			return $this->passIfStatementArray(
				$this->getValueFromData($match['key']),
				$this->getValueFromData($match['val'], $match['val']),
				$reverse
			);
		}

		/*
		* String comparison syntax:
		* foo = 'bar'
		* foo != 'bar'
		*/
		if (preg_match('#^(?P<key>[a-z0-9-_]+)\s*(?P<operator>\!?=)=*\s*[\'"]?(?P<val>.*?)[\'"]?$#si', $statement, $match))
		{
			$reverse = ($match['2'] == '!=');

			return $this->passIfStatementArray(
				$this->getValueFromData($match['key']),
				$this->getValueFromData($match['val'], $match['val']),
				$reverse
			);
		}

		/*
		* Variable check syntax:
		* foo (= not empty)
		* !foo (= empty)
		*/
		if (preg_match('#^(?P<operator>\!?)(?P<key>[a-z0-9-_]+)$#si', $statement, $match))
		{
			$reverse = ($match['operator'] == '!');

			return $this->passIfStatementSimple(
				$this->getValueFromData($match['key']),
				$reverse
			);
		}

		return $this->passIfStatementPHP($statement);
	}

	private function getValueFromData($key, $default = null)
	{
		if (!is_string($key))
		{
			return $default;
		}

		return isset($this->data->{$key}) ? $this->data->{$key} : (isset($this->data->article->{$key}) ? $this->data->article->{$key} : $default);
	}

	private function passIfStatementSimple($haystack, $reverse = 0)
	{
		if (is_null($haystack))
		{
			return false;
		}

		$pass = !empty($haystack);

		return $reverse ? !$pass : $pass;
	}

	private function passIfStatementString($haystack, $needle, $reverse = 0)
	{
		if (is_null($haystack))
		{
			return false;
		}

		if (is_array($haystack))
		{
			return $this->passIfStatementArray($haystack, $needle, $reverse);
		}

		$pass = $this->passString($haystack, $needle);

		return $reverse ? !$pass : $pass;
	}

	private function passIfStatementArray($haystack, $needle, $reverse = 0)
	{
		if (is_null($haystack))
		{
			return false;
		}

		if (!is_array($haystack))
		{
			$haystack = explode(',', str_replace(', ', ',', $haystack));
		}

		if (!is_array($haystack))
		{
			return false;
		}

		$pass = false;
		foreach ($haystack as $string)
		{
			if ($pass = $this->passString($string, $needle))
			{
				break;
			}
		}

		return $reverse ? !$pass : $pass;
	}

	private function passIfStatementPHP($statement)
	{
		$php = RLText::html_entity_decoder($statement);
		$php = preg_replace('#([^<>])=([^<>])#', '\1==\2', $php);

		// replace keys with $article->key
		$php = '$article->' . preg_replace('#\s*(&&|&&|\|\|)\s*#', ' \1 $article->', $php);

		// fix negative keys from $article->!key to !$article->key
		$php = str_replace('$article->!', '!$article->', $php);

		// replace back data variables
		foreach ($this->data as $key => $val)
		{
			if ($key == 'article')
			{
				continue;
			}

			$php = str_replace('$article->' . $key, (int) $val, $php);
		}
		$php = str_replace('$article->empty', (int) ($this->data->count > 0), $php);

		// Place statement in return check
		$php = 'return ( ' . $php . ' ) ? 1 : 0;';

		// Trim the text that needs to be checked and replace weird spaces
		$php = preg_replace(
			'#(\$article->[a-z0-9-_]*)#',
			'trim(str_replace(chr(194) . chr(160), " ", \1))',
			$php
		);

		// Fix extra-1 field syntax: $article->extra-1 to $article->{'extra-1'}
		$php = preg_replace(
			'#->(extra-[a-z0-9]+)#',
			'->{\'\1\'}',
			$php
		);

		$temp_PHP_func = create_function('&$article', $php);

		// evaluate the script
		// but without using the the evil eval
		ob_start();
		$pass = $temp_PHP_func($this->data->article);
		unset($temp_PHP_func);
		ob_end_clean();

		return $pass;
	}

	private function passString($haystack, $needle)
	{
		if (!is_string($haystack) && !is_string($needle)
			&& !is_numeric($haystack)
			&& !is_numeric($needle)
		)
		{
			return false;
		}

		// Simple string comparison
		if (strpos($needle, '*') === false && strpos($needle, '+') === false)
		{
			return strtolower($haystack) == strtolower($needle);
		}

		// Using wildcards
		$needle = preg_quote($needle, '#');
		$needle = str_replace(
			array('\\\\\\*', '\\*', '[:asterisk:]', '\\\\\\+', '\\+', '[:plus:]'),
			array('[:asterisk:]', '.*', '\\*', '[:plus:]', '.+', '\\+'),
			$needle
		);

		return preg_match('#' . $needle . '#si', $haystack);
	}

	public function replaceTags(&$text, &$matches, &$article)
	{
		$this->article = $article;
		foreach ($matches as $match)
		{
			$string = $this->processTag($match['1']);
			if ($string === false)
			{
				continue;
			}

			$text = str_replace($match['0'], $string, $text);
		}
	}

	public function getTagValues($string)
	{
		$tag = $this->getTagValuesFromString($string);

		$key_aliases = array(
			'limit'      => array('letters', 'letter_limit', 'characters', 'character_limit'),
			'words'      => array('word', 'word_limit'),
			'paragraphs' => array('paragraph', 'paragraph_limit'),
			'class'      => array('classes'),
		);

		RLTags::replaceKeyAliases($tag, $key_aliases);

		return $tag;
	}

	public function getTagValuesFromString($string)
	{
		if (preg_match('#^layout[ \:]([^=]+)$#', $string, $match))
		{
			$string = 'layout layout="' . trim($match['1']) . '"';
		}

		if (strpos($string, ':') !== false
			&& preg_match('#^([a-z]+ )?[a-z]+\s*:\s*[a-z0-9\|]#si', $string)
		)
		{
			return $this->getTagValuesFromOldSyntax($string);
		}

		$string = preg_replace('#^(.*?) #s', 'type="\1" ', $string);

		return RLTags::getValuesFromString($string, 'type');
	}

	public function getTagValuesFromOldSyntax($string)
	{
		$tag = new stdClass;

		if (strpos($string, ' ') !== false
			&& preg_match('#^[a-z]+ [a-z0-9\|]#', $string)
		)
		{
			$data      = explode(' ', $string, 2);
			$tag->type = array_shift($data);

			$data = explode('|', array_shift($data));

			foreach ($data as $parameter)
			{
				if (strpos($parameter, ':') === false)
				{
					continue;
				}

				list($key, $val) = explode(':', $parameter, 2);
				$tag->{$key} = $val;
				unset($data[array_search($key, $data)]);
			}

			return $tag;
		}

		$data = explode(':', $string);

		$tag->type = array_shift($data);

		foreach ($data as $parameter)
		{
			if (strpos($parameter, '=') === false)
			{
				continue;
			}

			list($key, $val) = explode('=', $parameter, 2);
			$tag->{$key} = $val;
		}

		if (empty($data))
		{
			return $tag;
		}

		switch (true)
		{
			// Readmore link
			case (strpos($tag->type, 'readmore') === 0):

				$tag->text = array_shift($data);
				if (strpos($tag->text, '|') === false)
				{
					break;
				}

				list($tag->text, $tag->class) = explode('|', $tag->text, 2);
				break;

			// Title / Text
			case (
					$tag->type == 'title'
					|| strpos($tag->type, 'title:') === 0
					|| strpos($tag->type, 'text') === 0)
				|| (strpos($tag->type, 'intro') === 0)
				|| (strpos($tag->type, 'full') === 0
				):

				if (in_array('strip', $data))
				{
					$tag->strip = 1;
					unset($data[array_search('strip', $data)]);
				}
				if (in_array('noimages', $data))
				{
					$tag->noimages = 1;
					unset($data[array_search('noimages', $data)]);
				}

				if (empty($data))
				{
					break;
				}

				$limit = array_shift($data);

				if (strpos($limit, 'word') !== false)
				{
					$tag->words = (int) $limit;
					break;
				}

				$tag->limit = (int) $limit;
				break;


			// Database values
			case (RLText::is_alphanumeric(str_replace(array('-', '_'), '', $tag->type))):
				$tag->format = array_shift($data);
				break;
		}

		return $tag;
	}

	public function processTag($string)
	{
		$tag = $this->getTagValues($string);

		switch (true)
		{
			// Link closing tag
			case ($tag->type == '/link'):
				return '</a>';

			// Total count
			case ($tag->type == 'total' || $tag->type == 'totalcount'):
				return $this->data->total;

			// Counter
			case ($tag->type == 'count' || $tag->type == 'counter'):
				return $this->data->count;

			// Div closing tag
			case ($tag->type == '/div'):
				return '</div>';

			// Div
			case ($tag->type == 'div' || strpos($tag->type, 'div ') === 0):
				return $this->processTagDiv($tag);

			// URL
			case ($tag->type == 'url' || $tag->type == 'nonsefurl'):
				return $this->getArticleUrl();

			// SEF URL
			case ($tag->type == 'sefurl'):
				return JRoute::_($this->getArticleUrl());

			// Link tag
			case ($tag->type == 'link'):
				return $this->processTagLink();

			// Readmore link
			case ($tag->type == 'readmore'):
				return $this->processTagReadmore($tag);

			// Title
			case ($tag->type == 'title'):
				return $this->processTagTitle($tag);

			// Text
			case (
				(strpos($tag->type, 'text') === 0)
				|| (strpos($tag->type, 'intro') === 0)
				|| (strpos($tag->type, 'full') === 0)
			):
				return $this->processTagText($tag);

			// Intro image
			case ($tag->type == 'image-intro'):
				return $this->processTagImageIntro();

			// Fulltext image
			case ($tag->type == 'image-fulltext'):
				return $this->processTagImageFulltext();

			// Layout
			case ($tag->type == 'layout'):
				return $this->processTagLayout($tag);


			// Database values
			case (RLText::is_alphanumeric(str_replace(array('-', '_'), '', $tag->type))):
				return $this->processTagDatabase($tag);

			default:
				return false;
		}
	}

	public function processTagDiv($tag)
	{
		$attributes = array();

		if (isset($tag->class))
		{
			$attributes[] = 'class="' . $tag->class . '"';
		}

		$style = array();

		if (isset($tag->width))
		{
			if (is_numeric($tag->width))
			{
				$tag->width .= 'px';
			}
			$style[] = 'width:' . $tag->width;
		}

		if (isset($tag->height))
		{
			if (is_numeric($tag->height))
			{
				$tag->height .= 'px';
			}
			$style[] = 'height:' . $tag->height;
		}

		if (isset($tag->align))
		{
			$style[] = 'float:' . $tag->align;
		}
		else if (isset($tag->float))
		{
			$style[] = 'float:' . $tag->float;
		}

		if (!empty($style))
		{
			$attributes[] = 'style="' . implode(';', $style) . ';"';
		}

		if (empty($attributes))
		{
			return '<div>';
		}

		return trim('<div ' . implode(' ', $attributes)) . '>';
	}

	public function processTagReadmore($tag)
	{
		if (!$link = $this->getArticleUrl())
		{
			return false;
		}

		// load the content language file
		RLFunctions::loadLanguage('com_content', JPATH_SITE);

		if (!empty($tag->class))
		{
			return '<a class="' . trim($tag->class) . '" href="' . $link . '">' . $this->getReadMoreText($tag) . '</a>';
		}

		$params = JComponentHelper::getParams('com_content');
		$params->set('access-view', true);

		if ($text = $this->getCustomReadMoreText($tag))
		{
			$this->article->alternative_readmore = $text;
			$params->set('show_readmore_title', false);
		}

		return JLayoutHelper::render('joomla.content.readmore', array('item' => $this->article, 'params' => $params, 'link' => $link));
	}

	private function getCustomReadMoreText($tag)
	{
		if (empty($tag->text))
		{
			return '';
		}

		$title = trim($tag->text);
		$text  = JText::sprintf($title, $this->article->title);

		return $text ?: $title;
	}

	public function getReadMoreText($tag)
	{
		if ($text = $this->getCustomReadMoreText($tag))
		{
			return $text;
		}

		switch (true)
		{
			case (isset($this->article->alternative_readmore) && $this->article->alternative_readmore) :
				$text = $this->article->alternative_readmore;
				break;
			case (!$this->config->get('show_readmore_title', 0)) :
				$text = JText::_('COM_CONTENT_READ_MORE_TITLE');
				break;
			default:
				$text = JText::_('COM_CONTENT_READ_MORE');
				break;
		}

		if (!$this->config->get('show_readmore_title', 0))
		{
			return $text;
		}

		return $text . JHtml::_('string.truncate', ($this->article->title), $this->config->get('readmore_limit'));
	}

	public function processTagLink()
	{
		if (!$link = $this->getArticleUrl())
		{
			return false;
		}

		return '<a href="' . $link . '">';
	}

	public function processTagTitle($extra)
	{
		$title = isset($this->article->title) ? $this->article->title : '';

		if (empty($title) || empty($extra))
		{
			return $title;
		}

		return $this->helpers->_('text')->process($title, $extra);
	}

	public function processTagText($tag)
	{
		switch (true)
		{
			case (strpos($tag->type, 'intro') === 0):
				if (!isset($this->article->introtext))
				{
					return false;
				}

				$this->article->text = $this->article->introtext;
				break;

			case (strpos($tag->type, 'full') === 0):
				if (!isset($this->article->fulltext))
				{
					return false;
				}

				$this->article->text = $this->article->fulltext;

				$this->hitArticle();
				break;

			case (strpos($tag->type, 'text') === 0):
				$this->article->text = (isset($this->article->introtext) ? $this->article->introtext : '')
					. (isset($this->article->fulltext) ? $this->article->fulltext : '');

				$this->hitArticle();
				break;
		}

		if ($this->article->text == '')
		{
			return '';
		}

		$string = $this->article->text;

		return $this->helpers->_('text')->process($string, $tag);
	}

	public function hitArticle()
	{
		if (!$this->params->increase_hits_on_text)
		{
			return;
		}

		if (!class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModelLegacy::getInstance('article', 'contentModel');

		if (!method_exists($model, 'hit'))
		{
			return;
		}

		$model->hit($this->article->id);
	}

	public function processTagImageIntro()
	{
		if (!isset($this->article->image_intro))
		{
			return '';
		}

		$class = 'img-intro-' . $this->article->float_intro;

		return $this->getImageHtml($this->article->image_intro, $this->article->image_intro_alt, $this->article->image_intro_caption, $class);
	}

	public function processTagImageFulltext()
	{
		if (!isset($this->article->image_fulltext))
		{
			return '';
		}

		$class = 'img-fulltext-' . $this->article->float_fulltext;

		return $this->getImageHtml($this->article->image_fulltext, $this->article->image_fulltext_alt, $this->article->image_fulltext_caption, $class);
	}

	public function getImageHtml($url, $alt = '', $caption = '', $class = '', $in_div = true)
	{
		$img_class = $caption ? 'caption' : '';
		$caption   = $caption ? ' title="' . htmlspecialchars($caption) . '"' : '';

		if ($in_div)
		{
			return '<div class="' . htmlspecialchars($class) . '"><img' . $caption . ' src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($alt) . '" class="' . $img_class . '"></div>';
		}

		$img_class = trim($img_class . ' ' . htmlspecialchars($class));

		return '<img' . $caption . ' src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($alt) . '" class="' . $img_class . '">';
	}

	public function processTagLayout($tag)
	{
		if (
			JFactory::getApplication()->input->get('option') == 'com_finder'
			&& JFactory::getApplication()->input->get('format') == 'json'
		)
		{
			// Force simple layout for finder indexing, as the setParams causes errors
			return
				'<h2>' . $this->article->title . '</h2>'
				. $this->processTagText('text', $tag);
		}

		list($template, $layout) = $this->getTemplateAndLayout($tag);

		require_once __DIR__ . '/article_view.php';

		$view = new ArticlesAnywhereArticleView;

		$view->setParams($this->article->id, $template, $layout, $this->params);

		return $view->display();
	}


	public function processTagDatabase($tag, $return_empty = false)
	{
		// Get data from data object, even, uneven, first, last
		if (isset($this->data->{$tag->type}) && is_bool($this->data->{$tag->type}))
		{
			return $this->data->{$tag->type} ? 'true' : 'false';
		}

		// Get data from db columns
		if (!isset($this->article->{$tag->type}) || is_array($this->article->{$tag->type}) || is_object($this->article->{$tag->type}))
		{
			return $return_empty ? '' : false;
		}

		$string = $this->article->{$tag->type};

		// Convert string if it is a date
		$string = $this->convertDateToString($string, isset($tag->format) ? $tag->format : '');

		return $string;
	}

	public function convertDateToString($string, $format = '')
	{
		// Check if string could be a date
		if ((strpos($string, '-') == false)
			|| preg_match('#[a-z]#i', $string)
			|| !strtotime($string)
		)
		{
			return $string;
		}

		if (empty($format))
		{
			$format = JText::_('DATE_FORMAT_LC2');
		}

		if (strpos($format, '%') !== false)
		{
			$format = RLText::dateToDateFormat($format);
		}

		return JHtml::_('date', $string, $format);
	}

	public function canEdit()
	{
		$user = JFactory::getUser();
		if ($user->get('guest'))
		{
			return false;
		}

		$userId = $user->get('id');
		$asset  = 'com_content.article.' . $this->article->id;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			return true;
		}

		// Now check if edit.own is available.
		if (empty($userId) || $user->authorise('core.edit.own', $asset))
		{
			return false;
		}

		// Check for a valid user and that they are the owner.
		if ($userId != $this->article->created_by)
		{
			return false;
		}

		return true;
	}

	public function getArticleUrl()
	{
		if (isset($this->article->url))
		{
			return $this->article->url;
		}

		if (!isset($this->article->id))
		{
			return false;
		}

		if (!class_exists('ContentHelperRoute'))
		{
			require_once JPATH_SITE . '/components/com_content/helpers/route.php';
		}

		$this->article->url = ContentHelperRoute::getArticleRoute($this->article->id, $this->article->catid, $this->article->language);

		if (empty($this->article->has_access))
		{
			$this->article->url = $this->getRestrictedUrl($this->article->url);
		}

		return $this->article->url;
	}

	public function getRestrictedUrl($url)
	{
		$menu   = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link   = new JUri(JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));

		$link->setVar('return', base64_encode(JRoute::_($url, false)));

		return (string) $link;
	}

	public function getArticleEditUrl()
	{
		if (isset($this->article->editurl))
		{
			return $this->article->editurl;
		}

		if (!isset($this->article->id))
		{
			return false;
		}

		$this->article->editurl = '';

		if (!$this->canEdit())
		{
			return '';
		}

		$uri                    = JUri::getInstance();
		$this->article->editurl = JRoute::_('index.php?option=com_content&task=article.edit&a_id=' . $this->article->id . '&return=' . base64_encode($uri));

		return $this->article->editurl;
	}


	public function getLayoutFile($tag)
	{
		jimport('joomla.filesystem.path');
		jimport('joomla.filesystem.file');

		$template_layout = (isset($tag->template) ? $tag->template . ':' : '')
			. (isset($tag->layout) ? $tag->layout . ':' : '');

		list($template, $layout) = $this->getTemplateAndLayout($template_layout);

		// Load the language file for the template
		$lang = JFactory::getLanguage();
		$lang->load('tpl_' . $template, JPATH_BASE, null, false, false)
		|| $lang->load('tpl_' . $template, JPATH_THEMES . '/' . $template, null, false, false)
		|| $lang->load('tpl_' . $template, JPATH_BASE, $lang->getDefault(), false, false)
		|| $lang->load('tpl_' . $template, JPATH_THEMES . '/' . $template, $lang->getDefault(), false, false);

		$paths = array(
			JPATH_THEMES . '/' . $template . '/html/com_content/article',
			JPATH_SITE . '/components/com_content/views/article/tmpl',
		);

		$file = JPath::find($paths, $layout . '.php');

		// Check if layout exists
		if (JFile::exists($file))
		{
			return $file;
		}

		// Return default layout
		return JPath::find($paths, 'default.php');
	}

	public function getTemplateAndLayout($data)
	{
		if (!isset($data->template) && isset($data->layout) && strpos($data->layout, ':') !== false)
		{
			list($data->template, $data->layout) = explode(':', $data->layout);
		}

		$layout   = !empty($data->layout) ? $data->layout : (!empty($this->article->article_layout) ? $this->article->article_layout : 'default');
		$template = !empty($data->template) ? $data->template : JFactory::getApplication()->getTemplate();

		if (strpos($layout, ':') !== false)
		{
			list($template, $layout) = explode(':', $layout);
		}

		jimport('joomla.filesystem.folder');

		// Layout is a template, so return default layout
		if (empty($data->template) && JFolder::exists(JPATH_THEMES . '/' . $layout))
		{
			return array($layout, 'default');
		}

		// Value is not a template, so a layout
		return array($template, $layout);
	}
}
