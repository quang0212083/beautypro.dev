<?php

/*
 * @version		$Id: mod_allvideoshareplayer.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
 // Include the syndicate functions only once
if(!defined('DS')) { define('DS',DIRECTORY_SEPARATOR); }
require_once( dirname(__FILE__).DS.'helper.php' );
require_once( JPATH_ROOT.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'player.php' );

$params->def('width', '-1');
$params->def('height','-1');
$params->def('title','0');
$params->def('description','0');
$params->def('cache','0');

$custom  = new AllVideoShareModelPlayer( $params->get('width'), $params->get('height') );
$videoid = AllVideoSharePlayerHelper::getVideoID( $params );
$player = $custom->buildPlayer( $videoid, $params->get('playerid'), $params->get('autodetect') );
$__width = ($custom->width == '100%') ? '100%' : $custom->width.'px';
if(!$video = $custom->video) {
	return;
}

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_allvideoshare/css/allvideoshare.css",'text/css',"screen");
$document->addStyleSheet(JURI::root()."components/com_allvideoshare/css/allvideoshareupdate.css", 'text/css', "screen");
$moduleclass_sfx = htmlspecialchars( $params->get('moduleclass_sfx') );
?>

<?php if($params->get('title') == 1) : ?>
	<h3 class="avs_video_title<?php echo $moduleclass_sfx; ?>" style="width:<?php echo $__width; ?>;">
		<?php echo $video->title; ?>
	</h3>
<?php endif; ?>

<?php echo $player; ?>

<?php if($params->get('description') == 1) : ?>
	<div class="avs_video_description<?php echo $moduleclass_sfx; ?>" style="width:<?php echo $__width; ?>;">
		<?php echo $video->description; ?>
	</div>
<?php endif;