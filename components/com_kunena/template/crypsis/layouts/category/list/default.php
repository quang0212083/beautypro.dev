<?php
/**
 * Kunena Component
 * @package     Kunena.Template.Crypsis
 * @subpackage  Layout.Category
 *
 * @copyright   (C) 2008 - 2015 Kunena Team. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        http://www.kunena.org
 **/
defined('_JEXEC') or die;
?>
<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena&view=category') ?>" method="post" name="kcategoryform" id="kcategoryform">
	<?php echo JHtml::_('form.token'); ?>

	<h3>
		<?php echo $this->escape($this->headerText); ?>
		<span class="badge badge-info"><?php echo (int) $this->pagination->total; ?></span>

		<?php if (!empty($this->actions)) : ?>
			<div class="input-append pull-right">
				<?php echo JHtml::_('select.genericlist', $this->actions, 'task', 'size="1"', 'value', 'text', 0,
					'kchecktask'); ?>
				<input type="submit" name="kcheckgo" class="btn" value="<?php echo JText::_('COM_KUNENA_GO') ?>" />
			</div>
		<?php endif; ?>

		<?php if (!empty($this->embedded)) : ?>
		<div class="pull-right">
			<?php echo $this->subLayout('Widget/Pagination/List')
				->set('pagination', $this->pagination)
				->set('display', true); ?>
		</div>
		<?php endif; ?>
	</h3>

	<table class="table table-striped table-bordered">

		<?php if (!empty($this->actions)) : ?>
			<thead>
				<tr>
					<th colspan="2"></th>
					<th class="center">
						<input class="kcheckall" type="checkbox" name="toggle" value="" />
					</th>
				</tr>
			</thead>
		<?php endif; ?>

		<?php if (!empty($this->actions)) : ?>
			<tfoot>
				<tr>
					<td colspan="<?php echo empty($this->actions) ? 3 : 4 ?>">
						<?php // FIXME: Add category actions (unsubscribe selected) ?>
					</td>
				</tr>
			</tfoot>
		<?php endif; ?>

		<?php if (empty($this->categories)) : ?>
			<tbody>
				<tr>
					<td>
						<?php echo JText::_('COM_KUNENA_CATEGORY_SUBSCRIPTIONS_NONE') ?>
					</td>
				</tr>
			</tbody>
		<?php else : ?>
			<tbody>
				<?php
				foreach ($this->categories as $this->category)
				{
					echo $this->subLayout('Category/List/Row')
						->set('category', $this->category)
						->set('config', $this->config)
						->set('checkbox', !empty($this->actions));
				}
				?>
			</tbody>
		<?php endif; ?>

	</table>
</form>
