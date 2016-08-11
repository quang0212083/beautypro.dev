<?php

/*
 * @version		$Id: email.php 3.1 2012-11-28 $
 * @package		Joomla
 * @subpackage	hdwebplayer
 * @copyright   Copyright (C) 2011-2012 HD Webplayer
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class HdwplayerModelEmail extends HdwplayerModel {

	function __construct() {
		parent::__construct();
    }
	
	function sendMail()
    {	
		$mailer    = JFactory::getMailer();
		
		$sender    = array(JRequest::getString('from')); 
		$mailer->setSender($sender);
		
        $recipient = JRequest::getString('to'); 
        $mailer->addRecipient($recipient);
		
		$body     = JRequest::getString('message')."\n\nPlease check the Following Video. Hope! you will enjoy it. Video :  ";
		$body     .= JRequest::getString('url');
		$mailer->setSubject('You Have received a Video!');
		$mailer->setBody($body);
		
		if ( !$mailer->Send() ) {
   			echo 'output='.$body;
		} else {
    		echo 'output=success';
		}
				
	}	
	
}

?>