<?php

/*
 * @version		$Id: default_right.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<div style="background-color:#fbfbfb; margin:0px 0px 0px 10px; padding:0px; text-align:center; height:385px;">
  <h1 style="padding-top:100px; margin:0px"><?php echo JText::_('YOU_HAVE_INSTALLED'); ?></h1>
  <div><?php echo JText::_('ALL_VIDEO_SHARE_VERSION'); ?></div>
  <table class="adminlist" style="width:90%; margin:10px auto;">
    <tbody>
      <tr class="row0">
      <td align="center">1.</td>
      <td class="left"><?php echo JText::_('WEBSITE'); ?></td>
      <td class="left">http://allvideoshare.mrvinoth.com/</td>
    </tr>
      <tr class="row1">
        <td align="center">2.</td>
        <td class="left"><?php echo JText::_('SUPPORT_MAIL'); ?></td>
        <td class="left">admin@mrvinoth.com</td>
      </tr>
      <tr class="row0">
        <td align="center">3.</td>
        <td class="left"><?php echo JText::_('FORUM_LINK'); ?></td>
        <td class="left"><a href="http://allvideoshare.mrvinoth.com/forum/" target="_blank">http://allvideoshare.mrvinoth.com/forum/</a></td>
      </tr>
    </tbody>
  </table>
  <div style="margin-top:5px;"><?php echo JText::_('ALL_VIDEO_SHARE_COPYRIGHTS'); ?></div>
</div>