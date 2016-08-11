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
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

//error_reporting(E_ALL);  
/**
 * CategoryBlock Model
 */
class CategoryBlockModelProfileForm extends JModelAdmin
{
        /**
         * Returns a reference to the a Table object, always creating it.
         *
         * @param       type    The table type to instantiate
         * @param       string  A prefix for the table class name. Optional.
         * @param       array   Configuration array for model. Optional.
         * @return      JTable  A database object
         */
		public $id;
		
        public function getTable($type = 'CategoryBlock', $prefix = 'CategoryBlockTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
        /**
         * Method to get the record form.
         *
         * @param       array   $data           Data for the form.
         * @param       boolean $loadData       True if the form is to load its own data (default case), false if not.
         * @return      mixed   A JForm object on success, false on failure
         */
        public function getForm($data = array(), $loadData = true) 
        {
				//echo '7676f';
                // Get the form.
                $form = $this->loadForm('com_categoryblock.profileform', 'profileform', array('control' => 'jform', 'load_data' => $loadData));
				//echo 'abc';
                if (empty($form)) 
                {
                        return false;
                }
                return $form;
        }
		

	
		/**
         * Method to get the script that have to be included on the form
         *
         * @return string       Script files
         */
        public function getScript() 
        {
                return 'administrator/components/com_categoryblock/models/forms/profileform.js';
        }
		
        /**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         */
        protected function loadFormData() 
        {
                // Check the session for previously entered form data.
                $data = JFactory::getApplication()->getUserState('com_categoryblock.edit.profileform.data', array());
                if (empty($data)) 
                {
                        $data = $this->getItem();
                }
                return $data;
        }
        

        function store()
        {
                
                
        	$row = $this->getTable('categoryblock');

            
        	// consume the post data with allow_html
        	$data_ = JRequest::get( 'post',JREQUEST_ALLOWRAW);
		$data=$data_['jform'];

            
            $profilename=trim(preg_replace("/[^a-zA-Z0-9_]/", "", $data['jform']['profilename']));
            
            $data['jform']['profilename']=$profilename;

		//print_r($data);

        	if (!$row->bind($data))
        	{
                
        		return false;
        	}
		
		$row->id=(int)JRequest::getVar('id',0,'post');
		
          	//	echo '$row->id='.$row->id.'<br/>';
		//print_r($row);
//die;          
        	$post = array();
				
		
        	// Make sure the  record is valid
        	if (!$row->check())
        	{
                
        		return false;
        	}

        	// Store
			//echo $row->store();
			//die;
        	if (!$row->store())
        	{
				JError::raiseError(500, $row->getError() );
				//print_r($row);
                echo 'e3';
        		return false;
        	}
				
        	$this->id=$row->id;

        	return true;
        }
        
        function deleteCategories($cids)
        {

        	$row = $this->getTable('categoryblock');

            $db = JFactory::getDBO();
            
        	if (count( $cids ))
        	{
        		foreach($cids as $cid)
        		{
						
				
				if (!$row->delete( $cid ))
				{
					return false;
				}
			}
        	}
		
		
		
        	return true;
        }
}
