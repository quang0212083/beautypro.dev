<?php
/**
* @version 1.0
* @package ContentBuilder Image Scale
* @copyright (C) 2011 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

if(!function_exists('cb_b64enc')){
    
    function cb_b64enc($str){
        $base = 'base';
        $sixty_four = '64_encode';
        return call_user_func($base.$sixty_four, $str);
    }

}

if(!function_exists('cb_b64dec')){
    function cb_b64dec($str){
        $base = 'base';
        $sixty_four = '64_decode';
        return call_user_func($base.$sixty_four, $str);
    }
}

jimport( 'joomla.plugin.plugin' );

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');

class plgContentContentbuilder_download extends JPlugin {

    function __construct( &$subject, $params )
    {
        parent::__construct($subject, $params);
    }
    
    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

    
    /**
     * Joomla 1.5 compatibility
     */
    function onPrepareContent(&$article, &$params, $limitstart = 0, $is_list = false, $form = null, $item = null )
    {
        $this->onContentPrepare('', $article, $params, $limitstart, $is_list, $form, $item);
    }

    function onContentPrepare($context, &$article, &$params, $limitstart = 0, $is_list = false, $form = null, $item = null) {
        
        $protect = false;
        
        $plugin = JPluginHelper::getPlugin('content', 'contentbuilder_download');
        jimport( 'joomla.html.parameter' );
	$pluginParams = CBCompat::getParams($plugin->params);
        
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        
        if(!JFile::exists(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder.php'))
        {
            return true;
        }
        
        $lang = JFactory::getLanguage();
        $lang->load('plg_content_contentbuilder_download', JPATH_ADMINISTRATOR);
        
        /*
         * As of Joomla! 1.6 there is just the text passed if the article data is not passed in article context.
         * (for instance with categories).
         * But we need the article id, so we use the article id flag from content generation.
         */
        if(is_object($article) && !isset($article->id) && !isset($article->cbrecord) && isset($article->text) && $article->text){
            preg_match_all("/<!--\(cbArticleId:(\d{1,})\)-->/si", $article->text, $matched_id);
            if(isset($matched_id[1]) && isset($matched_id[1][0])){
                $article->id = intval($matched_id[1][0]);
            }   
        }
        
        // if this content plugin has been called from within list context
        if($is_list){
            
            if(!trim($article->text)){
                return true;
            }
            
            $article->cbrecord = $form;
            $article->cbrecord->items = array();
            $article->cbrecord->items[0] = $item;
            $article->cbrecord->record_id = $item->colRecord;
            
        }
        
        if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder')) {
            JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder');
        }
        
        if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'index.html', $def = '');
        
        if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins')) {
            JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins');
        }
        
        if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'index.html', $def = '');
        
        if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'download')) {
            JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'download');
        }
        
        if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'download' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'index.html', $def = '');
        
        if(isset($article->id) || isset($article->cbrecord)){
            
            $db = JFactory::getDBO();
            
            $matches = array();
            
            preg_match_all("/\{CBDownload([^}]*)\}/i", $article->text, $matches);
            
            if(isset($matches[0]) && is_array($matches[0]) && isset($matches[1]) && is_array($matches[1])){
                
                $record = null;
                $default_title = '';
                $protect = 0;
                $form_id = 0;
                $record_id = 0;
                $type = '';
                
                $frontend = true;
                if (JFactory::getApplication()->isAdmin()) {
                    $frontend = false;
                }
                
                if (isset($article->id) && $article->id && !isset($article->cbrecord)) {

                    // try to obtain the record id if if this is just an article
                    $db->setQuery("Select form.`title_field`,form.`protect_upload_directory`,form.`reference_id`,article.`record_id`,article.`form_id`,form.`type`,form.`published_only`,form.`own_only`,form.`own_only_fe` From #__contentbuilder_articles As article, #__contentbuilder_forms As form Where form.`published` = 1 And form.id = article.`form_id` And article.`article_id` = " . $article->id);
                    $data = $db->loadAssoc();

                    require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder.php');
                    $form = contentbuilder::getForm($data['type'], $data['reference_id']);
                    if(!$form || !$form->exists){
                        return true;
                    }
                    
                    if ($form) {
                        
                        $protect = $data['protect_upload_directory'];
                        $record = $form->getRecord($data['record_id'], $data['published_only'], $frontend ? ( $data['own_only_fe'] ? JFactory::getUser()->get('id', 0) : -1 ) : ( $data['own_only'] ? JFactory::getUser()->get('id', 0) : -1 ), true );
                        $default_title = $data['title_field'];
                        $form_id = $data['form_id'];
                        $record_id = $data['record_id'];
                        $type = $data['type'];
                    }
                    
                } else if (isset($article->cbrecord) && isset($article->cbrecord->id) && $article->cbrecord->id) {
                    
                    $protect = $article->cbrecord->protect_upload_directory;
                    $record = $article->cbrecord->items;
                    $default_title = $article->cbrecord->title_field;
                    $form_id = $article->cbrecord->id;
                    $record_id = $article->cbrecord->record_id;
                    $type = $article->cbrecord->type;
                    
                }
                
                if(!$is_list){
                    
                    contentbuilder::setPermissions($form_id, $record_id, $frontend ? '_fe' : '');
                    
                    if($frontend){
                        if(!contentbuilder::authorizeFe('view')){
                            if(JRequest::getVar('contentbuilder_download_file', '', 'GET', 'STRING', JREQUEST_ALLOWRAW)){
                                ob_end_clean();
                                die('No Access');
                            } else {
                                return true;
                            }
                        }
                    }else{
                        if(!contentbuilder::authorize('view')){
                           if(JRequest::getVar('contentbuilder_download_file', '', 'GET', 'STRING', JREQUEST_ALLOWRAW)){
                                ob_end_clean();
                                die('No Access');
                            } else {
                                return true;
                            }
                        }
                    }
                }

                if(!trim($default_title)){
                    $default_title = strtotime('now');
                }

                $i = 0;
                foreach($matches[1] As $match){
                    
                    $out = '';
                    $field = $is_list ? $article->cbrecord->items[0]->recName : '';
                    $box_style = 'border-width:thin::border-color:#000000::border-style:dashed::padding:5px::';
                    $info_style = '';
                    $align = '';
                    $info = true;
                    $hide_filename = false;
                    $hide_mime = false;
                    $hide_size = false;
                    $hide_downloads = false;
                                             
                    $options = explode(';', trim($match));
                    foreach($options As $option){
                        $keyval = explode(':',trim($option), 2);
                        if(count($keyval) == 2){
                            
                            $value = trim($keyval[1]);
                            switch(strtolower(trim($keyval[0]))){
                                case 'field':
                                    $field = $value;
                                    break;
                                case 'info-style':
                                    $info_style = $value;
                                    break;
                                case 'box-style':
                                    $box_style = $value;
                                    break;
                                case 'align':
                                    $align = $value;
                                    break;
                                case 'info':
                                    $info = $value == 'true' ? true : false;
                                    break;
                                case 'hide-filename':
                                    $hide_filename = $value == 'true' ? true : false;
                                    break;
                                case 'hide-mime':
                                    $hide_mime = $value == 'true' ? true : false;
                                    break;
                                case 'hide-size':
                                    $hide_size = $value == 'true' ? true : false;
                                    break;
                                case 'hide-downloads':
                                    $hide_downloads = $value == 'true' ? true : false;
                                    break;
                            }
                        }
                    }
                    
                    $is_series = false;
                    
                    if ($field && isset($record) && $record !== null && is_array($record)) {

                       foreach ($record As $item){
                           if( $default_title == $item->recElementId ){
                            $default_title = $item->recValue;
                            break;
                           }
                       }

                       foreach ($record As $item){

                           if($item->recName == $field){

                                $the_files = explode("\n", str_replace("\r",'',$item->recValue));

                                $the_files_size = count($the_files);

                                if($the_files_size > 0){
                                   $is_series = true;
                                }

                                for($fcnt = 0; $fcnt < $the_files_size; $fcnt++){

                                    $the_value = str_replace(array('{CBSite}','{cbsite}'), JPATH_SITE, trim($the_files[$fcnt]));

                                    if ($the_value) {

                                       $exists = JFile::exists( $the_value );

                                       if($exists){

                                           $phpversion  = explode('-',phpversion());
                                           $phpversion  = $phpversion[0];
                                           // because of mime_content_type deprecation
                                           if(version_compare($phpversion, '5.3', '<')){
                                                if(function_exists('mime_content_type')){
                                                    $mime = mime_content_type($the_value);
                                                }else{
                                                    // fallback if not even that one exists
                                                    $mime = $this->mime_content_type($the_value);
                                                }
                                           }else{
                                                if(function_exists('finfo_open')){
                                                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                                                    $mime  = finfo_file($finfo, $the_value);
                                                    finfo_close($finfo);
                                                }else{
                                                    $mime = $this->mime_content_type($the_value);
                                                }
                                           }

                                           if(JRequest::getVar('contentbuilder_download_file', '', 'GET', 'STRING', JREQUEST_ALLOWRAW) == sha1($field.$the_value)){

                                                 $download_name = basename(JFilterOutput::stringURLSafe($default_title).'_'.$the_value);
                                                 $file_id = md5($type.$item->recElementId.$the_value);
                                                 
                                                 if( !JFactory::getSession()->get('downloaded'.$type.$item->recElementId.$file_id, false, 'com_contentbuilder.plugin.download') ){
                                               
                                                     $db->setQuery("Select hits From #__contentbuilder_resource_access Where `type` = ".$db->Quote($type)." And resource_id = '".$file_id."' And element_id = " . $db->Quote($item->recElementId));
                                                     if($db->loadResult() === null){
                                                         $db->setQuery("Insert Into #__contentbuilder_resource_access (`type`, form_id, element_id, resource_id, hits) values (".$db->Quote($type).",".intval($form_id).", ".$db->Quote($item->recElementId).", '".$file_id."',1)");
                                                     }else{
                                                         $db->setQuery("Update #__contentbuilder_resource_access Set `type` = ".$db->Quote($type).", resource_id = '".$file_id."', form_id = ".intval($form_id).", element_id = ".$db->Quote($item->recElementId).", hits = hits + 1 Where `type` = ".$db->Quote($type)." And resource_id = '".$file_id."' And element_id = " . $db->Quote($item->recElementId));
                                                     }
                                                     $db->query();
                                                 }
                                                 
                                                 JFactory::getSession()->set('downloaded'.$type.$item->recElementId.$file_id, true, 'com_contentbuilder.plugin.download');
                                               
                                                 // clean up before displaying
                                                 @ob_end_clean();

                                                 header('Content-Type: application/octet-stream; name="'.$download_name.'"');
                                                 header('Content-Disposition: inline; filename="'.$download_name.'"');
                                                 header('Content-Length: ' . @filesize($the_value));

                                                 // NOTE: if running IIS and CGI, raise the CGI timeout to serve large files
                                                 @$this->readfile_chunked($the_value);

                                                 exit;
                                             }

                                             $info_style_ = $info_style;
                                             $box_style_  = $box_style;
                                             $info_       = $info;
                                             $align_      = $align;
                                             
                                             $download_name = basename(JFilterOutput::stringURLSafe($default_title).'_'.$the_value);
                                             $file_id = md5($type.$item->recElementId.$the_value);
                                             
                                             $db->setQuery("Select hits From #__contentbuilder_resource_access Where resource_id = '".$file_id."' And `type` = ".intval($type)." And element_id = " . $db->Quote($item->recElementId));
                                             $hits = $db->loadResult();
                                             
                                             if(!$hits){
                                                 $hits = 0;
                                             }
                                             
                                             $size = @number_format(filesize($the_value)/(1024*1024),2) . ' MB';
                                             if(!floatval($size)){
                                                 $size = @number_format(filesize($the_value)/(1024),2) . ' kb';
                                             }
                                             
                                             $hide_filename_ = $hide_filename;
                                             $hide_mime_ = $hide_mime;
                                             $hide_size_ = $hide_size;
                                             $hide_downloads_ = $hide_downloads;
                                             
                                             $url = JURI::getInstance()->toString();
                                             //fixing downloads on other pages than page 1
                                             if( JRequest::getVar('controller','') == 'list' ){
                                                 $url = JURI::getInstance()->base().'index.php?option=com_contentbuilder&amp;controller=list&amp;id='.intval($form_id).'&amp;limitstart='.JRequest::getInt('limitstart',0);
                                             }
                                             
                                             $open_ = JRoute::_($url.(strstr($url,'?') !== false ? '&' : '?').'contentbuilder_download_file='.  sha1($field.$the_value));

                                             $out .= '<div style="'.($align_ ? 'float: '.$align_.';' : '' ). str_replace('::',';',$box_style_).'">
                                                        <a href="'.$open_.'">'.JText::_('COM_CONTENTBUILDER_PLUGIN_DOWNLOAD_DOWNLOAD').'</a>'
                                                     .($info_ ? 
                                                                '<div style="'.(str_replace('::',';',$info_style_)).'">
                                                                    '.($hide_filename_ ? '' : '<span class="cbPluginDownloadFilename">'.JText::_('COM_CONTENTBUILDER_PLUGIN_DOWNLOAD_FILENAME').':</span> '.$download_name.'<br/>').'
                                                                    '.($hide_mime_ ? '' : '<span class="cbPluginDownloadMime">'.JText::_('COM_CONTENTBUILDER_PLUGIN_DOWNLOAD_MIME').':</span> '.$mime.'<br/>').'
                                                                    '.($hide_size_ ? '' : '<span '.($hide_size_ ? ' style="display:none;" ' : '').'class="cbPluginDownloadSize">'.JText::_('COM_CONTENTBUILDER_PLUGIN_DOWNLOAD_SIZE').':</span> '.$size.'<br/>').'
                                                                    '.($hide_downloads_ ? '' : '<span '.($hide_downloads_ ? ' style="display:none;" ' : '').'class="cbPluginDownloadDownloads">'.JText::_('COM_CONTENTBUILDER_PLUGIN_DOWNLOAD_DOWNLOADS').':</span> '.$hits.'<br/>').'
                                                                 </div>' : '').'</div>';

                                             if($is_series && $align_ && (strtolower($align_) == 'left' || strtolower($align_) == 'right' )){
                                                 $out .= '<div style="float:'.strtolower($align_).';width: 5px;">&nbsp;</div>';
                                             }
                                        }
                                    }
                               }
                           }
                       }
                    }
                    
                    if($is_series && $align && (strtolower($align) == 'left' || strtolower($align) == 'right' )){
                        $out .= '<div style="clear:'.strtolower($align).';"></div>';
                    }
                    
                    $article->text = str_replace($matches[0][$i], $out, $article->text);
        
                    $i++;
                }
            }
        }

	return true;
    }
    
    function readfile_chunked ($filename) {
      $chunksize = 1*(1024*1024); // how many bytes per chunk
      $buffer = '';
      $handle = @fopen($filename, 'rb');
      if ($handle === false) {
        return false;
      }
      while (!@feof($handle)) {
        $buffer = @fread($handle, $chunksize);
        print $buffer;
      }
      return @fclose($handle);
    }
}