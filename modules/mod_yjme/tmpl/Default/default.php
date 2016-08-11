<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
//Title: 			$yj_get_items['item_title']
//Author: 			$yj_get_items['item_author'] = username || $yj_get_items['item_author_rn'] = real name
//Image:			$yj_get_items['img_url'] = use isset to check before output
//Intro text:		$yj_get_items['item_intro']
//Create date:		$yj_get_items['item_date']
//Category:			$yj_get_items['cat_title']
//Item url:			$yj_get_items['item_url']
//Author url: 		$yj_get_items['author_url']
//Cat url:			$yj_get_items['cat_url']
//Foreach to be used =  foreach ($main_yj_arr as $yj_get_items){ echo each part here }

/*Image sizing: The images are inside div that is resizing when you enter the values in module parameters. this way there is no image disortion. For those who dont like that , you can add this
style="width:<?php echo $img_width ?>;height:<?php echo $img_height ?>;"
within image tag after alt="" (space it please) and have the images resized */

  
defined('_JEXEC') or die('Restricted access'); ?>
<!-- Powered by YJ Module Engine find out more at www.youjoomla.com -->
<div class="yjme_holder">
  <?php 
    $numberof = count($main_yj_arr);
    foreach ($main_yj_arr as $key=> $yj_get_items):
     if($key == $numberof-1):
      $last = ' last';
    else:
      $last = '';
    endif;
    ?>
  <div class="yjme_item">
    <div class="yjme_item_in yjmeitem<?php echo $yj_get_items['item_id']?><?php echo $last ?>">
      <?php if ($show_title == 1 ):  ?>
      <a class="item_title" href="<?php echo $yj_get_items['item_url'] ?>">
        <?php echo $yj_get_items['item_title']?>
      </a>
      <?php endif; ?>
	  <?php if ($show_rating == 1 ):  ?>
	  <div class="yjme_rating yjmestars<?php echo $yj_get_items['item_rating']?>"></div>
	  <?php endif; ?>
      <?php  if (isset($yj_get_items['img_url']) && $yj_get_items['img_url'] != "" && $show_img == 1) :?>
      <div class="imageholder" style="width:<?php echo $img_width ?>;height:<?php echo $img_height ?>;float:<?php echo $align ?>;">
        <a class="item_image"  style="width:<?php echo $img_width ?>;height:<?php echo $img_height ?>;" href="<?php echo $yj_get_items['item_url'] ?>" >
          <img src="<?php echo $yj_get_items['img_url'] ?>" alt="<?php echo $yj_get_items['item_title']?>" />
        </a>
      </div>
      <?php endif;?>
      <?php if ($show_intro == 1 ):?>
      <p class="item_intro">
        <?php echo $yj_get_items['item_intro']?>
      </p>
      <?php endif; ?>
      <?php if ($show_cat_title == 1 || $show_date== 1 || $show_author == 1  ):?>
      <div class="clearnf"></div>
      <div class="item_details">
        <?php if ($show_cat_title == 1):?>
        <div class="item_category">
        <a href="<?php echo $yj_get_items['cat_url'] ?>">
          <?php echo $yj_get_items['cat_title']?>
          <?php if($show_cat_title == 1 && $show_date == 1):?> - <?php endif; ?>
        </a>
        </div>
        <?php endif; ?>
        <?php if ($show_date == 1):?>
        <div class="item_cdate">
        <?php echo $yj_get_items['item_date']?>
        </div>
        <?php endif; ?>
        <?php if($show_author == 1) : ?>
        <div class="item_author">
          <?php
          if($show_cat_title == 1 || $show_date == 1):
            $space = ' - ';
          else:
            $space = '';
          endif;
		  if(!empty($yj_get_items['item_author_alias'])):
		  
            	$author = $space.'by '.$yj_get_items['item_author_alias'];
		  
          elseif($author_name == 1) :

			$author = $space.'by '.$yj_get_items['item_author'];
			
          else:
            
			$author =  $space.'by '.$yj_get_items['item_author_rn'];
			
          endif;
          ?>
          <?php if($item_source == 1) : ?>
            <?php echo $author ?>
          <?php else: ?>
            <a href="<?php echo $yj_get_items['author_url'] ?>">
              <?php echo $author ?>
            </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php if ($show_read == 1 ): ?>
      <a class="item_readmore" href="<?php echo $yj_get_items['item_url']?>">
        <span>
          <?php echo JText::_('READ_MORE_TEXT');?>
        </span>
      </a>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>