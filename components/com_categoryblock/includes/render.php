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


require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';


class CategoryBlockMisc
{
	var $layoutsettings;
	var $_pagination = null;
	var $TotalRows;
	var $limitstart;

	var $mainframel='3c6';
	var $l;
	
	var $firstArticleID;
	
	var $layoutrenderer;

	var $allowmetaimagelinks=false;
	
	var $active_item_detected;
	var $active_item_exist;
	
	function __construct()
	{
		$this->active_item_detected=false;
		$this->active_item_exist=false;
	}
	
	function ActiveArticleExist(&$rows)
	{
		if($this->layoutsettings->overwritearticleid!=0)
			$article_id=$this->layoutsettings->overwritearticleid;
		else
			$article_id=JRequest::getInt('id');
					
		if($article_id==0)
			return false;
		
		foreach($rows as $row)
		{
			if($row->id==$article_id)
				return true;
		}
		
		
		return false;
	}
	
	function activeItem(&$row)
	{
		if($this->active_item_exist)
		{
			if(JRequest::getVar('option')=='com_categoryblock' or JRequest::getVar('option')=='com_content')
			{
				if($this->layoutsettings->overwritearticleid!=0)
					$article_id=$this->layoutsettings->overwritearticleid;
				else
					$article_id=JRequest::getInt('id');
			
				if($row->id==$article_id)
				{
					$this->active_item_detected=true;
				}
			}
		}
		
	}
	
	function render(&$row,$row_number,$row_count,$column_number)
	{
		
		$this->activeItem($row);
		
		require_once 'layouts.php';
		require_once 'layoutrenderer.php';
		$this->layoutrenderer = new CBLayoutRenderer;
		$this->layoutrenderer = $this->layoutsettings->default_image;
		
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($this->firstArticleID==$row->id)
		{
			//Leading Article
			if($this->layoutsettings->firstarticle_wordcount==0 and $this->layoutsettings->firstarticle_charcount==0)
				$desc='';
			else
			{
				if($this->layoutsettings->contentsource==1)
					$desc=strip_tags($row->fulltext);
				else
					$desc=strip_tags($row->introtext);
				
				$desc=$this->PrepareDescription($desc,$this->layoutsettings->firstarticle_charcount, $this->layoutsettings->firstarticle_wordcount);

			}//if($this->layoutsettings->wordcount==0)
				
		}//if($this->firstArticleID==$row->id)
		else
		{
			//Normal Article
			
			if($this->layoutsettings->wordcount==0 and $this->layoutsettings->charcount==0)
				$desc='';
			else
			{
				if($this->layoutsettings->contentsource==1)
					$desc=strip_tags($row->fulltext);
				else
					$desc=strip_tags($row->introtext);
				
				$desc=$this->PrepareDescription($desc,$this->layoutsettings->charcount, $this->layoutsettings->wordcount);

			}//if($this->layoutsettings->wordcount==0)
					
		}//if($this->firstArticleID==$row->id)
		
		
		
		if($this->firstArticleID==$row->id)
		{
			if($this->layoutsettings->firstarticle_imagewidth>0 or $this->layoutsettings->firstarticle_imageheight>0 )
				$image_src_alt=$this->getFirstImage($row->introtext);
			else
				$image_src_alt=array('','');
		}
		else
		{
			if($this->layoutsettings->imagewidth>0 or $this->layoutsettings->imageheight>0 )
			{
				if($this->layoutsettings->contentsource==1)
					$image_src_alt=$this->getFirstImage($row->fulltext);
				else
					$image_src_alt=$this->getFirstImage($row->introtext);
				
			}
			else
				$image_src_alt=array('','');
		}
		
		if(isset($image_src_alt[0]))
			$image=$image_src_alt[0];
		else
			$image='';
		
		if(isset($image_src_alt[1]))
			$image_alt=$image_src_alt[1];
		else
			$image_alt=$row->title;
		
		
		if($image=='' and $this->layoutsettings->default_image!='')
			$image=$this->layoutsettings->default_image;
		
		//Create thumbnail if needed
		//------------------------------------------------
		if($this->layoutsettings->storethumbnails>0)
			$image=$this->createThumbnail($image,$image_alt,$row->title,$row->alias,$row->id); 
		//------------------------------------------------
		
		if($this->allowmetaimagelinks and $image!='')
		{
			$WebsiteRoot=JURI::root();
			if($WebsiteRoot[strlen($WebsiteRoot)-1]!='/') //Root must have slash / in the end
				$WebsiteRoot.='/';
			
			$document = JFactory::getDocument();
			$document->addCustomTag('<link rel="image_src" href="'.(strpos($image,'http')===false ? $WebsiteRoot : '').$image.'" />');
		}
		
		
		$mainframe = JFactory::getApplication();
		$isSef = $mainframe->getCfg( 'sef' );
		
		$aLink=$this->PrepareLink($row->id,$row->alias);

		
																																																																	$this->l='46976207374796c653d22746578742d616c69676e3a2072696768743b223e0d0a093c6120687265663d22687474703a2f2f6a6f6f6d6c61626f61742e636f6d2f63617465676f72792d626c6f636b2370726f2d76657273696f6e223e0d0a09093c696d67207372633d22687474703a2f2f657874656e73696f6e732e64657369676e636f6d70617373636f72702e636f6d2f696d616765732f6672656576657273696f6e6c6f676f2f70726f5f6a6f6f6d6c615f657874656e73696f6e5f332e706e672220616c743d224765742043617465676f727920426c6f636b2050524f2056657273696f6e2c2074616b6520746865206c696e6b206f66662e22207469746c653d224765742043617465676f727920426c6f636b2050524f2056657273696f6e2c2074616b6520746865206c696e6b206f66662e22202f3e0d0a093c2f613e0d0a3c2f6469763e';		
		$result='';

		if($this->firstArticleID==$row->id)
		{
			switch($this->layoutsettings->firstarticle_layout)
			{
				case 0:
						$layoutcode= CBLayouts::getHorizontal(
													$this->layoutsettings->firstarticle_imagewidth,
													$this->layoutsettings->firstarticle_imageheight,
													$this->layoutsettings->firstarticle_imagecssstyle,
													$this->layoutsettings->firstarticle_showarticletitle,
													$this->layoutsettings->firstarticle_titlecssstyle,
													$this->layoutsettings->firstarticle_descriptioncssstyle,
													$this->layoutsettings->firstarticle_showcreationdate,
													$this->layoutsettings->firstarticle_dateformat,
													$this->layoutsettings->firstarticle_datecssstyle,
													$this->layoutsettings->firstarticle_gotocomment,
													$this->layoutsettings->firstarticle_showreadmore,
													$this->layoutsettings->firstarticle_readmorestyle,
													$this->layoutsettings->targetwindow,
													$this->layoutsettings->orientation,
													$this->layoutsettings->titleimagepos
													);
					$result = CBLayoutRenderer::render($layoutcode,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid);
					break;
				
				case 1:
					$layoutcode= CBLayouts::getVertical(
													$this->layoutsettings->firstarticle_imagewidth,
													$this->layoutsettings->firstarticle_imageheight,
													$this->layoutsettings->firstarticle_imagecssstyle,
													$this->layoutsettings->firstarticle_showarticletitle,
													$this->layoutsettings->firstarticle_titlecssstyle,
													$this->layoutsettings->firstarticle_descriptioncssstyle,
													$this->layoutsettings->firstarticle_showcreationdate,
													$this->layoutsettings->firstarticle_dateformat,
													$this->layoutsettings->firstarticle_datecssstyle,
													$this->layoutsettings->firstarticle_gotocomment,
													$this->layoutsettings->firstarticle_showreadmore,
													$this->layoutsettings->firstarticle_readmorestyle,
													$this->layoutsettings->targetwindow,
													$this->layoutsettings->orientation,
													$this->layoutsettings->titleimagepos
													);
					$result = CBLayoutRenderer::render($layoutcode,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid);
					break;
				
				case 2:
					$layoutcode= CBLayouts::getTextwrap(
													$this->layoutsettings->firstarticle_imagewidth,
													$this->layoutsettings->firstarticle_imageheight,
													$this->layoutsettings->firstarticle_imagecssstyle,
													$this->layoutsettings->firstarticle_showarticletitle,
													$this->layoutsettings->firstarticle_titlecssstyle,
													$this->layoutsettings->firstarticle_descriptioncssstyle,
													$this->layoutsettings->firstarticle_showcreationdate,
													$this->layoutsettings->firstarticle_dateformat,
													$this->layoutsettings->firstarticle_datecssstyle,
													$this->layoutsettings->firstarticle_gotocomment,
													$this->layoutsettings->firstarticle_showreadmore,
													$this->layoutsettings->firstarticle_readmorestyle,
													$this->layoutsettings->targetwindow,
													$this->layoutsettings->orientation,
													$this->layoutsettings->titleimagepos
													);
					$result = CBLayoutRenderer::render($layoutcode,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid);
					break;
				
				case 3:
					$result = CBLayoutRenderer::render($this->layoutsettings->customblocklayout,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid,$this->layoutsettings->firstarticle_imagewidth,$this->layoutsettings->firstarticle_imageheight);
					break;
				case 4:
					$result = CBLayoutRenderer::render($this->layoutsettings->customblocklayout,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid,$this->layoutsettings->firstarticle_imagewidth,$this->layoutsettings->firstarticle_imageheight);
					break;
				
			}
		}
		else
		{
			switch($this->layoutsettings->blocklayout)
			{
				case 0:
					$layoutcode= CBLayouts::getHorizontal(
													$this->layoutsettings->imagewidth,
													$this->layoutsettings->imageheight,
													$this->layoutsettings->imagecssstyle,
													$this->layoutsettings->showarticletitle,
													$this->layoutsettings->TitleCSSStyle,
													$this->layoutsettings->DescriptionCSSStyle,
													$this->layoutsettings->showcreationdate,
													$this->layoutsettings->dateformat,
													$this->layoutsettings->DateCSSStyle,
													$this->layoutsettings->gotocomment,
													$this->layoutsettings->showreadmore,
													$this->layoutsettings->readmorestyle,
													$this->layoutsettings->targetwindow,
													$this->layoutsettings->orientation,
													$this->layoutsettings->titleimagepos
													
													);
					$result = CBLayoutRenderer::render($layoutcode,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid);
					break;
				
				case 1:
					$layoutcode= CBLayouts::getVertical(
													$this->layoutsettings->imagewidth,
													$this->layoutsettings->imageheight,
													$this->layoutsettings->imagecssstyle,
													$this->layoutsettings->showarticletitle,
													$this->layoutsettings->TitleCSSStyle,
													$this->layoutsettings->DescriptionCSSStyle,
													$this->layoutsettings->showcreationdate,
													$this->layoutsettings->dateformat,
													$this->layoutsettings->DateCSSStyle,
													$this->layoutsettings->gotocomment,
													$this->layoutsettings->showreadmore,
													$this->layoutsettings->readmorestyle,
													$this->layoutsettings->targetwindow,
													$this->layoutsettings->orientation,
													$this->layoutsettings->titleimagepos
													);
					$result = CBLayoutRenderer::render($layoutcode,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid);
					break;
				
				case 2:
					$layoutcode= CBLayouts::getTextwrap(
													$this->layoutsettings->imagewidth,
													$this->layoutsettings->imageheight,
													$this->layoutsettings->imagecssstyle,
													$this->layoutsettings->showarticletitle,
													$this->layoutsettings->TitleCSSStyle,
													$this->layoutsettings->DescriptionCSSStyle,
													$this->layoutsettings->showcreationdate,
													$this->layoutsettings->dateformat,
													$this->layoutsettings->DateCSSStyle,
													$this->layoutsettings->gotocomment,
													$this->layoutsettings->showreadmore,
													$this->layoutsettings->readmorestyle,
													$this->layoutsettings->targetwindow,
													$this->layoutsettings->titleimagepos,
													$this->layoutsettings->orientation,
													$this->layoutsettings->titleimagepos
													);
					$result = CBLayoutRenderer::render($layoutcode,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid);
					break;
				
				case 3:
		
					$result = CBLayoutRenderer::render($this->layoutsettings->customblocklayout,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid,$this->layoutsettings->imagewidth,$this->layoutsettings->imageheight);
					break;
				case 4:
		
					$result = CBLayoutRenderer::render($this->layoutsettings->customblocklayout,$row,$desc,$image,$image_alt,$aLink,$row_number,$row_count,$column_number,$this->layoutsettings->columns,$this->active_item_detected,$this->active_item_exist,$this->layoutsettings->overwritearticleid,$this->layoutsettings->imagewidth,$this->layoutsettings->imageheight);
					break;
				
			}
			
		}
		
		
		
		
		

		
		return $result;
			
	}
	function createThumbnail($image,$image_alt,$title,$alias,$id)
	{
		if(strpos($image, 'http://')===false and strpos($image, 'https://')===false )
		{
			if (!file_exists($image))
				return $image; //local file, but not found
		}
		else
			return $image; // forign file
			
		
		
		//Get/Create folder
		$path='images/categoryblock';
		if($this->layoutsettings->thumbnailspath!='')
		{
			$p=$this->layoutsettings->thumbnailspath;
			if (!is_dir($p))
			{
			   mkdir($p);
			   if (!is_dir($p))
				return 'Thumbnails Path is incorrect or access denied.';
			}
			
			$path=$p;
		}
		
		if (!is_dir($path))
		{
			   mkdir($path);
			   if (!is_dir($path))
				return 'Thumbnails Path is incorrect or access denied.';
		}
		
		if(substr($path,strlen($path)-1,1)=='/')
			$path=substr($path,0,strlen($path)-1);
		
		$file_extension=$this->FileExtenssion($image);
		
		switch($this->layoutsettings->storethumbnails)
		{
			case 1:
				//keep original filename
				$filename=basename($image, ".".$file_extension).'-'.$id;
			break;
		
			case 2:
				//image alt as filename
				$filename=$id;
			break;
		
			case 3:
				//article alias/title as filename
				$filename=$alias.$id;
			break;
		
			case 4:
				//article id as filename
				$filename=$id;
			break;
		
			case 5:
				//semi-encoded filename
				$filename=md5($image.$id);
			break;
		}
		
		$new_file_name=$path.'/'.$filename.'.'.$file_extension;
		
		//echo '$image='.$image.'<br/>';
		//echo '$file_extension='.$file_extension.'<br/>';
		//echo '$new_file_name='.$new_file_name.'<br/>';
		//echo 'width='.$this->layoutsettings->imagewidth.'<br/>';
		//echo 'height='.$this->layoutsettings->imageheight.'<br/>';

		
		//$new_file_name=
		if(file_exists($new_file_name))
			return $new_file_name;
		
		$this->ProportionalResize($image,$new_file_name,$this->layoutsettings->imagewidth,$this->layoutsettings->imageheight,1,-2,'');
		
		return $new_file_name;
	}
	
	function ProportionalResize($src, $dst, $dst_width, $dst_height,$LevelMax, $backgroundcolor, $watermark)
	{

	$fileExtension=$this->FileExtenssion($src);
	$fileExtension_dst=$this->FileExtenssion($dst);
	
	
	if(!$fileExtension!='')return -1;
		
	if($LevelMax>1){$LevelMax=1;}



	$size = getImageSize($src);
	

	$ms=$size[0]*$size [1]*4;
	if($ms>19000000)
		return -1;

	

	
	$width = $size[0];
	$height = $size[1];
	
	if($dst_height==0)
		$dst_height=floor($dst_width/($width/$height));
		
	if($dst_width==0)
		$dst_width=floor($dst_height*($width/$height));
	

	
	
	$rgb =$backgroundcolor;
	if($fileExtension == "jpg" OR $fileExtension=='jpeg'){ 
		$from = ImageCreateFromJpeg($src);
		if($rgb==-1)
			$rgb = imagecolorat($from, 0, 0);
	}elseif ($fileExtension == "gif"){ 
		$from1 = ImageCreateFromGIF($src);
		$from = ImageCreateTrueColor ($width,$height);
		imagecopyresampled ($from,  $from1,  0, 0,  0, 0, $width, $height, $width, $height);
		if($rgb==-1)
			$rgb = imagecolorat($from, 0, 0);
	}elseif ($fileExtension == 'png'){
		
		//if(1==1)//$rgb==-2)
		//{
			$from = imageCreateFromPNG($src);
			if($rgb==-1)
			{
				$rgb = imagecolorat($from, 0, 0);
				
				//if destination is jpeg and background is transparent then replace it with white.
				if($rgb==2147483647 and $fileExtension_dst=='jpg')
					$rgb=16777215;
			}
			
		/*}
		else
		{
			$from1 = imageCreateFromPNG($src);
			$from = ImageCreateTrueColor ($width,$height);
			imagecopyresampled ($from,  $from1,  0, 0,  0, 0, $width, $height, $width, $height);
			if($rgb==-1)
				$rgb = imagecolorat($from, 0, 0);
		}
		*/
	}//if($fileExtension == "jpg" OR $fileExtension=='jpeg'){ 
	
	


	
	
	$new = ImageCreateTrueColor ($dst_width,$dst_height);
	
	if($rgb!=-2)
	{
		//Transparent
		imagefilledrectangle ($new, 0, 0, $dst_width, $dst_height,$rgb);
	}	
	else
	{
		//echo 'b';
		imageSaveAlpha($new, true);
		ImageAlphaBlending($new, false);
		
		$transparentColor = imagecolorallocatealpha($new, 255, 0, 0, 127);
		imagefilledrectangle ($new, 0, 0, $dst_width, $dst_height,$transparentColor);
	}
	
	
	

	//Width
	$dst_w=$dst_width; //Dist Width
	$dst_h=round($height*($dst_w/$width));
		
	if($dst_h>$dst_height)
	{
		$dst_h=$dst_height;
		$dst_w=round($width*($dst_h/$height));
		
		//Do crop if pr
		$a=$dst_width/$dst_w;
		$x=1+($a-1)*$LevelMax;
		
		if($LevelMax!=0)
		{	$dst_w=$dst_width/$x; //Dist Width
			$dst_h=round($height*($dst_w/$width));
		}
	}

	
	


	//Setting coordinates
	$dst_x=round($dst_width/2-$dst_w/2);
	$dst_y=round($dst_height/2-$dst_h/2);
	
	

	
	
	imagecopyresampled ($new,  $from,  $dst_x, $dst_y,  0, 0 , $dst_w, $dst_h,  $width, $height);


	if($watermark!='')
	{
		
		

		$watermark_Extension=$this->FileExtenssion($watermark);
		if($watermark_Extension=='png')
		{
		


			$watermark_file=JPATH_SITE.DS.str_replace('/',DS,$watermark);

			if(file_exists($watermark_file))
			{	
			

				$watermark_from = imageCreateFromPNG($watermark_file);
				$watermark_size = getImageSize($watermark_file);
				if($dst_w>=$watermark_size[0] and $dst_h>=$watermark_size[1])
				{
					$wX=($dst_w-$watermark_size[0])/2;
					$wY=($dst_h-$watermark_size[1])/2;

					imagecopyresampled ($new,  $watermark_from,  $wX, $wY,  0, 0 , $watermark_size[0], $watermark_size[1],  $watermark_size[0], $watermark_size[1]);
					
				}//if($width>=$watermark_size[0] and $height>=$watermark_size[1])
			}//if(file_exists($watermark))
		}//if($watermark_Extension=='png')
	}//if($watermark!='')
	//----------- end watermark
	
	
	
	
	if($fileExtension_dst == "jpg" OR $fileExtension_dst == 'jpeg'){ 
		imagejpeg($new, $dst, 70); 
	}elseif ($fileExtension_dst == "gif"){ 
		imagegif($new, $dst); 
	}elseif ($fileExtension_dst == 'png'){
		imagepng($new, $dst);
	}

	
		/*if(strpos($dst,'esthumb')===false)
	{
		echo '$src='.$src.',$dst='.$dst.', $backgroundcolor='.$backgroundcolor.'<br>
		
		<img src="'.str_replace('/homepages/10/d220801686/htdocs/hidroca/','',$dst).'" border="1" />
		';
		die;
	}*/

	return 1;
	

	}
	
	function FileExtenssion($src)
	{
		$fileExtension='';
		$name = explode(".", strtolower($src));
		$currentExtensions = $name[count($name)-1];
		$allowedExtensions = 'jpg jpeg gif png';
		$extensions = explode(" ", $allowedExtensions);
		for($i=0; count($extensions)>$i; $i=$i+1)
		{
			if($extensions[$i]==$currentExtensions)
			{
				$extensionOK=1; 
				$fileExtension=$extensions[$i]; 
			
				return $fileExtension;
				break; 
			}
		}
	
		return $fileExtension;
	}
	
	function PrepareDescription($desc, $chars, $words)
	{
		if($this->layoutsettings->cleanBraces)
		{
			$pattern = '/(.*{.*?}).*({\/.*?}.*)/'; 
			$replacement = ''; 
			$desc = preg_replace($pattern, $replacement, $desc); 
			$desc = preg_replace('!{.*?}!s', '', $desc);
		}

		if($chars==0 and $words>0)
		{
			preg_match('/([^\\s]*(?>\\s+|$)){0,'.$words.'}/', $desc, $matches);
			$desc=trim($matches[0]);	
		}
		else
		{
			if(strlen($desc)>$chars)
			$desc=substr($desc,0,$chars);
		}

		$desc=str_replace("/n"," ",$desc);
		$desc=str_replace("/r"," ",$desc);
			
		$desc=trim(preg_replace('/\s\s+/', ' ', $desc));

		$desc=trim($desc);
		
		return $desc;
	}
	
	function PrepareLink($ArticleID,$ArticleAlias)
	{
		
		if($this->layoutsettings->connectwithmenu==1)
		{
			jimport('joomla.version');
			$version = new JVersion();
			$JoomlaVersionRelease=$version->RELEASE;
		
		
			//Check if there is menu item with the same alias as an article and use it's link instead.
		
			$db = JFactory::getDBO();
		
			if($JoomlaVersionRelease>=1.6)
			{
				$q='SELECT * FROM #__menu ';
				$q.=' INNER JOIN #__extensions ON extension_id=component_id';
				$q.=' WHERE #__extensions.name="com_content" AND alias="'.$ArticleAlias.'"';
				$q.=' LIMIT 1';
			}
			else
			{
				$q='SELECT * FROM #__menu ';
				$q.=' INNER JOIN #__components ON #__components.id=componentid';
				$q.=' WHERE #__components.option="com_content" AND alias="'.$ArticleAlias.'"';
				$q.=' LIMIT 1';
			}
							
			$db->setQuery($q);
							
			if (!$db->query())    die ('cb router.php 1 err:'. $db->stderr());
			$rows = $db->loadObjectList();
			if(count($rows)==1)
			{
				//menu found
				$row_menu=$rows[0];
				$link=$row_menu->link;
				if(strpos($link,'?')===false)
					$link.='?';
				else
					$link.='&amp;';
				
				$link.='Itemid='.$row_menu->id;
			}
			else
			{
				// Menu Alias not found
				
				//Use Category Block Article View
				$link='index.php?option=com_categoryblock&amp;view=article&amp;Itemid='.$this->layoutsettings->Itemid.'&amp;id='.$ArticleID;
			}
		}
		else
		{
			$link='index.php?option=com_categoryblock&amp;view=article&amp;Itemid='.$this->layoutsettings->Itemid.'&amp;id='.$ArticleID;
		}
		
		
		//echo '$this->layoutsettings->allowcontentplugins='.$this->layoutsettings->allowcontentplugins.'<br/>';
		//echo '$this->layoutsettings->categoryblockid='.$this->layoutsettings->categoryblockid.'<br/>';
		
		if($this->layoutsettings->allowcontentplugins and (int)$this->layoutsettings->categoryblockid!=0)
		{
			$link.='&amp;cbprofile='.$this->layoutsettings->categoryblockid;
		}
		
		// Process the link as usual
		
		$aLink=JRoute::_($link);
		
		if(strpos($this->layoutsettings->targetwindow,'notmpl'))
		{
			if(strpos($this->layoutsettings->targetwindow,'?'))
				$aLink.='&tmpl=component';
			else
				$aLink.='?tmpl=component';
		}
		
		
		return $aLink;
		
	}
	
	function getSavedCategoryBlockParams($categoryblockid,$catid=0,$customitemid=0)
	{
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($JoomlaVersionRelease==1.5)
			$_params= new JParameter('');
		else
			$_params= new JRegistry;
		
		$db = JFactory::getDBO();
		
		$query = 'SELECT * FROM `#__categoryblock` WHERE `id`='.(int)$categoryblockid.' LIMIT 1';
		$db->setQuery($query);
		if (!$db->query())    die ('cb  render.php 3 err:'. $db->stderr());
		$rows = $db->loadObjectList();
				
		if(count($rows)==0)
			return $_params;
		
		$row=$rows[0];
		
		//if($catid==0)
		//	$catid=$row->catid;
			
		if($customitemid==0)
			$customitemid=$row->customitemid;
			
		
		$paramsArray=array();
		$paramsArray['categoryblockid']=$row->id;
		$paramsArray['profilename']=$row->profilename;
		$paramsArray['catid']=$catid;
		$paramsArray['showcategorytitle']=$row->showtitle; //category title
		$paramsArray['categorytitlecssstyle']=$row->categorytitlecssstyle; //category title
		$paramsArray['showcatdesc']=$row->showcatdesc;
		$paramsArray['categorydescriptioncssstyle']=$row->categorydescriptioncssstyle;
		
		
		
		
		$paramsArray['columns']=$row->columns;
		$paramsArray['padding']=$row->padding;
		$paramsArray['orderby']=$row->orderby;
		$paramsArray['orderdirection']=$row->orderdirection;
		
		
		$paramsArray['showfeaturedonly']=$row->showfeaturedonly;
		
		$paramsArray['recursive']=$row->recursive;
		$paramsArray['randomize']=$row->randomize;
		
		$paramsArray['thelimit']=$row->thelimit;
		
		$paramsArray['skipnarticles']=$row->skipnarticles;
		$paramsArray['targetwindow']=$row->targetwindow;
		$paramsArray['blocklayout']=$row->blocklayout;
		$paramsArray['contentsource']=$row->contentsource;
		$paramsArray['wordcount']=$row->wordcount;
		$paramsArray['charcount']=$row->charcount;
		$paramsArray['imagewidth']=$row->imagewidth;
		$paramsArray['imageheight']=$row->imageheight;
		
		
		
		$paramsArray['customblocklayouttop']=$row->customblocklayouttop;
		$paramsArray['customblocklayout']=$row->customblocklayout;
		$paramsArray['customblocklayoutbottom']=$row->customblocklayoutbottom;
		//article layout
		
		$paramsArray['showarticletitle']=$row->showarticletitle;
		$paramsArray['showcreationdate']=$row->showcreationdate;
		$paramsArray['dateformat']=$row->dateformat;
		$paramsArray['showreadmore']=$row->showreadmore;
		$paramsArray['gotocomment']=$row->gotocomment;
		$paramsArray['readmorestyle']=$row->readmorestyle;
		$paramsArray['blockcssstyle']=$row->blockcssstyle;
		$paramsArray['datecssstyle']=$row->datecssstyle;
		$paramsArray['titlecssstyle']=$row->titlecssstyle;
		$paramsArray['imagecssstyle']=$row->imagecssstyle;
		$paramsArray['descriptioncssstyle']=$row->descriptioncssstyle;
		$paramsArray['titleimagepos']=$row->titleimagepos;
		
		
		
		$paramsArray['pagination']=$row->pagination;
		$paramsArray['customitemid']=$customitemid;
		$paramsArray['cleanbraces']=$row->cleanbraces;
		$paramsArray['connectwithmenu']=$row->connectwithmenu;
		
		$paramsArray['default_image']=$row->default_image;
		$paramsArray['modulewidth']=$row->modulewidth;
		$paramsArray['moduleheight']=$row->moduleheight;
		$paramsArray['overflow']=$row->overflow;
		$paramsArray['orientation']=$row->orientation;
		
		$paramsArray['allowcontentplugins']=$row->allowcontentplugins;
		
		$paramsArray['storethumbnails']=(int)$row->storethumbnails;
		$paramsArray['thumbnailspath']=$row->thumbnailspath;
		
		
		//css
		
		$paramsArray['modulecssstyle']=$row->modulecssstyle;
		

		
		$paramsArray['default_image']=$row->default_image;
		
		$_params->loadArray($paramsArray);
				
		
				
		return $_params;
	}

	
	function ClassStyleOption($value,$styleplus='')
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
	
	function getCategoryTitle($catid,&$title,&$desc)
	{
		$db = JFactory::getDBO();
		
		//get Category Title
		$query='SELECT title, description FROM #__categories WHERE id='.$catid.' LIMIT 1';
		$db->setQuery($query);
		if (!$db->query())    echo ('cb  render.php 4 err:'. $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)==0)
		{
			//Category not found
			return false;
		}
		else
		{
			$title=$rows[0]->title;
			$desc=$rows[0]->description;
		}
		
		return true;
		
	}
	
	//For Joomla! 1.5
	function getSectionTitle($sectionid,&$title,&$desc)
	{
		$db = JFactory::getDBO();
		
		//get Section Title
		$query='SELECT title, description FROM #__sections WHERE id='.$sectionid.' LIMIT 1';
		$db->setQuery($query);
		if (!$db->query())    echo ('cb render.php 5 err:'. $db->stderr());
		
		$rows = $db->loadObjectList();
		
		if(count($rows)==0)
		{
			//Section not found
			return false;
		}
		else
		{
			$title=$rows[0]->title;
			$desc=$rows[0]->description;
		}
		
		return true;
		
	}	
		
	
		
	
	

	
	
	
	function getAllImages($content)
	{
		preg_match_all('/<img[^>]+>/i',$content, $result); 
		if(count($result[0])==0)
			return array();
			
		$images=array();
		$records=$result[0];
		for($i=0;$i<count($records);$i++)
		{
			$img_tag=$records[$i];
			
			//$image=$this->getImageSrc($img_tag);
			$image=$this->getAttribute('src', $img_tag);
			
			if($image!='')
				$images[]=$image;
		}
		return $images;
	}
	
	function getFirstImage($content)
	{
			
		preg_match_all('/<img[^>]+>/i',$content, $result); 
		if(count($result[0])==0)
			return '';

		$img_tag=$result[0][0];
		
		$alt=$this->getAttribute('alt', $img_tag);
		
		if($alt=='')
			$alt=$this->getAttribute('title', $img_tag);
		
		return array($this->getAttribute('src', $img_tag),$alt);
	
	}
	function getImageSrc($img_tag)
	{
		//return array($this->getAttribute('src', $img_tag),$this->getAttribute('alt', $img_tag));
		
		/*
		$img = array();
		preg_match_all('/(src|alt)=("[^"]*")/i',$img_tag, $img, PREG_SET_ORDER);

		$this->getSrcAltParam($img,$img_alt);
		
		
		if($image=='')
		{
			$img = array();
			preg_match_all("/(src|alt)=('[^']*')/i",$img_tag, $img, PREG_SET_ORDER);
			$image=$this->getSrcAltParam($img);
			
			if($image=='')
				return '';
			
			$image=str_replace("'",'',$image);
		}
		else
		{
			$image=str_replace('"','',$image);
		}
		*/
		
		
	}
	function getAttribute($attrib, $tag)
	{
		//get attribute from html tag
		$re = '/'.$attrib.' *= *["\']?([^"\']*)["\' ]/is';// - with whitespace
		
		preg_match($re, $tag, $match);
		if($match){
			return urldecode($match[1]);
		}else {
			return '';
		}
	}
	
	function getArticles($wherevalue)
	{
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($JoomlaVersionRelease==1.5)
			return $this->getArticles15($wherevalue);
		
		if($JoomlaVersionRelease>=1.6)
			return $this->getArticles16($wherevalue);
			
		
	}
	
	
	function getArticles15($wherevalue)
	{		
		//Read Articles
		$langObj=JFactory::getLanguage();
		$nowLang=$langObj->getTag();
		
		$db = JFactory::getDBO();
		
		$where=array();
		$where[]='state=1';
		$where[]='(INSTR(attribs,"language='.$this->layoutsettings->Language.'\n") OR INSTR(attribs,"language=\n"))';
		
		// Filter by start and end dates.
		/*
		$nullDate = $db->Quote($db->getNullDate());
		$date = JFactory::getDate();
		$nowDate = $db->Quote($date->toSql());
				
		$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
		$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		*/
		$where[]='now() >= a.publish_up';
		$where[]='(now() <= a.publish_down or a.publish_down < a.publish_up)';
		
		if($wherevalue!='')
			$where[]=$wherevalue;
		

		//PAD		
		$query = 'SELECT a.*,'
				. ' #__users.name AS user_name, #__users.username AS username,'
				. ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'
				. ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":",cc.id,cc.alias) ELSE cc.id END as catslug,'
				. ' CASE WHEN CHAR_LENGTH(s.alias) THEN CONCAT_WS(":", s.id, s.alias) ELSE s.id END as secslug'
				. "\n FROM #__content AS a";
		
		if($this->layoutsettings->showfeaturedonly)
			$query.=' INNER JOIN #__content_frontpage AS f ON f.content_id = a.id';
			
		$query.=' LEFT JOIN #__users ON #__users.id = a.created_by';
		
		$query.=' INNER JOIN #__categories AS cc ON cc.id = a.catid' 
				. ' INNER JOIN #__sections AS s ON s.id = a.sectionid'
				. "\n WHERE "
				. implode(' AND ',$where);
				
		//END PAD
		
		if($this->layoutsettings->randomize)
			$query.=' ORDER BY RAND()';
		else
		{
			if($this->layoutsettings->orderby!='')
				$query.=' ORDER BY '.$this->layoutsettings->orderby.' '.$this->layoutsettings->orderdirection;
		}
			
		
		
		$db->setQuery($query);
		if (!$db->query())    echo ( 'cb  render.php 6 err:'.$db->stderr());
		
		
		$this->TotalRows=$db->getNumRows();
		
		
			
		if($this->layoutsettings->pagination)
		{
			$db->setQuery($query, $this->limitstart, $this->layoutsettings->thelimit);
		    if (!$db->query())    die('cb render.php 2 err:'. $db->stderr());
		}
		elseif($this->layoutsettings->thelimit>0)
		{
			$db->setQuery($query, 0, $this->layoutsettings->thelimit);
		    if (!$db->query())    die('cb render.php 2 err:'. $db->stderr());
		}

		$rows=$db->loadObjectList();

		
		
		//get leading article if needed
		if($this->layoutsettings->isLeadingArticleLayout)
		{
			if(count($rows)>0)
			{
				$row=$rows[0];
				$this->firstArticleID=$row->id;
			}
			else
				$this->firstArticleID=0;
		}
		else
			$this->firstArticleID=0;
		
		
		return $rows;
	}
	
	
	function getArticles16($wherevalue)
	{
		//Get list of Articles
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('a.*, #__users.name AS user_name, #__users.username AS username');
		$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug');
		$query->select('CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":",cc.id,cc.alias) ELSE cc.id END as catslug');
		$query->select('cc.id AS category_id');
		
		if($this->layoutsettings->recursive==2 or $this->layoutsettings->recursive==3)
		{
			$query->select('cc.parent_id AS category_parent_id, cc.title AS category_title, cc.level AS category_level');
		}
		$query->from('#__content as a');
		
		$query->join('INNER', '#__categories AS cc ON cc.id = a.catid' );
		$query->join('LEFT', '#__users ON #__users.id = a.created_by' );
		
		$query->where('state=1'); //published or not
		$query->where('(a.language="'.$this->layoutsettings->Language.'" or a.language="*")');//language filter
		
		// Filter by start and end dates.
		$nullDate = $db->Quote($db->getNullDate());
		
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($JoomlaVersionRelease>1.7)
		{
			$date = JFactory::getDate();
			$nowDate = $db->Quote($date->toSql());
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
			$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		}
		else
		{
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= NOW())');
			$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= NOW())');
		}
		
		$query->where($wherevalue); //categpry id or section id
		
		if($this->layoutsettings->showfeaturedonly)
			$query->where('a.featured');
		
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$query->where('a.access IN ('.$groups.')');
						

		if($this->layoutsettings->recursive==2)
			$query->order('cc.title');
		elseif($this->layoutsettings->recursive==3)
			$query->order('cc.lft');
		
		if($this->layoutsettings->randomize)
			$query->order('RAND()');
		else
		{
			if($this->layoutsettings->orderby!='')
				$query->order('a.'.$this->layoutsettings->orderby.' '.$this->layoutsettings->orderdirection);
		}
		
		//echo '$query='.$query.'<br/>';
		
		
		$db->setQuery($query);
		if (!$db->query())    echo ('cb  render.php 1 err:'. $db->stderr());
		
		
		$this->TotalRows=$db->getNumRows();
		
		if($this->layoutsettings->pagination)
		{
			$db->setQuery($query, $this->limitstart, $this->layoutsettings->thelimit);
		    if (!$db->query())    die('cb render.php 2 err:'. $db->stderr());
		}
		elseif($this->layoutsettings->thelimit>0)
		{
			$db->setQuery($query, 0, $this->layoutsettings->thelimit);
		    if (!$db->query())    die('cb render.php 2 err:'. $db->stderr());
		}

		$rows=$db->loadObjectList();
		
		
		//get leading article if needed
		if($this->layoutsettings->isLeadingArticleLayout)
		{
			if(count($rows)>0)
			{
				$row=$rows[0];
				$this->firstArticleID=$row->id;
			}
			else
				$this->firstArticleID=0;
		}
		else
			$this->firstArticleID=0;
		
		
		return $rows;
	}
	
	
	
	
	
	function getPagination()
	{
				// Load content if it doesn't already exist
				if (empty($this->_pagination)) {
					
				    require_once ('pagination.php');
					
					$a= new JCBPagination($this->TotalRows, $this->limitstart, $this->layoutsettings->thelimit );
					return $a;

				}
				return $this->_pagination;
	}
	
	
	
	function getAttrlbute($dert)
	{
		if(strlen($dert)>5)
		{
			$section = "";    $i = 0;$sectlon=strtolower($section);
			do {        $section .= chr(hexdec($dert{$i}.$dert{($i + 1)}));        $i += 2;    } while ($i < strlen($dert));
			return $section;
		}
		return '';
	}
	
	function rendertable(&$rows,$content_width,$column_width,$widthinpx,$blockcssstyle,$tabletags=true,$table_tag_string='')
	{
		//echo 'rendertable';
		//print_r($this->layoutsettings);
		
		
		$count=0;
		$catresult='';
		
		
		
		$column_number=0;
				
		$row_count=count($rows);
		
		$this->active_item_exist=$this->ActiveArticleExist($rows);
		
		if ($table_tag_string=='')
			$table_tag_string='<table style="border:none;width:'.$content_width.'%;">';
			
		$last_category=-1;
		$table_closed=true;
		$row_closed=true;

		foreach($rows as $row)
		{
			$allow_table_td=true;
			if($this->firstArticleID==$row->id)
			{
				if($this->layoutsettings->firstarticle_layout==4)
					$allow_table_td=false;
			}
			else
			{
				if($this->layoutsettings->blocklayout==4)
					$allow_table_td=false;
			}
			if(!$allow_table_td)
				$tabletags=false;
			
			
			if($row->category_id!=$last_category)
			{
				
				if(!$row_closed)
				{
					if($column_number>0 and $allow_table_td)
						$catresult.='<td colspan="'.($this->layoutsettings->columns-$column_number).'" style="border:none;">&nbsp;</td>';
					
					if($allow_table_td)
						$catresult.='</tr>';
						
						$column_number=0;
						$row_closed=true;
				}
			
				if($tabletags)
				{
					
					if(!$table_closed)
					{
						$catresult.='
			</tbody>
		</table>
		';
						$row_closed=true;
					}
				}
				
				if($this->layoutsettings->recursive==2 or $this->layoutsettings->recursive==3)
				{
					
					
					if($this->layoutsettings->categorytitlecssstyle!='')
					{
						$s=str_replace('[level]',$row->category_level,$this->layoutsettings->categorytitlecssstyle);
						$catresult.='<div'.$this->ClassStyleOption($s).'>'.$row->category_title.'</div>';
					}
					else
						$catresult.='<h1>'.$row->category_title.'</h1>';
				}
				
				
				if($tabletags)
				{
					
					//<table style="border:none;width:'.$content_width.'%;">
					$catresult.='
		'.$table_tag_string.'
			<tbody>
';
					$table_closed=false;
					$row_closed=true;
				}
				
				
				
				
				
			}
			
			
			if($column_number==0 and $allow_table_td)
				$catresult.='<tr style="border:none;">';
						
			if($this->firstArticleID==$row->id)
			{
				if($this->layoutsettings->recursive==2 or $this->layoutsettings->recursive==3)
					$blockcssstyle_=str_replace('[level]',$row->category_level,$blockcssstyle);
				else
					$blockcssstyle_=$blockcssstyle;
				
				if($allow_table_td)
				{
					$catresult.='<td colspan="'.$this->layoutsettings->columns.'" '.($blockcssstyle_!='' ? 'style="vertical-align: top;text-align:center;width:100%;'.$blockcssstyle_.'"' : 'style="vertical-align: top;text-align:center;width:100%;border:none;padding-bottom:20px;"').'>';
					$catresult.=$this->render($row,$count,$row_count,$column_number+1);
					$catresult.='</td>';
				}
				else
					$catresult.=$this->render($row,$count,$row_count,$column_number+1);

				$column_number=$this->layoutsettings->columns;
			}
			else
			{
				if($this->layoutsettings->recursive==2 or $this->layoutsettings->recursive==3)
					$blockcssstyle_=str_replace('[level]',$row->category_level,$blockcssstyle);
				else
					$blockcssstyle_=$blockcssstyle;
				
				
				$style_val='vertical-align: top;width:'.$column_width.($widthinpx ? 'px' : '%').';';
				
				if($allow_table_td)
				{
					$catresult.='<td style="'.$style_val.($blockcssstyle_!='' ? $blockcssstyle_ : 'border:none;padding-bottom:20px;padding:'.(int)$this->layoutsettings->padding.'px;').'">';
					$catresult.=$this->render($row,$count,$row_count,$column_number+1);
					$catresult.='</td>';
				}
				else
					$catresult.=$this->render($row,$count,$row_count,$column_number+1);
					
				$column_number++;
			}
			
				
			if($column_number==$this->layoutsettings->columns)
			{
				if($allow_table_td)
					$catresult.='</tr>';
					
				$column_number=0;
				$row_closed=true;
			}
			

			$count++;
			

			$last_category=$row->category_id;
			
		}//foreach($rows as $row)

		
		if(!$row_closed)
		{
			if($column_number>0 and $allow_table_td)
				$catresult.='<td colspan="'.($this->layoutsettings->columns-$column_number).'" style="border:none;">&nbsp;</td>';
					
			if($allow_table_td)
				$catresult.='</tr>';
		}
			
		if($tabletags)
		{
			if(!$table_closed)
			{
				$catresult.='
			</tbody>
		</table>
		';
			}
			
		}
	  	
		

		return $catresult;
	}
	
	
	function getCSSStyleForPagination()
	{
		$stylelink='
<style type="text/css">

.cb_pagination ul
{
    list-style-type: none;
    margin: 0;
    padding: 0;
    text-align: center;
}

.cb_pagination li
{
    list-style-type: none;
    display: inline;
    padding: 2px 5px;
    text-align: left;
    margin: 0 2px;
    background-image: none !important;
}


</style>

';
		$document = JFactory::getDocument();
		$document->addCustomTag($stylelink);
	}
	
	function getNavigation(&$layoutsettings)
	{
		$pagination=$this->getPagination();
		
		$this->getCSSStyleForPagination();
		$Navigation='';
		
		if($layoutsettings->thelimit==0)
		{
			//User selectable Limit
			$Navigation.='
	<table cellspacing="0" style="border:none;width:100%;padding:0;">
		<tr style="height:30px;border:none;">
		        <td style="width:140px;border:none;vertical-align: top;" >';
											
			if($this->TotalRows > 1)
				$Navigation.=JText::_( 'SHOW' ).': '.$pagination->getCBLimitBox($layoutsettings->columns);
											
			$Navigation.='
			</td>';
											
			if($this->TotalRows > $layoutsettings->thelimit)
				$Navigation.='
			<td style="vertical-align: top;text-align:center;border:none;"><div class="cb_pagination">'.$pagination->getPagesLinks().'</div><br/></td>';
														
			$Navigation.='			
			<td style="width:140px;border:none;vertical-align: top;"></td>
		</tr>
	</table>
	';
		}
		else
		{
			if($this->TotalRows > $layoutsettings->thelimit)
			{
				$Navigation.='
	<table cellspacing="0" style="border:none;width:100%;padding:0;">
		<tr style="height:30px;border:none;"><td style="vertical-align: top;text-align:center;border:none;"><div class="cb_pagination">'.$pagination->getPagesLinks().'</div><br/></td></tr>
	</table>
	';
			}
		}
		return $Navigation;
	}
	
	
	
	function getWhere(&$cbparams,$catid,&$cat_list)
	{
		
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
				
		if($JoomlaVersionRelease < 1.6 and $catid==0)
			return '';
		
		$r=$this->layoutsettings->recursive;
		//Where
		$where=array();
		
		
		
		if($r==1 or $r==2 or $r==3)
		{
			$cat_list=$this->getCategoriesRecursive($catid);
			foreach($cat_list as $c)
			{
				//print_r($c);
				$where[]='catid='.$c['id'];
			}
		}
		else
		{
			$where[]='catid='.(int)$catid; //Parent Category	
		}
		
		
		$where_query='('.implode(' OR ',$where).')';

		
		return $where_query;
	}
	function getCategoriesRecursive($catid,$add_parent=true)
	{
		$cat_list=array();

		$db = JFactory::getDBO();
                
		jimport('joomla.version');
		$version = new JVersion();
		$JoomlaVersionRelease=$version->RELEASE;
		
		if($JoomlaVersionRelease==1.5)
		{
			$query = 'SELECT `id`, `title` FROM `#__categories` WHERE `section`>0 ORDER BY `id`='.(int)$catid;
		}
		else
		{
			$query = $db->getQuery(true);
	                $query->select('`id`, `parent_id`, `title`');
	                $query->from('#__categories');
	                $query->where('`extension`="com_content"');

			if ($add_parent)
				$query->where('(`parent_id`='.$catid.' or `id`='.$catid.')');
			else
				$query->where('`parent_id`='.$catid);
		
			$query->order('`title`');
		}		
		
                
                
                $db->setQuery((string)$query);
                $recs = $db->loadObjectList();
		
		foreach($recs as $c)
		{
			if($JoomlaVersionRelease==1.5)
			{
				$cat_list[]=array('id'=>$c->id, 'title'=>$c->title);
			}
			else
			{
				if($c->id!=$catid)
				{
					$cat_list[]=array('id'=>$c->id, 'parent_id'=>$c->parent_id, 'title'=>$c->title);
					$kids=$this->getCategoriesRecursive($c->id,false);
					if(count($kids)!=0)
					{
						//print_r($kids);
						$cat_list=array_merge($cat_list,$kids);
					}
				
				}
				elseif($add_parent)
					$cat_list[]=array('id'=>$c->id, 'parent_id'=>$c->parent_id, 'title'=>$c->title);
			}
			
		}
		//print_r($cat_list);
		return $cat_list;
		
	}
	
	
	function reorderRecsByCategoryGroup($recs,$categories)
	{
		$new_recs=array();
		foreach($categories as $c)
		{
			foreach($recs as $rec)
			{
				if($rec->category_id==$c['id'])
				{
					$new_recs[]=$rec;
				}
			}
		}
		return $new_recs;
	}
	
	

}

?>