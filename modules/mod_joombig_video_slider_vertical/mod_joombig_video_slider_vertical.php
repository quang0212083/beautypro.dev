<?php
/**
* @title		joombig video slider vertical module
* @website		http://www.joombig.com
* @copyright	Copyright (C) 2014 joombig.com. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/
    // no direct access
    defined('_JEXEC') or die('Restricted access');
	$mosConfig_absolute_path = JPATH_SITE;
	$mosConfig_live_site = JURI :: base();
	if(substr($mosConfig_live_site, -1)=="/") { $mosConfig_live_site = substr($mosConfig_live_site, 0, -1); }

    $module_name             = basename(dirname(__FILE__));
    $module_dir              = dirname(__FILE__);
    $module_id               = $module->id;
    $document                = JFactory::getDocument();
    $style                   = $params->get('sp_style');

    if( empty($style) )
    {
        JFactory::getApplication()->enqueueMessage( 'Slider style no declared. Check joombig video slider vertical configuration and save again from admin panel' , 'error');
        return;
    }

    $layoutoverwritepath     = JURI::base(true) . '/templates/'.$document->template.'/html/'. $module_name. '/tmpl/'.$style;
    $document                = JFactory::getDocument();
    require_once $module_dir.'/helper.php';
    $helper = new mod_Videoslidervertical($params, $module_id);
    $data = (array) $helper->display();
	
	$enable_jQuery			= $params->get('enable_jQuery', 1);
    $video_skin             = $params->get('video_skin',"1");
	if ($video_skin == "1"){
		$data_skin = "vertical";
	}
	else
	{
		$data_skin = "hozizontal";
	}
	$width					= $params->get('width', "100%");
    $autoslide             = $params->get('autoslide',"1");
    $autoplayvideo             = $params->get('autoplayvideo',"1");
	
	$width_vthumb			= $params->get('width_vthumb', "148");
	$height_vthumb			= $params->get('height_vthumb', "48");
	
	$width_hthumb			= $params->get('width_hthumb', "64");
	$height_hthumb			= $params->get('height_hthumb', "48");
	
    //$option = (array) $params->get('animation')->$style;
    if(  is_array( $helper->error() )  )
    {
        JFactory::getApplication()->enqueueMessage( implode('<br /><br />', $helper->error()) , 'error');
    } else {
        if( file_exists($layoutoverwritepath.'/view.php') )
        {
            require(JModuleHelper::getLayoutPath($module_name, $layoutoverwritepath.'/view.php') );   
        } else {
            require(JModuleHelper::getLayoutPath($module_name, $style.'/view') );   
        }

        $helper->setAssets($document, $style);
}