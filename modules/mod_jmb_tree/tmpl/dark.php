<?php
/**
 * @package    Jmb_Tree
 * @author     Artem Valchuk <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('_JEXEC') or die;

if ($params->get('include_css', 1))
{
	JHtml::stylesheet('mod_jmb_tree/dark/style.css', false, true);
}

$menuOpts   = array();
$linkIdHref = array();
$type       = $params->get('type', 'menu');
$selVal     = ($type == 'menu') ? 'Itemid' : 'id';

echo '<nav role="navigation" class="jmb-tree jmb-tree-dark"><ul role="menubar" class="menu">';

foreach ($list as $k => $link)
{
	if ($type == 'menu')
	{
		// Note. It is important to remove spaces between elements.
		$class = $link->anchor_css ? 'class="' . $link->anchor_css . '" ' : '';
		$title = $link->anchor_title ? 'title="' . $link->anchor_title . '" ' : '';

		if ($link->menu_image && $params->get('menu_img', 1))
		{
			$link->params->get('menu_text', 1) ?
				$linktype = '<img src="' . $link->menu_image . '" alt="' . $link->text . '" /><span class="image-title">' . $link->text . '</span> ' :
				$linktype = '<img src="' . $link->menu_image . '" alt="' . $link->text . '" />';
		}
		else
		{
			$linktype = $link->text;
		}
	}

	// Dropdown item class
	$checkMenuItemLevel = !empty($list[$k + 1]->level) ? $list[$k + 1]->level : false;

	if ($link->level >= $checkMenuItemLevel)
	{
		$dropdown = '';
		$area     = 'role="menuitem"';
	}
	else
	{
		$dropdown = ' jmb-tree-dropdown';
		$area     = 'role="menuitem" tabindex="0" aria-haspopup="true"';
	}

	if (isset($list[$k - 1]->level) && ($link->level > $list[$k - 1]->level))
	{
		echo '<div class="jmb-tree-dropdown-menu"><ul role="menu" aria-hidden="true">';
	}

	if (isset($list[$k - 1]->level) && ($link->level >= 2))
	{
		$area = 'role="menuitem" tabindex="-1"';
	}

	// Parent item active class
	$jMenu      = JFactory::getApplication()->getMenu();
	$activeItem = $jMenu->getActive();

	if ($activeItem)
	{
		$parentItem = $jMenu->getItem($activeItem->parent_id);
	}

	$activeParent = '';

	if (!empty($parentItem))
	{
		if ($parentItem->id == $link->id || $parentItem->parent_id == $link->id)
		{
			$activeParent = ' active';
		}
	}

	$active = (JFactory::getApplication()->input->getInt($selVal) == $link->id) ? ' active' : '';

	echo '<li ' . $area . ' class="jmb-tree-level' . $link->level . '' . $dropdown . '' . $active . '' . $activeParent . '">';

	if ($type == 'menu')
	{
		switch ($link->browserNav)
		{
			default:
			case 0:
				?>
				<a <?php echo $class; ?>href="<?php echo $link->href; ?>" <?php echo $title; ?><?php echo $link->nofollow; ?>><?php echo $linktype; ?></a>
				<?php
				break;

			case 1:
			case 2:
				?>
				<a <?php echo $class; ?>href="<?php echo $link->href; ?>"
				   <?php echo $title; ?>target="_blank" <?php echo $link->nofollow; ?>><?php echo $linktype; ?></a>
				<?php
				break;
		}
	}
	else
	{
		echo '<a href="' . $link->href . '">' . $link->text . '</a>';
	}

	if (isset($list[$k + 1]->level) && ($link->level >= $list[$k + 1]->level))
	{
		echo str_repeat('</li></ul></div>', ($link->level - $list[$k + 1]->level)) . '</li>';
	}

	if (!isset($list[$k + 1]->level))
	{
		echo str_repeat('</li></ul></nav>', $link->level);
	}
}

if ($params->get('show_backlink', 1)) : ?>
	<div style="text-align: left; clear: both; font-family: Arial, Helvetica, sans-serif; font-size: 7pt; text-decoration: none">
		<?php echo JText::_('MOD_JMB_TREE_BACKLINK'); ?>
	</div>
<?php endif; ?>
