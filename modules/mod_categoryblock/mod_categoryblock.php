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

	
require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'modulebox.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'render.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'settings.php');

$cbm=new CategoryBlockMisc;
$modulebox=new CategoryBlockModuleBox;
$layoutsettings=new CategoryBlockSettings();

$categoryblockid=intval($params->get( 'categoryblockid' ));
$catid=intval($params->get( 'catid' ));

$ShowCategoryBlockModule=true;

if($categoryblockid==0)
{
	echo '<p style="background-color:white;color:red;">Category Block profile not selected.</p>';
	$ShowCategoryBlockModule=false;
}

if($catid==-1)
{
	//Smart Category Detection. Detect Category by Current article if possible. Usefull for "Related Articles"
	$cid=$modulebox->getSmartCategory();
	if($cid!=-1)
		$catid=$cid;
	else
		$ShowCategoryBlockModule=false;
}


if($catid==0)
{
	echo '<p style="background-color:white;color:red;">Category not selected.</p>';
	$ShowCategoryBlockModule=false;
}


//-------------------------------------------------------------------------------

if($ShowCategoryBlockModule)
{

	$cbparams=$cbm->getSavedCategoryBlockParams($categoryblockid,$catid,intval($params->get( 'customitemid' ))); //saved profile

	$layoutsettings->getSettings($cbparams);
	$cbm->layoutsettings = $layoutsettings;

	if($cbparams->get( 'showtitle' ) or $cbparams->get( 'showcatdesc' ))
	{
		$categorytitle='';
		$categorycatdesc='';
			
		$result='';
		if($cbm->getCategoryTitle($catid,$categorytitle,$categorycatdesc))
		{
			if($cbparams->get( 'showtitle' ))
			{
				if($layoutsettings->categorytitlecssstyle!='')
					$result.='<div'.$cbm->ClassStyleOption($layoutsettings->categorytitlecssstyle).'>'.$categorytitle.'</div>';
				else
					$result.='<h1>'.$categorytitle.'</h1>';
			}
		
			if($cbparams->get( 'showcatdesc' ))
			{
				$result.='<div'.$cbm->ClassStyleOption($layoutsettings->categorydescriptioncssstyle).'>'.$categorycatdesc.'</div>';
			}
			
			echo $result;
		}
			
			
		
		
	}
	

	//Where
	$cat_list=array();
	$where_query=$cbm->getWhere($cbparams,$catid,$cat_list);
				
	$rows=$cbm->getArticles16($where_query);
	if($cbm->layoutsettings->recursive==2 or $cbm->layoutsettings->recursive==3)
	{
		//Resort article by category group
		$rows=$cbm->reorderRecsByCategoryGroup($rows,$cat_list);
	}
				

	if(count($rows)>0)
		echo $modulebox->render($cbparams,$cbm,$rows,false,'mod'.$module->id);
		
		

}


?>