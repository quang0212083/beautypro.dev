<?php 

/*
 * @version		$Id: default_edit.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$target = JURI::getInstance()->toString();
$target = preg_replace('/(?:&|(\?))uid=[^&]*(?(1)&|)?/i', "$1", $target);

$session = JFactory::getSession();
$session->set('target', $target);

?>
<style type="text/css">
#tbl table tr, #tbl table th, #tbl table td {
	border:none;
	padding:5px;
}
</style>
<form action="index.php?option=com_hdwplayer&view=edit" method="post" name="adminForm" id="adminForm" onsubmit="return submitbutton();" enctype="multipart/form-data">
  <div id="tbl">
    <table cellpadding="0px" cellspacing="0px" border="0">
      <tr>
        <td>Title</td>
        <td><input type="text" name="title" size="60" value="<?php echo $data->title; ?>" /></td>
      </tr>
      <tr>
        <td>Video Type</td>
        <td><select id="type" name="type" onchange="javascript:changeType(this.options[this.selectedIndex].id);">
            <option value="General Upload" id="General Upload" >General Upload</option>
            <option value="Direct URL" id="Direct URL" >Direct URL</option>
            <?php
          	if($items['ffmpeg']) echo '<option value="FFMPEG Upload" id="FFMPEG Upload" >FFMPEG Upload</option>';
          	if($items['youtube']) echo '<option value="Youtube Videos" id="Youtube Videos" >Youtube Videos</option>';
          	if($items['dailymotion']) echo '<option value="Dailymotion Videos" id="Dailymotion Videos" >Dailymotion Videos</option>';
          	if($items['vimeo']) echo '<option value="Vimeo Videos" id="Vimeo Videos" >Vimeo Videos</option>';
          	if($items['rtmp']) echo '<option value="RTMP Streams" id="RTMP Streams" >RTMP Streams</option>';
          	if($items['smil']) echo '<option value="SMIL" id="SMIL" >SMIL</option>';
          	if($items['lighttpd']) echo '<option value="Lighttpd Videos" id="Lighttpd Videos" >Lighttpd Videos</option>';
          	if($items['bitgravity']) echo '<option value="Bitgravity Videos" id="Bitgravity Videos" >Bitgravity Videos</option>';
		  ?>
          </select>
          &nbsp;&nbsp;<a href="javascript:switchHelp();">what is this?</a> <?php echo '<script>document.getElementById("'.$data->type.'").selected="selected"</script>'; ?> </td>
      </tr>
      <tr id="videotypehelp" style="display:none";>
        <td></td>
        <td><div style="padding:10px; border:1px solid #CCC; background-color:#F0F0F0"> <strong>General Upload - </strong>This is the default Upload Method to upload your videos to our site. By Using this method you will have to upload video along with Preview image & Thumb image. <br />
            <i>Supported fomats are flv, mp4 , 3g2, 3gp, aac, f4b, f4p, f4v, m4a, m4v, mov, sdp, vp6</i><br />
            <br />
            <strong>Direct URL - </strong>This method allow you to add your Video by just providing it's Direct Download link to the appropriate field.<br />
            <br />
            <?php 			
			if($items['ffmpeg']) echo '<strong>FFMPEG Upload - </strong>Video formats like <i>mpg, wma, avi, qt, rm, wmv</i> are not supported directly by our player. They are converted to the supported format using this method. Just upload your Videos of the above format by choosing this video type. <br /><br />';		
			if($items['youtube']) echo '<strong>Youtube Videos - </strong>This method allow you to add Youtube Videos by just entering the Youtube Video Page URL to the appropriate field.<br /><br />';
			if($items['dailymotion']) echo '<strong>Dailymotion Videos - </strong>This method allow you to add Dilymotion Videos by just entering the Dailymotion Video Page URL to the appropriate field.<br /><br />';
			if($items['vimeo']) echo '<strong>Vimeo Videos - </strong>This method allow you to add Vimeo Videos by just entering the Vimeo Video Page URL to the appropriate field.<br /><br />';
			if($items['rtmp']) echo '<strong>RTMP Streams - </strong>This method allow you to add videos(streams) from special servers like Wowza, FMS & Red5.<br /><br />';
			if($items['smil']) echo '<strong>SMIL - </strong>The Format for Multi-bitrate Streaming. This Method allow you to play videos from SMIL xml files.<br /><br />';
			if($items['lighttpd']) echo '<strong>Lighttpd Videos - </strong>This method allow you to add videos from servers enabled with HTTP Pseudostreaming Modules.';
			if($items['lighttpd']) echo '<strong>Bitgravity Videos - </strong>This method allow you to add videos from Bitgravity CDN Server.';
		?>
          </div></td>
      </tr>
      <tr id="dvr">
        <td class="key">DVR</td>
        <td><input type="checkbox" name="dvr" value="1" <?php if($data->dvr==1){echo 'checked="checked" ';}?> /></td>
      </tr>
      <tr id="streamdata">
        <td>Streamer</td>
        <td><input type="text" name="streamer" size="60" value="<?php echo $data->streamer; ?>" /></td>
      </tr>
      <tr id="uploadvideodata">
        <td>Upload Video</td>
        <td><div id="upload_video">
            <?php if($data->video) { ?>
            <input name="uploadvideo" readonly="readonly" value="<?php echo $data->video; ?>"  size="47" />
            <input type="button" name="change" value="Change" onclick="uploadMode('video')" />
            <?php } else { ?>
            <input type="file" name="uploadvideo" maxlength="100" />
            <?php } ?>
          </div></td>
      </tr>
      <tr id="uploadhddata">
        <td>Upload HD Video [Optional]</td>
        <td><div id="upload_hd_video">
            <?php if($data->hdvideo) { ?>
            <input name="uploadhdvideo" readonly="readonly" value="<?php echo $data->hdvideo; ?>"  size="47" />
            <input type="button" name="change" value="Change" onclick="uploadMode('hdvideo')" />
            <?php } else { ?>
            <input type="file" name="uploadhdvideo" maxlength="100" />
            <?php } ?>
          </div></td>
      </tr>
      <tr id="uploadpreviewdata">
        <td>Upload Preview Image</td>
        <td><div id="upload_preview">
            <?php if($data->preview) { ?>
            <input name="uploadpreview" readonly="readonly" value="<?php echo $data->preview; ?>"  size="47" />
            <input type="button" name="change" value="Change" onclick="uploadMode('preview')" />
            <?php } else { ?>
            <input type="file" name="uploadpreview" maxlength="100" />
            <?php } ?>
          </div></td>
      </tr>
      <tr id="uploadthumbdata">
        <td>Upload Thumb Image</td>
        <td><div id="upload_thumb">
            <?php if($data->thumb) { ?>
            <input name="uploadthumb" readonly="readonly" value="<?php echo $data->thumb; ?>"  size="47" />
            <input type="button" name="change" value="Change" onclick="uploadMode('thumb')" />
            <?php } else { ?>
            <input type="file" name="uploadthumb" maxlength="100" />
            <?php } ?>
          </div></td>
      </tr>
      <tr id="videourldata">
        <td>Video URL</td>
        <td><input type="text" name="video" size="60"  value="<?php echo $data->video; ?>" /></td>
      </tr>
      <tr id="hdurldata">
        <td>HD Video URL [Optional]</td>
        <td><input type="text" name="hdvideo" size="60"  value="<?php echo $data->hdvideo; ?>" /></td>
      </tr>
      <tr id="previewurldata">
        <td>Preview Image</td>
        <td><input type="text" name="preview" size="60" value="<?php echo $data->preview; ?>" /></td>
      </tr>
      <tr id="thumburldata">
        <td>Thumb Image</td>
        <td><input type="text" name="thumb" size="60" value="<?php echo $data->thumb; ?>" /></td>
      </tr>
      <tr id="tokendata">
        <td>Security Token [Wowza]</td>
        <td><input type="text" name="token" size="60" value="<?php echo $data->token; ?>"/></td>
      </tr>     
      <tr>
        <td valign="top" style="padding-top:10px !important;">Description</td>
        <td><textarea name="description" rows="6" cols="50" ><?php echo $data->description; ?></textarea></td>
      </tr>
      <tr>
        <td>Category</td>
        <td><?php echo JHTML::_('select.genericlist', $category_options, 'category', '', 'value', 'text', $data->category); ?></tr>
      <tr>
        <td></td>
        <td>
        	<input type="submit" value="Submit" style="height:25px; width:80px;" />
        	<input type="button" value="Cancel" onClick="javascript:location.href='<?php echo $target; ?>';" style="height:25px; width:80px; margin-left:5px" /> 
        </td>
      </tr>
    </table>
  </div>
  <input type="hidden" name="id" value="<?php echo $data->id; ?>" />
</form>
<script type="text/javascript">
changeType("<?php echo $data->type; ?>");

function submitbutton(){
	var form            = document.adminForm;
 	var type            = document.getElementById("type");
    var method          = type.options[type.selectedIndex].value;
	var videoExtensions = ['flv', 'mp4' , '3g2', '3gp', 'aac', 'f4b', 'f4p', 'f4v', 'm4a', 'm4v', 'mov', 'sdp', 'vp6', 'smil'];
	var ffmpegExtensions= ['flv', 'mp4' , 'mpg', 'wma', 'avi', 'qt', 'rm', 'mov', 'wmv'];
	var imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
	var isAllowed       = true;
	
	if(method == 'General Upload' || method == 'FFMPEG Upload') {
		if (form.uploadvideo.value == "") {
       		alert( "<?php echo JText::_( 'You must add a Video', true); ?>" );
       		return false;
    	}
	} else {
		if (form.video.value == "") {
       		alert( "<?php echo JText::_( 'You must add a Video', true); ?>" );
       		return false;
    	}
	}
		
	switch(method) {
		case 'General Upload':
			isAllowed = checkExtension('VIDEO', form.uploadvideo.value, videoExtensions, method);
			break;
		case 'FFMPEG Upload':
			isAllowed = checkExtension('FFMPEG', form.uploadvideo.value, ffmpegExtensions, method);
			break;
		default:
			isAllowed = checkExtension('VIDEO', form.video.value, videoExtensions, method);
	}
	
	if(isAllowed == false) return;
	
	if(form.uploadhdvideo.value) {
		isAllowed = checkExtension('HD VIDEO', form.uploadhdvideo.value, videoExtensions, method);
		if(isAllowed == false) 	return false;
	} 
	
	if(form.hdvideo.value) {
		isAllowed = checkExtension('HD VIDEO', form.hdvideo.value, videoExtensions, method);
		if(isAllowed == false) 	return false;
	} 
	
	if(form.uploadthumb.value) {
		isAllowed = checkExtension('THUMB', form.uploadthumb.value, imageExtensions, method);
		if(isAllowed == false) 	return false;
	} 
		
	if(form.thumb.value) {
		isAllowed = checkExtension('THUMB', form.thumb.value, imageExtensions, method);
		if(isAllowed == false) 	return false;
	} 
	
	if(form.uploadpreview.value) {
		isAllowed = checkExtension('PREVIEW', form.uploadpreview.value, imageExtensions, method);
		if(isAllowed == false) 	return false;
	} 
	
	if(form.preview.value) {
		isAllowed = checkExtension('PREVIEW', form.preview.value, imageExtensions, method);
		if(isAllowed == false) 	return false;
	}
	
	if (form.title.value == "") {
       	alert( "<?php echo JText::_( 'You must enter a Title for the Video', true); ?>" );
       	return false;
    }

	return true;
}
 
function checkExtension(type, filePath, validExtensions, method) {

	if(method == 'Youtube Videos' ||  method == 'Dailymotion Videos' || method == 'Vimeo Videos' || method == 'RTMP Streams') return true;
        
    var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

    for(var i = 0; i < validExtensions.length; i++) {
        if(ext == validExtensions[i]) return true;
    }

    alert(type + ' :   The file extension ' + ext.toUpperCase() + ' is not allowed!');
    return false;	
}

function changeType(typ) {
	document.getElementById('dvr').style.display="none";
    document.getElementById('streamdata').style.display="none";
	document.getElementById('uploadvideodata').style.display="none";
	document.getElementById('uploadhddata').style.display="none";
	document.getElementById('uploadpreviewdata').style.display="none";
	document.getElementById('uploadthumbdata').style.display="none";
   	document.getElementById('videourldata').style.display="none";
	document.getElementById('hdurldata').style.display="none";
	document.getElementById('previewurldata').style.display="none";
	document.getElementById('thumburldata').style.display="none";
	document.getElementById('tokendata').style.display="none";
			
    switch(typ) {
		case 'General Upload':
			document.getElementById('uploadvideodata').style.display="";
			document.getElementById('uploadhddata').style.display="";
			document.getElementById('uploadpreviewdata').style.display="";
			document.getElementById('uploadthumbdata').style.display="";
			break;
		case 'FFMPEG Upload':
			document.getElementById('uploadvideodata').style.display="";
			break;
		case 'Lighttpd Videos':
		case 'Direct URL':
			document.getElementById('videourldata').style.display="";
			document.getElementById('hdurldata').style.display="";
			document.getElementById('previewurldata').style.display="";
			document.getElementById('thumburldata').style.display="";
			break;
		case 'Youtube Videos':
			document.getElementById('videourldata').style.display="";
			break;
		case 'Dailymotion Videos':
		case 'Vimeo Videos':
		case 'SMIL':
			document.getElementById('videourldata').style.display="";
			document.getElementById('previewurldata').style.display="";
			document.getElementById('thumburldata').style.display="";
			break;
		case 'RTMP Streams':
			document.getElementById('streamdata').style.display="";
			document.getElementById('videourldata').style.display="";
			document.getElementById('previewurldata').style.display="";
			document.getElementById('thumburldata').style.display="";
			document.getElementById('tokendata').style.display="";
			break;
		case 'Bitgravity Videos':
		    document.getElementById('dvr').style.display="";
			document.getElementById('videourldata').style.display="";
			document.getElementById('hdurldata').style.display="";
			document.getElementById('previewurldata').style.display="";
			document.getElementById('thumburldata').style.display="";
			break;
	}
}
 
function switchHelp() {
	if(document.getElementById('videotypehelp').style.display == "none") {
		document.getElementById('videotypehelp').style.display = "";
	} else {
		document.getElementById('videotypehelp').style.display = "none";
	}
}	
</script>