<?php
/**
 * @package    Jmb_Tree
 * @author     Sherza & b2z <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('_JEXEC') or die;

if (empty($list))
{
	return;
}

$menuOpts = array();
$linkIdHref = array();

$type = $params->get('type', 'menu');
$firstItem = $params->get('firstitem', '');

if (!empty($firstItem))
{
	$menuOpts[] = JHtml::_('select.option', '', htmlspecialchars($firstItem));
}
else
{
	$menuOpts[] = JHtml::_('select.option', '', JText::_('MOD_JMB_TREE_SELECT_' . strtoupper($type)));
}

foreach ($list as $link)
{
	$menuOpts[] = JHtml::_('select.option', $link->href, $link->levelSeparator . $link->text, 'value', 'text', $disable = ($link->href ? false : true));
	$linkIdHref[$link->id] = $link->href;
}

$selVal = ($type == 'category') ? 'id' : 'Itemid';
$selected = isset($linkIdHref[JFactory::getApplication()->input->getInt($selVal)]) ?
	$linkIdHref[JFactory::getApplication()->input->getInt($selVal)] :
	'';

echo JHtml::_(
	'select.genericlist',
	$menuOpts,
	'norr_dropdown',
	'class = "inputbox"
	onchange = "if(this.value) window.location.href=this.value"',
	'value',
	'text',
	$selected
);

if ($params->get('show_backlink', 1)) : ?>
	<div style="text-align: left; clear: both; font-family: Arial, Helvetica, sans-serif; font-size: 7pt; text-decoration: none">
		<?php echo JText::_('MOD_JMB_TREE_BACKLINK'); ?>
	</div>
<?php endif; ?>
