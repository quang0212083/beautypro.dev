<?php
	if($helper->getRows('data.title') >= $helper->getRows('data.description')) {
		$count = $helper->getRows('data.title');
	} else {
		$count = $helper->getRows('data.description');
	}
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($helper->get('block-bg')) : ?>style="background-image: url("<?php echo $helper->get('block-bg'); ?>")"<?php endif; ?> >
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
	</h3>
	<?php endif; ?>
  
  <div class="acm-accordion">
  	<div id="acm-accordion-<?php echo $module->id; ?>">
  		<div class="style-1">

  			<!-- Wrapper for slides -->
          <div class="panel-group" id="accordion-<?php echo $module->id; ?>">
    				<?php for ($i=0; $i<$count; $i++) : ?>
    				<div class="panel panel-default">
    					<?php if($helper->get('data.title', $i)): ?>
              <div class="panel-heading" id="accordion-item-<?php echo $i; ?>">
                <a class=" <?php if($i==0) echo "active"; ?> clearfix" data-toggle="collapse" data-parent="#accordion-<?php echo $module->id; ?>" href="#accordion-body-<?php echo $i; ?>"

                >
                  <?php echo $helper->get('data.title', $i) ?>
                  <span class="heading-icon pull-right">
                    <i class="fa icon <?php if($i==0) { echo 'fa-minus'; } else { echo 'fa-plus'; } ?>" style="display:block;"></i>
                  </span>

                </a>
              </div>
    					<?php endif; ?>
              <div id="accordion-body-<?php echo $i; ?>" class="panel-collapse collapse <?php if($i<1) echo "in"; ?>">
                <div class="panel-body">
                  <?php if($helper->get('data.image', $i)): ?>
                    <img src="<?php echo $helper->get('data.image', $i) ?>" alt="<?php echo $helper->get('data.title', $i) ?>" class="media-object pull-left">
                  <?php endif; ?>
                  <?php if($helper->get('data.description', $i)): ?>
                    <p><?php echo $helper->get('data.description', $i) ?></p>
                      <?php if($helper->get('data.action-url', $i)): ?>
                        <a href="<?php echo $helper->get('data.action-url', $i) ?>" class="btn btn-primary btn-sm">Read More</a>
                      <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>
    				</div>
    			 	<?php endfor ;?>
          </div>

        </div>
  	</div>
  </div>
</div>
