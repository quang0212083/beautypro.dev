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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * CategoryBlock FlashMovieList View
 */
class CategoryBlockViewDocumentation extends JView
{
        /**
         * CategoryBlock view display method
         * @return void
         */
        function display($tpl = null) 
        {
                JToolBarHelper::title(JText::_('Category Block - Documentation'), 'generic.png');
                 
                 
                parent::display($tpl);
        }
        

}
