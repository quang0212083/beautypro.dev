<?php
/**
 * @package Huge IT Video Gallery
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/
defined('_JEXEC') or die('Restircted access');


class VideogallerylitesHelper {
    private function add_scripts() {
        $document = JFactory::getDocument();
        //$document->addScript("http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js");
                JHtml::stylesheet(Juri::root() .'media/com_videogallerylite/style/css/font-awesome.min.css');
		JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/gallery-all.css');
		JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/style2-os.css');
		JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/lightbox.css');
                JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/css/font-awesome.css');
                $document->addScript("http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js");
		$document->addScript("http://code.jquery.com/ui/1.10.4/jquery-ui.js");
    }

    private function get_data() {
        $db = JFactory::getDBO();
        $id = $this->videogallery_id;
        $query = $db->getQuery(true);
        $query->select('*,#__huge_it_videogallery_videos.name as imgname,#__huge_it_videogallery_galleries.description as pousetimeDescription');
        $query->from('#__huge_it_videogallery_galleries,#__huge_it_videogallery_videos');
        $query->where('#__huge_it_videogallery_galleries.id ='.$id)->where('#__huge_it_videogallery_galleries.id = #__huge_it_videogallery_videos.videogallery_id');
        $query ->order('#__huge_it_videogallery_videos.ordering desc');
        $db->setQuery($query);
        $this->_data = $db->loadObjectList();
    }
    
    function youtube_or_vimeo($videourl){
        if(strpos($videourl,'youtube') !== false || strpos($videourl,'youtu') !== false){   
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videourl, $match)) {
                return 'youtube';
            }
    }
    elseif(strpos($videourl,'vimeo') !== false && strpos($videourl,'video') !== false) {
        $explode = explode("/",$videourl);
        $end = end($explode);
        if(strlen($end) == 8)
            return 'vimeo';
    }
    return 'image';
}

       
    function get($url) {
        if (ini_get('allow_url_fopen')) return file_get_contents($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    private function get_dataParams() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_params');
        $db->setQuery($query);
        $this->options_params = $db->loadObjectList();
    }
	
function get_video_gallery_id_from_url($url){
    if(strpos($url,'youtube') !== false || strpos($url,'youtu') !== false){ 
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            return array ($match[1],'youtube');
        }
    }else {
        $vimeoid =  explode( "/", $url );
        $vimeoid =  end($vimeoid);
        return array($vimeoid,'vimeo');
    }
}

    public function render_html() {
        ob_start();
        if ($this->type != 'plugin')
        $this->add_scripts();
        $this->get_data();
		
	
        $this->get_dataParams();
      
        $cis_options = array();
        $paramssld = array();
        foreach ($this->options_params as $rowpar) {
            $key = $rowpar->name;
            $value = $rowpar->value;
            $paramssld[$key] = $value;
        }
        for ($i = 0, $n = count($this->_data); $i < $n; $i++) {
            $cis_options[$this->_data[$i]->id][] = $this->_data[$i];
        }
        if (sizeof($cis_options) > 0) {
            reset($cis_options);
            $first_key = key($cis_options);
            $cis_options_value = $cis_options[$first_key][0];
            $images = $this->_data; 
            $videogalleryID = $cis_options_value->videogallery_id;
            $gallerytitle = $cis_options_value->sl_height;
            $gallerywidth = $cis_options_value->sl_width;
            $galleryeffect = $cis_options_value->videogallery_list_effects_s;
            $slidepausetime = ($cis_options_value->pousetimeDescription + $cis_options_value->param);
            $gallerypauseonhover = $cis_options_value->pause_on_hover;
            $galleryposition = $cis_options_value->sl_position;
            $slidechangespeed = $cis_options_value->param;
	    $gallerychangeview = $cis_options_value ->huge_it_sl_effects;


}

if($paramssld['light_box_open']  == 'on')$paramssld['light_box_open'] = "true"; else $paramssld['light_box_open']="false";
if($paramssld['light_box_overlayclose'] == 'on')$paramssld['light_box_overlayclose']="true";else $paramssld['light_box_overlayclose'] = "false";
if($paramssld['light_box_esckey'] == 'on')$paramssld['light_box_esckey']="true";else $paramssld['light_box_esckey'] = "false";
if($paramssld['light_box_loop'] == 'on')$paramssld['light_box_loop']="true";else $paramssld['light_box_loop'] = "false";
if($paramssld['light_box_loop'] == 'on')$paramssld['light_box_loop']="true";else $paramssld['light_box_loop'] = "false";
if($paramssld['light_box_returnfocus'] == 'on')$paramssld['light_box_returnfocus']="true";else $paramssld['light_box_returnfocus'] = "false";
if($paramssld['light_box_preloading'] == 'on')$paramssld['light_box_preloading']="true";else $paramssld['light_box_preloading'] = "false";
if($paramssld['light_box_html'] == 'on')$paramssld['light_box_html']="true";else $paramssld['light_box_html'] = "false";
if($paramssld['light_box_photo'] == 'on')$paramssld['light_box_photo']="true";else $paramssld['light_box_photo'] = "false";
if($paramssld['light_box_fixed'] == 'on')$paramssld['light_box_fixed']="true";else $paramssld['light_box_fixed'] = "false";



$paramssld["ht_view2_element_linkbutton_text"] = "View More";
$paramssld["ht_view2_element_show_linkbutton"] = "on";
$paramssld["ht_view2_element_linkbutton_color"] = "ffffff";
$paramssld["ht_view2_element_linkbutton_font_size"] = "14";
$paramssld["ht_view2_element_linkbutton_background_color"] = "FF2C2C";
$paramssld["ht_view2_show_popup_linkbutton"] = "on";
$paramssld["ht_view2_popup_linkbutton_text"] = "View More";
$paramssld["ht_view2_popup_linkbutton_background_hover_color"] = "c02121";
$paramssld["ht_view2_popup_linkbutton_background_color"] = "FF2C2C";
$paramssld["ht_view2_popup_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view2_popup_linkbutton_color"] = "ffffff";
$paramssld["ht_view2_popup_linkbutton_font_size"] = "14";
$paramssld["ht_view2_description_color"] = "222222";
$paramssld["ht_view2_description_font_size"] = "14";
$paramssld["ht_view2_show_description"] = "on";
$paramssld["ht_view2_thumbs_width"] = "75";
$paramssld["ht_view2_thumbs_height"] = "75";
$paramssld["ht_view2_thumbs_position"] = "before";
$paramssld["ht_view2_show_thumbs"] = "on";
$paramssld["ht_view2_popup_background_color"] = "FFFFFF";
$paramssld["ht_view2_popup_overlay_color"] = "000000";
$paramssld["ht_view2_popup_overlay_transparency_color"] = "70";
$paramssld["ht_view2_popup_closebutton_style"] = "dark";
$paramssld["ht_view2_show_separator_lines"] = "on";
$paramssld["ht_view2_show_popup_title"] = "on";
$paramssld["ht_view2_element_title_font_size"] = "18";
$paramssld["ht_view2_element_title_font_color"] = "222222";
$paramssld["ht_view2_popup_title_font_size"] = "18";
$paramssld["ht_view2_popup_title_font_color"] = "222222";
$paramssld["ht_view2_element_overlay_color"] = "FFFFFF";
$paramssld["ht_view2_element_overlay_transparency"] = "70";
$paramssld["ht_view2_zoombutton_style"] = "light";
$paramssld["ht_view2_element_border_width"] = "1";
$paramssld["ht_view2_element_border_color"] = "dedede";
$paramssld["ht_view2_element_background_color"] = "f9f9f9";
$paramssld["ht_view2_element_width"] = "275";
$paramssld["ht_view2_element_height"] = "160";
$paramssld["ht_view5_icons_style"] = "dark";
$paramssld["ht_view5_show_separator_lines"] = "on";
$paramssld["ht_view5_linkbutton_text"] = "View More";
$paramssld["ht_view5_show_linkbutton"] = "on";
$paramssld["ht_view5_linkbutton_background_hover_color"] = "c02121";
$paramssld["ht_view5_linkbutton_background_color"] = "FF2C2C";
$paramssld["ht_view5_linkbutton_font_hover_color"] = "ffffff";
$paramssld["ht_view5_linkbutton_color"] = "ffffff";
$paramssld["ht_view5_linkbutton_font_size"] = "14";
$paramssld["ht_view5_description_color"] = "555555";
$paramssld["ht_view5_description_font_size"] = "14";
$paramssld["ht_view5_show_description"] = "on";
$paramssld["ht_view5_thumbs_width"] = "75";
$paramssld["ht_view5_thumbs_height"] = "75";
$paramssld["ht_view5_show_thumbs"] = "on";
$paramssld["ht_view5_title_font_size"] = "16";
$paramssld["ht_view5_title_font_color"] = "c02121";
$paramssld["ht_view5_main_image_width"] = "275";
$paramssld["ht_view5_slider_tabs_font_color"] = "d9d99";
$paramssld["ht_view5_slider_tabs_background_color"] = "555555";
$paramssld["ht_view5_slider_background_color"] = "f9f9f9";
$paramssld["ht_view6_title_font_size"] = "16";
$paramssld["ht_view6_title_font_color"] = "c02121";
$paramssld["ht_view6_title_font_hover_color"] = "FF2C2C";
$paramssld["ht_view6_title_background_color"] = "000000";
$paramssld["ht_view6_title_background_transparency"] = "80";
$paramssld["ht_view6_border_radius"] = "3";
$paramssld["ht_view6_border_width"] = "0";
$paramssld["ht_view6_border_color"] = "eeeeee";
$paramssld["ht_view6_width"] = "275";
/***/
$paramssld["ht_view8_element_cssAnimation"]= 'false';
$paramssld["ht_view8_element_height"]= '120';
$paramssld["ht_view8_element_maxheight"] = '155';
$paramssld["ht_view8_element_show_caption"] = 'true';
$paramssld["ht_view8_element_padding"] ='0';
$paramssld["ht_view8_element_border_radius"]='5';
$paramssld["ht_view8_icons_style"] ='dark';
$paramssld["ht_view8_element_title_font_size"] = '13';
$paramssld["ht_view8_element_title_font_color"] = '3AD6FC';
$paramssld["ht_view8_popup_background_color"] =  '000000';
$paramssld["ht_view8_popup_overlay_transparency_color"] =  '0';
$paramssld["ht_view8_popup_closebutton_style"] = 'dark';
$paramssld["ht_view8_element_title_overlay_transparency"] =  '90';
$paramssld["ht_view8_element_size_fix"] =  'false';
$paramssld["ht_view8_element_title_background_color"] =  'FF1C1C';
$paramssld["ht_view8_element_justify"] =  'true';
$paramssld["ht_view8_element_randomize"] =  'false';
$paramssld["ht_view8_element_animation_speed"] =  '2000';
/***/
$paramssld["light_box_size"] = "17";
$paramssld["light_box_width"] = "800";
$paramssld["light_box_transition"] = "elastic";
$paramssld["light_box_speed"] = "800";
$paramssld["light_box_href"] = "False";
$paramssld["light_box_title"] = "false";
$paramssld["light_box_scalephotos"] = "true";
$paramssld["light_box_rel"] = "false";
$paramssld["light_box_scrolling"] = "false";
$paramssld["light_box_opacity"] = "20";
$paramssld["light_box_open"] = "false";
$paramssld["light_box_overlayclose"] = "true";
$paramssld["light_box_esckey"] = "false";
$paramssld["light_box_arrowkey"] = "false";
$paramssld["light_box_loop"] = "true";
$paramssld["light_box_data"] = "false";
$paramssld["light_box_classname"] = "false";
$paramssld["light_box_fadeout"] = "300";
$paramssld["light_box_closebutton"] = "true";
$paramssld["light_box_current"] = "image";
$paramssld["light_box_previous"] = "previous";
$paramssld["light_box_next"] = "next";
$paramssld["light_box_close"] = "close";
$paramssld["light_box_iframe"] = "false";
$paramssld["light_box_inline"] = "false";
$paramssld["light_box_html"] = "false";
$paramssld["light_box_photo"] = "false";
$paramssld["light_box_height"] = "600";
$paramssld["light_box_innerwidth"] = "false";
$paramssld["light_box_innerheight"] = "false";
$paramssld["light_box_initialwidth"] = "300";
$paramssld["light_box_initialheight"] = "100";
$paramssld["light_box_maxwidth"] = "";
$paramssld["light_box_maxheight"] = "";
$paramssld["light_box_slideshow"] = "false";
$paramssld["light_box_slideshowspeed"] = "2500";
$paramssld["light_box_slideshowauto"] = "true";
$paramssld["light_box_slideshowstart"] = "start slideshow";
$paramssld["light_box_slideshowstop"] = "stop slideshow";
$paramssld["light_box_fixed"] = "true";
$paramssld["light_box_top"] = "false";
$paramssld["light_box_bottom"] = "false";
$paramssld["light_box_left"] = "false";
$paramssld["light_box_right"] = "false";
$paramssld["light_box_reposition"] = "false";
$paramssld["light_box_retinaimage"] = "true";
$paramssld["light_box_retinaurl"] = "false";
$paramssld["light_box_retinasuffix"] = "@2x.$1";
$paramssld["light_box_returnfocus"] = "true";
$paramssld["light_box_trapfocus"] = "true";
$paramssld["light_box_fastiframe"] = "true";
$paramssld["light_box_preloading"] = "true";
$paramssld["lightbox_open_position"] = "5";
$paramssld["light_box_style"] = "1";
$paramssld["light_box_size_fix"] = "false";
$paramssld["slider_crop_image"] = "crop";
$paramssld["slider_title_color"] = "000000";
$paramssld["slider_title_font_size"] = "13";
$paramssld["slider_description_color"] = "ffffff";
$paramssld["slider_description_font_size"] = "12";
$paramssld["slider_title_position"] = "right-top";
$paramssld["slider_description_position"] = "right-bottom";
$paramssld["slider_title_border_size"] = "0";
$paramssld["slider_title_border_color"] = "ffffff";
$paramssld["slider_title_border_radius"] = "4";
$paramssld["slider_description_border_size"] = "0";
$paramssld["slider_description_border_color"] = "ffffff";
$paramssld["slider_description_border_radius"] = "0";
$paramssld["slider_slideshow_border_size"] = "0";
$paramssld["slider_slideshow_border_color"] = "ffffff";
$paramssld["slider_slideshow_border_radius"] = "0";
$paramssld["slider_navigation_type"] = "1";
$paramssld["slider_navigation_position"] = "bottom";
$paramssld["slider_title_background_color"] = "ffffff";
$paramssld["slider_description_background_color"] = "000000";
$paramssld["slider_title_transparent"] = "on";
$paramssld["slider_description_transparent"] = "on";
$paramssld["slider_slider_background_color"] = "ffffff";
$paramssld["slider_dots_position"] = "none";
$paramssld["slider_active_dot_color"] = "ffffff";
$paramssld["slider_dots_color"] = "000000";
$paramssld["slider_description_width"] = "70";
$paramssld["slider_description_height"] = "50";
$paramssld["slider_description_background_transparency"] = "70";
$paramssld["slider_description_text_align"] = "justify";
$paramssld["slider_title_width"] = "30";
$paramssld["slider_title_height"] = "50";
$paramssld["slider_title_background_transparency"] = "70";
$paramssld["slider_title_text_align"] = "right";
$paramssld["slider_title_has_margin"] = "off";
$paramssld["slider_description_has_margin"] = "off";
$paramssld["slider_show_arrows"] = "on";
$paramssld["thumb_image_behavior"] = "on";
$paramssld["thumb_image_width"] = "240";
$paramssld["thumb_image_height"] = "150";
$paramssld["thumb_image_border_width"] = "1";
$paramssld["thumb_image_border_color"] = "444444";
$paramssld["thumb_image_border_radius"] = "5";
$paramssld["thumb_margin_image"] = "1";
$paramssld["thumb_title_font_size"] = "16";
$paramssld["thumb_title_font_color"] = "FFFFFF";
$paramssld["thumb_title_background_color"] = "CCCCCC";
$paramssld["thumb_title_background_transparency"] = "80";
$paramssld["thumb_box_padding"] = "28";
$paramssld["thumb_box_background"] = "333333";
$paramssld["thumb_box_use_shadow"] = "on";
$paramssld["thumb_box_has_background"] = "on";
$paramssld["thumb_view_text"] = "Watch Video";
////////////////////////////////
///////////////////////////////////
$paramssld["video_ht_view9_title_fontsize"] = "18";
$paramssld["video_ht_view9_title_color"] = "FFFFFF";
$paramssld["video_ht_view9_desc_color"] = "000000";
$paramssld["video_ht_view9_desc_fontsize"] = "14";
$paramssld["video_ht_view9_element_title_show"] = "true";
$paramssld["video_ht_view9_element_desc_show"] = "true";
$paramssld["video_ht_view9_general_width"] = "100";
$paramssld["video_view9_general_position"] = "center";
$paramssld["video_view9_title_textalign"] = "left";
$paramssld["video_view9_desc_textalign"] = "justify";
$paramssld["video_view9_image_position"] = "2";
$paramssld["video_ht_view9_title_back_color"] = "000000";
$paramssld["video_ht_view9_title_opacity"] = "70";
$paramssld["video_ht_view9_desc_opacity"] = "100";
$paramssld["video_ht_view9_desc_back_color"] = "FFFFFF";
$paramssld["video_ht_view9_general_space"] = "0";
$paramssld["video_ht_view9_general_separator_size"] = "0";
$paramssld["video_ht_view9_general_separator_color"] = "010457";
$paramssld["video_view9_general_separator_style"] = "dotted";
$paramssld["video_ht_view9_general_content_count"] = "2";
$paramssld["video_ht_view9_paginator_fontsize"] = "22";
$paramssld["video_ht_view9_paginator_color"] = "615854";
$paramssld["video_ht_view9_paginator_icon_color"] = "B3A5A1";
$paramssld["video_ht_view9_paginator_icon_size"] = "25";
$paramssld["video_view9_paginator_position"] = "left";
$paramssld["video_ht_view9_video_width"] = "720";
$paramssld["video_ht_view9_video_height"] = "420";
$paramssld["video_view9_video_position"] = "center";
///////////////////////////////////////
//////////////////////////////////////
$paramssld["video_view9_loadmore_position"] = "center";
$paramssld["video_ht_view9_loadmore_fontsize"] = "19";
$paramssld["video_ht_view9_button_color"] = "615854";
$paramssld["video_ht_view9_loadmore_font_color"] = "FFFFFF";
$paramssld["loading_type"] = "1";
$paramssld["video_ht_view9_loadmore_text"] = "View More";
$paramssld["video_ht_view8_paginator_position"] = "center";
$paramssld["video_ht_view8_paginator_icon_size"] = "18";
$paramssld["video_ht_view8_paginator_icon_color"] = "26A6FC";
$paramssld["video_ht_view8_paginator_color"] = "26A6FC";
$paramssld["video_ht_view8_paginator_fontsize"] = "18";
$paramssld["video_ht_view8_loadmore_position"] = "center";
$paramssld["video_ht_view8_loadmore_fontsize"] = "14";
$paramssld["video_ht_view8_button_color"] = "26A6FC";
$paramssld["video_ht_view8_loadmore_font_color"] = "FF1C1C";
$paramssld["video_ht_view8_loading_type"] = "3";
$paramssld["video_ht_view8_loadmore_text"] = "View More";
$paramssld["video_ht_view7_paginator_fontsize"] = "22";
$paramssld["video_ht_view7_paginator_color"] = "0A0202";
$paramssld["video_ht_view7_paginator_icon_color"] = "333333";
$paramssld["video_ht_view7_paginator_icon_size"] = "22";
$paramssld["video_ht_view7_paginator_position"] = "center";
$paramssld["video_ht_view7_loadmore_position"] = "center";
$paramssld["video_ht_view7_loadmore_fontsize"] = "19";
$paramssld["video_ht_view7_button_color"] = "333333";
$paramssld["video_ht_view7_loadmore_font_color"] = "CCCCCC";
$paramssld["video_ht_view7_loading_type"] = "1";
$paramssld["video_ht_view7_loadmore_text"] = "View More";
$paramssld["video_ht_view4_paginator_fontsize"] = "19";
$paramssld["video_ht_view4_paginator_color"] = "FF2C2C";
$paramssld["video_ht_view4_paginator_icon_color"] = "B82020";
$paramssld["video_ht_view4_paginator_icon_size"] = "21";
$paramssld["video_ht_view4_paginator_position"] = "center";
$paramssld["video_ht_view4_loadmore_position"] = "center";
$paramssld["video_ht_view4_loadmore_fontsize"] = "16";
$paramssld["video_ht_view4_button_color"] = "5CADFF";
$paramssld["video_ht_view4_loadmore_font_color"] = "FF0D0D";
$paramssld["video_ht_view4_loading_type"] = "3";
$paramssld["video_ht_view4_loadmore_text"] = "View More";
$paramssld["video_ht_view1_paginator_fontsize"] = "22";
$paramssld["video_ht_view1_paginator_color"] = "222222";
$paramssld["video_ht_view1_paginator_icon_color"] = "FF2C2C";
$paramssld["video_ht_view1_paginator_icon_size"] = "22";
$paramssld["video_ht_view1_paginator_position"] = "left";
$paramssld["video_ht_view1_loadmore_position"] = "center";
$paramssld["video_ht_view1_loadmore_fontsize"] = "22";
$paramssld["video_ht_view1_button_color"] = "FF2C2C";
$paramssld["video_ht_view1_loadmore_font_color"] = "FFFFFF";
$paramssld["video_ht_view1_loading_type"] = "2";
$paramssld["video_ht_view1_loadmore_text"] = "Load More";
$paramssld["video_ht_view9_loadmore_font_color_hover"] = "D9D9D9";
$paramssld["video_ht_view9_button_color_hover"] = "8F827C";
$paramssld["video_ht_view8_loadmore_font_color_hover"] = "FF4242";
$paramssld["video_ht_view8_button_color_hover"] = "0FEFFF";
$paramssld["video_ht_view7_loadmore_font_color_hover"] = "D9D9D9";
$paramssld["video_ht_view7_button_color_hover"] = "8F827C";
$paramssld["video_ht_view4_loadmore_font_color_hover"] = "FF4040";
$paramssld["video_ht_view4_button_color_hover"] = "99C5FF";
$paramssld["video_ht_view1_loadmore_font_color_hover"] = "F2F2F2";
$paramssld["video_ht_view1_button_color_hover"] = "991A1A";


?>







<script>
	var lightbox_transition = '<?php echo $paramssld['light_box_transition'];?>';
	var lightbox_speed = <?php echo $paramssld['light_box_speed'];?>;
	var lightbox_fadeOut = <?php echo $paramssld['light_box_fadeout'];?>;
	var lightbox_title = "<?php echo $paramssld['light_box_title'];?>";
	var lightbox_scalePhotos = <?php echo $paramssld['light_box_scalephotos'];?>;
	var lightbox_scrolling = <?php echo $paramssld['light_box_scrolling'];?>;
	var lightbox_opacity = <?php echo ($paramssld['light_box_opacity']/100)+0.001;?>;
	var lightbox_open = <?php echo $paramssld['light_box_open'];?>;
	var lightbox_returnFocus = <?php echo $paramssld['light_box_returnfocus'];?>;
	var lightbox_trapFocus = <?php echo $paramssld['light_box_trapfocus'];?>;
	var lightbox_fastIframe = <?php echo $paramssld['light_box_fastiframe'];?>;
	var lightbox_preloading = <?php echo $paramssld['light_box_preloading'];?>;
	var lightbox_overlayClose = <?php echo $paramssld['light_box_overlayclose'];?>;
	var lightbox_escKey = <?php echo $paramssld['light_box_esckey'];?>;
	var lightbox_arrowKey = "<?php echo $paramssld['light_box_arrowkey'];?>";
	var lightbox_loop = <?php echo $paramssld['light_box_loop'];?>;
	var lightbox_closeButton = "<?php echo $paramssld['light_box_closebutton'];?>";
	var lightbox_previous = "<?php echo $paramssld['light_box_previous'];?>";
	var lightbox_next = "<?php echo $paramssld['light_box_next'];?>";
	var lightbox_close = "<?php echo $paramssld['light_box_close'];?>";
	var lightbox_html = <?php echo $paramssld['light_box_html'];?>;
	var lightbox_photo = <?php echo $paramssld['light_box_photo'];?>;
	var lightbox_width = "<?php echo $paramssld['light_box_width'];?>";
	var lightbox_height = "<?php  echo $paramssld['light_box_height'];?>";
	var lightbox_innerWidth = '<?php echo $paramssld['light_box_innerwidth'];?>';
	var lightbox_innerHeight = '<?php echo $paramssld['light_box_innerheight'];?>';
	var lightbox_initialWidth = '<?php echo $paramssld['light_box_initialwidth'];?>';
	var lightbox_initialHeight = '<?php echo $paramssld['light_box_initialheight'];?>';
	
        var lightbox_maxWidth = '';
        var lightbox_maxHeight = '';
        
	var lightbox_slideshow = "<?php echo $paramssld['light_box_slideshow'];?>";
	var lightbox_slideshowSpeed = <?php echo $paramssld['light_box_slideshowspeed'];?>;
	var lightbox_slideshowAuto = <?php echo $paramssld['light_box_slideshowauto'];?>;
	var lightbox_slideshowStart = "<?php echo $paramssld['light_box_slideshowstart'];?>";
	var lightbox_slideshowStop = "<?php echo $paramssld['light_box_slideshowstop'];?>";
	var lightbox_fixed = <?php echo $paramssld['light_box_fixed'];?>;
	<?php
	$pos = $paramssld['lightbox_open_position'];
	switch($pos){ 
	case 1:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 1:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 2:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = false;
	<?php
	break;	
	case 3:
	?>
		var lightbox_top = '10%';
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = '10%';
	<?php
	break;
	case 4:
	?>
		var lightbox_top = false;
		var lightbox_bottom = false;
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 5:
	?>
		var lightbox_top = false;
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = false;
	<?php
	break;	
	case 6:
	?>
		var lightbox_top = false;
		var lightbox_bottom = false;
		var lightbox_left = false;
		var lightbox_right = '10%';
	<?php
	break;	
	case 7:
	?>
		var lightbox_top = false;
		var lightbox_bottom = '10%';
		var lightbox_left = '10%';
		var lightbox_right = false;
	<?php
	break;	
	case 8:
	?>
		var lightbox_top = false;
		var lightbox_bottom = '10%';
		var lightbox_left = false;
		var lightbox_right = false;
	<?php
	break;	
	case 9:
	?>
		var lightbox_top = false;
		var lightbox_bottom = '10%';
		var lightbox_left = false;
		var lightbox_right = '10%';
	<?php
	break;	
	} ?>
<?php
  	if($paramssld['light_box_reposition']  == 'on')$paramssld['light_box_reposition'] = "true"; else $paramssld['light_box_reposition']="false";
	if($paramssld['light_box_retinaimage']  == 'on')$paramssld['light_box_retinaimage'] = "true"; else $paramssld['light_box_retinaimage']="false";
	if($paramssld['light_box_retinaurl']  == 'on')$paramssld['light_box_retinaurl'] = "true"; else $paramssld['light_box_retinaurl']="false";
 ?>
	var lightbox_reposition = <?php echo $paramssld['light_box_reposition'];?>;
	var lightbox_retinaImage = <?php echo $paramssld['light_box_retinaimage'];?>;
	var lightbox_retinaUrl = <?php echo $paramssld['light_box_retinaurl'];?>;
	var lightbox_retinaSuffix = "<?php echo $paramssld['light_box_retinasuffix'];?>";
	
				jQuery(document).ready(function(){
				jQuery("#huge_it_videogallery_content a[href$='.jpg'], #huge_it_videogallery_content a[href$='.png'], #huge_it_videogallery_content a[href$='.gif']").addClass('group1');
				
				
				jQuery(".group1").colorbox({rel:'group1'});
				jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				jQuery(".vimeo").colorbox({iframe:true, innerWidth:640, innerHeight:390});
				jQuery(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
				jQuery(".inline").colorbox({inline:true, width:"50%"});
				jQuery(".callbacks").colorbox({
					onOpen:function(){ alert('onOpen: colorbox is about to open'); },
					onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
					onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
					onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
					onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
				});

				jQuery('.non-retina').colorbox({rel:'group5', transition:'none'})
				jQuery('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
				

				jQuery("#click").click(function(){ 
					jQuery('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		
</script>
<script src="<?php echo JURI::root() . 'media/com_videogallerylite/js/jquery.colorbox.js' ?>"></script>
<?php  $doc = JFactory::getDocument(); ?>
<?php $doc->addScript(JURI::root() . 'media/com_videogallerylite/js/gallery-all.js'); ?>
<!--<script src="<?php // echo JURI::root() . 'media/com_videogallerylite/js/gallery-all.js'  ?>"></script>-->
<?php // $doc->addScript(JURI::root() . 'media/com_videogallerylite/js/jquery.isotope.min.js'); ?>
<script src="<?php echo JURI::root() . 'media/com_videogallerylite/js/jquery.isotope.min.js' ?>"></script>
<link href="<?php echo JURI::root() . 'media/com_videogallerylite/style/colorbox-' . $paramssld['light_box_style'] . '.css' ?>" rel="stylesheet" type="text/css" />	<?php
$i = $gallerychangeview;
switch ($i) {
    case 0:
?>
<script> 
jQuery(function(){
    var defaultBlockHeight=<?php echo $paramssld['ht_view2_element_height']+37; ?>;
    var defaultBlockWidth=<?php echo $paramssld['ht_view2_element_width']; ?>;
    var $container = jQuery('#huge_it_videogallery_container_<?php echo $videogalleryID; ?>');
      // add randomish size classes
      $container.find('.element_<?php echo $videogalleryID; ?>').each(function(){
        var $this = jQuery(this),
            number = parseInt( $this.find('.number').text(), 10 );
			//alert(number);
        if ( number % 7 % 2 === 1 ) {
          $this.addClass('width2');
        }
        if ( number % 3 === 0 ) {
          $this.addClass('height2');
        }
      });
    
    $container.isotope({
      itemSelector : '.element_<?php echo $videogalleryID; ?>',
      masonry : {
        columnWidth : defaultBlockWidth+20
      },
      masonryHorizontal : {
        rowHeight: defaultBlockHeight+20
      },
      cellsByRow : {
        columnWidth : defaultBlockWidth+20,
        rowHeight : defaultBlockHeight
      },
      cellsByColumn : {
        columnWidth : defaultBlockWidth+20,
        rowHeight : defaultBlockHeight
      },
      getSortData : {
        symbol : function( $elem ) {
          return $elem.attr('data-symbol');
        },
        category : function( $elem ) {
          return $elem.attr('data-category');
        },
        number : function( $elem ) {
          return parseInt( $elem.find('.number').text(), 10 );
        },
        weight : function( $elem ) {
          return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
        },
        name : function ( $elem ) {
          return $elem.find('.name').text();
        }
      }
	 
    });
     <?php if($paramssld['ht_view2_content_in_center_popup'] == 'on') { ?>
    centersection();
      jQuery(window).resize(function(){
          centersection();
      });
        function centersection(){
                if(jQuery(window).width() < defaultBlockWidth) {
                        jQuery("#huge_it_videogallery_container_moving_<?php echo $videogalleryID; ?>").width('100%');
                        return;
                }
                var elementwidth=defaultBlockWidth + 20 + <?php echo $paramssld['ht_view2_element_border_width']*2; ?>;
                var enterycontent=jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?>").width();
                var enterycontentHeight=jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?>").height();
                var whole=~~(enterycontent/(elementwidth));
                if(whole==0){ return false; }
                else { var sectionwidth= whole * elementwidth;}
                jQuery("#huge_it_videogallery_container_moving_<?php echo $videogalleryID; ?>").width(sectionwidth).css({"margin":"0px auto","overflow":"hidden","position":"relative","height":enterycontentHeight});
//                 alert(elementwidth + " " + enterycontent + " " + whole + " " + sectionwidth);
      }
      <?php } ?>
    
	var $optionSets = jQuery('#huge_it_videogallery_options .option-set'),
	$optionLinks = $optionSets.find('a');

	$optionLinks.click(function(){
		var $this = jQuery(this);

		if ( $this.hasClass('selected') ) {
		  return false;
		}
		var $optionSet = $this.parents('.option-set');
		$optionSet.find('.selected').removeClass('selected');
		$this.addClass('selected');

		var options = {},
			key = $optionSet.attr('data-option-key'),
			value = $this.attr('data-option-value');

		value = value === 'false' ? false : value;
		options[ key ] = value;
		if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {

		  changeLayoutMode( $this, options )
		} else {

		  $container.isotope( options );
		}

		return false;
	});    

	var isHorizontal = false;
	function changeLayoutMode( $link, options ) {
		var wasHorizontal = isHorizontal;
		isHorizontal = $link.hasClass('horizontal');

		if ( wasHorizontal !== isHorizontal ) {

		  var style = isHorizontal ? 
			{ height: '100%', width: $container.width() } : 
			{ width: 'auto' };

		  $container.filter(':animated').stop();

		  $container.addClass('no-transition').css( style );
		  setTimeout(function(){
			$container.removeClass('no-transition').isotope( options );
		  }, 100 )
		} else {
		  $container.isotope( options );
		}
	}

    var $sortBy = jQuery('#sort-by');
    jQuery('#shuffle a').click(function(){
      $container.isotope('shuffle');
      $sortBy.find('.selected').removeClass('selected');
      $sortBy.find('[data-option-value="random"]').addClass('selected');
      return false;
    });
});

jQuery(document).ready(function(){
	
	document.onkeydown = function(e){
		(e.keyCode == 39)&&(jQuery('.pupup-element.active .right-change a').click());
		(e.keyCode == 37)&&(jQuery('.pupup-element.active .left-change a').click());
		if(e.keyCode == 27){
			closePopup()
		};

	};

	jQuery('body').on('click','.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> .videogallery-image-overlay a', function(){
		var strid = jQuery(this).attr('href').replace('#','');
		jQuery('body').append('<div id="huge-popup-overlay"></div>');
		jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>').insertBefore('#huge-popup-overlay');
		var height = jQuery(window).height();
		var width=jQuery(window).width();
		if(width<=767){
			jQuery(window).scrollTop(0);
			jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery('body').width()*0.5);
		}else {jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery('body').width()*0.23);}
		jQuery('#huge_it_videogallery_pupup_element_'+strid).addClass('active').css({height:height*0.7});
		jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>').addClass('active');
		
		if(jQuery('.pupup-element.active .description').height() > jQuery('.pupup-element.active .right-block').height()){
			if(jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?> img').height() > jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?>').height()){
				jQuery('.pupup-element.active .popup-wrapper_<?php echo $videogalleryID; ?>').css({
					'overflow-y':'scroll',
					'width':'96%',
				});
				
			}
			else{
				jQuery('.pupup-element.active .right-block').css('overflow-y', 'scroll');
			}
			
		} 
		else if(jQuery('.pupup-element.active .description').height() < jQuery('.pupup-element.active .right-block').height() && jQuery('.pupup-element.active img').height() > jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?>').height() ){
			jQuery('.pupup-element.active .popup-wrapper_<?php echo $videogalleryID; ?>').css({
				'overflow-y':'scroll',
				'width':'96%',
			});
		}
		
		return false;
	});
	/*      <-- POPUP LEFT CLICK -->        */
        jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .left-change").click(function(){
        	//var strid = jQuery(this).closest(".pupup-element").prev(".pupup-element").find('a').data('popupid').replace('#','');
        	var height = jQuery(window).height();
        	//jQuery('#huge_it_gallery_pupup_element_'+strid).css({height:height*0.7});
            var num = jQuery(this).find("a").attr("href").replace('#', '');
            if(num >= 1){
            	var strid = jQuery(this).closest(".pupup-element").prev(".pupup-element").find('a').data('popupid').replace('#','');
            	jQuery('#huge_it_videogallery_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery(this).closest(".pupup-element").prev(".pupup-element").addClass("active");
            }else{
            	var strid = jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>").find(".pupup-element").last().find('a').data('popupid').replace('#','');
            	jQuery('#huge_it_videogallery_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>").find(".pupup-element").last().addClass("active");
            }
			
			if(jQuery('.pupup-element.active .description').height() > jQuery('.pupup-element.active .right-block').height()){
				if(jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?> img').height() > jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?>').height()){
					jQuery('.pupup-element.active .popup-wrapper_<?php echo $videogalleryID; ?>').css({
						'overflow-y':'scroll',
						'width':'96%',
					});
					
				}
				else{
					jQuery('.pupup-element.active .right-block').css('overflow-y', 'scroll');
				}
				
			} 
			else if(jQuery('.pupup-element.active .description').height() < jQuery('.pupup-element.active .right-block').height() && jQuery('.pupup-element.active img').height() > jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?>').height() ){
				jQuery('.pupup-element.active .popup-wrapper_<?php echo $videogalleryID; ?>').css({
					'overflow-y':'scroll',
					'width':'96%',
				});
			}
            
        });
        
        /*      <-- POPUP RIGHT CLICK -->        */
        jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .right-change").click(function(){
        	var height = jQuery(window).height();
            var num = jQuery(this).find("a").attr("href").replace('#', '');
            var cnt = 0;
            jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>").find(".pupup-element").each(function(){
                cnt++;
            });
//            alert(num+" "+cnt);
            if(num <= cnt){
            	var strid = jQuery(this).closest(".pupup-element").next(".pupup-element").find('a').data('popupid').replace('#','');
	        	jQuery('#huge_it_videogallery_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery(this).closest(".pupup-element").next(".pupup-element").addClass("active");
            }else{
            	var strid = jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>").find(".pupup-element:first-child a").data('popupid').replace('#','');	        	
	        	jQuery('#huge_it_videogallery_pupup_element_'+strid).css({height:height*0.7});
                jQuery(this).closest(".pupup-element").removeClass("active");
                jQuery("#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>").find(".pupup-element:first-child").addClass("active");
            }
			
			if(jQuery('.pupup-element.active .description').height() > jQuery('.pupup-element.active .right-block').height()){
				if(jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?> img').height() > jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?>').height()){
					jQuery('.pupup-element.active .popup-wrapper_<?php echo $videogalleryID; ?>').css({
						'overflow-y':'scroll',
						'width':'96%',
					});
					
				}
				else{
					jQuery('.pupup-element.active .right-block').css('overflow-y', 'scroll');
				}
				
			} 
			else if(jQuery('.pupup-element.active .description').height() < jQuery('.pupup-element.active .right-block').height() && jQuery('.pupup-element.active img').height() > jQuery('.pupup-element.active .image-block_<?php echo $videogalleryID; ?>').height() ){
				jQuery('.pupup-element.active .popup-wrapper_<?php echo $videogalleryID; ?>').css({
					'overflow-y':'scroll',
					'width':'96%',
				});
			}
        });
	//////
	
	jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close').on('click',function(){
		closePopup();
		return false;
	});
	
	jQuery('body').on('click','#huge-popup-overlay',function(){
		closePopup();
		return false;
	});
	
	function closePopup() {
		var scrollingTo = jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .pupup-element.active').attr('id');
		if(jQuery(window).width<=767){
			jQuery(window).scrollTop(jQuery("#"+scrollingTo+"_child").offset().top-100);
		}
		jQuery('#huge-popup-overlay').remove();
		var videsrc=jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.active iframe').attr('src');
		jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.active iframe').attr('src','');
		jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.active iframe').attr('src',videsrc);
	
		/* Removing autoplay attribute from iframe src */
		var container = jQuery(".pupup-element.active");
		iframe_src_have_autoplay_container = videsrc;
		//var container_iframe_src_replace = iframe_src_have_autoplay_container.replace("&autoplay=1", "");
var container_iframe_src_replace = iframe_src_have_autoplay_container.split("?");
//alert(iframe_src_have_autoplay_container);alert(container_iframe_src_replace[0] );
		container.find("iframe").attr("src", container_iframe_src_replace[0]); 
	jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li').removeClass('active');
		jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>').removeClass('active');
	}



	jQuery(window).resize(function(){
		var imgBlockWidth=jQuery(window).width();
		if(imgBlockWidth>=768){
			jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery(window).width()*0.20);
		}else{
			jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery(window).width()*0.5);
		}
	});

	
	
}); 
</script>
    
<style type="text/css"> 
    #huge_it_videogallery_content_<?php echo $videogalleryID; ?> a{
        border:0;
    }

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> {
	width: 100%;
	max-width:<?php echo $paramssld['ht_view2_element_width']; ?>px;
	height:<?php echo $paramssld['ht_view2_element_height']+45; ?>px;
	margin:0px 0px 10px 0px;
	background:#<?php echo $paramssld['ht_view2_element_background_color']; ?>;
	border:<?php echo $paramssld['ht_view2_element_border_width']; ?>px solid #<?php echo $paramssld['ht_view2_element_border_color']; ?>;
	outline:none;
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> {
	<?php if($paramssld['video_natural_size_contentpopup']=='resize'){?>
	position:relative;
	width:100%;
	<?php }elseif($paramssld['video_natural_size_contentpopup']=='natural'){?>
            position:relative;
            width:100%;
            overflow: hidden;
            height:<?php echo $paramssld['ht_view2_element_height']; ?>px !important;
	<?php }?>
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> img {
    <?php if($paramssld['video_natural_size_contentpopup']=='resize'){?>
	width:100% !important;
	max-width:<?php echo $paramssld['ht_view2_element_width']; ?>px !important;
	height:<?php echo $paramssld['ht_view2_element_height']; ?>px !important;
	display:block;
	border-radius: 0px !important;
	box-shadow: 0 0px 0px rgba(0, 0, 0, 0) !important; 
	<?php }elseif($paramssld['video_natural_size_contentpopup']=='natural'){?>
	display:block;
	max-width: none !important;
	border-radius: 0px !important;
	box-shadow: 0 0px 0px rgba(0, 0, 0, 0) !important; 
	<?php }?>   
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> .videogallery-image-overlay {
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	background: <?php
            list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view2_element_overlay_color'],2));
                    $titleopacity=$paramssld["ht_view2_element_overlay_transparency"]/100;						
                    echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
	display:none;
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?>:hover .image-block_<?php echo $videogalleryID; ?>  .videogallery-image-overlay {
	display:block;
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> .videogallery-image-overlay a {
	position:absolute;
	top:0px;
	left:0px;
	display:block;
	width:100%;
	height:100%;
        background:url('<?php echo JUri::root().'media/com_videogallerylite/images/zoom.'.$paramssld["ht_view2_zoombutton_style"].'.png'; ?>') center center no-repeat;
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> {
	position:relative;
	height: 30px;
	margin: 0;
	padding: 15px 0px 15px 0px;
	-webkit-box-shadow: inset 0 1px 0 rgba(0,0,0,.1);
	box-shadow: inset 0 1px 0 rgba(0,0,0,.1);
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> h3 {
	position:relative;
	margin:0px !important;
	padding:0px 1% 5px 1% !important;
	width:98%;
	text-overflow: ellipsis;
	overflow: hidden; 
	white-space:nowrap;
	font-weight:normal;
	font-size: <?php echo $paramssld["ht_view2_element_title_font_size"];?>px !important;
	line-height: <?php echo $paramssld["ht_view2_element_title_font_size"];?>px !important;
	color:#<?php echo $paramssld["ht_view2_element_title_font_color"];?>;
}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> .button-block {
	position:absolute;
	right:0px;
	top:0px;
	display:none;
	vertical-align:middle;
	/*height:30px;*/
	padding:10px 10px 4px 10px;
	/* background: <?php
            list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view2_element_overlay_color'],2));
                    $titleopacity=$paramssld["ht_view2_element_overlay_transparency"]/100;						
                    echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>; */
	border-left: 1px solid rgba(0,0,0,.05);
}
	.load_more5 {
            margin: 10px 0;
            position:relative;
            text-align:<?php if($paramssld['video_ht_view1_loadmore_position'] == 'left') {echo 'left';} 
                elseif ($paramssld['video_ht_view1_loadmore_position'] == 'center') { echo 'center'; }
                elseif($paramssld['video_ht_view1_loadmore_position'] == 'right') { echo 'right'; }?>;

            width:100%;
	}
	.load_more_button5 {
		border-radius: 10px;
		display:inline-block;
		padding:5px 15px;
		font-size:<?php echo $paramssld['video_ht_view1_loadmore_fontsize']; ?>px !important;;
		color:<?php echo '#'.$paramssld['video_ht_view1_loadmore_font_color']; ?> !important;;
		background:<?php echo '#'.$paramssld['video_ht_view1_button_color']; ?> !important;
		cursor:pointer;
	}
	.load_more_button5:hover{
		color:<?php echo '#'.$paramssld['video_ht_view1_loadmore_font_color_hover']; ?> !important;
		background:<?php echo '#'.$paramssld['video_ht_view1_button_color_hover']; ?> !important;
	}
	.loading5 {
		display:none;
	}
	.paginate5{
		font-size:<?php echo $paramssld['video_ht_view1_paginator_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view1_paginator_color']; ?> !important;
		text-align: <?php echo $paramssld['video_ht_view1_paginator_position']; ?>;
		margin-top:15px;
	}
	.paginate5 a{
		border-bottom: none !important;
	}
	.icon-style5{
		font-size: <?php echo $paramssld['video_ht_view1_paginator_icon_size']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view1_paginator_icon_color']; ?> !important;
	}
	.clear{
		clear:both;
	}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?>:hover .title-block_<?php echo $videogalleryID; ?> .button-block {display:block;}

#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a,.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:link,.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:visited,
#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:hover,.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:focus,.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:active {
	position:relative;
	display:block;
	vertical-align:middle;
	padding: 3px 10px 3px 10px; 
	border-radius:3px;
	font-size:<?php echo $paramssld["ht_view2_element_linkbutton_font_size"];?>px;
	background:#<?php echo $paramssld["ht_view2_element_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view2_element_linkbutton_color"];?>;
	text-decoration:none;
}

/*#####POPUP#####*/

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> {
	position:fixed;
	display:table;
	width:80%;
	top:7%;
	left:7%;
	margin:0px !important;
	padding:0px !important;
	list-style:none;
	z-index:2000;
	display:none;
	height:85%;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>.active {display:table;}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element {
	position:relative;
	display:none;
	width:100%;
	padding:40px 0px 20px 0px;
	min-height:100%;
	position:relative;
	background:#<?php echo $paramssld["ht_view2_popup_background_color"];?>;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element.active {
	display:block;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation {
	position:absolute;
	width:100%;
	height:40px;
	top:0px;
	left:0px;
	z-index:2001;
        background:url('<?php echo JUri::root().'media/com_videogallerylite/images/divider.line.png'; ?>') center bottom repeat-x;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close,#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close:link, #huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close:visited {
	position:relative;
	float:right;
	width:40px;
	height:40px;
	display:block;
	background:url('<?php echo JUri::root().'media/com_videogallerylite/images/close.popup.'.$paramssld["ht_view2_popup_closebutton_style"].'.png' ; ?>') center center no-repeat;
	border-left:1px solid #ccc;
	opacity:.65;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close:hover, #huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close:focus, #huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close:active {opacity:1;}


#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element .popup-wrapper_<?php echo $videogalleryID; ?> {
	position:relative;
	width:100%;
	height:98%;
	padding:2% 2% 0% 2%;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> {
	width:55.5%;
	height:100%;
	position:relative;
	float:left;
	margin-right:2%;
	border-right:1px solid #ccc;
	min-width:200px;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> img {
	width:100% !important;
	display:block;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe  {
	width:100% !important;
	height:100%;
	display:block;

}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block {
	width:40%;
	height:100%;
	position:relative;
	float:left;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element .popup-wrapper_<?php echo $videogalleryID; ?> .right-block > div {
	padding-top:10px;
	margin-bottom:10px;
	<?php if($paramssld['ht_view2_show_separator_lines']=="on") {?>
		background:url('<?php echo JUri::root().'media/com_videogallerylite/images/divider.line.png'; ?>') center top repeat-x;
	<?php } ?>
}
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element .popup-wrapper_<?php echo $videogalleryID; ?> .right-block > div:last-child {background:none;}


#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .title {
	position:relative;
	display:block;
	margin:0px 0px 10px 0px !important;
	font-size:<?php echo $paramssld["ht_view2_popup_title_font_size"];?>px !important;
	line-height:<?php echo $paramssld["ht_view2_popup_title_font_size"];?>px !important;
	color:#<?php echo $paramssld["ht_view2_popup_title_font_color"];?>;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description {
	clear:both;
	position:relative;
	font-weight:normal;
	text-align:justify;
	font-size:<?php echo $paramssld["ht_view2_description_font_size"];?>px !important;
	color:#<?php echo $paramssld["ht_view2_description_color"];?>;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description h1,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description h2,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description h3,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description h4,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description h5,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description h6,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description p, 
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description strong,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description span {
	padding:2px !important;
	margin:0px !important;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description ul,
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block .description li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block ul.thumbs-list {
	list-style:none;
	display:table;
	position:relative;
	clear:both;
	width:100%;
	margin:0px auto;
	padding:0px;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block ul.thumbs-list li {
	display:block;
	float:left;
	width:<?php echo $paramssld["ht_view2_thumbs_width"];?>px;
	height:<?php echo $paramssld["ht_view2_thumbs_height"];?>px;
	margin:0px 2% 5px 1% !important;
	opacity:0.45;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block ul.thumbs-list li.active,#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block ul.thumbs-list li:hover {
	opacity:1;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block ul.thumbs-list li a {
	display:block;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block ul.thumbs-list li img {
	width:<?php echo $paramssld["ht_view2_thumbs_width"];?>px !important;
	height:<?php echo $paramssld["ht_view2_thumbs_height"];?>px !important;
}
/**/
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .left-change, #huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .right-change{
    width: 40px;
    height: 39px;
    font-size: 25px;
    display: inline-block;
    text-align: center;
    border: 1px solid #eee;
    border-bottom: none;
    border-top: none;
}
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .right-change{
    position: relative;
    margin-left: -6px;
}
#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .right-change:hover, #huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .left-change:hover{
    background: #ddd;
    border-color: #ccc;
    color: #000 !important;
    cursor: pointer;
}

#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .right-change a, #huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .left-change a{
    position: absolute;
    top:50%;
    transform: translate(-50%, -50%);
    color: #777;
    text-decoration: none;
    width: 12px;
    height: 24px;  
    display: inline-block;
    line-height:1;
}


/**/

.pupup-element .button-block {
	position:relative;
}

.pupup-element .button-block a,.pupup-element .button-block a:link,.pupup-element .button-block a:visited {
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#<?php echo $paramssld["ht_view2_popup_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view2_popup_linkbutton_color"];?>;
	font-size:<?php echo $paramssld["ht_view2_popup_linkbutton_font_size"];?>px;
	text-decoration:none;
}

.pupup-element .button-block a:hover,.pupup-element .button-block a:focus,.pupup-element .button-block a:active {
	background:#<?php echo $paramssld["ht_view2_popup_linkbutton_background_hover_color"];?>;
	color:#<?php echo $paramssld["ht_view2_popup_linkbutton_font_hover_color"];?>;
}


#huge-popup-overlay {
	position:fixed;
	top:0px;
	left:0px;
	width:100%;
	height:100%;
	z-index:199;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view2_popup_overlay_color'],2));
				$titleopacity=$paramssld["ht_view2_popup_overlay_transparency_color"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>
}


@media only screen and (max-width: 767px) {
	
	#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> {
		position:absolute;
		left:0px;
		top:0px;
		width:100%;
		height:auto !important;
		left:0px;
	}
	
	#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element {
		margin:0px;
		height:auto !important;
		position:absolute;
		left:0px;
		top:0px;
	}

	#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.pupup-element .popup-wrapper_<?php echo $videogalleryID; ?> {
		height:auto !important;
		overflow-y:auto;
	}


	#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> {
		width:100%;
		float:none;
		clear:both;
		margin-right:0px;
		border-right:0px;
	}

	#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .right-block {
		width:100%;
		float:none;
		clear:both;
		margin-right:0px;
		border-right:0px;
	}

	#huge-popup-overlay {
		position:fixed;
		top:0px;
		left:0px;
		width:100%;
		height:100%;
		z-index:199;
	}

}
</style>
   
<section id="huge_it_videogallery_content_<?php echo $videogalleryID; ?>">
  <div id="huge_it_videogallery_container_<?php echo $videogalleryID; ?>" class="super-list variable-sizes clearfix">
  	<div id="huge_it_videogallery_container_moving_<?php echo $videogalleryID; ?>">
  	<input type="hidden" class="pagenum" value="1" />
  	<?php
        $gallery = '';
            foreach($images as $image){
            $idofgallery=$image->videogallery_id ;
            //print_r($image);
            }
                        
        $db = JFactory::getDBO();
        $query2 = $db->getQuery(true);
        $query2->select('*');
        $query2->from('#__huge_it_videogallery_galleries');
        $query2 -> where('id ='.$idofgallery);
        $query2 ->order('#__huge_it_videogallery_galleries.ordering asc');
        $db->setQuery($query2);
        $gallery = $db->loadObjectList();
        $pattern='/-/';
    $pID = '';
     $post = 0;
        foreach ($gallery as $gall) {
	global $post;
        $pID=$post;
            $disp_type=$gall->display_type;
            $count_page=$gall->content_per_page;
            if($count_page==0){
                    $count_page=999;
            }elseif(preg_match($pattern, $count_page)){
                    $count_page=preg_replace($pattern, '', $count_page);
            }
	}
        $num=$count_page;
        $total = intval(((count($images) - 1) / $num) + 1);
        if(isset($_GET['page-img'.$videogalleryID.$pID])){
                $page = $_GET['page-img'.$videogalleryID.$pID];
        }else{
                $page = '';
                        }
        $page = intval($page);
        if(empty($page) or $page < 0) $page = 1;  
        if($page > $total) $page = $total; 
            $start = $page * $num - $num; 
            $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query -> where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
            if($disp_type==2){
                    $page_images=$images;
                    $count_page=9999;
            }
            ?>
			<input type="hidden" id="total" value="<?=$total; ?>" />
  	<?php
	
	foreach($page_images as $key=>$row)
	{
		$link = str_replace('__5_5_5__','%',$row->sl_url);
		$descnohtml=strip_tags(str_replace('__5_5_5__','%',$row->description));
		$result = substr($descnohtml, 0, 50);
		?>
		<div class="element_<?php echo $videogalleryID; ?>" id="huge_it_videogallery_pupup_element_<?php echo $row->id; ?>_child" tabindex="0" data-symbol="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-category="alkaline-earth">
                    <div class="image-block_<?php echo $videogalleryID; ?>">
			<?php 
                            $imagerowstype=$row->sl_type;
                            if($row->sl_type == ''){$imagerowstype='image';}
                            switch($imagerowstype){
                                    case 'image':
                    ?>									
                    <?php $imgurl=explode(";",$row->image_url); ?>
                    <?php 	if($row->image_url != ';'){ ?>
                    <img id="wd-cl-img<?php echo $key; ?>" src="<?php echo $imgurl[0]; ?>" alt="" />
                    <?php } else { ?>
                    <img id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" alt="" />
                    <?php
                    } ?>

				<?php
						break;
						case 'video':
				?>
							<?php
								$videourl=$this->get_video_gallery_id_from_url($row->image_url);
								if($videourl[1]=='youtube'){
										if(empty($row->thumb_url)){
											$thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
										}else{
											$thumb_pic=$row->thumb_url;
										}
									?>
									
										
										
										<img src="<?php echo $thumb_pic; ?>" alt="" />		
								<?php
									}else {
									$hash = unserialize($this->get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
									if(empty($row->thumb_url)){
										$imgsrc=$hash[0]['thumbnail_large'];
									}else{
										$imgsrc=$row->thumb_url;
									}
									
								?>
										<img src="<?php echo $imgsrc; ?>" alt="" />
								<?php
								}
							?>
				<?php
						break;
					}
				?>
			<?php if(str_replace('__5_5_5__','%',$row->sl_url)==''){
				$viwMoreButton='';
			}else{
				if ($row->link_target=="on"){
					$target='target="_blank"';
				}else{
					$target='';
				}
				$viwMoreButton='<div class="button-block"><a href="'.str_replace('__5_5_5__','%',$row->sl_url).'" '.$target.' >'.$paramssld["ht_view2_element_linkbutton_text"].'</a></div>';
			}




			?>
				<div class="videogallery-image-overlay"><a href="#<?php echo $row->id; ?>"></a></div>
			</div>
			<div class="title-block_<?php echo $videogalleryID; ?>">
				<h3><?php echo str_replace('__5_5_5__','%',$row->name); ?></h3>
				<?php if($paramssld["ht_view2_element_show_linkbutton"]=='on'){?>
					<?php echo $viwMoreButton?>
				<?php } ?>
			</div>
		</div>	
		<?php
	}?>
	</div>
	<div style="clear:both;"></div>
  </div>
  <?php 
		 $path_site = JUri::root()."media/com_videogallerylite/Front_images"; 
                 $path_siteArrow = JUri::root()."media/com_videogallerylite/images"; 
			$a=$disp_type;
			if($a==1){

				//if (!function_exists('check_url')) {
  //  function check_url() {
		    $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
				$pattern="/\?p=/";
				$pattern2="/&page-img[0-9]+=[0-9]+/";
				$pattern3="/\?page-img[0-9]+=[0-9]+/";
					if(preg_match($pattern, $actual_link)){

						if(preg_match($pattern2, $actual_link)){
							$actual_link=preg_replace($pattern2, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
							
						}	
					}elseif(preg_match($pattern3, $actual_link)){
						$actual_link=preg_replace($pattern3, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
						
					}
		 //  }
 //}
				
				?>
				<div class="load_more5"> 
					<div class="load_more_button5"><?=$paramssld['video_ht_view1_loadmore_text']; ?></div>
					<div class="loading5"><img src="<?php if($paramssld['video_ht_view1_loading_type'] == '1') {echo $path_siteArrow.'/loading1.gif';} 
					elseif ($paramssld['video_ht_view1_loading_type'] == '2') { echo $path_siteArrow.'/loading4.gif'; }
					elseif($paramssld['video_ht_view1_loading_type'] == '3') { echo $path_siteArrow.'/loading36.gif'; }
					elseif ($paramssld['video_ht_view1_loading_type'] == '4') { echo $path_siteArrow.'/loading51.gif'; }?>"></div>
					<script>
                                            jQuery(document).ready(function(){
                                                if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5").length){
                                                    jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5").on("click tap",function(){
                                                        if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()<jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?> #total").val()) {
										
										var pagenum = parseInt(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()) + 1;
										var perpage =<?=$count_page; ?>;
										var galleryid="<?=$image->videogallery_id; ?>";
										var thumbtext="<?php echo $paramssld["thumb_view_text"]; ?>";
										var linkbutton="<?=$paramssld["ht_view2_element_linkbutton_text"]; ?>"
										var showbutton="<?=$paramssld["ht_view2_element_show_linkbutton"]?>"
										var width="<?=$paramssld['video_ht_view9_video_width']; ?>";
										var height="<?=$paramssld['video_ht_view9_video_height']; ?>";
										//alert(pagenum+','+perpage+','+galleryid+','+width+','+height);
										getresult(pagenum,perpage,galleryid,thumbtext,linkbutton,showbutton,width,height);
									}else{
										jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5').hide();
									}
									return false;
								});
							}
											if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()==jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?> #total").val()) {
												jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5').hide();
											}
							function getresult(pagenum,perpage,galleryid,thumbtext,linkbutton,showbutton,width,height){
								var data = {
										action:"huge_it_video_gallery_ajax",
										task:'load_videos_content',
										page:pagenum,
										perpage:perpage,
										galleryid:galleryid,
										thumbtext:thumbtext,
										linkbutton:linkbutton,
										showbutton:showbutton,
										width:width,
										height:height
								}
								jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .loading5').show();
								
								jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5').hide();

								jQuery.post("components/com_videogallerylite/ajax_url.php",data,function(response){
                                                                            //   response = JSON.parse(response);  
									if(response.success){
										jQuery('#huge_it_videogallery_container_<?php echo $videogalleryID; ?>').append(jQuery(response.success) ).isotope( 'reloadItems' ).isotope({ sortBy: 'original-order' }).isotope( 'reLayout' );
										jQuery('#huge_it_videogallery_container_<?php echo $videogalleryID; ?> img').on("load",function(){
											
											//jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?>").append(response.success);
											jQuery(document).ready(function(){

												/* jQuery('.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> .videogallery-image-overlay a').on('click',function(){
													var strid = jQuery(this).attr('href').replace('#','');
													jQuery('body').append('<div id="huge-popup-overlay"></div>');
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>').insertBefore('#huge-popup-overlay');
													var height = jQuery(window).height();
													var width=jQuery(window).width();
													if(width<=767){
														jQuery(window).scrollTop(0);
														jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery('body').width()*0.5);
													}else {jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery('body').width()*0.23);}
													jQuery('#huge_it_videogallery_pupup_element_'+strid).addClass('active').css({height:height*0.7});
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>').addClass('active');
													
													return false;
												});
												
												
												jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .heading-navigation .close').on('click',function(){
													closePopup();
													return false;
												});
												
												jQuery('body').on('click','#huge-popup-overlay',function(){
													closePopup();
													return false;
												});
												
												function closePopup() {
													jQuery('#huge-popup-overlay').remove();
													var videsrc=jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.active iframe').attr('src');
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.active iframe').attr('src','');
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li.active iframe').attr('src',videsrc);
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> li').removeClass('active');
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>').removeClass('active');
													
												}

												jQuery(window).resize(function(){
													jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> iframe').height(jQuery('#huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?> .popup-wrapper_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?>').width()*0.5);
												}); */

												
											}); 
											////////////////////////////////////////////////////////////////////////////////////////////////////
											jQuery(".group1").colorbox({rel:'group1'});
											jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".vimeo").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
											jQuery(".inline").colorbox({inline:true, width:"50%"});
											jQuery(".callbacks").colorbox({
												onOpen:function(){ alert('onOpen: colorbox is about to open'); },
												onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
												onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
												onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
												onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
											});

											jQuery('.non-retina').colorbox({rel:'group5', transition:'none'})
											jQuery('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});


  
											jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5').show();
											jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .loading5').hide();
											
											if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()==jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?> #total").val()) {
												jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button5').hide();
											}
										});

									}else{
										alert("no");
									}
								},"json");
							}

						});
					</script>
				</div>
				<?php 
			}elseif($a==0){
		?>
			<div class="paginate5">
			<?php
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
			
			
			$checkREQ='';
			$pattern="/\?p=/";
			$pattern2="/&page-img[0-9]+=[0-9]+/";
			//$res=preg_match($pattern, $actual_link);
			if(preg_match($pattern, $actual_link)){

				if(preg_match($pattern2, $actual_link)){
					$actual_link=preg_replace($pattern2, '', $actual_link);
				}

				$checkREQ=$actual_link.'&page-img'.$videogalleryID.$pID;
				
			}else{
				$checkREQ='?page-img'.$videogalleryID.$pID;
				
			}
			//var_dump($res);  
			
			// Проверяем нужны ли стрелки назад  
				$pervpage='';
			if ($page != 1) $pervpage = '<a href= '.$checkREQ.'=1><i class="hugeiticon-style5 hugeiticons-fast-backward" ></i></a>  
			                               <a href= '.$checkREQ.'='. ($page - 1) .'><i class="hugeiticon-style5 hugeiticons-chevron-left"></i></a> ';  
			// Проверяем нужны ли стрелки вперед  
		       $nextpage='';
			if ($page != $total) $nextpage = ' <a href= '.$checkREQ.'='. ($page + 1) .'><i class="hugeiticon-style5 hugeiticons-chevron-right"></i></a>  
			                                   <a href= '.$checkREQ.'=' .$total. '><i class="hugeiticon-style5 hugeiticons-fast-forward" ></i></a>'; 

			// Вывод меню  
			echo $pervpage.$page.'/'.$total.$nextpage;  

			?>
</div>
<?php 
}
?>
</section>
<ul id="huge_it_videogallery_popup_list_<?php echo $videogalleryID; ?>" class="hg_video_popup">
	<?php
	$changePopup=1;
	foreach($images as $key=>$row)
	{
		$imgurl=explode(";",$row->image_url);
		$link = str_replace('__5_5_5__','%',$row->sl_url);
		$descnohtml=strip_tags(str_replace('__5_5_5__','%',$row->description));
		$result = substr($descnohtml, 0, 50);
		?>
		<li class="pupup-element" id="huge_it_videogallery_pupup_element_<?php echo $row->id; ?>">
			<div class="heading-navigation">
				<div style="display: inline-block; float: left;">
                        <div class="left-change" ><a href="#<?php echo $changePopup - 1; ?>" data-popupid="#<?php echo $row->id; ?>"><</a></div>
                        <div class="right-change" ><a href="#<?php echo $changePopup + 1; ?>" data-popupid="#<?php echo $row->id; ?>">></a></div>
                </div>
                <?php $changePopup=$changePopup+1; ?>
				<a href="#close" class="close"></a>
				<div style="clear:both;"></div>
			</div>
			<div class="popup-wrapper_<?php echo $videogalleryID; ?>">
				<div class="image-block_<?php echo $videogalleryID; ?>">					
					<?php 
						$imagerowstype=$row->sl_type;
						if($row->sl_type == ''){$imagerowstype='image';}
						switch($imagerowstype){
							case 'image':
					?>									
							<?php 	if($row->image_url != ';'){ ?>
							<img id="wd-cl-img<?php echo $key; ?>" src="<?php echo $imgurl[0]; ?>" alt="" />
							<?php } else { ?>
							<img id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" alt="" />
							<?php
							} ?>	

					<?php
							break;
							case 'video':
					?>
								<?php
									$videourl=$this->get_video_gallery_id_from_url($row->image_url);
									if($videourl[1]=='youtube'){?>
										<div class="hg_iframe_class"><div class="hg_iframe_class_sub"></div><iframe class="hg_iframe_class" src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>" style="border: 0;" allowfullscreen></iframe></div>
									<?php
									}else {
									?>
										<div class="hg_iframe_class"><div class="hg_iframe_class_sub"></div><iframe  src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>?title=0&amp;byline=0&amp;portrait=0"  style="border: 0;" allowfullscreen></iframe></div>
									<?php
									}
								?>
					<?php
							break;
						}
					?>
				<?php if(str_replace('__5_5_5__','%',$row->sl_url)==''){
				$viwMoreButton='';
			}else{
				if ($row->link_target=="on"){
					$target='target="_blank"';
				}else{
					$target='';
				}
				$viwMoreButton='<div class="button-block"><a href="'.str_replace('__5_5_5__','%',$row->sl_url).'" '.$target.' >'.$paramssld["ht_view2_element_linkbutton_text"].'</a></div>';
			}




			?>
				</div>
				<div class="right-block">
					 <h3 class="title"><?php echo str_replace('__5_5_5__','%',$row->name); ?></h3>
					<?php if($paramssld["ht_view2_show_description"]=='on'){?><div class="description"><?php echo str_replace('__5_5_5__','%',$row->description); ?></div><?php } ?>
					<?php if($paramssld["ht_view2_show_popup_linkbutton"]=='on'){?>
						<?php echo $viwMoreButton; ?>
					<?php } ?>
					<div style="clear:both;"></div>
				</div>
				<div style="clear:both;"></div>
			</div>
		</li>
		<?php
	}?>
</ul>

</>
<script>
jQuery(document).ready(function(){

jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> #huge_it_videogallery_container_<?php echo $videogalleryID; ?> .element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> img").each(function(i, img) {

	if(jQuery(this).prop('naturalWidth') < jQuery(this).prop('naturalHeight')){
		

  		jQuery(img).css({
        position: "relative",
        width:'100%',  
    	});
    	var middleHeight=(jQuery(img).parent().height()-jQuery(this).innerHeight())/2
    	jQuery(img).css({
        top: middleHeight
    	});	
  	}else if(jQuery(this).prop('naturalWidth') > jQuery(this).prop('naturalHeight')){
		jQuery(img).css({
        position: "relative",
        height:'100%'       
        });
        var middleWidth=(jQuery(img).parent().width()-jQuery(this).innerWidth())/2
    	jQuery(img).css({
        left: middleWidth
    	});
		//alert('yo')
	}
});
})
</script>
<?php
    break;
/////////////////////////////////// VIEW 1 CONTENT SLIDER ////////////////////////////////////
        case 1;
?>
<script src="<?php echo JUri::root().'media/com_videogallerylite/js/jquery.easing.min.js'; ?>"></script>
<script src="<?php echo JUri::root().'media/com_videogallerylite/js/jquery.touchSwipe.min.js';?>"></script>
<link rel="stylesheet" href="<?php echo  JUri::root().'media/com_videogallerylite/style/animate.min.css';?>">
<link href="<?php echo JUri::root().'media/com_videogallerylite/style/liquid-slider.css';?>" rel="stylesheet" type="text/css" />


<style>
* {outline:none;}
#main-slider_<?php echo $videogalleryID; ?> a{
    border:none;
}
#main-slider_<?php echo $videogalleryID; ?> {background:#<?php echo $paramssld["ht_view5_slider_background_color"];?>;}

#main-slider_<?php echo $videogalleryID; ?> div.slider-content {
	position:relative;
	width:100%;
	padding:0px 0px 0px 0px;
	position:relative;
	background:#<?php echo $paramssld["ht_view5_slider_background_color"];?>;
}



[class$="-arrow"] {
    background-image:url(<?php echo JUri::root().'media/com_videogallerylite/images/arrow.'.$paramssld["ht_view5_icons_style"].'.png';?>);
}

.ls-select-box {
    background:url(<?php echo JUri::root().'media/com_videogallerylite/images/menu.'.$paramssld["ht_view5_icons_style"].'.png';?>) right center no-repeat #<?php echo $paramssld["ht_view5_slider_background_color"];?>;
}

#main-slider_<?php echo $videogalleryID; ?>-nav-select {
	color:#<?php echo $paramssld["ht_view5_title_font_color"];?>;
}

#main-slider_<?php echo $videogalleryID; ?> div.slider-content .slider-content-wrapper {
	position:relative;
	width:100%;
	padding:0px;
	display:block;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .image-block_<?php echo $videogalleryID; ?> {
	position:relative;
	width:<?php echo $paramssld["ht_view5_main_image_width"];?>px;
	display:table-cell;
	padding:0px 10px 0px 0px;
	float:left;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .image-block_<?php echo $videogalleryID; ?> img.main-image {
	position:relative;
	width:100%;
	height:auto;
	display:block;
}


#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .image-block_<?php echo $videogalleryID; ?> .play-icon {
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;	
}
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .image-block_<?php echo $videogalleryID; ?>  .play-icon.youtube-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.youtube.png' ?>) center center no-repeat;}
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .image-block_<?php echo $videogalleryID; ?>  .play-icon.vimeo-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.vimeo.png' ?>) center center no-repeat;}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block {
	display:table-cell;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block > div {
	padding-bottom:10px;
	margin-top:10px;
	<?php if($paramssld['ht_view5_show_separator_lines']=="on") {?>
        background:url('<?php echo JUri::root().'media/com_videogallerylite/images/divider.line.png'; ?>') center bottom repeat-x;
	<?php } ?>
}
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block > div:last-child {background:none;}


#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .title {
	position:relative;
	display:block;
	margin:-10px 0px 0px 0px;
	font-size:<?php echo $paramssld["ht_view5_title_font_size"];?>px !important;
	line-height:<?php echo $paramssld["ht_view5_title_font_size"];?>px !important;
	color:#<?php echo $paramssld["ht_view5_title_font_color"];?>;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description {
	clear:both;
	position:relative;
	font-weight:normal;
	text-align:justify;
	font-size:<?php echo $paramssld["ht_view5_description_font_size"];?>px !important;
	line-height:<?php echo $paramssld["ht_view5_description_font_size"];?>px !important;
	color:#<?php echo $paramssld["ht_view5_description_color"];?>;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description h1,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description h2,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description h3,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description h4,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description h5,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description h6,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description p, 
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description strong,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description span {
	padding:2px !important;
	margin:0px !important;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description ul,
#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block .description li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}



#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block {
	position:relative;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block a,#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block a:link,#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block a:visited{
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#<?php echo $paramssld["ht_view5_linkbutton_background_color"];?>;
	color:#<?php echo $paramssld["ht_view5_linkbutton_color"];?>;
	font-size:<?php echo $paramssld["ht_view5_linkbutton_font_size"];?>;
	text-decoration:none;
}

#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block a:hover,#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block a:focus,#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .button-block a:active {
	background:#<?php echo $paramssld["ht_view5_linkbutton_background_hover_color"];?>;
	color:#<?php echo $paramssld["ht_view5_linkbutton_font_hover_color"];?>;
}

@media only screen and (min-width:500px) {
	#main-slider_<?php echo $videogalleryID; ?>-nav-ul {
		visibility:hidden !important;
		height:1px;
	}
}

@media only screen and (max-width:500px) {
	#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .image-block_<?php echo $videogalleryID; ?>,#main-slider_<?php echo $videogalleryID; ?> .slider-content-wrapper .right-block {
		width:100%;
		display:block;
		float:none;
		clear:both;
	}
}

</style>


<div id="main-slider_<?php echo $videogalleryID; ?>" class="liquid-slider">
    <?php
	foreach($images as $key=>$row) {
            $imgurl=explode(";",$row->image_url);
            //array_pop($imgurl);
            $link = str_replace('__5_5_5__','%',$row->sl_url);
            $descnohtml=strip_tags(str_replace('__5_5_5__','%',$row->description));
            $result = substr($descnohtml, 0, 50);
            ?>
            <div class="slider-content">
                <div class="slider-content-wrapper">
                    <div class="image-block_<?php echo $videogalleryID; ?>">
                        <?php 
                            $imagerowstype=$row->sl_type;
                            if($row->sl_type == ''){$imagerowstype='image';}
                            switch($imagerowstype){
                                    case 'image':
                    ?>									
                        <?php 	if($row->image_url != ';'){ ?>
                        <a class="group1" href="<?php echo $imgurl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>"><img class="main-image" src="<?php echo $imgurl[0]; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" /></a>
                        <?php } else { ?>
                        <img class="main-image" src="images/noimage.jpg" alt="" />
                        <?php
                        } ?>

						<?php
						break;
						case 'video':
				?>
						<?php
							$videourl=$this->get_video_gallery_id_from_url($row->image_url);
							if($videourl[1]=='youtube'){
									if(empty($row->thumb_url)){
											$thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
										}else{
											$thumb_pic=$row->thumb_url;
										}
									
								?>
								<a data-id="<?php echo $row->id; ?>" class="youtube huge_it_videogallery_item"  href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>">
									<img src="<?php echo $thumb_pic; ?>" alt="" />
									<div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
								</a>								
							<?php
								}else {
								$hash = unserialize($this->get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
								if(empty($row->thumb_url)){
										$imgsrc=$hash[0]['thumbnail_large'];
									}else{
										$imgsrc=$row->thumb_url;
									}
								//$imgsrc=$hash[0]['thumbnail_large'];
							?>
								<a class="vimeo huge_it_videogallery_item" data-id="<?php echo $row->id; ?>" href="//player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>">
									<img src="<?php echo $imgsrc; ?>" alt="" />
									<div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
								</a>
							<?php
							}
						?>
				<?php
						break;
					}
				?>
	
				</div>
				<div class="right-block">
					<div><h2 class="title"><?php echo str_replace('__5_5_5__','%',$row->name); ?></h2></div>
					<?php if($paramssld["ht_view5_show_description"]=='on'){?><div class="description"><?php echo str_replace('__5_5_5__','%',$row->description); ?></div><?php } ?>
					<?php if($paramssld["ht_view5_show_linkbutton"]=='on'){?>
                                        <?php if($link != '') {?>
						<div class="button-block">
							<a href="<?php echo $link; ?>" data-id="b<?php echo $row->id; ?>"  <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo $paramssld["ht_view5_linkbutton_text"]; ?></a>
						</div>
                                        <?php }?>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	} ?>
</div>
<script src="<?php echo JUri::root().'media/com_videogallerylite/js/jquery.liquid-slider.min.js';?>"></script>  
<script>
    jQuery('#main-slider_<?php echo $videogalleryID; ?>').liquidSlider();
</script>
<?php  
    break;
/////////////////////////////// VIEW 2 Lightbox videogallery /////////////////////////////
        case 5:
?>
<script src="<?php echo JUri::root().'media/com_videogallerylite/js/jquery.isotope.min.js';?>"></script>
<style type="text/css"> 

.element_<?php echo $videogalleryID; ?> {
	width: 100%;
	max-width:<?php echo $paramssld['ht_view6_width']; ?>px;
	margin:0px 0px 10px 0px;
	border:<?php echo $paramssld['ht_view6_border_width']; ?>px solid #<?php echo $paramssld['ht_view6_border_color']; ?>;
	border-radius:<?php echo $paramssld['ht_view6_border_radius']; ?>px;
	outline:none;
	overflow:hidden;
}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> {
	position:relative;
	width: 100%;
	max-width:<?php echo $paramssld['ht_view6_width']; ?>px;
}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> a {display:block;}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> img {
	width:<?php echo $paramssld['ht_view6_width']; ?>px !important;
	height:auto;
	display:block;
	border-radius: 0px !important;
	box-shadow: 0 0px 0px rgba(0, 0, 0, 0) !important; 
}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> img:hover {
	cursor: -webkit-zoom-in; cursor: -moz-zoom-in;
}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?> .play-icon {
	position:absolute;
	top:0px;
	left:0px;
	width:100%;
	height:100%;	
	
}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?>  .play-icon.youtube-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.youtube.png' ?>) center center no-repeat;}

.element_<?php echo $videogalleryID; ?> .image-block_<?php echo $videogalleryID; ?>  .play-icon.vimeo-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.vimeo.png' ?>) center center no-repeat;}


.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> {
	position:absolute;
	text-overflow: ellipsis;
	overflow: hidden; 
	left:0px;
	width:100%;
	/*padding-top:5px;*/
	height:30px;
	bottom:-35px;
	color:#<?php echo $paramssld["ht_view6_title_font_color"];?>;
	background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view6_title_background_color'],2));
				$titleopacity=$paramssld["ht_view6_title_background_transparency"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
	?>;
	 -webkit-transition: bottom 0.3s ease-out 0.1s;
     -moz-transition: bottom 0.3s ease-out 0.1s;
     -o-transition: bottom 0.3s ease-out 0.1s;
     transition: bottom 0.3s ease-out 0.1s;
}
.entry-content a{
	border-bottom: 0px;
}
.element_<?php echo $videogalleryID; ?>:hover .title-block_<?php echo $videogalleryID; ?> {bottom:0px;}

.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a, .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:link, .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:visited {
	position:relative;
	margin:0px;
	padding:0px 1% 0px 2%;
	width:97%;
	text-decoration:none;
	text-overflow: ellipsis;
	overflow: hidden; 
	white-space:nowrap;
	z-index:20;
	font-size: <?php echo $paramssld["ht_view6_title_font_size"];?>px;
	color:#<?php echo $paramssld["ht_view6_title_font_color"];?>;
	font-weight:normal;
}



.element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:hover, .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:focus, .element_<?php echo $videogalleryID; ?> .title-block_<?php echo $videogalleryID; ?> a:active {
	color:#<?php echo $paramssld["ht_view6_title_font_hover_color"];?>;
	text-decoration:none;
}

.load_more4 {
		margin: 10px 0;
		position:relative;
		text-align:<?php if($paramssld['video_ht_view4_loadmore_position'] == 'left') {echo 'left';} 
			elseif ($paramssld['video_ht_view4_loadmore_position'] == 'center') { echo 'center'; }
			elseif($paramssld['video_ht_view4_loadmore_position'] == 'right') { echo 'right'; }?>;

		width:100%;


	}

	.load_more_button4 {
		border-radius: 10px;
		display:inline-block;
		padding:5px 15px;
		font-size:<?php echo $paramssld['video_ht_view4_loadmore_fontsize']; ?>px !important;;
		color:<?php echo '#'.$paramssld['video_ht_view4_loadmore_font_color']; ?> !important;;
		background:<?php echo '#'.$paramssld['video_ht_view4_button_color']; ?> !important;
		cursor:pointer;

	}
	.load_more_button4:hover{
		color:<?php echo '#'.$paramssld['video_ht_view4_loadmore_font_color_hover']; ?> !important;
		background:<?php echo '#'.$paramssld['video_ht_view4_button_color_hover']; ?> !important;
	}

	.loading4 {
		display:none;
	}
	.paginate4{
		font-size:<?php echo $paramssld['video_ht_view4_paginator_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view4_paginator_color']; ?> !important;
		text-align: <?php echo $paramssld['video_ht_view4_paginator_position']; ?>;
		margin-top: 25px;
	}
	.paginate4 a{
		border-bottom: none !important;
	}
	.icon-style4{
		font-size: <?php echo $paramssld['video_ht_view4_paginator_icon_size']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view4_paginator_icon_color']; ?> !important;
	}
	.clear{
		clear:both;
	}
</style>


<section id="huge_it_videogallery_content_<?php echo $videogalleryID; ?>">
    <div id="huge_it_videogallery_container_<?php echo $videogalleryID; ?>" class="super-list variable-sizes clearfix">
  	<div id="huge_it_videogallery_container_moving_<?php echo $videogalleryID; ?>" class="super-list variable-sizes clearfix">
  	<input type="hidden" class="pagenum" value="1" />
  	<?php
			foreach($images as $image){
			$idofgallery=$image->videogallery_id ;
			//print_r($image);
			}
			$db = JFactory::getDBO();
                        $query2 = $db->getQuery(true);
                        $query2->select('*');
                        $query2->from('#__huge_it_videogallery_galleries');
                        $query2 -> where('id ='.$idofgallery);
                        $query2 ->order('#__huge_it_videogallery_galleries.ordering asc');
                        $db->setQuery($query2);
                        $gallery = $db->loadObjectList();
			$pattern='/-/';
			//$query2=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_galleries where id = '%d' order by ordering ASC ",$idofgallery);
            
			//$gallery=$wpdb->get_results($query2);
			foreach ($gallery as $gall) {
				  $post = 0;
				//$pID=$post->ID;
                                  $pID = 0;
				$disp_type=$gall->display_type;
				$count_page=$gall->content_per_page;
				if($count_page==0){
					$count_page=999;
				}elseif(preg_match($pattern, $count_page)){
					$count_page=preg_replace($pattern, '', $count_page);

				}
				//var_dump($count_page) ;

			}

			//global $wpdb;
			$num=$count_page;
			//$total = count($images) ; 
			$total = intval(((count($images) - 1) / $num) + 1);
			//$total_img=count($images);
			if(isset($_GET['page-img'.$videogalleryID.$pID])){
				$page = $_GET['page-img'.$videogalleryID.$pID];
			}else{
				$page = '';
					}
			
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;  
			  if($page > $total) $page = $total; 
			$start = $page * $num - $num; 
                        
                        $query = $db->getQuery(true);
                        $query->select('*');
                        $query->from('#__huge_it_videogallery_videos');
                        $query -> where('videogallery_id ='.$idofgallery);
                        $query ->order('#__huge_it_videogallery_videos.ordering asc');
                        $db->setQuery($query,$start,$num);
                        $page_images = $db->loadObjectList();
			//$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos where videogallery_id = '%d' order by ordering ASC LIMIT ".$start.",".$num."",$idofgallery);
			//$page_images=$wpdb->get_results($query);
			if($disp_type==2){
				$page_images=$images;
				$count_page=9999;
			}
			?>
			<input type="hidden" id="total" value="<?=$total; ?>" />
  	<?php
	
	foreach($page_images as $key=>$row)
	{
		$link = str_replace('__5_5_5__','%',$row->sl_url);
		$descnohtml=strip_tags(str_replace('__5_5_5__','%',$row->description));
		$result = substr($descnohtml, 0, 50);
		?>
		<div data-id="<?php echo $row->id; ?>" class="element_<?php echo $videogalleryID; ?>" tabindex="0" data-symbol="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-category="alkaline-earth">
			<div class="image-block_<?php echo $videogalleryID; ?>">
				<?php 
					$imagerowstype=$row->sl_type;
					if($row->sl_type == ''){$imagerowstype='image';}
					switch($imagerowstype){
						case 'image':
				?>									
							<?php $imgurl=explode(";",$row->image_url); ?>
							<?php 	if($row->image_url != ';'){ ?>
							<a href="<?php echo $imgurl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>"><img id="wd-cl-img<?php echo $key; ?>" src="<?php echo $imgurl[0]; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" /></a>
							<?php } else { ?>
							<img id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg" alt="" />
							<?php
							} ?>

				<?php
						break;
						case 'video':
				?>
						<?php
							$videourl=$this->get_video_gallery_id_from_url($row->image_url);
							if($videourl[1]=='youtube'){
									if(empty($row->thumb_url)){
											$thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
										}else{
											$thumb_pic=$row->thumb_url;
										}
								?>
								<a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-id="<?php echo $row->id; ?>">
									<img src="<?php echo $thumb_pic; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" />
									<div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
								</a>								
							<?php
								}else {
								$hash = unserialize($this->get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
								if(empty($row->thumb_url)){
										$imgsrc=$hash[0]['thumbnail_large'];
									}else{
										$imgsrc=$row->thumb_url;
									}
							?>
								<a class="vimeo huge_it_videogallery_item group1" href="//player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-id="<?php echo $row->id; ?>">
									<img src="<?php echo $imgsrc; ?>" alt="" />
									<div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
								</a>
							<?php
							}
						?>
				<?php
						break;
					}
				?>
			</div>
			<?php if(str_replace('__5_5_5__','%',$row->name)!=""){?>
			<div class="title-block_<?php echo $videogalleryID; ?>">
				<a href="<?php echo $link; ?>" <?php if ($row->link_target=="on"){echo 'target="_blank"';}?>><?php echo str_replace('__5_5_5__','%',$row->name); ?></a>
			</div>
			<?php } ?>
		</div>	
		<?php
	}?>
</div>
	<!-- <div style="clear:both;"></div> -->
  </div>

<?php 
		 $path_site = JUri::root()."media/com_videogallerylite/Front_images"; 
                 $path_siteArrow = JUri::root()."media/com_videogallerylite/images"; 
                 
			$a=$disp_type;
			if($a==1){
				 $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
				$pattern="/\?p=/";
				$pattern2="/&page-img[0-9]+=[0-9]+/";
				$pattern3="/\?page-img[0-9]+=[0-9]+/";
					if(preg_match($pattern, $actual_link)){

						if(preg_match($pattern2, $actual_link)){
							$actual_link=preg_replace($pattern2, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
							
						}	
					}elseif(preg_match($pattern3, $actual_link)){
						$actual_link=preg_replace($pattern3, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
						
					}
				?>
                                <?php $paramssld['video_ht_view4_loading_type'] = $paramssld['slider_navigation_type']; ?>
				<div class="load_more4"> 
					<div class="load_more_button4"><?=$paramssld['video_ht_view4_loadmore_text']; ?></div>
					<div class="loading4"><img src="<?php if($paramssld['video_ht_view4_loading_type'] == '1') {echo $path_siteArrow.'/loading1.gif';} 
					elseif ($paramssld['video_ht_view4_loading_type'] == '2') { echo $path_siteArrow.'/loading4.gif'; }
					elseif($paramssld['video_ht_view4_loading_type'] == '3') { echo $path_siteArrow.'/loading36.gif'; }
					elseif ($paramssld['video_ht_view4_loading_type'] == '4') { echo $path_siteArrow.'/loading51.gif'; }?>"></div>
					<script>
                                            jQuery(document).ready(function(){
                                                if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4").length){
                                                    jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4").on("click tap",function(){
                                                        if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()<jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?> #total").val()) {
                                                            var pagenum = parseInt(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()) + 1;
                                                            var perpage =<?=$count_page; ?>;
                                                            var galleryid="<?=$image->videogallery_id; ?>";
                                                            var thumbtext="<?php echo $paramssld["thumb_view_text"]; ?>";
                                                            var width="<?=$paramssld['video_ht_view9_video_width']; ?>";
                                                            var height="<?=$paramssld['video_ht_view9_video_height']; ?>";
                                                            //alert(pagenum+','+perpage+','+galleryid+','+width+','+height);
                                                            getresult(pagenum,perpage,galleryid,thumbtext,width,height);
                                                            }else{
                                                                jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4').hide();
                                                            }
                                                            return false;
                                                        });
                                    }
											if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()==jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?> #total").val()) {
												jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4').hide();
											}
							function getresult(pagenum,perpage,galleryid,thumbtext,width,height){
								var data = {
										action:"huge_it_video_gallery_ajax",
										task:'load_videos_lightbox',
										page:pagenum,
										perpage:perpage,
										galleryid:galleryid,
										thumbtext:thumbtext,
										width:width,
										height:height
								}
								jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .loading4').show();
								
								jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4').hide();

								jQuery.post("components/com_videogallerylite/ajax_url.php",data,function(response){
									if(response.success){
											//alert(response.success);
										var $objnewitems= jQuery(response.success);										
											jQuery('#huge_it_videogallery_container_<?php echo $videogalleryID; ?>').append( $objnewitems ).isotope('reloadItems').isotope({ sortBy: 'original-order' }).isotope( 'reLayout' );
										jQuery('#huge_it_videogallery_container_<?php echo $videogalleryID; ?> img').on('load',function(){
											
											//############# Hakob
											
											//############# End
											
											
											jQuery(".group1").colorbox({rel:'group1'});
											jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".vimeo").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
											jQuery(".inline").colorbox({inline:true, width:"50%"});
											jQuery(".callbacks").colorbox({
												onOpen:function(){ alert('onOpen: colorbox is about to open'); },
												onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
												onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
												onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
												onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
											});

											jQuery('.non-retina').colorbox({rel:'group5', transition:'none'})
											jQuery('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
											var defaultBlockWidth=<?php echo $paramssld['ht_view6_width']; ?>+20+<?php echo $paramssld['ht_view6_width']*2; ?>;
    										

  
											jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4').show();
											jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .loading4').hide();
											
											if(jQuery("#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .pagenum:last").val()==jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?> #total").val()) {
												jQuery('#huge_it_videogallery_content_<?php echo $videogalleryID; ?> .load_more_button4').hide();
											}
										});
										

									}else{
										alert("no");
									}
								},"json");
							}

						});
					</script>
				</div>
				<?php 
			}elseif($a==0){
		?>
			<div class="paginate4">
			<?php
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
			
			
			$checkREQ='';
			$pattern="/\?p=/";
			$pattern2="/&page-img[0-9]+=[0-9]+/";
			//$res=preg_match($pattern, $actual_link);
			if(preg_match($pattern, $actual_link)){

				if(preg_match($pattern2, $actual_link)){
					$actual_link=preg_replace($pattern2, '', $actual_link);
				}

				$checkREQ=$actual_link.'&page-img'.$videogalleryID.$pID;
				
			}else{
				$checkREQ='?page-img'.$videogalleryID.$pID;
				
			}
			//var_dump($res);  
			
			// Проверяем нужны ли стрелки назад  
				$pervpage='';
			if ($page != 1) $pervpage = '<a href= '.$checkREQ.'=1><i class="hugeiticons hugeiticons-fast-backward" ></i></a>  
			                             <a href= '.$checkREQ.'='. ($page - 1) .'><i class="hugeiticons hugeiticons-chevron-left"></i></a> ';  
			// Проверяем нужны ли стрелки вперед  
		       $nextpage='';
			if ($page != $total) $nextpage = '<a href= '.$checkREQ.'='. ($page + 1) .'><i class="hugeiticons hugeiticons-chevron-right"></i></a>  
			                                  <a href= '.$checkREQ.'=' .$total. '><i class="hugeiticons hugeiticons-fast-forward" ></i></a>'; 

			// Вывод меню  
			echo $pervpage.$page.'/'.$total.$nextpage;  

			?>
</div>
<?php 
}
?>
</section>
<script> 
 jQuery(function(){
	var defaultBlockWidth=<?php echo $paramssld['ht_view6_width']; ?>+20+<?php echo $paramssld['ht_view6_width']*2; ?>;
    var $container = jQuery('#huge_it_videogallery_container_<?php echo $videogalleryID; ?>');
    
    
     // add randomish size classes
   //    $container.find('.element_<?php echo $videogalleryID; ?>').each(function(){
   //      var $this = jQuery(this),
   //          number = parseInt( $this.find('.number').text(), 10 );
			// //alert(number);
   //      if ( number % 7 % 2 === 1 ) {
   //        $this.addClass('width2');
   //      }
   //      if ( number % 3 === 0 ) {
   //        $this.addClass('height2');
   //      }
   //    });
    
reSortImages();   
function reSortImages() {    
	$container.isotope({
	  itemSelector : '.element_<?php echo $videogalleryID; ?>',
	  masonry : {
		columnWidth : <?php echo $paramssld['ht_view6_width']; ?>+10+<?php echo $paramssld['ht_view6_border_width']*2; ?>
	  },
	  masonryHorizontal : {
		rowHeight: 'auto'
	  },
	  cellsByRow : {
		columnWidth : <?php echo $paramssld['ht_view6_width']; ?>,
		rowHeight : 'auto'
	  },
	  cellsByColumn : {
		columnWidth : <?php echo $paramssld['ht_view6_width']; ?>,
		rowHeight : 'auto'
	  },
	  getSortData : {
		symbol : function( $elem ) {
		  return $elem.attr('data-symbol');
		},
		category : function( $elem ) {
		  return $elem.attr('data-category');
		},
		number : function( $elem ) {
		  return parseInt( $elem.find('.number').text(), 10 );
		},
		weight : function( $elem ) {
		  return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
		},
		name : function ( $elem ) {
		  return $elem.find('.name').text();
		}
	  }
	})
};
      jQuery(window).resize(function(){
		reSortImages();
      });   
    
  
	  <?php if($paramssld['ht_view2_content_in_center_lightbox'] == 'on') { ?>
		centersection();
		reSortImages();
      jQuery(window).resize(function(){
                centersection();
                reSortImages();

      });
            function centersection(){
                var elementwidth=<?php echo $paramssld['ht_view6_width']; ?> + 10 + <?php echo $paramssld['ht_view6_border_width']*2; ?>;
                var enterycontent=jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?>").width();
                var enterycontentHeight=jQuery("#huge_it_videogallery_container_<?php echo $videogalleryID; ?>").height();
                var whole=~~(enterycontent/(elementwidth));
                if(whole==0){ 
                    jQuery("#huge_it_videogallery_container_moving_<?php echo $videogalleryID; ?>").width('100%');
                    reSortImages();
                    return false;
            }else { var sectionwidth= whole * elementwidth;}	
                jQuery("#huge_it_videogallery_container_moving_<?php echo $videogalleryID; ?>").width(sectionwidth).css({"margin":"0px auto","overflow":"hidden","position":"relative","height":enterycontentHeight});
//                 console.log(elementwidth + " " + enterycontent + " " + whole + " " + sectionwidth);
			}
      <?php } ?>
    

	  jQuery(window).load(function(){
		$container.isotope('reLayout');
	  });
  });
</script>

<?php 
break;
/////////////////////////////// VIEW 3 videogallery Huge IT Slider /////////////////////////////
   case 3:
       
       
       $paramssld['slider_description_height'] = 50;
       $paramssld['slider_description_width'] = 50;
       $paramssld['slider_title_position'] = 'right-top';
       $paramssld["slider_title_background_transparency"] = '70';
       $paramssld['slider_title_background_color'] = 'FFFFFF';
       $paramssld['slider_description_position'] = 'right-bottom';
       $paramssld['slider_description_background_color'] = '000000';
       $slider_title_margin = '';
     
	$sliderID=$cis_options_value->id;
	$slidertitle=$cis_options_value->name;
	$sliderheight=$cis_options_value->sl_height;
	$sliderwidth=$cis_options_value->sl_width;
	$slidereffect=$cis_options_value->videogallery_list_effects_s;
	$slidepausetime=($cis_options_value->pousetimeDescription+$cis_options_value->param);
	$sliderpauseonhover=$cis_options_value->pause_on_hover;
	$sliderposition=$cis_options_value->sl_position;
	$slidechangespeed=$cis_options_value->param;
	$trim_slider_title_position = trim($paramssld['slider_title_position']);
	$slideshow_title_position = explode('-', $trim_slider_title_position);
	$trim_slider_description_position = trim($paramssld['slider_description_position']);
	$slideshow_description_position = explode('-', $trim_slider_description_position);
	
        
	$hasyoutube=false;
	$hasvimeo=false;
        
	foreach ($images as $key => $image_row) {
		if(strpos($image_row->image_url,'youtu') !== false){$hasyoutube=true;}
		if(strpos($image_row->image_url,'vimeo') !== false){$hasvimeo=true;}
	}
    ?>
<script>var video_is_playing_videogallery_<?php echo $sliderID; ?>=false;</script>
<?php if ($hasvimeo==true){?>
<script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
<script>
jQuery(function(){
	
	var vimeoPlayer = document.querySelector('iframe');
		
	jQuery('iframe').each(function(){
				Froogaloop(this).addEvent('ready', ready);
	});

	jQuery(".sidedock,.controls").remove();
	function ready(player_id) {
	
		froogaloop = $f(player_id);
	
		function setupEventListeners() {
			function onPlay() {
				froogaloop.addEvent('play',
				function(){
					video_is_playing_videogallery_<?php echo $sliderID; ?>=true;
				});
			}
			function onPause() {
				froogaloop.addEvent('pause',
				function(){
					video_is_playing_videogallery_<?php echo $sliderID; ?>=false;
				});
			}					
			function stopVimeoVideo(player){
				Froogaloop(player).api('pause');
			}
			
			onPlay();
			onPause();
			jQuery('#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>, #huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>').click(function(){
				stopVimeoVideo(player_id);
			});
		}
		setupEventListeners();
	}
});		
</script>
<?php } ?>     
<?php if ($hasyoutube==true){?>

<script src="<?php echo JUri::root().'media/com_videogallerylite/js/youtube.lib.js' ; ?>"></script>
<script> 
  <?php  
	if (!function_exists('get_youtube_id_from_url')) {
	  function get_youtube_id_from_url($url){
		if (stristr($url,'youtu.be/'))
			{ preg_match('/(https:|http:|)(\/\/www\.|\/\/|)(.*?)\/(.{11})/i', $url, $final_ID); return $final_ID[4]; }
		else 
			{ preg_match('/(https:|http:|):(\/\/www\.|\/\/|)(.*?)\/(embed\/|watch\?v=|(.*?)&v=|v\/|e\/|.+\/|watch.*v=|)([a-z_A-Z0-9]{11})/i', $url, $IDD); return $IDD[6]; }
		}                                    
	}
  
			
	$i=0;
	 foreach ($images as $key => $image_row) {
		if($image_row->sl_type=="video" and strpos($image_row->image_url,'youtu') !== false){	
  ?> 
		var player_<?php echo $image_row->id; ?>;
<?php
		}else if (strpos($image_row->image_url,'vimeo') !== false){ ?>
				
<?php
		}else{continue;}
		$i++;
	}
?>
		video_is_playing_videogallery_<?php echo $sliderID; ?>=false;
		function onYouTubeIframeAPIReady() {
			<?php
			foreach ($images as $key => $image_row) {?>
							
				<?php if($image_row->sl_type=="video" and strpos($image_row->image_url,'youtu') !== false){
			?> 
				player_<?php echo $image_row->id; ?> = new YT.Player('video_id_videogallery_<?php echo $sliderID; ?>_<?php echo $key;?>', {
				  height: '<?php echo $sliderheight; ?>',
				  width: '<?php echo $sliderwidth; ?>',
				  videoId: '<?php echo get_youtube_id_from_url($image_row->image_url); ?>',
				   playerVars: {
					'controls': <?php if ($images[$key]->sl_url=="on"){ echo 1;}else{echo 0;} ?>,           
					'showinfo': <?php if ($images[$key]->link_target=="on"){ echo 1;}else{echo 0;} ?>
				  },
				  events: {
					'onStateChange': onPlayerStateChange_<?php echo $image_row->id; ?>,
					'loop':1
				  }
				});
			<?php
				}else{continue;}
			}
			?>
		}
		
		
<?php			
	foreach ($images as $key => $image_row) {
		if($image_row->sl_type=="video" and strpos($image_row->image_url,'youtu') !== false){
?>
		 function onPlayerStateChange_<?php echo $image_row->id; ?>(event) {
			//(event.data);
                        <?php $images[$key]->name = htmlspecialchars($images[$key]->name, ENT_QUOTES); ?>
			if (event.data == YT.PlayerState.PLAYING) {
				event.target.setPlaybackQuality('<?php echo $images[$key]->name; ?>');
				video_is_playing_videogallery_<?php echo $sliderID; ?>=true;
			}
			else{
				video_is_playing_videogallery_<?php echo $sliderID; ?>=false;
			}
		  }
<?php 
	    }else{continue;}
	}
?>
	function stopYoutubeVideo() {
		<?php 
		$i=0;
		foreach ($images as $key => $image_row) {
			if($image_row->sl_type=="video" and strpos($image_row->image_url,'youtu') !== false){	
		?>
			player_<?php echo $image_row->id; ?>.pauseVideo();
		<?php
			}else{continue;}
				$i++;
			}
		?>
	}

</script>
<?php } ?>
	<script>
	var data_videogallery_<?php echo $sliderID; ?> = [];      
	var event_stack_videogallery_<?php echo $sliderID; ?> = [];
	<?php
	//	$images=array_reverse($images);
		//$recent_posts = wp_get_recent_posts( $args, ARRAY_A );

		$i=0;
		
		foreach($images as $image){
			  	$imagerowstype=$image->sl_type;
				if($image->sl_type == ''){
				$imagerowstype='image';
				}
				switch($imagerowstype){
							
					case 'image':
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]=[];';
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["id"]="'.$i.'";';
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["image_url"]="'.$image->image_url.'";';
						
						
						$strdesription=str_replace('"',"'",$image->description);
						$strdesription=preg_replace( "/\r|\n/", " ", $strdesription );
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["description"]="'.$strdesription.'";';

						
						$stralt=str_replace('"',"'",$image->name);
						$stralt=preg_replace( "/\r|\n/", " ", $stralt );
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["alt"]="'.$stralt.'";';
						$i++;
					break;
					
					case 'video':
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]=[];';
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["id"]="'.$i.'";';
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["image_url"]="'.$image->image_url.'";';
						
						
						$strdesription=str_replace('"',"'",$image->description);
						$strdesription=preg_replace( "/\r|\n/", " ", $strdesription );
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["description"]="'.$strdesription.'";';

						
						$stralt=str_replace('"',"'",$image->name);
						$stralt=preg_replace( "/\r|\n/", " ", $stralt );
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["alt"]="'.$stralt.'";';
						$i++;
					break;
					
					
					/*case 'last_posts':
					
					foreach($recent_posts as $keyl => $recentimage){
					if(get_the_post_thumbnail($recentimage["ID"], 'thumbnail') != ''){
						if($keyl < $image->sl_url){
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]=[];';
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["id"]="'.$i.'";';
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["image_url"]="'.$recentimage['guid'].'";';
						
						
						$strdesription=str_replace('"',"'",$recentimage['post_content']);
						$strdesription=preg_replace( "/\r|\n/", " ", $strdesription );
						$strdesription=substr_replace($strdesription, "",$image->description);
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["description"]="'.$strdesription.'";';

						
						$stralt=str_replace('"',"'",$recentimage['post_title']);
						$stralt=preg_replace( "/\r|\n/", " ", $stralt );
						echo 'data_videogallery_'.$sliderID.'["'.$i.'"]["alt"]="'.$stralt.'";';
						$i++;
						}
					}
					}
					
					break;
					*/
			}
			
			
		}
	?>
	
	

      var huge_it_trans_in_progress_videogallery_<?php echo $sliderID; ?> = false;
      var huge_it_transition_duration_videogallery_<?php echo $sliderID; ?> = <?php echo $slidechangespeed;?>;
	  var huge_it_playInterval_videogallery_<?php echo $sliderID; ?>;
      // Stop autoplay.
      window.clearInterval(huge_it_playInterval_videogallery_<?php echo $sliderID; ?>);
	 // alert('huge_it_current_key_videogallery_<?php echo $sliderID; ?>');
     var huge_it_current_key_videogallery_<?php echo $sliderID; ?> = '<?php echo (isset($current_key) ? $current_key : ''); ?>';
	 function huge_it_move_dots_videogallery_<?php echo $sliderID; ?>() {
        var image_left = jQuery(".huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").position().left;
        var image_right = jQuery(".huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").position().left + jQuery(".huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").outerWidth(true);
       
      }
      function huge_it_testBrowser_cssTransitions_videogallery_<?php echo $sliderID; ?>() {
        return huge_it_testDom_videogallery_<?php echo $sliderID; ?>('Transition');
      }
      function huge_it_testBrowser_cssTransforms3d_videogallery_<?php echo $sliderID; ?>() {
        return huge_it_testDom_videogallery_<?php echo $sliderID; ?>('Perspective');
      }
      function huge_it_testDom_videogallery_<?php echo $sliderID; ?>(prop) {
        // Browser vendor CSS prefixes.
        var browserVendors = ['', '-webkit-', '-moz-', '-ms-', '-o-', '-khtml-'];
        // Browser vendor DOM prefixes.
        var domPrefixes = ['', 'Webkit', 'Moz', 'ms', 'O', 'Khtml'];
        var i = domPrefixes.length;
        while (i--) {
          if (typeof document.body.style[domPrefixes[i] + prop] !== 'undefined') {
            return true;
          }
        }
        return false;
      }
		function huge_it_cube_videogallery_<?php echo $sliderID; ?>(tz, ntx, nty, nrx, nry, wrx, wry, current_image_class, next_image_class, direction) {
        /* If browser does not support 3d transforms/CSS transitions.*/
        if (!huge_it_testBrowser_cssTransitions_videogallery_<?php echo $sliderID; ?>()) {
			jQuery(".huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_videogallery_<?php echo $sliderID; ?> + "_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>");
          return huge_it_fallback_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
        }
        if (!huge_it_testBrowser_cssTransforms3d_videogallery_<?php echo $sliderID; ?>()) {
          return huge_it_fallback3d_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
        }
        huge_it_trans_in_progress_videogallery_<?php echo $sliderID; ?> = true;
        /* Set active thumbnail.*/
		jQuery(".huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>");  
		jQuery("#huge_it_dots_" + huge_it_current_key_videogallery_<?php echo $sliderID; ?> + "_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>");
        jQuery(".huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>").css('perspective', 1000);
        jQuery(current_image_class).css({
          transform : 'translateZ(' + tz + 'px)',
          backfaceVisibility : 'hidden'
        });
		
		 jQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>,.huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_image_item_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_image_second_item_videogallery_<?php echo $sliderID; ?> ").css('overflow', 'visible');
		
        jQuery(next_image_class).css({
          opacity : 1,
          filter: 'Alpha(opacity=100)',
          backfaceVisibility : 'hidden',
          transform : 'translateY(' + nty + 'px) translateX(' + ntx + 'px) rotateY('+ nry +'deg) rotateX('+ nrx +'deg)'
        });
        jQuery(".huge_it_slider_videogallery_<?php echo $sliderID; ?>").css({
          transform: 'translateZ(-' + tz + 'px)',
          transformStyle: 'preserve-3d'
        });
        /* Execution steps.*/
        setTimeout(function () {
          jQuery(".huge_it_slider_videogallery_<?php echo $sliderID; ?>").css({
            transition: 'all ' + huge_it_transition_duration_videogallery_<?php echo $sliderID; ?> + 'ms ease-in-out',
            transform: 'translateZ(-' + tz + 'px) rotateX('+ wrx +'deg) rotateY('+ wry +'deg)'
          });
        }, 20);
        /* After transition.*/
        jQuery(".huge_it_slider_videogallery_<?php echo $sliderID; ?>").one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(huge_it_after_trans));
        function huge_it_after_trans() {
          /*if (huge_it_from_focus_videogallery_<?php echo $sliderID; ?>) {
            huge_it_from_focus_videogallery_<?php echo $sliderID; ?> = false;
            return;
          }*/
		  jQuery(".huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_image_item_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_image_second_item_videogallery_<?php echo $sliderID; ?> ").css('overflow', 'hidden');
		  jQuery(".huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>").removeAttr('style');
          jQuery(current_image_class).removeAttr('style');
          jQuery(next_image_class).removeAttr('style');
          jQuery(".huge_it_slider_videogallery_<?php echo $sliderID; ?>").removeAttr('style');
          jQuery(current_image_class).css({'opacity' : 0, filter: 'Alpha(opacity=0)', 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, filter: 'Alpha(opacity=100)', 'z-index' : 2});
         // huge_it_change_watermark_container_videogallery_<?php echo $sliderID; ?>();
          huge_it_trans_in_progress_videogallery_<?php echo $sliderID; ?> = false;
          if (typeof event_stack_videogallery_<?php echo $sliderID; ?> !== 'undefined' && event_stack_videogallery_<?php echo $sliderID; ?>.length > 0) {
            key = event_stack_videogallery_<?php echo $sliderID; ?>[0].split("-");
            event_stack_videogallery_<?php echo $sliderID; ?>.shift();
            huge_it_change_image_videogallery_<?php echo $sliderID; ?>(key[0], key[1], data_videogallery_<?php echo $sliderID; ?>, true,false);
          }
        }
      }
      function huge_it_cubeH_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        /* Set to half of image width.*/
        var dimension = jQuery(current_image_class).width() / 2;
        if (direction == 'right') {
          huge_it_cube_videogallery_<?php echo $sliderID; ?>(dimension, dimension, 0, 0, 90, 0, -90, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          huge_it_cube_videogallery_<?php echo $sliderID; ?>(dimension, -dimension, 0, 0, -90, 0, 90, current_image_class, next_image_class, direction);
        }
      }
      function huge_it_cubeV_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        /* Set to half of image height.*/
        var dimension = jQuery(current_image_class).height() / 2;
        /* If next slide.*/
        if (direction == 'right') {
          huge_it_cube_videogallery_<?php echo $sliderID; ?>(dimension, 0, -dimension, 90, 0, -90, 0, current_image_class, next_image_class, direction);
        }
        else if (direction == 'left') {
          huge_it_cube_videogallery_<?php echo $sliderID; ?>(dimension, 0, dimension, -90, 0, 90, 0, current_image_class, next_image_class, direction);
        }
      }
      /* For browsers that does not support transitions.*/
      function huge_it_fallback_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_fade_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
      }
      /* For browsers that support transitions, but not 3d transforms (only used if primary transition makes use of 3d-transforms).*/
      function huge_it_fallback3d_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_sliceV_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
      }
      function huge_it_none_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
        jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});

        /* Set active thumbnail.*/
        jQuery(".huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_videogallery_<?php echo $sliderID; ?> + "_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>");
      }
      function huge_it_fade_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
		if (huge_it_testBrowser_cssTransitions_videogallery_<?php echo $sliderID; ?>()) {
          jQuery(next_image_class).css('transition', 'opacity ' + huge_it_transition_duration_videogallery_<?php echo $sliderID; ?> + 'ms linear');
		  jQuery(current_image_class).css('transition', 'opacity ' + huge_it_transition_duration_videogallery_<?php echo $sliderID; ?> + 'ms linear');
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
        }
        else {
          jQuery(current_image_class).animate({'opacity' : 0, 'z-index' : 1}, huge_it_transition_duration_videogallery_<?php echo $sliderID; ?>);
          jQuery(next_image_class).animate({
              'opacity' : 1,
              'z-index': 2
            }, {
              duration: huge_it_transition_duration_videogallery_<?php echo $sliderID; ?>,
              complete: function () {return false;}
            });
          // For IE.
          jQuery(current_image_class).fadeTo(huge_it_transition_duration_videogallery_<?php echo $sliderID; ?>, 0);
          jQuery(next_image_class).fadeTo(huge_it_transition_duration_videogallery_<?php echo $sliderID; ?>, 1);
        }

		jQuery(".huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>");
		jQuery("#huge_it_dots_" + huge_it_current_key_videogallery_<?php echo $sliderID; ?> + "_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>");
      }
      function huge_it_grid_videogallery_<?php echo $sliderID; ?>(cols, rows, ro, tx, ty, sc, op, current_image_class, next_image_class, direction) {
        /* If browser does not support CSS transitions.*/
        if (!huge_it_testBrowser_cssTransitions_videogallery_<?php echo $sliderID; ?>()) {
			jQuery(".huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_videogallery_<?php echo $sliderID; ?> + "_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>");
          return huge_it_fallback_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
		  
        }
        huge_it_trans_in_progress_videogallery_<?php echo $sliderID; ?> = true;
        /* Set active thumbnail.*/
		jQuery(".huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>");
        jQuery("#huge_it_dots_" + huge_it_current_key_videogallery_<?php echo $sliderID; ?> + "_videogallery_<?php echo $sliderID; ?>").removeClass("huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?>").addClass("huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>");
        /* The time (in ms) added to/subtracted from the delay total for each new gridlet.*/
        var count = (huge_it_transition_duration_videogallery_<?php echo $sliderID; ?>) / (cols + rows);
        /* Gridlet creator (divisions of the image grid, positioned with background-images to replicate the look of an entire slide image when assembled)*/
        function huge_it_gridlet(width, height, top, img_top, left, img_left, src, imgWidth, imgHeight, c, r) {
          var delay = (c + r) * count;
          /* Return a gridlet elem with styles for specific transition.*/
          return jQuery('<div class="huge_it_gridlet_videogallery_<?php echo $sliderID; ?>" />').css({
            width : width,
            height : height,
            top : top,
            left : left,
            backgroundImage : 'url("' + src + '")',
            backgroundColor: jQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").css("background-color"),
            /*backgroundColor: rgba(0, 0, 0, 0),*/
            backgroundRepeat: 'no-repeat',
            backgroundPosition : img_left + 'px ' + img_top + 'px',
            backgroundSize : imgWidth + 'px ' + imgHeight + 'px',
            transition : 'all ' + huge_it_transition_duration_videogallery_<?php echo $sliderID; ?> + 'ms ease-in-out ' + delay + 'ms',
            transform : 'none'
          });
        }
        /* Get the current slide's image.*/
        var cur_img = jQuery(current_image_class).find('img');
        /* Create a grid to hold the gridlets.*/
        var grid = jQuery('<div />').addClass('huge_it_grid_videogallery_<?php echo $sliderID; ?>');
        /* Prepend the grid to the next slide (i.e. so it's above the slide image).*/
        jQuery(current_image_class).prepend(grid);
        /* vars to calculate positioning/size of gridlets*/
        var cont = jQuery(".huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>");
        var imgWidth = cur_img.width();
        var imgHeight = cur_img.height();
        var contWidth = cont.width(),
            contHeight = cont.height(),
            imgSrc = cur_img.attr('src'),/*.replace('/thumb', ''),*/
            colWidth = Math.floor(contWidth / cols),
            rowHeight = Math.floor(contHeight / rows),
            colRemainder = contWidth - (cols * colWidth),
            colAdd = Math.ceil(colRemainder / cols),
            rowRemainder = contHeight - (rows * rowHeight),
            rowAdd = Math.ceil(rowRemainder / rows),
            leftDist = 0,
            img_leftDist = (jQuery(".huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>").width() - cur_img.width()) / 2;
        /* tx/ty args can be passed as 'auto'/'min-auto' (meaning use slide width/height or negative slide width/height).*/
        tx = tx === 'auto' ? contWidth : tx;
        tx = tx === 'min-auto' ? - contWidth : tx;
        ty = ty === 'auto' ? contHeight : ty;
        ty = ty === 'min-auto' ? - contHeight : ty;
        /* Loop through cols*/
        for (var i = 0; i < cols; i++) {
          var topDist = 0,
              img_topDst = (jQuery(".huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>").height() - cur_img.height()) / 2,
              newColWidth = colWidth;
          /* If imgWidth (px) does not divide cleanly into the specified number of cols, adjust individual col widths to create correct total.*/
          if (colRemainder > 0) {
            var add = colRemainder >= colAdd ? colAdd : colRemainder;
            newColWidth += add;
            colRemainder -= add;
          }
          /* Nested loop to create row gridlets for each col.*/
          for (var j = 0; j < rows; j++)  {
            var newRowHeight = rowHeight,
                newRowRemainder = rowRemainder;
            /* If contHeight (px) does not divide cleanly into the specified number of rows, adjust individual row heights to create correct total.*/
            if (newRowRemainder > 0) {
              add = newRowRemainder >= rowAdd ? rowAdd : rowRemainder;
              newRowHeight += add;
              newRowRemainder -= add;
            }
            /* Create & append gridlet to grid.*/
            grid.append(huge_it_gridlet(newColWidth, newRowHeight, topDist, img_topDst, leftDist, img_leftDist, imgSrc, imgWidth, imgHeight, i, j));
            topDist += newRowHeight;
            img_topDst -= newRowHeight;
          }
          img_leftDist -= newColWidth;
          leftDist += newColWidth;
        }
        /* Set event listener on last gridlet to finish transitioning.*/
        var last_gridlet = grid.children().last();
        /* Show grid & hide the image it replaces.*/
        grid.show();
        cur_img.css('opacity', 0);
        /* Add identifying classes to corner gridlets (useful if applying border radius).*/
        grid.children().first().addClass('rs-top-left');
        grid.children().last().addClass('rs-bottom-right');
        grid.children().eq(rows - 1).addClass('rs-bottom-left');
        grid.children().eq(- rows).addClass('rs-top-right');
        /* Execution steps.*/
        setTimeout(function () {
          grid.children().css({
            opacity: op,
            transform: 'rotate('+ ro +'deg) translateX('+ tx +'px) translateY('+ ty +'px) scale('+ sc +')'
          });
        }, 1);
        jQuery(next_image_class).css('opacity', 1);
        /* After transition.*/
        jQuery(last_gridlet).one('webkitTransitionEnd transitionend otransitionend oTransitionEnd mstransitionend', jQuery.proxy(huge_it_after_trans));
        function huge_it_after_trans() {
          jQuery(current_image_class).css({'opacity' : 0, 'z-index': 1});
          jQuery(next_image_class).css({'opacity' : 1, 'z-index' : 2});
          cur_img.css('opacity', 1);
          grid.remove();
          huge_it_trans_in_progress_videogallery_<?php echo $sliderID; ?> = false;
          if (typeof event_stack_videogallery_<?php echo $sliderID; ?> !== 'undefined' && event_stack_videogallery_<?php echo $sliderID; ?>.length > 0) {
            key = event_stack_videogallery_<?php echo $sliderID; ?>[0].split("-");
            event_stack_videogallery_<?php echo $sliderID; ?>.shift();
            huge_it_change_image_videogallery_<?php echo $sliderID; ?>(key[0], key[1], data_videogallery_<?php echo $sliderID; ?>, true,false);
          }
        }
      }
      function huge_it_sliceH_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 8, 0, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_sliceV_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'min-auto';
        }
        else if (direction == 'left') {
          var translateY = 'auto';
        }
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(10, 1, 0, 0, translateY, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_slideV_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateY = 'auto';
        }
        else if (direction == 'left') {
          var translateY = 'min-auto';
        }
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 1, 0, 0, translateY, 1, 1, current_image_class, next_image_class, direction);
      }
      function huge_it_slideH_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var translateX = 'min-auto';
        }
        else if (direction == 'left') {
          var translateX = 'auto';
        }
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 1, 0, translateX, 0, 1, 1, current_image_class, next_image_class, direction);
      }
      function huge_it_scaleOut_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 1, 0, 0, 0, 1.5, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_scaleIn_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 1, 0, 0, 0, 0.5, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_blockScale_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(8, 6, 0, 0, 0, .6, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_kaleidoscope_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(10, 8, 0, 0, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_fan_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        if (direction == 'right') {
          var rotate = 45;
          var translateX = 100;
        }
        else if (direction == 'left') {
          var rotate = -45;
          var translateX = -100;
        }
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 10, rotate, translateX, 0, 1, 0, current_image_class, next_image_class, direction);
      }
      function huge_it_blindV_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(1, 8, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function huge_it_blindH_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        huge_it_grid_videogallery_<?php echo $sliderID; ?>(10, 1, 0, 0, 0, .7, 0, current_image_class, next_image_class);
      }
      function huge_it_random_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction) {
        var anims = ['sliceH', 'sliceV', 'slideH', 'slideV', 'scaleOut', 'scaleIn', 'blockScale', 'kaleidoscope', 'fan', 'blindH', 'blindV'];
        /* Pick a random transition from the anims array.*/
        this["huge_it_" + anims[Math.floor(Math.random() * anims.length)] + "_videogallery_<?php echo $sliderID; ?>"](current_image_class, next_image_class, direction);
      }
      
      function iterator_videogallery_<?php echo $sliderID; ?>() {
        var iterator = 1;

        return iterator;
     }
	 
     function huge_it_change_image_videogallery_<?php echo $sliderID; ?>(current_key, key, data_videogallery_<?php echo $sliderID; ?>, from_effect,clicked) {
		
        if (data_videogallery_<?php echo $sliderID; ?>[key]) {
			
			if(video_is_playing_videogallery_<?php echo $sliderID; ?> && !clicked){
				return false;
			}
        
          if (!from_effect) {
			
            // Change image key.
            jQuery("#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>").val(key);
             // if (current_key == '-2') { /* Dots.*/
				current_key = jQuery(".huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?>").attr("data-image_key");
			//  }
          }

          if (huge_it_trans_in_progress_videogallery_<?php echo $sliderID; ?>) {
			//errorlogjQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").after(" --IN TRANSACTION-- <br />");
            event_stack_videogallery_<?php echo $sliderID; ?>.push(current_key + '-' + key);
            return;
          }
		  
          var direction = 'right';
          if (huge_it_current_key_videogallery_<?php echo $sliderID; ?> > key) {
            var direction = 'left';
          }
          else if (huge_it_current_key_videogallery_<?php echo $sliderID; ?> == key) {
            return false;
          }

         
          // Set active thumbnail position.
      
          huge_it_current_key_videogallery_<?php echo $sliderID; ?> = key;
          //Change image id, title, description.
          jQuery("#huge_it_slideshow_image_videogallery_<?php echo $sliderID; ?>").attr('data-image_id', data_videogallery_<?php echo $sliderID; ?>[key]["id"]);
		  
		  
		  jQuery(".huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>").html(data_videogallery_<?php echo $sliderID; ?>[key]["alt"]);
          jQuery(".huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>").html(data_videogallery_<?php echo $sliderID; ?>[key]["description"]);
        
		  var current_image_class = "#image_id_videogallery_<?php echo $sliderID; ?>_" + data_videogallery_<?php echo $sliderID; ?>[current_key]["id"];
          var next_image_class = "#image_id_videogallery_<?php echo $sliderID; ?>_" + data_videogallery_<?php echo $sliderID; ?>[key]["id"];
          
		  
		if(jQuery(current_image_class).find('.huge_it_video_frame_videogallery_<?php echo $sliderID; ?>').length>0) {
			var streffect='<?php echo $slidereffect; ?>';
			if(streffect=="cubeV" || streffect=="cubeH" || streffect=="none" || streffect=="fade"){
				huge_it_<?php echo $slidereffect; ?>_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
			}else{	
				huge_it_fade_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
			}	
		}else{	
				huge_it_<?php echo $slidereffect; ?>_videogallery_<?php echo $sliderID; ?>(current_image_class, next_image_class, direction);
		}	
		  
		  
		jQuery('.huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>').removeClass('none');
		if(jQuery('.huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>').html()==""){jQuery('.huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>').addClass('none');}

		jQuery('.huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>').removeClass('none');
		if(jQuery('.huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>').html()==""){jQuery('.huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>').addClass('none');}
	  
		  
		  
		  jQuery(current_image_class).find('.huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>').addClass('none');
		  jQuery(current_image_class).find('.huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>').addClass('none');
		
		

		  
		  //errorlogjQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").after("--cur-key="+current_key+" --cur-img-class="+current_image_class+" nxt-img-class="+next_image_class+"--");
			huge_it_move_dots_videogallery_<?php echo $sliderID; ?>();
			<?php if ($hasyoutube==true){?>stopYoutubeVideo(); <?php } ?>
			window.clearInterval(huge_it_playInterval_videogallery_<?php echo $sliderID; ?>);
			play_videogallery_<?php echo $sliderID; ?>();
        }

      }
	  
      function huge_it_popup_resize_videogallery_<?php echo $sliderID; ?>() {

		var staticsliderwidth=<?php echo $sliderwidth;?>;
		var sliderwidth=<?php echo $sliderwidth;?>;
		
		var bodyWidth=jQuery(window).width();
        var parentWidth = jQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").parent().width();
		//if responsive js late responsive.js @  take body size and not parent div
		if(sliderwidth>parentWidth){sliderwidth=parentWidth;}
		if(sliderwidth>bodyWidth){sliderwidth=bodyWidth;}
		
		var str=(<?php echo $sliderheight;?>/staticsliderwidth);
		
		jQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").css({width: (sliderwidth)});
		jQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").css({height: ((sliderwidth) * str)});
		jQuery(".huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>").css({width: (sliderwidth)});
		jQuery(".huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>").css({height: ((sliderwidth) * str)});
			
		if("<?php echo $slideshow_title_position[1]; ?>"=="middle"){var titlemargintopminus=jQuery(".huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>").outerHeight()/2;}		
		if("<?php echo $slideshow_title_position[0]; ?>"=="center"){var titlemarginleftminus=jQuery(".huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>").outerWidth()/2;}		
		jQuery(".huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>").css({cssText: "margin-top:-" + titlemargintopminus + "px; margin-left:-"+titlemarginleftminus+"px;"});
		
		if("<?php echo $slideshow_description_position[1]; ?>"=="middle"){var descriptionmargintopminus=jQuery(".huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>").outerHeight()/2;}	
		if("<?php echo $slideshow_description_position[0]; ?>"=="center"){var descriptionmarginleftminus=jQuery(".huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>").outerWidth()/2;}
		jQuery(".huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>").css({cssText: "margin-top:-" + descriptionmargintopminus + "px; margin-left:-"+descriptionmarginleftminus+"px;"});		
		
		
		if("<?php echo $paramssld['slider_crop_image']; ?>"=="resize"){
			jQuery(".huge_it_slideshow_image_videogallery_<?php echo $sliderID; ?>, .huge_it_slideshow_image_item1_videogallery_<?php echo $sliderID; ?> img, .huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?> img").css({
				cssText: "width:" + sliderwidth + "px; height:" + ((sliderwidth) * str)	+"px;"
			});
		}else {
			jQuery(".huge_it_slideshow_image_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_image_item1_videogallery_<?php echo $sliderID; ?>,.huge_it_slideshow_image_item2_videogallery_<?php echo $sliderID; ?>").css({
			cssText: "max-width: " + sliderwidth + "px !important; max-height: " + (sliderwidth * str) + "px !important;"
		  });
		}
		
		jQuery('.huge_it_video_frame_videogallery_<?php echo $sliderID; ?>').each(function (e) {
          jQuery(this).width(sliderwidth);
          jQuery(this).height(sliderwidth * str);
        });
      }
      
      jQuery(window).load(function () {
		jQuery(window).resize(function() {
			huge_it_popup_resize_videogallery_<?php echo $sliderID; ?>();
		});
		
		huge_it_popup_resize_videogallery_<?php echo $sliderID; ?>();
        /* Disable right click.*/
        jQuery('div[id^="huge_it_container"]').bind("contextmenu", function () {
          return false;
        });
        			
		/*HOVER SLIDESHOW*/
		jQuery("#huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>, .huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>, .huge_it_slideshow_dots_container_videogallery_<?php echo $sliderID; ?>,#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>,#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>").hover(function(){
			//errorlogjQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").after(" -- hover -- <br /> ");
			jQuery("#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>").css({'display':'inline'});
			jQuery("#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>").css({'display':'inline'});
		},function(){
			jQuery("#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>").css({'display':'none'});
			jQuery("#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>").css({'display':'none'});
		});
		var pausehover="<?php echo $sliderpauseonhover;?>";
		if(pausehover=="on"){
			jQuery("#huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>, .huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>, .huge_it_slideshow_dots_container_videogallery_<?php echo $sliderID; ?>,#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>,#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>").hover(function(){
				window.clearInterval(huge_it_playInterval_videogallery_<?php echo $sliderID; ?>);
			},function(){
				window.clearInterval(huge_it_playInterval_videogallery_<?php echo $sliderID; ?>);
				play_videogallery_<?php echo $sliderID; ?>();
			});		
		}	
          play_videogallery_<?php echo $sliderID; ?>();        
      });

      function play_videogallery_<?php echo $sliderID; ?>() {	   
        /* Play.*/
		//errorlogjQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").after(" -- paly  ---- ");
        huge_it_playInterval_videogallery_<?php echo $sliderID; ?> = setInterval(function () {
			//errorlogjQuery(".huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>").after(" -- time left ---- ");
          var iterator = 1;
          huge_it_change_image_videogallery_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()) + iterator) % data_videogallery_<?php echo $sliderID; ?>.length, data_videogallery_<?php echo $sliderID; ?>,false,false);
        }, '<?php echo $slidepausetime; ?>');
      }
	  
      jQuery(window).focus(function() {
       /*event_stack_videogallery_<?php echo $sliderID; ?> = [];*/
        var i_videogallery_<?php echo $sliderID; ?> = 0;
        jQuery(".huge_it_slider_videogallery_<?php echo $sliderID; ?>").children("div").each(function () {
          if (jQuery(this).css('opacity') == 1) {
            jQuery("#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>").val(i_videogallery_<?php echo $sliderID; ?>);
          }
          i_videogallery_<?php echo $sliderID; ?>++;
        });
      });
      jQuery(window).blur(function() {
        event_stack_videogallery_<?php echo $sliderID; ?> = [];
        window.clearInterval(huge_it_playInterval_videogallery_<?php echo $sliderID; ?>);
      });      
    </script>
	<style>				
	 .huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?> {
		height:<?php echo $sliderheight; ?>px;
		width:<?php  echo $sliderwidth; ?>px;
		position:relative;
		display: block;
		text-align: center;
		/*HEIGHT FROM HEADER.PHP*/
		clear:both;
		<?php if($sliderposition=="left"){ $position='float:left;';}elseif($sliderposition=="right"){$position='float:right;';}else{$position='float:none; margin:0px auto;';} ?>
		<?php echo $position;  ?>
		
		border-style:solid;
		border-left:0px !important;
		border-right:0px !important;
	}


	.huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?> * {
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
	}
		 

	  .huge_it_slideshow_image_videogallery_<?php echo $sliderID; ?> {
			/*width:100%;*/
	  }

	  #huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>,
	  #huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
		cursor: pointer;
		display:none;
		display: block;
		
		height: 100%;
		outline: medium none;
		position: absolute;

		/*z-index: 10130;*/
		z-index: 13;
		bottom:25px;
		top:50%;		
	  }
	 

	  #huge_it_slideshow_left-ico_videogallery_<?php echo $sliderID; ?>,
	  #huge_it_slideshow_right-ico_videogallery_<?php echo $sliderID; ?> {
		z-index: 13;
		-moz-box-sizing: content-box;
		box-sizing: content-box;
		cursor: pointer;
		display: table;
		left: -9999px;
		line-height: 0;
		margin-top: -15px;
		position: absolute;
		top: 50%;
		/*z-index: 10135;*/
	  }
	  #huge_it_slideshow_left-ico_videogallery_<?php echo $sliderID; ?>:hover,
	  #huge_it_slideshow_right-ico_videogallery_<?php echo $sliderID; ?>:hover {
		cursor: pointer;
	  }
	  
	  .huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?> {
		display: table;
		position: relative;
		top:0px;
		left:0px;
		text-align: center;
		vertical-align: middle;
		width:100%;
	  }	  
		
	  .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> {
		text-decoration: none;
		position: absolute;
		z-index: 11;
		display: inline-block;
		<?php  if($paramssld['slider_title_has_margin']=='on'){
				$slider_title_width=($paramssld['slider_title_width']-6);
				$slider_title_height=($paramssld['slider_title_height']-6);
				$slider_title_margin="3";
			}else{
				$slider_title_width=($paramssld['slider_title_width']);
				$slider_title_height=($paramssld['slider_title_height']);
				$slider_title_margin="0";
			}  ?>
		
		width:<?php echo $slider_title_width; ?>%;
		/*height:<?php echo $slider_title_height; ?>%;*/
		
		<?php 
			if($slideshow_title_position[0]=="left"){echo 'left:'.$slider_title_margin.'%;';}
			elseif($slideshow_title_position[0]=="center"){echo 'left:50%;';}
			elseif($slideshow_title_position[0]=="right"){echo 'right:'.$slider_title_margin.'%;';}
			
			if($slideshow_title_position[1]=="top"){echo 'top:'.$slider_title_margin.'%;';}
			elseif($slideshow_title_position[1]=="middle"){echo 'top:50%;';}
			elseif($slideshow_title_position[1]=="bottom"){echo 'bottom:'.$slider_title_margin.'%;';}
		 ?>
		padding:2%;
		text-align:<?php echo $paramssld['slider_title_text_align']; ?>;  
		font-weight:bold;
		color:#<?php echo $paramssld['slider_title_color']; ?>;
			
		background:<?php 			
				list($r,$g,$b) = array_map('hexdec',str_split($paramssld['slider_title_background_color'],2));
				$titleopacity=$paramssld["slider_title_background_transparency"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important'; 		
		?>;
		border-style:solid;
		font-size:<?php echo $paramssld['slider_title_font_size']; ?>px;
		border-width:<?php echo $paramssld['slider_title_border_size']; ?>px;
		border-color:#<?php echo $paramssld['slider_title_border_color']; ?>;
		border-radius:<?php echo $paramssld['slider_title_border_radius']; ?>px;
	  }
	  	  
	  .huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?> {
		text-decoration: none;
		position: absolute;
		z-index: 11;
		border-style:solid;
		display: inline-block;
		<?php  if($paramssld['slider_description_has_margin']=='on'){
				$slider_description_width=($paramssld['slider_description_width']-6);
				$slider_description_height=($paramssld['slider_description_height']-6);
				$slider_description_margin="3";
			}else{
				$slider_description_width=($paramssld['slider_description_width']);
				$slider_descriptione_height=($paramssld['slider_description_height']);
				$slider_description_margin="0";
			}  ?>
		
		width:<?php echo $slider_description_width; ?>%;
		/*height:<?php  //echo $slider_description_height; ?>%;*/
		<?php 
			if($slideshow_description_position[0]=="left"){echo 'left:'.$slider_description_margin.'%;';}
			elseif($slideshow_description_position[0]=="center"){echo 'left:50%;';}
			elseif($slideshow_description_position[0]=="right"){echo 'right:'.$slider_description_margin.'%;';}
			
			if($slideshow_description_position[1]=="top"){echo 'top:'.$slider_description_margin.'%;';}
			elseif($slideshow_description_position[1]=="middle"){echo 'top:50%;';}
			elseif($slideshow_description_position[1]=="bottom"){echo 'bottom:'.$slider_description_margin.'%;';}
		 ?>
		padding:3%;
		text-align:<?php echo $paramssld['slider_description_text_align']; ?>;  
		color:#<?php echo $paramssld['slider_description_color']; ?>;
		
		background:<?php 
			list($r,$g,$b) = array_map('hexdec',str_split($paramssld['slider_description_background_color'],2));	
			$descriptionopacity=$paramssld["slider_description_background_transparency"]/100;
			echo 'rgba('.$r.','.$g.','.$b.','.$descriptionopacity.') !important';
		?>;
		border-style:solid;
		font-size:<?php echo $paramssld['slider_description_font_size']; ?>px;
		border-width:<?php echo $paramssld['slider_description_border_size']; ?>px;
		border-color:#<?php echo $paramssld['slider_description_border_color']; ?>;
		border-radius:<?php echo $paramssld['slider_description_border_radius']; ?>px;
	  }
	  
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>.none, .huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>.none,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?>.hidden, .huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?>.hidden	   {display:none;}
	      
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h1, .huge_it_slideshow_description_text_videogallery_<?php echo $sliderID; ?> h1,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h2, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h2,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h3, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h3,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h4, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> h4,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> p, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> p,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> strong,  .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> strong,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> span, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> span,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> ul, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> ul,
	   .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> li, .huge_it_slideshow_title_text_videogallery_<?php echo $sliderID; ?> li {
			padding:2px;
			margin:0px;
	   }

	  .huge_it_slide_container_videogallery_<?php echo $sliderID; ?> {
		display: table-cell;
		margin: 0 auto;
		position: relative;
		vertical-align: middle;
		width:100%;
		height:100%;
		_width: inherit;
		_height: inherit;
	  }
	  .huge_it_slide_bg_videogallery_<?php echo $sliderID; ?> {
		margin: 0 auto;
		width:100%;
		height:100%;
		_width: inherit;
		_height: inherit;
	  }
	  .huge_it_slider_videogallery_<?php echo $sliderID; ?> {
		width:100%;
		height:100%;
		display:table;
		padding:0px;
		margin:0px;
		
	  }
	  .huge_it_slideshow_image_item_videogallery_<?php echo $sliderID; ?> {
		width:100%;
		height:100%;
		width: inherit;
		height: inherit;
		display: table-cell;
		filter: Alpha(opacity=100);
		opacity: 1;
		position: absolute;
		top:0px;
		left:0px;
		vertical-align: middle;
		z-index: 2;
		margin:0px !important;
		padding:0px;
		overflow:hidden;
		border-radius: <?php echo $paramssld['slider_slideshow_border_radius']; ?>px !important;
	  }
	  .huge_it_slideshow_image_second_item_videogallery_<?php echo $sliderID; ?> {
		width:100%;
		height:100%;
		_width: inherit;
		_height: inherit;
		display: table-cell;
		filter: Alpha(opacity=0);
		opacity: 0;
		position: absolute;
		top:0px;
		left:0px;
		vertical-align: middle;
		z-index: 1;
		overflow:hidden;
		margin:0px !important;
		padding:0px;
		border-radius: <?php echo $paramssld['slider_slideshow_border_radius']; ?>px !important;
	  }
	  .huge_it_grid_videogallery_<?php echo $sliderID; ?> {
		display: none;
		height: 100%;
		overflow: hidden;
		position: absolute;
		width: 100%;
	  }
	  .huge_it_gridlet_videogallery_<?php echo $sliderID; ?> {
		opacity: 1;
		filter: Alpha(opacity=100);
		position: absolute;
	  }
	  
					
	  .huge_it_slideshow_dots_container_videogallery_<?php echo $sliderID; ?> {
		display: table;
		position: absolute;
		width:100% !important;
		height:100% !important;
	  }
	  .huge_it_slideshow_dots_thumbnails_videogallery_<?php echo $sliderID; ?> {
		margin: 0 auto;
		overflow: hidden;
		position: absolute;
		width:100%;
		height:30px;
	  }
	  
	  .huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?> {
		display: inline-block;
		position: relative;
		cursor: pointer;
		box-shadow: 1px 1px 1px rgba(0,0,0,0.1) inset, 1px 1px 1px rgba(255,255,255,0.1);
		width:10px;
		height: 10px;
		border-radius: 10px;
		background: #00f;
		margin: 10px;
		overflow: hidden;
		z-index: 17;
	  }
	  
	  .huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?> {
		opacity: 1;
		background:#0f0;
		filter: Alpha(opacity=100);
	  }
	  .huge_it_slideshow_dots_deactive_videogallery_<?php echo $sliderID; ?> {
	  
	  }
	  
	  .huge_it_slideshow_image_item1_videogallery_<?php echo $sliderID; ?> {
		 display: table; 
		 width: inherit; 
		 height: inherit;
	  }
	  .huge_it_slideshow_image_item2_videogallery_<?php echo $sliderID; ?> {
		 display: table-cell; 
		 vertical-align: middle; 
		 text-align: center;
	  }
	  
	  .huge_it_slideshow_image_item2_videogallery_<?php echo $sliderID; ?> a {
		display:block;
		vertical-align:middle;
		width:100%;
		height:100%;
	  }
		.slide_thumb{
			position: absolute;
		}
		
		.huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?> {
			background:#<?php echo $paramssld['slider_slider_background_color']; ?>;
			border-width:<?php echo $paramssld['slider_slideshow_border_size']; ?>px;
			border-color:#<?php echo $paramssld['slider_slideshow_border_color']; ?>;
			border-radius:<?php echo $paramssld['slider_slideshow_border_radius']; ?>px;
		}
		
		.huge_it_slideshow_dots_thumbnails_videogallery_<?php echo $sliderID; ?> {
			<?php if($paramssld['slider_dots_position']=="bottom"){?>
			bottom:0px;
			<?php }else if($paramssld['slider_dots_position']=="none"){?>
			display:none;
			
			}else{
			top:0px; <?php } ?>
		}
		
		.huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?> {
			background:#<?php echo $paramssld['slider_dots_color']; ?>;
		}
		
		.huge_it_slideshow_dots_active_videogallery_<?php echo $sliderID; ?> {
			background:#<?php echo $paramssld['slider_active_dot_color']; ?>;
		}
                .youtube-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.youtube.png' ?>) center center no-repeat;}
	.vimeo-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.vimeo.png' ?>) center center no-repeat;}
	.playbutton{
		width: 100%;
		height: 100%;
		position: absolute;
	}
		
		<?php
		
		$arrowfolder=  JUri::root().'media/com_videogallerylite/Front_images';
		switch ($paramssld['slider_navigation_type']) {
			case 1:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-21px;
						height:43px;
						width:29px;
						background:url(<?php echo $arrowfolder;?>/arrows.simple.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-21px;
						height:43px;
						width:29px;
						background:url(<?php echo $arrowfolder;?>/arrows.simple.png) right top no-repeat; 
					}
				<?php
				break;
			case 2:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-25px;
						height:50px;
						width:50px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-25px;
						height:50px;
						width:50px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.shadow.png) right top no-repeat; 
					}

					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:left -50px;
					}

					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:right -50px;
					}
				<?php
				break;
			case 3:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-22px;
						height:44px;
						width:44px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-22px;
						height:44px;
						width:44px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.simple.dark.png) right top no-repeat; 
					}

					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:left -44px;
					}

					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:right -44px;
					}
				<?php
				break;
			case 4:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-33px;
						height:65px;
						width:59px;
						background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-33px;
						height:65px;
						width:59px;
						background:url(<?php echo $arrowfolder;?>/arrows.cube.dark.png) right top no-repeat; 
					}

					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:left -66px;
					}

					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:right -66px;
					}
				<?php
				break;
			case 5:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-18px;
						height:37px;
						width:40px;
						background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-18px;
						height:37px;
						width:40px;
						background:url(<?php echo $arrowfolder;?>/arrows.light.blue.png) right top no-repeat; 
					}

				<?php
				break;
			case 6:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-25px;
						height:50px;
						width:50px;
						background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-25px;
						height:50px;
						width:50px;
						background:url(<?php echo $arrowfolder;?>/arrows.light.cube.png) right top no-repeat; 
					}

					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:left -50px;
					}

					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:right -50px;
					}
				<?php
				break;
			case 7:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						right:0px;
						margin-top:-19px;
						height:38px;
						width:38px;
						background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-19px;
						height:38px;
						width:38px;
						background:url(<?php echo $arrowfolder;?>/arrows.light.transparent.circle.png) right top no-repeat; 
					}
				<?php
				break;
			case 8:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-22px;
						height:45px;
						width:45px;
						background:url(<?php echo $arrowfolder;?>/arrows.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-22px;
						height:45px;
						width:45px;
						background:url(<?php echo $arrowfolder;?>/arrows.png) right top no-repeat; 
					}
				<?php
				break;
			case 9:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-22px;
						height:45px;
						width:45px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-22px;
						height:45px;
						width:45px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.blue.png) right top no-repeat; 
					}
				<?php
				break;
			case 10:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-24px;
						height:48px;
						width:48px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-24px;
						height:48px;
						width:48px;
						background:url(<?php echo $arrowfolder;?>/arrows.circle.green.png) right top no-repeat; 
					}

					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:left -48px;
					}

					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>:hover {
						background-position:right -48px;
					}
				<?php
				break;
			case 11:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-29px;
						height:58px;
						width:55px;
						background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-29px;
						height:58px;
						width:55px;
						background:url(<?php echo $arrowfolder;?>/arrows.blue.retro.png) right top no-repeat; 
					}
				<?php
				break;
			case 12:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-37px;
						height:74px;
						width:74px;
						background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-37px;
						height:74px;
						width:74px;
						background:url(<?php echo $arrowfolder;?>/arrows.green.retro.png) right top no-repeat; 
					}
				<?php
				break;
			case 13:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-16px;
						height:33px;
						width:33px;
						background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-16px;
						height:33px;
						width:33px;
						background:url(<?php echo $arrowfolder;?>/arrows.red.circle.png) right top no-repeat; 
					}
				<?php
				break;
			case 14:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-51px;
						height:102px;
						width:52px;
						background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-51px;
						height:102px;
						width:52px;
						background:url(<?php echo $arrowfolder;?>/arrows.triangle.white.png) right top no-repeat; 
					}
				<?php
				break;
			case 15:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:0px;
						margin-top:-19px;
						height:39px;
						width:70px;
						background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:0px;
						margin-top:-19px;
						height:39px;
						width:70px;
						background:url(<?php echo $arrowfolder;?>/arrows.ancient.png) right top no-repeat; 
					}
				<?php
				break;
			case 16:
				?>
					#huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?> {	
						left:-21px;
						margin-top:-20px;
						height:40px;
						width:37px;
						background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) left  top no-repeat; 
					}
					
					#huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?> {
						right:-21px;
						margin-top:-20px;
						height:40px;
						width:37px;
						background:url(<?php echo $arrowfolder;?>/arrows.black.out.png) right top no-repeat; 
					}
				<?php
				break;
		}
?>
.thumb_image{
		  position: absolute;
		  width: 100%;
		  height: 100%;
		  top: 0;
		  left:0;
	}
	.entry-content a{
		border-bottom: none !important;
	}
	</style>

	<div class="huge_it_slideshow_image_wrap_videogallery_<?php echo $sliderID; ?>">

      <?php
      $current_pos = 0;
      ?>
		<!-- ##########################DOTS######################### -->
        <div class="huge_it_slideshow_dots_container_videogallery_<?php echo $sliderID; ?>">
			  <div class="huge_it_slideshow_dots_thumbnails_videogallery_<?php echo $sliderID; ?>">
				<?php
				$current_image_id=0;
				$current_pos =0;
				$current_key=0;
				$stri=0;
				foreach ($images as $key => $image_row) {				
					  if ($image_row->id == $current_image_id) {
						$current_pos = $stri;
						$current_key = $stri;
					  }
					?>
						<div id="huge_it_dots_<?php echo $stri; ?>_videogallery_<?php echo $sliderID; ?>" class="huge_it_slideshow_dots_videogallery_<?php echo $sliderID; ?> <?php echo (($key==$current_image_id) ? 'huge_it_slideshow_dots_active_videogallery_' . $sliderID : 'huge_it_slideshow_dots_deactive_videogallery_' . $sliderID); ?>" onclick="huge_it_change_image_videogallery_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()), '<?php echo $stri; ?>', data_videogallery_<?php echo $sliderID; ?>,false,true);return false;" data-image_id="<?php echo $image_row->id; ?>" data-image_key="<?php echo $stri; ?>"></div>
					<?php
					  $stri++;
				}
				?>
			  </div>
			
			<?php
			   if ($paramssld['slider_show_arrows']=="on") {
			 ?>
				<a id="huge_it_slideshow_left_videogallery_<?php echo $sliderID; ?>" href="#" onclick="huge_it_change_image_videogallery_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()) - iterator_videogallery_<?php echo $sliderID; ?>()) >= 0 ? (parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()) - iterator_videogallery_<?php echo $sliderID; ?>()) % data_videogallery_<?php echo $sliderID; ?>.length : data_videogallery_<?php echo $sliderID; ?>.length - 1, data_videogallery_<?php echo $sliderID; ?>,false,true);return false;">
					<div id="huge_it_slideshow_left-ico_videogallery_<?php echo $sliderID; ?>">
					<div><i class="huge_it_slideshow_prev_btn_videogallery_<?php echo $sliderID; ?> fa"></i></div></div>
				</a>
				
				<a id="huge_it_slideshow_right_videogallery_<?php echo $sliderID; ?>" href="#" onclick="huge_it_change_image_videogallery_<?php echo $sliderID; ?>(parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()), (parseInt(jQuery('#huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>').val()) + iterator_videogallery_<?php echo $sliderID; ?>()) % data_videogallery_<?php echo $sliderID; ?>.length, data_videogallery_<?php echo $sliderID; ?>,false,true);return false;">
					<div id="huge_it_slideshow_right-ico_<?php echo $sliderID;?>">
					<div><i class="huge_it_slideshow_next_btn_videogallery_<?php echo $sliderID; ?> fa"></i></div></div>
				</a>
			<?php
			}
			?>
		</div>
		
	  <!-- ##########################IMAGES######################### -->
	  <script>


jQuery(document).ready(function($) {
	
	$('.thumb_wrapper').on('click', function(ev) {
		
		var hg_youtube_or_vimeo = $(this).find(".playbutton");
		
		if(hg_youtube_or_vimeo.hasClass('vimeo-icon')){
			var hg_y_or_v = 'vimeo';
		}else if(hg_youtube_or_vimeo.hasClass('youtube-icon')){
			hg_y_or_v = 'youtube';
		}
		
		if( hg_y_or_v == "youtube") {
			var hugeid = $(this).data('rowid');
			var myid = hugeid;
			myid = parseInt(myid);
			eval('player_'+myid+'.playVideo()')
			ev.preventDefault();				
		}
		
		if( hg_y_or_v == "vimeo") {
			the_video_src = $(this).parent().find("#thevideo iframe").attr("src");
			
			$(this).parent().find("#thevideo iframe").attr('src', the_video_src + '&autoplay=1');
			
			$(this).parent().find(".playbutton").css("display", "none");
		}
	});
});
//console.log($(".video_view9_img"));
</script>
      <div id="huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>" class="huge_it_slideshow_image_container_videogallery_<?php echo $sliderID; ?>">        
        <div class="huge_it_slide_container_videogallery_<?php echo $sliderID; ?>">
          <div class="huge_it_slide_bg_videogallery_<?php echo $sliderID; ?>">
            <ul class="huge_it_slider_videogallery_<?php echo $sliderID; ?>">
			<?php
				$i=0;
                                
				foreach ($images as $key => $image_row) {
                                   
					$video_thumb = ''; //$image_row->thumb_url;
					$videourl = $image_row->image_url;
					$icon = $this->youtube_or_vimeo($videourl);
					?>
						<li  class="huge_it_slideshow_image<?php if ($i != $current_image_id) {$current_key = $key; echo '_second';} ?>_item_videogallery_<?php echo $sliderID; ?>" id="image_id_videogallery_<?php echo $sliderID.'_'.$i ?>" data-id="<?php echo $image_row->id; ?>">      
							<?php 
								if(strpos($image_row->image_url,'youtu') !== false){
									$video_thumb_url=get_youtube_id_from_url($image_row->image_url); 
								?>
								<?php if($video_thumb != ''):?>
								<div class="thumb_wrapper" data-rowid="<?php echo $image_row->id; ?>" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
									
									<div class="playbutton hg_play_button <?php echo $icon; ?>-icon" data-id="<?php echo $image_row->id; ?>"></div>
									
									<img  src="<?php echo $image_row->thumb_url ?>">
									
								</div>
								<?php else : ?>
								<div class="thumb_wrapper" data-rowid="<?php echo $image_row->id; ?>" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
									
									<img  class="thumb_image" src="https://i.ytimg.com/vi/<?php echo $video_thumb_url; ?>/hqdefault.jpg">
									<div class="playbutton <?php echo $icon;?>-icon"></div>
									
								</div>
								<?php endif;?>
		
									<div id="thevideo" style="display: block;">
										<div id="video_id_videogallery_<?php echo $sliderID;?>_<?php echo $key ;?>" class="huge_it_video_frame_videogallery_<?php echo $sliderID; ?> framvideo"></div>
									</div>
							<?php }else {
									$vimeo = $image_row->image_url;
									$vimeo_explode = explode( "/", $vimeo );
									$imgid =  end($vimeo_explode);
									 //var_dump($image_row->thumb_url);
							?>		
							<?php if($video_thumb != ''):?>
								<div class="thumb_wrapper" data-rowid="<?php echo $image_row->id; ?>" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
									
									<div class="playbutton <?php echo $icon; ?>-icon"></div>
									
									<img  src="<?php echo $image_row->thumb_url ?>">
								</div>
								<?php endif;?>
								
								<div class="thumb_wrapper" data-rowid="<?php echo $image_row->id; ?>" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
									
									<div class="hg_play_button playbutton <?php echo $icon;?>-icon" data-id="<?php echo $image_row->id; ?>"></div>
									
								</div>
								
								<div id="thevideo" style="display: block;">
									<iframe id="player_<?php echo $key ;?>"  class="huge_it_video_frame_videogallery_<?php echo $sliderID; ?> framvideo" src="//player.vimeo.com/video/<?php echo $imgid; ?>?api=1&amp;player_id=player_<?php echo $key ;?>&amp;showinfo=0&amp;controls=0" style="border: 0;" allowfullscreen></iframe>
								</div>	
							<?php } 

							?>
						</li>
					<?php
					$i++;
				} 
			?>
            </ul>
			<input type="hidden" id="huge_it_current_image_key_videogallery_<?php echo $sliderID; ?>" value="0" />
          </div>
        </div>
      </div>
	</div>
		
		<?php
		break;
                
                /* ########## VIEW 4 Thumbnails VIEW ##########*/
		case 4:
                $paramssld['video_natural_size_thumbnail'] = $paramssld['thumb_image_behavior'];
		?>
        
        <link href="<?php echo JUri::root().'media/com_videogallerylite/style/thumb_view.css';?>" rel="stylesheet" type="text/css" />
        <script src="<?php echo JUri::root().'media/com_videogallerylite/js/thumb_view.min.js' ;?>"></script>
        <script src="<?php echo JUri::root().'media/com_videogallerylite/js/jquery.lazyload.min.js';?>"></script>
        
        
        
        <style>
            section #huge_it_videogallery a{
                border:none;
            }
            section #huge_it_videogallery {
                    padding: <?php echo $paramssld["thumb_box_padding"]."px"; ?>;
                    /*min-width: 100%;*/
                    display: block;

                    min-height: 100%;
                    text-align: center;
                    margin-bottom: 30px;
                    <?php if($paramssld["thumb_box_has_background"] == 'on'){ ?>  background-color: #<?php echo $paramssld["thumb_box_background"]; ?>; <?php } ?>
                    <?php if($paramssld["thumb_box_use_shadow"] == 'on'){ echo 'box-shadow: 0 0 10px;'; } ?>
            }
				

            #huge_it_videogallery .huge_it_big_li {
                    <?php if($paramssld["video_natural_size_thumbnail"]=='resize'){?>
                    overflow:hidden;
                    width: 100%;	
                    max-width: <?php echo $paramssld["thumb_image_width"]; ?>px;	
                    height: <?php echo $paramssld["thumb_image_height"]; ?>px;
                    margin: <?php echo $paramssld["thumb_margin_image"]; ?>px !important;
                    border-radius: <?php echo $paramssld["thumb_image_border_radius"]; ?>px;
                    padding:0px !important;
                    <?php }
                    elseif($paramssld["video_natural_size_thumbnail"]=='natural'){
                    ?>
                    overflow:hidden;
                    width: <?php echo $paramssld["thumb_image_width"]; ?>px;
                    height: <?php echo $paramssld["thumb_image_height"]; ?>px;
                    margin: <?php echo $paramssld["thumb_margin_image"]; ?>px !important;
                    border-radius: <?php echo $paramssld["thumb_image_border_radius"]; ?>px;
                    padding:0px !important;
                    <?php }?>
            }
			
			
			
            section #huge_it_videogallery li .infoLayer ul li {
                    max-height:80px;
                    overflow:hidden;
            }

            section #huge_it_videogallery li .overLayer ul li h2,
            section #huge_it_videogallery li .infoLayer ul li h2 {
                    font-size: <?php echo $paramssld["thumb_title_font_size"]; ?>px;
                    color: #<?php echo $paramssld["thumb_title_font_color"]; ?>;
                    margin:0px !important;
            }

            section #huge_it_videogallery li .infoLayer ul li p {
                    color: #<?php echo $paramssld["thumb_title_font_color"]; ?>;
                    margin:0px !important;
            }

            section #huge_it_videogallery li .overLayer,
            section #huge_it_videogallery li .infoLayer {
                    -webkit-transition: opacity 0.3s linear;
                    -moz-transition: opacity 0.3s linear;
                    -ms-transition: opacity 0.3s linear;
                    -o-transition: opacity 0.3s linear;
                    transition: opacity 0.3s linear;
                    width: 100%;
                    max-width: <?php echo $paramssld["thumb_image_width"]; ?>px;
                    height: <?php echo $paramssld["thumb_image_height"]; ?>px;
                    position: absolute;
                    text-align: center;
                    opacity: 0;
                    top: 0px;
                    left: 0;
                    z-index: 4;
                    border-radius: <?php echo $paramssld["thumb_image_border_radius"]; ?>px;
            }
			
			
			
            section #huge_it_videogallery li a {
                    position: absolute;
                    display: block;
                    width: 100%;
                    max-width: <?php echo $paramssld["thumb_image_width"]; ?>px;
                    height: <?php echo $paramssld["thumb_image_height"]; ?>px;
                    top: 0px;
                    left: 0px;
                    z-index: 6; 
                    border-radius: <?php echo $paramssld["thumb_image_border_radius"]; ?>px;
            }
	.load_more3 {
		margin: 10px 0;
		position:relative;
		text-align:<?php if($paramssld['video_ht_view7_loadmore_position'] == 'left') {echo 'left';} 
			elseif ($paramssld['video_ht_view7_loadmore_position'] == 'center') { echo 'center'; }
			elseif($paramssld['video_ht_view7_loadmore_position'] == 'right') { echo 'right'; }?>;

		width:100%;


	}

	.load_more_button3 {
		border-radius: 10px;
		display:inline-block;
		padding:5px 15px;
		font-size:<?php echo $paramssld['video_ht_view7_loadmore_fontsize']; ?>px !important;;
		color:<?php echo '#'.$paramssld['video_ht_view7_loadmore_font_color']; ?> !important;;
		background:<?php echo '#'.$paramssld['video_ht_view7_button_color']; ?> !important;
		cursor:pointer;

	}
	.load_more_button3:hover{
		color:<?php echo '#'.$paramssld['video_ht_view7_loadmore_font_color_hover']; ?> !important;
		background:<?php echo '#'.$paramssld['video_ht_view7_button_color_hover']; ?> !important;
	} 
	.loading3 {
		display:none;
	}
	.paginate3{
		font-size:<?php echo $paramssld['video_ht_view7_paginator_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view7_paginator_color']; ?> !important;
		text-align: <?php echo $paramssld['video_ht_view7_paginator_position']; ?>;
	}
	.paginate3 a{
		border-bottom: none !important;
	}
	.icon-style3{
		font-size: <?php echo $paramssld['video_ht_view7_paginator_icon_size']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view7_paginator_icon_color']; ?> !important;
	}
	.clear{
		clear:both;
	}
			
			#huge_it_videogallery li img {
					<?php if($paramssld["video_natural_size_thumbnail"]=='resize'){?>
				width: 100%;	
				max-width: <?php echo $paramssld["thumb_image_width"] - 2*$paramssld["thumb_image_border_width"]; ?>px;	
			
				height: <?php echo $paramssld["thumb_image_height"] - 2*$paramssld["thumb_image_border_width"]; ?>px;
			
				border: <?php echo $paramssld["thumb_image_border_width"]; ?>px solid #<?php echo $paramssld["thumb_image_border_color"]; ?>;
				border-radius: <?php echo $paramssld["thumb_image_border_radius"]; ?>px;
				margin:0px !important;
				<?php }
				elseif($paramssld["video_natural_size_thumbnail"]=='natural'){
				?>
				border: <?php echo $paramssld["thumb_image_border_width"]; ?>px solid #<?php echo $paramssld["thumb_image_border_color"]; ?>;
				border-radius: <?php echo $paramssld["thumb_image_border_radius"]; ?>px;
				margin:0px !important;
				max-width: none !important;
				<?php }?>
			}
			
			
			section #huge_it_videogallery li:hover .overLayer {
				-webkit-transition: opacity 0.3s linear;
				-moz-transition: opacity 0.3s linear;
				-ms-transition: opacity 0.3s linear;
				-o-transition: opacity 0.3s linear;
				transition: opacity 0.3s linear;
				opacity: <?php echo ($paramssld["thumb_title_background_transparency"]/100)+0.001; ?>;
				display: block;
				background: #<?php echo $paramssld["thumb_title_background_color"]; ?>; 
			}
			section #huge_it_videogallery li:hover .infoLayer {
				-webkit-transition: opacity 0.3s linear;
				-moz-transition: opacity 0.3s linear;
				ms-transition: opacity 0.3s linear;
				-o-transition: opacity 0.3s linear;
				transition: opacity 0.3s linear;
				opacity: 1;
				display: block; 
			}
			
			section #huge_it_videogallery p {text-align:center;}
		</style>
                <section id="thumbwrapper<?=$videogalleryID; ?>">
			<input type="hidden" class="pagenum" value="1" />
			<ul id="huge_it_videogallery">
				<li id="fullPreview"></li>
				<?php
			foreach($images as $image){
			$idofgallery=$image->videogallery_id ;
			//print_r($image);
			}
			global $wpdb;
			$pattern='/-/';
                        
                        $db = JFactory::getDBO();
                        $query2 = $db->getQuery(true);
                        $query2->select('*');
                        $query2->from('#__huge_it_videogallery_galleries');
                        $query2 -> where('id ='.$idofgallery);
                        $query2 ->order('#__huge_it_videogallery_galleries.ordering asc');
                        $db->setQuery($query2);
                        $gallery = $db->loadObjectList();
			//$query2=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_galleries where id = '%d' order by ordering ASC ",$idofgallery);
			//$gallery=$wpdb->get_results($query2);
			foreach ($gallery as $gall) {
				 global $post;
				//$pID=$post->ID;
                                 $pID=0;
				$disp_type=$gall->display_type;
				$count_page=$gall->content_per_page;
				if($count_page==0){
					$count_page=999;
				}elseif(preg_match($pattern, $count_page)){
					$count_page=preg_replace($pattern, '', $count_page);

				}
				//var_dump($count_page) ;

			}

			global $wpdb;
			$num=$count_page;
			//$total = count($images) ; 
			$total = intval(((count($images) - 1) / $num) + 1);
			//$total_img=count($images);
			if(isset($_GET['page-img'.$videogalleryID.$pID])){
				$page = $_GET['page-img'.$videogalleryID.$pID];
			}else{
				$page = '';
					}
			
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;  
			  if($page > $total) $page = $total; 
			$start = $page * $num - $num; 
                        
                        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_videos');
        $query -> where('videogallery_id ='.$idofgallery);
        $query ->order('#__huge_it_videogallery_videos.ordering asc');
        $db->setQuery($query,$start,$num);
        $page_images = $db->loadObjectList();
			//$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos where videogallery_id = '%d' order by ordering ASC LIMIT ".$start.",".$num."",$idofgallery);
			//$page_images=$wpdb->get_results($query);
			if($disp_type==2){
				$page_images=$images;
				$count_page=9999;
			}
			?>
			<input type="hidden" id="total" value="<?=$total; ?>" />
				<?php  foreach($page_images as $key=>$row) {
				$imgurl=explode(";",$row->image_url); ?>
				<li class="huge_it_big_li" data-id="<?php echo $row->id; ?>">
				<?php 
					$imagerowstype=$row->sl_type; 
					if($row->sl_type == ''){$imagerowstype='image';}
					
					switch($imagerowstype){
						case 'image': 
					?>									
                                    <a class="group1" href="<?php echo $row->image_url; ?>"></a>
						<img src="<?php echo $row->image_url; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" />
					<?php 
						break;
						case 'video':
					?>
							<?php
								$videourl=$this->get_video_gallery_id_from_url($row->image_url);
								$thumb_pic="http://img.youtube.com/vi/<?php echo $videourl[0]; ?>/mqdefault.jpg";
								if($videourl[1]=='youtube'){
										if(empty($row->thumb_url)){
											$thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
										}else{
											$thumb_pic=$row->thumb_url;
										}
										
									?>
								
									<a  class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-id="<?php echo $row->id; ?>"></a>
									<img src="<?php echo $thumb_pic; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" />				
								<?php
								}else {

									$hash = unserialize($this->get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
									
									if(empty($row->thumb_url)){
										$imgsrc=$hash[0]['thumbnail_large'];
									}else{
										$imgsrc=$row->thumb_url;
									}
								?>
									<a  class="vimeo huge_it_videogallery_item group1" href="//player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-id="<?php echo $row->id; ?>"></a>
									<img src="<?php echo $imgsrc; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" />
								<?php
								}
							?>
					<?php
						break;
					}
					?>
					
					<div class="overLayer"></div>
					<div class="infoLayer">
						<ul>
							<li>
								<h2>
									<?php echo str_replace('__5_5_5__','%',$row->name); ?>
								</h2>
							</li>
							<li>
								<p>
									<?php echo $paramssld["thumb_view_text"]; ?>
								</p>
							</li>
						</ul>
					</div>
				</li>
				<?php }  ?>
				
			</ul>
			<?php 
		 $path_site = JUri::root()."media/com_videogallerylite/Front_images"; 
                 $path_siteArrow = JUri::root()."media/com_videogallerylite/images";
			$a=$disp_type;
			if($a==1){
				$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
				$pattern="/\?p=/";
				$pattern2="/&page-img[0-9]+=[0-9]+/";
				$pattern3="/\?page-img[0-9]+=[0-9]+/";
					if(preg_match($pattern, $actual_link)){

						if(preg_match($pattern2, $actual_link)){
							$actual_link=preg_replace($pattern2, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
							
						}	
					}elseif(preg_match($pattern3, $actual_link)){
						$actual_link=preg_replace($pattern3, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
						
					}
				?>
				<div class="load_more3">
					<div class="load_more_button3"><?=$paramssld['video_ht_view7_loadmore_text']; ?></div>
					<div class="loading3"><img src="<?php if($paramssld['video_ht_view7_loading_type'] == '1') {echo $path_siteArrow.'/loading1.gif';} 
					elseif ($paramssld['video_ht_view7_loading_type'] == '2') { echo $path_siteArrow.'/loading4.gif'; }
					elseif($paramssld['video_ht_view7_loading_type'] == '3') { echo $path_siteArrow.'/loading36.gif'; }
					elseif ($paramssld['video_ht_view7_loading_type'] == '4') { echo $path_siteArrow.'/loading51.gif'; }?>"></div>
					<script>
						jQuery(document).ready(function(){
							if(jQuery("#thumbwrapper<?=$videogalleryID; ?> .load_more_button3").length){
								jQuery("#thumbwrapper<?=$videogalleryID; ?> .load_more_button3").on("click tap",function(){

									if(jQuery("#thumbwrapper<?=$videogalleryID; ?> .pagenum:last").val()<jQuery("#thumbwrapper<?=$videogalleryID; ?> #total").val()) {
										var pagenum = parseInt(jQuery("#thumbwrapper<?=$videogalleryID; ?> .pagenum:last").val()) + 1;
										var perpage =<?=$count_page; ?>;
										var galleryid="<?=$image->videogallery_id; ?>";
										var thumbtext="<?php echo $paramssld["thumb_view_text"]; ?>";
										var width="<?=$paramssld['video_ht_view9_video_width']; ?>";
										var height="<?=$paramssld['video_ht_view9_video_height']; ?>";
										//alert(pagenum+','+perpage+','+galleryid+','+width+','+height);
										getresult(pagenum,perpage,galleryid,thumbtext,width,height);
									}else{
										jQuery('#thumbwrapper<?=$videogalleryID; ?> .load_more_button3').hide();
									}
									return false;
								});
							}
											if(jQuery("#thumbwrapper<?=$videogalleryID; ?> .pagenum:last").val()==jQuery("#thumbwrapper<?=$videogalleryID; ?> #total").val()) {
												jQuery('#thumbwrapper<?=$videogalleryID; ?> .load_more_button3').hide();
											}
							function getresult(pagenum,perpage,galleryid,thumbtext,width,height){
								var data = {
										action:"huge_it_video_gallery_ajax",
										task:'load_videos_thumbnail',
										page:pagenum,
										perpage:perpage,
										galleryid:galleryid,
										thumbtext:thumbtext,
										width:width,
										height:height
								}
								jQuery('#thumbwrapper<?=$videogalleryID; ?> .loading3').show();
								
								jQuery('#thumbwrapper<?=$videogalleryID; ?> .load_more_button3').hide();

								jQuery.post("components/com_videogallerylite/ajax_url.php",data,function(response){
									if(response.success){
										jQuery("#thumbwrapper<?=$videogalleryID; ?> #huge_it_videogallery").append(response.success);
										jQuery('#thumbwrapper<?=$videogalleryID; ?> #huge_it_videogallery img').on('load',function(){
											
											
											jQuery(".group1").colorbox({rel:'group1'});
											jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".vimeo").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
											jQuery(".inline").colorbox({inline:true, width:"50%"});
											jQuery(".callbacks").colorbox({
												onOpen:function(){ alert('onOpen: colorbox is about to open'); },
												onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
												onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
												onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
												onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
											});

											jQuery('.non-retina').colorbox({rel:'group5', transition:'none'})
											jQuery('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
											jQuery('#thumbwrapper<?=$videogalleryID; ?> .load_more_button3').show();
											jQuery('#thumbwrapper<?=$videogalleryID; ?> .loading3').hide();
											
											if(jQuery("#thumbwrapper<?=$videogalleryID; ?> .pagenum:last").val()==jQuery("#thumbwrapper<?=$videogalleryID; ?> #total").val()) {
												jQuery('#thumbwrapper<?=$videogalleryID; ?> .load_more_button3').hide();
											}
										});
										

									}else{
										alert("no");
									}
								},"json");
							}

						});
					</script>
				</div>
				<?php 
			}elseif($a==0){
		?>
			<div class="paginate3">
			<?php
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
			
			
			$checkREQ='';
			$pattern="/\?p=/";
			$pattern2="/&page-img[0-9]+=[0-9]+/";
			//$res=preg_match($pattern, $actual_link);
			if(preg_match($pattern, $actual_link)){

				if(preg_match($pattern2, $actual_link)){
					$actual_link=preg_replace($pattern2, '', $actual_link);
				}

				$checkREQ=$actual_link.'&page-img'.$videogalleryID.$pID;
				
			}else{
				$checkREQ='?page-img'.$videogalleryID.$pID;
				
			}
			//var_dump($res);  
			
			// Проверяем нужны ли стрелки назад  
				$pervpage='';
			if ($page != 1) $pervpage = '<a href= '.$checkREQ.'=1><i class="hugeiticons hugeiticons-fast-backward" ></i></a>  
			                               <a href= '.$checkREQ.'='. ($page - 1) .'><i class="hugeiticons hugeiticons-chevron-left"></i></a> ';  
			// Проверяем нужны ли стрелки вперед  
		       $nextpage='';
			if ($page != $total) $nextpage = ' <a href= '.$checkREQ.'='. ($page + 1) .'><i class="hugeiticons hugeiticons-chevron-right"></i></a>  
			                                   <a href= '.$checkREQ.'=' .$total. '><i class="hugeiticons hugeiticons-fast-forward" ></i></a>'; 

			// Вывод меню  
			echo $pervpage.$page.'/'.$total.$nextpage;  

			?>
</div>
<?php 
}
?>
	<script>
jQuery(document).ready(function(){

jQuery("#huge_it_videogallery .huge_it_big_li >img").each(function(i, img) {

	if(jQuery(this).prop('naturalWidth') < jQuery(this).prop('naturalHeight')){
		

  		jQuery(img).css({
        position: "relative",
        width:'100%',  
    	});
    	var middleHeight=(jQuery(img).parent().height()-jQuery(this).innerHeight())/2
    	jQuery(img).css({
        top: middleHeight
    	});	
  	}else if(jQuery(this).prop('naturalWidth') > jQuery(this).prop('naturalHeight')){
		jQuery(img).css({
        position: "relative",
        height:'100%'       
        });
        var middleWidth=(jQuery(img).parent().width()-jQuery(this).innerWidth())/2
    	jQuery(img).css({
        left: middleWidth
    	});
		//alert('yo')
	}
});
})
</script>
</section>
        <?php
break;
/* ########## VIEW 8 justified's ##########*/
    case 6:
        
        
        if($paramssld["ht_view8_element_randomize"] == 'off') {
            $paramssld["ht_view8_element_randomize"] = 'false';
        } else $paramssld["ht_view8_element_randomize"] = 'true';
        
        if($paramssld["ht_view8_element_cssAnimation"] == 'off') {
            $paramssld["ht_view8_element_cssAnimation"] = 'false';
        } else $paramssld["ht_view8_element_cssAnimation"] ='true';
        
        if($paramssld["ht_view8_element_justify"] == 'off') {
            $paramssld["ht_view8_element_justify"] = 'false';
        } else $paramssld["ht_view8_element_justify"] = 'true';
        if($paramssld["ht_view8_element_show_caption"] == 'off') {
            $paramssld["ht_view8_element_show_caption"] = 'false';
        } else $paramssld["ht_view8_element_show_caption"] = 'true';
?>
<?php $path_site = JUri::root(); ?>
<link rel="stylesheet" href="<?php echo $path_site ; ?>/media/com_videogallerylite/style/justifiedGallery.css" />
<script>
    var imagemargin=<?php echo $paramssld["ht_view8_element_padding"]; ?>;
    var imagerandomize=<?php echo $paramssld["ht_view8_element_randomize"]; ?>;
    var imagecssAnimation=<?php echo $paramssld["ht_view8_element_cssAnimation"]; ?>;
    var imagecssAnimationSpeed=<?php echo $paramssld["ht_view8_element_animation_speed"]; ?>;
    var imageheight= <?php echo $paramssld["ht_view8_element_height"]; ?>;
    var imagejustify= <?php echo $paramssld["ht_view8_element_justify"]; ?>;
    var imageshowcaption= <?php echo $paramssld["ht_view8_element_show_caption"]; ?>;
    //var imagemaxheight=<?php //echo $paramssld["ht_view8_element_maxheight"]; ?>;
    //var imagefixed=<?php //echo $paramssld["ht_view8_element_size_fix"]; ?>;
</script>

 <script src="<?php echo $path_site ; ?>/media/com_videogallerylite/js/justifiedGallery.js"></script>
 
 <style>
	.justified-gallery {
		width: 100%;
		position: relative;
	}
	.justified-gallery > a,
	.justified-gallery > div {
		position: absolute;
		display: inline-block;
		opacity: 0;
		overflow:hidden;
		filter: alpha(opacity=0);
		/* IE8 or Earlier */
	}
	.justified-gallery > a > img,
	.justified-gallery > div > img {
	   /* width: 200px !important;*/
		position: absolute;
		top: 50%;
		left: 50%;
		padding: 0;
	}
	.justified-gallery > a > .caption,
	.justified-gallery > div > .caption {
		display: none;
		position: absolute;
		bottom: 0;
		padding: 5px;
		left: 0;
		right: 0;
		margin: 0;
		color: #<?php echo $paramssld["ht_view8_element_title_font_color"]; ?>;
		font-size: <?php echo $paramssld["ht_view8_element_title_font_size"]; ?>px;
		font-weight: 300;
		font-family: sans-serif;
		//margin-left: <?php //echo $paramssld["ht_view8_element_border_width"]; ?>px;
		background:<?php 			
				list($r,$g,$b) = array_map('hexdec',str_split($paramssld['ht_view8_element_title_background_color'],2));
				$titleopacity=$paramssld["ht_view8_element_title_overlay_transparency"]/100;						
				echo 'rgba('.$r.','.$g.','.$b.','.$titleopacity.')  !important';	
		?>;
		
		overflow: hidden;
		text-overflow: ellipsis;
		white-space:nowrap;
	}
	.justified-gallery > a > .caption.caption-visible,
	.justified-gallery > div > .caption.caption-visible {
		display: initial;
		opacity: 0.7;
		filter: "alpha(opacity=70)";
		/* IE8 or Earlier */
		-webkit-animation: justified-gallery-show-caption-animation 500ms 0 ease;
		-moz-animation: justified-gallery-show-caption-animation 500ms 0 ease;
		-ms-animation: justified-gallery-show-caption-animation 500ms 0 ease;
	}
	.justified-gallery > .entry-visible {
		opacity: 1.0;
		filter: alpha(opacity=100);
		/* IE8 or Earlier */
		-webkit-animation: justified-gallery-show-entry-animation 300ms 0 ease;
		-moz-animation: justified-gallery-show-entry-animation 300ms 0 ease;
		-ms-animation: justified-gallery-show-entry-animation 300ms 0 ease;
	}
	.justified-gallery > .spinner {
		position: absolute;
		bottom: 0;
		margin-left: -24px;
		padding: 10px 0 10px 0;
		left: 50%;
		opacity: initial;
		filter: initial;
		overflow: initial;
	}
	.justified-gallery > .spinner > span {
		display: inline-block;
		opacity: 0;
		filter: alpha(opacity=0);
		/* IE8 or Earlier */
		width: 8px;
		height: 8px;
		margin: 0 4px 0 4px;
		background-color: #000;
		border-top-left-radius: 6px;
		border-top-right-radius: 6px;
		border-bottom-right-radius: 6px;
		border-bottom-left-radius: 6px;
	}
	.load_more2 {
		margin: 10px 0;
		position:relative;
		text-align:<?php if($paramssld['video_ht_view8_loadmore_position'] == 'left') {echo 'left';} 
			elseif ($paramssld['video_ht_view8_loadmore_position'] == 'center') { echo 'center'; }
			elseif($paramssld['video_ht_view8_loadmore_position'] == 'right') { echo 'right'; }?>;

		width:100%;


	}

	.load_more_button2 {
		border-radius: 10px;
		display:inline-block;
		padding:5px 15px;
		font-size:<?php echo $paramssld['video_ht_view8_loadmore_fontsize']; ?>px !important;;
		color:<?php echo '#'.$paramssld['video_ht_view8_loadmore_font_color']; ?> !important;;
		background:<?php echo '#'.$paramssld['video_ht_view8_button_color']; ?> !important;
		cursor:pointer;

	}
	.load_more_button2:hover{
		color:<?php echo '#'.$paramssld['video_ht_view8_loadmore_font_color_hover']; ?> !important;
		background:<?php echo '#'.$paramssld['video_ht_view8_button_color_hover']; ?> !important;
	} 

	.loading2 {
		display:none;
	}
	.paginate2{
		font-size:<?php echo $paramssld['video_ht_view8_paginator_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view8_paginator_color']; ?> !important;
		text-align: <?php echo $paramssld['video_ht_view8_paginator_position']; ?>;
		margin-top: 25px;
	}
	.paginate2 a{
		border-bottom: none !important;
	}
	.icon-style2{
		font-size: <?php echo $paramssld['video_ht_view8_paginator_icon_size']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view8_paginator_icon_color']; ?> !important;
	}
	.clear{
		clear:both;
	}
        .play-icon {
                position:absolute;
                top:0px;
                left:0px;
                width:100%;
                height:100%;	

        }

        .play-icon.youtube-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.youtube.png' ?>) center center no-repeat;background-size:20%;}
        .play-icon.vimeo-icon {background:url(<?php echo JUri::root().'media/com_videogallerylite/images/play.vimeo.png' ?>) center center no-repeat;background-size:20%;}

</style>

<?php
    $path_site = JUri::root();
?>	

<div id="my_video_gallery_wrapper_<?php echo $videogalleryID; ?>" class="clearfix">

					
                <div id="my_video_gallery_<?php echo $videogalleryID; ?>" class="clearfix">

<?php foreach($images as $image){
			$idofgallery=$image->videogallery_id ;
			//print_r($image);
			}
			//global $wpdb;
			$pattern='/-/';
                        
                        $db = JFactory::getDBO();
                        $query2 = $db->getQuery(true);
                        $query2->select('*');
                        $query2->from('#__huge_it_videogallery_galleries');
                        $query2 -> where('id ='.$idofgallery);
                        $query2 ->order('#__huge_it_videogallery_galleries.ordering asc');
                        $db->setQuery($query2);
                        $gallery = $db->loadObjectList();
			//$query2=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_galleries where id = '%d' order by ordering ASC ",$idofgallery);
			//$gallery=$wpdb->get_results($query2);
			foreach ($gallery as $gall) {
				// global $post;
				$pID=0; //$post->ID;
				$disp_type=$gall->display_type;
				$count_page=$gall->content_per_page;
				if($count_page==0){
					$count_page=999;
				}elseif(preg_match($pattern, $count_page)){
					$count_page=preg_replace($pattern, '', $count_page);

				}
				//var_dump($count_page) ;

			}

			global $wpdb;
			$num=$count_page;
			//$total = count($images) ; 
			$total = intval(((count($images) - 1) / $num) + 1);
			//$total_img=count($images);
			if(isset($_GET['page-img'.$videogalleryID.$pID])){
				$page = $_GET['page-img'.$videogalleryID.$pID];
			}else{
				$page = '';
					}
			
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;  
			  if($page > $total) $page = $total; 
			$start = $page * $num - $num; 
                        
                        $query = $db->getQuery(true);
                        $query->select('*');
                        $query->from('#__huge_it_videogallery_videos');
                        $query -> where('videogallery_id ='.$idofgallery);
                        $query ->order('#__huge_it_videogallery_videos.ordering asc');
                        $db->setQuery($query,$start,$num);
                        $page_images = $db->loadObjectList();
                                        //$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos where videogallery_id = '%d' order by ordering ASC LIMIT ".$start.",".$num."",$idofgallery);
			
			//$page_images=$wpdb->get_results($query);
			if($disp_type==2){
				$page_images=$images;
				$count_page=9999;
			}
		
			?>
			

<input type="hidden" id="total" value="<?=$total; ?>" />
                    <?php

                    foreach($page_images as $key=>$row)
                    {
                        $imgurl=explode(";",$row->image_url);
                        $imagerowstype=$row->sl_type;
                        if($row->sl_type == ''){$imagerowstype='image';}
                        switch($imagerowstype){
                            case 'image':
                    ?>
                                <?php 	if($row->image_url != ';'){ ?>
                                <a class="group1" href="<?php echo $imgurl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>">
                                    <img id="wd-cl-img<?php echo $key; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" src="<?php echo get_huge_image($imgurl[0],$image_prefix); ?>"/>
                                </a>
                                <?php } else { ?>
                                <img alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" id="wd-cl-img<?php echo $key; ?>" src="images/noimage.jpg"  />
                                <input type="hidden" class="pagenum" value="1" />
                                <?php
                                } ?>
                                <?php break;
                            
                            case 'video':
        
                                $videourl=$this->get_video_gallery_id_from_url($row->image_url);
                                if($videourl[1]=='youtube'){
                                		if(empty($row->thumb_url)){
											$thumb_pic='http://img.youtube.com/vi/'.$videourl[0].'/mqdefault.jpg';
										}else{
											$thumb_pic=$row->thumb_url;
										}
                                	?>
                                        <a class="youtube huge_it_videogallery_item group1"  href="https://www.youtube.com/embed/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-id="<?php echo $row->id; ?>">
                                                <img  src="<?php echo $thumb_pic; ?>" alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" />
                                                <div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
                                        </a>
                                        <input type="hidden" class="pagenum" value="1" />
                                <?php }
                                else {
                                        $hash = unserialize($this->get("http://vimeo.com/api/v2/video/".$videourl[0].".php"));
                                        if(empty($row->thumb_url)){
										$imgsrc=$hash[0]['thumbnail_large'];
										}else{
											$imgsrc=$row->thumb_url;
										}
                                ?>
                                        <a class="vimeo huge_it_videogallery_item group1" href="//player.vimeo.com/video/<?php echo $videourl[0]; ?>" title="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" data-id="<?php echo $row->id; ?>">
                                                <img alt="<?php echo str_replace('__5_5_5__','%',$row->name); ?>" src="<?php echo $imgsrc; ?>"/>
                                                <div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
                                        </a>
                                        <input type="hidden" class="pagenum" value="1" />
                                <?php
                                }
                        }
                    }
                    ?>
                </div>
   

 <script>
    jQuery(document).ready(function($){
        $( window ).load(function(){
        $('.huge_it_videogallery_item').each(function(){
                var img=$(this).find('img');
                if($(this).find('img').attr('alt')==''){
                //	alert('yo');
                        img.parent().find(".caption" ).css('display','none');
                }
        })
        })
    })
</script>
<?php 
        $path_site = JUri::root()."media/com_videogallerylite/Front_images"; 
        $path_siteArrow = JUri::root()."media/com_videogallerylite/"; 
        $a=$disp_type;
if($a==1){
				 $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
				$pattern="/\?p=/";
				$pattern2="/&page-img[0-9]+=[0-9]+/";
				$pattern3="/\?page-img[0-9]+=[0-9]+/";
					if(preg_match($pattern, $actual_link)){

						if(preg_match($pattern2, $actual_link)){
							$actual_link=preg_replace($pattern2, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
							
						}	
					}elseif(preg_match($pattern3, $actual_link)){
						$actual_link=preg_replace($pattern3, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
						
					}
				?>
				<div class="load_more2">
					<div class="load_more_button2 load_more_button_<?php echo $videogalleryID; ?>"><?=$paramssld['video_ht_view8_loadmore_text']; ?></div>
					<div class="loading2 loading_<?php echo $videogalleryID; ?>"><img src="<?php if($paramssld['video_ht_view8_loading_type'] == '1') {echo $path_siteArrow.'images/loading1.gif';} 
					elseif ($paramssld['video_ht_view8_loading_type'] == '2') { echo $path_siteArrow.'images/loading4.gif'; }
					elseif($paramssld['video_ht_view8_loading_type'] == '3') { echo $path_siteArrow.'images/loading36.gif'; }
					elseif ($paramssld['video_ht_view8_loading_type'] == '4') { echo $path_siteArrow.'images/loading51.gif'; }?>"></div>
					<script>
						jQuery(document).ready(function(){ 

						if(jQuery("#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>").length){
								jQuery("#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>").on("click tap",function(){
								//alert();

									if(jQuery("#my_video_gallery_<?php echo $videogalleryID; ?> .pagenum:last").val()<jQuery("#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> #total").val()) {
										
										var pagenum = parseInt(jQuery("#my_video_gallery_<?php echo $videogalleryID; ?> .pagenum:last").val()) + 1;
										//alert(pagenum);
										var perpage =<?=$count_page; ?>;
										var galleryid="<?=$image->videogallery_id; ?>";
										var width="<?=$paramssld['video_ht_view9_video_width']; ?>";
										var height="<?=$paramssld['video_ht_view9_video_height']; ?>";
										getresult(pagenum,perpage,galleryid,width,height);
									}else{
										jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>').hide();
									}
									return false;
								});
							}
							if(jQuery("#my_video_gallery_<?php echo $videogalleryID; ?> .pagenum:last").val()==jQuery("#my_video_gallery_<?php echo $videogalleryID; ?> #total").val()) {
								jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>').hide();
											}			
							function getresult(pagenum,perpage,galleryid,width,height){
								var data = {
										action:"huge_it_video_gallery_ajax",
										task:'load_videos_justified',
										page:pagenum,
										perpage:perpage,
										galleryid:galleryid,
										width:width,
										height:height										
								}
								jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .loading_<?php echo $videogalleryID; ?>').show();
								jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>').hide();
								
								jQuery.post("components/com_videogallerylite/ajax_url.php",data,function(response){
									jQuery('.huge_it_videogallery_item').each(function(){
							                		var img=jQuery(this).find('img');
							                		if(jQuery(this).find('img').attr('alt')==''){
							                		//	alert('yo');
							                			img.parent().find(".caption" ).css('display','none');
							                		}
							                	})
									if(response.success){
											//jQuery( window ).load(function(){
							                	
							              // })
										jQuery("#my_video_gallery_<?php echo $videogalleryID; ?>").append(response.success);
										jQuery("#my_video_gallery_<?php echo $videogalleryID; ?>").justifiedGallery();
					
										jQuery('#my_video_gallery_<?php echo $videogalleryID; ?> img').on('load',function(){
											
											
											jQuery(".group1").colorbox({rel:'group1'});
											jQuery(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".vimeo").colorbox({iframe:true, innerWidth:640, innerHeight:390});
											jQuery(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
											jQuery(".inline").colorbox({inline:true, width:"50%"});
											jQuery(".callbacks").colorbox({
												onOpen:function(){ alert('onOpen: colorbox is about to open'); },
												onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
												onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
												onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
												onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
											});

											jQuery('.non-retina').colorbox({rel:'group5', transition:'none'})
											jQuery('.retina').colorbox({rel:'group5', transition:'none', retinaImage:true, retinaUrl:true});
											jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>').show();
											jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .loading_<?php echo $videogalleryID; ?>').hide();
											
										


											if(jQuery("#my_video_gallery_<?php echo $videogalleryID; ?> .pagenum:last").val()==jQuery("#my_video_gallery_<?php echo $videogalleryID; ?> #total").val()) {
												jQuery('#my_video_gallery_wrapper_<?php echo $videogalleryID; ?> .load_more_button_<?php echo $videogalleryID; ?>').hide();
											}
										});
										

									}else{
										alert("no");
									}
								},"json");
							}

						});
					</script>
				</div> </div>
				<?php 
			}elseif($a==0){
		?>
		<div class="paginate2">
			<?php
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
			
			
			$checkREQ='';
			$pattern="/\?p=/";
			$pattern2="/&page-img[0-9]+=[0-9]+/";
			//$res=preg_match($pattern, $actual_link);
			if(preg_match($pattern, $actual_link)){

				if(preg_match($pattern2, $actual_link)){
					$actual_link=preg_replace($pattern2, '', $actual_link);
				}

				$checkREQ=$actual_link.'&page-img'.$videogalleryID.$pID;
				
			}else{
				$checkREQ='?page-img'.$videogalleryID.$pID;
				
			}
			//var_dump($res);  
			
			// Проверяем нужны ли стрелки назад  
				$pervpage='';
			if ($page != 1) $pervpage = '<a href= '.$checkREQ.'=1><i class="hugeiticonse2 hugeiticons-fast-backward" ></i></a>  
			                               <a href= '.$checkREQ.'='. ($page - 1) .'><i class="hugeiticons hugeiticons-chevron-left"></i></a> ';  
			// Проверяем нужны ли стрелки вперед  
		       $nextpage='';
			if ($page != $total) $nextpage = ' <a href= '.$checkREQ.'='. ($page + 1) .'><i class="hugeiticons hugeiticons-chevron-right"></i></a>  
			                                   <a href= '.$checkREQ.'=' .$total. '><i class="hugeiticons hugeiticons-fast-forward" ></i></a>';  

			// Находим две ближайшие станицы с обоих краев, если они есть  
			/*if($page - 2 > 0) $page2left = ' <a href= ./?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';  
			if($page - 1 > 0) $page1left = '<a href= ./?page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';  
			if($page + 2 <= $total) $page2right = ' | <a href= ./?page='. ($page + 2) .'>'. ($page + 2) .'</a>';  
			if($page + 1 <= $total) $page1right = ' | <a href= ./?page='. ($page + 1) .'>'. ($page + 1) .'</a>'; */

			// Вывод меню  
			echo $pervpage.$page.'/'.$total.$nextpage;  

			?>
</div>
</div>
<?php
}
?>


<script>
    jQuery(document).ready(function(){

    jQuery("#my_video_gallery_<?php echo $videogalleryID; ?>").justifiedGallery();

}); 
</script>
<?php	  
	break;	

	/////////////////////Blog style view///////////////////////
	case 7:

	?>
         <style>
	.video_view9_container:nth-last-child(3){
		margin-bottom: 0px !important;
		padding-bottom: 0px !important;
		border:none !important;

	}
	.video_view9_container{
		width: <?php echo $paramssld['video_ht_view9_general_width']; ?>%;
		float:<?php if($paramssld['video_view9_general_position'] == 'left' || $paramssld['video_view9_general_position'] == 'center') { echo 'none'; }
					elseif($paramssld['video_view9_general_position'] == 'right') { echo 'right'; }?>;
		<?php if($paramssld['video_view9_general_position'] == 'center') { echo 'margin:0 auto;'; }?>;
		margin-bottom: <?php echo $paramssld['video_ht_view9_general_space']; ?>px !important;
		padding-bottom: <?php echo $paramssld['video_ht_view9_general_space']; ?>px !important;
		border-bottom:  <?php echo $paramssld['video_ht_view9_general_separator_size']; ?>px 

		<?php if($paramssld['video_view9_general_separator_style'] == 'none') { echo 'none'; }
		      elseif($paramssld['video_view9_general_separator_style'] == 'solid') { echo 'solid'; }
		      elseif($paramssld['video_view9_general_separator_style'] == 'dashed') { echo 'dashed'; }
		      elseif($paramssld['video_view9_general_separator_style'] == 'dotted') { echo 'dotted'; }
		      elseif($paramssld['video_view9_general_separator_style'] == 'groove') { echo 'groove'; }
		      elseif($paramssld['video_view9_general_separator_style'] == 'double') { echo 'double'; }?> #<?php echo $paramssld['video_ht_view9_general_separator_color']; ?>;
	}
	.video_new_view_title{
		font-size:<?php echo $paramssld['video_ht_view9_title_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view9_title_color']; ?> !important;
		<?php if($paramssld['video_ht_view9_element_title_show'] == 'false') { echo 'display:none;'; }?>;
		<?php if($paramssld['video_view9_title_textalign'] == 'left') { echo 'text-align:left;'; }
		      elseif($paramssld['video_view9_title_textalign'] == 'right') { echo 'text-align:right;'; }
		      elseif($paramssld['video_view9_title_textalign'] == 'center') { echo 'text-align:center;'; }
		      elseif($paramssld['video_view9_title_textalign'] == 'justify') { echo 'text-align:justify;'; }?>;
		background-color:<?php echo '#'.$paramssld['video_ht_view9_title_back_color']; ?> !important;  
		<?php if($paramssld['video_ht_view9_title_opacity'] != 100) { echo 'opacity:'.($paramssld['video_ht_view9_title_opacity']/100).';'; }?>;    
	}
	.video_new_view_desc ul{
		list-style-type: none;
	}
	.video_new_view_desc{
		margin-top: 15px;
		font-size:<?php echo $paramssld['video_ht_view9_desc_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view9_desc_color']; ?> !important;
		<?php if($paramssld['video_ht_view9_element_desc_show'] == 'false') { echo 'display:none;'; }?>;
		<?php if($paramssld['video_view9_desc_textalign'] == 'left') { echo 'text-align:left;'; }
	      elseif($paramssld['video_view9_desc_textalign'] == 'right') { echo 'text-align:right;'; }
	      elseif($paramssld['video_view9_desc_textalign'] == 'center') { echo 'text-align:center;'; }
	      elseif($paramssld['video_view9_desc_textalign'] == 'justify') { echo 'text-align:justify;'; }?>;
	      background-color:<?php echo '#'.$paramssld['video_ht_view9_desc_back_color']; ?> !important;  
		<?php if($paramssld['video_ht_view9_desc_opacity'] != 100) { echo 'opacity:'.($paramssld['video_ht_view9_desc_opacity']/100).';'; }?>; 
	}
	.paginate{
		font-size:<?php echo $paramssld['video_ht_view9_paginator_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view9_paginator_color']; ?> !important;
		text-align: <?php echo $paramssld['video_view9_paginator_position']; ?>;
	}
	.paginate a{
		border-bottom: none !important;
	}
	.icon-style{
		font-size: <?php echo $paramssld['video_ht_view9_paginator_icon_size']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view9_paginator_icon_color']; ?> !important;
	}
	.clear{
		clear:both;
	}
	.video_view9_img{
		display:block;
		margin: 0 auto;
	}
	.video_view9_vid_wrapper{
		position: relative;
		margin-bottom: 15px;
	}
	.video_view9_vid_wrapper iframe{
		/*position: relative;*/
		opacity: 1;
		cursor: pointer;
		float:<?php if($paramssld['video_view9_video_position'] == 'left') {echo 'left';} 
			elseif ($paramssld['video_view9_video_position'] == 'center') { echo 'none'; }
			elseif($paramssld['video_view9_video_position'] == 'right') { echo 'right'; }?>;


	}
	.thumb_image{
		  position: absolute;
		  width: 100%;
		  height: 100%;
	}
        .youtube-icon {background:url(<?php echo JUri::root(); ?>media/com_videogallerylite/images/play.youtube.png) center center no-repeat;}
	.vimeo-icon {background:url(<?php echo JUri::root(); ?>media/com_videogallerylite/images/play.vimeo.png) center center no-repeat;}
	.playbutton{
		width: 100%;
		height: 100%;
		position: absolute;
	}

	.load_more {
		margin: 10px 0;
		position:relative;
		text-align:<?php if($paramssld['video_view9_loadmore_position'] == 'left') {echo 'left';} 
			elseif ($paramssld['video_view9_loadmore_position'] == 'center') { echo 'center'; }
			elseif($paramssld['video_view9_loadmore_position'] == 'right') { echo 'right'; }?>;

		width:100%;


	}

	.load_more_button {
		border-radius: 10px;
		display:inline-block;
		padding:5px 15px;
		font-size:<?php echo $paramssld['video_ht_view9_loadmore_fontsize']; ?>px !important;
		color:<?php echo '#'.$paramssld['video_ht_view9_loadmore_font_color']; ?> !important;
		background:<?php echo '#'.$paramssld['video_ht_view9_button_color']; ?> !important;
		cursor:pointer;
		margin-top: 15px;

	}
	.load_more_button:hover{
		color:<?php echo '#'.$paramssld['video_ht_view9_loadmore_font_color_hover']; ?> !important;
		background:<?php echo '#'.$paramssld['video_ht_view9_button_color_hover']; ?> !important;
	} 
	.loading {
		display:none;
	}

</style>

<script>

jQuery(document).ready(function($) {
  jQuery('.thumb_wrapper').on('click', function(ev) {
  	var hugeid=$(this).parent().parent().index();
  	var myid=hugeid;
  	myid=parseInt(myid/2);
  	
  	//alert(myid);
  	
 	//console.log(jQuery(".video_view9_img")[2]);
   jQuery(".video_view9_img")[myid].src += "?autoplay=1";
   ev.preventDefault();
 
  });
});
//console.log($(".video_view9_img"));
</script>
<div class="video_view9_cont_wrapper" id="video_view9_cont_wrapper<?=$videogalleryID; ?>">
	<div id="video_view9_cont_list<?=$videogalleryID; ?>">
		<?php

			foreach($images as $image){
			$idofgallery=$image->videogallery_id ;
			//print_r($image);
			}
			//global $wpdb;
			$pattern='/-/';
                        $db = JFactory::getDBO();
                        $query2 = $db->getQuery(true);
                        $query2->select('*');
                        $query2->from('#__huge_it_videogallery_galleries');
                        $query2 -> where('id ='.$idofgallery);
                        $query2 ->order('#__huge_it_videogallery_galleries.ordering asc');
                        $db->setQuery($query2);
                        $gallery = $db->loadObjectList();
			//$query2=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_galleries where id = '%d' order by ordering ASC ",$idofgallery);
			//$gallery=$wpdb->get_results($query2);
			foreach ($gallery as $gall) {
				 //global $post;
				$pID=0;//$post->ID;
				$disp_type=$gall->display_type;
				$count_page=$gall->content_per_page;
				if($count_page==0){
					$count_page=999;
				}elseif(preg_match($pattern, $count_page)){
					$count_page=preg_replace($pattern, '', $count_page);

				}
				//var_dump($count_page) ;

			}

			//global $wpdb;
			$num=$count_page;
			//$total = count($images) ; 
			$total = intval(((count($images) - 1) / $num) + 1);
			//$total_img=count($images);
			if(isset($_GET['page-img'.$videogalleryID.$pID])){
				$page = $_GET['page-img'.$videogalleryID.$pID];
			}else{
				$page = '';
					}
			
			$page = intval($page);
			if(empty($page) or $page < 0) $page = 1;  
			  if($page > $total) $page = $total; 
			$start = $page * $num - $num; 
                        $query = $db->getQuery(true);
                        $query->select('*');
                        $query->from('#__huge_it_videogallery_videos');
                        $query -> where('videogallery_id ='.$idofgallery);
                        $query ->order('#__huge_it_videogallery_videos.ordering asc');
                        $db->setQuery($query,$start,$num);
                        $page_images = $db->loadObjectList();
			//$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos where videogallery_id = '%d' order by ordering ASC LIMIT ".$start.",".$num."",$idofgallery);
			
			//$page_images=$wpdb->get_results($query);
			if($disp_type==2){
				$page_images=$images;
				$count_page=9999;
			}
			?>
			<input type="hidden" id="total" value="<?=$total; ?>" />
			<?php
			foreach($page_images as $key=>$row)
			{
				$videourl=$row->image_url;
				//var_dump($row);
				$pattern='/watch\?v=/';
				$videourl=preg_replace($pattern, 'embed/', $videourl);
				$icon=$this->youtube_or_vimeo($videourl);
				//var_dump($icon);
				$video_name=str_replace('__5_5_5__','%',$row->name);
				$video_desc=str_replace('__5_5_5__','%',$row->description);
				$video_thumb=$row->thumb_url;

					
			if($paramssld['video_view9_image_position'] == 1) :
				//var_dump($row);
			?>
			<div class="video_view9_container">
				<input type="hidden" class="pagenum" value="1" />
				<div class="video_view9_vid_wrapper" data-id="<?php echo $row->id; ?>">
					<?php if($video_thumb != ''):?>
					<div class="thumb_wrapper" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
						<img class="thumb_image" style="cursor: pointer;" src="<?php echo $video_thumb; ?>" alt="" />
						
							<div class="playbutton <?php echo $icon;?>-icon"></div>
						
					</div>
					<?php endif;?>
					<div id="thevideo" style="display: block;">
							<?php
						$videourl=$this->get_video_gallery_id_from_url($row->image_url);
							if($videourl[1]=='youtube'){ ?>
								<iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>" style="border: 0;" allowfullscreen></iframe>
								<?php
							}else {
								?>
									<iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"  style="border: 0;" allowfullscreen></iframe>
								<?php
							}
							?>
					</div>
				</div>
				<h1 class="video_new_view_title"><?php echo $video_name;?></h1>
				<div class="video_new_view_desc"><?php echo $video_desc;?></div>
			</div>
			<div class="clear"></div>
			<?php 
				elseif($paramssld['video_view9_image_position'] == 2) :
				?>
			<div class="video_view9_container">
				<input type="hidden" class="pagenum" value="1" />
				<h1 class="video_new_view_title"><?php echo $video_name;?></h1>
				<div class="video_view9_vid_wrapper" data-id="<?php echo $row->id; ?>">
									
						<?php if($video_thumb != ''):?>
						<div class="thumb_wrapper" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
							<img class="thumb_image" style="cursor: pointer;" src="<?php echo $video_thumb; ?>" alt="" />
							
							<div class="playbutton <?php echo $icon;?>-icon"></div>
							
						</div>
						<?php endif;?>
						
						<div class="thumb_wrapper" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
										
							<div class="playbutton hg_play_button <?php echo $icon;?>-icon" data-id="<?php echo $row->id; ?>"></div>
							
						</div>
						<div id="thevideo" style="display: block;">
									
								<?php
						$videourl=$this->get_video_gallery_id_from_url($row->image_url);
							if($videourl[1]=='youtube'){ ?>
								<iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>" style="border: 0;" allowfullscreen></iframe>
								<?php
							}else {
								?>	
									<iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"  style="border: 0;" allowfullscreen></iframe>
								<?php
							}
							?>
							<!-- <iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="<?php echo $videourl;?>" frameborder="0" allowfullscreen> -->
							
				 		</iframe>
						</div>
				
				</div>
				<div class="video_new_view_desc"><?php echo $video_desc;?></div>
			
			</div>
			
			<div class="clear"></div>
			<?php
			elseif($paramssld['video_view9_image_position'] == 3) :
			?>
			<div class="video_view9_container">
				<input type="hidden" class="pagenum" value="1" />
				<h1 class="video_new_view_title"><?php echo $video_name;?></h1>
				<div class="video_new_view_desc"><?php echo $video_desc;?></div>
				<div class="video_view9_vid_wrapper" data-id="<?php echo $row->id; ?>">
					<?php if($video_thumb != ''):?>
						<div class="thumb_wrapper" onclick="thevid=document.getElementById('thevideo'); thevid.style.display='block'; this.style.display='none'">
							<img class="thumb_image" style="cursor: pointer;" src="<?php echo $video_thumb; ?>" alt="" />
							
							<div class="playbutton <?php echo $icon;?>-icon"></div>
							
						</div>
						<?php endif;?>
						<div id="thevideo" style="display: block;">
								<?php
						$videourl=$this->get_video_gallery_id_from_url($row->image_url);
							if($videourl[1]=='youtube'){ ?>
								<iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="//www.youtube.com/embed/<?php echo $videourl[0]; ?>" style="border: 0;" allowfullscreen></iframe>
								<?php
							}else {
								?>
									<iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"  style="border: 0;" allowfullscreen></iframe>
								<?php
							}
							?>
							<!-- <iframe class="video_view9_img" width="<?php echo $paramssld['video_ht_view9_video_width']; ?>" height="<?php echo $paramssld['video_ht_view9_video_height']; ?>" src="<?php echo $videourl;?>" frameborder="0" allowfullscreen> -->
							
				 		</iframe>
						</div>
				</div>
			</div>

			<div class="clear"></div>
			<?php
			endif;
			}


			
		?>
	</div>
	
		<?php 
		 $path_site = JUri::root()."media/com_videogallerylite/Front_images";
                 
		$path_siteArrow = JUri::root()."media/com_videogallerylite/images";	
                $a=$disp_type;
			if($a==1){
				 $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
				$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
				$pattern="/\?p=/";
				$pattern2="/&page-img[0-9]+=[0-9]+/";
				$pattern3="/\?page-img[0-9]+=[0-9]+/";
					if(preg_match($pattern, $actual_link)){

						if(preg_match($pattern2, $actual_link)){
							$actual_link=preg_replace($pattern2, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
							
						}	
					}elseif(preg_match($pattern3, $actual_link)){
						$actual_link=preg_replace($pattern3, '', $actual_link);
							header("Location:".$actual_link."");
							exit;
						
					}
				?>
				<div class="load_more">
					<div class="load_more_button"><?=$paramssld['video_ht_view9_loadmore_text']; ?></div>
					<div class="loading"><img src="<?php if($paramssld['loading_type'] == '1') {echo $path_siteArrow.'/loading1.gif';} 
					elseif ($paramssld['loading_type'] == '2') { echo $path_siteArrow.'/loading4.gif'; }
					elseif($paramssld['loading_type'] == '3') { echo $path_siteArrow.'/loading36.gif'; }
					elseif ($paramssld['loading_type'] == '4') { echo $path_siteArrow.'/loading51.gif'; }?>"></div>
					<script>
						jQuery(document).ready(function(){
							if(jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button").length){
								jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button").on("click tap",function(){
									if(jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> .pagenum:last").val()<jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> #total").val()) {
										var pagenum = parseInt(jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> .pagenum:last").val()) + 1;
										var perpage =<?=$count_page; ?>;
										var galleryid="<?=$image->videogallery_id; ?>";
										var width="<?=$paramssld['video_ht_view9_video_width']; ?>";
										var height="<?=$paramssld['video_ht_view9_video_height']; ?>";
										var position="<?=$paramssld['video_view9_image_position']; ?>";
										getresult(pagenum,perpage,galleryid,width,height,position);
									}else{
										jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button').hide();
									}
									return false;
								});
							}
if(jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> .pagenum:last").val()==jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> #total").val()) {
												jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button').hide();
											}
							function getresult(pagenum,perpage,galleryid,width,height,position){
								var data = {
										action:"huge_it_video_gallery_ajax",
										task:'load_videos',
										page:pagenum,
										perpage:perpage,
										galleryid:galleryid,
										width:width,
										height:height,
										position:position
								}
								jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .loading').show();
								jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button').hide();
								jQuery.post("components/com_videogallerylite/ajax_url.php",data,function(response){
									if(response.success){
										setTimeout(function(){
											jQuery("#video_view9_cont_list<?=$videogalleryID; ?>").append(response.success);
											jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button').show();
											jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .loading').hide();
											
										},10);
										setTimeout(function(){
											if(jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> .pagenum:last").val()==jQuery("#video_view9_cont_wrapper<?=$videogalleryID; ?> #total").val()) {
												jQuery('#video_view9_cont_wrapper<?=$videogalleryID; ?> .load_more_button').hide();
											}
										},1000);

									}else{
										alert("no");
									}
								},"json");
							}

						});
					</script>
				</div>
				<?php 
			}elseif($a==0){
		?>
		<div class="paginate">
			<?php
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$actual_link = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."";
			
			
			$checkREQ='';
			$pattern="/\?p=/";
			$pattern2="/&page-img[0-9]+=[0-9]+/";
			$pattern3="/?page-img[0-9]+=[0-9]+/";
			//$res=preg_match($pattern, $actual_link);
			if(preg_match($pattern, $actual_link)){

				if(preg_match($pattern2, $actual_link)){
					$actual_link=preg_replace($pattern2, '', $actual_link);
				}

				$checkREQ=$actual_link.'&page-img'.$videogalleryID.$pID;
				
			}else{
				$checkREQ='?page-img'.$videogalleryID.$pID;
				
			}
			//var_dump($res);  
			
			// Проверяем нужны ли стрелки назад  
				$pervpage='';
			if ($page != 1) $pervpage = '<a href= '.$checkREQ.'=1><i class="hugeiticons hugeiticons-fast-backward" ></i></a>  
			                               <a href= '.$checkREQ.'='. ($page - 1) .'><i class="hugeiticons hugeiticons-chevron-left"></i></a> ';  
			// Проверяем нужны ли стрелки вперед  
		       $nextpage='';
			if ($page != $total) $nextpage = ' <a href= '.$checkREQ.'='. ($page + 1) .'><i class="hugeiticons hugeiticons-chevron-right"></i></a>  
			                                   <a href= '.$checkREQ.'=' .$total. '><i class="hugeiticons hugeiticons-fast-forward" ></i></a>';  

			// Находим две ближайшие станицы с обоих краев, если они есть  
			/*if($page - 2 > 0) $page2left = ' <a href= ./?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';  
			if($page - 1 > 0) $page1left = '<a href= ./?page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';  
			if($page + 2 <= $total) $page2right = ' | <a href= ./?page='. ($page + 2) .'>'. ($page + 2) .'</a>';  
			if($page + 1 <= $total) $page1right = ' | <a href= ./?page='. ($page + 1) .'>'. ($page + 1) .'</a>'; */

			// Вывод меню  
			echo $pervpage.$page.'/'.$total.$nextpage;  

			?>
</div>
<?php
}
?>
				
</div>
          <?php 
            break;

?>
<?php
}
return $render_html1 = ob_get_clean();
}


}
	