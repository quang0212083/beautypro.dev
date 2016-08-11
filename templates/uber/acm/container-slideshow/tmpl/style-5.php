<?php
  $items_position = $helper->get('position');
  $mods = JModuleHelper::getModules($items_position);
  $count = count($mods); 

  $doc = JFactory::getDocument();
  $doc->addScript (T3_TEMPLATE_URL . '/acm/container-slideshow/js/landing/jquery.easing.js');
  $doc->addScript (T3_TEMPLATE_URL.'/acm/container-slideshow/js/landing/jquery.mousewheel.min.js');
  $doc->addScript (T3_TEMPLATE_URL.'/acm/container-slideshow/js/landing/jquery.singular.js');
?>

<div class="style-1 block-landing">
  <?php $i = 0; foreach ($mods as $mod) : ?>
  <div class="block-landing-item <?php if (isset($animation)) echo $animation; ?>" id="block-landing-item-<?php echo $mod->id; ?>">
	<?php
      echo JModuleHelper::renderModule($mod);
  ?>

  <?php if (($i==$count-1) && $helper->get('enable-leave-slide')): ?>
    <a class="arrow-bottom block-landing-next" href="#"><i class="fa fa-long-arrow-down"></i></a>
  <?php endif; ?>
  </div>
  <?php $i++; endforeach; ?>

  <?php if($helper->get('enable-leave-slide')): ?>
  <div id="sec-last" class="block-landing-item"><a href="" class="block-landing-prev"></a></div>
  <?php endif; ?>

  <div class="mod-nav">
      <ul class="block-landing-nav">
          <?php $i = 0; foreach ($mods as $mod) : ?>
          <li class="mod-nav-item"><a href="#block-landing-item-<?php echo $mod->id; ?>"><?php echo $mod->id; ?></a></li>
          <?php $i++; endforeach; ?>
          <?php if($helper->get('enable-leave-slide')): ?>
          <li class="mod-nav-item sec-last"><a href="#sec-last">Options</a></li>
          <?php endif; ?>
      </ul>
      <!-- /mod-nav -->
  </div>
</div>

<script>
    (function($){
		var iOS = parseFloat(('' + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent) || [0,''])[1]).replace('undefined', '3_2').replace('_', '.').replace('_', '')) || false;
		if(!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || iOS)) {
			var slide = $('.block-landing');
			  var parent = slide.parents('#t3-section');
			  slide.singular({
				  section: '.block-landing-item',
				  nav: '.block-landing-nav',
				  prev: '.block-landing-prev',
				  next: '.block-landing-next',
				  navActiveClass: 'block-landing-active',
				  scrollSpeed: 600,
				  mousewheel: true,
				  easing: 'easeInOutQuart',
				  scrollEnd: function(elem) {
					  if ($('.block-landing-nav .block-landing-active').hasClass('sec-last')) {
						  $('#sec-last').animate({
							  height: 0,
							  opacity: 0
						  }, 400, function() {
							  parent.addClass('slide-hide');
							  $('html, body').css('overflow', 'unset');
						  });
					  }
				  }
			  });

			  $(window).on('mousewheel', function(e, delta) {
				  if(delta < -0.8) {

				  }
				  if(delta > 0.8) {
					  if ($(this).scrollTop() == 0) {
						  parent.removeClass('slide-hide');
						  $('.block-landing-prev', '#sec-last').click();
						  $('html, body').css('overflow', 'hidden');
					  }
				  }
			  });
		} else {
			$('.home-landing').css('overflow','unset');
			$('.block-landing').css('position','static');
			$('.block-landing > div').css('height','auto!important');
			$('.block-landing-nav').css('display','none');
		}
    })(jQuery);
</script>