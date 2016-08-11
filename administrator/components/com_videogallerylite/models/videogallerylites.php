<?php 
/**
 * @package  Video Gallery Lite
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website     http://www.huge-it.com/
 **/

defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class VideogalleryliteModelVideogallerylites extends JModelList {

    public function getListQuery() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__huge_it_videogallery_galleries');
        return $query;
    }

    public function getGallery() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('#__huge_it_videogallery_galleries.name, #__huge_it_videogallery_galleries.id,count(*) as count');
        $query->from(array('#__huge_it_videogallery_galleries' => '#__huge_it_videogallery_galleries', '#__huge_it_videogallery_videos' => '#__huge_it_videogallery_videos'));
        $query->where('#__huge_it_videogallery_galleries.id = videogallery_id');
        $query->group('#__huge_it_videogallery_galleries.name');
        $db->setQuery($query);
        $results = $db->loadObjectList();
        return $results;
    }

    public function getOther() {
        $db = JFactory::getDBO();
        $query2 = $db->getQuery(true);
        $query2->select('#__huge_it_videogallery_galleries.name, #__huge_it_videogallery_galleries.id,0 as count');
        $query2->from('#__huge_it_videogallery_galleries');
        $query2->where('#__huge_it_videogallery_galleries.id not in (select videogallery_id from #__huge_it_videogallery_videos)');
        $db->setQuery($query2);

        $results = $db->loadObjectList();
        return $results;
    }

}
