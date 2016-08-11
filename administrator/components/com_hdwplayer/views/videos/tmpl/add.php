<?php

/*
 * @version		$Id: add.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
$editor = JFactory::getEditor();
?>

<div id="hdwplayer">
  <form action="index.php?option=com_hdwplayer&view=videos" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div style="float:left; width:52%">
      <fieldset class="adminform">
      <legend>Add Video</legend>
      <table class="admintable">
        <tr>
          <td class="key">Title</td>
          <td><input type="text" name="title" size="60" /></td>
        </tr>
        <tr>
          <td class="key">Video Type</td>
          <td><select id="type" name="type" onchange="javascript:changeType(this.options[this.selectedIndex].id);">
              <option value="General Upload" id="General Upload" selected="selected" >General Upload</option>
              <option value="FFMPEG Upload" id="FFMPEG Upload" >FFMPEG Upload</option>
              <option value="Direct URL" id="Direct URL" >Direct URL</option>
              <option value="Youtube Videos" id="Youtube Videos" >Youtube Videos</option>
              <option value="Dailymotion Videos" id="Dailymotion Videos" >Dailymotion Videos</option>
              <option value="Vimeo Videos" id="Vimeo Videos" >Vimeo Videos</option>
              <option value="RTMP Streams" id="RTMP Streams" >RTMP Streams</option>
              <option value="SMIL" id="SMIL" >SMIL</option>
              <option value="Lighttpd Videos" id="Lighttpd Videos" >Lighttpd Videos</option>
              <option value="Bitgravity Videos" id="Bitgravity Videos" >Bitgravity Videos</option>
            </select>
          </td>
        </tr>
        <tr id="dvr">
          <td class="key">DVR</td>
          <td><input type="checkbox" name="dvr" value="1" /></td>
        </tr>
        <tr id="streamdata">
          <td class="key">Streamer</td>
          <td><input type="text" name="streamer" size="60" /></td>
        </tr>
        <tr id="uploadvideodata">
          <td class="key">Upload Video</td>
          <td><div id="upload_video">
              <input type="file" name="uploadvideo" maxlength="100" />
            </div></td>
        </tr>
        <tr id="uploadhddata">
          <td class="key">Upload HD Video [Optional]</td>
          <td><div id="upload_hd_video">
              <input type="file" name="uploadhdvideo" maxlength="100" />
            </div></td>
        </tr>
        <tr id="uploadpreviewdata">
          <td class="key">Upload Preview Image</td>
          <td><div id="upload_preview">
              <input type="file" name="uploadpreview" maxlength="100" />
            </div></td>
        </tr>
        <tr id="uploadthumbdata">
          <td class="key">Upload Thumb Image</td>
          <td><div id="upload_thumb">
              <input type="file" name="uploadthumb" maxlength="100" />
            </div></td>
        </tr>
        <tr id="videourldata">
          <td class="key">Video URL</td>
          <td><input type="text" name="video" size="60" /></td>
        </tr>
        <tr id="hdurldata">
          <td class="key">HD Video URL [Optional]</td>
          <td><input type="text" name="hdvideo" size="60" /></td>
        </tr>
        <tr id="previewurldata">
          <td class="key">Preview Image</td>
          <td><input type="text" name="preview" size="60" /></td>
        </tr>
        <tr id="thumburldata">
          <td class="key">Thumb Image</td>
          <td><input type="text" name="thumb" size="60" /></td>
        </tr>
        <tr id="tokendata">
          <td class="key">Security Token [Wowza]</td>
          <td><input type="text" name="token" size="60" /></td>
        </tr>
        <tr>
          <td class="key">Category</td>
          <td><?php echo $this->category; ?></td>
        </tr>
        <tr>
          <td class="key">Featured</td>
          <td><input type="checkbox" name="featured" value="1" /></td>
        </tr>
        <tr>
          <td class="key">Publish</td>
          <td><input type="checkbox" name="published" value="1" checked="checked" /></td>
        </tr>
      </table>
      </fieldset>
      <fieldset class="adminform">
      <legend>Description</legend>
      <table class="admintable">
        <tr>
          <td><?php echo $editor->display( 'description', '' , '625', '300', '', '', true ); ?></td>
        </tr>
      </table>
      </fieldset>
    </div>
    <div style="float:right;width:48%; color:#333333;">
      <fieldset class="adminform">
      <legend>SEO Settings</legend>
      <table class="admintable">
        <tr>
          <td class="key">Meta Keywords</td>
          <td><input type="text" name="tags" size="60" /></td>
        </tr>
        <tr>
          <td class="key" valign="top" style="padding-top:10px !important;">Meta Description</td>
          <td><textarea name="metadescription" rows="6" cols="50" ></textarea></td>
        </tr>
      </table>
      </fieldset>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="task" value="add" />
    <input type="hidden" name="catid" value="<?php echo $category->id; ?>">
    <input type="hidden" name="boxchecked" value="1">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<script type="text/javascript">
changeType('General Upload');

if(<?php echo substr(JVERSION,0,3); ?> != '1.5') {
	Joomla.submitbutton = submitbutton;
}
	
function submitbutton(pressbutton) { 
    var form            = document.adminForm;
 	var type            = document.getElementById("type");
    var method          = type.options[type.selectedIndex].value;
	var videoExtensions = ['flv', 'mp4' , '3g2', '3gp', 'aac', 'f4b', 'f4p', 'f4v', 'm4a', 'm4v', 'mov', 'sdp', 'vp6', 'smil'];
	var ffmpegExtensions= ['flv', 'mp4' , 'mpg', 'wma', 'avi', 'qt', 'rm', 'mov', 'wmv'];
	var imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
	var isAllowed       = true;
	
	if(pressbutton == 'save' || pressbutton == 'apply') {
	
		if(method == 'General Upload' || method == 'FFMPEG Upload') {
			if (form.uploadvideo.value == "") {
        		alert( "<?php echo JText::_( 'You must add a Video', true); ?>" );
         		return;
     		}
		} else {
			if (form.video.value == "") {
        		alert( "<?php echo JText::_( 'You must add a Video', true); ?>" );
         		return;
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
			if(isAllowed == false) 	return;
		} 
		
		if(form.hdvideo.value) {
			isAllowed = checkExtension('HD VIDEO', form.hdvideo.value, videoExtensions, method);
			if(isAllowed == false) 	return;
		} 
		
		if(form.uploadthumb.value) {
			isAllowed = checkExtension('THUMB', form.uploadthumb.value, imageExtensions, method);
			if(isAllowed == false) 	return;
		} 
		
		if(form.thumb.value) {
			isAllowed = checkExtension('THUMB', form.thumb.value, imageExtensions, method);
			if(isAllowed == false) 	return;
		} 
		
		if(form.uploadpreview.value) {
			isAllowed = checkExtension('PREVIEW', form.uploadpreview.value, imageExtensions, method);
			if(isAllowed == false) 	return;
		} 
		
		if(form.preview.value) {
			isAllowed = checkExtension('PREVIEW', form.preview.value, imageExtensions, method);
			if(isAllowed == false) 	return;
		}
	
		if (form.title.value == "") {
        	alert( "<?php echo JText::_( 'You must enter a Title for the Video', true); ?>" );
         	return;
    	}
	}
	
	submitform( pressbutton );	
	return;
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
</script>