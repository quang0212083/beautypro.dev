<?php
	if($helper->getRows('data.title') >= $helper->getRows('data.description')) {
		$count = $helper->getRows('data.title');
	} else {
		$count = $helper->getRows('data.description');
	}

  $autoplay = $helper->get('enable-auto-play');
?>

<div class="section-inner <?php echo $helper->get('block-extra-class'); ?>" <?php if($helper->get('block-bg')) : ?>style="background-image: url("<?php echo $helper->get('block-bg'); ?>")"<?php endif; ?> >
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title ">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>
		<?php endif; ?>
	</h3>
	<?php endif; ?>
  
  <div class="acm-slideshow">
    <?php if ($count>1) : ?>
      <hr class="transition-timer-carousel-progress-bar" />
    <?php endif; ?>
  	<div id="acm-slideshow-pro" data-interval="<?php echo $autoplay; ?>" class="carousel slide <?php if($helper->get('enable-fade-effect')): ?>carousel-fade<?php endif; ?>" data-ride="carousel">
  		<div class="style-3">
  			<!-- Indicators -->
        <?php if ($count>1) : ?>
    			<ol class="carousel-indicators">
      			<?php for ($i=0; $i<$count; $i++) : ?>
      			<li data-target="#acm-slideshow-pro" data-slide-to="<?php echo $i ?>" class="<?php if($i<1) echo "active"; ?>"></li>
      			<?php endfor ;?>
    			</ol>
        <?php endif; ?>
  
  			<!-- Wrapper for slides -->
  			<div class="carousel-inner">
  				<?php for ($i=0; $i<$count; $i++) : ?>
  				<div class="item <?php if($i<1) echo "active"; ?>" style="background-image:url('<?php echo $helper->get('data.itembg', $i) ?>'); height:<?php echo $helper->get('data.item-height') ?>">
  					<div class="container">
                <?php if($helper->get('data.title', $i) || $helper->get('data.description', $i)): ?>
                  <div class="row">
                    <div class="col-sm-4">
                    <div class="slide-desc">
                      <h3><?php echo $helper->get('data.title', $i) ?></h3>
                      <p><?php echo $helper->get('data.description', $i) ?></p>
                      <?php if($helper->get('data.btn', $i)): ?>
                        <a href="<?php echo $helper->get('data.btn-url', $i) ?>" class="btn-sm <?php echo $helper->get('data.btn-class', $i) ?>"><?php echo $helper->get('data.btn', $i) ?></a>
                      <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>     
            </div>
  				</div>
  			 	<?php endfor ;?>
  			</div>
  			<?php if ($count>1) : ?>
    			<?php if($helper->get('enable-controls')): ?>
    			 <a data-slide="prev" role="button" href="#acm-slideshow-pro" class="left carousel-control"><i class="fa fa-angle-left"></i></a>
    			 <a data-slide="next" role="button" href="#acm-slideshow-pro" class="right carousel-control"><i class="fa fa-angle-right"></i></a>
    			<?php endif; ?>
        <?php endif; ?>
  		</div>
  	</div>

        
  </div>
</div>

<?php if($autoplay != 'false'): ?>
<script>
(function($){
  $(document).ready(function(){
  var percent = 0, bar = $('.transition-timer-carousel-progress-bar'), crsl = $('#acm-slideshow-pro');
  function progressBarCarousel() {
    bar.css({width:percent+'%'});
   percent = percent +0.5;
    if (percent>100) {
        percent=0;
        crsl.carousel('next');
    }      
  }
  crsl.carousel({
      interval: false,
      pause: true
  }).on('slid.bs.carousel', function () {});var barInterval = setInterval(progressBarCarousel, 30);
  crsl.hover(
      function(){
          clearInterval(barInterval);
      },
      function(){
          barInterval = setInterval(progressBarCarousel, 30);
      })
});

})(jQuery);
</script>

<?php endif; ?>