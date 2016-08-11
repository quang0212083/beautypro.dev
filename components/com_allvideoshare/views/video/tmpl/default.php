<?php

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$video  = $this->video;

if(!$this->user && $video->access == 'registered') {
	echo JText::_('YOU_NEED_TO_REGISTER_TO_VIEW_THIS_PAGE');
	return;
}

$config = $this->config;
$custom = $this->custom;
$player = $this->player;
$action = "index.php?option=com_allvideoshare&view=search";
$qs = JRequest::getInt('Itemid') ? '&Itemid=' . JRequest::getInt('Itemid') : '';

$document = JFactory::getDocument();
$document->addStyleSheet( JRoute::_("index.php?option=com_allvideoshare&view=css"),'text/css',"screen");
$document->addStyleSheet( JURI::root() . "components/com_allvideoshare/css/allvideoshareupdate.css",'text/css',"screen");
if($config[0]->comments_type == 'facebook') {
	$document->addScriptDeclaration("
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = '//connect.facebook.net/en_US/all.js#appId=".$config[0]->fbappid."&xfbml=1';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));  
	");
	if($config[0]->fbappid) {
		$document->addCustomTag('<meta property="fb:app_id" content="'.$config[0]->fbappid.'">');
	}
}

$isResponsive = ($config[0]->responsive == 1) ? 'class="avs_responsive"' : 'style=width:'.$custom->width.'px';
?>

<div id="fb-root"></div>
<?php if($config[0]->title) { ?>
	<h2> <?php echo $this->escape($video->title); ?> </h2>
<?php } ?>
<div id="avs_video<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" <?php echo $isResponsive; ?>>
  <div class="avs_video_header">
    <?php if($config[0]->category) { ?>
    	<div class="avs_category_label"><strong><?php echo JText::_('CATEGORY'); ?> : </strong><?php echo $video->category; ?></div>
    <?php } ?>
    <?php if($config[0]->views) { ?>
    	<div class="avs_views_label"><strong><?php echo JText::_('VIEWS'); ?> : </strong><?php echo $video->views; ?></div>
    <?php } ?>
    <?php if($config[0]->search) { ?>
    	<div class="avs_input_search">
      	<form action="<?php echo JRoute::_( $action.$qs ); ?>" name="hsearch" id="hsearch" method="post" enctype="multipart/form-data">
            <input type="hidden" name="option" value="com_allvideoshare"/>
    		<input type="hidden" name="view" value="search"/>
        	<input type="text" name="avssearch" id="avssearch" value=""/>
        	<input type="submit" id="search_btn" class="btn" value="Go" />
      	</form>
    	</div>
    <?php } ?>
    <div style="clear:both;"></div>
  </div>
  <?php echo $player; ?>
  <?php if($config[0]->description) { ?>
  	<div class="avs_video_description"><?php echo $video->description; ?></div>
  <?php } 
	if($config[0]->layout != 'none') {
		echo $this->loadTemplate($config[0]->layout); 
	}
  ?>
</div>