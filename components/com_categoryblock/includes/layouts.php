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


class CBLayouts
{
	static function getTextwrap($imagewidth, $imageheight,$imagecssstyle, $showarticletitle, $titlecssstyle,$descriptioncssstyle,$showcreationdate,$dateformat,$datecssstyle,$gotocomments, $showreadmore,$readmorestyle,$targetwindow,$orientation,$titleimagepos)
	{
		$result='<!-- Category Bloock - Textwrap Layout -->';
		
		$_the_image='';
		$_the_title='';
		
		if(stripos($imagecssstyle,'float')===false)
			$imagecssstyle='float:left;'.$imagecssstyle;
		
		if($imagewidth>0 or $imageheight>0)
		{
					
			if($orientation)
			{
				//Horizontal
				if($imagewidth!=0)
				{
					//to crop by height
					$_the_image.='<div '
						.CBLayouts::ClassStyleOption($imagecssstyle,
						($imagewidth>0 ? 'width:'.$imagewidth.'px;' : '')
						.($imageheight>0 ? 'height:'.$imageheight.'px;' : '')
						.'overflow: hidden;position:relative;overflow:hidden;float:left;'
						)
					.'>';
				
				
				
					$_the_image.=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
					$_the_image.='</div>';
				}
				else
				{
					//Auto width
					$_the_image.=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
				}
			}
			else
			{
				//Vertical
				if($imageheight!=0)
				{
					//to crop by height
					$_the_image.='<div '
						.CBLayouts::ClassStyleOption($imagecssstyle,
						($imagewidth>0 ? 'width:'.$imagewidth.'px;' : '')
						.($imageheight>0 ? 'height:'.$imageheight.'px;' : '')
						.'overflow: hidden;position:relative;overflow:hidden;float:left;'
						)
					.'>';
				
					$_the_image.=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
					$_the_image.='</div>';
				}
				else
				{
					//Auto height
					$_the_image.=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
				}
			
			}//if($orientation)
			
		}//if($imagewidth>0 or $imageheight>0)
		
		if($showarticletitle)
			$_the_title.='<p'.CBLayouts::ClassStyleOption($titlecssstyle).'><a href="[link]" '.CBLayouts::getTarget($targetwindow,'[link]').'>[articletitle]</a></p>';
	
			
		$_the_desc='
		[if:article]
			<p'.CBLayouts::ClassStyleOption($descriptioncssstyle).'>[article]...</p>
		[endif:article]
		';
				
		if($titleimagepos=='titleimage')
		{
			$result.=$_the_title;
					
			$result.=$_the_image;
					
			$result.=$_the_desc;
		}
		else
		{
			$result.=$_the_image;
				
			$result.=$_the_title;
					
			if($_the_title and $_the_desc!='')
				$result.='<br/>';
					
			$result.=$_the_desc;
		}
				
		$result.=CBLayouts::getButtom($gotocomments,$targetwindow,$showreadmore,$readmorestyle);
		
		return $result;
		
		
		
	}
	
	
	static function getHorizontal($imagewidth, $imageheight,$imagecssstyle, $showarticletitle, $titlecssstyle,$descriptioncssstyle,$showcreationdate,$dateformat,$datecssstyle,$gotocomments, $showreadmore,$readmorestyle,$targetwindow, $titleimagepos)
	{
		
		$result='<!-- Category Block - Horizontal Layout -->
			<table cellspacing="0" style="border:none;padding:3px;width:100%;">
			<tbody>
				<tr style="border:none;">';
				
				//normal
			
				if(($imagewidth>0 or $imageheight>0) and $titleimagepos!='titleimage')
				{
					$result.='<td style="width:'.$imagewidth.'px;vertical-align: top;border:none;" >';
					$result.=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
					$result.='</td>';
				}
			
				$result.='
					<td style="vertical-align: top;border:none;">';
					
				if($showarticletitle)
					$result.='<p'.CBLayouts::ClassStyleOption($titlecssstyle).'><a href="[link]" '.CBLayouts::getTarget($targetwindow,'[link]').'>[articletitle]</a></p>';
	
				$result.='
				[if:article]
					<p'.CBLayouts::ClassStyleOption($descriptioncssstyle).'>[article]...</p>
				[endif:article]
				';

				$result.=CBLayouts::getButtom($gotocomments,$targetwindow,$showreadmore,$readmorestyle);
			
			$result.='
					</td>
					';
					
				if(($imagewidth>0 or $imageheight>0) and $titleimagepos=='titleimage')
				{
					$result.='<td style="width:'.$imagewidth.'px;vertical-align: top;border:none;" >';
					$result.=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
					$result.='</td>';
				}
				
			$result.='
				</tr>
			</tbody>
			</table>
			';
			return $result;
		
		
		
	}
	
	static function getVertical($imagewidth, $imageheight,$imagecssstyle, $showarticletitle, $titlecssstyle,$descriptioncssstyle,$showcreationdate,$dateformat,$datecssstyle,$gotocomments, $showreadmore,$readmorestyle,$targetwindow, $titleimagepos)
	{
		
		$result='
		<!-- Category Bloock - Vertical Layout -->';
		
		$result_image=CBLayouts::getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle);
		
		if($showarticletitle)
			$result_title='<p'.CBLayouts::ClassStyleOption($titlecssstyle).'><a href="[link]" '.CBLayouts::getTarget($targetwindow,'[link]').'>[articletitle]</a></p>';
		else
			$result_title='';
		
		if($titleimagepos=='titleimage')
		{
			$result.=$result_title;
			$result.=$result_image;
		}
		else
		{
			$result.=$result_image;
			$result.=$result_title;
		}
		
		
		
		$result.='
		[if:article]
			<p'.CBLayouts::ClassStyleOption($descriptioncssstyle).'>[article]...</p>
		[endif:article]
		';
		
		if($showcreationdate)
		{
			if($datecssstyle=='')
					$result.='<p style="text-align:left;"><i>[creationdate'.($dateformat=='' ? '' : ':'.$dateformat).']</i></p>';
				else
					$result.='<p'.CBLayouts::ClassStyleOption($datecssstyle).'>[creationdate'.($dateformat=='' ? '' : ':'.$dateformat).']</p>';
		}
		
		$result.=CBLayouts::getButtom($gotocomments,$targetwindow,$showreadmore,$readmorestyle);
		
		return $result;
	}
	
	static function getImage($imagewidth,$imageheight,$imagecssstyle,$targetwindow,$showarticletitle)
	{
		$result='';
		
		if($imagewidth>0 or $imageheight>0)
		{
			$result.='[if:image]';
			
			$result.='<a href="[link]" '.CBLayouts::getTarget($targetwindow,'[link]').'>'
					.'[image:'.$imagewidth.','.$imageheight.',style="'.$imagecssstyle.'"]</a>';
			
			$result.='[endif:image]';
		}
		
		return $result;
	}
	
	static function ClassStyleOption($value,$styleplus='')
	{
		$prvalue=str_replace(' ','',$value);
		if(strpos($prvalue,'class:')===false)
		{
			return ' style="'.$value.($styleplus!='' ? ';'.$styleplus : '').'" ';
		}
		else
		{
			$pair=explode('class:',$prvalue);
			return ' class="'.$pair[1].'" '.($styleplus!='' ? ' style="'.$styleplus.'" ' : '');
			
		}
		return '';
	}
	
	
	static function getButtom($gotocomments,$targetwindow,$showreadmore,$readmorestyle)
	{
		$result='';
		if($gotocomments)
		{
			$result.='
			<table style="border:none;"><tbody><tr>
			<td style="text-align:left;border:none;">[gotocomments]</td>';
			

			if($showreadmore)
			{
				if($readmorestyle!='')
				{
					$result.='</tr></tbody></table>';
					$result.='<div'.CBLayouts::ClassStyleOption($readmorestyle).'>[readmore:,'.$targetwindow.','.CBLayouts::ClassStyleOption($readmorestyle).']</div>';
				}
				else
				{
					$result.='<td style="text-align:center;border:none;">|</td>';
					$result.='<td style="text-align:right;border:none;">[readmore:,'.$targetwindow.','.CBLayouts::ClassStyleOption($readmorestyle).']</td>';
					$result.='</tr></tbody></table>';
				}
			}
			else
				$result.='</tr></tbody></table>';
		}
		else
		{
			if($showreadmore)
			{
				if($readmorestyle!='')
				{
					$result.='<div'.CBLayouts::ClassStyleOption($readmorestyle).'>[readmore:,'.$targetwindow.','.CBLayouts::ClassStyleOption($readmorestyle).']</div>';
				}
				else
				{
					$result.='<p style="text-align:right;">[readmore]</p>';
				}
				
			}//if($this->layoutsettings->firstarticle_showreadmore)
		}
	
		return $result;
	}
	
	
	static function getTarget($targetwindow,$aLink)
	{
		switch($targetwindow)
		{
			case '_blank':
				return 'target="_blank"';
			break;
		
			case 'jblank':
				return 'onClick="popup = window.open(\''.$aLink.'\', \'PopupPage\', \'height=450,width=500,scrollbars=yes,resizable=yes\'); return false" target="_blank"';
			break;
		
		
			case '_blanknotmpl':
				return 'target="_blank"';
			break;
		
			case ';blanknotmpl':
				return 'onClick="popup = window.open(\''.$aLink.'\', \'PopupPage\', \'height=450,width=500,scrollbars=yes,resizable=yes\'); return false" target="_blank"';
			break;
		
			default:
				return 'target="_top"';
			break;
		}
		
	}
	

}