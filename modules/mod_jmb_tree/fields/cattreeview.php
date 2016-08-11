<?php
/**
 * @package    Jmb_Tree
 * @author     Sherza & Dmitry Rekun <support@norrnext.com>
 * @copyright  Copyright (C) 2012 - 2016 NorrNext. All rights reserved.
 * @license    GNU General Public License version 3 or later; see license.txt
 */

defined('JPATH_BASE') or die;

/**
 * Categories tree view's field class.
 *
 * @package  Jmb_Tree
 * @since    1.0
 */
class JFormFieldcatTreeview extends JFormField
{
	/**
	 * Field name.
	 *
	 * @var  string
	 */
	protected $type = 'catTreeview';

	/**
	 * Method to recurse the list of categories.
	 *
	 * @param   array    $categories    Categories.
	 * @param   integer  &$level        Hierarchy level.
	 * @param   array    $checkedElems  Checked elements.
	 *
	 * @return  void
	 */
	public static function getCatListRecurse($categories, &$level, $checkedElems)
	{
		$level++;

		foreach ($categories as $cat)
		{
			$childrenCategories = $cat->getChildren();

			$img0 = ($childrenCategories) ? 'book.gif' : 'book_titel.gif';
			$img1 = ($childrenCategories) ? 'books_open.gif' : 'book_titel.gif';
			$img2 = ($childrenCategories) ? 'book.gif' : 'book_titel.gif';

			$open    = ($level < 4) ? 1 : 0;
			$checked = (in_array('zcat' . $cat->id, $checkedElems)) ? 'checked="1"' : '';

			echo '<div text="' . $cat->title . '" id="zcat' . $cat->id . '" im0="' . $img0 . '" im1="' . $img1 . '" im2="' . $img2 . '" open="' . $open . '" ' . $checked . '>';

			if ($childrenCategories)
			{
				self::getCatListRecurse($childrenCategories, $level, $checkedElems);
			}

			echo '</div>';
		}

		$level--;
	}

	/**
	 * Method to get field input.
	 *
	 * @return  mixed  HTML output.
	 */
	protected function getInput()
	{
		$doc = JFactory::getDocument();
		$doc->addScript(JUri::root() . 'modules/mod_jmb_tree/fields/tree.js');
		$doc->addStyleSheet(JUri::root() . 'modules/mod_jmb_tree/fields/tree.css');

		$doc->addScriptDeclaration(
			"jQuery(document).ready(function () {

				var joomla_menu = jQuery('#zmenu_treeboxbox_tree'),
				joomla_cat  = jQuery('#zcat_treeboxbox_tree_wrapper');

				jQuery( '#jform_params_type' )
				.change(function () {
					if(this.value=='menu'){
						joomla_menu.show();
						joomla_cat.hide();
					}else{
						joomla_menu.hide();
						joomla_cat.show();
					}
				})
				.change();
			});"
		);

		$cat        = ' ';
		$cats       = JCategories::getInstance('content', array($cat));
		$catRoot    = $cats->get($cat);
		$categories = $catRoot->getChildren();
		$level      = 0;

		if (!isset($this->value[0]))
		{
			$this->value[0] = '';
			$this->value[1] = '';
		}

		$checkedElems = explode(',', $this->value[0]);

		ob_start();
		?>

		<div style="clear:both"></div>
		<div id="zcat_treeboxbox_tree_wrapper">
			<label><?php echo JText::_('MOD_JMB_TREE_FIELDSET_CATEGORIES_LABEL'); ?></label>

			<div id="zcat_treeboxbox_tree" class="treeboxbox_tree"></div>
			<div id="zcatdhtmlxTree">
				<?php self::getCatListRecurse($categories, $level, $checkedElems); ?>
			</div><br/>
			<label><?php echo JText::_('MOD_JMB_TREE_EXCLUDE_CATEGORIES'); ?></label>
			<input type="text" value="<?php echo isset($this->value[2]) ? $this->value[2] : ''; ?>" name="<?php echo $this->name; ?>[2]"/>

		</div>
		<div style="clear:both"></div>

		<script>
			var zcatTree = new dhtmlXTreeObject("zcat_treeboxbox_tree", "100%", "100%", 'zcatdhtmlxTree'); // for script conversion
			zcatTree.setImagePath("<?php echo JUri::root(); ?>modules/mod_jmb_tree/fields/treeImgs/");
			zcatTree.enableCheckBoxes(1);
			zcatTree.enableThreeStateCheckboxes(true);
			zcatTree.setOnCheckHandler(function (id, state) {
				document.getElementById('zcatdhtmlxTreeCheckboxes').value = zcatTree.getAllChecked();
				document.getElementById('zcatdhtmlxTreeCheckboxesBranches').value = zcatTree.getAllCheckedBranches();
			});
			zcatTree.loadHTML();
		</script>
		<input type="hidden" value="<?php echo $this->value[0]; ?>" name="<?php echo $this->name; ?>[0]" id="zcatdhtmlxTreeCheckboxes"/>
		<input type="hidden" value="<?php echo $this->value[1]; ?>" name="<?php echo $this->name; ?>[1]" id="zcatdhtmlxTreeCheckboxesBranches"/>
		<?php
		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}
}
