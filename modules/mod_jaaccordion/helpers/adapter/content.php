<?php
/**
 * ------------------------------------------------------------------------
 * JA Accordion Module for J25 & J34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 


require_once JPATH_SITE . '/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');
if (version_compare(JVERSION, '3.0', 'ge'))
{
	JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
	//$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
}
else if (version_compare(JVERSION, '2.5', 'ge'))
{
	JModel::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
   	//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
}
else
{
	JModel::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
	//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
}

/**
 * Get Articles Adapter class
 * @param object $params
 * @author JA	
 **/
class articlesAdapter
{


    /**
     * Get Articles from article ids or categories
     * @param object $params
     * @param array  $artIds  array of articles id
     * @param array  $catIds  array of categories id
     * @return array articles object	
     **/
    public static function getArticles($artIds = array(), $catIds = array(), &$params)
    {
        // Get the dbo
        $db = JFactory::getDbo();
        
        // Get an instance of the generic articles model
        
	    if (version_compare(JVERSION, '3.0', 'ge'))
		{
			$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			//$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
		}
		else if (version_compare(JVERSION, '2.5', 'ge'))
		{
		   	$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
		}
		else
		{
			$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
		} 
        // Set application parameters in model
        $app = JFactory::getApplication();
        $appParams = $app->getParams();
        $model->setState('params', $appParams);
        $model->setState('list.select', 'a.id, a.title, a.alias, a.introtext, a.fulltext, a.images, ' . 'a.checked_out, a.checked_out_time, ' . 'a.catid, a.created, a.created_by, a.created_by_alias, ' . // use created if modified is 0
        'CASE WHEN a.modified = 0 THEN a.created ELSE a.modified END as modified, ' . 'a.modified_by,' . // use created if publish_up is 0
        'CASE WHEN a.publish_up = 0 THEN a.created ELSE a.publish_up END as publish_up, ' . 'a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' . 'a.hits, a.xreference, a.featured ');
        
        // Set the filters based on the module params
        $model->setState('list.start', 0);
        $model->setState('list.limit', (int) $params->get('count', 5));
		
        if ($params->get('count', 5)==0) {
			$model->setState('filter.published', 10);	
		}
		else {
			$model->setState('filter.published', 1);
        }
        
        // Access filter
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $model->setState('filter.access', $access);
        
        // Articles group filter
        if (!empty($artIds)) {
            $model->setState('filter.article_id', $artIds);
        }
        // Category filter
        if (!empty($catIds)) {
	        if (version_compare(JVERSION, '3.0', 'ge'))
			{
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				
				//$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
			}
			else if (version_compare(JVERSION, '2.5', 'ge'))
			{
			   	$categories = JModel::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
			}
			else
			{
				$categories = JModel::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				//$model = JModel::getInstance('Article', 'ContentModel', array('ignore_request' => true));
			}
            $categories->setState('params', $appParams);
            $levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
            $categories->setState('filter.get_children', 9999);
            $categories->setState('filter.published', 1);
            $categories->setState('filter.access', $access);
            $additional_catids = array();
            
            foreach ($catIds as $catid) {
                $categories->setState('filter.parentId', $catid);
                $recursive = true;
                $items = $categories->getItems($recursive);
                
                if ($items) {
                    foreach ($items as $category) {
                        $condition = (($category->level - $categories->getParent()->level) <= $levels);
                        if ($condition) {
                            $additional_catids[] = $category->id;
                        }
                    
                    }
                }
            }
            
            $catIds = array_unique(array_merge($catIds, $additional_catids));
            $model->setState('filter.category_id', $catIds);
        }
        
        // Filter by language
        $model->setState('filter.language', $app->getLanguageFilter());
        
		// User filter
		$userId = JFactory::getUser()->get('id');
		switch ($params->get('user_id'))
		{
			case 'by_me':
				$model->setState('filter.author_id', (int) $userId);
				break;
			case 'not_me':
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;

			case '0':
				break;

			default:
				$model->setState('filter.author_id', (int) $params->get('user_id'));
				break;
		}
        //  Featured switch
        if($params->get('type') == 'categoryIDs'){
	        switch ($params->get('show_featured')) {
	            case '1':
	                $model->setState('filter.featured', 'only');
	                break;
	            case '0':
	                $model->setState('filter.featured', 'hide');
	                break;
	            default:
	                $model->setState('filter.featured', 'show');
	                break;
	        }
        }
        
        // Set ordering
        $order_map = array('m_dsc' => 'a.modified DESC, a.created', 'mc_dsc' => 'CASE WHEN (a.modified = ' . $db->quote($db->getNullDate()) . ') THEN a.created ELSE a.modified END', 'c_dsc' => 'a.created', 'p_dsc' => 'a.publish_up', 'title' => 'a.title');
        $ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.publish_up');
        $dir = $params->get('direction', 'DESC');

        $model->setState('list.ordering', $ordering);
        $model->setState('list.direction', $dir);
        
        $items = $model->getItems();
        /*
         * Get params component content
         * */
        $params_content = $app->getParams('com_content');
        
        foreach ($items as &$item) {
        	$images = json_decode($item->images);
            $item->slug = $item->id . ':' . $item->alias;
            $item->catslug = $item->catid . ':' . $item->category_alias;
            
            if ($access || in_array($item->access, $authorised)) {
                // We know that user has the privilege to view the article
                $item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
            } else {
                $item->link = JRoute::_('index.php?option=com_users&view=login');
            }
            if ($params->get('view') == 'fulltext') {
            	$item->content ='';
            	
	            if (isset($images->image_intro) and !empty($images->image_intro)) :
				$imgfloat = (empty($images->float_intro)) ? $params_content->get('float_intro') : $images->float_intro;
				$item->content .='<div class="img-intro-'.htmlspecialchars($imgfloat).'">';
				$caption = '';
				if ($images->image_intro_caption):
						$caption = 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
				endif;
				$item->content .='<img '.$caption.' src="'.htmlspecialchars($images->image_intro).'" alt="'.htmlspecialchars($images->image_intro_alt).'"/>';
				$item->content .= '</div>';
				endif;
            	
                $item->content .= $item->introtext.$item->fulltext;
                
               	if (isset($images->image_fulltext) and !empty($images->image_fulltext)) :
 				$imgfloat = (empty($images->float_fulltext)) ? $params_content->get('float_fulltext') : $images->float_fulltext;
				$item->content.= '<div class="img-fulltext-'.htmlspecialchars($imgfloat).'">';
				$item->content.= '<img';
					if ($images->image_fulltext_caption):
							$item->content.= 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
					endif; 
				$item->content.= 'src="'.htmlspecialchars($images->image_fulltext).'" alt="'.htmlspecialchars($images->image_fulltext_alt).'"/>';
				$item->content.= '</div>';
				endif;
            } else {
            	$item->content ='';
            	/*
            	 * Add images intro
            	 * */
	            if (isset($images->image_intro) and !empty($images->image_intro)) :
				$imgfloat = (empty($images->float_intro)) ? $params_content->get('float_intro') : $images->float_intro;
				$item->content .='<div class="img-intro-'.htmlspecialchars($imgfloat).'">';
				$caption = '';
				if ($images->image_intro_caption):
					$caption = 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
				endif;
				$item->content .='<img '.$caption.' src="'.htmlspecialchars($images->image_intro).'" alt="'.htmlspecialchars($images->image_intro_alt).'"/>';
				$item->content .= '</div>';
				endif;
				
                $item->content .= $item->introtext;
                /*
                 * Add images fulltext
                 * */
                if (isset($images->image_fulltext) and !empty($images->image_fulltext)) :
 				$imgfloat = (empty($images->float_fulltext)) ? $params_content->get('float_fulltext') : $images->float_fulltext;
				$item->content.= '<div class="img-fulltext-'.htmlspecialchars($imgfloat).'">';
				$caption = '';
				if ($images->image_fulltext_caption):
					$caption = 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
				endif;
				$item->content.= '<img '.$caption.' src="'.htmlspecialchars($images->image_fulltext).'" alt="'.htmlspecialchars($images->image_fulltext_alt).'"/>';
				$item->content.= '</div>';
				endif;
            }
        }
        
        return $items;
    }
}
?>