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
        if (task == 'application.cancel' || document.formvalidator.isValid(document.id('application-form')))
        {
            Joomla.submitform(task, document.getElementById('application-form'));
        }
    }
</script>
<style>
.element {
	position: relative;
	width:93%; 
	margin:0px 0px 5px 0px;
	padding:2%;
	clear:both;
	overflow: hidden;
	border:1px solid #DEDEDE;
	background:#F9F9F9;
}
.element > div {
	display:table-cell;
}
.element div.left-block {
	padding-right:10px;
}
.element div.left-block .main-image-block {
	clear:both; 
}
.element div.left-block .thumbs-block {
	position:relative;
	margin-top:10px;
}
.element div.left-block .thumbs-block ul {
	width:240px; 
	height:auto;
	display:table;
	margin:0px;
	padding:0px;
	list-style:none;
}
.element div.left-block .thumbs-block ul li {
	margin:0px 3px 0px 2px;
	padding:0px;
	width:75px; 
	height:75px; 
	float:left;
}
.element div.left-block .thumbs-block ul li a {
	display:block;
	width:75px; 
	height:75px; 
}
.element div.left-block .thumbs-block ul li a img {
	width:75px; 
	height:75px; 
}
.element div.right-block {
	vertical-align:top;
}
.element div.right-block > div {
	width:100%;
	padding-bottom:10px;
	margin-top:10px;
}
.element div.right-block > div:last-child {
	background:none;
}
.element div.right-block .title-block  {
	margin-top:20px;
}
.element div.right-block .title-block h3 {
	margin:0px;
	padding:0px;
	font-weight:normal;
	font-size:18px !important;
	line-height:18px !important;
	color:#0074A2;
}
.element div.right-block .description-block p,.element div.right-block .description-block * {
	margin:0px;
	padding:0px;
	font-weight:normal;
	font-size:14px;
	color:#555555;
}
.element div.right-block .description-block h1,
.element div.right-block .description-block h2,
.element div.right-block .description-block h3,
.element div.right-block .description-block h4,
.element div.right-block .description-block h5,
.element div.right-block .description-block h6,
.element div.right-block .description-block p, 
.element div.right-block .description-block strong,
.element div.right-block .description-block span {
	padding:2px !important;
	margin:0px !important;
}
.element div.right-block .description-block ul,
.element div.right-block .description-block li {
	padding:2px 0px 2px 5px;
	margin:0px 0px 0px 8px;
}
.element .button-block {
	position:relative;
}
.element div.right-block .button-block a,.element div.right-block .button-block a:link,.element div.right-block .button-block a:visited {
	position:relative;
	display:inline-block;
	padding:6px 12px;
	background:#2EA2CD;
	color:#FFFFFF;
	font-size:14;
	text-decoration:none;
}
.element div.right-block .button-block a:hover,.pupup-elemen.element div.right-block .button-block a:focus,.element div.right-block .button-block a:active {
	background:#0074A2;
	color:#FFFFFF;
}
.button-block a {
	float: right;
}
.description-block p {
	text-align: justify !important;
}
@media only screen and (max-width: 767px) {
	.element > div {
		display:block;
		width:100%;
		clear:both;
	}
	.element div.left-block {
		padding-right:0px;
	}
	.element div.left-block .main-image-block {
		clear:both;
		width:100%; 
	}
	.element div.left-block .main-image-block img {
		width:100% !important;  
		height:auto;
	}
	.element div.left-block .thumbs-block ul {
		width:100%; 
	}
}
</style>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'application.cancel' || document.formvalidator.isValid(document.id('application-form')))
        {
            Joomla.submitform(task, document.getElementById('application-form'));
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
                    <li>
                            <a href="index.php?option=com_videogallerylite&amp;view=general"><?php echo JText::_('COM_GENERAL'); ?></a>
                    </li>
                    <li>
                            <a href="index.php?option=com_videogallerylite&amp;view=lightbox"><?php echo JText::_('COM_VIDEOGALLERYLITE_LIGHTBOX'); ?></a>
                    </li>
                    <li class="active">
                            <a href="index.php?option=com_videogallerylite&amp;view=featured"><?php echo JText::_('COM_FEATURED'); ?></a>
                    </li>
                </ul>
											</div>
	</div>
	<div id="j-toggle-sidebar"></div>
</div>
<div id="j-main-container" class="span10 j-toggle-main">
    <div class="">
    <?php $path_site2 = JUri::root()."media/com_videogallerylite/images"; 
	?>
	<style>
		.free_version_banner {
			position:relative;
			display:block;
			background-image:url(<?php echo $path_site2; ?>/wp_banner_bg.jpg);
			background-position:top left;
			backround-repeat:repeat;
			overflow:hidden;
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
		<div  class="description_text"><p>This is the free version of the plugin. Click "GET THE FULL VERSION" for more advanced options.   We appreciate every customer.</p></div>
	</div>
        <div style="clear:both;"></div>
	
		</div>
    
    <form action="<?php echo JRoute::_('index.php?option=com_videogallerylite'); ?>" id="application-form" method="post" name="adminForm" class="form-validate" style="margin-top:10px">
        <div class="element hugeitmicro-item">
            <div class="left-block">
                <div class="main-image-block">
                    <a href="<?php echo JUri::root() . 'media/com_videogallerylite/images/lightbox.png'; ?>" rel="content"><img src="<?php echo JUri::root() . 'media/com_videogallerylite/images/lightbox.png'; ?>"></a>
                </div>
            </div>
            <div class="right-block">
                <div class="title-block"><h3>Lightbox</h3></div>
                <div class="description-block">
                    <p>Joomla Lightbox is a perfect tool for viewing photos. It is created especially for simplification of using, permits you to view larger version of images and giving an interesting design. With the help of slideshow and various styles, betray a unique image to your website.</p>
                </div>			  				
                <div class="button-block">
                    <a href="http://huge-it.com/joomla-lightbox/" target="_blank">View Extension</a>
                </div>
            </div>
        </div>


        <div class="element hugeitmicro-item">
            <div class="left-block">
                <div class="main-image-block">
                    <a href="<?php echo JUri::root() . 'media/com_videogallerylite/images/gallery.png'; ?>" rel="content"><img src="<?php echo JUri::root() . 'media/com_videogallerylite/images/gallery.png'; ?>"></a>
                </div>
            </div>
            <div class="right-block">
                <div class="title-block"><h3>Gallery</h3></div>
                <div class="description-block">
                    <p>Huge - IT Image gallery for Joomla is wonderful extension for those who needs to show image in wonderful gallery with  five views. with the help of our module you can create various sliders with many styles, it includes awesome lightbox with itР Р†Р вЂљРІвЂћСћs options for any taste. The module allows to add descriptions and titles for every image of the Gallery.</p>
                </div>			  				
                <div class="button-block">
                    <a href="http://huge-it.com/joomla-gallery/" target="_blank">View Extension</a>
                </div>
            </div>
        </div>


      <div class="element hugeitmicro-item">
            <div class="left-block">
                <div class="main-image-block">
                    <a href="<?php echo JUri::root() . 'media/com_videogallerylite/images/catalog.png'; ?>" rel="content"><img src="<?php echo JUri::root() . 'media/com_videogallerylite/images/catalog.png'; ?>"></a>
                </div>
            </div>
            <div class="right-block">
                <div class="title-block"><h3>Catalog</h3></div>
                <div class="description-block">
                    <p> Huge-IT Catalog is made for demonstration, sale, advertisements for your products. Imagine a stand with a variety of catalogs with a specific product category. To imagine is not difficult, to use is even easier. </p>
                </div>			  				
                <div class="button-block">
                    <a href="http://huge-it.com/joomla-catalog/" target="_blank">View Extension</a>
                </div>
            </div>
        </div>
        <div class="element hugeitmicro-item">
            <div class="left-block">
                <div class="main-image-block">
                    <a href="<?php echo JUri::root() . 'media/com_videogallerylite/images/map.png'; ?>" rel="content"><img src="<?php echo JUri::root() . 'media/com_videogallerylite/images/map.png'; ?>"></a>
                </div>
            </div>
            <div class="right-block">
                <div class="title-block"><h3>Google Map</h3></div>
                <div class="description-block">
                    <p>Huge-IT Google Map. One more perfect extensions from Huge-IT. Improved Google Map, where we have our special contribution. Most simple and effective tool for rapid creation of individual Google Map in posts and pages. </div>			  				
                <div class="button-block">
                    <a href="http://huge-it.com/joomla-google-maps/" target="_blank">View Extension</a>
                </div>
            </div>
        </div>

        <div class="element hugeitmicro-item">
            <div class="left-block">
                <div class="main-image-block">
                    <a href="<?php echo JUri::root() . 'media/com_videogallerylite/images/portfolio.png'; ?>" rel="content"><img src="<?php echo JUri::root() . 'media/com_videogallerylite/images/portfolio.png'; ?>"></a>
                </div>
            </div>
            <div class="right-block">
                <div class="title-block"><h3>Portfolio Gallery</h3></div>
                <div class="description-block">
                    <p>Portfolio Gallery - one of the wonderful extensions for photo projects, show in portfolio in many different views. The module allows to add description text and titles for every portfolio project. Choose one from 7 views and reflect the whole essence of all projects . </p>
                </div>			  				
                <div class="button-block">
                    <a href="http://huge-it.com/joomla-portfolio/" target="_blank">View Extension</a>
                </div>
            </div>
        </div>


       <div class="element hugeitmicro-item">
    <div class="left-block">
            <div class="main-image-block">
                <a href="<?php echo JUri::root(). 'media/com_videogallerylite/images/slider.png'; ?>" rel="content"><img src="<?php echo JUri::root(). 'media/com_videogallerylite/images/slider.png'; ?>"></a>
            </div>
    </div>
    <div class="right-block">
            <div class="title-block"><h3>Joomla Slider</h3></div>
            <div class="description-block">
                    <p> Huge-IT Slider - one of the perfect Joomla extensions with a many nice features. Just install and make sliders in a few minutes.
It is a quick and easy way to add custom sliders to the Joomla websites.The slider allows having unlimited amount of images with their titles and descriptions. </p>
            </div>			  				
            <div class="button-block">
                    <a href="http://huge-it.com/joomla-slider/" target="_blank">View Extension</a>
            </div>
    </div>
</div>


        <div class="element hugeitmicro-item">
            <div class="left-block">
                <div class="main-image-block">
                    <a href="<?php echo JUri::root() . 'media/com_videogallerylite/images/slideshow.png'; ?>" rel="content"><img src="<?php echo JUri::root() . 'media/com_videogallerylite/images/slideshow.png'; ?>"></a>
                </div>
            </div>
            <div class="right-block">
                <div class="title-block"><h3>Slideshow</h3></div>
                <div class="description-block">
                    <p>Slideshow one more perfect tool from Huge-IT developers. With the help of Slideshow you can demonstrate your images/videos with beautiful slide effect. 
                        Joomla users can use the extension with unlimited amount of images, and slideshows in a page. </div>			  				
                <div class="button-block">
                    <a href="http://huge-it.com/joomla-slideshow/" target="_blank">View Extension</a>
                </div>
            </div>
        </div>
   
        <div>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </div>

    </form>
</div>