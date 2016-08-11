<?php

/*
 * @version		$Id: edit.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

$data = $this->data;
?>

<div id="avs">
  <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
  	<?php
		AllVideoShareFallback::startTabs();
		AllVideoShareFallback::initPanel(JText::_('GENERAL_SETTINGS'), 'generalsettingstab');
  	?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('TITLE'); ?></td>
        <td><input type="text" name="title" size="60" value="<?php echo htmlentities($data->title); ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SLUG'); ?></td>
        <td><input type="text" name="slug" size="60" value="<?php echo $data->slug; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('TYPE'); ?></td>
        <td>
          <?php echo $this->type; ?>
          <span id="avs_help">
          	<a href="http://allvideoshare.mrvinoth.com/forum/7-adding-videos/3-video-uploading-issues" target="_blank"><?php echo JText::_('GENERAL_UPLOAD_HELP'); ?></a>
          </span> 
        </td>
      </tr>
      <tr id="data_streamer">
        <td class="avskey"><?php echo JText::_('STREAMER'); ?></td>
        <td><input type="text" name="streamer" size="60" value="<?php echo $data->streamer; ?>" /></td>
      </tr>
      <tr id="url_data_video">
        <td class="avskey"><?php echo JText::_('VIDEO'); ?></td>
        <td><input type="text" name="video" size="60" value="<?php echo $data->video; ?>" /></td>
      </tr>
      <tr id="url_data_hd">
        <td class="avskey"><?php echo JText::_('HD_VIDEO'); ?></td>
        <td><input type="text" name="hd" size="60" value="<?php echo $data->hd; ?>" /></td>
      </tr>
      <tr id="url_data_thumb">
        <td class="avskey"><?php echo JText::_('THUMB'); ?></td>
        <td><input type="text" name="thumb" size="60" value="<?php echo $data->thumb; ?>" /></td>
      </tr>
      <tr id="url_data_preview">
        <td class="avskey"><?php echo JText::_('PREVIEW'); ?></td>
        <td><input type="text" name="preview" size="60" value="<?php echo $data->preview; ?>" /></td>
      </tr>
      <tr id="upload_data_video">
        <td class="avskey"><?php echo JText::_('VIDEO'); ?></td>
        <td id="upload_video"><?php if($data->video) { ?>
          <input name="upload_video" readonly="readonly" value="<?php echo $data->video; ?>" size="47" />
          <input type="button" name="change" value="Change" onclick="changeMode('video')" />
          <?php } else { ?>
          <input type="file" name="upload_video" maxlength="100" />
          <?php } ?>
        </td>
      </tr>
      <tr id="upload_data_hd">
        <td class="avskey"><?php echo JText::_('HD_VIDEO'); ?></td>
        <td id="upload_hd"><?php if($data->hd) { ?>
          <input name="upload_hd" readonly="readonly" value="<?php echo $data->hd; ?>" size="47" />
          <input type="button" name="change" value="Change" onclick="changeMode('hd')" />
          <?php } else { ?>
          <input type="file" name="upload_hd" maxlength="100" />
          <?php } ?>
        </td>
      </tr>
      <tr id="upload_data_thumb">
        <td class="avskey"><?php echo JText::_('THUMB'); ?></td>
        <td id="upload_thumb"><?php if($data->thumb) { ?>
          <input name="upload_thumb" readonly="readonly" value="<?php echo $data->thumb; ?>" size="47" />
          <input type="button" name="change" value="Change" onclick="changeMode('thumb')" />
          <?php } else { ?>
          <input type="file" name="upload_thumb" maxlength="100" />
          <?php } ?>
        </td>
      </tr>
      <tr id="upload_data_preview">
        <td class="avskey"><?php echo JText::_('PREVIEW'); ?></td>
        <td id="upload_preview"><?php if($data->preview) { ?>
          <input name="upload_preview" readonly="readonly" value="<?php echo $data->preview; ?>" size="47" />
          <input type="button" name="change" value="Change" onclick="changeMode('preview')" />
          <?php } else { ?>
          <input type="file" name="upload_preview" maxlength="100" />
          <?php } ?>
        </td>
      </tr>
      <tr id="data_token">
        <td class="avskey"><?php echo JText::_('TOKEN'); ?></td>
        <td><input type="text" name="token" size="60" value="<?php echo $data->token; ?>" /></td>
      </tr>
      <tr id="data_dvr">
        <td class="avskey"><?php echo JText::_('DVR'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('dvr', $data->dvr); ?></td>
      </tr>
      <tr id="data_thirdparty">
        <td class="avskey"><?php echo JText::_('THIRD_PARTY_EMBEDCODE'); ?></td>
        <td><textarea name="thirdparty" rows="6" cols="50" ><?php echo $data->thirdparty; ?></textarea></td>
      </tr>
      <tr>
        <td class="avskey" valign="top" style="padding-top:10px;"><?php echo JText::_('DESCRIPTION'); ?></td>
        <td><?php echo AllVideoShareFallback::getEditor('description', $data->description); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SELECT_A_CATEGORY'); ?></td>
        <td><?php echo $this->category; ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('ACCESS');?></td>
        <td><?php echo $this->access; ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('FEATURED'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('featured', $data->featured); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('PUBLISH'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('published', $data->published); ?></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('SEO_SETTINGS'), 'seosettingstab', true); ?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('META_KEYWORDS'); ?></td>
        <td><textarea name="tags" rows="3" cols="50" ></textarea></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('META_DESCRIPTION'); ?></td>
        <td><textarea name="metadescription" rows="6" cols="50" ></textarea></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::endTabs(); ?>
    <input type="hidden" name="boxchecked" value="1">
    <input type="hidden" name="option" value="com_allvideoshare" />
    <input type="hidden" name="view" value="videos" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="user" value="<?php echo $data->user; ?>" />
    <input type="hidden" name="id" value="<?php echo $data->id; ?>">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<script type="text/javascript">
var form            = document.adminForm;
var type            = document.getElementById("type");
var videoExtensions = ['flv', 'mp4' , '3g2', '3gp', 'aac', 'f4b', 'f4p', 'f4v', 'm4a', 'm4v', 'mov', 'sdp', 'vp6', 'smil'];
var imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
var isAllowed       = true;
changeType('<?php echo $data->type; ?>');

if(<?php echo substr(JVERSION,0,3); ?> != '1.5') {
	Joomla.submitbutton = submitbutton;
}
	
function submitbutton(pressbutton){ 	
	if(pressbutton == 'save' || pressbutton == 'apply') {	
		if(valForm() == false) return;
	}
	submitform( pressbutton );	
	return;
}

function valForm() {
	var method = type.options[type.selectedIndex].value;
	
	if(form.title.value == '') {
       	alert( "<?php echo JText::_( 'TITLE_FIELD_SHOULD_NOT_BE_EMPTY', true); ?>" );
       	return false;
	}
	
	if(method == 'upload') {
		if(form.upload_video.value == '') {
       		alert( "<?php echo JText::_( 'YOU_MUST_ADD_A_VIDEO', true); ?>" );
       		return false;
	    } else {
			isAllowed = checkExtension('VIDEO', form.upload_video.value, videoExtensions);
			if(isAllowed == false) 	return false;
		}
		
		if(form.upload_hd.value) {
			isAllowed = checkExtension('HD VIDEO', form.upload_hd.value, videoExtensions);
			if(isAllowed == false) 	return false;
		}
		
		if(form.upload_thumb.value) {
       		isAllowed = checkExtension('THUMB', form.upload_thumb.value, imageExtensions);
			if(isAllowed == false) 	return false;
		}
		
		if(form.upload_preview.value) {
			isAllowed = checkExtension('PREVIEW', form.upload_preview.value, imageExtensions);
			if(isAllowed == false) 	return false;
		}
	} else if(method == 'url') {
		if(form.video.value == '') {
       		alert( "<?php echo JText::_( 'YOU_MUST_ADD_A_VIDEO', true); ?>" );
       		return false;
	    } else {
			isAllowed = checkExtension('VIDEO', form.video.value, videoExtensions);
			if(isAllowed == false) 	return false;
		}
		
		if(form.hd.value) {
			isAllowed = checkExtension('HD VIDEO', form.hd.value, videoExtensions);
			if(isAllowed == false) 	return false;
		}
		
		if(form.preview.value) {
			isAllowed = checkExtension('PREVIEW', form.preview.value, imageExtensions);
			if(isAllowed == false) 	return false;
		}
	} else if(method == 'rtmp') {
		if(form.streamer.value == '') {
       		alert( "<?php echo JText::_( 'YOU_MUST_ADD_THE_STREAMER_PATH', true); ?>" );
       		return false;
	    }
	} else if(method == 'thirdparty') {
		if(form.thirdparty.value == '') {
       		alert( "<?php echo JText::_( 'YOU_MUST_ADD_ANY_THIRD_PARTY_EMBEDCODE', true); ?>" );
       		return false;
	    }
	}	
	
	if(method != 'upload' && method != 'youtube') {
		if(form.thumb.value) {
       		isAllowed = checkExtension('THUMB', form.thumb.value, imageExtensions);
			if(isAllowed == false) 	return false;
		}
	}
	
	if(form.category.value == '') {
		alert( "<?php echo JText::_( 'YOU_HAVE_NOT_SELECTED_ANY_CATEGORY_FOR_THE_VIDEO', true); ?>" );
       	return false;
	}
}

function checkExtension(type, filePath, validExtensions) {
	var ext = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

    for(var i = 0; i < validExtensions.length; i++) {
        if(ext == validExtensions[i]) return true;
    }

    alert(type + ' :   The file extension ' + ext.toUpperCase() + ' is not allowed!');
    return false;	
}

function changeType(typ) {
	document.getElementById('url_data_video').style.display              = "none";
	document.getElementById('url_data_hd').style.display                 = "none";
	document.getElementById('url_data_thumb').style.display              = "none";
	document.getElementById('url_data_preview').style.display            = "none";
	document.getElementById('upload_data_video').style.display           = "none";
	document.getElementById('upload_data_hd').style.display              = "none";
	document.getElementById('upload_data_thumb').style.display           = "none";
	document.getElementById('upload_data_preview').style.display         = "none";
	document.getElementById('data_streamer').style.display               = "none";
	document.getElementById('data_token').style.display                  = "none";
	document.getElementById('data_dvr').style.display                    = "none";
	document.getElementById('data_thirdparty').style.display             = "none";
	document.getElementById('avs_help').style.display                    = "none";
    switch(typ) {
		case 'url' :
			document.getElementById('url_data_video').style.display      = "";
			document.getElementById('url_data_hd').style.display         = "";
			document.getElementById('url_data_thumb').style.display      = "";
			document.getElementById('url_data_preview').style.display    = "";
			break;
		case 'upload':
			document.getElementById('upload_data_video').style.display   = "";
			document.getElementById('upload_data_hd').style.display      = "";
			document.getElementById('upload_data_thumb').style.display   = "";
			document.getElementById('upload_data_preview').style.display = "";
			document.getElementById('avs_help').style.display            = "";
			break;
		case 'youtube'  :
		case 'highwinds':
		case 'lighttpd' :
			document.getElementById('url_data_video').style.display      = "";
			document.getElementById('url_data_thumb').style.display      = "";
			document.getElementById('url_data_preview').style.display    = "";
			break;
		case 'rtmp':
			document.getElementById('url_data_video').style.display      = "";
			document.getElementById('url_data_thumb').style.display      = "";
			document.getElementById('url_data_preview').style.display    = "";
			document.getElementById('data_streamer').style.display       = "";
			document.getElementById('data_token').style.display          = "";
			break;
		case 'bitgravity':
			document.getElementById('url_data_video').style.display      = "";
			document.getElementById('url_data_thumb').style.display      = "";
			document.getElementById('url_data_preview').style.display    = "";
			document.getElementById('data_dvr').style.display            = "";
			break;
		case 'thirdparty':
			document.getElementById('url_data_thumb').style.display      = "";
			document.getElementById('data_thirdparty').style.display     = "";
			break;
	}	
}

function changeMode(inp) {
    var mode;
    mode='<input type="file" name="upload_' + inp + '" maxlength="100" />';
	document.getElementById('upload_' + inp).innerHTML = mode;
}
</script>