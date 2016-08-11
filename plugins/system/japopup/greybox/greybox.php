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

if (!class_exists('greyboxClass')) {
    class greyboxClass extends JAPopupHelper
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
            parent::__construct("greybox", $pluginParams);
            $this->_modal_name = "greybox";
            $this->_pluginParams = $pluginParams;
        }


        /**
         * Get Library for GreyBox
         * @param 	Array	$pluginParams	Plugin paramaters
         * @return 	String	Include JS, CSS string.
         * */
        function getHeaderLibrary()
        {
            // Base path string
            $hs_base = JURI::base() . 'plugins/system/japopup/' . $this->_modal_name . '/';
            // Tag array
            $headtag = array();
            $headtag[] = '<script type="text/javascript" > var GB_ROOT_DIR = "' . $hs_base . '"; var DimOverlay = "'.$this->_pluginParams->get("overlay").'"</script>';
            $headtag[] = '<script src="' . $hs_base . 'js/AJS.js" type="text/javascript" ></script>';
            $headtag[] = '<script src="' . $hs_base . 'js/AJS_fx.js" type="text/javascript" ></script>';
            $headtag[] = '<script src="' . $hs_base . 'js/gb_scripts.js" type="text/javascript" ></script>';
            $headtag[] = '<link href="' . $hs_base . 'css/gb_styles.css" type="text/css" rel="stylesheet" />';

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
            $ranID = time() . rand(0, 100);
            // To standard content
            $content = html_entity_decode($content);

            // Config for GreyBox
            $modalBehavior = "gb_page" . $this->_pluginParams->get("group1-greybox-behavior");
            $modalGroup = $this->getValue("group");
            $modalContent = $this->getValue("content");
            if (!empty($modalGroup))
                $relGroup = 'gb_pageset[' . $modalGroup . ']';
            else {
                $relGroup = '' . $modalBehavior . '[' . $arrData['frameWidth'] . ', ' . $arrData['frameHeight'] . ']';
                $modalGroup = $ranID;
            }
            $arrData["rel"] = $relGroup;
            $arrData["group"] = $modalGroup;
            $arrData["overlayShow"] = $this->_pluginParams->get("overlay");
            $arrData["overlayOpacity"] = $this->_pluginParams->get("overlay_opacity");

            $type = $this->getValue("type");
            $str = "";

            switch ($type) {               
				case "ajax":
                case "iframe":
                    {
                        $str .= $this->showDataInTemplate("greybox", "default", $arrData);
                        break;
                    }
				case "inline":
				{
					$lang = JFactory::getLanguage();
					$lang->load('plg_system_japopup', JPATH_ADMINISTRATOR);
					return "<a href='#' onclick=\"alert('".JText::_('NOT_SUPPORT_INLINE_TYPE')."')\">".$arrData['content']."</a>";					
				}
                case "image":
                    {
                        if (!empty($modalGroup))
                            $arrData['rel'] = 'gb_imageset[' . $modalGroup . ']';
                        else {
                            $arrData['rel'] = 'gb_imageset[' . $ranID . ']';
                            $modalGroup = $ranID;
                        }
                        $str .= $this->showDataInTemplate("greybox", "default", $arrData);
                        break;
                    }

                case "slideshow":
                    {

                        $show = false;

                        $modalContent = $arrData['content'];

                        foreach ($modalContent as $k => $v) {
                            $image_url = trim($v);
                            $arrData['href'] = $image_url;
                            $arrData['rel'] = "gb_imageset[" . $ranID . "]";
                            $arrData['content'] = "";

                            if ($arrData['imageNumber'] == "all") {
                                $arrData['content'] = "<img src=\"" . $image_url . "\" width=\"" . $arrData['frameWidth'] . "\"/>";
                            } elseif ($show === false) {
                                $show = true;
                                $arrData['content'] = $content;
                            }
                            $str .= $this->showDataInTemplate("greybox", "default", $arrData);
                        }
                        break;
                    }

                case "youtube":
                    {
                        $str .= $this->showDataInTemplate("greybox", "default", $arrData);
                        break;
                    }
            }

            // Return value string.
            return $str;
        }

    }
}
?>