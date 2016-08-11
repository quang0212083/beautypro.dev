<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
defined('_JEXEC') or die();

/**
 * List of populated Variables
 * $displayData : object of stdclass containing data to show applied filters
 */

$categoryTree = $filters->core->categoryTree;
$selectedCategoryId = $filters->core->selectedCategoryId;

//recursive function to display tree structure of categories
$displayTree = function($tree,$displayData,$searchWord) use (&$displayTree) {
	$selectedCategory    = $displayData->core->selectedCategoryId;
	$categories		     = $displayData->core->categories;
		
    if (!is_array($tree)) {
    	$title   = ($selectedCategory == $tree)?'<span class="muted">'.$categories[$tree]->title.'</span>':$categories[$tree]->title;
    	$q       = ($searchWord)?'?q='.urlencode($searchWord):'';
    	$link    = PaycartRoute::_("index.php?option=com_paycart&view=productcategory&task=display&productcategory_id=".$tree).$q;
    	$html    = str_repeat('<span class="gi">&nbsp;&nbsp;&nbsp;&nbsp;</span>', ($categories[$tree]->level - 1)<0?0:($categories[$tree]->level - 1)).
    			   '<span class="pc-cursor-pointer" data-pc-category="click" data-pc-categorylink="'.$link.'" data-pc-categoryid="'.$tree.'">'.
    			  $title.'</span><br/>';
						
        echo $html;
        return;
    }

    foreach($tree as $k => $value) {
       $displayTree($k,$displayData,$searchWord);
        if(is_array($value)){
            $displayTree($value,$displayData,$searchWord);
        }
    }
};
		
?>

<div class="accordion" id="accordion-id-category">
   <div class="accordion-group">
	 	<div class="accordion-heading">
	 		<h2>
	 			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-id-category" href="#accordion-body-id-category">		 				
	 				<span>&nbsp;<?php echo JText::_("COM_PAYCART_BROWSE");?></span>
	 			</a>
	 		</h2>
	 	</div>
	 	<!-- use class "in" for keeping it open -->
	 	 <div class="accordion-body collapse in" id="accordion-body-id-category">
	 	 	<div class="accordion-inner pc-product-filter-body">
	 	 		<?php 
					// Display Link to All category
					$q    = ($searchWord)?'?q='.urlencode($searchWord):'';
					$link = PaycartRoute::_("index.php?option=com_paycart&view=productcategory&task=display").$q;
					echo '<span class="pc-cursor-pointer" data-pc-category="click" data-pc-categorylink="'.$link.'" data-pc-categoryid="0">'.JText::_('COM_PAYCART_ALL').'</span><br/>';
					echo $displayTree($categoryTree,$filters,$searchWord);
				?>		
				<input type="hidden" name="filters[core][category]" value="<?php echo $selectedCategoryId;?>"/>
		 	</div>
	 	 </div>
    </div>
</div>
<?php 