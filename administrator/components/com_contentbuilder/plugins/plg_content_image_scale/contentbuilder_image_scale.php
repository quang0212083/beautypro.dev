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

set_error_handler('myErrorHandler'); 
register_shutdown_function('fatalErrorShutdownHandler');
function myErrorHandler($code, $message, $file, $line) {   
    // nothing
}  
function fatalErrorShutdownHandler() {
     $last_error = error_get_last();   
     if ($last_error['type'] === E_ERROR) {
          // fatal error     
          myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);  
     } 
}

jimport( 'joomla.plugin.plugin' );
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');
require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder_helpers.php');

// some hosting providers think it is a good idea not to compile in exif with php...
if ( ! function_exists( 'exif_imagetype' ) ) {
    function exif_imagetype ( $filename ) {
        if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
            return $type;
        }
    return false;
    }
}

class plgContentContentbuilder_image_scale extends JPlugin {

    function __construct( &$subject, $params )
    {
        parent::__construct($subject, $params);
    }
    
    /**
     * Joomla 1.5 compatibility
     */
    function onPrepareContent(&$article, &$params, $limitstart = 0, $is_list = false, $form = null, $item = null )
    {
        $this->onContentPrepare('', $article, $params, $limitstart, $is_list, $form, $item);
    }

    function onContentPrepare($context, &$article, &$params, $limitstart = 0, $is_list = false, $form = null, $item = null) {
       
        static $use_title;
        static $use_form;
        
        if (!extension_loaded('gd') || !function_exists('gd_info')) {
            return true;
        }
        
        $protect = false;
        $time_passed = 0;
        $start_time = $this->measureTime();
        $max_exec_time = 15;
        if(function_exists('ini_get')){
            $max_exec_time = @ini_get('max_execution_time');
        }
        $max_time = !empty($max_exec_time) ? intval($max_exec_time) / 2 : 15;
        
        $plugin = JPluginHelper::getPlugin('content', 'contentbuilder_image_scale');
        jimport( 'joomla.html.parameter' );
        $pluginParams = CBCompat::getParams($plugin->params);
        
        $max_filesize = (8 * 8 * 8 * 1024 * 2) * intval($pluginParams->def('max_filesize', 4)); // 4M default
        
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        
        if(!JFile::exists(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder.php'))
        {
            return true;
        }
        
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
        
        if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale')) {
            JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale');
        }
        
        if(isset($article->id) || isset($article->cbrecord)){
            
            $db = JFactory::getDBO();
            
            $matches = array();
            
            preg_match_all("/\{CBImageScale([^}]*)\}/i", $article->text, $matches);
            
            if(isset($matches[0]) && is_array($matches[0]) && isset($matches[1]) && is_array($matches[1])){
                
                $record = null;
                $default_title = '';
                $protect = 0;
                $form_id = 0;
                $record_id = 0;
                
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
                        $ref_id = $record_id = $data['reference_id'];
                        $ref_type = $data['type'];
                        $ref_published_only = $data['published_only'];
                        $ref_own_only_fe = $data['own_only_fe'];
                        $ref_own_only = $data['own_only'];
                    }
                    
                } else if (isset($article->cbrecord) && isset($article->cbrecord->id) && $article->cbrecord->id) {
                    
                    $protect = $article->cbrecord->protect_upload_directory;
                    $record = $article->cbrecord->items;
                    $default_title = $article->cbrecord->title_field;
                    $form_id = $article->cbrecord->id;
                    $record_id = $article->cbrecord->record_id;
                    $ref_id = $article->cbrecord->reference_id;
                    $ref_type = $article->cbrecord->type;
                    $ref_published_only = $article->cbrecord->published_only;
                    $ref_own_only_fe = $article->cbrecord->own_only_fe;
                    $ref_own_only = $article->cbrecord->own_only;
                }
                
                if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'index.html', $def = '');
        
                if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache')) {
                    JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache');
                }

                if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . 'index.html', $def = '');

                if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id )) {
                    JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id);
                }

                if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id . DS . 'index.html', $def = '');

                
                if($protect){
                    if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id . DS . '.htaccess')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id . DS . '.htaccess', $def = 'deny from all');
                } else {
                    if(JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id . DS . '.htaccess')){
                        JFile::delete(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id . DS . '.htaccess');
                    }
                }
                
                $default_folder = JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . 'cache' . DS . $form_id;
        
                // if it is a list, permissions will be handled by the list
                if(!$is_list){
                    
                    contentbuilder::setPermissions($form_id, $record_id, $frontend ? '_fe' : '');
                    
                    if($frontend){
                        if(!contentbuilder::authorizeFe('view')){
                            if(JRequest::getInt('contentbuilder_display',0) || ($protect && JRequest::getInt('contentbuilder_display_detail',0))){
                                ob_end_clean();
                                die('No Access');
                            } else {
                                return true;
                            }
                        }
                    }else{
                        if(!contentbuilder::authorize('view')){
                           if(JRequest::getInt('contentbuilder_display',0) || ($protect && JRequest::getInt('contentbuilder_display_detail',0))){
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
                    
                    $alt = '';
                    $out = '';
                    $width = 0;
                    $height = 0;
                    $original_width = 0;
                    $original_height = 0;
                    $field = $is_list ? $article->cbrecord->items[0]->recName : '';
                    $folder = $default_folder;
                    $bgcolor = null;
                    $title = '';
                    $type = '';
                    $cache = 86400;
                    $global_cache = 86400;
                    $align = '';
                    $open = '';
                    $default_image = '';
                    $default_image_width = 50;
                    $default_image_height = 50;
                    
                    $options = explode(';', trim($match));
                    foreach($options As $option){
                        $keyval = explode(':',trim($option), 2);
                        if(count($keyval) == 2){
                            
                            $value = trim($keyval[1]);
                            switch(strtolower(trim($keyval[0]))){
                                case 'width':
                                    $width = $value;
                                    break;
                                case 'height':
                                    $height = $value;
                                    break;
                                case 'original-width':
                                    $original_width = $value;
                                    break;
                                case 'original-height':
                                    $original_height = $value;
                                    break;
                                case 'field':
                                    $field = $is_list ? $article->items[0]->recName : $value;
                                    break;
                                case 'background-color':
                                    $bgcolor = $value;
                                    break;
                                case 'folder':
                                    $folder = $value;
                                    break;
                                case 'alt':
                                    $alt = $value;
                                    break;
                                case 'title':
                                    $title = $value;
                                    break;
                                case 'type':
                                    $type = $value;
                                    break;
                                case 'cache':
                                    $cache = $value;
                                    break;
                                case 'global_cache':
                                    $global_cache = $value;
                                    break;
                                case 'align':
                                    $align = $value;
                                    break;
                                case 'open':
                                    $open = $value;
                                    break;
                                case 'default-image':
                                    $default_image = $value;
                                    break;
                                case 'default-image-width':
                                    $default_image_width = $value;
                                    break;
                                case 'default-image-height':
                                    $default_image_height = $value;
                                    break;
                            }
                        }
                    }
                    
                    if($is_list && $alt == 'USE-TITLE'){
                        
                        if(!$use_form){
                            
                            require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder.php');
                            $use_form = contentbuilder::getForm($ref_type, $ref_id);
                        }
                        
                        if($use_form && $use_form->exists){
                            
                            if(!is_array($use_title) || !isset($use_title[intval($default_title)])){
                                
                                $use_record = $use_form->getRecord($record_id, $ref_published_only, $frontend ? ( $ref_own_only_fe ? JFactory::getUser()->get('id', 0) : -1 ) : ( $ref_own_only ? JFactory::getUser()->get('id', 0) : -1 ), true );
                                
                                foreach ($use_record As $use_item){
                                   if( $default_title == $use_item->recElementId ){
                                    $default_title = cbinternal($item->recValue);
                                    if(!$is_list && $alt == 'USE-TITLE'){
                                        $alt   = $default_title;
                                        $title = $default_title;
                                    }
                                    break;
                                   }
                               }
                                
                                $use_title[intval($default_title)] = $db->loadResult();
                            }

                            $alt   = $use_title[intval($default_title)];
                            $title = $use_title[intval($default_title)];
                        
                        }
                    }
                    else if($is_list && trim($alt) == ''){
                        $alt   = cbinternal($article->cbrecord->items[0]->recValue);
                        $title = cbinternal($article->cbrecord->items[0]->recValue);
                    }
                    
                    $is_series = false;
                    
                    if ($field && ($width || $height)) {

                        if($record !== null){
                            
                           if(isset($record) && is_array($record)){
                               
                               foreach ($record As $item){
                                   if( $default_title == $item->recElementId ){
                                    $default_title = cbinternal($item->recValue);
                                    if(!$is_list && $alt == 'USE-TITLE'){
                                        $alt   = $default_title;
                                        $title = $default_title;
                                    }
                                    break;
                                   }
                               }
                               
                               foreach ($record As $item){
                                   
                                   if($item->recName == $field){
                                        
                                       if(trim($alt) == ''){
                                           $alt   = cbinternal($item->recValue);
                                           $title = cbinternal($item->recValue);
                                       }
                                       
                                        $the_files = explode("\n", str_replace("\r",'',$item->recValue));
                                       
                                        $the_files_size = count($the_files);
                                       
                                        if($the_files_size > 0){
                                           $is_series = true;
                                        }
                    
                                        for($fcnt = 0; $fcnt < $the_files_size; $fcnt++){

                                            $the_value = str_replace(array('{CBSite}','{cbsite}'), JPATH_SITE, trim($the_files[$fcnt]));

                                            if ($the_value && ($width || $height)) {
                                       
                                               $image = @getimagesize( $the_value );

                                               if($image !== false){

                                                   if($type != 'simple'){
                                                   
                                                       if(!$width || $width < 0){
                                                           $width = $height;
                                                       }

                                                       if(!$height || $height < 0){
                                                           $height = $width;
                                                       }
                                                   
                                                   }

                                                   if($width > 16384){
                                                       $width = 16384;
                                                   }

                                                   if($height > 16384){
                                                       $height = 16384;
                                                   }

                                                   $exif_type = exif_imagetype( $the_value );
                                                   
                                                   // displaying the original file on request
                                                   if(JRequest::getInt('contentbuilder_display_detail',0)){

                                                      if(JRequest::getVar('contentbuilder_detail_file', '', 'REQUEST', 'STRING', JREQUEST_ALLOWRAW) == sha1($field.$the_value)){

                                                          // clean up before displaying
                                                          ob_end_clean();

                                                          switch ($exif_type){
                                                               case IMAGETYPE_JPEG2000 :
                                                                   header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_JPEG2000));
                                                                   break;
                                                               case IMAGETYPE_JPEG :
                                                                   header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_JPEG));
                                                                   break;
                                                               case IMAGETYPE_GIF :
                                                                   header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_GIF));
                                                                   break;
                                                               case IMAGETYPE_PNG :
                                                                   header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_PNG));
                                                                   break;
                                                         }

                                                         header('Content-Disposition: inline; filename="'.basename(JFilterOutput::stringURLSafe($title).'_'.$the_value).'"');
                                                         header('Content-Length: ' . @filesize($the_value));
                                                         @$this->readfile_chunked($the_value);

                                                         exit;
                                                     }
                                                   }
                                                   
                                                   $filename = '';
                                                   $pathinfo = pathinfo($the_value);
                                                   $basename = basename($the_value, '.' . $pathinfo['extension']) . '_' . $width . 'x' . $height .  '_cbresized';

                                                   if ($folder && JFolder::exists($folder)) {
                                                       $filename = $folder . DS . $basename . image_type_to_extension($exif_type);
                                                   } else {
                                                       $filename = $pathinfo['dirname'] . DS . $basename . image_type_to_extension($exif_type);
                                                       $folder = $pathinfo['dirname'];
                                                   }

                                                   if(is_numeric($global_cache)){
                                                       $limit = intval($global_cache);
                                                       $sourcePath = $folder . DS;
                                                        if (@file_exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
                                                            while (false !== ($file = @readdir($handle))) {
                                                                if ($file != "." && $file != "..") {
                                                                    $parts = explode('_', $file);
                                                                    $exparts = explode('.', isset($parts[count($parts) - 1]) ? $parts[count($parts) - 1] : array());
                                                                    if (isset($exparts[0]) && $exparts[0] == 'cbresized') {
                                                                        if (@JFile::exists($sourcePath . $file) && @is_readable($sourcePath . $file)) {
                                                                            $fileCreationTime = @filectime($sourcePath . $file);
                                                                            $fileAge = time() - $fileCreationTime;
                                                                            if ($fileAge >= $limit) {
                                                                                JFile::delete($sourcePath . $file);
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            @closedir($handle);
                                                        }
                                                   }

                                                   $image_changed = false;
                                                   $image_filesize = @filesize($filename);

                                                   if($image_filesize !== null){
                                                       $existing_image = @getimagesize( $filename );
                                                       if($existing_image[0] != $width || $existing_image[1] != $height){
                                                           $image_changed = true;
                                                       }
                                                   }

                                                   $create = false;
                                                   switch($cache){
                                                       case 'none':
                                                           $create = true;
                                                           break;
                                                       default:
                                                           if(is_numeric($cache) && JFile::exists($filename)){
                                                               $limit = intval($cache);
                                                               $fileCreationTime = @filectime($filename);
                                                               $fileAge = time() - $fileCreationTime; 
                                                               if($fileAge >= $limit){
                                                                    JFile::delete($filename);
                                                                    $create = true;
                                                               }
                                                           }
                                                   }

                                                   $max_ok = true;
                                                   if( @filesize($the_value) > $max_filesize ) 
                                                   {
                                                       $max_ok = false;
                                                   }

                                                   if( $max_ok && ( $create || $image_filesize === false || $image_changed ) ){
                                                       
                                                       $col_ = $bgcolor;
                                                       if($bgcolor !== null){
                                                           $col = array();
                                                           $col[0] = intval(@hexdec(@substr($bgcolor, 1, 2)));
                                                           $col[1] = intval(@hexdec(@substr($bgcolor, 3, 2)));
                                                           $col[2] = intval(@hexdec(@substr($bgcolor, 5, 2)));
                                                           $col_ = $col;
                                                       }

                                                       $resized = false;
                                                       $resource = false;
                                                       
                                                       // try to prevent memory issues
                                                       $memory = true;
                                                       
                                                       $imageInfo = $image;
                                                       
                                                       $MB = 1048576;
                                                       $K64 = 65536;
                                                       $TWEAKFACTOR = 1.5;
                                                       $memoryNeeded = round(( $imageInfo[0] * $imageInfo[1]
                                                               * @$imageInfo['bits']
                                                               * (@$imageInfo['channels'] / 8)
                                                               + $K64
                                                               ) * $TWEAKFACTOR
                                                       );

                                                       $ini = 8 * $MB;
                                                       if(ini_get('memory_limit') !== false){
                                                           $ini = $this->returnBytes(ini_get('memory_limit'));
                                                       }
                                                       $memoryLimit = $ini;
                                                       if (function_exists('memory_get_usage') &&
                                                               memory_get_usage() + $memoryNeeded > $memoryLimit) {
                                                           $memory = false;
                                                       }

                                                       if($memory){
                                                       
                                                           switch ($exif_type){
                                                               case IMAGETYPE_JPEG2000 :
                                                               case IMAGETYPE_JPEG :
                                                                   $resource = @imagecreatefromjpeg($the_value);
                                                                   if($resource){
                                                                       $resized = @$this->resize_image($resource, $width, $height, $type == 'crop' ? 1 : ( $type == 'simple' ? 3 : 2), $col_);
                                                                       if($resized) @imagejpeg($resized, $filename);
                                                                       if($resized){
                                                                            @imagedestroy($resized);
                                                                       }
                                                                       if($image[0] != $original_width && $image[1] != $original_height && ($original_width > 0 || $original_height > 0) ){
                                                                           if($original_width != 0 && $original_height == 0){
                                                                               $original_height = $original_width;
                                                                           }
                                                                           if($original_width == 0 && $original_height != 0){
                                                                               $original_width = $original_height;
                                                                           }
                                                                           $resized2 = @$this->resize_image($resource, $original_width, $original_height, $type == 'crop' ? 1 : ( $type == 'simple' ? 3 : 2), $col_);
                                                                           if($resized2) { @imagejpeg($resized2, $the_value); @imagedestroy($resized2); $image = @getimagesize( $the_value ); };
                                                                       }
                                                                       @imagedestroy($resource);
                                                                   }
                                                                   break;
                                                               case IMAGETYPE_GIF :
                                                                   $resource = @imagecreatefromgif($the_value);
                                                                   if($resource){
                                                                       $resized = @$this->resize_image($resource, $width, $height, $type == 'crop' ? 1 : ( $type == 'simple' ? 3 : 2), $col_);
                                                                       if($resized) @imagegif($resized, $filename);
                                                                       if($resized){
                                                                            @imagedestroy($resized);
                                                                       }
                                                                       if($image[0] != $original_width && $image[1] != $original_height && ($original_width > 0 || $original_height > 0) ){
                                                                           if($original_width != 0 && $original_height == 0){
                                                                               $original_height = $original_width;
                                                                           }
                                                                           if($original_width == 0 && $original_height != 0){
                                                                               $original_width = $original_height;
                                                                           }
                                                                           $resized2 = @$this->resize_image($resource, $original_width, $original_height, $type == 'crop' ? 1 : ( $type == 'simple' ? 3 : 2), $col_);
                                                                           if($resized2) { @imagegif($resized2, $the_value); @imagedestroy($resized2); $image = @getimagesize( $the_value );};
                                                                       }
                                                                       @imagedestroy($resource);
                                                                   }
                                                                   break;
                                                               case IMAGETYPE_PNG :
                                                                   $resource = @imagecreatefrompng($the_value);
                                                                   if($resource){
                                                                       $resized = @$this->resize_image($resource, $width, $height, $type == 'crop' ? 1 : ( $type == 'simple' ? 3 : 2), $col_);
                                                                       if($resized) @imagepng($resized, $filename);
                                                                       if($resized){
                                                                            @imagedestroy($resized);
                                                                       }
                                                                       if($image[0] != $original_width && $image[1] != $original_height && ($original_width > 0 || $original_height > 0) ){
                                                                           if($original_width != 0 && $original_height == 0){
                                                                               $original_height = $original_width;
                                                                           }
                                                                           if($original_width == 0 && $original_height != 0){
                                                                               $original_width = $original_height;
                                                                           }
                                                                           $resized2 = @$this->resize_image($resource, $original_width, $original_height, $type == 'crop' ? 1 : ( $type == 'simple' ? 3 : 2), $col_);
                                                                           if($resized2) { @imagepng($resized2, $the_value); @imagedestroy($resized2); $image = @getimagesize( $the_value );};
                                                                       }
                                                                       @imagedestroy($resource);
                                                                   }
                                                                   break;
                                                           }
                                                       }
                                                   }

                                                   if($filename){
                                                      $the_image = @getimagesize($filename);
                                                      
                                                      if($the_image !== false){
                                                          if(JRequest::getInt('contentbuilder_display',0)){

                                                              if(JRequest::getVar('contentbuilder_field', '', 'REQUEST', 'STRING', JREQUEST_ALLOWRAW) == sha1($field.$filename)){

                                                                  // clean up before displaying
                                                                  ob_end_clean();

                                                                  switch ($exif_type){
                                                                       case IMAGETYPE_JPEG2000 :
                                                                           header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_JPEG2000));
                                                                           break;
                                                                       case IMAGETYPE_JPEG :
                                                                           header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_JPEG));
                                                                           break;
                                                                       case IMAGETYPE_GIF :
                                                                           header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_GIF));
                                                                           break;
                                                                       case IMAGETYPE_PNG :
                                                                           header('Content-Type: ' . @image_type_to_mime_type(IMAGETYPE_PNG));
                                                                           break;
                                                                 }

                                                                 header('Content-Disposition: inline; filename="'.JFilterOutput::stringURLSafe($title).'_'.basename($filename).'"');
                                                                 header('Content-Length: ' . @filesize($filename));
                                                                 @$this->readfile_chunked($filename);

                                                                 exit;

                                                             }

                                                          }else{
                                                             $align_ = $align;
                                                             $open_ = '';
                                                             $close_ = '';

                                                             $url = JURI::getInstance()->toString();
                                                             //fixing downloads on other pages than page 1
                                                             if( JRequest::getVar('controller','') == 'list' ){
                                                                $url = JURI::getInstance()->base().'index.php?option=com_contentbuilder&amp;controller=list&amp;id='.intval($form_id).'&amp;limitstart='.JRequest::getInt('limitstart',0);
                                                             }
                                                             
                                                             if(trim($open) == 'true'){
                                                                 if($protect){
                                                                    $open_ = JRoute::_($url.(strstr($url,'?') !== false ? '&' : '?').'contentbuilder_display_detail=1&contentbuilder_detail_file='.  sha1($field.$the_value));
                                                                 }else{
                                                                     $ex = explode(JPATH_SITE . DS, JPath::clean($the_value), 2);
                                                                     $open_ = JURI::root(true) . '/' . str_replace("\\","/",$ex[count($ex)-1]);
                                                                 }
                                                             }
                                                             if($open_){
                                                                 $inPopup = false;
                                                                 JHTML::_('behavior.modal');
                                                                 if($image[0] > 960){
                                                                     $inPopup = true;
                                                                 }
                                                                 if($image[1] > 720){
                                                                     $inPopup = true;
                                                                 }
                                                                 $hrefalign = $align_ ? 'style="float: '.$align_.';" ' : '';
                                                                 if($inPopup){
                                                                    $open_ = '<a href="javascript:var win = window.open(\''.$open_.'\',\'ImageSizer\',\'height='.$image[1].',width='.$image[0].',scrollbars=1\');win.focus();void(0);" '.$hrefalign.'>';
                                                                 }else{
                                                                    $open_ = '<a href="'.$open_.'" '.$hrefalign.'class="modal" rel="{handler:\'iframe\',size:{x:'.($image[0]+20).',y:'.($image[1]+20).'}}">';
                                                                 }
                                                                 $close_ = '</a>';
                                                             }
                                                             
                                                             if($protect){
                                                                $src = JRoute::_($url.(strstr($url,'?') !== false ? '&' : '?').'contentbuilder_display=1&contentbuilder_field='.  sha1($field.$filename));
                                                             }else{
                                                                $ex = explode(JPATH_SITE . DS, $filename, 2);
                                                                $src = JURI::root(true) . '/' . str_replace("\\","/",$ex[count($ex)-1]);
                                                             }
                                                             
                                                             $out .= $open_.'<img border="0" '.$the_image[3].' '.($align_ ? 'style="float: '.$align_.';" ' : '').'alt="'.$alt.'" title="'.$title.'" src="'.$src.'"/>'.$close_;
                                                             if($is_series && $align_ && (strtolower($align_) == 'left' || strtolower($align_) == 'right' )){
                                                                 $out .= '<div style="float:'.strtolower($align_).';width: 5px;">&nbsp;</div>';
                                                             }
                                                             $align_ = '';
                                                        }
                                                      }
                                                   }
                                                }
                                            }
                                            
                                            $time_passed = $this->measureTime();
                                            if(($time_passed - $start_time) > $max_time){
                                                break;
                                            }
                                       }
                                   }
                               }
                           }
                        }
                    }
                    
                    if(trim($out) == '' && JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'plugins' . DS . 'image_scale' . DS . basename($default_image))){
                        $out = '<img width="'.$default_image_width.'" height="'.$default_image_height.'" alt="" src="'.JURI::root(true).'/media/contentbuilder/plugins/image_scale/'.basename($default_image).'"/>';
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
    
    public function returnBytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }
    
    public function measureTime()
    {
        $a = explode (' ',microtime());
        return ((double) $a[0] + $a[1]) / 1000;
    }
    /*
    public function resize_image($source_image, $destination_width, $destination_height, $type = 0, $bgcolor = array(0,0,0)) {
        // $type (1=crop to fit, 2=letterbox)
        $source_width = imagesx($source_image);
        $source_height = imagesy($source_image);
        $source_ratio = $source_width / $source_height;
        $destination_ratio = $destination_width / $destination_height;
        if ($type == 1) {
            // crop to fit
            if ($source_ratio > $destination_ratio) {
                // source has a wider ratio
                $temp_width = (int) ($source_height * $destination_ratio);
                $temp_height = $source_height;
                $source_x = (int) (($source_width - $temp_width) / 2);
                $source_y = 0;
            } else {
                // source has a taller ratio
                $temp_width = $source_width;
                $temp_height = (int) ($source_width * $destination_ratio);
                $source_x = 0;
                $source_y = (int) (($source_height - $temp_height) / 2);
            }
            $destination_x = 0;
            $destination_y = 0;
            $source_width = $temp_width;
            $source_height = $temp_height;
            $new_destination_width = $destination_width;
            $new_destination_height = $destination_height;
        } else {
            // letterbox
            if ($source_ratio < $destination_ratio) {
                // source has a taller ratio
                $temp_width = (int) ($destination_height * $source_ratio);
                $temp_height = $destination_height;
                $destination_x = (int) (($destination_width - $temp_width) / 2);
                $destination_y = 0;
            } else {
                // source has a wider ratio
                $temp_width = $destination_width;
                $temp_height = (int) ($destination_width / $source_ratio);
                $destination_x = 0;
                $destination_y = (int) (($destination_height - $temp_height) / 2);
            }
            $source_x = 0;
            $source_y = 0;
            $new_destination_width = $temp_width;
            $new_destination_height = $temp_height;
        }
        $destination_image = imagecreatetruecolor($destination_width, $destination_height);
        if ($type > 1) {
            imagefill($destination_image, 0, 0, imagecolorallocate($destination_image, $bgcolor[0], $bgcolor[1], $bgcolor[2]));
        }
        imagecopyresampled($destination_image, $source_image, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height);
        return $destination_image;
    }*/
    
    public function resize_image($source_image, $destination_width, $destination_height, $type = 0, $bgcolor = array(0,0,0)) {
        // $type (1=crop to fit, 2=letterbox)
        $source_width = imagesx($source_image);
        $source_height = imagesy($source_image);
        $source_ratio = $source_width / $source_height;
        if($destination_height == 0 && $type == 3){
            $destination_height = $source_height;
        }
        $destination_ratio = $destination_width / $destination_height;
        if($type == 3){
            
            $old_width  = $source_width;
            $old_height = $source_height;
            
            // Target dimensions
            $max_width = $destination_width;
            $max_height = $destination_height;
            // Get current dimensions
            
            // Calculate the scaling we need to do to fit the image inside our frame
            $scale      = min($max_width/$old_width, $max_height/$old_height);

            // Get the new dimensions
            $destination_width  = ceil($scale*$old_width);
            $destination_height = ceil($scale*$old_height);
            
            $new_destination_width = $destination_width;
            $new_destination_height = $destination_height;
            
            $source_x = 0;
            $source_y = 0;
            $destination_x = 0;
            $destination_y = 0;
            
        } else if ($type == 1) {
            // crop to fit
            if ($source_ratio > $destination_ratio) {
                // source has a wider ratio
                $temp_width = (int) ($source_height * $destination_ratio);
                $temp_height = $source_height;
                $source_x = (int) (($source_width - $temp_width) / 2);
                $source_y = 0;
            } else {
                // source has a taller ratio
                $temp_width = $source_width;
                $temp_height = (int) ($source_width * $destination_ratio);
                $source_x = 0;
                $source_y = (int) (($source_height - $temp_height) / 2);
            }
            $destination_x = 0;
            $destination_y = 0;
            $source_width = $temp_width;
            $source_height = $temp_height;
            $new_destination_width = $destination_width;
            $new_destination_height = $destination_height;
        } else {
            // letterbox
            if ($source_ratio < $destination_ratio) {
                // source has a taller ratio
                $temp_width = (int) ($destination_height * $source_ratio);
                $temp_height = $destination_height;
                $destination_x = (int) (($destination_width - $temp_width) / 2);
                $destination_y = 0;
            } else {
                // source has a wider ratio
                $temp_width = $destination_width;
                $temp_height = (int) ($destination_width / $source_ratio);
                $destination_x = 0;
                $destination_y = (int) (($destination_height - $temp_height) / 2);
            }
            $source_x = 0;
            $source_y = 0;
            $new_destination_width = $temp_width;
            $new_destination_height = $temp_height;
        }
        $destination_image = imagecreatetruecolor($destination_width, $destination_height);
        if ($type == 2) {
            imagefill($destination_image, 0, 0, imagecolorallocate($destination_image, $bgcolor[0], $bgcolor[1], $bgcolor[2]));
        }
        imagecopyresampled($destination_image, $source_image, $destination_x, $destination_y, $source_x, $source_y, $new_destination_width, $new_destination_height, $source_width, $source_height);
        return $destination_image;
    }

}
