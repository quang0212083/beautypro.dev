<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 * @subpackage Topics
 *
 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

$this->displayAnnouncement ();
?>
<!-- Module position: kunena_announcement -->
<?php $this->displayModulePosition ( 'kunena_announcement' ) ?>
<div class="klist-actions clearfix">
		<div class="klist-actions-info-all">
			<strong><?php echo intval($this->total) ?></strong>
			<?php echo JText::_('COM_KUNENA_TOPICS')?>
		</div>

		<div class="klist-times-all">
			<form action="<?php echo $this->escape(JURI::getInstance()->toString());?>" id="timeselect" name="timeselect" method="post" target="_self">
			<?php $this->displayTimeFilter('sel', 'class="inputboxusl" onchange="this.form.submit()" size="1"') ?>
			</form>
		</div>

		<div class="klist-jump-all visible-desktop"><?php $this->displayForumJump () ?></div>

		<div class="klist-pages-all"><?php echo $this->getPagination ( 5 ); ?></div>
</div>

<?php $this->displayTemplateFile('topics', 'default', 'embed'); ?>

<div class="klist-actions clearfix">
		<div class="klist-actions-info-all">
			<strong><?php echo intval($this->total) ?></strong>
			<?php echo JText::_('COM_KUNENA_TOPICS')?>
		</div>
		<div class="klist-pages-all"><?php echo $this->getPagination ( 5 ); ?></div>
</div>

<?php
$this->displayWhoIsOnline ();
$this->displayStatistics ();
?>
