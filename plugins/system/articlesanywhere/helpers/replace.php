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

class PlgSystemArticlesAnywhereHelperReplace
{
	var $helpers            = array();
	var $params             = null;
	var $aid                = null;
	var $data               = null;
	var $current_article_id = null;
	var $message            = '';

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = PlgSystemArticlesAnywhereHelpers::getInstance();
		$this->params  = $this->helpers->getParams();

		// Tag character start and end
		list($tag_start, $tag_end) = $this->getTagCharacters(true);

		// Break/paragraph start and end tags
		$this->params->breaks_start = RLTags::getRegexSurroundingTagPre(array('p'));
		$this->params->breaks_end   = RLTags::getRegexSurroundingTagPost(array('p'));
		$breaks_start               = $this->params->breaks_start;
		$breaks_end                 = $this->params->breaks_end;
		$spaces                     = RLTags::getRegexSpaces();
		$inside_tag                 = RLTags::getRegexInsideTag();

		$this->params->tags = '(?P<tag>'
			. preg_quote($this->params->article_tag, '#')
			. ')';

		$this->params->regex = '#'
			. '(?P<opening_tags_before_open>' . $breaks_start . ')'
			. $tag_start . $this->params->tags . '(?:' . $spaces . '(?P<id>' . $inside_tag . '))?' . $tag_end
			. '(?P<closing_tags_after_open>' . $breaks_end . ')'
			. '\s*'
			. '(?P<opening_tags_before_content>' . $breaks_start . ')'
			. '(?P<html>.*?)'
			. '(?P<closing_tags_after_content>' . $breaks_end . ')'
			. '\s*'
			. '(?P<opening_tags_before_close>' . $breaks_start . ')'
			. $tag_start . '/\2' . $tag_end
			. '(?P<closing_tags_after_close>' . $breaks_end . ')'
			. '#s';

		$this->aid = JFactory::getUser()->getAuthorisedViewLevels();
	}

	public function _(&$string, $area = 'article', $context = '', $current_article_id = null)
	{
		if (!is_null($current_article_id))
		{
			$this->current_article_id = $current_article_id;
		}

		if (!is_string($string) || $string == '')
		{
			return;
		}

		list($tag_start, $tag_end) = $this->getTagCharacters();

		if (
			strpos($string, $tag_start . $this->params->article_tag) === false
		)
		{
			return;
		}

		$this->message = '';

		// allow in component?
		if (RLProtect::isRestrictedComponent(isset($this->params->components) ? $this->params->components : array(), $area))
		{

			$this->message = JText::_('AA_OUTPUT_REMOVED_NOT_ENABLED');
		}

		$this->helpers->_('protect')->protect($string);

		switch ($area)
		{
			case 'article':
				$replace = $this->prepareStringForArticles($string, $context);
				continue;
			case 'component':
				$replace = $this->prepareStringForComponent($string);
				continue;
			default:
			case 'body':
				$replace = $this->prepareStringForBody($string);
				continue;
		}

		if ($replace)
		{
			$this->process($string);
		}

		RLProtect::unprotect($string);
	}

	public function prepareStringForArticles(&$string, $context = '')
	{
		if (strpos($context, 'com_search.') === 0)
		{
			$limit = explode('.', $context, 2);
			$limit = (int) array_pop($limit);

			$string_check = substr($string, 0, $limit);

			list($tag_start, $tag_end) = $this->getTagCharacters();

			if (
				strpos($string_check, $tag_start . $this->params->article_tag) === false
			)
			{
				return false;
			}
		}


		return true;
	}

	public function prepareStringForComponent(&$string)
	{

		if (RLFunctions::isFeed())
		{
			$s      = '#(<item[^>]*>)#s';
			$string = preg_replace($s, '\1<!-- START: AA_COMPONENT -->', $string);
			$string = str_replace('</item>', '<!-- END: AA_COMPONENT --></item>', $string);
		}

		if (strpos($string, '<!-- START: AA_COMPONENT -->') === false)
		{
			$this->helpers->_('tagarea')->_($string, 'component');
		}

		$components = $this->helpers->_('tagarea')->getByType($string, 'component');

		foreach ($components as $component)
		{
			if (strpos($string, $component['0']) === false)
			{
				continue;
			}

			$this->process($component['1']);
			$string = str_replace($component['0'], $component['1'], $string);
		}

		return false;
	}

	public function prepareStringForBody(&$string)
	{

		return true;
	}

	public function process(&$string)
	{
		list($tag_start, $tag_end) = $this->getTagCharacters();

		list($pre_string, $string, $post_string) = RLText::getContentContainingSearches(
			$string,
			array(
				$tag_start . $this->params->article_tag,
			),
			array(
				$tag_start . '/' . $this->params->article_tag . $tag_end,
			)
		);

		if ($string == '')
		{
			$string = $pre_string . $string . $post_string;

			return;
		}

		$regex = $this->params->regex;

		if (@preg_match($regex . 'u', $string))
		{
			$regex .= 'u';
		}

		if (!preg_match($regex, $string))
		{
			$string = $pre_string . $string . $post_string;

			return;
		}

		$matches = array();
		$break   = 0;

		while (
			$break++ < 10
			&& (
				strpos($string, $this->params->article_tag) !== false
			)
			&& preg_match_all($regex, $string, $matches, PREG_SET_ORDER))
		{
			$this->cleanMatches($matches);

			$items = $this->helpers->_('items')->_($matches, $this->message);

			$this->processMatch($string, $items);

			$matches = array();
		}

		$string = $pre_string . $string . $post_string;
	}

	private function cleanMatches(&$matches)
	{
		foreach ($matches as &$match)
		{
			foreach ($match as $k => $v)
			{
				if ($k && is_numeric($k))
				{
					unset($match[$k]);
				}
			}
		}
	}

	public function processMatch(&$string, $items)
	{
		foreach ($items as $item)
		{
			$this->data = $item;
			$this->processData($string);
		}
	}

	public function processData(&$string)
	{
		$output = $this->getOutputHtml(implode('', $this->data->output));

		$string = RLText::strReplaceOnce($this->data->original_string, $output, $string);
	}

	public function getOutputHtml($html)
	{
		if (empty($this->data->opening_tags_main) || empty($this->data->closing_tags_main))
		{
			return
				$this->data->opening_tags_main
				. $this->fixBrokenHtmlTags($html)
				. $this->data->closing_tags_main;
		}

		return $this->fixBrokenHtmlTags(
			$this->data->opening_tags_main
			. $html
			. $this->data->closing_tags_main
		);
	}

	private function fixBrokenHtmlTags($string)
	{
		$string = RLTags::fixBrokenHtmlTags($string);

		if (!$this->params->place_comments)
		{
			return $string;
		}

		return $this->params->comment_start . $string . $this->params->comment_end;
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
}
