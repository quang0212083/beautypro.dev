<?php
  $count = $helper->getRows('data.testimonial-text'); 
?>

<div class="<?php echo $helper->get('block-extra-class'); ?>">

  <div class="acm-testimonials style-8 <?php if($fullWidth): ?>full-width <?php endif; ?>">
  
      <!-- BEGIN: TESTIMONIALS STYLE 8 -->
    	<div id="acm-testimonials-<?php echo $module->id ?>" class="testimonial-content carousel carousel-fade slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <?php for ($i=0; $i<$count; $i++) : ?>
            <li data-target="#acm-testimonials-<?php echo $module->id ?>" data-slide-to="<?php echo $i ?>" class="<?php if($i<1) echo "active"; ?>"></li>
          <?php endfor ?>
        </ol>

        <div class="carousel-inner">
         <?php for ($i=0; $i<$count; $i++) : ?>
          <div class="item <?php if($i<1) echo "active"; ?>">
          
          <?php if ($helper->get ('data.testimonial-text', $i)) : ?>
             <p class="testimonial-text"><?php echo $helper->get ('data.testimonial-text', $i) ?></p>
          <?php endif; ?>
  
          <div class="author-info">
            <?php if ($helper->get ('data.author-img', $i)) : ?>
              <span class="author-image"><img src="<?php echo $helper->get ('data.author-img', $i) ?>" alt="Author Avatar" /></span>
            <?php endif; ?>
  
            <?php if ( ($helper->get ('data.author-name', $i)) || ($helper->get ('data.author-title', $i)) ) : ?>
              <div class="author-info-text">
                <span class="author-name"><?php echo $helper->get ('data.author-name', $i) ?> </span>
                
                <?php if ($helper->get ('data.author-title', $i)) : ?>
                  <span class="author-title"><?php echo $helper->get ('data.author-title', $i) ?></span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
  
          </div>
         <?php endfor ?>
        </div>
  
      </div>
  
  </div>
</div>