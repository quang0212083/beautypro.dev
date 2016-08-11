<?php 

/*
 * @version		$Id: default_videos.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access'); 

$target = JURI::getInstance()->toString();
$qstr   = (!strpos($target, '?')) ? '?' : '&';

$session = JFactory::getSession();
$session->set('target', $target);

?>
<style type="text/css">
#tbl table tr, #tbl table th, #tbl table td {
	border:none;
	padding:5px;
}
</style>
<div style="float:right; margin-bottom:7px;"> 
	<a href="<?php echo JRoute::_( $target.$qstr.'add=1' ); ?>"><strong>Add New Video</strong></a> 
</div>
<div style="clear:both;" id="tbl">
  <table cellpadding="0px" cellspacing="0px" border="0">
    <thead bgcolor="#FOFOFO">
      <tr>
        <th width="5%" align="center">#</th>
        <th width="30%" style="text-align:left;">Title</th>
        <th width="15%" style="text-align:left;">Category</th>
        <th width="15%" style="text-align:left;">Video</th>
        <th width="8%" align="center">Edit</th>
        <th width="8%" align="center">Delete</th>
        <th width="8%" align="center">Approved</th>
      </tr>
    </thead>
    <tbody>
      <?php
		$k = 0;
		for ($i=0, $n=count($data); $i < $n; $i++) {
			$row = $data[$i];
			
			$link 	   = JRoute::_( $target.$qstr.'uid='.$row->id );
			if($row->published == 1){
				$vid  	   = JRoute::_( 'index.php?option=com_hdwplayer&view=video&wid='.$row->id );	
			}else{
				$vid  	   = '';
			}		
			$delete    = JRoute::_( 'index.php?option=com_hdwplayer&view=delete&uid='.$row->id );
			$published = ($row->published == 1) ? 'Yes' : 'No';
		?>
      <tr class="<?php echo "row$k"; ?>">
        <td align="center"><?php echo $i+1; ?></td>
        <td><a href="<?php echo $link; ?>"> <?php echo $row->title;?></a></td>
        <td><?php echo $row->category; ?></td>
        <td><a href="<?php echo $vid; ?>"><?php echo $row->video; ?></a></td>
        <td align="center">
        	<a href="<?php echo $link; ?>">
            	<img src="<?php echo JURI::root().'components/com_hdwplayer/assets/edit.jpg'; ?>" border="0" />
            </a>
        </td>
        <td align="center">
        	<a href="<?php echo $delete; ?>">
            	<img src="<?php echo JURI::root().'components/com_hdwplayer/assets/delete.jpg'; ?>"  border="0" />
            </a>
        </td>
        <td align="center"><?php echo $published; ?></td>
      </tr>
      <?php
      }
  ?>
    </tbody>
    <tfoot bgcolor="#FOFOFO">
    </tfoot>
  </table>
</div>