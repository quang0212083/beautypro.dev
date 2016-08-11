<?php
/*
 * @version		$Id: default_left.php 1.0 2013-08-21 $
 * @package		Joomla
 * @subpackage	com_hdwplayer
 * @copyright   Copyright (C) 2013 HDWPlayer. All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$datas = $this->data;

?>
<style type="text/css">
.icon {
	text-align: center;
	margin-right: 9px;
	float: left;
	margin-bottom: 5px;
}
.icon a:hover {
	border-left: 1px solid #E0F2F7;
	border-top: 1px solid #E0F2F7;
	border-right: 1px solid #CEECF5;
	border-bottom: 1px solid #CEECF5;
	background: #EFF8FB;
	color: #0B55C4;
}
img {
	padding: 10px 0;
	margin: 0 auto;
}
.icon a {
	background-color:#ffffff;
	display: block;
	float: left;
	border: 1px solid #E0F2F7;
	height: 97px;
	width: 107px;
	color: #333;
	vertical-align: middle;
	text-decoration: none;
}
.icon span {
	display: block;
	text-align: center;
	font-weight:bold;
}
span {
	display: block;
}
span.note {
	display: block;
	background:#ffffff;
	border:0px solid #e7e7e7;
	color:#333;
	margin:8px 0pt;
	padding:8px 10px 8px 10px;
	text-align:left;
}
.server-setting .row0{
	background: #CEECF5;
	
}
.server-setting .row0 td, .server-setting .row1 td{
	padding:10px;
	font-weight:bold;
	
}
.server-setting  .row1{
	background: #ffffff;
}
.adminlist .heading{
	padding-bottom:10px;
	font-size:15px;
}
.server-setting legend{
margin-bottom: 0px;
border-bottom: 1px solid #E0F2F7;
}
</style>
<div style="float:left; ">
  <div class="icon"> <a title="Settings" href="index.php?option=com_hdwplayer&amp;view=settings"> <img alt="<?php echo JText::_('Settings'); ?>" src="components/com_hdwplayer/assets/setting.png" /> <span><?php echo JText::_('Settings'); ?></span> </a> </div>
</div>
<div style="float:left;">
  <div class="icon"> <a title="Videos" href="index.php?option=com_hdwplayer&amp;view=videos"> <img alt="<?php echo JText::_('Videos'); ?>" src="components/com_hdwplayer/assets/video.png" /> <span><?php echo JText::_('Videos'); ?></span> </a> </div>
</div>
<div style="float:left;">
  <div class="icon"> <a title="Category" href="index.php?option=com_hdwplayer&amp;view=category"> <img alt="<?php echo JText::_('Category'); ?>" src="components/com_hdwplayer/assets/category.png" /> <span><?php echo JText::_('Category'); ?></span> </a> </div>
</div>
<div style=" clear:both"></div>
<fieldset class="server-setting" style="margin-top:5px; padding:10px;">
<legend>Server Information</legend>
<table class="adminlist" style="color:#333; width:100%;">
  <thead>
    <tr>
      <th class="heading" width="60%">Check</th>
      <th class="heading" width="40%">Result</th>
    </tr>
  </thead>
  <?php
		$k = 0;
		
		for ($i=0, $n=count($datas); $i < $n; $i++) {
			$row    = $datas[$i];
			$k          = $i % 2;
			$color  = ($row['value'] == 'NO') ? '#FF0000' : '#339900';
			$status = $row['value'];
		?>
  <tr class="<?php echo "row$k"; ?>">
    <td style="padding-left:10px;"><strong><?php echo $row['name']; ?></strong></td>
    <td  align="center" style="color:<?php echo $color; ?>"><?php echo $status; ?></td>
  </tr>
  <?php
      }
   
  ?>
</table>
<span class="note"><strong>Note :</strong> In-case you are not able to upload your videos successfully, HDW Player asks you to copy the above Server Information Details and mail-to <strong>support@hdwplayer.com </strong>
</span>
</fieldset>