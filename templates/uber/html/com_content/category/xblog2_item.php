<?php
/**
 * ------------------------------------------------------------------------
 * Uber Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
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
$info    = $this->item->params->get('info_block_position', 0);
$hasInfo = (($params->get('show_author') && !empty($this->item->author)) or
  ($params->get('show_category')) or
  ($params->get('show_create_date')) or
  $params->get('show_publish_date') or
  ($params->get('show_parent_category'))) ||
  ($params->get('show_modify_date')) ||
  ($params->get('show_hits'));
  $hasCtrl = ($params->get('show_print_icon') ||
  $params->get('show_email_icon') ||
  $canEdit);

?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
	<?php endif; ?>

	<!-- Article -->
	<article class="row">

			<aside class="article-aside col-sm-3 col-md-2 hidden-xs clearfix">
			
			<?php // to do not that elegant would be nice to group the params ?>
			<?php if ($hasInfo) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.block_blog2', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
			<?php endif; ?>
		
			</aside>

		<div class="col-sm-9 col-md-10 blog-detail">

      <!-- Intro image -->
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
	  <?php endif;?>
      <!-- //Intro image -->

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
			<?php if ($hasInfo || $hasCtrl) : ?>
				<aside class="article-aside clearfix">

					<?php // to do not that elegant would be nice to group the params ?>
					<?php if ($hasInfo) : ?>
						<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
					<?php endif; ?>

					<?php if ($hasCtrl) : ?>
						<div class="btn-group pull-right">
							<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-cog"></i> <span class="caret"></span></a>
							<ul class="dropdown-menu">

								<?php if ($params->get('show_print_icon')) : ?>
									<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
								<?php endif; ?>

								<?php if ($params->get('show_email_icon')) : ?>
									<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
								<?php endif; ?>

								<?php if ($canEdit) : ?>
									<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
								<?php endif; ?>
							</ul>
						</div>
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
					<a class="btn btn-default" href="<?php echo $link; ?>">
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
		</div>
	</article>
	<!-- //Article -->

	<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?> 
