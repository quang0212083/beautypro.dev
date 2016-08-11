<?php
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
 
$related_item = modRelatedItemHelper::getItems($params);
require JModuleHelper::getLayoutPath('mod_related_item');