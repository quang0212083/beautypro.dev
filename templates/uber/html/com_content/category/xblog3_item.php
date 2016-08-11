<?php
 /**
 *------------------------------------------------------------------------------
 * @package Purity III Template - JoomlArt
 * @version 1.0 Feb 1, 2014
 * @author JoomlArt http://www.joomlart.com
 * @copyright Copyright (c) 2004 - 2014 JoomlArt.com
 * @license GNU General Public License version 2 or later;
 *------------------------------------------------------------------------------
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
if(version_compare(JVERSION, '3.0', 'lt')){
	JHtml::_('behavior.tooltip');
}
JHtml::_('behavior.framework');

// Create a shortcut for params.
$params  = & $this->item->params;
$images  = json_decode($this->item->images);
$canEdit = $this->item->params->get('access-edit');
$info    = $params->get('info_block_position', 2);
$icons = ($params->get('show_print_icon') ||
	$params->get('show_email_icon') ||
	$canEdit);
$aInfo1 = ($params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
$aInfo2 = ($params->get('show_create_date') || $params->get('show_modify_date') || $params->get('show_hits'));
$topInfo = ($aInfo1 && $info != 1) || ($aInfo2 && $info == 0);
$botInfo = ($aInfo1 && $info == 1) || ($aInfo2 && $info != 0);

?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
	<?php endif; ?>

	<!-- Article -->
	<article>

		<!-- Intro image -->
		<div class="col-sm-4">
			<?php if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
				<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
				<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image article-image article-image-intro">
					<img
						<?php if ($images->image_intro_caption):
							echo 'class="caption"' . ' title="' . htmlspecialchars($images->image_intro_caption) . '"';
						endif; ?>
						src="<?php echo htmlspecialchars($images->image_intro); ?>"
						alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
				</div>
			<?php endif; ?>
		</div>

		<div class="col-sm-8">
			<?php if ($params->get('show_title')) : ?>
				<header class="article-header clearfix">
					<h2 class="article-title">
						<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
							<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>"> <?php echo $this->escape($this->item->title); ?></a>
						<?php else : ?>
							<?php echo $this->escape($this->item->title); ?>
						<?php endif; ?>
					</h2>
				</header>
			<?php endif; ?>



			<!-- Aside -->
			<?php if ($topInfo || $icons) : ?>
				<aside class="article-aside clearfix">

					<dl class="article-info">
						<dt class="article-info-term">
							<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
						</dt>
						<?php if ($params->get('show_author') && !empty($this->item->author)) : ?>
							<?php echo JLayoutHelper::render('joomla.content.info_block.author', array('item' => $this->item, 'params' => $params)); ?>
						<?php endif; ?>

						<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
							<?php echo JLayoutHelper::render('joomla.content.info_block.parent_category', array('item' => $this->item, 'params' => $params)); ?>
						<?php endif; ?>

						<?php if ($params->get('show_category')) : ?>
							<?php echo JLayoutHelper::render('joomla.content.info_block.category', array('item' => $this->item, 'params' => $params)); ?>
						<?php endif; ?>

						<?php if ($info == 0) : ?>
							<?php if ($params->get('show_modify_date')) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.modify_date', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>
							<?php if ($params->get('show_create_date')) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.create_date', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>

							<?php if ($params->get('show_hits')) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.hits', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>
						<?php endif; ?>
					</dl>
      
		      <?php if ($icons): ?>
		      <?php echo JLayoutHelper::render('joomla.content.icons', array('item' => $this->item, 'params' => $params)); ?>
		      <?php endif; ?>
				</aside>
			<?php endif; ?>
			<!-- //Aside -->

			<section class="article-intro clearfix">
				<?php if (!$params->get('show_intro')) : ?>
					<?php echo $this->item->event->afterDisplayTitle; ?>
				<?php endif; ?>

				<?php echo $this->item->event->beforeDisplayContent; ?>

				<?php echo $this->item->introtext; ?>
			</section>
			
			<!-- footer -->
			<?php if ($botInfo) : ?>
				<footer class="article-footer clearfix">
	
					<dl class="article-info">
						<dt class="article-info-term">
							<?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?>
						</dt>
						<?php if ($info == 1) : ?>
							<?php if ($params->get('show_author') && !empty($this->item->author)) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.author', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>
							<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.parent_category', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>
							<?php if ($params->get('show_category')) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.category', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>
							<?php if ($params->get('show_publish_date')) : ?>
								<?php echo JLayoutHelper::render('joomla.content.info_block.publish_date', array('item' => $this->item, 'params' => $params)); ?>
							<?php endif; ?>
						<?php endif; ?>
	
						<?php if ($params->get('show_create_date')) : ?>
							<?php echo JLayoutHelper::render('joomla.content.info_block.create_date', array('item' => $this->item, 'params' => $params)); ?>
						<?php endif; ?>
						<?php if ($params->get('show_modify_date')) : ?>
							<?php echo JLayoutHelper::render('joomla.content.info_block.modify_date', array('item' => $this->item, 'params' => $params)); ?>
						<?php endif; ?>
						<?php if ($params->get('show_hits')) : ?>
							<?php echo JLayoutHelper::render('joomla.content.info_block.hits', array('item' => $this->item, 'params' => $params)); ?>
						<?php endif; ?>
					</dl>
	
				</footer>
			<?php endif; ?>
			<!-- //footer -->

			<?php if ($params->get('show_readmore') && $this->item->readmore) :
				if ($params->get('access-view')) :
					$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
				else :
					$menu      = JFactory::getApplication()->getMenu();
					$active    = $menu->getActive();
					$itemId    = $active->id;
					$link1     = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
					$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
					$link      = new JURI($link1);
					$link->setVar('return', base64_encode($returnURL));
				endif;
				?>
				<section class="readmore">
					<a class="btn btn-primary" href="<?php echo $link; ?>">
						<span>
						<?php if (!$params->get('access-view')) :
							echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
						elseif ($readmore = $this->item->alternative_readmore) :
							echo $readmore;
							if ($params->get('show_readmore_title', 0) != 0) :
								echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
							endif;
						elseif ($params->get('show_readmore_title', 0) == 0) :
							echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
						else :
							echo JText::_('COM_CONTENT_READ_MORE');
							echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
						endif; ?>
						</span>
					</a>
				</section>
			<?php endif; ?>
			
			<?php echo $this->item->event->afterDisplayContent; ?> 
		</div>
	</article>
	<!-- //Article -->

	<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>


