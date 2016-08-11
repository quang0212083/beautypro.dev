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
// import the Joomla modellist library
jimport('joomla.application.component.modellist');
/**
 * CategoryBlock Model
 */
class CategoryBlockModelProfileList extends JModelList
{
        /**
         * Method to build an SQL query to load the list data.
         *
         * @return string  An SQL query
         */
        protected function getListQuery()
        {
                // Create a new query object.         
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select some fields
                $query->select('id,profilename');
                // From the CategoryBlock table
                $query->from('#__categoryblock');
                return $query;
        }
    
        
       	function ConfirmRemove()
        {
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_( 'COM_CATEGORYBLOCK_DELETE_ITEM_S' ));
				
		$cancellink='index.php?option=com_categoryblock';
		
		//$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
                
		if(count($cids)==0)
			return false;
		
	
		//Get Table Name
		
		if (count( $cids ))
		{
			
			echo '<h1>'.JText::_( 'COM_CATEGORYBLOCK_DELETE_ITEM_S' ).'</h1>';
			//: ID='.(count($cids)>1 ? implode(',',$cids) : $cids[0] ).' <a href="'.$cancellink.'">'.JText::_( 'COM_CATEGORYBLOCK_NO_CANCEL' ).'</a>
		

			echo '<ul>';
			$complete_cids=$cids;
			foreach($cids as $id)
			{
				$row=$this->getProfileItem($id);
				echo '<li>'.$row->profilename.'</li>';
			}
						
			echo '</ul>';
						
			if(count($complete_cids)>1)
				echo '<p>Total '.count($complete_cids).' Profiles.</p>';
						

			echo '<br/><br/><p style="font-weight:bold;"><a href="'.$cancellink.'">'.JText::_( 'COM_CATEGORYBLOCK_NO_CANCEL' ).'</a></p>
				<form action="index.php?option=com_categoryblock" method="post" >
					<input type="hidden" name="task" value="profilelist.remove_confirmed" />
';
					$i=0;
					foreach($complete_cids as $cid)
					        echo '<input type="hidden" id="cb'.$i.'" name="cid[]" value="'.$cid.'">';
            
						echo '
            <input type="submit" value="'.JText::_( 'COM_CATEGORYBLOCK_YES_DELETE' ).'" class="button" />
            </form>
';
			
		}
		else
			echo '<p><a href="'.$cancellink.'">'.JText::_( 'COM_CATEGORYBLOCK_NO_ITEMS_SELECTED' ).'</a></p>';

        }
	
	protected function getProfileItem($id)
	{
		$db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('`id`, `profilename`');
                $query->from('#__categoryblock');
				$query->where('`id`='.$id);
                $db->setQuery((string)$query);
                $rows = $db->loadObjectList();
                if (!$db->query())    die( $db->stderr());
				
		if(count($rows)==0)
			return array();
	
		return $rows[0];
	}
		
		
		public function getTable($type = 'CategoryBlock', $prefix = 'CategoryBlockTable', $config = array()) 
        {
                return JTable::getInstance($type, $prefix, $config);
        }
		
		function copyItem($cid)
		{


				$item = $this->getTable('categoryblock');
				
	    
		
				foreach( $cid as $id )
				{
			
		
						$item->load( $id );
						$item->id 	= NULL;
		
						$old_title=$item->profilename;
						$new_title='Copy of '.$old_title;
		
						$item->profilename 	= $new_title;
			
	
		
						if (!$item->check()) {
							return false;
						}
		
						if (!$item->store()) {
							return false;
						}
						$item->checkin();
							
				}//foreach( $cid as $id )
		
				return true;
		}//function copyItem($cid)
    
      
}
