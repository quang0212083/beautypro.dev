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

class PlgSystemArticlesAnywhereHelperText
{
	var $helpers = array();
	var $params  = null;

	public function __construct()
	{
		require_once __DIR__ . '/helpers.php';
		$this->helpers = PlgSystemArticlesAnywhereHelpers::getInstance();
		$this->params  = $this->helpers->getParams();
	}

	public function process($string, $data)
	{
		if (isset($data->strip))
		{
			return $this->strip($string, $data);
		}

		if (isset($data->noimages))
		{
			// remove images
			$string = preg_replace(
				'#(<p><' . 'img\s.*?></p>|<' . 'img\s.*?>)#si',
				' ',
				$string
			);
		}

		if (empty($data->limit) && empty($data->words) && empty($data->paragraphs))
		{
			return $string;
		}

		if (strpos($string, '<') === false || strpos($string, '>') === false)
		{
			// No html tags found. Do a simple limit.
			return $this->limit($string, $data);
		}

		return $this->limitHtml($string, $data);
	}

	private function limitHtml($string, $data)
	{
		if (empty($data->limit) && empty($data->words) && empty($data->paragraphs))
		{
			return $string;
		}

		if (!empty($data->paragraphs))
		{
			return $this->limitHtmlParagraphs($string, (int) $data->paragraphs);
		}

		if (!empty($data->words))
		{
			return $this->limitHtmlWords($string, (int) $data->words);
		}

		return $this->limitHtmlLetters($string, (int) $data->limit);
	}

	private function limitHtmlParagraphs($string, $limit)
	{
		if (!preg_match('#^' . str_repeat('.*?</p>', $limit) . '#s', $string, $match))
		{
			return $string;
		}

		return $match['0'];
	}

	private function limitHtmlWords($string, $limit)
	{
		return $this->limitHtmlByType($string, $limit, 'words');
	}

	private function limitHtmlLetters($string, $limit)
	{
		return $this->limitHtmlByType($string, $limit);
	}

	private function limitHtmlByType($string, $limit, $type = 'letters')
	{
		if (strlen($string) < $limit)
		{
			return $string;
		}

		// store pagenavcounter & pagenav (exclude from count)
		$pagenavcounter = '';
		if (strpos($string, 'pagenavcounter') !== false)
		{
			if (preg_match('#<' . 'div class="pagenavcounter">.*?</div>#si', $string, $pagenavcounter))
			{
				$pagenavcounter = $pagenavcounter['0'];
				$string         = str_replace($pagenavcounter, '<!-- ARTA_PAGENAVCOUNTER -->', $string);
			}
		}

		$pagenavbar = '';
		if (strpos($string, 'pagenavbar') !== false)
		{
			if (preg_match('#<' . 'div class="pagenavbar">(<div>.*?</div>)*</div>#si', $string, $pagenavbar))
			{
				$pagenavbar = $pagenavbar['0'];
				$string     = str_replace($pagenavbar, '<!-- ARTA_PAGENAV -->', $string);
			}
		}

		// add explode helper strings around tags
		$explode_str = '<!-- ARTA_TAG -->';
		$string      = preg_replace(
			'#(<\/?[a-z][a-z0-9]?.*?>|<!--.*?-->)#si',
			$explode_str . '\1' . $explode_str,
			$string
		);

		$str_array = explode($explode_str, $string);

		$string    = array();
		$tags      = array();
		$count     = 0;
		$is_script = 0;

		foreach ($str_array as $i => $str_part)
		{
			if (fmod($i, 2))
			{
				// is tag
				$string[] = $str_part;
				preg_match(
					'#^<(\/?([a-z][a-z0-9]*))#si',
					$str_part,
					$tag
				);
				if (!empty($tag))
				{
					if ($tag['1'] == 'script')
					{
						$is_script = 1;
					}

					if (!$is_script
						// only if tag is not a single html tag
						&& (strpos($str_part, '/>') === false)
						// just in case single html tag has no closing character
						&& !in_array($tag['2'], array('area', 'br', 'hr', 'img', 'input', 'link', 'param'))
					)
					{
						$tags[] = $tag['1'];
					}

					if ($tag['1'] == '/script')
					{
						$is_script = 0;
					}
				}

				continue;
			}

			if ($is_script)
			{
				$string[] = $str_part;
				continue;
			}

			if ($type == 'words')
			{
				// word limit
				if ($str_part)
				{
					$words      = explode(' ', trim($str_part));
					$word_count = count($words);

					if ($limit < ($count + $word_count))
					{
						$words_part = array();
						$word_count = 0;
						foreach ($words as $word)
						{
							if ($word)
							{
								$word_count++;
							}

							if ($limit < ($count + $word_count))
							{
								break;
							}

							$words_part[] = $word;
						}

						$string_part = rtrim(implode(' ', $words_part));

						$string[] = $this->addEllipsis($string_part);
						break;
					}

					$count += $word_count;
				}

				$string[] = $str_part;

				continue;
			}

			// character limit
			if ($limit < ($count + strlen($str_part)))
			{
				// strpart has to be cut off
				$maxlen = $limit - $count;

				if ($maxlen < 3)
				{
					$string_part = '';
					if (preg_match('#[^a-z0-9]$#si', $str_part))
					{
						$string_part .= ' ';
					}

					$string[] = $this->addEllipsis($string_part);

					break;
				}

				$string[] = $this->shorten($str_part, $limit);

				break;
			}

			$count += strlen($str_part);
			$string[] = $str_part;
		}

		// revers sort open tags
		krsort($tags);
		$tags  = array_values($tags);
		$count = count($tags);

		for ($i = 0; $i < 3; $i++)
		{
			foreach ($tags as $ti => $tag)
			{
				if ($tag['0'] != '/')
				{
					continue;
				}

				for ($oi = $ti + 1; $oi < $count; $oi++)
				{
					if (!isset($tags[$oi]))
					{
						unset($tags[$ti]);
						break;
					}

					$opentag = $tags[$oi];

					if ($opentag == $tag)
					{
						break;
					}

					if ('/' . $opentag == $tag)
					{
						unset($tags[$ti]);
						unset($tags[$oi]);
						break;
					}
				}
			}
		}

		foreach ($tags as $tag)
		{
			// add closing tag to end of string
			if ($tag['0'] != '/')
			{
				$string[] = '</' . $tag . '>';
			}
		}
		$string = implode('', $string);

		if ($pagenavcounter)
		{
			$string = str_replace('<!-- ARTA_PAGENAVCOUNTER -->', $pagenavcounter, $string);
		}

		if ($pagenavbar)
		{
			$string = str_replace('<!-- ARTA_PAGENAV -->', $pagenavbar, $string);
		}

		// Fix links in pagination to point to the included article instead of the main article
		// This doesn't seem to work correctly and causes issues with other links in the article
		// So commented out until I find a better solution
		/*if ($art && isset($art->id) && $art->id) {
			$string = str_replace('view=article&amp;id=' . $art->id, 'view=article&amp;id=' . $this->article->id, $string);
		}*/

		return $string;
	}

	private function strip($string, $data)
	{
		// remove pagenavcounter
		$string = preg_replace('#(<' . 'div class="pagenavcounter">.*?</div>)#si', ' ', $string);
		// remove pagenavbar
		$string = preg_replace('#(<' . 'div class="pagenavbar">(<div>.*?</div>)*</div>)#si', ' ', $string);
		// remove inline scripts
		$string = preg_replace('#(<' . 'script[^a-z0-9].*?</script>)#si', ' ', $string);
		$string = preg_replace('#(<' . 'noscript[^a-z0-9].*?</noscript>)#si', ' ', $string);
		// remove inline styles
		$string = preg_replace('#(<' . 'style[^a-z0-9].*?</style>)#si', ' ', $string);
		// remove other tags
		$string = preg_replace('#(<' . '/?[a-z][a-z0-9]?.*?>)#si', ' ', $string);
		// remove double whitespace
		$string = trim(preg_replace('#(\s)[ ]+#s', '\1', $string));

		return $this->limit($string, $data);
	}

	private function limit($string, $data)
	{
		if (empty($data->limit) && empty($data->words))
		{
			return $string;
		}

		if (!empty($data->words))
		{
			return $this->limitWords($string, (int) $data->words);
		}

		return $this->limitLetters($string, (int) $data->limit);
	}

	private function limitWords($string, $limit)
	{
		$orig_len = strlen($string);

		// word limit
		$string = trim(
			preg_replace(
				'#^(([^\s]+\s*){' . (int) $limit . '}).*$#s',
				'\1',
				$string
			)
		);

		if (strlen($string) < $orig_len)
		{
			$string = $this->addEllipsis($string);
		}

		return $string;
	}

	private function limitLetters($string, $limit)
	{
		$orig_len = strlen($string);

		// character limit
		if ($limit >= $orig_len)
		{
			return $string;
		}

		return $this->shorten($string, $limit);
	}

	private function shorten($string, $limit)
	{
		if (strlen($string) <= $limit)
		{
			return $string;
		}

		$string = $this->rtrim($string, $limit);

		return $this->addEllipsis($string);
	}

	private function rtrim($string, $limit)
	{
		if (function_exists('mb_substr'))
		{
			return rtrim(mb_substr($string, 0, ($limit - 3), 'utf-8'));
		}

		return rtrim(substr($string, 0, ($limit - 3)));
	}

	private function addEllipsis($string)
	{
		if (!$this->params->use_ellipsis)
		{
			return $string;
		}

		if (preg_match('#[^a-z0-9]$#si', $string))
		{
			$string .= ' ';
		}

		return $string . '...';
	}
}
