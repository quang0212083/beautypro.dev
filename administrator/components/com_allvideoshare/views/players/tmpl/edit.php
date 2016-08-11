<?php

/*
 * @version		$Id: add.php 2.3.0 2014-06-21 $
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
        <td class="avskey"><?php echo JText::_('NAME'); ?></td>
        <td><input type="text" name="name" size="60" value="<?php echo $data->name; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('WIDTH'); ?></td>
        <td><input type="text" name="width" size="60" value="<?php echo $data->width; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('HEIGHT'); ?></td>
        <td><input type="text" name="height" size="60" value="<?php echo $data->height; ?>" /></td>
      </tr>      
      <tr>
        <td class="avskey"><?php echo JText::_('BUFFER_TIME'); ?></td>
        <td><input type="text" name="buffer" size="60" value="<?php echo $data->buffer; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('VOLUME_LEVEL'); ?></td>
        <td><input type="text" name="volumelevel" size="60" value="<?php echo $data->volumelevel; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('STRETCH'); ?></td>
        <td>
          <select id="stretch" name="stretch" >
            <option value="uniform" id="uniform"><?php echo JText::_('UNIFORM'); ?></option>
            <option value="fill" id="fill"><?php echo JText::_('FILL'); ?></option>
            <option value="original" id="original"><?php echo JText::_('ORIGINAL'); ?></option>
            <option value="exactfit" id="exactfit"><?php echo JText::_('EXACT_FIT'); ?></option>
          </select>
          <?php echo '<script>document.getElementById("'.$data->stretch.'").selected="selected"</script>'; ?> </td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('LOOP'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('loop', $data->loop); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('AUTOSTART'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('autostart', $data->autostart); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('PUBLISH'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('published', $data->published, 1); ?></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('ENABLE_OR_DISABLE_SKIN_ELEMENTS'), 'skinelementstab', true); ?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('CONTROLBAR'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('controlbar', $data->controlbar); ?></td>
      </tr>     
      <tr>
        <td class="avskey"><?php echo JText::_('DURATION_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('durationdock', $data->durationdock); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('TIMER_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('timerdock', $data->timerdock); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('FULLSCREEN_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('fullscreendock', $data->fullscreendock); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('HD_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('hddock', $data->hddock); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('EMBED_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('embeddock', $data->embeddock); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('FACEBOOK_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('facebookdock', $data->facebookdock); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('TWITTER_DOCK'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('twitterdock', $data->twitterdock); ?></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('COLOR_YOUR_SKIN'), 'coloryourskintab', true); ?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('CONTROLBAR_OUTLINE_COLOR'); ?></td>
        <td><input type="text" name="controlbaroutlinecolor" size="60" value="<?php echo $data->controlbaroutlinecolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('CONTROLBAR_BG_COLOR'); ?></td>
        <td><input type="text" name="controlbarbgcolor" size="60" value="<?php echo $data->controlbarbgcolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('CONTROLBAR_OVERLAY_COLOR'); ?></td>
        <td><input type="text" name="controlbaroverlaycolor" size="60" value="<?php echo $data->controlbaroverlaycolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('CONTROLBAR_OVERLAY_ALPHA'); ?></td>
        <td><input type="text" name="controlbaroverlayalpha" size="60" value="<?php echo $data->controlbaroverlayalpha; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('ICON_COLOR'); ?></td>
        <td><input type="text" name="iconcolor" size="60" value="<?php echo $data->iconcolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('PROGRESSBAR_BG_COLOR'); ?></td>
        <td><input type="text" name="progressbarbgcolor" size="60" value="<?php echo $data->progressbarbgcolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('PROGRESSBAR_BUFFER_COLOR'); ?></td>
        <td><input type="text" name="progressbarbuffercolor" size="60" value="<?php echo $data->progressbarbuffercolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('PROGRESSBAR_SEEK_COLOR'); ?></td>
        <td><input type="text" name="progressbarseekcolor" size="60" value="<?php echo $data->progressbarseekcolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('VOLUMEBAR_BG_COLOR'); ?></td>
        <td><input type="text" name="volumebarbgcolor" size="60" value="<?php echo $data->volumebarbgcolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('VOLUMEBAR_SEEK_COLOR'); ?></td>
        <td><input type="text" name="volumebarseekcolor" size="60" value="<?php echo $data->volumebarseekcolor; ?>" /></td>
      </tr>      
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('RELATED_VIDEOS_INSIDE_THE_PLAYER'), 'relatedvideostab', true); ?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('RELATED_VIDEOS'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('playlist', $data->playlist); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('RELATED_VIDEOS_BG_COLOR'); ?></td>
        <td><input type="text" name="playlistbgcolor" size="60" value="<?php echo $data->playlistbgcolor; ?>" /></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('CUSTOM_PLAYER_PAGE'); ?></td>
        <td><input type="text" name="customplayerpage" size="60" value="<?php echo $data->customplayerpage; ?>" />
          <span id="avs_help"> <a href="http://allvideoshare.mrvinoth.com/custom-player-page-url" target="_blank"><?php echo JText::_('WHAT_IS_THIS'); ?></a> </span> 
        </td>
      </tr>
    </table>
    <?php AllVideoShareFallback::initPanel(JText::_('ADVERTISEMENTS'), 'advertisementstab', true); ?>
    <table class="admintable">
      <tr>
        <td class="avskey"><?php echo JText::_('PREROLL'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('preroll', $data->preroll); ?></td>
      </tr>
      <tr>
        <td class="avskey"><?php echo JText::_('POSTROLL'); ?></td>
        <td><?php echo AllVideoShareUtils::ListBoolean('postroll', $data->postroll); ?></td>
      </tr>
    </table>
    <?php AllVideoShareFallback::endTabs(); ?>
    <input type="hidden" name="boxchecked" value="1">
    <input type="hidden" name="option" value="com_allvideoshare" />
    <input type="hidden" name="view" value="players" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="<?php echo $data->id; ?>">
    <?php echo JHTML::_( 'form.token' ); ?>
  </form>
</div>
<script type="text/javascript">
var form = document.adminForm;

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
	if(form.name.value == '') {
       	alert( "<?php echo JText::_( 'NAME_FIELD_SHOULD_NOT_BE_EMPTY', true); ?>" );
       	return false;
	}
	
	if(form.width.value == '') {
       	alert( "<?php echo JText::_( 'WIDTH_FIELD_SHOULD_NOT_BE_EMPTY', true); ?>" );
       	return false;
	}
	
	if(form.height.value == '') {
       	alert( "<?php echo JText::_( 'HEIGHT_FIELD_SHOULD_NOT_BE_EMPTY', true); ?>" );
       	return false;
	}
	
	if(form.playlist.value == 1 && form.width.value < 320 || form.height.value < 240) {
       	alert( "<?php echo JText::_( 'YOUR_PLAYER_SIZE_SHOULD_BE_ATLEAST_320X240_TO_HAVE_THE_RELATED_VIDEOS', true); ?>" );
       	return false;
	}
}
</script>