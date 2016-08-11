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

if (!class_exists('highslideClass')) {
    class highslideClass extends JAPopupHelper
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
            parent::__construct("highslide", $pluginParams);
            $this->_modal_name = "highslide";
            $this->_pluginParams = $pluginParams;
        }


        /**
         * Get Library for HighSlide
         * @param 	Array	$pluginParams	Plugin paramaters
         * @return 	String	Include JS, CSS string.
         * */
        function getHeaderLibrary()
        {

            // Base path string
            $hs_base = JURI::base() . 'plugins/system/japopup/' . $this->_modal_name . '/';
            // Tag array
            $headtag = array();
            // CSS
            $headtag[] = '<link href="' . $hs_base . 'css/highslide.css" type="text/css" rel="stylesheet" />';
            //JS
            $headtag[] = '<script src="' . $hs_base . 'js/do_cookie.js" type="text/javascript" ></script>';
            $headtag[] = '<script src="' . $hs_base . 'js/highslide-full.js" type="text/javascript" ></script>';
            $headtag[] = '<script src="' . $hs_base . 'js/swfobject.js" type="text/javascript" ></script>';
            $headtag[] = "<script type='text/javascript' >
							hs.graphicsDir = '" . $hs_base . "images/';
							hs.wrapperClassName = 'draggable-header';
							</script>";
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
            $modalContent = $this->getValue("content");
            // Get common value


            $str = "";
            $modalGroup = $this->getValue("group");
            if (!empty($modalGroup)) {
                $captionId = " , captionId: 'CaptionArea" . $modalGroup . date("dHis") . "'";
                if (!isset($_SESSION["CaptionArea" . $modalGroup . date("dHis")])) {
                    $str .= $this->showCaptionArea("CaptionArea" . $modalGroup . date("dHis"));
                    $_SESSION["CaptionArea" . $modalGroup . date("dHis")] = "true";
                }
            } else {
                $captionId = '';
            }

			$arrData["outlineType"] = $this->_pluginParams->get("group1-highslide-outline") == 0 ? 'null' : " '" . $this->_pluginParams->get("group1-highslide-outline") . "' ";
            $arrData["group"] = $modalGroup;
            $arrData["class"] = "highslide" . $modalGroup;
            $arrData["expandDuration"] = $this->_pluginParams->get("group1-highslide-speed_in");
            $arrData["restoreDuration"] = $this->_pluginParams->get("group1-highslide-speed_out");
            $arrData["captionId"] = $captionId;
			
			$overlayOpacity = ($this->_pluginParams->get("overlay_opacity")==1)?0.7:$this->_pluginParams->get("overlay_opacity");
			$arrData['overlayOpacity'] = $overlayOpacity;

            // Even proccess
            $arrData['eventStr'] = "";
            if ($arrData['onopen'] != "" || $arrData['onclose'] != "") {
                $arrData['onopen'] = ($arrData['onopen'] != '') ? ",onOpen: " . $arrData['onopen'] : "";
                $arrData['onclose'] = ($arrData['onclose'] != '') ? ",onClose: " . $arrData['onclose'] : "'";
                $arrData['eventStr'] = $arrData['onopen'] . $arrData['onclose'];
            }

            $type = $this->getValue("type");

            switch ($type) {
                case "ajax":
                    {
                        $arrData['objectType'] = "ajax";
						//$arrData['objectType'] = "iframe";
                        $str .= $this->showDataInTemplate("highslide", "default", $arrData);
                        break;
                    }

                case "iframe":
                    {
                        $arrData['objectType'] = "iframe";
                        $str .= $this->showDataInTemplate("highslide", "default", $arrData);
                        break;
                    }

                case "inline":
                    {
                        $str .= $this->showDataInTemplate("highslide", "inline", $arrData);
                        break;
                    }

                case "image":
                    {
                        $arrData['class'] = "highslide";
                        //$str .= showDataInTemplate("highslide", "image", $arrData);


                        $str .= '<a class="highslide" href="' . $arrData['href'] . '" onclick="return hs.expand(this, {dimmingOpacity:'.$arrData['overlayOpacity'].', outlineType:' . $arrData['outlineType'] . ' ' . $arrData['captionId'] . ',maxWidth:'.$arrData['frameWidth'].', maxHeight:'. $arrData['frameHeight'].'});" >' . $content . "</a>";
                        break;
                    }

                case "slideshow":
                    {
                        // Show preview image


                        $show = false;
                        $modalContent = $arrData['content'];
                        foreach ($modalContent as $k => $v) {
                            $image_url = trim($v);
                            $arrData['class'] = "highslide";
                            $arrData['href'] = $image_url;
                            $arrData['captionId'] = ", captionId: '" . $ranID . "'";
                            $arrData['content'] = "";

                            if ($arrData['imageNumber'] == "all") {
                                $arrData['content'] = "<img src='" . $image_url . "' width='" . $arrData['frameWidth'] . "'/>";
                            } elseif ($show === false) {
                                $show = true;
                                $arrData['content'] = $content;
                            }
                            // $str .= showDataInTemplate("highslide", "image", $arrData);


                            $str .= '<a class="highslide" href="' . $image_url . '" onclick="return hs.expand(this, {dimmingOpacity:'.$arrData['overlayOpacity'].', outlineType:' . $arrData['outlineType'] . ', captionId: \'ja-highslide' . $ranID . '\',maxWidth:'.$arrData['frameWidth'].', maxHeight:'. $arrData['frameHeight'].'});" >' . $arrData['content'] . '</a>';
                        }
                        // Caption Area
                        $str .= $this->showCaptionArea($ranID);
                        break;

                    }

                case "youtube":
                    {
                        $arrData['objectType'] = "iframe";
                        $str .= $this->showDataInTemplate("highslide", "default", $arrData);
                        break;
                    }
            }

            // Return value string.
            return $str;
        }


        /**
         * Show caption area
         */
        function showCaptionArea($captionID)
        {
            $arrData['captionID'] = "ja-highslide" . $captionID;
            return $this->showDataInTemplate("highslide", "caption", $arrData);
            ;
        }
    }
}

?>