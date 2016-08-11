<?php
/**
 * ------------------------------------------------------------------------
 * JA CountDown Module for Joomla 2.5 & 3.4
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
class JFormFieldJadate extends JFormField
{
    /*
	 * Category name
	 *
	 * @access	protected
	 * @var		string
	 */
    var $type = 'Jadate';


    function getInput()
    {

        $uri = $this->getCurrentURL();
        $this->loadjscss($uri);
        $value = $this->value ? $this->value : (string) $this->element['default'];
        $string = '<input id="jadate'.$this->name.'" value="' . $value . '" name="' . $this->name . '" >';
        return $string;
    }


    /**
     * get current url
     */
    function getCurrentURL()
    {
        $uri = str_replace(DS, "/", str_replace(JPATH_SITE, JURI::base(), dirname(__FILE__)));
        $uri = str_replace("/administrator", "", $uri);
        return $uri;
    }


    /**
     * load css and js file
     */
    function loadjscss($uri)
    {	
		$document = JFactory::getDocument();
        if (!defined('_JA_PARAM_HELPER_JADATE_')) {
            define('_JA_PARAM_HELPER_JADATE_', 1);
            JHTML::script($uri . '/jadate/Locale.en-US.DatePicker.js');
			JHTML::script($uri . '/jadate/Picker.js');
			JHTML::script($uri . '/jadate/Picker.Attach.js');
			JHTML::script($uri . '/jadate/Picker.Date.js');
			JHTML::stylesheet($uri . '/jadate/datepicker.css');
        }
		$document->addScriptDeclaration("
				window.addEvent('domready', function(){
					new Picker.Date('jadate".$this->name."', {
						timePicker: true,
						minDate:Date.now(),
						positionOffset: {x: 5, y: 0},
						pickerClass: 'datepicker custom',
						format:  '%Y-%m-%d %H:%M:%S',
						useFadeInOut: !Browser.ie
					});
				});
		");

    }
}
?>