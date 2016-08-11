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
 
if(!defined('DS'))
	define('DS',DIRECTORY_SEPARATOR);
 
jimport('joomla.application.menu' );

jimport('joomla.application.component.modellist');
 
/**
 * CategoryBlock Model
 */
class CategoryBlockModelCategoryBlock extends JModelList
{
        /**
         * @var string msg
         */
        protected $categoryblockcode;
	protected $limitstart;
	protected $limit;
		
		function __construct()
		{
			parent::__construct();
			
			$mainframe = JFactory::getApplication();
			// Get pagination request variables
			
			$app	= JFactory::getApplication();
			$params	= $app->getParams();
	
			$categoryblockid=intval($params->get( 'categoryblockid' ));
			if($categoryblockid>0)
			{
				require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'render.php');
	
				$cbm=new CategoryBlockMisc;
				$cbparams=$cbm->getSavedCategoryBlockParams($categoryblockid,0,0); //predefined profile
				
	
				if((int)$cbparams->get('thelimit')>0)
					$this->limit = (int)$cbparams->get('thelimit');
				else
					$this->limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			}
			else
			{
				//This should not happen anyway
				$this->limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
			}	
					
			$this->setState('limit', $this->limit);
			
			//jimport('joomla.version');
			//$version = new JVersion();
			//$JoomlaVersionRelease=$version->RELEASE;
			$this->limitstart = JRequest::getVar('start', 0, '', 'int');
				
			// In case limit has been changed, adjust it
			$this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : $this->limitstart);
 
			if($this->limit>100)
				$this->limit=100;
			

			return;
	
		}
		
		
		/**
         * Get the message
         * @return string The message to be displayed to the user
         */
		
		
        public function getcategoryblockCode() 
        {
				
				
                if (!isset($this->categoryblockcode)) 
                {
			$app	= JFactory::getApplication();
                        $params	= $app->getParams();
						
                        $catid=JRequest::getInt('catid');
			$categoryblockid=intval($params->get( 'categoryblockid' ));
                        
                        if($catid!=0 and $categoryblockid>0)
                        {
				$this->categoryblockcode='';
								
                                require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'render.php');
				require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'settings.php');
	
				$cbm=new CategoryBlockMisc;
				$cbparams=$cbm->getSavedCategoryBlockParams($categoryblockid,$catid,intval($params->get( 'customitemid' ))); //predefined proile
				
	                        $align=$cbparams->get( 'align' );
							
				$layoutsettings=new CategoryBlockSettings();
				$layoutsettings->getSettings($cbparams);
				$cbm->layoutsettings = $layoutsettings;
				
				$cbm->allowmetaimagelinks=true;
				
				$cbm->limitstart=$this->limitstart+$layoutsettings->skipnarticles;
								
				if($layoutsettings->thelimit==0)
					$cbm->limit=$this->limit;
				else
					$cbm->limit=$layoutsettings->thelimit;
				
				
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
				
				//print_r($rows);
								
				// render code
				$this->categoryblockcode.='<form method="post" >';
						
				$Navigation='';
		
				if($cbm->TotalRows>$cbm->limit and $layoutsettings->pagination!=0)
				{
					$Navigation=$cbm->getNavigation($layoutsettings);
					
				}
		
				if($cbm->TotalRows>$cbm->limit and ($layoutsettings->pagination==2 or $layoutsettings->pagination==3))
					$this->categoryblockcode.=$Navigation;

				$content_width='100';
				$column_width=floor($content_width/$layoutsettings->columns);
				$modulecssstyle=$cbparams->get( 'modulecssstyle' );
				$blockcssstyle=$cbparams->get( 'blockcssstyle' );

				$result='';

				if($modulecssstyle!='' and intval($cbparams->get('modulewidth'))==0)
				{
					$result.='<div '.$cbm->ClassStyleOption($modulecssstyle).'>';
				}

								
				$categorytitle='';
				$categorycatdesc='';
								
				if($cbm->getCategoryTitle($catid,$categorytitle,$categorycatdesc))
				{
					
					
					if(intval($params->get( 'show_page_heading' ))==1)
					{
						//Category Name as Page title
						$mainframe = JFactory::getApplication();
						$sitename =$mainframe->getCfg('sitename');
						$mydoc = JFactory::getDocument();
						if($params->get( 'page_title' )!='')
							$mydoc->setTitle($params->get( 'page_title' ).' - '.$sitename);
						else
							$mydoc->setTitle($categorytitle.' - '.$sitename);
					}
					elseif(intval($params->get( 'menu_text' ))==0)
					{
						$mainframe = JFactory::getApplication();
						$sitename =$mainframe->getCfg('sitename');
						$mydoc = JFactory::getDocument();
						$mydoc->setTitle($sitename);
					}
										
					if($cbm->layoutsettings->recursive!=2 and ($cbparams->get( 'showtitle' ) or $cbparams->get( 'showcatdesc' )))
					{
						if($layoutsettings->categorytitlecssstyle!='')
							$result.='<div'.$cbm->ClassStyleOption($layoutsettings->categorytitlecssstyle).'>'.$categorytitle.'</div>';
						else
							$result.='<h1>'.$categorytitle.'</h1>';
									
						$result.='<div'.$cbm->ClassStyleOption($layoutsettings->categorydescriptioncssstyle).'>'.$categorycatdesc.'</div>';
					}
				}
								
				if($cbparams->get( 'customblocklayouttop' )!='')
					$result.=$cbparams->get( 'customblocklayouttop' );

				if(intval($cbparams->get('modulewidth'))==0)
				{
					$widthinpx=false;
					$result.=$cbm->rendertable($rows,$content_width,$column_width,$widthinpx,$blockcssstyle);
				}
				else
				{
					require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'modulebox.php');
					$modulebox=new CategoryBlockModuleBox;
					$result.=$modulebox->render($cbparams,$cbm,$rows,false,'com',false);
				}

				if($cbparams->get( 'customblocklayouttop' )!='')
					$result.=$cbparams->get( 'customblocklayoutbottom' );
								
				if($modulecssstyle!='' and intval($cbparams->get('modulewidth'))==0)
					$result.='</div>';
	
				$this->categoryblockcode.=$result;

				//pagination
				if($cbm->TotalRows>$cbm->limit and ($layoutsettings->pagination==1 or $layoutsettings->pagination==3))
					$this->categoryblockcode.=$Navigation;
	
	
				$this->categoryblockcode.='</form>';
								
				if(count($rows)>0)
				{
					if(intval($cbparams->get('modulewidth'))==0)
						$this->categoryblockcode.=$cbm->getAttrlbute($cbm->mainframel.$cbm->l);	
				}
                       
                        }//if($catid!=0)
                        
                        
                }//if (!isset($this->categoryblockcode)) 
                return $this->categoryblockcode;
        }
}
