<?php

/*
 * @version		$Id: router.php 3.1 2012-11-28 $
 * @package		Joomla
 * @copyright   Copyright (C) 2012-2013 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

function HdwplayerBuildRoute( &$query )
{
	$segments = array();
   
    if(isset($query['view'])) {
    	$segments[] = $query['view'];
        unset( $query['view'] );
    }
	
	if(isset($query['orderby'])) {
		if($query['orderby'] != 'default') {
			$segments[] = $query['orderby'];
		}
    	unset( $query['orderby'] );	
	}
	
    if(isset($query['wid'])) {
    	$segments[] = $query['wid'];
        unset( $query['wid'] );
    }
	
    return $segments;
}

function HdwplayerParseRoute( $segments )
{
	$vars  = array();
	$order = array('default', 'latest', 'popular', 'random', 'featured');
	$count = count( $segments );

	if( $count >= 1 && $segments[0] ) {
    	$vars['view'] = $segments[0];
    }
	
	if( $count == 2 ) {
		if( in_array($segments[1], $order) ) {
			$vars['orderby'] = $segments[1];
		} else {
			$vars['wid'] = $segments[1];
		}
	} else if($count == 3) {
		$vars['orderby'] = $segments[1];
		$vars['wid'] = $segments[2];    	
	}

    return $vars;
}

?>