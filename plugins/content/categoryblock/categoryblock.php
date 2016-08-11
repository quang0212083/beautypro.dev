<?php
/**
 * CategoryBlock Joomla! 3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

defined('_JEXEC') or die('Restricted access');

if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);


jimport('joomla.plugin.plugin');


class plgContentCategoryBlock extends JPlugin
{

	public function onContentPrepare($context, &$article, &$params, $limitstart=0)
	{
		
		$this->plgCategoryBlockByID($article->text, $params);
		$this->plgCategoryBlockByName($article->text, $params);
		
	}
	
	function strip_html_tags_textarea( $text )
	{
	    $text = preg_replace(
        array(
          // Remove invisible content
            '@<textarea[^>]*?>.*?</textarea>@siu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',"$0", "$0", "$0", "$0", "$0", "$0","$0", "$0",), $text );
     
		return $text ;
	}
	
	function plgCategoryBlockByName(&$text_original, &$params)
	{
		//Syntax {categoryblock=profile name, category name, custom itemid}
		
		$text=$this->strip_html_tags_textarea($text_original);
		
		$options=array();
		$fList=$this->getListToReplace('categoryblock',$options,$text);
	
		for($i=0; $i<count($fList);$i++)
		{
			$pair=explode(',',$options[$i]);
			if(count($pair)<1)
			{
				echo '<p style="background-color:white;color:red;">Category Block profile not selected.</p>';	
			}
			elseif(count($pair)<2)
			{
				echo '<p style="background-color:white;color:red;">Category Block category not selected.</p>';	
			}
			else
			{
				$profile_name=$pair[0];
				$category_name=$pair[1];
				
				if(isset($pair[2]))
					$customitemid=intval($pair[2]);
				else
					$customitemid=0;
					
				$categoryblockid=$this->getProfileIDByName($profile_name);
				$categoryid=$this->getCategoryIDByName($category_name);

				if($categoryblockid!=0)
				{
					if($categoryid!=0)
						$replaceWith=$this->getCategoryBlock($categoryblockid,$categoryid,$customitemid);
					else
						$replaceWith ='Category "'.$category_name.'"  not found';
				}
				else
					$replaceWith ='Category Block profile "'.$profile_name.'"  not found';
				
				$text_original=str_replace($fList[$i],$replaceWith,$text_original);
			}
		}
	
		return count($fList);
	}
	
	function plgCategoryBlockByID(&$text_original, &$params)
	{
		//Syntax {categoryblockid=profile id, category id, custom itemid}
		
		$text=$this->strip_html_tags_textarea($text_original);
		
		$options=array();
		$fList=$this->getListToReplace('categoryblockid',$options,$text);
	
		for($i=0; $i<count($fList);$i++)
		{
			$pair=explode(',',$options[$i]);
			if(count($pair)<1)
			{
				echo '<p style="background-color:white;color:red;">Category Block profile not selected.</p>';	
			}
			elseif(count($pair)<2)
			{
				echo '<p style="background-color:white;color:red;">Category Block category not selected.</p>';	
			}
			else
			{
				$categoryblockid=intval($pair[0]);
				$categoryid=intval($pair[1]);
				if(isset($pair[2]))
					$customitemid=intval($pair[2]);
				else
					$customitemid=0;
				
				$replaceWith=$this->getCategoryBlock($categoryblockid,$categoryid,$customitemid);
			
				$text_original=str_replace($fList[$i],$replaceWith,$text_original);
			}
		}
	
		return count($fList);
	}

	function getProfileIDByName($profilename)
	{
		$db = JFactory::getDBO();
		$profilename=trim($profilename);
		
		$query = 'SELECT id FROM #__categoryblock WHERE profilename="'.$profilename.'" LIMIT 1';
			
		$db->setQuery($query);
		if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
				
		if(count($rows)==0)
			return 0;
		
		return $rows[0]->id;
		
	}
	
	function getCategoryIDByName($categoryname)
	{
                $db = JFactory::getDBO();
                $query = $db->getQuery(true);
                $query->select('`id`');
                $query->from('#__categories');
                $query->where('`extension`="com_content"');
		$query->where('`alias`="'.$categoryname.'"');
		$query->limit(1);
                
                $db->setQuery((string)$query);
                if (!$db->query())    die ( $db->stderr());
		
		$rows = $db->loadObjectList();
				
		if(count($rows)==0)
			return 0;
		
		return $rows[0]->id;
		
	}

	function getCategoryBlock($categoryblockid,$catid,$customitemid)
	{
		require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'modulebox.php');
		require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'render.php');
		require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'settings.php');
		
		$cbm=new CategoryBlockMisc;
		
		$params=$cbm->getSavedCategoryBlockParams($categoryblockid,$catid,$customitemid);
		
		$layoutsettings=new CategoryBlockSettings();
		$layoutsettings->getSettings($params);
		
		
		$cbm->layoutsettings = $layoutsettings;

		$modulebox=new CategoryBlockModuleBox;

		
		//Where
		$cat_list=array();
		$where_query=$cbm->getWhere($cbparams,$catid,$cat_list);

		//Get articles
		$rows=$cbm->getArticles16($where_query);
		if($cbm->layoutsettings->recursive==2 or $cbm->layoutsettings->recursive==3)
		{
			//Resort article by category group
			$rows=$cbm->reorderRecsByCategoryGroup($rows,$cat_list);
		}
								
		if(count($rows)>0)
			return $modulebox->render($params,$cbm,$rows);
		else
			return '';
		
	}



	function getListToReplace($par,&$options,&$text)
	{
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($text))
				break;
		
			$ps=strpos($text, '{'.$par.'=', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($text))
				break;
		
		$pe=strpos($text, '}', $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($text,$ps,$pe-$ps+1);

			$options[]=substr($text,$ps+$l,$pe-$ps-$l);
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		return $fList;
	}
}



?>