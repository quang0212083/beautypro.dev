<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentbuilder_themesKhepri extends JPlugin
{
        function __construct( &$subject, $params )
        {
            parent::__construct($subject, $params);
        }
        
        /**
         * Any content template specific JS?
         * Return it here
         * 
         * @return string
         */
        function getContentTemplateJavascript(){
            
            return '';
        }
        
        /**
         * Any editable template specific JS?
         * Return it here
         * 
         * @return string
         */
        function getEditableTemplateJavascript(){
            
            return '';
        }
        
        /**
         * Any list view specific JS?
         * Return it here
         * 
         * @return string
         */
        function getListViewJavascript(){
            
            return '';
        }
        
        /**
         * Any content template specific CSS?
         * Return it here
         * 
         * @return string
         */
        function getContentTemplateCss(){
            
            return '/* Administrator forms, lists */
fieldset.adminform {
	margin: 10px;
	overflow: hidden;
}

fieldset.adminform legend {
	margin: 0;
	padding: 0;
}

ul.adminformlist,
ul.adminformlist li {
	margin: 0;
	padding: 0;
	list-style: none;
}

fieldset label,
fieldset span.faux-label {
	float: left;
	clear: left;
	display:block;
	margin: 5px 0;
}
fieldset ul {
	margin: 0;
	padding: 0;
}

form label,
form span.faux-label {
	font-size: 1.091em;
}

fieldset input,
fieldset textarea,
fieldset select,
fieldset img,
fieldset button {
	float: left;
	width: auto;
	margin: 5px 5px 5px 0px;
}

fieldset.adminform textarea {
	width: 355px;
}

fieldset ul.checklist input {
	clear:left;
	margin-right: 10px;
}

fieldset ul.checklist label,
fieldset ul.menu-links label,
fieldset#filter-bar label {
	clear:none;
}
fieldset.adminform ul.checklist li {
	width: 100%;
	margin: 0;
	padding:0;
}
fieldset.adminform ul.checklist li label {
	width: auto;
}

input.readonly {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1.091em;
	padding-top: 1px;
	border: 0;
	font-weight: bold;
	color: #666;
}

#jform_id,
span.readonly {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 1.091em;
	margin:5px 5px 5px 0;
	font-weight: bold;
	float: left;
	display:block;
	color: #666;
}

#jform_params_alt,
#jform_clickurl {
	width: 355px;
}

#jform-imp {
	min-width: 70px;
}

.mod-desc { margin-left: 135px !important; float: none !important;}

input.readonly {
	background-color: #ffffff;
}

#jform_params_width,
#jform_params_height,
#jform_params_increase,
#jform_params_shownumber,
#jform_params_count {
	width: 50px;
}

#jform_id {
	width: 50px;
	background-color:#FFFFFF;
	border: 0 none;
}

input#jform_title,
input#jform_leveltitle,
input#jform_grouptitle {
	font-size: 1.364em;
}

label#jform_title-lbl,
label#jform_leveltitle-lbl,
label#jform_grouptitle-lbl {
	padding-top: 3px;
}

div#content-pane fieldset {
	border: 0;
	padding: 10px 15px 0 15px;
}

div.panel fieldset {
	border: 0;
}

/* Required elements */
input.required {
	background-color: #d5eeff;
}

.star {
	color:#EB8207;
	font-size:1.2em;
}

/* -------- Batch Section ---------- */
fieldset.batch {
	margin: 20px 0px 10px 0px;
	background: #fff;
	padding: 10px;
}
fieldset.batch label {
	margin: 5px;
	min-width: 40px;
}
fieldset.batch button {
	margin: 3px;
}
fieldset#batch-choose-action {
	clear: left;
	border: 0 none;
}
fieldset.batch label {
	float: left;
	clear: none;
}
fieldset label#batch-choose-action-lbl {
	clear: left;
	margin-top: 15px;
}
select#batch-menu-id {
	margin-right: 30px;
}
label#batch-access-lbl {
	margin-right: 10px;
}


/* -------- Menu Assigments ---------- */
div#menu-assignment {
	clear:left;
}
div#menu-assignment input,
div#menu-assignment h3 {
	clear:left;
	padding-bottom: 0;
	margin-bottom: 0;
}
div#menu-assignment ul.menu-links {
	float:left;
	width:49%;
}
div#menu-assignment ul.menu-links li:last-child label {
	margin-bottom: 20px;
}

fieldset.adminform .menu-links label {
	white-space:nowrap;
}
fieldset.adminform .menu-links input {
	margin: 8px 5px 0 0;
}

button.jform-rightbtn { 
	float:right;
	margin-right: 0;
}

#jform_impmade,
#jform_clicks {
	width: 30px;
}

/* Field label widths - long label */
fieldset.adminform.long label, 
fieldset.adminform.long span.faux-label {
	min-width: 180px;
}
fieldset.adminform.long input { }

/* Field label widths - short label */
fieldset.adminform label, 
fieldset.adminform span.faux-label {
	min-width: 135px;
	padding: 0 5px 0 0;
}

fieldset.panelform {
	overflow: hidden;
}
fieldset.panelform label,
fieldset.panelform div.paramrow label,
fieldset.panelform span.faux-label {
	min-width: 145px;
	max-width: 250px;
	padding: 0 5px 0 0;
}

/* One-offs */
/* Field label widths - medium label */
label#userparamsallowUserRegistration-lbl,
label#userparamsnew_usertype-lbl,
label#userparamsuseractivation-lbl,
label#userparamsfrontend_userparams-lbl,
label#jform_MetaDesc-lbl {
	min-width: 180px;
}

/* Field label widths - long label */
label#paramsusermode-lbl,
label#paramsphishing-resistant-lbl,
label#paramsmulti-factor-lbl,
label#paramsmulti-factor-physical-lbl,
paramslang_mode-lbl {
	min-width: 200px;
}

div.jform_mod_title,
div.jform_na {
	margin-top: 5px;
	float: left;
}

div#jform_template,
div#jform_template-desc {
	float: left;
	padding-right: 5px;
	padding-top: 5px;
	font-size: 1.091em;
}

div#jform_template-desc {
	padding-top: 3px;
}

input#description {
	margin-top: 3px;
}

th.col1template {
	width: 210px;
}

div.editor-border {
	border: 1px solid #CCCCCC;
}

fieldset p {
	margin: 0 0 15px 0;
	font-size: 1.091em;
}

ul#overviewlist,ul#paramlist {
	clear: both;
	font-size: 1.091em;
	padding-top: 5px;
}

ul#overviewlist li {
	list-style-type: none;
	margin-left: -40px;
	margin-bottom: 15px;
	min-width: 140px;
}

ul#paramlist li {
	list-style-type: none;
	margin-left: -40px;
	margin-bottom: 5px;
}

li#jform_menutype_label,
li#jform_parentid_label,
li#jform_published_label,
li#jform_access_label {
	float: left;
	clear: left;
}

fieldset.adminform fieldset.radio,
fieldset.panelform fieldset.radio,
fieldset.adminform-legacy fieldset.radio {
	border: 0;
	float: left;
	padding: 0;
	margin: 0 0 5px 0;
	/* clear: right; */
}

fieldset.adminform fieldset.radio label,
fieldset.panelform fieldset.radio label {
	min-width: 60px;
	padding-left: 0;
	padding-right: 10px;
	float: left;
	clear: none;
	/*width: 40px;*/
	display:inline;
}

/* checkboxes */
fieldset.adminform fieldset.checkboxes,
fieldset.panelform fieldset.checkboxes,
fieldset.adminform-legacy fieldset.checkboxes  {
	border: 0;
	float:left;
	padding: 0;
	margin: 0 0 5px 0;
	clear:right;
}


fieldset.adminform fieldset.checkboxes input[type="checkbox"],
fieldset.panelform fieldset.checkboxes input[type="checkbox"] {
	float: left;
	clear: left;
}
fieldset.adminform fieldset.checkboxes label,
fieldset.panelform fieldset.checkboxes label,
fieldset.adminform fieldset.checkboxes span.faux-label,
fieldset.panelform fieldset.checkboxes span.faux-label {
	clear: right;
}

/* end checkboxes */

/* spacer */
div.current span.spacer > span.before,
fieldset.adminform span.spacer > span.before,
fieldset.panelform span.spacer > span.before {
	clear: both;
	overflow: hidden;
	height: 0;
	display: block;
}

div.current span.spacer > span.text label ,
fieldset.adminform span.spacer > span.text label ,
fieldset.panelform span.spacer > span.text label {
	white-space: nowrap;
	font-weight: bold;
	color: #666;
}

/* end spacer */

fieldset.panelform-legacy label,
fieldset.adminform-legacy label {
	min-width: 150px;
	float: left;
}

/* JParameter classes on radio button labels  */
fieldset.panelform-legacy label.radiobtn-jno,
fieldset.panelform-legacy label.radiobtn-jyes,
fieldset.panelform-legacy label.radiobtn-show,
fieldset.panelform-legacy label.radiobtn-hide,
fieldset.panelform-legacy label.radiobtn-off,
fieldset.panelform-legacy label.radiobtn-on	{
	min-width: 40px !important;
	clear: none !important;
}

#jform_plugdesc-lbl,
#jform_description-lbl {
	clear: both;
	margin-top: 15px;
}

p.jform_desc {
	clear: left;
}

div#jform_ordering {
	font-size: 1.091em;
	margin-top: 3px;
}

fieldset.filter {
	border: 0;
	margin: 0;
	padding: 0 0 5px;
}

fieldset.filter ol {
	border: 0;
	list-style: none;
	margin: 0;
	padding: 5px 0 0;
}

fieldset.filter ol li {
	float: left;
	padding: 0 5px 0 0;
}

fieldset.filter ol li fieldset {
	border: 0;
	margin: 0;
	padding: 0;
}

fieldset.filter .left {
	float: left;
}

fieldset.filter .right {
	float: right;
}

fieldset.filter .right select {
	margin-left: 10px;
}

fieldset#filter-bar {
	height: 35px;
	border: 0;
	border-bottom: 1px solid #d5d5d5;
}

label.filter-search-lbl {
	margin-left: 5px;
}

label.filter-hide-lbl {
	margin-left: 5px;
	clear: none;
}

div.filter-select input,
div.filter-select select {
	margin-left: 5px;
	margin-right: 5px;
}

button.filter-go {
	float: left;
	margin-right: 15px;
}

label.filter-published-lbl {
	clear: none;
	margin-left: 10px;
}

table.adminform {
	background-color: #fff;
	border: solid 1px #d5d5d5;
	width: 100%;
	border-collapse: collapse;
	margin: 8px 0 10px 0;
	margin-bottom: 15px;
	width: 100%;
}

table.adminform tr.row0 {
	background-color: #f9f9f9;
}

table.adminform tr.row1 {
	background-color: #eeeeee;
}

table.adminform th {
	font-size: 1.091em;
	padding: 6px 2px 4px 4px;
	text-align: left;
	height: 25px;
	color: #000;
	background-repeat: repeat;
}

table.adminform td {
	padding: 5px;
	text-align: left;
	font-size: 1.091em;
}

table.adminform td.filter {
	text-align: left;
}

table.adminform td.helpMenu {
	text-align: right;
}

table#template-mgr td {
	padding: 15px 0;
}

table#template-mgr td p {
	margin: 3px;
}

td.template-name a {
	padding-left: 15px;
	font-weight: bold;
}

.helplinks {
	margin-top: 60px;
}

ul.helpmenu li {
	float: right;
	margin: 10px;
	padding: 0;
	list-style-type: none;
}

input.text-area-order {
	text-align: center;
	margin-right: 5px;
}

fieldset.uploadform label {
	clear: left;
	min-width: 100px;
}

span.gi {
	color: #d7d7d7;
	font-weight: bold;
	margin-right: 5px;
}

span.gtr {
	visibility:hidden;
	margin-right: 5px;
}

ul#legend li {
	float: left;
	margin: 20px;
	list-style-type: none;
}

#jform_params_target { width: 190px;}


/* Adminlist grids */

table.adminlist {
	width: 100%;
	border-spacing: 1px;
	background-color: #f3f3f3;
	color: #666;
}

table.adminlist td,
table.adminlist th {
	padding: 4px;
}

table.adminlist td {padding-left: 8px;}

table.adminlist thead th {
	text-align: center;
	background: #f7f7f7;
	color: #666;
	border-bottom: 1px solid #CCC;
	border-left: 1px solid #fff;
}

table.adminlist thead th.left {
	text-align: left;
}

table.adminlist thead a:hover {
	text-decoration: none;
}

table.adminlist thead th img {
	vertical-align: middle;
	padding-left: 3px;
}

table.adminlist tbody th {
	font-weight: bold;
}

table.adminlist tbody tr {
	background-color: #fff;
	text-align: left;
}

table.adminlist tbody tr.row0:hover td,
table.adminlist tbody tr.row1:hover td	{
	background-color: #e8f6fe;
}

table.adminlist tbody tr td {
	background: #fff;
	border: 1px solid #fff;
}

table.adminlist tbody tr.row1 td {
	background: #f0f0f0;
	border-top: 1px solid #FFF;
}

table.adminlist tfoot tr {
	text-align: center;
	color: #333;
}

table.adminlist tfoot td,table.adminlist tfoot th {
	background-color: #f7f7f7;
	border-top: 1px solid #999;
	text-align: center;
}

table.adminlist td.order {
	text-align: center;
	white-space: nowrap;
	width: 200px;
}

table.adminlist td.order span {
	float: left;
	width: 20px;
	text-align: center;
	background-repeat: no-repeat;
	height: 13px;
}

table.adminlist .pagination {
	display: inline-block;
	padding: 0;
	margin: 0 auto;
}

table.adminlist td.indent-4 	{	padding-left: 4px;		}
table.adminlist td.indent-19 	{	padding-left: 19px;		}
table.adminlist td.indent-34 	{	padding-left: 34px;		}
table.adminlist td.indent-49 	{	padding-left: 49px;		}
table.adminlist td.indent-64 	{	padding-left: 64px;		}
table.adminlist td.indent-79 	{	padding-left: 79px;		}
table.adminlist td.indent-94 	{	padding-left: 94px;		}
table.adminlist td.indent-109 	{	padding-left: 109px;	}
table.adminlist td.indent-124 	{	padding-left: 124px;	}
table.adminlist td.indent-139 	{	padding-left: 139px;	}

table.adminlist tr td.btns a {
	text-decoration: underline;
}

#permissions-sliders ul#rules,
#permissions-sliders ul#rules ul
{  
    margin:0 0 0 0px !important;
    padding:0 0 0 0px !important;
    border:solid 0px #ccc; 
    background:#fff; 
    list-style-type:none;
}

#permissions-sliders ul#rules li
{
	margin:0px 0 0 0%;
	padding:0;
}

ul#rules li .pane-sliders .panel h3.title
{
	border:solid 0px #ccc;
}

#permissions-sliders ul#rules .pane-slider
{
		border:solid 1px #ccc;
}

#permissions-sliders ul#rules .pane-slider.pane-hide
{
	display:none;
}

#permissions-sliders ul#rules li h3
{
	background:#fafafa;
	 font-size:1.10em;
}

#permissions-sliders ul#rules li h3
{
	border: solid 1px #ccc;
}

#permissions-sliders ul#rules li h3.pane-toggler-down a
{	
	border:solid 0px;
}

#permissions-sliders ul#rules li h3.pane-toggler-down
{
	color:#000; 
	/*background:#146295;*/
}
    
#permissions-sliders ul#rules li h3.pane-toggler-down span
{ 
	/*color:#fff; */
}
        
        
#permissions-sliders ul#rules .group-kind
{
	color:#025A8D;
}
            
#permissions-sliders ul#rules table.group-rules
{
    border-collapse:collapse;
     width:100%
}

#permissions-sliders ul#rules table.group-rules td
{
    border:solid 1px #ccc; 
    padding:4px;
    vertical-align:middle; 
    text-align:left;
    overflow:hidden
}

#permissions-sliders ul#rules table.group-rules th
{
    background:#ddd; 
    border:solid 1px #ddd; 
    font-size:0.9em; 
    color:#025A8D;
    overflow:hidden
}

#permissions-sliders .panel 
{
    margin-bottom: 3px;
    margin-left:0;
    border:0;
}

#permissions-sliders p.rule-desc
{
	font-size: 1.091em;
	margin-left: 0;
}

#permissions-sliders div.rule-notes
	
{
	font-size: 1.091em;
}

ul#rules table.aclmodify-table
{
	border:solid 1px #000
}
	
ul#rules table.group-rules td label
{
	border:solid 0px #000;
	margin:0 !important
}

ul#rules table.group-rules td span
{
	padding-bottom: 4px;
}

table.group-rules td select
{
	margin:0 !important
}
	
#permissions-sliders ul#rules .mypanel
{
	background:#ffffff; 
	padding:0px; 
}

#permissions-sliders .mypanel table.group-rules
{
	margin: 5px;
} 

#permissions-sliders .mypanel table.group-rules caption
{
}  

#permissions-sliders ul#rules 
{	
	padding:5px
}

#permissions-sliders  ul#rules  table.group-rules th
{
    text-align:left;
    padding:4px
}

#permissions-sliders .pane-toggler span
{
	padding-left:20px;
}

#permissions-sliders .pane-toggler-down span
{
}

#permissions-sliders .pane-toggler-down span
{
	padding-left:20px;
}
	
#permissions-sliders .pane-toggler-down span.level,
#permissions-sliders .pane-toggler span.level
{
	color:#aaa;
	background-image:none;
	padding: 0;
}

#permissions-sliders ul#rules .panel h3
{
}
	
/* global_config permissions */

#page-permissions  fieldset ul#rules  .pane-slider
{
}

#permissions-sliders
{
}
';
        }
        
        /**
         * Any editable template specific CSS?
         * Return it here
         * 
         * @return string
         */
        function getEditableTemplateCss(){
            
            return $this->getContentTemplateCss();
        }
        
        /**
         * Any list view specific CSS?
         * Return it here
         * 
         * @return string
         */
        function getListViewCss(){
            
            return '';
        }
        
        /**
         * Return the sample html code for content here (triggered in view admin, after checking "SAMPLE"
         * 
         * @return string
         */
        function getContentTemplateSample($contentbuilder_form_id, $form){
            $db = JFactory::getDBO();
            $out = '<table border="0" width="100%" class="admintable adminlist"><tbody>'."\n";
            $names = $form->getElementNames();
            foreach($names As $reference_id => $name){
                $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
                $result = $db->loadAssoc();
                if( is_array($result) ){
                    if($result['type'] != 'hidden'){
                        $out .= '{hide-if-empty '.$name.'}'."\n\n";
                        $out .= '<tr class="row0"><td width="20%" class="key" valign="top"><label>{'.$name.':label}</label></td><td>{'.$name.':value}</td></tr>'."\n\n";
                        $out .= '{/hide}'."\n\n";
                    }
                }
            }
            $out .= '</tbody></table>'."\n";
            return $out;
        }
        
        /**
         * Return the sample html code for editables here (triggered in view admin, after checking "SAMPLE"
         * 
         * @return string
         */
        function getEditableTemplateSample($contentbuilder_form_id, $form){
            $db = JFactory::getDBO();
            $out = '<table border="0" width="100%" class="admintable adminlist"><tbody>'."\n";
            $names = $form->getElementNames();
            $hidden = array();
            foreach($names As $reference_id => $name){
                $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And editable = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
                $result = $db->loadAssoc();
                if( is_array($result) ){
                    if($result['type'] != 'hidden'){
                        $out .= '<tr class="row0"><td width="20%" class="key" valign="top">{'.$name.':label}</td><td>{'.$name.':item}</td></tr>'."\n";
                    } else {
                        $hidden[] = '{'.$name.':item}'."\n";
                    }
                }
            }
            $out .= '</tbody></table>'."\n";
            foreach($hidden As $hid){
                $out .= $hid;
            }
            return $out;
        }
}
