<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 * @subpackage Category
 *
 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
?>

<?php $this->displayCategories () ?>
<?php if ($this->category->headerdesc) : ?>
<div class="kblock">
	<div class="kheader">
		<span class="ktoggler"><a class="ktoggler close" title="<?php echo JText::_('COM_KUNENA_TOGGLER_COLLAPSE') ?>" rel="frontstats_tbody"></a></span>
		<h2><span><?php echo JText::_('COM_KUNENA_FORUM_HEADER'); ?></span></h2>
	</div>
	<div class="kcontainer" id="frontstats_tbody">
		<div class="kbody">
			<div class="kfheadercontent">
				<?php echo KunenaHtmlParser::parseBBCode ( $this->category->headerdesc ); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (!$this->category->isSection()) : ?>
<div class="klist-actions clearfix">
  <div class="klist-actions-goto">
  	<a id="forumtop"> </a>
  	<a class="kbuttongoto" href="#forumbottom" rel="nofollow"><?php echo $this->getIcon ( 'kforumbottom', JText::_('COM_KUNENA_GEN_GOTOBOTTOM') ) ?></a>
  </div>
  
  <?php $this->displayCategoryActions() ?>
  <div class="klist-pages-all"><?php echo $this->getPagination (7); // odd number here (# - 2) ?></div>
</div>

<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena') ?>" method="post" name="ktopicsform">
	<input type="hidden" name="view" value="topics" />
	<?php echo JHTML::_( 'form.token' ); ?>

<div class="kblock kflat">
	<div class="kheader">
		<?php if (!empty($this->topicActions)) : ?>
		<span class="kcheckbox select-toggle"><input class="kcheckall" type="checkbox" name="toggle" value="" /></span>
		<?php endif; ?>
		<h2><span><?php echo $this->escape($this->headerText); ?></span></h2>
	</div>
	<div class="kcontainer">
		<div class="kbody">
				<table class="kblocktable<?php echo $this->escape($this->category->class_sfx); ?>" id="kflattable">

					<?php if (empty ( $this->topics ) && empty ( $this->subcategories )) : ?>
					<tr class="krow2"><td class="kcol-first"><?php echo JText::_('COM_KUNENA_VIEW_NO_POSTS') ?></td></tr>

					<?php else : ?>
						<?php $this->displayRows (); ?>

					<?php  if ( !empty($this->topicActions) || !empty($this->embedded) ) : ?>
					<!-- Bulk Actions -->
					<tr class="krow1">
						<td colspan="<?php echo empty($this->topicActions) ? 5 : 6 ?>" class="kcol-first krowmoderation">
							<?php if (!empty($this->moreUri)) echo JHtml::_('kunenaforum.link', $this->moreUri, JText::_('COM_KUNENA_MORE'), null, null, 'follow'); ?>
							<?php if (!empty($this->topicActions)) : ?>
							<?php echo JHTML::_('select.genericlist', $this->topicActions, 'task', 'class="inputbox kchecktask" size="1"', 'value', 'text', 0, 'kchecktask'); ?>
							<?php if ($this->actionMove) :
								$options = array (JHTML::_ ( 'select.option', '0', JText::_('COM_KUNENA_BULK_CHOOSE_DESTINATION') ));
								echo JHTML::_('kunenaforum.categorylist', 'target', 0, $options, array(), 'class="inputbox fbs" size="1" disabled="disabled"', 'value', 'text', 0, 'kchecktarget');
								endif;?>
							<input type="submit" name="kcheckgo" class="kbutton" value="<?php echo JText::_('COM_KUNENA_GO') ?>" />
							<?php endif; ?>
						</td>
					</tr>
					<!-- /Bulk Actions -->
					<?php endif; ?>
					<?php endif; ?>
				</table>
		</div>
	</div>
</div>
</form>

<div class="klist-actions-bottom clearfix" >
  <div class="klist-actions-goto">
  	<a id="forumbottom"> </a>
  	<a  class="kbuttongoto" href="#forumtop" rel="nofollow"><?php echo $this->getIcon ( 'kforumtop', JText::_('COM_KUNENA_GEN_GOTOTOP') ) ?></a>
  </div>
  <?php $this->displayCategoryActions() ?>
  <div class="klist-pages-all"><?php echo $this->getPagination (7); // odd number here (# - 2) ?></div>
</div>

<div class="kcontainer klist-bottom">
	<div class="kbody">
		<div class="kmoderatorslist-jump fltrt"><?php $this->displayForumJump (); ?></div>
		<?php if (!empty ( $this->moderators ) ) : ?>
		<div class="klist-moderators">
			<?php
				$modslist = array();
				foreach ( $this->moderators as $moderator ) {
					$modslist[] = $moderator->getLink();
				}
				echo JText::_('COM_KUNENA_MODERATORS') . ': ' . implode(', ', $modslist);
			?>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>
