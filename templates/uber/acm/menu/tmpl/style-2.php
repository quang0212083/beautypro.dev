<?php
  $count      = $helper->getRows('data.dish-name');
  $col        = $helper->get('number_col');
  $fullWidth  = $helper->get('full-width');
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
  
  <div class="acm-menu <?php if($fullWidth): ?>full-width<?php endif; ?>">
    <?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
  
  	<div class="style-2 menu-items">
  		<?php
        for ($i=0; $i < $count; $i++) :
          if ($i%$col==0) echo '<div class="row">'; 
      ?>
  		<div class="dish-item col-sm-6 col-md-<?php echo (12/$col); ?> <?php if($helper->get('data.dish-image', $i)) echo 'has-image'; ?>">
        <div class="item-inner" style="background-image: url(<?php echo $helper->get('data.dish-image', $i); ?>);">
          <h4 class="dish-name"><span><?php echo $helper->get('data.dish-name', $i); ?></span></h4>
          <span class="dish-price"><?php echo $helper->get('data.dish-price', $i); ?></span>
          <p class="dish-description"><?php echo $helper->get('data.dish-description', $i); ?></p>
        </div>
  		</div>
      
      <?php if ( ($i%$col==($col-1)) || $i==($count-1) )  echo '</div>'; ?>
  		<?php endfor; ?>
  	</div>
    
  <?php if(!$fullWidth) : ?></div><?php endif; ?>
  </div>
</div>