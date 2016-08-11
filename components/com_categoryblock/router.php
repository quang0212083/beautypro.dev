<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');
function CategoryBlockBuildRoute(&$query) {



       $segments = array();
       if(isset($query['view']))
       {
			  if($query['view']=='article' or $query['view']=='')
			  {
					 
					 $segments[] = '';
					 unset( $query['view'] );
					 
					 if(isset($query['id']))
					 {
							//find article's alias
							$articleid=(int)$query['id'];
							$db = JFactory::getDBO();
		
							$db->setQuery('SELECT alias FROM #__content WHERE id='.$articleid.' LIMIT 1');
							if (!$db->query())    die ('cb router.php 1 err:'. $db->stderr());
							$rows = $db->loadObjectList();
			  		
							if(count($rows)==0)
								   return array();
		
					 		$row=$rows[0];
							
						
							$segments[] = $row->alias;
							unset( $query['id'] );
					 }			 
					 
			  }
			  else
			  {
                
					  if(isset($query['view']))
						 unset( $query['view'] );
					 
					  if(isset($query['catid']))
						 unset( $query['catid'] );
			  }
       }
	   
	   

       return $segments;


}
function CategoryBlockParseRoute($segments) {

$vars = array();
$vars['view'] = 'article';


if(isset($segments[0]))
{
	   $alias=str_replace(':','-',$segments[0]);
	   
	   //find article's id
							
	   
	   $db = JFactory::getDBO();
	   	
	   $db->setQuery('SELECT id FROM #__content WHERE alias="'.$alias.'" LIMIT 1');
	   if (!$db->query())    die ('cb router.php 2 err:'. $db->stderr());
	   $rows = $db->loadObjectList();
			  		
	   if(count($rows)==0)
			  $vars['id'] = 0;
	   else
	   {
			  
			  $row=$rows[0];
			  $vars['id'] = $row->id;
	   }
	   
}
else
{
	   echo '<p style="background-color:white;color:red;">Article Not Set</p>';
	   die;
}

return $vars;
}
?>