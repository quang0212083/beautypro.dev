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



error_reporting(E_ALL); 
/**
 * CategoryBlock View
 */
class CategoryBlockViewProfileForm extends JViewLegacy
{
        /**
         * display method of CategoryBlock view
         * @return void
         */
        public function display($tpl = null) 
        {
                //echo 'ddd';
                // get the Data
                //echo 'dddzz';
                $form = $this->get('Form');
                //echo 'dddb';
                $item = $this->get('Item');
                //echo 'dddc';
                $script = $this->get('Script');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) 
                {
                       // echo 'ddd1';
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                
                //echo 'ddda';
                // Assign the Data
                $this->form = $form;
                $this->item = $item;
                $this->script = $script;

 
                // Set the toolbar
                $this->addToolBar();
 
                // Display the template
                //echo 'ddd2';
                parent::display($tpl);
                
                // Set the document
                $this->setDocument();

        }
 
        /**
         * Setting the toolbar
         */
        protected function addToolBar() 
        {
                JRequest::setVar('hidemainmenu', true);
                $isNew = ($this->item->id == 0);
                JToolBarHelper::title($isNew ? JText::_('COM_CATEGORYBLOCK_NEW') : JText::_('COM_CATEGORYBLOCK_EDIT'));
                JToolBarHelper::apply('profileform.apply');
                JToolBarHelper::save('profileform.save');
                JToolBarHelper::cancel('profileform.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
        }
        
        /**
        * Method to set up the document properties
        *
        * @return void
        */
        protected function setDocument() 
        {
                $isNew = ($this->item->id < 1);
                $document = JFactory::getDocument();
                $document->setTitle($isNew ? JText::_('COM_CATEGORYBLOCK_NEW') : JText::_('COM_CATEGORYBLOCK_EDIT'));
                $document->addScript(JURI::root() . $this->script);
                $document->addScript(JURI::root() . "/administrator/components/com_categoryblock/views/profileform/submitbutton.js");
                JText::script('COM_CATEGORYBLOCK_PROFILEFORM_ERROR_UNACCEPTABLE');
        }
}


?>