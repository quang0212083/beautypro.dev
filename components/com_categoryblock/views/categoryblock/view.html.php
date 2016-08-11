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
 * HTML View class for the CategoryBlock Component
 */
class CategoryBlockViewCategoryBlock extends JViewLegacy
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
                // Assign data to the view
                
                 // Assign data to the view
                $this->categoryblockcode = $this->get('CategoryBlockCode');
 
                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                
 
                // Display the view
                parent::display($tpl);
        }
}


?>