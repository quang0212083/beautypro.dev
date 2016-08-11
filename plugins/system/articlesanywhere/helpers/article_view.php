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

if (!class_exists('ContentViewArticle'))
{
	require_once JPATH_SITE . '/components/com_content/views/article/view.html.php';
}

class ArticlesAnywhereArticleView extends ContentViewArticle
{
	public function setParams($id, $template, $layout, $params)
	{
		if (!class_exists('ContentModelArticle'))
		{
			require_once JPATH_SITE . '/components/com_content/models/article.php';
		}

		$model = JModelLegacy::getInstance('article', 'contentModel');

		$this->plugin_params = $params;

		$this->item  = $model->getItem($id);
		$this->state = $model->getState();

		$this->setLayout($template . ':' . $layout);

		$this->item->article_layout = $template . ':' . $layout;

		$this->_addPath('template', JPATH_SITE . '/components/com_content/views/article/tmpl');
		$this->_addPath('template', JPATH_SITE . '/templates/' . $template . '/html/com_content/article');
	}

	public function display($tpl = null)
	{
		$app  = JFactory::getApplication();
		$user = JFactory::getUser();

		$this->print = $app->input->getBool('print');
		$this->user  = $user;

		// Create a shortcut for $item.
		$item            = $this->item;
		$item->tagLayout = new JLayoutFile('joomla.content.tags');

		// Add router helpers.
		$item->slug        = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
		$item->catslug     = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
		$item->parent_slug = $item->parent_alias ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;

		// No link for ROOT category
		if ($item->parent_alias == 'root')
		{
			$item->parent_slug = null;
		}

		// TODO: Change based on shownoauth
		if (!class_exists('ContentHelperRoute'))
		{
			require_once JPATH_SITE . '/components/com_content/helpers/route.php';
		}

		$item->readmore_link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));

		// Merge article params. If this is single-article view, menu params override article params
		// Otherwise, article params override menu item params
		$this->params = $this->state->get('params');

		if ($item->params->get('show_intro', '1') == '1')
		{
			$item->text = $item->introtext . ' ' . $item->fulltext;
		}
		elseif ($item->fulltext)
		{
			$item->text = $item->fulltext;
		}
		else
		{
			$item->text = $item->introtext;
		}

		$item->tags = new JHelperTags;
		$item->tags->getItemTags('com_content.article', $this->item->id);

		$item->event                       = new stdClass;
		$item->event->beforeDisplayContent = '';
		$item->event->afterDisplayContent  = '';

		if ($this->plugin_params->force_content_triggers)
		{
			// Process the content plugins.
			$dispatcher = JEventDispatcher::getInstance();
			JPluginHelper::importPlugin('content');

			$dispatcher->trigger('onContentPrepare', array('com_content.article', &$item, &$item->params, 0));

			$results                        = $dispatcher->trigger('onContentAfterTitle', array('com_content.article', &$item, &$item->params, 0));
			$item->event->afterDisplayTitle = trim(implode("\n", $results));

			$results                           = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.article', &$item, &$item->params, 0));
			$item->event->beforeDisplayContent = trim(implode("\n", $results));

			$results                          = $dispatcher->trigger('onContentAfterDisplay', array('com_content.article', &$item, &$item->params, 0));
			$item->event->afterDisplayContent = trim(implode("\n", $results));
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

		return $this->loadTemplate($tpl);
	}
}
