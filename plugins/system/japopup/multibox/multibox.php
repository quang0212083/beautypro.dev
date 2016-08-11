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

if (!class_exists('multiboxClass')) {
    class multiboxClass extends JAPopupHelper
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
            parent::__construct("multibox", $pluginParams);
            $this->_modal_name = "multibox";
            $this->_pluginParams = $pluginParams;
        }


        /**
         * Get Library for MultiBox
         * @param 	Array	$pluginParams	Plugin paramaters
         * @return 	String	Include JS, CSS string.
         * */
        function getHeaderLibrary()
        {
            // Base path string
            $hs_base = JURI::base() . 'plugins/system/japopup/' . $this->_modal_name . '/';
            // Tag array
            $headtag = array();
            $headtag[] = '<script src="' . $hs_base . 'js/overlay.js" type="text/javascript" ></script>';
            $headtag[] = '<script src="' . $hs_base . 'js/multibox.js" type="text/javascript" ></script>';
            $headtag[] = '<link href="' . $hs_base . 'css/multibox.css" type="text/css" rel="stylesheet" />';
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

            $eventStr = "";
            if ($arrData['onopen'] != "" || $arrData['onclose'] != "") {
                $arrData['onopen'] = ($arrData['onopen'] != '') ? ",onOpen: " . $arrData['onopen'] : "";
                $arrData['onclose'] = ($arrData['onclose'] != '') ? ",onClose: " . $arrData['onclose'] : "'";
                $eventStr = $arrData['onopen'] . $arrData['onclose'];
            }

            $str = "";
            $modalGroup = $this->getValue("group");

            if (!empty($modalGroup)) {
                $classGroup = $modalGroup;
                $str .= '<script type="text/javascript" >
							if(! box' . $modalGroup . ' ) {
								var box' . $modalGroup . ' = {};
								window.addEvent("domready", function()
								{
									box' . $modalGroup . ' = new multiBox({
																		mbClass: \'.' . $classGroup . '\',
																		container: $(document.body),
																		currentGallery: \'jagroup' . $modalGroup . '\',
																		descClassName: \'multiBoxDesc\',
																		useOverlay: ' . $this->_pluginParams->get("overlay") . ',
																		contentColor: \'#' . $this->_pluginParams->get("group1-multibox-contentColor") . '\',
																		showControls: ' . $this->_pluginParams->get("group1-multibox-showControls") . ' ' . $eventStr . ',
																		maxSize: {w:' . $arrData["frameWidth"] . ', h:' . $arrData["frameHeight"] . '},
																		showNumbers: true,
																		showControls: '.$this->_pluginParams->get("group1-multibox-showControls").'
																		});
								});
							}
						</script>';
            } else {
                $classGroup = 'jagroup' . $ranID;
                $str .= '<script type="text/javascript" >
							var box' . $ranID . ' = {};
							window.addEvent("domready", function(){
								box' . $ranID . ' = new multiBox({
															mbClass: \'.' . $classGroup . '\',
															container: $(document.body),//where to inject multiBox
															descClassName: \'multiBoxDesc\',//the class name of the description divs
															useOverlay: ' . $this->_pluginParams->get("overlay") . ',//use a semi-transparent background. default: false;
															contentColor: \'#' . $this->_pluginParams->get("group1-multibox-contentColor") . '\',
															showControls: '.$this->_pluginParams->get("group1-multibox-showControls").',
															maxSize: {w:600, h:400}//max dimensions (width,height) - set to null to disable resizing
															});
							});
						</script>';
            }

            $arrData["group"] = $modalGroup;
            $arrData["id"] = "mb" . $ranID;
            $arrData["class"] = $classGroup;

            $type = $this->getValue("type");

            switch ($type) {
                case "ajax":
                    {
                        //$arrData['title'] = 'AJAX';
                        //$arrData['rel'] = 'width:' . $arrData['frameWidth'] . ',height:' . $arrData['frameHeight'] . ',req:true';
						$arrData['rel'] = 'width:' . $arrData['frameWidth'] . ',height:' . $arrData['frameHeight'];
                        $str .= $this->showDataInTemplate('multibox', 'default', $arrData);
                        break;
                    }

                case "iframe":
                    {
                        $arrData['rel'] = 'width:' . $arrData['frameWidth'] . ',height:' . $arrData['frameHeight'];
                        $str .= $this->showDataInTemplate('multibox', 'default', $arrData);
                        break;
                    }

                case "inline":
                    {
                        $arrData['href'] = "#" . $arrData['href'];
                        $arrData['rel'] = "type:element";
                        $str .= $this->showDataInTemplate("multibox", "default", $arrData);
                        break;
                    }

                case "image":
                    {
                        $arrData['rel'] = "[images]";
                        if (trim($arrData['desc']) == '') {
                            $arrData['rel'] .= ',noDesc';
                        }
                        $str .= $this->showDataInTemplate("multibox", "default", $arrData);
                        break;
                    }

                case "slideshow":
                    {
                        $modalContent = $this->getValue("content");
                        $modalContent = $arrData['content'];
                        $show = false;
                        foreach ($modalContent as $k => $v) {
                            $image_url = trim($v);
                            $arrData['rel'] = "[images],noDesc";
                            $arrData['href'] = $image_url;
                            $arrData['content'] = "";

                            if ($arrData['imageNumber'] == "all") {
                                $arrData['content'] = "<img src=\"" . $image_url . "\" width=\"" . $arrData["frameWidth"] . "\"/>";
                            } elseif ($show === false) {
                                $show = true;
                                $arrData['content'] = $content;
                            }

                            $str .= $this->showDataInTemplate("multibox", "default", $arrData);
                        }
                        break;
                    }

                case "youtube":
                    {
                        $arrData['rel'] = 'width:' . $arrData['frameWidth'] . ',height:' . $arrData['frameHeight'] . ',[video]';
						if (trim($arrData['desc']) == '') {
                            $arrData['rel'] .= ',noDesc';
                        }
                        $str .= $this->showDataInTemplate("multibox", "default", $arrData);
                        break;
                    }

            }
            //$str = 'Xin chao';
            // Return value string.
            return $str;
        }
    }
}
?>