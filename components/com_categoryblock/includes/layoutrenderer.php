<?php
/**
 * CategoryBlock Joomla! 1.5/2.5/3.0 Native Component
 * @version 1.8.0
 * @author DesignCompass corp <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @GNU General Public License
 **/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


class CBLayoutRenderer
{
	static function getValue($fld,$params,$row,$article,$image,$image_alt,$link,$imagewidth=0,$imageheight=0)
	{
		
			
		switch($fld)
		{
			case 'image':
				$pair=CBLayoutRenderer::csv_explode(',', $params, $enclose='"', true);
				
				if(isset($pair[0]) and $pair[0]!='')
					$width=(int)$pair[0];
				else
					$width=$imagewidth;
					
				if(isset($pair[1]) and $pair[1]!='')
					$height=(int)$pair[1];
				else
					$height=$imageheight;
					
				if(isset($pair[2]) and $pair[2]!='')
					$customoption=$pair[2];
				else
					$customoption='';
					
				return CBLayoutRenderer::renderImage($image,$image_alt,$row->title,$width,$height,$customoption);
				break;
			
			case 'alt':
				if($image_alt!='')
					return $image_alt;
				else
					return $row->title;
			
			case 'article':
				$pair=CBLayoutRenderer::csv_explode(',', $params, $enclose='"', true);
				if(!isset($pair[0]))
					return $article;
					
				jimport('joomla.version');
				$version = new JVersion();
				$JoomlaVersionRelease=$version->RELEASE;
					
				if($JoomlaVersionRelease==1.5)
					return '';

				switch($pair[0])
				{
					case 'image_intro':
						$a=json_decode($row->images);
						if(isset($pair[1]) and $pair[1]!='')
							$width=(int)$pair[1];
						else
							$width=$imagewidth;
					
						if(isset($pair[2]) and $pair[2]!='')
							$height=(int)$pair[2];
						else
							$height=$imageheight;
					
						if(isset($pair[3]) and $pair[3]!='')
							$customoption=$pair[3];
						else
							$customoption='';
						
						return CBLayoutRenderer::renderImage($a->image_intro,$a->image_intro_alt,$a->image_intro_caption,$width,$height,$customoption);
						
					break;
				
					case 'image_fulltext':
						$a=json_decode($row->images);
						if(isset($pair[1]) and $pair[1]!='')
							$width=(int)$pair[1];
						else
							$width=$imagewidth;
					
						if(isset($pair[2]) and $pair[2]!='')
							$height=(int)$pair[2];
						else
							$height=$imageheight;
					
						if(isset($pair[3]) and $pair[3]!='')
							$customoption=$pair[3];
						else
							$customoption='';
						
						return CBLayoutRenderer::renderImage($a->image_fulltext,$a->image_fulltext_alt,$a->image_fulltext_caption,$width,$height,$customoption);
					break;
				
					case 'urla':
						$a=json_decode($row->urls);
						return '<a href="'.$a->urla.'" target="'.$a->targeta.'">'.$a->urlatext.'</a>';
						break;
					
					case 'urlb':
						$a=json_decode($row->urls);
						return '<a href="'.$a->urlb.'" target="'.$a->targetb.'">'.$a->urlbtext.'</a>';
						break;
					
					case 'urlc':
						$a=json_decode($row->urls);
						return '<a href="'.$a->urlc.'" target="'.$a->targetc.'">'.$a->urlctext.'</a>';
						break;
				}
				return $article;
				break;
			
			case 'articletitle':
				return $row->title;
				break;
			
			case 'id':
				return $row->id;
				break;
			
			case 'hits':
				return $row->hits;
				break;
			
			case 'createdby':
				return $row->user_name;
				break;
			
			case 'username':
				return $row->username;
				break;
			
			case 'metakey':
				return $row->metakey;
				break;
			
			case 'metadesc':
				return $row->metadesc;
				break;
			
			case 'introtext':
				return $row->introtext;
				break;
			
			case 'fulltext':
				return $row->fulltext;
				break;

			case 'metadata':
				
				return CBLayoutRenderer::getMenuParam($params,$row->metadata);
				break;
			
			case 'link':
				return $link;
				break;
			
			case 'creationdate':
				
				if($params=='')
					$creationdate= JHTML::date($row->created);
				else
					$creationdate=date($params,strtotime($row->created));
				//example: "F j, Y, g:i a"
		
				return $creationdate;
				break;
			
			case 'readmore':
				$pair=explode(',',$params);
				
				if(isset($pair[0]) and $pair[0]!='')
					$label=$pair[0];
				else
					$label=JText::_( 'READMORE' );
					
				if(isset($pair[1]) and $pair[1]!='')
					$targetwindow=$pair[1];
				else
					$targetwindow='';
					
					
				if(isset($pair[2]) and $pair[2]!='')
					$customoption=$pair[2];
				else
					$customoption='';
					
					
					
				
				return '<a href="'.$link.'" '.CBLayouts::getTarget($targetwindow,$link).($customoption!='' ? ' '.$customoption : '').'>'.$label.'</a>';
				break;
			
			case 'gotocomments':
				
				$pair=explode(',',$params);
				
				if(isset($pair[0]) and $pair[0]!='')
					$label=$pair[0];
				else
					$label=JText::_( 'ADDCOMMENT' );
					
				if(isset($pair[1]) and $pair[1]!='')
					$targetwindow=$pair[1];
				else
					$targetwindow='';
					
					
				if(isset($pair[2]) and $pair[2]!='')
					$customoption=$pair[2];
				else
					$customoption='';
					
				
				return '<a href="'.$link.'#addcomments" '.CBLayouts::getTarget($targetwindow,$link).($customoption!='' ? ' '.$customoption : '').'>'.$label.'</a>';
				break;
			
		}
		
		return '';
	}
	

	
	static function renderImage($image,$image_alt,$title_,$width,$height,$customoption)
	{
		$result='';
		
		if($image!='')
		{
			$result='<img'
						.' src="'.$image.'"'
						.($width>0 ? ' width="'.$width.'"' : '')
						.($height>0 ? ' height="'.$height.'"' : '');
			
			if($image_alt!='')
				$title=$image_alt;
			else
				$title=$title_;
			
			$cc=strtolower(str_replace(' ','',$customoption));
			
			if(strpos($cc,'alt="')===false and strpos($cc,'alt=\'')===false)
				$result.=' alt="'.$title.'"';
				
			if(strpos($cc,'title="')===false and strpos($cc,'title=\'')===false)
				$result.=' title="'.$title.'"';
				
				
			$result.=($customoption!='' ? ' '.$customoption : '').' />';
		}	
				
		return $result;
	}
	
	static function render($htmlresult,$row,$article,$image,$image_alt,$link,$row_number_,$row_count,$column_number,$columns,$active_item_detected,$active_item_exist,$overwritearticleid,$imagewidth=0,$imageheight=0)
	{
		
		
		$fields=array('image','alt','link','articletitle','article','creationdate',
			      'readmore','gotocomments','hits','createdby','username',
			      'metadata','metakey','metadesc','introtext','fulltext','id');
		
		$row_number=$row_number_+1;
		
		$line_number=ceil($row_number/$columns);
		
		if(JRequest::getVar('option')=='com_categoryblock' or JRequest::getVar('option')=='com_content')
		{
			if($overwritearticleid!=0)
				$article_id=$overwritearticleid;
			else
				$article_id=JRequest::getInt('id');
			
			CBLayoutRenderer::IFStatment('[if:active]','[endif:active]',$htmlresult,!($row->id==$article_id));
			CBLayoutRenderer::IFStatment('[ifnot:active]','[endifnot:active]',$htmlresult,$row->id==$article_id);
		}
		
		CBLayoutRenderer::IFStatment('[if:first]','[endif:first]',$htmlresult,!($row_number==1));
		CBLayoutRenderer::IFStatment('[if:last]','[endif:last]',$htmlresult,!($row_number==$row_count));
		
		CBLayoutRenderer::IFStatment('[ifnot:first]','[endifnot:first]',$htmlresult,($row_number==1));
		CBLayoutRenderer::IFStatment('[ifnot:last]','[endifnot:last]',$htmlresult,($row_number==$row_count));
		 //or !$active_item_exist
		 //or !$active_item_exist
		CBLayoutRenderer::IFStatment('[if:beforeactive]','[endif:beforeactive]',$htmlresult,($active_item_detected or !$active_item_exist));
		CBLayoutRenderer::IFStatment('[ifnot:beforeactive]','[endifnot:beforeactive]',$htmlresult,!($active_item_detected or !$active_item_exist));
				
		CBLayoutRenderer::IFStatment('[if:item_even]','[endif:item_even]',$htmlresult,(bool)($row_number%2));
		CBLayoutRenderer::IFStatment('[if:item_odd]','[endif:item_odd]',$htmlresult,!(bool)($row_number%2));
		
		CBLayoutRenderer::IFStatment('[if:column_even]','[endif:column_even]',$htmlresult,(bool)($column_number%2));
		CBLayoutRenderer::IFStatment('[if:column_odd]','[endif:column_odd]',$htmlresult,!(bool)($column_number%2));
		
		CBLayoutRenderer::IFStatment('[if:line_even]','[endif:line_even]',$htmlresult,(bool)($line_number%2));
		CBLayoutRenderer::IFStatment('[if:line_odd]','[endif:line_odd]',$htmlresult,!(bool)($line_number%2));
		
		foreach($fields as $fld)
		{
			$isEmpty=false;
			
			if($fld=='article' and $article=='')
					$isEmpty=true;
					
			if($fld=='image' and $image=='')
					$isEmpty=true;
					
					

			$ValueOptions=array();
			$ValueList=CBLayoutRenderer::getListToReplace($fld,$ValueOptions,$htmlresult,'[]');
		
			CBLayoutRenderer::IFStatment('[if:'.$fld.']','[endif:'.$fld.']',$htmlresult,$isEmpty);
			CBLayoutRenderer::IFStatment('[ifnot:'.$fld.']','[endifnot:'.$fld.']',$htmlresult,!$isEmpty);

			$i=0;
			foreach($ValueOptions as $ValueOption)
			{
				$vlu=CBLayoutRenderer::getValue($fld,$ValueOptions[$i], $row,$article,$image,$image_alt,$link,$imagewidth,$imageheight);
				$htmlresult=str_replace($ValueList[$i],$vlu,$htmlresult);
				$i++;
			}

		}
		
		return $htmlresult;
		
		
	}
	
	static function IFStatment($ifname,$endifname,&$htmlresult,$isEmpty)
	{
					
		if($isEmpty)
		{
			do{
				$textlength=strlen($htmlresult);
						
				$startif_=strpos($htmlresult,$ifname);
				if($startif_===false)
					break;
			
				if(!($startif_===false))
				{
				
					$endif_=strpos($htmlresult,$endifname);
					if(!($endif_===false))
					{
						$p=$endif_+strlen($endifname);	
						$htmlresult=substr($htmlresult,0,$startif_).substr($htmlresult,$p);
					}	
				}
					
			}while(1==1);//$textlengthnew!=$textlength);
		}
		else
		{
			$htmlresult=str_replace($ifname,'',$htmlresult);
			$htmlresult=str_replace($endifname,'',$htmlresult);

		}
	}
	
	static function getListToReplace($par,&$options,&$text,$qtype)
	{
		$fList=array();
		$l=strlen($par)+2;
	
		$offset=0;
		do{
			if($offset>=strlen($text))
				break;
		
			$ps=strpos($text, $qtype[0].$par.':', $offset);
			if($ps===false)
				break;
		
		
			if($ps+$l>=strlen($text))
				break;
		
		$pe=strpos($text, $qtype[1], $ps+$l);
				
		if($pe===false)
			break;
		
		$notestr=substr($text,$ps,$pe-$ps+1);

			$options[]=trim(substr($text,$ps+$l,$pe-$ps-$l));
			$fList[]=$notestr;
			

		$offset=$ps+$l;
		
			
		}while(!($pe===false));
		
		//for these with no parameters
		$ps=strpos($text, $qtype[0].$par.$qtype[1]);
		if(!($ps===false))
		{
			$options[]='';
			$fList[]=$qtype[0].$par.$qtype[1];
		}
		
		return $fList;
	}

	static function csv_explode($delim=',', $str, $enclose='"', $preserve=false)
	{
		$resArr = array();
		$n = 0;
		$expEncArr = explode($enclose, $str);
		foreach($expEncArr as $EncItem)
		{
			if($n++%2){
				array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
			}else{
				$expDelArr = explode($delim, $EncItem);
				array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
			    $resArr = array_merge($resArr, $expDelArr);
			}
		}
	return $resArr;
	}
	


	static function getMenuParam($param, $rawparams)
	{
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
				
		if($JoomlaVersionRelease < 1.6)
		{
			//Joomla 1.5
			$paramslist=explode("\n",$rawparams);
			
			foreach($paramslist as $pl)
		    {
				$p=strpos($pl,'=');
			
				if(!($p===false))
				{
			
					$option=substr($pl,0,$p);
					$vlu=substr($pl,$p+1);
				
					if($option==$param and strlen($vlu)>0)
						return $vlu;
			    
				}//if(!($p===false))
		    }//foreach($paramslist as $pl)
			return '';
			
		}//		if($JoomlaVersionRelease == 1.5)
		else
		{
			if(strlen($rawparams)<8)
				return '';
			
			$rawparams=substr($rawparams,1,strlen($rawparams)-2);
			
			
			$paramslist=CBLayoutRenderer::csv_explode(',', $rawparams,'"', true);
			
			foreach($paramslist as $pl)
			{
				
				$pair=CBLayoutRenderer::csv_explode(':', $pl,'"', false);
				if($pair[0]==$param)
					return $pair[1];
			}
			
		}

		
			
		return '';
		
	}//function getMenuParam($param, $Itemid,$rawparams='')
	

}
?>