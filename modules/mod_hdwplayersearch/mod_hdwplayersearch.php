<?php

/*
 * @version		$Id: mod_webplayersearch.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div align="center">
  <form action="<?php echo JRoute::_('index.php?option=com_hdwplayer&view=search'); ?>" name="hsearch" id="hsearch" method="post" enctype="multipart/form-data"  >
    <input type="text" name="hdwplayersearch" id="hdwplayersearch" style="width:75%" value="" />
    <input type="submit" name="search_btn" id="search_btn" value="Go" />
  </form>
</div>