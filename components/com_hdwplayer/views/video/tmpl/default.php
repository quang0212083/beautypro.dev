<?php 

/*
 * @version		$Id: default.php 3.0 2012-10-10 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$player     = $this->settings;
$width      = $this->player_width;
$height     = $this->player_height;
$video      = '';
$category   = $this->category;
$flashvars  = 'baseJ='.JURI::root();
$flashvars .= JRequest::getCmd('wid') ? '&id='.JRequest::getCmd('wid')  : '' ;
$flashvars .= ($this->autostart == 0) ? '&autoStart=false' : '&autoStart=true';
$flashvars .= ($this->playlist_autostart == 1) ? '&playListAutoStart=true' : '&playListAutoStart=false';
$flashvars .= '&playListRandom=false';
$flashvars .= ($category) ? '&category='.$category : '';


require JPATH_ROOT.DS.'components'.DS.'com_hdwplayer'.DS.'models'.DS.'embed.php';

$this->generateMetaTags( $this->params, $html5Obj[0] );
$this->generateBreadcrumbs( $this->params, $html5Obj[0] );

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . "components/com_hdwplayer/css/hdwplayer.css",'text/css',"screen");
$document->addScript( JURI::root() . "components/com_hdwplayer/js/hdwplayer.js" );

?>

<?php if($this->show_title) : ?>
	<h2 id="hdwplayer_title" style="width:<?php echo $width; ?>px;">&nbsp;</h2>
<?php endif; ?>
<div id="hdwplayer_video">
	<?php echo $contents; ?>
</div>
<?php if($this->show_description) : ?>
	<div id="hdwplayer_description" style="width:<?php echo $width; ?>px;">&nbsp;</div>
<?php endif; ?>
<?php echo $this->loadTemplate('related'); ?>