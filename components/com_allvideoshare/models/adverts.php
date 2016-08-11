<?php

/*
 * @version		$Id: adverts.php 2.3.0 2014-06-21 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2014 MrVinoth
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import libraries
require_once( JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_allvideoshare'.DS.'models'.DS.'model.php' );

class AllVideoShareModelAdverts extends AllVideoShareModel {

    function __construct() {
		parent::__construct();
    }
	
	function updateImpressions() {
		$id = JRequest::getInt('id');
		$db = JFactory::getDBO();
		$query = "UPDATE #__allvideoshare_adverts SET impressions = impressions + 1 WHERE id=".$id;
    	$db->setQuery( $query );
		$db->query();	
	}
	
	function updateClicks() {
		$id = JRequest::getInt('id');
		$db = JFactory::getDBO();
		$query = "UPDATE #__allvideoshare_adverts SET clicks = clicks + 1 WHERE id=".$id;
    	$db->setQuery( $query );
		$db->query();	
	}
		
}