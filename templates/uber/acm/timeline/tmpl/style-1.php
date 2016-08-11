<?php
	if($helper->getRows('data.title') >= $helper->getRows('data.description')) {
		$count = $helper->getRows('data.title');
	} else {
		$count = $helper->getRows('data.description');
	}
  $defaultWidth = $helper->get('block-size');

// sort by date.
$array=array();
for ($i=0; $i<$count; $i++) {
	$array[$i] = $helper->get('data.date', $i);
}
arsort($array);
$j=0;
?>

<div class="section-inner section-timeline <?php echo $helper->get('block-extra-class'); ?>" <?php if($helper->get('block-bg')) : ?>style="background-image: url("<?php echo $helper->get('block-bg'); ?>")"<?php endif; ?> >
	<?php if($module->showtitle || $helper->get('block-intro')): ?>
	<h3 class="section-title text-center">
		<?php if($module->showtitle): ?>
			<span><?php echo $module->title ?></span>

		<?php endif; ?>
    <?php if($helper->get('block-intro')): ?>
      <br><small class="container-sm section-intro hidden-xs"><?php echo $helper->get('block-intro'); ?></small>
    <?php endif; ?> 
	</h3>
	<?php endif; ?>
  
  <div class="acm-timeline style-1" id="acm-timeline-<?php echo $module->id; ?>">
    <div class="timeline-list"  style="width: <?php if($defaultWidth): echo $defaultWidth; else: echo '800' ; endif; ?>px">
      <?php foreach ($array AS $i => $v) :
        $icon = $helper->get('data.icon', $i);
        $rowcount = (($j%2) == 0);

        $pullClass = '';
        $alignClass = '';
        if($rowcount) {
          $pullClass = "pull-left";
          $alignClass = 'text-left';
        } else {
          $pullClass = "pull-right";
          $alignClass = 'text-right';
        }
		$j++;
      ?>
        <div class="item-row">
          <div class="timeline-item">
            <div class="item-icon">
              <i class="fa <?php if($icon): echo $icon; else: echo 'fa-clock-o' ; endif; ?>"></i>
            </div>

            <?php if($helper->get('data.date', $i)): ?><div class="item-date"><?php echo $helper->get('data.date', $i) ?></div><?php endif; ?>
            <div class="item-content">

              <div class="item-body media <?php echo $alignClass; ?>">
                <?php if($helper->get('data.image', $i)): ?>
                  <img src="<?php echo $helper->get('data.image', $i) ?>" class="media-object <?php echo $pullClass; ?>" alt="<?php echo $helper->get('data.title', $i) ?>">
                <?php endif; ?>
                <div class="media-body">
                  <?php if($helper->get('data.title', $i)): ?><h4 class="item-title"><?php echo $helper->get('data.title', $i) ?></h4><?php endif; ?>
                  <p><?php echo $helper->get('data.description', $i) ?></p>
                  <?php if($helper->get('data.action-btn', $i)): ?>
                  <a href="<?php echo $helper->get('data.action-url', $i) ?>" class="item-btn btn-sm btn btn-success"><?php echo $helper->get('data.action-btn', $i) ?></a>
                  <?php endif; ?>
                </div>

              </div>

            </div>
          </div>
        </div>
        <?php endforeach ;?>
    </div>
  </div>
</div>



