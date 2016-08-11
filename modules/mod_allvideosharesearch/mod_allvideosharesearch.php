<?php

/*
 * @version		$Id: mod_allvideosharesearch.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$params->def('cache','0');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_allvideoshare/css/allvideoshare.css",'text/css',"screen");
$moduleclass_sfx = htmlspecialchars( $params->get('moduleclass_sfx') );

$view = JRequest::getCmd('view');
if($view == 'search') {
	$mainframe = JFactory::getApplication();
	$keyword = $mainframe->getUserStateFromRequest('avssearch', 'avssearch', '', 'string');
} else {
	$keyword = '';	
}
?>

<div align="center" class="avs_input_search<?php echo $moduleclass_sfx; ?>">
  <form action="<?php echo JRoute::_( "index.php?option=com_allvideoshare&view=search" ); ?>" name="hsearch" id="hsearch" method="post" enctype="multipart/form-data">
    <input type="hidden" name="option" value="com_allvideoshare"/>
    <input type="hidden" name="view" value="search"/>
    <input type="text" name="avssearch" id="avssearch" style="width:75%" value="<?php echo htmlspecialchars($keyword); ?>"/>
    <input type="submit" id="search_btn" class="btn" value="<?php echo JText::_('GO'); ?>" />
  </form>
</div>