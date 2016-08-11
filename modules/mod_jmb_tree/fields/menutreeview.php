<?php
/**
 * @package    Jmb_Tree
 * @author     Sherza & Dmitry Rekun <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('JPATH_BASE') or die;

/**
 * Menu tree view's field class.
 *
 * @package  Jmb_Tree
 * @since    1.0
 */
class JFormFieldmenuTreeview extends JFormField
{
	/**
	 * Field name.
	 *
	 * @var  string
	 */
	protected $type = 'menuTreeview';

	/**
	 * Method to get field input.
	 *
	 * @return  mixed  HTML output.
	 */
	protected function getInput()
	{
		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root() . 'modules/mod_jmb_tree/fields/tree.js');
		$doc->addStyleSheet(JURI::root() . 'modules/mod_jmb_tree/fields/tree.css');

		$doc->addScriptDeclaration(
			"jQuery(document).ready(function () {

				var joomla_menu = jQuery('#zmenu_treeboxbox_tree_wrapper'),
				joomla_cat  = jQuery('#zcat_treeboxbox_tree_wrapper');

				jQuery( '#jform_params_type' )
				.change(function () {
					if(this.value=='category'){
						joomla_menu.hide();
						joomla_cat.show();
					}else{
						joomla_menu.show();
						joomla_cat.hide();
					}
				})
				.change();
			});"
		);

		require_once realpath(JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

		$menuitems = MenusHelper::getMenuLinks();

		$groups = array();
		$menus  = array();

		$firstMenutype = '';

		foreach ($menuitems as $k => $menu)
		{
			$groups[$menu->menutype] = array();
			$menus[$menu->menutype]  = $menu;

			if (!$k)
			{
				$firstMenutype = $menu->menutype;
			}

			foreach ($menu->links as $link)
			{
				$groups[$menu->menutype][] = $link;
			}
		}

		if (!isset($this->value[0]))
		{
			$this->value[0] = $firstMenutype;
			$this->value[1] = '';
			$this->value[2] = '';
		}

		$menuOpts = array();

		foreach ($menus as $menu)
		{
			$menuOpts[] = JHtml::_('select.option', $menu->menutype, $menu->title);
		}

		$categoriesSel = JHtml::_(
			'select.genericlist',
			$menuOpts,
			$this->name . '[0]',
			'class = "inputbox" onchange = "zmenu_select_other_menu(this.value)"',
			'value',
			'text',
			$this->value[0]
		);

		ob_start();
		?>
		<div style="clear:both"></div>
		<div id="zmenu_treeboxbox_tree_wrapper">
			<label><?php echo JText::_('MOD_JMB_TREE_FIELDSET_MENUITEMS_LABEL'); ?></label>
			<table class='zmenu_items_table'>
				<tr>
					<td>
						<?php
						echo $categoriesSel;

						echo "</td></tr><tr><td>";

						$checkedElems = explode(',', $this->value[1]);
						?>

						<div id="zmenu_treeboxbox_tree" class="treeboxbox_tree"></div>
						<?php
						foreach ($groups as $menutype => $group)
						{
							?>
							<div id="zmenudhtmlxTree<?php echo $menutype ?>">
								<?php
								$level = 1;

								foreach ($group as $k => $menuitem)
								{
									$level     = $menuitem->level;
									$nextLevel = ($k < (count($group) - 1)) ? $group[$k + 1]->level : 1;

									$img0 = ($nextLevel > $level) ? 'book.gif' : 'book_titel.gif';
									$img1 = ($nextLevel > $level) ? 'books_open.gif' : 'book_titel.gif';
									$img2 = ($nextLevel > $level) ? 'book.gif' : 'book_titel.gif';

									$open    = ($level < 4) ? 1 : 0;
									$checked = (in_array('zmenu' . $menuitem->value, $checkedElems)) ? 'checked="1"' : '';

									$menutext = $menuitem->text . '&nbsp;&nbsp;(' . $menuitem->value . ')';

									while (substr($menutext, 0, 2) == '- ')
									{
										$menutext = substr($menutext, 2);
									}

									echo '<div text="' . $menutext . '" id="zmenu' . $menuitem->value . '" im0="' . $img0 . '" im1="' . $img1 . '" im2="' . $img2 . '" open="' . $open . '" ' . $checked . '>';

									if ($level >= $nextLevel)
									{
										echo str_repeat('</div>', ($level - $nextLevel + 1));
									}
								}

								if ($level > 1)
								{
									echo str_repeat('</div>', ($level - 1));
								}
								?>
							</div>
						<?php
						}
						?>
					</td>
				</tr>
			</table>
			<br/>
			<label><?php echo JText::_('MOD_JMB_TREE_EXCLUDE_MENUS'); ?></label>
			<input type="text" value="<?php echo isset($this->value[3]) ? $this->value[3] : ''; ?>" name="<?php echo $this->name; ?>[3]"/>
		</div>
		<div style="clear:both"></div>
		<script>
			function zmenu_select_other_menu(value) {

				document.getElementById('zmenu_treeboxbox_tree').innerHTML = '';
				var zmenuTree = new dhtmlXTreeObject("zmenu_treeboxbox_tree", "100%", "100%", 'zmenudhtmlxTree' + value); // for script conversion
				zmenuTree.setImagePath("<?php echo JUri::root(); ?>modules/mod_jmb_tree/fields/treeImgs/");
				zmenuTree.enableCheckBoxes(1);
				zmenuTree.enableThreeStateCheckboxes(true);
				zmenuTree.setOnCheckHandler(function (id, state) {
					document.getElementById('zmenudhtmlxTreeCheckboxes').value = zmenuTree.getAllChecked();
					document.getElementById('zmenudhtmlxTreeCheckboxesBranches').value = zmenuTree.getAllCheckedBranches();
				});
				zmenuTree.loadHTML();
			}

			zmenu_select_other_menu('<?php echo $this->value[0]; ?>');
		</script>
		<input type="hidden" value="<?php echo $this->value[1]; ?>" name="<?php echo $this->name; ?>[1]" id="zmenudhtmlxTreeCheckboxes"/>
		<input type="hidden" value="<?php echo $this->value[2]; ?>" name="<?php echo $this->name; ?>[2]" id="zmenudhtmlxTreeCheckboxesBranches"/>
		<?php
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
}
