<?php
/*
 * @version		$Id: dashboard.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

// Import filesystem libraries.
jimport('joomla.filesystem.file');

class HdwplayerModelDashboard extends HdwplayerModel {

    function __construct() {
		parent::__construct();
    }
	
	function getdata() {
        $allow_fileuploads   = ini_get('file_uploads') ? 'YES' : 'NO';
		$upload_max_filesize = ini_get('upload_max_filesize');
		$max_input_time      = ini_get('max_input_time');
		$memory_limit        = ini_get('memory_limit');
		$max_execution_time  = ini_get('max_execution_time');
		$post_max_size       = ini_get('post_max_size');
		$upload_dir          = (is_writable(JPATH_ROOT.DS.'media'.DS)) ? 'YES' : 'NO';
            
		$output[0] = array( 'name' => 'allow_fileuploads',            'value' => $allow_fileuploads );
		$output[1] = array( 'name' => 'upload_max_filesize',          'value' => $upload_max_filesize );
		$output[2] = array( 'name' => 'max_input_time',               'value' => $max_input_time );
		$output[3] = array( 'name' => 'memory_limit',                 'value' => $memory_limit );
		$output[4] = array( 'name' => 'max_execution_time',           'value' => $max_execution_time );
		$output[5] = array( 'name' => 'post_max_size',                'value' => $post_max_size );
		$output[6] = array( 'name' => 'upload_directory_permission',  'value' => $upload_dir );
          
        return $output;
	}
	
}

?>