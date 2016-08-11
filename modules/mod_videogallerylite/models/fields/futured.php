<?php 
/**
 * @package Video Gallery Lite
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     http://www.huge-it.com/
 **/
defined('_JEXEC') or die('Restricted access');

class JFormFieldFutured extends JFormField {

    protected $type = 'ckillustration';
    
    protected function getInput() {
        $module = strrchr(dirname(dirname(__FILE__)), 'mod_');
        $doc = JFactory::getDocument();
                       
        $type_ = $this->element['type_'];
		if($type_== "text"){
                    return '<div class="element hugeitmicro-item">
                 		<div class="title-block"><h3><p>Soon we will introduce to you our new wonderful pluginds.</p></h3></div>
                            </div>';
                }
    }

    
	


}

