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

if (!class_exists('thickboxClass')) {
    class thickboxClass extends JAPopupHelper
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
            parent::__construct("thickbox", $pluginParams);
            $this->_modal_name = "thickbox";
            $this->_pluginParams = $pluginParams;
        }


        /**
         * Get Library for Thickbox
         * @param 	Array	$pluginParams	Plugin paramaters
         * @return 	String	Include JS, CSS string.
         * */
        function getHeaderLibrary()
        {
            $lang = JFactory::getLanguage();
			$lang->load('plg_system_japopup', JPATH_ADMINISTRATOR);
			// Base path string
            $hs_base = JURI::base() . 'plugins/system/japopup/' . $this->_modal_name . '/';
            // Tag array

            $headtag = array();
            $jversion = new JVersion;
            $headtag[] = '<!--jquery-->';
			$headtag[] = '<script type="text/javascript">var next_text = "'.JText::_("POPUP_NEXT_TEXT").'";var prev_text="'.JText::_("POPUP_PREV_TEXT").'";var image_text="'.JText::_("POPUP_IMAGE_TEXT").'";var of_text="'.JText::_("POPUP_OF_TEXT").'";var close_text="'.JText::_("POPUP_CLOSE_TEXT").'";var esc_key_text="'.JText::_("POPUP_ESC_KEY_TEXT").'";</script>';
            $headtag[] = '<script type="text/javascript" >var tb_pathToImage = "'.JURI::base().'plugins/system/japopup/thickbox/images/loadingAnimation.gif";</script>';
            $headtag[] = '<script type="text/javascript" src="' . $hs_base . 'js/thickbox.js"></script>';
            $headtag[] = '<link href="' . $hs_base . 'css/thickbox.css" type="text/css" rel="stylesheet" />';
			if($this->_pluginParams->get('group1-shadowbox-overlayColor')){
                $headtag[] = '<style>div.TB_overlayBG{background-color: #'.$this->_pluginParams->get("group1-shadowbox-overlayColor").';}</style>';               
            }
			if($this->_pluginParams->get('group1-shadowbox-viewportPadding')){
                $headtag[] = '<style>div#TB_window{padding: '.$this->_pluginParams->get("group1-shadowbox-viewportPadding").'px;}</style>';               
            }
			$headtag[] = '<style>div#TB_window{padding: 20px;}</style>';

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
            $modalContent = $this->getValue("content");
            // Generate random id
            $ranID = time() . rand(0, 100);
            $content = html_entity_decode($content);

            $modalGroup = $this->getValue("group");
            if (!empty($modalGroup))
                $relGroup = 'jagroup' . $modalGroup;
            else {
                $relGroup = '';
            }
            $arrData['group'] = $modalGroup;
            $arrData['rel'] = $relGroup;

            $eventStr = "";
            if ($arrData['onopen'] != "" || $arrData['onclose'] != "") {
                $arrData['onopen'] = ($arrData['onopen'] != '') ? "&amp;onOpen=" . $arrData['onopen'] : "";
                $arrData['onclose'] = ($arrData['onclose'] != '') ? "&amp;onClose=" . $arrData['onclose'] : "'";
                $eventStr = $arrData['onopen'] . $arrData['onclose'];
            }

            $type = $this->getValue("type");
            $str = "";

            switch ($type) {
			
                case "ajax":
				/*
                    {
                        $arrData['href'] = $arrData['href'] . "?height=" . $arrData['frameHeight'] . "&amp;width=" . $arrData['frameWidth'] . $eventStr;
                        $str .= $this->showDataInTemplate("thickbox", "default", $arrData);
                        break;
                    }
*/
                case "iframe":
                    {
                        $arrData['href'] = $arrData['href'] . "?keepThis=true&amp;TB_iframe=true&amp;height=" . $arrData['frameHeight'] . "&amp;width=" . $arrData['frameWidth'] . $eventStr;
                        $str .= $this->showDataInTemplate("thickbox", "default", $arrData);
                        break;
                    }

                case "inline":
                    {
                        $arrData['href'] = "#TB_inline?height=" . $arrData['frameHeight'] . "&amp;width=" . $arrData['frameWidth'] . "&amp;inlineId=" . $arrData['href'] . $eventStr;
                        $str .= $this->showDataInTemplate("thickbox", "default", $arrData);
                        break;
                    }

                case "image":
                    {
                        $arrData['href'] = $arrData['href'] . "?keepThis=true&amp;TB_iframe=true" . $eventStr;
                        $str .= $this->showDataInTemplate("thickbox", "default", $arrData);
                        break;
                    }

                case "slideshow":
                    {
                        $modalContent = $arrData['content'];
                        $show = false;
						$group_random = rand();
                        foreach ($modalContent as $k => $v) {
                            $image_url = trim($v);
                            $itemData = array();
                            $itemData['title'] = @$arrData['title'];
                            $itemData['href'] = $image_url;
                            $itemData['rel'] = "gallery-plants-".$group_random;
                            $itemData['content'] = "";

                            if ($arrData['imageNumber'] == "all") {
                                $itemData['content'] = "<img src=\"" . $image_url . "\" width=" . $arrData['frameWidth'] . "/>";
                            } elseif ($show === false) {
                                $show = true;
                                $itemData['content'] = $content;
                            }

                            $str .= $this->showDataInTemplate("thickbox", "default", $itemData);
                        }
                        break;
                    }

                case "youtube":
                    {
                        $arrData['YouTubeLink'] = $arrData['href'];
                        $arrData['YouTubeID'] = "youtube" . $ranID;
                        $arrData['href'] = "#TB_inline?height=" . $arrData['frameHeight'] . "&amp;width=" . $arrData['frameWidth'] . "&amp;inlineId=youtube" . $ranID . $eventStr;
                        $str .= $this->showDataInTemplate("thickbox", "youtube", $arrData);

                        break;
                    }

            }

            // Return value string.
            return $str;
        }
    }
}
?>