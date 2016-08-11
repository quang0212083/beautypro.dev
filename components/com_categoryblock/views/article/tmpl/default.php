<?php
/**
 * @version		$Id: default.php 21518 2012-06-10 21:38:12Z chdemko $
 * @package		Joomla.Site
 * @subpackage	com_content -> com_categoryblock
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * Used with Category Block
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');



// Create shortcuts to some parameters.
$params		= $this->item->params;
//$app	= JFactory::getApplication();
//$params_	= $app->getParams();

//$app = JFactory::getApplication('site');
//$params_ = $app->getParams();

$canEdit	= $this->item->params->get('access-edit');
$user		= JFactory::getUser();



$mainframe = JFactory::getApplication();
$sitename =$mainframe->getCfg('sitename');
 
$mydoc = JFactory::getDocument();
 
$mydoc->setTitle($this->item->title.' - '.$sitename);

$result='';

$result.='<div class="'.$params->get('article_class').'">';
	
	

if ($params->get('show_title'))
{
	$result.='<h2>';
	if ($params->get('link_titles') && !empty($this->item->readmore_link))
	{
		$result.='<a href="'.$this->item->readmore_link.'">';
		$result.=$this->escape($this->item->title).'</a>';
	}
	else
	{
		$result.=$this->escape($this->item->title);
	}
	$result.='</h2>';
}


if ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon')  || $params->get('edit_icon')  || $this->print)
{


	$result.='<div class="'.$params->get('article_icons_class').'">';
	
	$result.='<ul class="actions">';
	if (!$this->print)
	{
		if ($params->get('show_print_icon'))
		{
			$result.='<li class="print-icon">';
			$result.=cb_print_popup($this->item, $params);
			$result.='</li>';
		}
		
		if ($params->get('show_email_icon'))
		{
			$result.='<li class="email-icon">';
			$result.=cb_email($this->item, $params);
			$result.='</li>';
		}
		
	
		if ($canEdit)
		{
			$result.='<li class="edit-icon">';
			$result.=cb_edit($this->item, $params);
			$result.='</li>';
		}
	}
	else
	{
		$result.='<li>';
		$result.=cb_print_screen($this->item, $params);
		$result.='</li>';
	}

	$result.='</ul>';
	
	
	$result.='</div>';
}



if (!$params->get('show_intro'))
	$result.=$this->item->event->afterDisplayTitle;


echo $this->item->event->beforeDisplayContent;



$useDefList = (($params->get('show_author')) OR ($params->get('show_category')) OR ($params->get('show_parent_category'))
	OR ($params->get('show_create_date')) OR ($params->get('show_modify_date')) OR ($params->get('show_publish_date'))
	OR ($params->get('show_hits')));

if ($useDefList)
	$result.='<dl class="article-info">';
	


if ($params->get('show_create_date'))
{
	$result.='<dd class="create">';
	$result.=JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2')));
	$result.='</dd>';
}

if ($params->get('show_modify_date'))
{
	$result.='<dd class="modified">';
	$result.=JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2')));
	$result.='</dd>';
}

if ($params->get('show_publish_date'))
{
	$result.='<dd class="published">';
	$result.=JText::sprintf('COM_CONTENT_PUBLISHED_DATE', JHtml::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC2')));
	$result.='</dd>';
}

if ($params->get('show_author') && !empty($this->item->author ))
{
	$result.='<dd class="createdby">';
	$author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
	if (!empty($this->item->contactid) && $params->get('link_author') == true)
	{

		$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
		$item = JSite::getMenu()->getItems('link', $needle, true);
		$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
	
		$result.=JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author));
	}
	else
	{
		$result.=JText::sprintf('COM_CONTENT_WRITTEN_BY', $author);
	}
	$result.='</dd>';
}

if ($params->get('show_hits'))
{
	$result.='<dd class="hits">';
	$result.=JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits);
	$result.='</dd>';
}

if ($useDefList)
	$result.='</dl>';


if (isset ($this->item->toc))
	$result.=$this->item->toc;


//To remove page navigation buttons added by plugin.
$p=strpos($this->item->text,'<ul class="pagenav">');
if(!($p===false))
{
	$p2=strpos($this->item->text,'</ul>',$p);
	if(!($p2===false))
		$this->item->text=substr($this->item->text,0,$p).substr($this->item->text,$p2+5);
}


if ($params->get('access-view'))
{


	$result.=$this->item->text;

	//optional teaser intro text for guests
}	
elseif($params->get('show_noauth') == true AND  $user->get('guest') )
{
	$result.=$this->item->introtext;
	//Optional link to let them register to see the whole article.
	if ($params->get('show_readmore') && $this->item->fulltext != null)
	{
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JURI($link1);
		$result.='<p class="readmore">';
		$result.='<a href="'.$link.'">';
		$attribs = json_decode($this->item->attribs);
		
		if ($attribs->alternative_readmore == null)
		{
			$result.=JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		}
		elseif ($readmore = $this->item->alternative_readmore)
		{
			$result.=$readmore;
			if ($params->get('show_readmore_title', 0) != 0)
			{
			    $result.=JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			}
			
		}
		elseif ($params->get('show_readmore_title', 0) == 0)
		{
			$result.=JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
		}
		else
		{
			$result.=JText::_('COM_CONTENT_READ_MORE');
			$result.=JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		}
		$result.='</a>';
		$result.='</p>';
	}
}


$result.=$this->item->event->afterDisplayContent;
$result.='</div>';


//------------------------------------------- output

		
		if($params->get( 'allowcontentplugins' ))
		{
			//JRequest::setVar('option','com_content');
			//echo 'Apply Plugins';
			$o = new stdClass();
			$o->text=$result;

			$dispatcher	= JDispatcher::getInstance();

			JPluginHelper::importPlugin('content');

			$r = $dispatcher->trigger('onContentPrepare', array ('com_content.article', &$o, &$params_, 0));
			echo $o->text;
			JRequest::setVar('option','com_categoryblock');
		}
		else
			echo $result;

//-------------------------------------------

		function cb_email($article, $params, $attribs = array())
	{
		require_once(JPATH_SITE . '/components/com_mailto/helpers/mailto.php');
		$uri	= JURI::getInstance();
		$base	= $uri->toString(array('scheme', 'host', 'port'));
		$template = JFactory::getApplication()->getTemplate();
//		$link	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid) , false);
		
		$link=cb_curPageURL();
		
		if(strpos($link,'?'))
			$link .= '&';
		else
			$link .= '?';
				
		
		$url	= 'index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.MailToHelper::addLink($link);

		$status = 'width=400,height=350,menubar=yes,resizable=yes';

		if ($params->get('show_icons')) {
			$text = JHtml::_('image','system/emailButton.png', JText::_('JGLOBAL_EMAIL'), NULL, true);
		} else {
			$text = '&#160;'.JText::_('JGLOBAL_EMAIL');
		}

		$attribs['title']	= JText::_('JGLOBAL_EMAIL');
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";

		$output = JHtml::_('link',JRoute::_($url), $text, $attribs);
		return $output;
	}

	/**
	 * Display an edit icon for the article.
	 *
	 * This icon will not display in a popup window, nor if the article is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param	object	$article	The article in question.
	 * @param	object	$params		The article parameters
	 * @param	array	$attribs	Not used??
	 *
	 * @return	string	The HTML for the article edit icon.
	 * @since	1.6
	 */
	function cb_edit($article, $params, $attribs = array())
	{
		// Initialise variables.
		$user	= JFactory::getUser();
		$userId	= $user->get('id');
		$uri	= JFactory::getURI();

		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return;
		}

		// Ignore if the state is negative (trashed).
		if ($article->state < 0) {
			return;
		}

		JHtml::_('behavior.tooltip');

		// Show checked_out icon if the article is checked out by a different user
		if (property_exists($article, 'checked_out') && property_exists($article, 'checked_out_time') && $article->checked_out > 0 && $article->checked_out != $user->get('id')) {
			$checkoutUser = JFactory::getUser($article->checked_out);
			$button = JHtml::_('image','system/checked_out.png', NULL, NULL, true);
			$date = JHtml::_('date',$article->checked_out_time);
			$tooltip = JText::_('JLIB_HTML_CHECKED_OUT').' :: '.JText::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name).' <br /> '.$date;
			return '<span class="hasTip" title="'.htmlspecialchars($tooltip, ENT_COMPAT, 'UTF-8').'">'.$button.'</span>';
		}

		$url	= 'index.php?option=com_content&task=article.edit&a_id='.$article->id.'&return='.base64_encode($uri);
		$icon	= $article->state ? 'edit.png' : 'edit_unpublished.png';
		$text	= JHtml::_('image','system/'.$icon, JText::_('JGLOBAL_EDIT'), NULL, true);

		if ($article->state == 0) {
			$overlib = JText::_('JUNPUBLISHED');
		}
		else {
			$overlib = JText::_('JPUBLISHED');
		}

		$date = JHtml::_('date',$article->created);
		$author = $article->created_by_alias ? $article->created_by_alias : $article->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= JText::sprintf('COM_CONTENT_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		$button = JHtml::_('link',JRoute::_($url), $text);

		$output = '<span class="hasTip" title="'.JText::_('COM_CONTENT_EDIT_ITEM').' :: '.$overlib.'">'.$button.'</span>';

		return $output;
	}


	function cb_print_popup($article, $params, $attribs = array())
	{
		//$url  = ContentHelperRoute::getArticleRoute($article->slug, $article->catid);
		
		//$WebsiteRoot=JURI::root();
		//if($WebsiteRoot[strlen($WebsiteRoot)-1]!='/') //Root must have slash / in the end
			//$WebsiteRoot.='/';
		
		$url=cb_curPageURL();
		
		if(strpos($url,'?'))
			$url .= '&';
		else
			$url .= '?';
				
		$url .= 'tmpl=component&print=1';//&layout=default&page='.@ $request->limitstart;

		$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		// checks template image directory for image, if non found default are loaded
		if ($params->get('show_icons')) {
			$text = JHtml::_('image','system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
		} else {
			$text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JGLOBAL_PRINT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
		}

		$attribs['title']	= JText::_('JGLOBAL_PRINT');
		$attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
		$attribs['rel']		= 'nofollow';

		//return JHtml::_('link',JRoute::_($url), $text, $attribs);
		return JHtml::_('link',$url, $text, $attribs);
		
	}

	function cb_print_screen($article, $params, $attribs = array())
	{
		// checks template image directory for image, if non found default are loaded
		if ($params->get('show_icons')) {
			$text = JHtml::_('image','system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
		} else {
			$text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JGLOBAL_PRINT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
		}
		return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
	}
	
	function cb_curPageURL()
	{
		$pageURL = '';
		
			$pageURL .= 'http';
		
			if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
				$pageURL .= "://";
				
			if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].(isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '');
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].(isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '');
			}

		
		return $pageURL;
	}
	

?>