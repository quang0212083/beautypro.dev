<?php
  $count = $helper->count('member-name');
  $col = $helper->get('number_col');
  $fullWidth = $helper->get('full-width');
  $blockImg 				= $helper->get('block-bg');
	$blockImgBg  			= 'background-image: url("'.$blockImg.'"); background-repeat: no-repeat; background-size: cover; background-position: center center;';
	$hasSlide = 0;
	if ($count > $col): $hasSlide = 1;
?>
<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($blockImg): echo 'style="'.$blockImgBg.'"'; endif; ?>>	
	
	<div class="acm-teams style-5">
		<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="team-title container">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
	</h3>

	<?php if($helper->get('block-intro')): ?>
		<p class="container section-intro hidden-xs"><?php echo $helper->get('block-intro'); ?></p>
	<?php endif; ?>	
	<?php endif; ?>	
	  <?php if(!$fullWidth): ?><div class="container"><?php endif; ?>
	
		<div class=" team-list-items <?php if($hasSlide): echo 'carousel slide'; endif; ?>"  data-interval="false" id="team-member-<?php echo $module->id;?>">
			<?php if($hasSlide): ?>
			<a class="left carousel-control" href="#team-member-<?php echo $module->id;?>" data-slide="prev">
		    <i class="fa fa-angle-left"></i>
		  </a>
		  <a class="right carousel-control" href="#team-member-<?php echo $module->id;?>" data-slide="next">
		    <i class="fa fa-angle-right"></i>
		  </a>
			
			<div class="carousel-inner">
			<?php endif; ?>
				<?php
		      for ($i=0; $i < $count; $i++) :
		        if ($i%$col==0 && $hasSlide && $i==0) echo '<div class="row item active">'; 
		      	if ($i%$col==0 && $hasSlide && $i!=0) echo '<div class="row item">'; 
		      	if ($i%$col==0 && !$hasSlide) echo '<div class="row">'; 
		    ?>
				<div class="member-item col-sm-<?php echo (12/$col); ?> col-md-<?php echo (12/$col); ?>">
		      <div class="item-inner">
		    
		        <div class="member-image">
		          <img src="<?php echo $helper->get('member-image', $i); ?>" alt="<?php echo $helper->get('member-name', $i); ?>" />
		        </div>
		        
		        <h4><?php echo $helper->get('member-name', $i); ?> <small class="member-title"> - <?php echo $helper->get('member-position', $i); ?></small></h4>
		        <p class="member-desc"><?php echo $helper->get('member-desc', $i); ?></p>
		        
		          <ul class="social-links">
		          <?php
		            for($j=1; $j <= 5; $j++) :
		              if(trim($helper->get('member-link-icon'.$j, $i)) != ""):
		          ?>
		          <li><a href="<?php echo $helper->get('member-link'.$j, $i); ?>" title=""><i class="<?php echo $helper->get('member-link-icon'.$j, $i); ?>"></i></a></li>
		          <?php
		            endif;
		          endfor;
		          ?>
		          </ul>
		          <span class="view-social-btn">
		          	<i class="fa fa-angle-right"></i>
		          </span>
		        
		      </div>
				</div>
	    

	    <?php if ( ($i%$col==($col-1)) || $i==($count-1) )  echo '</div>'; ?>
			<?php endfor; ?>
		</div>
		<?php endif; ?>

		<?php if($hasSlide): ?></div><?php endif; ?>
	  
		<?php if(!$fullWidth) : ?></div><?php endif; ?>
	</div>
</div>