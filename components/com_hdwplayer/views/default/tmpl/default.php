<?php 

/*
 * @version		$Id: default.php 3.0 2012-10-10 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$player    = $this->settings;
$width     = $this->player_width;
$height    = $this->player_height;
$lang      = JRequest::getCmd('lang') ? '&lang='.JRequest::getCmd('lang') : '';
$video     = '';
$category  = '';
$plugin    = true;
$src       = COM_HDWPLAYER_BASEURL.'&view=player'.$lang;
$flashvars = 'baseJ='.JURI::root();
$flashvars .= JRequest::getCmd('wid') ? '&id='.JRequest::getCmd('wid')  : '' ;

require JPATH_ROOT.DS.'components'.DS.'com_hdwplayer'.DS.'models'.DS.'embed.php';

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root() . "components/com_hdwplayer/css/hdwplayer.css",'text/css',"screen");
$document->addScript( JURI::root() . "components/com_hdwplayer/js/hdwplayer.js" );

?>

<?php if($this->show_title) : ?>
	<h2 id="hdwplayer_title">&nbsp;</h2>
<?php endif; ?>
<div id="hdwplayer_video">
	<?php echo $contents; ?>
</div>
<?php if($this->show_description) : ?>
	<div id="hdwplayer_description">&nbsp;</div>
<?php endif; ?>