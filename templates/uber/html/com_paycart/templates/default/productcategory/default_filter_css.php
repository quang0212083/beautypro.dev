<?php

/**
* @copyright	Copyright (C) 2009 - 2014 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+contact@readybytes.in
* @author		rimjhim jain
*/


// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );?>

<style>

.paycart .pc-product-filter .accordion-group{
	border: none;
    border-radius: 0px;
    margin-bottom: 0px;
}

.paycart .pc-product-filter .accordion-inner {
    border : none;
    padding: 0px 15px;
}

.paycart .pc-product-filter .accordion-toggle {
    padding: 0 15px;
    font-weight :0;
}

.paycart .pc-product-filter-body{
    overflow-y: auto;
    height : auto;
	max-height:150px;
}

.paycart .pc-product-filter h2{
    font-weight : normal;
}

.paycart .pc-filter-color{
	min-height :13px;
	min-width :13px;
	border:1px solid #d6d6d6;
	margin-top : 4px;
}

.row-offcanvas-left.active .pc-filter-apply-btn{
	position: fixed;
}

.row-offcanvas-left.active .pc-fixed-top .table {
	background: #f5f5f5;
	box-sizing: border-box;
	top: 0;
	position: fixed;
	width: 100%;
	border-bottom: 1px solid #ddd;
	z-index: 3;
}

.row-offcanvas-left.active .pc-form-product-filter {
	max-height: 80%;
	overflow-y: auto;
	margin: 10% 0;
}

.pc-filter-apply-btn {
	border-top: 1px solid #ddd;
	background: #f5f5f5;
	padding: 10px;
	box-sizing: border-box;
	bottom: 0;
	width: 100%;
}

.pc-fixed-top .table th {
	width: 33%;
	text-align: center;
}

.pc-refine-filter-mobile{
	padding : 5px;
}

.pc-product-filter .accordion-toggle span:before{
	font-family: FontAwesome;
	content: "\f078";
}

.pc-product-filter .accordion-toggle.collapsed span:before{
	content: "\f054";
}

.row-offcanvas-left.active .pc-product-filter .checkbox {
	min-height: 2.2em;
}

/* disable scrolling in individual filter in mobile */
.row-offcanvas-left.active .pc-product-filter-body{
	max-height:none;
}

</style>
