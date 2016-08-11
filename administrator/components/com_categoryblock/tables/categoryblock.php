<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/


// No direct access
defined('_JEXEC') or die('Restricted access');
 
// import Joomla table library
jimport('joomla.database.table');
 
/**
 * CategoryBlock Table class
 */
class CategoryBlockTableCategoryBlock extends JTable
{
        /**
         * Constructor
         *
         * @param object Database connector object
         */
		
		
       	var $id = null;
        var $profilename = null;
		var $catid = null;
		var $showtitle = null;
		var $categorytitlecssstyle = null;
		var $showcatdesc = null;
		var $categorydescriptioncssstyle = null;
		var $columns = null;
		var $padding = null;
		var $orderby = null;
		var $orderdirection = null;
		var $thelimit = null;
		var $skipnarticles = null;
		var $targetwindow = null;
		var $blocklayout = null;
		var $wordcount = null;
		var $charcount = null;
		var $imagewidth = null;
		var $imageheight = null;
		var $modulecssstyle = null;
		var $blockcssstyle = null;
		var $showarticletitle = null;
		var $titlecssstyle = null;
		var $imagecssstyle = null;
		var $descriptioncssstyle = null;
		var $showcreationdate = null;
		var $dateformat = null;
		var $datecssstyle = null;
		var $showreadmore = null;
		var $readmorestyle = null;
		var $gotocomment = null;
		var $pagination = null;
		var $customitemid = null;
		var $cleanbraces = null;
		var $default_image = null;
		var $modulewidth = null;
		var $moduleheight = null;
		var $overflow = null;
		var $showfeaturedonly = null;
		
		var $customblocklayouttop = null;
		var $customblocklayout = null;
		var $customblocklayoutbottom = null;
		var $titleimagepos = null;
		var $orientation = null;
		var $connectwithmenu = null; 
	 
		var $allowcontentplugins = null;
		var $storethumbnails = null;
		var $thumbnailspath = null;
		

        function __construct(&$db) 
        {
                parent::__construct('#__categoryblock', 'id', $db);
        }
}

?>