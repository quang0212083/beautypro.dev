<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );?>

<div class='pc-categories-wrapper row-fluid clearfix'>
	<div id="pc-categories" class='pc-categories span12 clearfix' data-columns >

		<?php foreach($categories as $c) : ?>
			<?php $instance = PaycartProductcategory::getInstance($c->productcategory_id, $c);?>
	
			<div class="pc-category-outer">
			<?php $media = $instance->getCoverMedia();?>
			<?php $url   = PaycartRoute::_('index.php?option=com_paycart&view=productcategory&task=display&productcategory_id='.$c->productcategory_id);?>
				<a href="<?php echo $url?>" title="<?php echo $instance->getTitle();?>">
					<div class='pc-category blurground' style="background-image: url('<?php echo $media['squared'];?>'); background-size: contain;">
						<div class="pc-category-inner blurground vertical-center-wrapper" >
							<div class="pc-category-content">
								<h2 class="vertical-center-content pc-ellipsis"><?php echo $instance->getTitle();?></h2>
							</div>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach;?>

	</div>
</div>
<?php

