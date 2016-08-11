<?php
/**
 * @package Xpert Tabs
 * @version 3.7
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

// no direct access
defined( '_JEXEC' ) or die('Restricted access');

abstract class modXpertTabsHelper
{

    public static function loadScripts($module, $params){
        $doc = JFactory::getDocument();

        if(!defined('XPERT_TABS'))
        {
            $doc->addScript(JURI::root(true).'/modules/mod_xperttabs/assets/js/tabs.js');
            define('XPERT_TABS', 1);
        }
    }


    public static function generateTabs($tabs, $list, $params, $module)
    {
        $module_id = XEFUtility::getModuleId($module, $params);

        $title_type = $params->get('tabs_title_type');
        $position = $params->get('tabs_position','top');
        $html = array();

        if($title_type == 'custom'){
            $titles = explode(",", $params->get('tabs_title_custom'));
        }

        if($tabs == 0 OR $tabs>count($list)) $tabs = count($list);

        $html[] = '<ul class="txtabs-nav '. $position .' clearfix">';

        for($i=0; $i<$tabs; $i++){

            if($list[$i]->introtext != NULL)
            {
                // li and a classes
                $class = '';
                $aclass = '';

                if(!$i){
                    $class  = 'first active';
                    //$aclass = 'active';
                }
                if($i == $tabs - 1) $class= 'last';

                if($title_type == 'custom') $title = (isset($titles[$i])) ? $titles[$i] : '';
                else $title = $list[$i]->title;

                $html[] = '<li class="'. $class .'">';
                    $html[] = '<a data-toggle="tab" data-target="#'. $module_id . '-'. $i.'">';
                        $html[] = "<span>$title</span>";
                    $html[] = '</a>';
                $html[] = '</li>';
            }

        }
        $html[] = '</ul>';

        return implode("\n", $html);
        
    }

}