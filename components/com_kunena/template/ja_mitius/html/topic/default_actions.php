<?php
/**
 * Kunena Component
 * @package Kunena.Template.Blue_Eagle
 * @subpackage Topic
 *
 * @copyright (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

// Goto up / down
$locations = array('top','bottom');
if (!isset($this->location)) $this->location = 0;
$goto = '<a name="forum'.$locations[$this->location].'"></a>';
$this->location ^= 1;
$goto .= '<a class="kbuttongoto" href="#forum'.$locations[$this->location].'" rel="nofollow">' . $this->getIcon ( 'kforum'.$locations[$this->location], JText::_('COM_KUNENA_GEN_GOTO'.strtoupper($locations[$this->location] )) ) . '</a>';
?>
<div class="klist-actions clearfix">

	<div class="klist-actions-goto">
		<?php echo $goto ?>
	</div>
	
	<div class="klist-actions-forum">
	<?php if ($this->topicButtons->get('reply') || $this->topicButtons->get('subscribe') || $this->topicButtons->get('favorite') ) : ?>
		<div class="kmessage-buttons-row">
		<?php echo $this->topicButtons->get('reply') ?>
		<?php echo $this->topicButtons->get('subscribe') ?>
		<?php echo $this->topicButtons->get('favorite') ?>
		</div>
	<?php endif ?>
		<?php if ($this->topicButtons->get('undelete') || $this->topicButtons->get('delete') || $this->topicButtons->get('moderate') || $this->topicButtons->get('sticky') || $this->topicButtons->get('lock')) : ?>
		<div class="kmessage-buttons-row">
		<?php echo $this->topicButtons->get('undelete') ?>
		<?php echo $this->topicButtons->get('delete') ?>
		<?php echo $this->topicButtons->get('moderate') ?>
		<?php echo $this->topicButtons->get('sticky') ?>
		<?php echo $this->topicButtons->get('lock') ?>
		</div>
	<?php endif ?>
	</div>
	
	<?php if ($this->topicButtons->get('flat') || $this->topicButtons->get('threaded') || $this->topicButtons->get('indented')) : ?>
	<div class="klist-actions-forum">
		<div class="kmessage-buttons-row">
		<?php echo $this->topicButtons->get('flat') ?>
		<?php echo $this->topicButtons->get('threaded') ?>
		<?php echo $this->topicButtons->get('indented') ?>
		</div>
	</div>
	<?php endif ?>

	<div class="klist-pages-all">
		<?php echo $this->pagination; ?>
	</div>

</div>
