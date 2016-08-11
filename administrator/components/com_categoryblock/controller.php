<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * General Controller of CategoryBlock component
 */
class CategoryBlockController extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = array()) 
        {
                
                // set default view if not set
                JRequest::setVar('view', JRequest::getCmd('view', 'profilelist'));
                
                
                // call parent behavior
                parent::display($cachable);
        }
}
