<?php
/**
 * ------------------------------------------------------------------------
 * JA Mitius Template
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites:  http://www.joomlart.com -  http://www.joomlancers.com
 * This file may not be redistributed in whole or significant part.
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die;

?>

<div class="ja-cp-group">
<?php 
$ref			= 'ja-cp-group.active';	
$target			= '';
if ($show_popup) {
	$target			= 'target="yoxview"';
}


$width 	= $params->get('width','100') > 0 ? $params->get('width','100').'px':'100%';
$style = ' style="width:'.$width.';"';
foreach ($lists as $row) : 
	$groupcls = '';
	
	if ($row->jagroup) {
		$ref	  = 'group_'.$row->id;
		$groupcls = ' group_'.$row->id;
		
		if(isset($row->otherlink)){
			$target 	= 'target="yoxview"';
		}else{
			$target 	= '';
		}
	}
?>
	<div class="ja-cp-item<?php echo $groupcls;?>">
		<div class="item-inner"<?php echo $style;?>>
			<a <?php echo $target;?> href="<?php echo $row->link;?>" title="<?php echo isset($row->subtitle)?htmlspecialchars($row->subtitle):htmlspecialchars($row->title);?>" data-ref="<?php echo $ref;?>">
				<span class="ja-cp-image">
					<?php echo $row->image;?>
				</span>
				<?php if ($show_titles): ?>
				<span class="ja-cp-title">
						<?php echo $row->title;?>
				</span>
				<?php endif;?>
			</a>
			<?php if($show_introtext): ?>
			<span class="ja-cp-intro">
				<?php echo $row->text; ?>
			</span>
			<?php endif;?>
			<?php if (isset($row->otherlink)):
					echo $row->otherlink;
				  endif; ?>
		</div>
	</div>
<?php endforeach; ?>
</div>