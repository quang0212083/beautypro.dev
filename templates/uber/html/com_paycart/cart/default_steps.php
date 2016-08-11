<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

$counter = 1;
// before the current task, all tak will be completed so "text-succes" class and cursor pointer class
// and onlick event is allowed
$class = 'pc-checkout-cursor-pointer text-success';
$click_event = true;
?>
<div class="checkout-steps text-center">
	<div class="row">
	<?php foreach($available_steps as $name => $step ):?>
			
			<?php if($name == $active_task):?>
				<?php // for current task, no class?>
				<?php $class = "";?>
				<?php // with and after current task no click event should be fired ?>
				<?php $click_event = false;?>
			<?php endif;?>		
			
			<div class="col-xs-3">
				<div class="pc-checkout-step <?php echo $step->class." ".$class; ?>" <?php echo $click_event ? 'onClick="'.$step->onclick.'"' : '';?>>
					<div class="fa-stack ">
				    	<i class="fa fa-circle fa-stack-2x"></i>
				    	<i class="fa <?php echo $step->icon; ?> fa-stack-1x fa-inverse"></i>
				    </div>
				    
			    	<div class="step-info">
			    		<span class="step-counter"><?php echo $counter++; ?></span>
			    		<span class='hidden-xs'> <?php echo JText::_($step->title)?></span>
			    	</div>
			    </div>
	    	</div>
    	
    		<?php // after the cureent task all tak will be muted , so add muted class?>
	    	<?php if($name == $active_task):?>
				<?php $class = "text-muted";?>
			<?php endif;?>		
	<?php endforeach;?>
</div>
</div>
<?php

