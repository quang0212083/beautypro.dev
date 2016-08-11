<?php 

/*
 * @version		$Id: embed.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

require_once JPATH_ROOT.DS.'components'.DS.'com_hdwplayer'.DS.'models'.DS.'html5.php';
require_once JPATH_ROOT.DS.'components'.DS.'com_hdwplayer'.DS.'models'.DS.'isMobile.php';

srand ((double) microtime( )*1000000);
$dyn      = rand( );
$contents = '';
$htmlNode = '';
$html5Obj = array();
$html5Obj[0] = new stdClass();
if(JRequest::getCmd('wid')) {
	$id = JRequest::getCmd('wid');
} else {
	$id = '';
}

if($id == '' && isset($wid)) {
	$id = $wid;
}

if($video == '') {
	$html5     = new HdwplayerModelHtml5();
	$html5Obj  = $html5->getvideo($id, $category);
	$video     = $html5Obj[0]->video;
	$streamer  = $html5Obj[0]->streamer;
	$preview   = $html5Obj[0]->preview;
} else {
	$_type = $type ? $type : 'video';
	$html5Obj[0]->type = $_type;
	if($_type != 'youtube') {
		$html5Obj[0]->ext = @end(explode(".", $video));
	} else {
		$html5Obj[0]->ext = '';
	}
}


$cover = "fill";
if(isset($stretch) && $stretch != ''){
	$stretch = $stretch;
}
if(isset($player) && $player->stretchtype != '')
{
	$stretch = $player->stretchtype;
}


if(isset($items['stretch']) && $items['stretch'] != ''){
	$stretch = $items['stretch'];
}

if(isset($stretch) && $stretch != ''){
	if($stretch == 'fill')$cover = 'fill';
	if($stretch == 'uniform')$cover = 'cover';
	if($stretch == 'none')$cover = 'none';
	if($stretch == 'exactfit')$cover = 'contain';
}

echo "<style>
			[id^=hdwplayer_] video {
				object-fit: ".$cover." !important;
			}
			</style>";

if($video == '') {
	$key = 'category';
	$flashvars  = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $flashvars . '&'); 
	$flashvars  = substr($flashvars, 0, -1); 
	$flashvars .= '&playListXml=&playList=false&video=video.flv';
}

switch($html5Obj[0]->type) {
	case 'Youtube Videos' :
	    $url_string = parse_url($video, PHP_URL_QUERY);
  	    parse_str($url_string, $args);
	    $htmlNode  = '<iframe title="YouTube video player" width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$args['v'].'" frameborder="0" allowfullscreen></iframe>';
		break;
	case 'Dailymotion Videos':
	 	$htmlNode  = '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" src="'.$video.'"></iframe>';
		break;
	case 'RTMP Streams':
		$url_string = str_replace('rtmp', 'http', $streamer).'/'.$video.'/playlist.m3u8';
	 	$htmlNode   = '<video poster="'.$preview.'" onclick="this.play();" width="'.$width.'" height="'.$height.'" controls>';
  	    $htmlNode  .= '<source src="'.$url_string.'" />';
		$htmlNode  .= '</video>';
		break;
	default :
	    if($html5Obj[0]->ext == 'mp4' || $html5Obj[0]->ext == 'm4v') {
	    	$htmlNode  = '<video poster="'.$preview.'" onclick="this.play();" width="'.$width.'" height="'.$height.'" controls>';
  	    	$htmlNode .= '<source src="'.$video.'" />';
			$htmlNode .= '</video>';
		}		
}

$detect = new Mobile_Detect();
if ($detect->isMobile()) {
    $contents  .= '<p>'.$htmlNode.'</p>';
} else {
	$src = JURI::root() . 'components/com_hdwplayer/player.swf?r=' . rand();
	$contents  .= '<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="'.$width.'" height="'.$height.'">';
	$contents  .= '<param name="movie" value="'.$src.'" />';
	$contents  .= '<param name="wmode" value="opaque" />';
	$contents  .= '<param name="allowfullscreen" value="true" />';
	$contents  .= '<param name="allowscriptaccess" value="always" />';
	$contents  .= '<param name="flashvars" value="'.$flashvars.'" />';
	$contents  .= '<object type="application/x-shockwave-flash" data="'.$src.'" width="'.$width.'" height="'.$height.'">';
	$contents  .= '<param name="movie" value="'.$src.'" />';
	$contents  .= '<param name="wmode" value="opaque" />';
	$contents  .= '<param name="allowfullscreen" value="true" />';
	$contents  .= '<param name="allowscriptaccess" value="always" />';
	$contents  .= '<param name="flashvars" value="'.$flashvars.'" />';
	$contents  .= '</object>';
	$contents  .= '</object>';
}

?>