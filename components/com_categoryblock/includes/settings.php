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


class CategoryBlockSettings
{
	//general
        var $categoryblockid;
        
	var $width; //module width
	
	var $columns;
	var $default_image;
	var $pagination;
	var $Language;
	
	//	link
	var $Itemid;
	var $customitemid;
	var $targetwindow;
	
	var $thelimit;
	var $skipnarticles;

	//List organization
	
	var $orderby;
	var $orderdirection;
	
	var $recursive;
        var $showfeaturedonly;
        
	var $randomize;
	
	//article processing
	var $contentsource;
	var $wordcount;
	var $charcount;
	var $cleanBraces;


	//block default layout
	
	var $blocklayout;
	
	var $showcategorytitle;  //infront or below the module
	var $showcategorydescription;  //infront or below the module
	
	var $showarticletitle; //inside of block
	
	var $showcreationdate;
	var $gotocomment;
	
	
	
	var $showreadmore;
	var $titleimagepos;
	
	
	//block styles
	
	var $padding;
	var $imagewidth;
	var $imageheight;
	var $dateformat;
	var $bgcolor;
	
	//CSS
	
	var $TitleCSSStyle;
	var $imagecssstyle;
	var $DescriptionCSSStyle;
	var $DateCSSStyle;
	
	var $categorytitlecssstyle;
	var $categorydescriptioncssstyle;
	var $readmorestyle;
	//

	//for leading article

	var $customblocklayout;
	var $orientation;
	
	var $connectwithmenu;
	var $overwritearticleid;
	var $isLeadingArticleLayout;
        
        var $allowcontentplugins;
        
        var $storethumbnails = null;
	var $thumbnailspath = null;
	
	var $firstarticle_layout;
	
	var $firstarticle_imagewidth;
	var $firstarticle_imageheight;
	var $firstarticle_readmorestyle;
	
	var $firstarticle_wordcount;
	var $firstarticle_charcount;
	var $firstarticle_descriptioncssstyle;
	
	var $firstarticle_blockcssstyle;
	
	var $firstarticle_showarticletitle;
	var $firstarticle_titlecssstyle;
	var $firstarticle_imagecssstyle;
	
	var $firstarticle_showcreationdate;
	var $firstarticle_datecssstyle;
	var $firstarticle_dateformat;
	var $firstarticle_showreadmore;
	
	var $firstarticle_gotocomment;
        
        
        

	
	
	function getSettings(&$params)
	{
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($JoomlaVersionRelease != 1.5)
		{
			$langObj=JFactory::getLanguage();
			$this->Language=$langObj->getTag();

		}
		else
		{
			$langObj=JFactory::getLanguage();
			$this->Language=$langObj->getTag();
		
		
		}
		
                
                $this->categoryblockid=(int)$params->get( 'categoryblockid' );
                
		//-----------------
		$this->customitemid=(int)$params->get( 'customitemid' );
		if($this->customitemid==0)
			$this->Itemid=JRequest::getInt('Itemid');
		else
			$this->Itemid=$this->customitemid;
		
		$this->overwritearticleid=(int)$params->get( 'overwritearticleid' );
		
		$this->columns=(int)$params->get( 'columns' );
		if($this->columns<1)
			$this->columns=1;

		
		//---------------
		$this->width=(int)$params->get( 'width' );
		if($this->width<0)
			$this->width=0;
			
		
		//---------------
		
		$this->contentsource=(int)$params->get( 'contentsource' );
		
		
		//---------------
		$this->wordcount=(int)$params->get( 'wordcount' );
			
		if($this->wordcount<0)
			$this->wordcount=0;
			
		//---------------
		$this->charcount=(int)$params->get( 'charcount' );
			
		if($this->charcount<0)
			$this->charcount=0;
		
		
                //-----------------
		$this->randomize=(int)$params->get( 'randomize' );

		//-----------------
		$this->padding=(int)$params->get( 'padding' );
		if($this->padding<0)
			$this->padding=5;
		

		//--
		//-----------------
		$this->imagewidth=(int)$params->get( 'imagewidth' );
		if($this->imagewidth<0)
			$this->imagewidth=0;
			
		//--
		//-----------------
		$this->imageheight=(int)$params->get( 'imageheight' );
		if($this->imageheight<0)
			$this->imageheight=0;
		
		//--
		//-----------------
		$this->pagination=(int)$params->get( 'pagination' );
		
		
		$this->showarticletitle=(int)$params->get( 'showarticletitle' );
		
		$this->showreadmore=(int)$params->get( 'showreadmore' );
		$this->orientation=(int)$params->get( 'orientation' );
		
		$this->default_image=$params->get( 'default_image' );
		
		
		
		$this->readmorestyle=$params->get( 'readmorestyle');
		
		
		$this->categorytitlecssstyle=$params->get( 'categorytitlecssstyle');
		$this->categorydescriptioncssstyle=$params->get( 'categorydescriptioncssstyle');
		
		
		//-----------------
		$this->bgcolor=$params->get( 'bgcolor' );

		$this->customblocklayout=$params->get( 'customblocklayout') ;

								    
		$this->TitleCSSStyle=$params->get( 'titlecssstyle') ;
		$this->imagecssstyle=$params->get( 'imagecssstyle') ;
		
												  
		$this->DescriptionCSSStyle=$params->get( 'descriptioncssstyle');
		$this->DateCSSStyle=$params->get( 'datecssstyle');
		
		
		
		$this->titleimagepos=$params->get( 'titleimagepos');
		
		$this->dateformat=$params->get( 'dateformat');
		
		
		$this->blocklayout=$params->get( 'blocklayout');
		
		$this->orderby=$params->get( 'orderby');
		if($this->orderby!='hits' and $this->orderby!='title' and $this->orderby!='medified' and $this->orderby!='created' and $this->orderby!='ordering')
			$this->orderby='title';
		
		$this->orderdirection=$params->get( 'orderdirection');
                
                
                $this->showfeaturedonly=$params->get( 'showfeaturedonly');
                
		$this->recursive=$params->get( 'recursive');
		$this->randomize=$params->get( 'randomize');
		
		$this->showcreationdate=$params->get( 'showcreationdate');

		$this->cleanBraces=$params->get( 'cleanbraces');
		
		$this->connectwithmenu=$params->get( 'connectwithmenu');
	
	
		$this->gotocomment=$params->get( 'gotocomment');
		
		
		$this->targetwindow=$params->get( 'targetwindow');
		
		if((int)$params->get( 'thelimit' )>0)
                {
                    
                    $this->thelimit=(int)$params->get( 'thelimit' );
                }
                else
                {
                    
                    $this->thelimit=(int)$params->get( 'limit' ); //for older versions (1.5, 2.5)
                }
                
                
                    
		$this->skipnarticles=(int)$params->get( 'skipnarticles' );
                
                $this->allowcontentplugins=(int)$params->get( 'allowcontentplugins' );
                
                $this->storethumbnails=(int)$params->get( 'storethumbnails' );
                $this->thumbnailspath=$params->get( 'thumbnailspath' );
                
		
		//for leading article
		
		if(JRequest::getCmd('layout')=='leadingarticle')
		{
			$this->isLeadingArticleLayout=true;
			
			$this->firstarticle_layout=(int)$params->get( 'firstarticle_layout');
			$this->firstarticle_imagewidth=(int)$params->get( 'firstarticle_imagewidth');
			$this->firstarticle_imageheight=(int)$params->get( 'firstarticle_imageheight');
			$this->firstarticle_readmorestyle=$params->get( 'firstarticle_readmorestyle');
			
			
			
			$this->firstarticle_titlecssstyle=$params->get( 'firstarticle_titlecssstyle');
	
			$this->firstarticle_wordcount=(int)$params->get( 'firstarticle_wordcount');
			
			if($this->firstarticle_wordcount<0)
				$this->firstarticle_wordcount=0;
			
			$this->firstarticle_charcount=(int)$params->get( 'firstarticle_charcount' );
			
			if($this->firstarticle_charcount<0)
				$this->firstarticle_charcount=0;
			
			
			
			$this->firstarticle_descriptioncssstyle=$params->get( 'firstarticle_descriptioncssstyle');
	
			$this->firstarticle_blockcssstyle=$params->get( 'firstarticle_blockcssstyle');
	
			$this->firstarticle_showarticletitle=$params->get( 'firstarticle_showarticletitle');
			$this->firstarticle_titlecssstyle=$params->get( 'firstarticle_titlecssstyle');
			
		
			$this->firstarticle_imagecssstyle=$params->get( 'firstarticle_imagecssstyle');
	
			$this->firstarticle_showcreationdate=$params->get( 'firstarticle_showcreationdate');
			$this->firstarticle_datecssstyle=$params->get( 'firstarticle_datecssstyle');
			$this->firstarticle_dateformat=$params->get( 'firstarticle_dateformat');
			$this->firstarticle_showreadmore=$params->get( 'firstarticle_showreadmore');
	
			$this->firstarticle_gotocomment=$params->get( 'firstarticle_gotocomment');
		}
		else
			$this->isLeadingArticleLayout=false;

	
	}
}

?>