<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modelitem');

class VideogalleryliteModelVideogallerylite extends JModelItem
{
	public function getTable($type = 'Videogallerylite', $prefix = 'VideogalleryliteTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getItem($id = null)
	{
		
		$id = (!empty($id)) ? $id : (int) $this->getState('message.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$id]))
		{
			
			$table = $this->getTable();

			
			$table->load($id);

			
			$this->_item[$id] = $table->name;
		}

		return $this->_item[$id];
	}

        protected function populateState()
	{
		$app = JFactory::getApplication();

		
		$id = $app->input->getInt('id', 0);

		
		$this->setState('message.id', $id);

		parent::populateState();
	}
        
        public function getGallerys(){
            $db = JFactory::getDBO();
            $id = (!empty($id)) ? $id : (int) $this->getState('message.id');
            $id = $this->setState('message.id', $id);
            $query = $db->getQuery(true);
            $query->SELECT('*');
            $query-> FROM ('#__huge_it_videogallery_galleries');
            $query-> where('id='.$id);
            $query->order('ordering ASC',$id);
            $db->setQuery($query);
            $results = $db->loadObjectList();
       return $results;
        }
       public function getGalleryParams(){
           $db = JFactory::getDBO();
           $id = (!empty($id)) ? $id : (int) $this->getState('message.id');
           $id = $this->setState('message.id', $id);
           $query = $db->getQuery(true);
           $query->select('*,#__huge_it_videogallery_videos.name as imgname');
           $query->from('#__huge_it_videogallery_galleries,#__huge_it_videogallery_videos');
           $query->where('#__huge_it_videogallery_galleries.id ='.$id)->where('#__huge_it_videogallery_galleries.id = #__huge_it_videogallery_videos.videogallery_id');
           $db->setQuery($query);
           $results = $db->loadObjectList();
           return $results;
           }
          
		  public function getVideogalleryId(){
        $db = JFactory::getDBO();
        $id = (!empty($id)) ? $id : (int) $this->getState('message.id');
        $id = $this->setState('message.id', $id);
        return $id;
        }
		  
		  
          public function  getGeneralParams(){
           $db = JFactory::getDBO();
           $query = $db->getQuery(true);
           $query ->select('*');
           $query -> from('#__huge_it_videogallery_params');
           $db->setQuery($query);
           $results = $db->loadObjectList();
            return $results;
          }
     
}