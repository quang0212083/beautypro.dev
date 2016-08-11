<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentbuilder_verifyPassthrough extends JPlugin
{

    function __construct( &$subject, $params )
    {
        parent::__construct($subject, $params);
    }

    /**
     * Will be called in the content element (article or record)
     * If the return is not empty, it will render the returned value.
     * 
     * By that things like coupon codes may be implemented.
     * 
     * @param string $link the link that points to the verifier
     * @param string $plugin_settings The raw query string with the plugin options
     * @return string empty for nothing (default) or a string to render instead of the default
     */
    function onViewport($link, $plugin_settings){
        
        return '';
    }
    
    /**
     * Will always be called by the verifier
     * 
     * @param type $return_url
     * @param type $options
     * @return string empty if everything is ok, else a message describing the problem 
     */
    function onSetup($return_url, $options){
        
        return '';
    }

    /**
     * Will be called on forward, right after setup IF there is no verification yet
     * 
     * @param string $return_url
     * @param array $options 
     */
    function onForward($return_url, $options){
        return $return_url;
    }

    /**
     * Will be called on verification
     * 
     * @param string $return_url
     * @param array $options
     * @return mixed boolean false on errors or an array with optional verification data (msg[string], is_test[0/1], data [array])
     */
    function onVerify($return_url, $options){
        
        return array(
                    'msg' => '', 
                    'is_test' => 0,
                    'data' => array()
                );
    }
}
