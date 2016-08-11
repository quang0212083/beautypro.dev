<?php
/**
 * ------------------------------------------------------------------------
 * JA Popup Plugin for Joomla 25 & 34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if (!class_exists('fancyboxClass')) {
    class fancyboxClass extends JAPopupHelper
    {
        // Modal name
        var $_modal_name;

        // Plugin params
        var $_pluginParams;

        // Param in {japopup} tag
        var $_tagParams;


        // Constructor
        function __construct($pluginParams)
        {
            parent::__construct("fancybox", $pluginParams);
            $this->_modal_name = "fancybox";
            $this->_pluginParams = $pluginParams;
        }


        /**
         * Get Library for FancyBox
         * @param 	Array	$pluginParams	Plugin paramaters
         * @return 	String	Include JS, CSS string.
         * */
        function getHeaderLibrary()
        {
            $jversion = new JVersion;
            // Base path string
            $hs_base = JURI::base() . 'plugins/system/japopup/' . $this->_modal_name . '/';
            // Tag array
            $headtag = array();
            $headtag[] = '<link type="text/css" rel="stylesheet" href="' . $hs_base . 'css/jquery.fancybox-1.3.4.css" />';
            /*if($jversion->isCompatible('3.0') < 0 || ($jversion->isCompatible('3.0') >= 0 && !preg_match('/\/jquery.min.js/', $bodyString))){ //not sure that jquery is load on J3.0
                $headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/jquery.jbk.js"></script>';
                $headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/jquery-1.8.3.min.js"></script>';
            	$headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/jquery.noconflict.js"></script>';
            }*/
            $headtag[] = '<!--jquery-->';
            $headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/jquery.mousewheel.js"></script>';
            $headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/jquery.fancybox-1.3.4.js"></script>';
            $headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/jquery.easing.1.3.js"></script>'; //do not worry
           
            return $headtag;
        }


        /**
         * Get content to display in Front-End.
         * @param 	Array	$paras	Key and value in {japopup} tag
         * @return 	String	HTML string to display
         * */
        function getContent($paras, $content)
        {
            $arrData = parent::getCommonValue($paras, $content);

            // Generate random id
            $ranID = rand(0, 10000);
            // To standard content
            $content = html_entity_decode($content);

            // Proccess group tag
            $modalGroup = $this->getValue("group");
            if (!empty($modalGroup))
                $relGroup = ' rel ="jagroup' . $modalGroup . '"';
            else {
                $relGroup = '';
                $modalGroup = $ranID;
            }
			
			$overlayOpacity = ($this->_pluginParams->get("overlay_opacity")==1)?0.7:$this->_pluginParams->get("overlay_opacity");
			
            $arrData['rel'] = $relGroup;
            $arrData['group'] = $modalGroup;
            $arrData['class'] = "fancybox" . $modalGroup;
            $arrData['zoomSpeedIn'] = $this->_pluginParams->get("group1-fancybox-speed_in", "500");
            $arrData['zoomSpeedOut'] = $this->_pluginParams->get("group1-fancybox-speed_out", "500");
            $arrData['overlayShow'] = $this->_pluginParams->get("overlay", "1");
            $arrData['overlayOpacity'] = $overlayOpacity;
            $arrData["imageScale"] = $this->_pluginParams->get("group1-fancybox-image_scale", "1");
            $arrData["centerOnScroll"] = $this->_pluginParams->get("group1-fancybox-centerOnScroll", "1");

            $type = $this->getValue("type");
            $str = "";
            switch ($type) {
                case "ajax":
                    {
                        $arrData['group'] = "fancybox" . $arrData['group'];
                        $arrData['class'] .= " iframe";
                        $str .= $this->showDataInTemplate("fancybox", "ajax", $arrData);
                        break;
                    }

                case "iframe":
                    {
                        $arrData['group'] = "fancybox" . $arrData['group'];
                        $arrData['class'] .= " iframe";
                        $str .= $this->showDataInTemplate("fancybox", "iframe", $arrData);
                        break;
                    }

                case "inline":
                    {
                        $arrData['href'] = "#" . $arrData['href'];
                        $str .= $this->showDataInTemplate("fancybox", "inline", $arrData);
                        break;
                    }

                case "image":
                    {
                        $str .= $this->showDataInTemplate("fancybox", "image", $arrData);
                        break;
                    }

                case "slideshow":
                    {
                        if ($arrData['imageNumber'] == "one")
                            $arrData['imageNumber'] = $content;

                        $show = false;
                        $arrData['class'] = "group" . $ranID;
                        $arrData['rel'] = "slideshow" . $ranID;

                        $str .= $this->showDataInTemplate("fancybox", "slideshow", $arrData);

                        break;
                    }

                case "youtube":
                    {
                        $arrData['YoutubeLink'] = str_replace("&amp;", "&", $arrData['href']);
                        $arrData['YoutubeLink'] = str_replace("&", "&amp;", $arrData['href']);
                        
                        $arrData['href'] = str_replace('youtube.com/v/', 'youtube.com/embed/', $arrData['YoutubeLink']);
                        if(strpos($arrData['href'], 'youtube.com/embed/') !== false){
                            $arrData['group'] = "fancybox" . $arrData['group'];
                            $arrData['class'] .= " iframe";
                            $str .= $this->showDataInTemplate("fancybox", "iframe", $arrData);
                        } else {
                            $arrData['href'] = "youtubeID" . $ranID;
                            $arrData['useragent'] = $this->get_user_browser();

                            $str .= $this->showDataInTemplate("fancybox", "youtube", $arrData);
                        }
                        break;
                    }
            }
            // Return value string.
            return $str;
        }
    }
}
?>
