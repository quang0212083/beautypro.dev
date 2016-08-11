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
 
 echo 'zzzaaa1';
		die;
 
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 
/**
 * CategoryBlock- ProfileList Controller
 */

 
 
 
class CategoryBlockControllerProfileList extends JControllerAdmin
{
	function display()
	{
			$task=JRequest::getVar( 'task');
				
				switch($task)
				{
						case 'delete':
								$this->delete();
								break;
						case 'profilelist.delete':
								$this->delete();
								break;
						case 'remove_confirmed':
								$this->remove_confirmed();
								break;
						case 'profilelist.remove_confirmed':
								$this->remove_confirmed();
								break;
						case 'copyItem':
								$this->copyItem();
								break;
						case 'profilelist.copyItem':
								$this->copyItem();
								break;
						
						default:
								JRequest::setVar( 'view', 'profilelist');
								parent::display();
								break;
				}
		
				
	}

        public function getModel($name = 'ProfileList', $prefix = 'CategoryBlockModel') 
        {
                $model = parent::getModel($name, $prefix, array('ignore_request' => true));
				
                return $model;
        }
 
        public function delete()
        {
                
        	// Check for request forgeries
        	JRequest::checkToken() or jexit( 'Invalid Token' );
        	
            $cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );

            if (!count($cid)) {

                $this->setRedirect( 'index.php?option=com_categoryblock&view=profilelist', JText::_('COM_CATEGORYBLOCK_NO_ITEMS_SELECTED'),'error' );
                
        		return false;
        	}
		
        	$model =$this->getModel();
        	
        	$model->ConfirmRemove();
        }
	
        public function remove_confirmed()
        {
		
        	// Get some variables from the request
        	
        	$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );


        	if (!count($cid)) {
        		$this->setRedirect( 'index.php?option=com_categoryblock&view=flashmovielist', JText::_('COM_CATEGORYBLOCK_NO_ITEMS_SELECTED'),'error' );
        		return false;
        	}

        	$model = $this->getModel('profileform');
        	if ($n = $model->deleteCategories($cid)) {
        		$msg = JText::sprintf( 'COM_CATEGORYBLOCK_ITEM_S_DELETED', $n );
        		$this->setRedirect( 'index.php?option=com_categoryblock&view=profilelist', $msg );
        	} else {
        		$msg = $model->getError();
        		$this->setRedirect( 'index.php?option=com_categoryblock&view=profilelist', $msg,'error' );
        	}
		
        }
		
		public function copyItem()
		{
				
		    $cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
	    
		    $model = $this->getModel('profilelist');
	    
	    
		    if($model->copyItem($cid))
		    {
				$msg = JText::_( 'COM_CATEGORYBLOCK_PROFILE_COPIED_SUCCESSFULLY' );
		    }
		    else
		    {
				$msg = JText::_( 'COM_CATEGORYBLOCK_PROFILE_WAS_UNAMBE_TO_COPY' );
		    }
	    
		    $link 	= 'index.php?option=com_categoryblock&view=profilelist';
		    $this->setRedirect($link, $msg);
		}

}

?>