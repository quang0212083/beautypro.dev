<?php

/*
 * @version		$Id: default.php 2.3.0 2014-06-21 $
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
        <td class="avskey"><?php echo JText::_('RESPONSIVE'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('responsive', $data->responsive); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('NO_OF_ROWS'); ?></td>
        <td><input type="text" name="rows" value="<?php echo $data->rows; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('NO_OF_COLS'); ?></td>
        <td><input type="text" name="cols" value="<?php echo $data->cols; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('THUMBNAIL_WIDTH'); ?></td>
        <td><input type="text" name="thumb_width" value="<?php echo $data->thumb_width; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('THUMBNAIL_HEIGHT'); ?></td>
        <td><input type="text" name="thumb_height" value="<?php echo $data->thumb_height; ?>" /></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('VIDEO_PAGE_SETTINGS'), 'videopagesettings', true); ?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('SELECT_THE_PLAYER'); ?></td>
        <td><?php echo $this->playerid; ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('LAYOUT_TYPE'); ?></td>
        <td><?php echo $this->layout; ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SHOW_VIDEO_TITLE'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('title', $data->title); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SHOW_VIDEO_DESCRIPTION'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('description', $data->description); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SHOW_CATEGORY_NAME'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('category', $data->category); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SHOW_VIEW_COUNT'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('views', $data->views); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('SHOW_SEARCH_BOX'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('search', $data->search); ?></td>
      </tr>
    </table>
    <div id="data_comments">      
      <table class="admintable">
        <tr>
          <td class="avskey"></td>
          <td><h2><?php echo JText::_('COMMENTS_SETTINGS'); ?></h2></td>
        </tr>
      	<tr>
          <td class="avskey"><?php echo JText::_('COMMENTS_TYPE'); ?></td>
          <td>
		  	<?php echo $this->comments_type; ?>
            <span id="avs_help"> 
          		<a href="http://allvideoshare.mrvinoth.com/forum/15-third-party-plugins/2757-jcomments-integration" target="_blank"><?php echo JText::_('LEARN_USING_JCOMMENTS_WITH_ALLVIDEOSHARE'); ?></a>
          	</span>
          </td>
        </tr>
        <tr id="data_facebook_id">
          <td class="avskey"><?php echo JText::_('FACEBOOK_APP_ID'); ?></td>
          <td><input type="text" name="fbappid" value="<?php echo $data->fbappid; ?>" /></td>
      	</tr> 
        <tr id="data_comments_posts">
          <td class="avskey"><?php echo JText::_('NO_OF_POSTS'); ?></td>
          <td><input type="text" name="comments_posts" value="<?php echo $data->comments_posts; ?>" /></td>
        </tr>
        <tr id="data_comments_color">
          <td class="avskey"><?php echo JText::_('COLOR_SCHEME'); ?></td>
          <td><?php echo $this->comments_color; ?></td>
        </tr>
      </table>
    </div>
    <?php AllVideoShareFallback::initPanel(JText::_('FRONT_END_USER_SETTINGS'), 'frontendusersettings', true); ?>
    <table class="admintable">
      <tr>        
        <td class="avskey"><?php echo JText::_('AUTO_APPROVE_USER_ADDED_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('auto_approval', $data->auto_approval); ?></td>
      </tr>
      <tr>        
        <td class="avskey"><?php echo JText::_('ALLOW_USERS_TO_ADD_YOUTUBE_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('type_youtube', $data->type_youtube); ?></td>
      </tr>
      <tr>        
        <td class="avskey"><?php echo JText::_('ALLOW_USERS_TO_ADD_RTMP_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('type_rtmp', $data->type_rtmp); ?></td>
      </tr>
      <tr>        
        <td class="avskey"><?php echo JText::_('ALLOW_USERS_TO_ADD_LIGHTTPD_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('type_lighttpd', $data->type_lighttpd); ?></td>
      </tr>
      <tr>        
        <td class="avskey"><?php echo JText::_('ALLOW_USERS_TO_ADD_HIGHWINDS_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('type_highwinds', $data->type_highwinds); ?></td>
      </tr>
      <tr>        
        <td class="avskey"><?php echo JText::_('ALLOW_USERS_TO_ADD_BITGRAVITY_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('type_bitgravity', $data->type_bitgravity); ?></td>
      </tr>
      <tr>        
        <td class="avskey"><?php echo JText::_('ALLOW_USERS_TO_ADD_THIRDPARTY_VIDEOS'); ?></td>
        <td>
        	<input type="checkbox" name="type_thirdparty" value="1" <?php if($data->type_thirdparty == 1) echo 'checked="checked"'; ?> onchange="secWarning(this.checked);" />
            &nbsp;<?php echo JText::_('DEPRECATED'); ?>
        </td>
      </tr>
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('FRONT_END_STYLESHEET'), 'frontendstylesheet', true); ?>
    <table class="admintable">
      <tr>
        <td><textarea name="css" style="width:99.5%; height:500px;" rows="40" cols="200"><?php echo $data->css; ?></textarea></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::endTabs(); ?>
    <input type="hidden" name="boxchecked" value="1">
    <input type="hidden" name="option" value="com_allvideoshare" />
    <input type="hidden" name="view" value="config" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="1">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<script type="text/javascript">
var form = document.adminForm;
changeComments('<?php echo $data->comments_type; ?>');
changeType('<?php echo $data->layout; ?>');

if(<?php echo substr(JVERSION,0,3); ?> != '1.5') {
	Joomla.submitbutton = submitbutton;
}
	
function submitbutton(pressbutton){ 	
	submitform( pressbutton );	
	return;
}

function changeType(typ) {
	document.getElementById('data_comments').style.display = "";	
	switch(typ) {
		case 'relatedvideos' :
		case 'none'          :
			document.getElementById('data_comments').style.display = "none";
			break;
	}	
}

function changeComments(typ) {
	if( typ == 'facebook' ) {
		document.getElementById('avs_help').style.display = "none";
		display = '';	
	} else if(typ == 'komento' ) {
		document.getElementById('avs_help').style.display = "none";
		display = 'none';	
	} else {
		document.getElementById('avs_help').style.display = "";
		display = 'none';
	}
	document.getElementById('data_facebook_id').style.display = display;
	document.getElementById('data_comments_posts').style.display = display;
	document.getElementById('data_comments_color').style.display = display;	
}

function secWarning(val) {
	if(val == true) {
		alert("<?php echo JText::_( 'THIRD_PARTY_SECURITY_WARNING', true); ?>");
	}
}
</script>