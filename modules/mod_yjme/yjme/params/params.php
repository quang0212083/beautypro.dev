<?php
/**
 * @package		YJ Module Engine
 * @author		Youjoomla.com
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla.com.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
/*Those are changable module params.They will not affect the news engines.These params are dinamic. You can add more or remove the ones that are here. Do not forget to edit/remove the xml param tags for the params changed/added. Also remove the conditions for the param in module template default.php file*/
defined('_JEXEC') or die('Restricted access');
	
	
	$show_title   			 	= $params->get   ('show_title');			// Disable/enable item title
	$show_img    			 	= $params->get   ('show_img');				// Disable/enable item image
	$img_align					= $params->get   ('img_align');				// Image align. see the array below 
	$img_width					= $params->get	 ('img_width');				// Image width
	$img_height					= $params->get	 ('img_height');			// Image height
	$show_intro   			 	= $params->get   ('show_intro');			// Disable/enable intro text
	$show_cat_title				= $params->get   ('show_cat_title');		// Disable/enable category title
	$show_date   			 	= $params->get   ('show_date');				// Disable/enable item date
	$author_name				= $params->get   ('author_name');			// Author name display usarename/real name
	$show_author				= $params->get   ('show_author');			// Disable/enable author name
	$show_read    			 	= $params->get   ('show_read');				// Disable/enable read more link
	$show_rating    			= $params->get   ('show_rating');			// Disable/enable rating stars

/* image align */
$alig = array(
	1=>'left',
	2=>'right',
	3=>'none'
	);
$align = $alig[$img_align];	

/*the headfile.php is moved here in case you need to do some calulations before output or you have params created for your inline JS. This way the headfiles.php sees the params before the load.*/
	require('modules/'.$yj_mod_name.'/yjme/headfiles.php');
?>