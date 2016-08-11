<?php
$pricingStyle = $helper->get('pricing-style');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($helper->get('block-bg')) : ?>style="background-image: url("<?php echo $helper->get('block-bg'); ?>")"<?php endif; ?> >
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
		<?php if($helper->get('block-intro')): ?>
			<p class="container-sm section-intro hidden-xs"><?php echo $helper->get('block-intro'); ?></p>
		<?php endif; ?>	
	</h3>
	<?php endif; ?>
  
  <div class="acm-pricing">
  	<div class="container pricing-table style-2">
  		<div class="row">
  			<?php
  			$count = $helper->getCols('data');
  			$features_count = $helper->getRows('data');
  			if (!$count || !$features_count) {
  				$count = $helper->count('pricing-col-name');
  				$features_count = $helper->count('pricing-row-name');
  			}
  			?>
  
  			<?php for ($col = 0; $col < $count; $col++) :
  				$featured = $helper->get('data.pricing-col-featured', $col);
  				?>
  				<div
  					class="col col-sm-<?php echo 12 / ($count); ?> <?php if ($featured): ?> col-featured shadow <?php endif ?> no-padding">
  					<div class="col-header text-center">
  						<h2><?php echo $helper->get('data.pricing-col-name', $col) ?></h2>
  
  						<p><?php echo $helper->get('data.pricing-col-caption', $col) ?></p>
  					</div>
  					<div class="col-body">
  						<ul>
  							<?php for ($r = 0; $r < $features_count; $r++) :
  								$feature = $helper->getCell('data', $r, 0);
  								$value = $helper->getCell('data', $r, $col + 1);
  								$type = $value[0];
  								if (!$feature) {
  									$feature = $helper->get('pricing-row-name', $r);
  									$tmp = $helper->get('pricing-row-supportfor', $r);
  									$value = ($tmp & pow(2, $col)) ? 'b1' : 'b0'; // b1: yes, b0: no
  									$type = 'b'; // boolean
  								}
  								?>
  							<?php if ($type == 't'): ?>
  								<li class="row<?php echo($r % 2); ?>"><?php echo substr($value, 1) ?></li>
  							<?php elseif ($value == 'b1'): ?>
  								<li class="row<?php echo($r % 2); ?>"><?php echo $feature ?></li>
  							<?php
  							else: ?>
  								<li class="row<?php echo($r % 2); ?> no"><?php echo $feature ?></li>
  							<?php endif ?>
  
  							<?php endfor; ?>
  							<li class="row0"><span
  									class="big-number"><?php echo $helper->get('data.pricing-col-price', $col) ?></span></li>
  						</ul>
  					</div>
  					<div class="col-footer text-center">
  						<a class="btn btn-lg<?php if ($featured): ?> btn-success <?php else: ?> btn-default <?php endif ?>"
  							 title="<?php echo $helper->get('data.pricing-col-button', $col); ?>"
  							 href="<?php echo $helper->get('data.pricing-col-buttonlink', $col); ?>"><?php echo $helper->get('data.pricing-col-button', $col); ?></a>
  					</div>
  				</div>
  			<?php endfor; ?>
  
  		</div>
  	</div>
  </div>
</div>