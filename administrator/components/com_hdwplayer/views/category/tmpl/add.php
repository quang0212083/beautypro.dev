<?php

/*
 * @version		$Id: add.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
?>

<div id="hdwplayer">
  <form action="index.php?option=com_hdwplayer&view=category" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <div style="float:left; width:52%">
      <fieldset class="adminform">
      <legend>Category Name</legend>
      <table class="admintable">
        <tr>
          <td class="key">Name</td>
          <td><input type="text" name="name" size="60" /></td>
        </tr>
        <tr>
          <td class="key">Parent</td>
          <td><?php echo $this->parent; ?></td>
        </tr>
        <tr>
          <td class="key" rowspan="2" valign="top" style="padding-top:10px !important;">Category Image</td>
          <td style="font-weight:bold;"><input type="radio" name="type" value="Upload" checked="checked" onclick="changeType('Upload')" />
            Upload&nbsp;&nbsp;
            <input type="radio" name="type" value="Url" onclick="changeType('Url')" />
            URL </td>
        </tr>
        <tr id="categoryimage">
          <td><input type="text" name="image" size="60" /></td>
        </tr>
        <tr id="uploadcategoryimage">
          <td><div id="upload_image">
              <input type="file" name="uploadimage" maxlength="100" />
            </div></td>
        </tr>
        <tr>
          <td class="key">Publish</td>
          <td><input type="checkbox" name="published" value="1" checked="checked" /></td>
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
          <td><input type="text" name="metakeywords" size="60" /></td>
        </tr>
        <tr>
          <td class="key" valign="top" style="padding-top:10px !important;">Meta Description</td>
          <td><textarea name="metadescription" rows="8" cols="50" ></textarea></td>
        </tr>
      </table>
      </fieldset>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="task" value="add" />
    <input type="hidden" name="boxchecked" value="1" />
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<script type="text/javascript">
changeType('Upload');
 
if(<?php echo substr(JVERSION,0,3); ?> != '1.5') {
 	Joomla.submitbutton = submitbutton;
}

function submitbutton(pressbutton){
    var form = document.adminForm;
	var imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
	var isAllowed = true;

 	if(pressbutton == 'apply' || pressbutton == 'save') {
 		if (form.name.value == "") {
        	alert( "<?php echo JText::_( 'You must enter a Name for the Category', true); ?>" );
         	return;
     	}
		
		if(valButton(form.type) == 'Upload') {
			if(form.uploadimage.value) {
       			isAllowed = checkExtension('Category Image', form.uploadimage.value, imageExtensions);
				if(isAllowed == false) 	return false;
			}
		} else {
			if(form.image.value) {
       			isAllowed = checkExtension('Category Image', form.image.value, imageExtensions);
				if(isAllowed == false) 	return false;
			}
		}
	}
	 
	submitform( pressbutton );
	return;
}
 
function valButton(btn) {
	var cnt = -1;
    for (var i=btn.length-1; i > -1; i--) {
        if (btn[i].checked) {cnt = i; i = -1;}
    }
    if (cnt > -1) return btn[cnt].value;
    else return null;
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
	document.getElementById('uploadcategoryimage').style.display="none";
	document.getElementById('categoryimage').style.display="none";
    switch(typ) {
		case 'Upload':
			document.getElementById('uploadcategoryimage').style.display="";
			break;
		case 'Url':
			document.getElementById('categoryimage').style.display="";
			break;
	}	
}
</script>