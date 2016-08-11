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
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * CategoryBlock - ProfileForm Controller
 */
class CategoryBlockControllerProfileForm extends JControllerForm
{
	
	function display($cachable = false, $urlparams = array())
	{
		$task=$_POST['task'];
		
	
		if($task=='profileform.add' or $task=='add' )
		{
			$this->setRedirect( 'index.php?option=com_categoryblock&view=profileform&layout=edit');
			return true;
		}
		
		if($task=='profileform.edit' or $task=='edit' )
		{
			$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );

			if (!count($cid))
			{
				$this->setRedirect( 'index.php?option=com_categoryblock&view=profileform', JText::_('COM_CATEGORYBLOCK_NO_ITEMS_SELECTED'),'error' );
				return false;
			}
			
			$this->setRedirect( 'index.php?option=com_categoryblock&view=profileform&layout=edit&id='.$cid[0]);
			return true;
		}
	
		JRequest::setVar( 'view', 'profileform');
		JRequest::setVar( 'layout', 'edit');
		
		switch(JRequest::getVar( 'task'))
		{
		case 'apply':
			$this->save();
			break;
		case 'profileform.apply':
			$this->save();
			break;
		case 'save':
			$this->save();
			break;
		case 'profileform.save':
			$this->save();
			break;
		case 'cancel':
			$this->cancel();
			break;
		case 'profileform.cancel':
			$this->cancel();
			break;
		default:
			parent::display();
			break;
		}
		
	}
    
	function save()
	{
		$task = JRequest::getVar( 'task');
		
		// get our model
		$model = $this->getModel('ProfileForm');
		// attempt to store, update user accordingly
		
		
		if($task != 'save' and $task != 'apply' and $task != 'profileform.save' and $task != 'profileform.apply')
		{
			echo 'error #987554';
			die;
			$link 	= 'index.php?option=com_categoryblock&controller=profilelist';
			
			$msg = JText::_( 'COM_CATEGORYBLOCK_ITEM_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
		
		
		if ($model->store())
		{
		
			if($task == 'save' or $task == 'profileform.save' )
			{
				$link 	= 'index.php?option=com_categoryblock&controller=profilelist';
			}	
			elseif($task == 'apply' or $task == 'profileform.apply')
			{
				$link 	= 'index.php?option=com_categoryblock&view=profileform&layout=edit&id='.$model->id;
			}
			
			$msg = JText::_( 'COM_CATEGORYBLOCK_ITEM_SAVED_SUCCESSFULLY' );
			
			$this->setRedirect($link, $msg);
		}
		else
		{
			//echo 'bad2';
			//die;
			$link 	= 'index.php?option=com_categoryblock&controller=profilelist';
			
			$msg = JText::_( 'COM_CATEGORYBLOCK_ITEM_WAS_UNABLE_TO_SAVE');
			$this->setRedirect($link, $msg, 'error');
		}
			
	}
	
	/**
	* Cancels an edit operation
	*/
	function cancelItem()
	{
		

		$model = $this->getModel('item');
		$model->checkin();

		$this->setRedirect( 'index.php?option=com_categoryblock&controller=profilelist');
	}

	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_categoryblock&controller=profilelist');
	}

	/**
	* Form for copying item(s) to a specific option
	*/
}
