<?php 
/**
 * @package  Video Gallery Lite
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     http://www.huge-it.com/
 **/

defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');
jimport('joomla.application.component.helper');

class VideogalleryliteModelVideo extends JModelAdmin {

    public function getTable($type = 'Video', $prefix = 'VideoTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm(
                $this->option . '.video', 'video', array('control' => 'jform', 'load_data' => $loadData)
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState($this->option . '.editvideo.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    
    private function getNumber($videogallery_id) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('min(ordering) as maximum');
        $query->from('#__huge_it_videogallery_videos');
        $query->where('videogallery_id=' . $videogallery_id);
        $db->setQuery($query);
        $results = $db->loadResult();
        return $results;
        
    }
    
    function save($data) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $data = JRequest::get('post');
        $url = $data['huge_it_add_video_input'] ? $data['huge_it_add_video_input'] : '';
        $show_title = $data['show_title'] ? $data['show_title'] : ''; 
        $show_description1 = $data['show_description'] ? $data['show_description'] : '';
		
		
    	$show_description = preg_quote($show_description1,'"');
	
        $show_url = $data['show_url'] ? $data['show_url'] : ''; 
        
        $galleryId = JRequest::getVar('id');
        $ordering = $this->getNumber($galleryId)- 1;
        
        if(strstr($url, 'youtu.be/') === true  || strstr($url, 'youtube.com/') ||  strstr($url, 'vimeo.com/')){
        $query->insert('#__huge_it_videogallery_videos', 'id')
                ->set('videogallery_id = "' . $galleryId . '"')
                ->set('sl_url = "' . $show_url . '"')
                ->set('name = "' . $show_title . '"')
                ->set('description= "' . $show_description . '"')
                ->set('image_url= "' . $url . '"')
                ->set('sl_type= "video"')
                ->set('ordering="'.$ordering.'"');
        $db->setQuery($query);
        $db->execute();
        return ;
    }else return;
    }
}
