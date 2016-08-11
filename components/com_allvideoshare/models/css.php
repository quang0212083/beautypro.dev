<?php

/*
 * @version		$Id: css.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );

class AllVideoShareModelCSS extends AllVideoShareModel {

    function __construct() {
		parent::__construct();
    }	
	
	function buildCSS() {
		 ob_clean();
		 header("content-type:text/css");
		 echo $this->getconfig();
		 exit();
	}
	
	function getconfig() {
         $db = JFactory::getDBO();
         $query = "SELECT * FROM #__allvideoshare_config";
         $db->setQuery( $query );
         $output = $db->loadObjectList();
         return( $output[0]->css );
	}
	
}