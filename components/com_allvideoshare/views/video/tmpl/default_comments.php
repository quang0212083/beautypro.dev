<?php

/*
 * @version		$Id: default_comments.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$config = $this->config;
$custom = $this->custom;
$video = $this->video;
$comments = JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php';
$komento = JPATH_ROOT.DS.'components'.DS.'com_komento'.DS.'bootstrap.php';

$__width = ($custom->width == '100%') ? '100%' : $custom->width.'px';
  
if(file_exists($comments) && $config[0]->comments_type == 'jcomments') {
	require_once($comments);
    echo JComments::showComments($video->id, 'com_allvideoshare', $video->title);
	
} else if($config[0]->comments_type == 'facebook') {  ?>
	<div class="avs_video_comments">
  		<h2><?php echo JText::_('ADD_YOUR_COMMENTS'); ?></h2>
  		<div class="fb-comments" data-href="<?php echo JURI::getInstance()->toString(); ?>" data-num-posts="<?php echo $config[0]->comments_posts; ?>" data-width="<?php echo $__width; ?>" data-colorscheme="<?php echo $config[0]->comments_color; ?>"></div>
	</div>
<?php } else if(file_exists($komento) && $config[0]->comments_type == 'komento') {	
	$item = new stdClass;
	$item->id = $video->id;
	$item->catid = $video->category;
	$item->text = $video->description;
	$item->introtext = $video->description;
	$options = array();
		
	require_once($komento);
	echo '<div class="avs_video_comments">';
	echo Komento::commentify( 'com_allvideoshare', $item, $options );
	echo '</div>';	
 } ?>