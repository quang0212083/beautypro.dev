<?php
/**
 * ------------------------------------------------------------------------
 * JA Popup Plugin for Joomla 25 & 34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die();
jimport('joomla.plugin.plugin');
jimport('joomla.application.module.helper');
// Import library dependencies
jimport('joomla.event.plugin');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * JAPopup Content Plugin
 *
 * @package		Joomla
 * @subpackage	Content
 * @since 		1.7
 */
class plgSystemJAPopup extends JPlugin
{

	/** @var object $_modalObject  */
	protected $_params;
	protected $_includeScripts = array();


	/**
	 * Constructor
	 *
	 * For PHP 5 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments, NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param	object	$subject The object to observe
	 */
	function plgSystemJAPopup(&$subject)
	{
		parent::__construct($subject);

		// load plugin parameters
		$this->_plugin = JPluginHelper::getPlugin('system', 'japopup');
		$this->_params = new JRegistry();
		$this->_params->loadString($this->_plugin->params);


		// Require library for each Popup type
		require_once (dirname(__FILE__) . '/popupHelper.php');
	}

	/**
	 *
	 * Process data when after render
	 */
	function onAfterRender()
	{
		// Return if page is not html
		$app = JFactory::getApplication();

		if ($app->isAdmin()) {
			return;
		}

		if (!isset($this->_plugin))
			return;

		$body = JResponse::getBody();
		

		// Replace {japopup} tag by appropriate content to show popup
		$pattern = '#\{japopup([^}]*)\}([\s\S]*?)\{/japopup\}#i';
		$body = preg_replace_callback($pattern, array($this, 'renderPopup'), $body);
		
		if(count($this->_includeScripts)) {
			//var_dump($this->_includeScripts);
			$script = '';
			$jquery = 0;
			foreach ($this->_includeScripts as $resouces) {
				foreach ($resouces as $link) {
					if($link == '<!--jquery-->') {
						$jquery = 1;
					} else {
						$script .= $link . "\n";
					}
				}
			}
			if($jquery) {
				$pattern = '/(^|\/)jquery([-_]*\d+(\.\d+)+)?(\.min)?\.js/i';//is jquery core
				if(!preg_match($pattern, $body)) {
					
					$base = JURI::base() . 'plugins/system/japopup/asset/jquery/';
					$jqueryScript = '
						<script type="text/javascript" src="' . $base . 'jquery.min.js"></script>
						<script type="text/javascript" src="' . $base . 'jquery-noconflict.js"></script>
						';
					$script = $jqueryScript . $script;
				}
			}
			$body = str_replace('</head>', $script . "\n</head>", $body);
		}

		if ($body) {
			JResponse::setBody($body);
		}
		return true;
	}
	
	/**
	 * Get an instance of Popup class depend on given popup type
	 *
	 * @param string $ptype - Popup type
	 */
	function getPopupModal($ptype) {
		static $instances = array();
		if(!isset($instances[$ptype]) || !$instances[$ptype]) {
			$file = dirname(__FILE__) . '/' . $ptype . '/' . $ptype . '.php';
			if (JFile::exists($file)) {
				include_once($file);
				
				$cls = $ptype . "Class";
				if(class_exists($cls)) {
					$instances[$ptype] = new $cls($this->_params);
				}
			}
		}
		return @$instances[$ptype];
	}
	
	/**
	 * Parse user config
	 * @param string $params
	 * @return array
	 */
	function parseParams($params)
	{
		$params = html_entity_decode($params, ENT_QUOTES);
		$regex = "/([a-zA-Z0-9_-]+)\s*=\s*('([^']*)'|\"([^\"]*)\"|([^\s]*))/";
		preg_match_all($regex, $params, $matches);

		$arr = array();
		if (count($matches)) {
			$arr = array();
			for ($i = 0; $i < count($matches[1]); $i++) {
				$key = $matches[1][$i];
				$val = $matches[3][$i] ? $matches[3][$i] : ($matches[4][$i] ? $matches[4][$i] : $matches[5][$i]);
				$arr[$key] = $val;
			}
		}
		return $arr;
	}
	
	public function renderPopup($matches) {
		$params = $this->parseParams($matches[1]);
		$content = $matches[2];
		
		//group
		if(!isset($params['group'])) {
			$params['group'] = 'popupgroup';
		}
		
		$modal = isset($params['modal']) ? $params['modal'] : $this->_params->get("group1", "fancybox");
		$obj = $this->getPopupModal($modal);
		if($obj) {
			$content = $obj->getContent($params, $content);
			$this->_includeScripts[$modal] = $obj->getHeaderLibrary();
			return $content;
		} else {
			return $matches[0];
		}
	}
}