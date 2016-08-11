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

require_once(JPATH_SITE.DS.'components'.DS.'com_categoryblock'.DS.'includes'.DS.'render.php');

class CategoryBlockModuleBox
{
	function render(&$params,&$cbm,$rows,$horizontalfill=true,$moduleid='com',$showtopandbottom=true)
	{
		
		$result='';
		
		if($showtopandbottom)
		{
			if($params->get( 'customblocklayouttop' )!='')
				$result.=$params->get( 'customblocklayouttop' );
		}
		
		if($cbm->layoutsettings->orientation==0)
			$result.=$this->render_vertical($params,$cbm,$rows,$horizontalfill,$moduleid); // == 0
		else
			$result.=$this->render_horizontal($params,$cbm,$rows,$horizontalfill,$moduleid); // == 1
		
		if($showtopandbottom)
		{
			if($params->get( 'customblocklayoutbottom' )!='')
				$result.=$params->get( 'customblocklayoutbottom' );
		}
			
		return $result;
	}

	function render_vertical(&$params,&$cbm,$rows,$horizontalfill=true,$moduleid='com')
	{
	
		$result='
<!--  CB:Orientation - Vertical -->
';
		
		$document = JFactory::getDocument();
		$document->addScript( 'modules/mod_categoryblock/categoryblock.js' );

		//$pagination=$cbm->getPagination();
				


		$modulecssstyle=$params->get( 'modulecssstyle' );
		$blockcssstyle=$params->get( 'blockcssstyle' );
		
		if($horizontalfill or intval($params->get( 'modulewidth' ))==0)
			$content_width_real='100%';
		else
			$content_width_real=intval($params->get( 'modulewidth' ));
			
	
		$overflow=$params->get( 'overflow' );
		$widthinpx=true;


		$content_width=$content_width_real;

		if(strpos($content_width,'%')===false)
			$widthinpx=true;
		else
		{
			$widthinpx=false;
		
			$content_width_real=(int)str_replace('%','',$content_width_real);
	
		}	

		if($overflow=='scroll' or $overflow=='scroll-x' or $overflow=='scroll-y')
			$content_width=$content_width_real-17;
		else
		{
			$content_width=$content_width_real;
	
	
		}



		$content_height=(int)$params->get( 'moduleheight' );

		if($content_width<100)
			$content_width=100;
		




		$column_width=floor($content_width/$cbm->layoutsettings->columns);



		if($content_height!=0)
		{
			if($overflow=='autoflow-slow' or $overflow=='autoflow-normal' or $overflow=='autoflow-fast')
			{
				
				$result.= '<!-- Category Block - Auto Flow -->';
				
				$result.= '
				
				';
			}
			else
			{
				if($overflow=='scroll')
					$overflowcase='overflow:scroll;';
				elseif($overflow=='scroll-x')
					$overflowcase='overflow: -moz-scrollbars-horizontal;overflow-x: auto;overflow-y: hidden;';
				elseif($overflow=='scroll-y')
					$overflowcase='overflow: -moz-scrollbars-vertical;overflow-x: hidden;overflow-y: auto;';
				elseif($overflow=='hidden')
					$overflowcase='overflow: hidden;';
			
				$result.= '<!-- Category Block - Scroll -->';
			
				if($widthinpx)
					$mss=$cbm->ClassStyleOption($modulecssstyle,'width:'.$content_width_real.'px;height:'.$content_height.'px;'.$overflowcase.';');
				else
					$mss=$cbm->ClassStyleOption($modulecssstyle,'width:'.$content_width_real.';height:'.$content_height.'px;'.$overflowcase.';');

				$result.= '<div'.$mss.'>';
				
				
			}//			if($overflow=='autoflow-slow' or $overflow=='autoflow-normal' or $overflow=='autoflow-fast')
	
		}
		else
		{
			if($widthinpx)
				$mss=$cbm->ClassStyleOption($modulecssstyle,'width:'.$content_width_real.'px;');
			else
				$mss=$cbm->ClassStyleOption($modulecssstyle,'width:'.$content_width_real.';');

			$result.= '<div'.$mss.'>';
		}	
	

			$table_tag_string='<table style="border:none;width:'.$content_width.($widthinpx ? '' : '%').';padding:'.$cbm->layoutsettings->padding.'px;" cellspacing="0" >';

			$resultinside=$cbm->rendertable($rows,$content_width,$column_width,$widthinpx,$blockcssstyle,true,$table_tag_string);
	  
	

			if($overflow=='autoflow-slow' or $overflow=='autoflow-normal' or $overflow=='autoflow-fast')
			{
				if($overflow=='autoflow-slow')
					$speed=1;
			
				if($overflow=='autoflow-normal')
					$speed=2;
			
				if($overflow=='autoflow-fast')
					$speed=5;
			
				if($widthinpx)
				{
					$mss=$cbm->ClassStyleOption($modulecssstyle,'position:relative;width:'.$content_width_real.'px;overflow:hidden;height:'.$content_height.'px;');
					
					$result.= '<div'.$mss.'	onmouseover="MODCB'.$moduleid.'_Stop();" onmouseout="MODCB'.$moduleid.'_Start();" >';

					$result.= '
		<div id="MODCategoryBlock'.$moduleid.'_Div1" style="position:absolute;display:block;top:0px;width:'.$content_width_real.'px;">'.$resultinside.'</div>
		<div id="MODCategoryBlock'.$moduleid.'_Div2" style="position:absolute;display:none;top:0px;width:'.$content_width_real.'px;">'.$resultinside.'</div>';
				}
				else
				{
					$mss=$cbm->ClassStyleOption($modulecssstyle,'position: relative;width:'.$content_width_real.'%;overflow:hidden;height:'.$content_height.'px;');
					
					$result.= '<div'.$mss.'	onmouseover="MODCB'.$moduleid.'_Stop();" onmouseout="MODCB'.$moduleid.'_Start();" >';

					$result.= '
		<div id="MODCategoryBlock'.$moduleid.'_Div1" style="position: absolute;display: block;top: 0px;width:'.$content_width_real.'%;">'.$resultinside.'</div>
		<div id="MODCategoryBlock'.$moduleid.'_Div2" style="position: absolute;display: none;top: 0px;width:'.$content_width_real.'%;">'.$resultinside.'</div>';
				}	
		
			$result.= '
	</div>';
	

			$result.= '
	
	<script language="javascript" type="text/javascript">
	//<![CDATA[
		var MODCategoryBlock'.$moduleid.'_height = 0;
		var MODCategoryBlock'.$moduleid.'_position=0;
		var MODCategoryBlock'.$moduleid.'_turn=1;
		var MODCB'.$moduleid.'_Timer;
		
		function MODCB'.$moduleid.'_Move()
		{
			clearTimeout(MODCB'.$moduleid.'_Timer);
			
			var y=MODCategoryBlock'.$moduleid.'_position-'.$speed.';
			var h=MODCategoryBlock'.$moduleid.'_height;
			
			if(y<=-h)
			{
				MODCategoryBlock'.$moduleid.'_turn++;
				if(MODCategoryBlock'.$moduleid.'_turn>2)
					MODCategoryBlock'.$moduleid.'_turn=1;
					
				
				var hidden_idx=0;
				if(MODCategoryBlock'.$moduleid.'_turn==1)
					hidden_idx=2;
				else
					hidden_idx=1;
				
				var Hidden_Obj=document.getElementById("MODCategoryBlock'.$moduleid.'_Div"+hidden_idx);
				Hidden_Obj.style.display="none";
				
				y=0;
			}
			
			var hidden_idx=0;
			if(MODCategoryBlock'.$moduleid.'_turn==1)
				hidden_idx=2;
			else
				hidden_idx=1;
			
			var Hidden_Obj=document.getElementById("MODCategoryBlock'.$moduleid.'_Div"+hidden_idx);
			if(Hidden_Obj.style.display=="none")
			{
				Hidden_Obj.style.display="block";
			}
				
			Hidden_Obj.style.top=(y+h)+"px";
				
			var Shown_Obj=document.getElementById("MODCategoryBlock'.$moduleid.'_Div"+MODCategoryBlock'.$moduleid.'_turn);
			
			Shown_Obj.style.top=y+"px";
			
			
			MODCategoryBlock'.$moduleid.'_position=y;
			MODCB'.$moduleid.'_Timer=setTimeout("MODCB'.$moduleid.'_Move()", 100);
		}
		
		function MODCB'.$moduleid.'_Start()
		{
			clearTimeout(MODCB'.$moduleid.'_Timer);
		
			var h=document.getElementById("MODCategoryBlock'.$moduleid.'_Div1").offsetHeight;
			MODCategoryBlock'.$moduleid.'_height=h;
		
			if( h >'.$content_height.')
			{
				MODCB'.$moduleid.'_Timer=setTimeout("MODCB'.$moduleid.'_Move()", 100);
			}
		}
		
		function MODCB'.$moduleid.'_Stop()
		{
			
			var h=document.getElementById("MODCategoryBlock'.$moduleid.'_Div1").offsetHeight;
			MODCategoryBlock'.$moduleid.'_height=h;
		
			if( h >'.$content_height.')
			{
				clearTimeout(MODCB'.$moduleid.'_Timer);
			}
		}
		
		//Add module to start list
		cbmodarray[cbmodarray.length]="MODCB'.$moduleid.'_Start()";
		
		
			
	//]]>
	</script>
	';
			}
			else
				$result.= '
	
	'.$resultinside.'
	</div>';
	
	
		$result.= $cbm->getAttrlbute($cbm->mainframel.$cbm->l);
		
		return $result;
	}//function

	//--------------------------------------------------------------------------------------------
	
	function render_horizontal(&$params,&$cbm,$rows,$horizontalfill=true,$moduleid='com')
	{

	$result='
<!--  CB:Orientation - Horizontal -->
';

		
		$document = JFactory::getDocument();
		$document->addScript( 'modules/mod_categoryblock/categoryblock.js' );

		//$pagination=$cbm->getPagination();

		$modulecssstyle=$params->get( 'modulecssstyle' );
		$blockcssstyle=$params->get( 'blockcssstyle' );
		
	//	if($horizontalfill)
//			$content_height_real='100%';
		//else
		$content_height_real=$params->get( 'moduleheight' );

		$thelimit=(int)$params->get( 'limit' );
		$skipnarticles=(int)$params->get( 'skipnarticles' );
	
		$overflow=$params->get( 'overflow' );
		$widthinpx=true;


		$content_height=$content_height_real;

		if(strpos($content_height,'%')===false)
			$heightinpx=true;
		else
		{
			$heightinpx=false;
		
			$content_height_real=(int)str_replace('%','',$content_height_real);
	
		}	

		if($overflow=='scroll' or $overflow=='scroll-x' or $overflow=='scroll-y')
			$content_height=$content_height_real-17;
		else
		{
			$content_height=$content_height_real;
	
	
		}



		$content_width=(int)$params->get( 'modulewidth' );

		if($content_height<100)
			$content_height=100;


		$calculatet_columns=$this->roundUpTo(count($rows)/$cbm->layoutsettings->columns, 1);
		if($calculatet_columns<1)
			$column_height=0;	
		else
			$column_height=floor($content_height/$calculatet_columns);



		if($content_width!=0)
		{
			if($overflow=='autoflow-slow' or $overflow=='autoflow-normal' or $overflow=='autoflow-fast')
			{
				
				$result.= '<!-- Category Block - Auto Flow -->';
				
				$result.= '
				
				';
			}
			else
			{
				if($overflow=='scroll')
					$overflowcase='overflow:scroll;';
				elseif($overflow=='scroll-x')
					$overflowcase='overflow: -moz-scrollbars-horizontal;overflow-x: auto;overflow-y: hidden;';
				elseif($overflow=='scroll-y')
					$overflowcase='overflow: -moz-scrollbars-vertical;overflow-x: hidden;overflow-y: auto;';
				elseif($overflow=='hidden')
					$overflowcase='overflow: hidden;';
				
			
				$result.= '<!-- Category Block - Scroll -->';
			
				$mss=$cbm->ClassStyleOption($modulecssstyle,'');
			
				if($widthinpx)
					$mss=$cbm->ClassStyleOption($modulecssstyle,'width:'.$content_width.'px;height:'.$content_height_real.'px;'.$overflowcase.';');
				else
					$mss=$cbm->ClassStyleOption($modulecssstyle,'width:'.$content_width.';height:'.$content_height_real.'px;'.$overflowcase.';');
				
				$result.='<div'.$mss.'>';
			}
	
		}
		else
		{
			if($heightinpx)
				$mss=$cbm->ClassStyleOption($modulecssstyle,'height:'.$content_height_real.'px;');
			else
				$mss=$cbm->ClassStyleOption($modulecssstyle,'height:'.$content_height_real.';');
				
			$result.='<div'.$mss.'>';
		}	
	


			//$resultinside='';
/*
			$resultinside.='
			<table style="border:none;height:'.$content_height.($heightinpx ? '' : '%').';padding:'.$cbm->layoutsettings->padding.'px;" cellspacing="0">
			<tbody>
			';
			*/
	
		    $tr=0;
			
			$count=0;
			$catresult='';
			
			$row_count=count($rows);
			
			$cbm->active_item_exist=$cbm->ActiveArticleExist($rows);
			$last_category=-1;
			//$table_closed=true;
			$row_closed=true;
			
			$catresult.='
				<table style="border:none;height:'.$content_height.($heightinpx ? '' : '%').';padding:'.$cbm->layoutsettings->padding.'px;" cellspacing="0">
			<tbody>
';


			foreach($rows as $row)
			{
				if($count>=$skipnarticles)
				{
					// ----------------- header and staff
					
				

			

			
				//------------------ end of header and staff
					
					
					
					
					
					if($tr==0)
						$catresult.='<tr>';
						
						if($cbm->layoutsettings->recursive==2 or $cbm->layoutsettings->recursive==3)
							$blockcssstyle_=str_replace('[level]',$row->category_level,$blockcssstyle);
						else
							$blockcssstyle_=$blockcssstyle;
						
						//echo '$column_height='.$column_height.'</br>';
						
						$style_val='vertical-align: top;';//height:'.$column_height.($heightinpx ? '' : '%').'px;'; and strpos($blockcssstyle_,'height:')===false
						if(strpos($blockcssstyle_,'vertical-align:')===false)
						{
							$blockcssstyle_.=';'.$style_val;
							
						}
					
						$catresult.='<td style="'.($blockcssstyle_!='' ? ''.$blockcssstyle_ : 'border:none;').'">';
			
						
						
						if($row->category_id!=$last_category and ($cbm->layoutsettings->recursive==2 or $cbm->layoutsettings->recursive==3))
						{
					
					
							if($cbm->layoutsettings->categorytitlecssstyle!='')
							{
								$s=str_replace('[level]',$row->category_level,$cbm->layoutsettings->categorytitlecssstyle);
								$catresult.='<div'.$cbm->ClassStyleOption($s).'>'.$row->category_title.'</div>';
							}
							else
								$catresult.='<h1>'.$row->category_title.'</h1>';
						}	
						
						
						
						$catresult.=$cbm->render($row,$count,$row_count,$tr+1);
		
						$catresult.='</td>';
				
		
					$tr++;
					if($tr==$calculatet_columns)
					{
						$catresult.='
										</tr>
									';
				
						$tr	=0;
		
					}
				}
			
				$count++;
		
				if($count>=$thelimit+$skipnarticles and $thelimit!=0)
					break;
		
				$last_category=$row->category_id;
			}//foreach
			
			
		
			if(!$row_closed)
			{
				if($tr>0)
					$catresult.='<td colspan="'.($calculatet_columns-$tr).'" style="border:none;">&nbsp;</td></tr>';
			}  	

			//if(!$table_closed)
			//{
				$catresult.='
			</tbody>
		</table>
		';
			//			$row_closed=true;
			//}
	  
	  /*
		       .'</tbody>
        
		    </table>
			';
			*/
			$resultinside=$catresult;
	

			if($overflow=='autoflow-slow' or $overflow=='autoflow-normal' or $overflow=='autoflow-fast')
			{
				if($overflow=='autoflow-slow')
					$speed=1;
			
				if($overflow=='autoflow-normal')
					$speed=2;
			
				if($overflow=='autoflow-fast')
					$speed=5;
			
				if($widthinpx)
				{
					$mss=$cbm->ClassStyleOption($modulecssstyle,'position:relative;height:'.$content_height_real.'px;overflow:hidden;width:'.$content_width.'px;');
					$result.= '<div'.$mss.' onmouseover="MODCB'.$moduleid.'_Stop();" onmouseout="MODCB'.$moduleid.'_Start();" >';
					

					$result.= '
		<div id="MODCategoryBlock'.$moduleid.'_Div1" style="position: absolute;display: block;top:0px;left: 0px;height:'.$content_height_real.'px;">'.$resultinside.'</div>
		<div id="MODCategoryBlock'.$moduleid.'_Div2" style="position: absolute;display: none; top:0px;left: 0px;height:'.$content_height_real.'px;">'.$resultinside.'</div>';
				}
				else
				{
					$mss=$cbm->ClassStyleOption($modulecssstyle,'position: relative;height:'.$content_height_real.'%;overflow:hidden;width:'.$content_width.'px;');
					$result.= '<div'.$mss.'	onmouseover="MODCB'.$moduleid.'_Stop();" onmouseout="MODCB'.$moduleid.'_Start();" >';

					
					$result.= '
		<div id="MODCategoryBlock'.$moduleid.'_Div1" style="position: absolute;display: block;top: 0px;left: 0px;height:'.$content_height_real.'%;">'.$resultinside.'</div>
		<div id="MODCategoryBlock'.$moduleid.'_Div2" style="position: absolute;display: none; top: 0px;left: 0px;height:'.$content_height_real.'%;">'.$resultinside.'</div>';
				}	
		
			$result.= '
	</div>';
	

			$result.= '
	
	<script language="javascript" type="text/javascript">
	//<![CDATA[
	
		var MODCategoryBlock'.$moduleid.'_width = 0;
		var MODCategoryBlock'.$moduleid.'_position = 0;
		var MODCategoryBlock'.$moduleid.'_turn=1;
		var MODCB'.$moduleid.'_Timer;
		
		function MODCB'.$moduleid.'_Move()
		{
			clearTimeout(MODCB'.$moduleid.'_Timer);
			
			var x=MODCategoryBlock'.$moduleid.'_position-'.$speed.';
			var w=MODCategoryBlock'.$moduleid.'_width;
			
			if(x<=-w)
			{
				MODCategoryBlock'.$moduleid.'_turn++;
				if(MODCategoryBlock'.$moduleid.'_turn>2)
					MODCategoryBlock'.$moduleid.'_turn=1;
					
				
				var hidden_idx=0;
				if(MODCategoryBlock'.$moduleid.'_turn==1)
					hidden_idx=2;
				else
					hidden_idx=1;
				
				var Hidden_Obj=document.getElementById("MODCategoryBlock'.$moduleid.'_Div"+hidden_idx);
				Hidden_Obj.style.display="none";
				
				x=0;
			}
			
			var hidden_idx=0;
			if(MODCategoryBlock'.$moduleid.'_turn==1)
				hidden_idx=2;
			else
				hidden_idx=1;
			
			var Hidden_Obj=document.getElementById("MODCategoryBlock'.$moduleid.'_Div"+hidden_idx);
			if(Hidden_Obj.style.display=="none")
			{
				Hidden_Obj.style.display="block";
			}
				
			Hidden_Obj.style.left=(x+w)+"px";
				
			var Shown_Obj=document.getElementById("MODCategoryBlock'.$moduleid.'_Div"+MODCategoryBlock'.$moduleid.'_turn);
			
			Shown_Obj.style.left=x+"px";
			
			
			MODCategoryBlock'.$moduleid.'_position=x;
			MODCB'.$moduleid.'_Timer=setTimeout("MODCB'.$moduleid.'_Move()", 100);
		}
		
		function MODCB'.$moduleid.'_Start()
		{
			clearTimeout(MODCB'.$moduleid.'_Timer);
		
			var w=document.getElementById("MODCategoryBlock'.$moduleid.'_Div1").offsetWidth;
			MODCategoryBlock'.$moduleid.'_width=w;
		
			if( w >'.$content_width.')
			{
				MODCB'.$moduleid.'_Timer=setTimeout("MODCB'.$moduleid.'_Move()", 100);
			}
		}
		
		function MODCB'.$moduleid.'_Stop()
		{
			
			var w=document.getElementById("MODCategoryBlock'.$moduleid.'_Div1").offsetWidth;
			MODCategoryBlock'.$moduleid.'_width=w;
		
			if( w >'.$content_width.')
			{
				clearTimeout(MODCB'.$moduleid.'_Timer);
			}
		}
		
		//Add module to start list
		cbmodarray[cbmodarray.length]="MODCB'.$moduleid.'_Start()";

		
	//]]>
	</script>
	';
			}
			else
				$result.= '
	
	'.$resultinside.'
	</div>';
	
	

	

			$result.= $cbm->getAttrlbute($cbm->mainframel.$cbm->l);
			
		
			
			return $result;
			
			
	}//function
	
	function roundUpTo($number, $increments) {
	    $increments = 1 / $increments;
	    return (ceil($number * $increments) / $increments);
	}
	
	function getSmartCategory()
	{
		$article_id=$this->getArticleID();
		if($article_id!=-1)
		{
			//Article Found
			return $this->getCategoryByArticleID($article_id);
		}
		else
			return -1;
	}
	
	function getCategoryByArticleID($article_id)
	{
		$db = JFactory::getDBO();
		
		$query='SELECT `catid` FROM `#__content` WHERE `id`='.$article_id.' LIMIT 1';
		$db->setQuery($query);
		if (!$db->query())    echo ('Cannot find "Smart Category":'. $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)==0)
		{
			//Article not found
			return -1;
		}
		else
			return (int)$rows[0]->catid;
		
	}
	
	function getArticleID()
        {
	
		if(JRequest::getCmd('option')=='com_content' and JRequest::getCmd('view')=='article')
			return JRequest::getInt('id');
		
                if(JRequest::getCmd('option')=='com_categoryblock')
                        return JRequest::getInt('id');
                else
                        return -1;
				
                
        }
	
}//class
	
	
	
?>