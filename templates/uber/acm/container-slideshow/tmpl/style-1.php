<?php
  $items_position = $helper->get('position');
	$autoPlay = $helper->get('enable-autoplay');
  $slideshow_desktop_height = $helper->get('desktop-height');
	$mods = JModuleHelper::getModules($items_position);
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>">	
	<div class="acm-container-slide slideshow-thumbs">
	
	  <!-- BEGIN: CONTAINER SLIDESHOW [DEFAULT STYLE] -->
	  <div id="acm-slideshow-<?php echo $module->id ?>" class="carousel slide carousel-fade" data-ride="carousel" data-interval="<?php echo $autoPlay ;?>">
	
	    <div class="carousel-inner" <?php if ( trim($slideshow_desktop_height) ) : ?>style="height: <?php echo $helper->get('desktop-height')?>px;"<?php endif ?>>
				<?php
				echo $helper->renderModules($items_position, array(
					'style'=>'ACMContainerItems',
					'active'=>0,
					'tag'=>'div',
					'class'=>'item'
				))
				?>
	    </div>
	
	    <ol class="carousel-indicators">
	      <?php
				$i = 0;
				foreach ($mods as $mod) :
					$modparams = new JRegistry($mod->params);
					$modhelper = new ModJAACMHelper($modparams);
					?>
	        <li data-target="#acm-slideshow-<?php echo $module->id ?>" data-slide-to="<?php echo $i; ?>" class="<?php if($i++==0) echo 'active' ?>" style="width: <?php echo (100/count($mods)); ?>%;">
	          <span class="number"><?php echo $i; ?></span>
	          <span class="title <?php if(!$modhelper->get('block-intro')): ?>no-intro<?php endif ;?>">
	            <?php echo $modhelper->get('block-intro'); ?>
	            <strong><?php echo $mod->title ?></strong>
	          </span>
	        </li>
	      <?php endforeach; ?>
	    </ol>
	    
		   <?php if($helper->get('enable-controls')): ?>
			 <a data-slide="prev" role="button" href="#acm-slideshow-<?php echo $module->id ?>" class="left carousel-control"><i class="fa fa-angle-left"></i></a>
			 <a data-slide="next" role="button" href="#acm-slideshow-<?php echo $module->id ?>" class="right carousel-control"><i class="fa fa-angle-right"></i></a>
			 <?php endif; ?>
	  </div>
	  <!-- END: CONTAINER SLIDESHOW [DEFAULT STYLE] -->
	  
	</div>
</div>