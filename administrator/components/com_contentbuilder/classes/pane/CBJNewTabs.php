<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// shouldn't be required no longer in Joomla 3.0 Stable
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'pane'.DS.'CBBehaviorTabs.php');

    class CBTabs  {
            
            private $type = '';
            private $options = array();
            
            function __construct( $type = 'tabs', $options = array() ) {
                $this->options = $options;
                $this->type = $type;
            }
            
            public static function getInstance($type, $options = array()){
                
                static $instance;
                
                if( !$instance ){
                    $instance = new CBTabs($type, $options);
                }
                return $instance;
            }

            function startPanel( $tabText, $paneid ) {
                return CBBehaviorTabs::panel($tabText, $paneid);
            }

            function endPanel() {
                    return '';
            }
            
            function endTab() {
                    return '';
            }

            function startPane( $tabText ){
                    return CBBehaviorTabs::start($this->type, $this->options);
            }

            function endPane(){
                return CBBehaviorTabs::end();
            }
    }
