<?php 
/**
 * @package Videogallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/

defined('_JEXEC') or die('Restircted access');

require_once JPATH_SITE.'/components/com_videogallerylite/helpers/helper.php';

$id_15 = JRequest::getVar('videogallerylite',   $this -> videogall_id , '', 'int');



$cis_class = new VideogallerylitesHelper;
$cis_class->videogallery_id = $id_15;
$cis_class->type = 'component';
$cis_class->class_suffix = '';
$cis_class->module_id =  $this -> videogall_id ;
echo $cis_class->render_html();
