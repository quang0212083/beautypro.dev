<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'application.cancel' ||  document.formvalidator.isValid(document.id('application-form')))
		{
			Joomla.submitform(task, document.getElementById('application-form'));
		}
	}
</script>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'application.cancel' ||  document.formvalidator.isValid(document.id('application-form')))
		{
			Joomla.submitform(task, document.getElementById('application-form'));
                                    alert("Sorry, the General Settings are disabled in this free version, please purchase the commercial version for the full features.");

		}
	}
</script>

<div id="j-sidebar-container" class="j-sidebar-container j-sidebar-visible" style="border-top: 1px solid #e3e3e3;">
	<div id="j-toggle-button-wrapper" class="j-toggle-button-wrapper j-toggle-visible">
</div>
	<div id="sidebar" class="sidebar">
		<div class="sidebar-nav">
		<ul id="submenu" class="nav nav-list">
                    <li>
                            <a href="index.php?option=com_videogallerylite"><?php echo JText::_('COM_VIDEOGALLERYLITE'); ?></a>
                    </li>
                    <li class="active">
                            <a href="index.php?option=com_videogallerylite&amp;view=general"><?php echo JText::_('COM_GENERAL'); ?></a>
                    </li>
                    <li>
                            <a href="index.php?option=com_videogallerylite&amp;view=lightbox"><?php echo JText::_('COM_VIDEOGALLERYLITE_LIGHTBOX'); ?></a>
                    </li>
                    <li>
                            <a href="index.php?option=com_videogallerylite&amp;view=featured"><?php echo JText::_('COM_FEATURED'); ?></a>
                    </li>
                </ul>
		</div>
	</div>
	<div id="j-toggle-sidebar"></div>
</div>
		</div>
		<div id="j-main-container" class="span10 j-toggle-main">
                    
                    <div class="wrap">

                    <?php $path_site2 = JUri::root() . "media/com_videogallerylite/images" ?>

                    <div id="poststuff">
                        <?php $path_site2 = JUri::root() . "media/com_videogallerylite/images/"; ?>
                        <?php $path_site2 = JUri::root() . "media/com_videogallerylite/images";
                        ?>
                        <style>
                            .free_version_banner {
                                position:relative;
                                display:block;
                                background-image:url(<?php echo $path_site2; ?>/wp_banner_bg.jpg);
                                background-position:top left;
                                backround-repeat:repeat;
                                overflow:hidden;
                                margin-left:12px;
                            }

                            .free_version_banner .manual_icon {
                                position:absolute;
                                display:block;
                                top:15px;
                                left:15px;
                            }

                            .free_version_banner .usermanual_text {
                                font-weight: bold !important;
                                display:block;
                                float:left;
                                width:270px;
                                margin-left:75px;
                                font-family:'Open Sans',sans-serif;
                                font-size:12px;
                                font-weight:300;
                                font-style:italic;
                                color:#ffffff;
                                line-height:10px;
                                margin-top: 0;
                                padding-top: 15px;
                            }

                            .free_version_banner .usermanual_text a,
                            .free_version_banner .usermanual_text a:link,
                            .free_version_banner .usermanual_text a:visited {
                                display:inline-block;
                                font-family:'Open Sans',sans-serif;
                                font-size:17px;
                                font-weight:600;
                                font-style:italic;
                                color:#ffffff;
                                line-height:30.5px;
                                text-decoration:underline;
                            }

                            .free_version_banner .usermanual_text a:hover,
                            .free_version_banner .usermanual_text a:focus,
                            .free_version_banner .usermanual_text a:active {
                                text-decoration:underline;
                            }

                            .free_version_banner .get_full_version,
                            .free_version_banner .get_full_version:link,
                            .free_version_banner .get_full_version:visited {
                                padding-left: 60px;
                                padding-right: 4px;
                                display: inline-block;
                                position: absolute;
                                top: 15px;
                                right: calc(50% - 167px);
                                height: 38px;
                                width: 285px;
                                border: 1px solid rgba(255,255,255,.6);
                                font-family: 'Open Sans',sans-serif;
                                font-size: 23px;
                                color: #ffffff;
                                line-height: 43px;
                                text-decoration: none;
                                border-radius: 2px;
                            }

                            .free_version_banner .get_full_version:hover {
                                background:#ffffff;
                                color:#bf1e2e;
                                text-decoration:none;
                                outline:none;
                            }

                            .free_version_banner .get_full_version:focus,
                            .free_version_banner .get_full_version:active {

                            }

                            .free_version_banner .get_full_version:before {
                                content:'';
                                display:block;
                                position:absolute;
                                width:33px;
                                height:23px;
                                left:25px;
                                top:9px;
                                background-image:url(<?php echo $path_site2; ?>/wp_shop.png);
                                background-position:0px 0px;
                                background-repeat:repeat;
                            }

                            .free_version_banner .get_full_version:hover:before {
                                background-position:0px -27px;
                            }

                            .free_version_banner .huge_it_logo {
                                float:right;
                                margin:15px 15px;
                            }

                            .free_version_banner .description_text {
                                padding:0 0 13px 0;
                                position:relative;
                                display:block;
                                width:100%;
                                text-align:center;
                                float:left;
                                font-family:'Open Sans',sans-serif;
                                color:#fffefe;
                                line-height:inherit;
                            }
                            .free_version_banner .description_text p{
                                margin:0;
                                padding:0;
                                font-size: 14px;
                            }
                        </style>
                        <div class="free_version_banner">
                            <img class="manual_icon" src="<?php echo $path_site2; ?>/icon-user-manual.png" alt="user manual" />
                            <p class="usermanual_text">If you have any difficulties in using the options, Follow the link to <a href="http://huge-it.com/joomla-video-gallery-user-manual/" target="_blank">User Manual</a></p>
                            <a class="get_full_version" href="http://huge-it.com/joomla-video-gallery/" target="_blank">GET THE FULL VERSION</a>
                            <a href="http://huge-it.com" target="_blank"><img class="huge_it_logo" src="<?php echo $path_site2; ?>/Huge-It-logo.png"/></a>
                            <div style="clear: both;"></div>
                            <div  class="description_text"><p>This is the free version of the plugin. In order to use options from this section, get the full version. We appreciate every customer.</p></div>
                        </div>
                        <div style="clear:both;"></div>
                        <div style="color: #a00; margin-bottom: 15px;margin-left:12px">This options are for commercial users, it includes one of Personal, Multi-Site or Developer versions.Please upgrade to use this section. 
                        </div>

                    </div>
                </div>
<form action="<?php echo JRoute::_('index.php?option=com_videogallerylite&views=videogallerylites'); ?>" id="application-form" method="post" name="adminForm" class="form-validate">
    <div class="row-fluid" >
        <div class="span10" style="width:100%">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#page-site" data-toggle="tab"><?php echo JText::_('Video Gallery Lite/Content-Popup'); ?></a></li>
                <li><a href="#page-system" data-toggle="tab"><?php echo JText::_('CONTENT_SLIDER'); ?></a></li>
                <li><a href="#page-server" data-toggle="tab"><?php echo JText::_('LIGHTBOX_VIDEOGALLERYLITE'); ?></a></li>
                <li><a href="#page-perm" data-toggle="tab"><?php echo JText::_('SLIDER'); ?></a></li>
                <li><a href="#page-filters" data-toggle="tab"><?php echo JText::_('THUMBNAILS'); ?></a></li>
            </ul>
            
            <div id="config-document" class="tab-content" class="dconfig"  style="width:100%">
                <div id="page-site" class="tab-pane active">
                    <div class="row-fluid">
                        <?php echo $this->loadTemplate('Blocks_Toggle_Up_Down'); ?>
                    </div>
                </div>
                <div id="page-system" class="tab-pane">
                    <div class="row-fluid">
                        <?php echo $this->loadTemplate('Content_Slider'); ?>    
                    </div>
                </div>
                <div id="page-server" class="tab-pane">
                    <div class="row-fluid">
                        <?php echo $this->loadTemplate('Lightbox_Gallery'); ?>    
                    </div>
                </div>
                <div id="page-perm" class="tab-pane">
                    <div class="row-fluid">
                        <?php echo $this->loadTemplate('Slider'); ?>      
                    </div>
                </div>
                <div id="page-filters" class="tab-pane">
                    <div class="row-fluid">
                        <?php echo $this->loadTemplate('Thumbnails'); ?>  
                    </div>
                </div>
                </div>
        </div>
    </div>

    <div>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
   </div>
</form>