<?php
  $count      = $helper->getRows('data.event-title');
  $col        = $helper->get('number_col');
  $menuStyle  = $helper->get('event-style');
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
  
  <div class="acm-event">
    <div class="container">
  
  	<div class="style-1 event-items <?php echo $menuStyle; ?>">
  		<?php
        for ($i=0; $i < $count; $i++) :
          if ($i%$col==0) echo '<div class="row">'; 
      ?>
  		<div class="event-item col-sm-6 col-md-<?php echo (12/$col); ?>">
        <div class="item-inner">
          <div class="event-datetime">
            <span class="date"><?php echo $helper->get('data.event-date', $i); ?></span>
            <span class="month"><?php echo $helper->get('data.event-month', $i); ?></span>
          </div>
          <h4 class="event-title">
            <?php if($helper->get('data.event-link', $i)): ?>
            <a href="<?php echo $helper->get('data.event-link', $i); ?>" title="<?php echo $helper->get('data.event-title', $i); ?>">
            <?php endif; ?>
            
            <?php echo $helper->get('data.event-title', $i); ?>
              
            <?php if($helper->get('data.event-link', $i)): ?>
            </a>
            <?php endif; ?>
          </h4>
          <p class="event-description"><?php echo $helper->get('data.event-description', $i); ?></p>
        </div>
  		</div>
      
      <?php if ( ($i%$col==($col-1)) || $i==($count-1) )  echo '</div>'; ?>
  		<?php endfor; ?>
  	</div>
    
  </div>
  </div>
</div>