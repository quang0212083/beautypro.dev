<?php
/**
* @title		Joombig Menu Tree
* @website		http://www.joombig.com
* @copyright	Copyright (C) 2013 joombig.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<link rel="stylesheet" id="treeview-css" href="modules/mod_joombig_menu_tree/tmpl/css/jquery.treeview.css" type="text/css" media="all">
<script type="text/javascript" src="modules/mod_joombig_menu_tree/tmpl/css/jquery-noconflict.js"></script>
<script>
	var call_enable_jQuery;
	call_enable_jQuery = <?php echo $enable_jQuery;?>;
</script>
<script type="text/javascript" src="modules/mod_joombig_menu_tree/tmpl/css/jquery.treeview.js"></script>
<style>
	.joombig_tree_menu_root{
		width: <?php echo $width_module;?>;
		margin: <?php echo $margin;?>;
	}
</style>
	
<div class="joombig_tree_menu_root">
<?php if($show_title_directory == 1){?>
	<img src="modules/mod_joombig_menu_tree/tmpl/css/images/base.png">
	<span style="position: absolute; "><?php echo $title_directory;?></span>
<?php }?>
	<div class="joombig_tree_menu_browser">
		<ul id="browser" class="joombig_tree_menu<?php echo $class_sfx;?> filetree"<?php
			$tag = '';
			if ($params->get('tag_id')!=NULL) {
				$tag = $params->get('tag_id').'';
				echo ' id="'.$tag.'"';
			}
		?>>

		<?php
		foreach ($list as $i => &$item) :
			$class = 'item-'.$item->id;
			if ($item->id == $active_id) {
				$class .= ' current';
			}

			if (in_array($item->id, $path)) {
				$class .= ' active';
			}
			elseif ($item->type == 'alias') {
				$aliasToId = $item->params->get('aliasoptions');
				if (count($path) > 0 && $aliasToId == $path[count($path)-1]) {
					$class .= ' active';
				}
				elseif (in_array($aliasToId, $path)) {
					$class .= ' alias-parent-active';
				}
			}

			if ($item->deeper) {
				$class .= ' deeper';
			}

			if ($item->parent) {
				$class .= ' parent';
			}

			$class .= ' closed';
			if ($item->deeper)
			{
				$class .= ' folder';
			}
			else
			{
				$class .= ' file';
			}
			
			if (!empty($class)) {
				$class = ' class="'.trim($class) .'"';
			}

			echo '<li'.$class.'>';

			// Render the menu item.
			switch ($item->type) :
				case 'separator':
				case 'url':
				case 'component':
					require JModuleHelper::getLayoutPath('mod_joombig_menu_tree', 'default_'.$item->type);
					break;

				default:
					require JModuleHelper::getLayoutPath('mod_joombig_menu_tree', 'default_url');
					break;
			endswitch;

			// The next item is deeper.
			if ($item->deeper) {
				echo '<ul>';
			}
			// The next item is shallower.
			elseif ($item->shallower) {
				echo '</li>';
				echo str_repeat('</ul></li>', $item->level_diff);
			}
			// The next item is on the same level.
			else {
				echo '</li>';
			}
		endforeach;
		?></ul>
	</div>
</div>
