<?php 
	$featuresImg 				= $helper->get('block-bg');
	$aligment	= $helper->get('aligment');
	$featuresBackground  = 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($featuresImg): echo 'style="'.$featuresBackground.'"'; endif; ?>>
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
	</h3>
	<?php endif; ?>
	<div class="acm-features <?php echo $helper->get('features-style'); ?> style-11">
		<div class="container">
			<?php if($helper->get('features-description')) : ?>
				<h2 class="features-description"><?php echo $helper->get('features-description'); ?></h2>
			<?php endif ; ?>
			
			<div class="row">
			<?php $count = $helper->getRows('data.title'); ?>
			<?php $column = 12/($helper->get('columns')); ?>
			<?php for ($i=0; $i<$count; $i++) : ?>
			
				<div class="features-item col-sm-<?php echo $column ?> text-<?php echo $aligment ?>" style="
				<?php if($helper->get('data.font-icon', $i)) : ?>min-height:300px; padding-top:60px;<?php endif; ?>;
				
				background-color:<?php echo $helper->get('data.blockbgcolor', $i) ; ?>; 
				color:<?php echo $helper->get('data.textcolor', $i) ; ?> ;
				">
					
					<?php if($helper->get('data.font-icon', $i)) : ?>
						<div class="font-icon" style="color:<?php echo $helper->get('data.textcolor', $i) ; ?>;">
							<i class="<?php echo $helper->get('data.font-icon', $i) ; ?>" style="background-color: <?php echo $helper->get('data.ico-bg', $i) ?>"></i>
						</div>
					<?php endif ; ?>
					
					<?php if($helper->get('data.title', $i)) : ?>
						<h3 style="color:<?php echo $helper->get('data.textcolor', $i) ; ?>"><?php echo $helper->get('data.title', $i) ?></h3>
					<?php endif ; ?>
					
					<?php if($helper->get('data.description', $i)) : ?>
						<p><?php echo $helper->get('data.description', $i) ?></p>
					<?php endif ; ?>
				</div>
			<?php endfor ?>
			</div>
		</div>
		
	</div>
</div>