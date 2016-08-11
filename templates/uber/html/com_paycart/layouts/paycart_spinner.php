<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

/**
 * Spinner will be apper when you will call 
 * 		- by ajax :: payacart.ajax.go(link, request_data) where neew to set request_data[spinner_selector]='#paycart-ajax-spinner'
 * 	    - by json :: payacart.request(request_data), where neew to set request_data[spinner_selector]='#paycart-ajax-spinner'
 * 
 */
// no direct access
defined( '_JEXEC' ) OR  die( 'Restricted access' );

// include only one time
static $invoke_already = false;

if ($invoke_already) {
	return ;
}
$invoke_already = true;
?>

	<style>
	
		.paycart .pc-checkout-loader{
			background-color: rgba(255, 255, 255, 0.9);
		
		}
		.paycart .pc-checkout-loader i.fa-spinner{
			position: fixed;
			top: 50%;
			left: 50%;
		}
		
	</style>

	<div class="modal-backdrop pc-checkout-loader hide" id="paycart-ajax-spinner">
    	<i class="fa fa-spinner fa-3x fa-spin"></i>
	</div>	
	

	


