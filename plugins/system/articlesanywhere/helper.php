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

require_once JPATH_LIBRARIES . '/regularlabs/helpers/functions.php';
require_once JPATH_LIBRARIES . '/regularlabs/helpers/protect.php';
require_once JPATH_LIBRARIES . '/regularlabs/helpers/tags.php';
require_once JPATH_LIBRARIES . '/regularlabs/helpers/text.php';

RLFunctions::loadLanguage('plg_system_articlesanywhere');

/**
 * Plugin that places articles
 */
class PlgSystemArticlesAnywhereHelper
{
	var $helpers = array();

	public function __construct(&$params)
	{
		$this->params = $params;

		$this->params->comment_start = '<!-- START: Articles Anywhere -->';
		$this->params->comment_end   = '<!-- END: Articles Anywhere -->';
		$this->params->message_start = '<!--  Articles Anywhere Message: ';
		$this->params->message_end   = ' -->';

		$this->params->article_tag = trim($this->params->article_tag);

		$this->params->message = '';

		$this->params->option = JFactory::getApplication()->input->get('option');

		require_once __DIR__ . '/helpers/helpers.php';
		$this->helpers = PlgSystemArticlesAnywhereHelpers::getInstance($params);
	}

	public function onContentPrepare(&$article, $context, $params)
	{

		$area    = isset($article->created_by) ? 'articles' : 'other';
		$context = (($params instanceof JRegistry) && $params->get('rl_search')) ? 'com_search.' . $params->get('readmore_limit') : $context;

		RLHelper::processArticle($article, $context, $this, 'replaceItems', array($area, $context, &$article));
	}

	public function onAfterDispatch()
	{
		// only in html
		if (JFactory::getDocument()->getType() !== 'html' && !RLFunctions::isFeed())
		{
			return;
		}

		if (!$buffer = RLFunctions::getComponentBuffer())
		{
			return;
		}

		$this->helpers->_('replace')->_($buffer, 'component');

		JFactory::getDocument()->setBuffer($buffer, 'component');
	}

	public function onAfterRender()
	{
		// only in html and feeds
		if (JFactory::getDocument()->getType() !== 'html' && !RLFunctions::isFeed())
		{
			return;
		}

		$html = JFactory::getApplication()->getBody();
		if ($html == '')
		{
			return;
		}

		if (JFactory::getDocument()->getType() != 'html')
		{
			$this->helpers->_('replace')->_($html, 'body');
			$this->helpers->_('clean')->cleanLeftoverJunk($html);

			JFactory::getApplication()->setBody($html);

			return;
		}

		// only do stuff in body
		list($pre, $body, $post) = RLText::getBody($html);
		$this->helpers->_('replace')->_($body, 'body');
		$html = $pre . $body . $post;

		$this->helpers->_('clean')->cleanLeftoverJunk($html);

		// replace head with newly generated head
		// this is necessary because the plugins might have added scripts/styles to the head
		$this->helpers->_('head')->update($html);

		JFactory::getApplication()->setBody($html);
	}

	public function replaceItems(&$string, $area = 'articles', $context = '', &$article)
	{
		$article_id = isset($article->id) ? $article->id : null;

		$this->helpers->_('replace')->_($string, $area, $context, $article_id);
	}
}
