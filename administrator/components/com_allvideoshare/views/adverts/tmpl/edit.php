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
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('TITLE'); ?></td>
        <td><input type="text" name="title" size="60" value="<?php echo $data->title; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('TYPE'); ?></td>
        <td><?php echo $this->type; ?></td>
      </tr>
      <tr>
        <td class="avskey" rowspan="2"><?php echo JText::_('VIDEO'); ?></td>
        <td>
          <?php echo $this->method; ?>
          <span id="avs_help">
          	<a href="http://allvideoshare.mrvinoth.com/forum/7-adding-videos/3-video-uploading-issues" target="_blank"><?php echo JText::_('GENERAL_UPLOAD_HELP'); ?></a>
          </span> 
        </td>
      </tr>
      <tr id="url_data_video">
        <td><input type="text" name="video" size="60" value="<?php echo $data->video; ?>" /></td>
      </tr>
      <tr id="upload_data_video">
        <td id="upload_video"><?php if($data->video) { ?>
          <input name="upload_video" readonly="readonly" value="<?php echo $data->video; ?>" size="47" />
          <input type="button" name="change" value="Change" onclick="changeMode('video')" />
          <?php } else { ?>
          <input type="file" name="upload_video" maxlength="100" />
          <?php } ?>
        </td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('ADVERTISEMENT_LINK'); ?></td>
        <td><input type="text" name="link" size="60" value="<?php echo $data->link; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('PUBLISH'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('published', $data->published); ?></td>
      </tr>
    </table>
    <input type="hidden" name="boxchecked" value="1">
    <input type="hidden" name="option" value="com_allvideoshare" />
    <input type="hidden" name="view" value="adverts" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="<?php echo $data->id; ?>">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<script type="text/javascript">
var form            = document.adminForm;
var type            = document.getElementById("method");
var videoExtensions = ['flv', 'mp4' , '3g2', '3gp', 'aac', 'f4b', 'f4p', 'f4v', 'm4a', 'm4v', 'mov', 'sdp', 'vp6', 'smil'];
var isAllowed       = true;
changeMethod('<?php echo $data->method; ?>');

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
	} else {
		if(form.video.value == '') {
       		alert( "<?php echo JText::_( 'YOU_MUST_ADD_A_VIDEO', true); ?>" );
       		return false;
	    } else {
			isAllowed = checkExtension('VIDEO', form.video.value, videoExtensions);
			if(isAllowed == false) 	return false;
		}
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

function changeMethod(typ) {
	document.getElementById('url_data_video').style.display            = "none";
	document.getElementById('upload_data_video').style.display         = "none";
	document.getElementById('avs_help').style.display                  = "none";
    switch(typ) {
		case 'url' :
			document.getElementById('url_data_video').style.display    = "";
			break;
		case 'upload':
			document.getElementById('upload_data_video').style.display = "";
			document.getElementById('avs_help').style.display          = "";
			break;
	}	
}

function changeMode(inp) {
    var mode;
    mode='<input type="file" name="upload_' + inp + '" maxlength="100" />';
	document.getElementById('upload_' + inp).innerHTML = mode;
}
</script>