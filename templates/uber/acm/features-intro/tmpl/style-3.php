<?php 
	$featuresImg 				= $helper->get('block-bg');
	$featuresBackground  = 'background-image: url("'.$featuresImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($featuresImg): echo 'style="'.$featuresBackground.'"'; endif; ?>>
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
	<div class="acm-features style-3 <?php echo $helper->get('features-style'); ?>">
		<div class="container">
			<?php $count = $helper->getRows('data.title'); ?>
			<?php $column = $helper->get('columns'); ?>
			<?php 
				for ($i=0; $i<$count; $i++) : 
				if ($i%$column==0) echo '<div class="row">'; 
			?>
			
				<div class="features-item col-sm-<?php echo 12/$column ?>">
					
					<?php if($helper->get('data.font-icon', $i)) : ?>
						<div class="font-icon">
							<i class="<?php echo $helper->get('data.font-icon', $i) ; ?>"></i>
						</div>
					<?php endif ; ?>
	
					<?php if($helper->get('data.img-icon', $i)) : ?>
						<div class="img-icon">
							<img src="<?php echo $helper->get('data.img-icon', $i) ?>" alt="" />
						</div>
					<?php endif ; ?>
					
					<?php if($helper->get('data.title',$i)) : ?>
						<h3><?php echo $helper->get('data.title', $i) ?></h3>
					<?php endif ; ?>
					
					<?php if($helper->get('data.description', $i)) : ?>
						<p><?php echo $helper->get('data.description', $i) ?></p>
					<?php endif ; ?>
				</div>
				<?php if ( ($i%$column==($column-1)) || $i==($count-1) )  echo '</div>'; ?>
			<?php endfor ?>
		</div>
	</div>
</div>