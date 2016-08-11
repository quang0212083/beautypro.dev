<?php
defined('_JEXEC') or die;
JLog::addLogger(
	array('text_file' => 'com_videogallery.php'),
	JLog::ALL,
	array('com_videogallerylite')
);
JError::$legacy = false;
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Videogallerylite');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task', 'display'));
$controller->redirect();
