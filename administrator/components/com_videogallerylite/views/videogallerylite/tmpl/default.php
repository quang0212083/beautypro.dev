<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 

defined('_JEXEC') or die;
JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/admin.style.css');
JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/simple-slider1.css');
JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/simple-slider_sl.css');
$doc = JFactory::getDocument();
$editor = JFactory::getEditor('tinymce');
$doc->addScript(JURI::root(true) . "/media/com_videogallerylite/js/param_block.js");
JHTML::_('behavior.modal');
?>
<script src="<?php echo JURI::root(true) ?>/media/com_videogallerylite/js/admin.js"></script>
<script src="<?php echo JURI::root(true) ?>/media/com_videogallerylite/js/simple-gallery.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js" ></script>
<script src="/media/media/js/mediafield-mootools.min.js" type="text/javascript"></script>

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

<script type="text/javascript">
    var time = 0;
    par_images = [];
    Joomla.submitbutton = function(pressbutton)
    {
        if (document.adminForm.name.value == '' && pressbutton != 'cancel')
        {
            alert('Name is required.');
            document.adminForm.name.focus();
        }
        else
            submitform(pressbutton);
    }
</script>

<script type="text/javascript">
    var image_base_path = '<?php
$params = JComponentHelper::getParams('com_media');
echo $params->get('image_path', 'images');
?>/';
    function submitbutton(pressbutton)
    {
        if (!document.getElementById('name').value) {
            alert("Name is required.");
            return;
        }

        document.getElementById("adminForm").action = document.getElementById("adminForm").action + "&task=" + pressbutton;
        document.getElementById("adminForm").submit();
    }
    function change_select() {
        submitbutton('apply');
    }
    jQuery(function() {
        jQuery("#images-list").sortable({
            stop: function() {
                jQuery("#images-list > li").removeClass('has-background');
                count = jQuery("#images-list > li").length;
                for (var i = 0; i <= count; i += 2) {
                    jQuery("#images-list > li").eq(i).addClass("has-background");
                }
                jQuery("#images-list > li").each(function() {
                    jQuery(this).find('.order_by').val(jQuery(this).index());
                });
            },
            revert: true
        });

    });
</script>

<?php 
function get($url) {
    if (ini_get('allow_url_fopen')) return file_get_contents($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_videogallerylite&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm"  enctype="multipart/form-data">
    <div id="poststuff" >
        <div id="gallery-header">
            <ul id="gallerys-list">
                <?php
                foreach ($this->galleryParams as $rowsldires) {
                    if ($rowsldires->id != $this->item->id) {
                        ?>
                        <li>
                            <a href="#" onclick="window.location.href = 'index.php?option=com_videogallerylite&view=videogallery&layout=edit&id=<?php echo $rowsldires->id; ?>'" ><?php echo $rowsldires->name; ?></a>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="active" style='background-image:url("<?= JURI::root() . 'media/com_videogallerylite/images/edit.png' ?>")'>
                            <input class="text_area" onfocus="this.style.width = ((this.value.length + 1) * 8) + 'px'" type="text" name="name" id="name" maxlength="250" value="<?php echo stripslashes($this->item->name); ?>" />
                        </li>
                        <?php
                    }
                }
                ?>
                <li class="add-new">
                    <a onclick="window.location.href = 'index.php?option=com_videogallerylite&view=videogallerylite&task=videogallerylites.add'">+</a>
                </li>
            </ul>
        </div>
          <div id="post-body-wrapper" class="metabox-holder columns-2">
            <div id="post-body-heading">
              <input type="hidden" name="imagess" id="jform_images_image_intro" />
                <div class="huge-it-newuploader uploader button button-primary add-new-image">
                    <a class="btn btn-small btn-success modal btn" rel="{handler: 'iframe',size: {x: 800, y: 500}}" href="index.php?option=com_videogallerylite&view=video&tmpl=component&pid=<?php echo $_GET['id']; ?>" title="Image"  onclick="jInsertFieldValue('', 'jform_images_image_intro'); return false;" data-original-title="Clear">
                        Add Video
                    </a>
                </div>
            </div>
        </div>

<div id="video-sidebar-container" class="video-sidebar-container video-sidebar-visible" >
		
<div id="video-toggle-sidebar-wrapper">

	<div id="sidebar" class="sidebar">
		<div class="sidebar-nav">
			<div class="filter-select hidden-phone">
                            <label for="filter_client_id" class="element-invisible"></label>
                                <ul id="submenu" class="nav nav-list">
                                    <li class="active">
                                        <a href="index.php?option=com_videogallerylite">Huge-IT Video Gallery</a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_videogallerylite&amp;view=general">General Options</a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_videogallerylite&amp;view=lightbox">Lightbox Options</a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_videogallerylite&amp;view=featured">Featured Products</a>
                                    </li>
                                </ul>
                    <hr class="hr-condensed" style = "border-bottom: 1px solid #ccc" >
                    <label for="filter_module" class="element-invisible">- Sel -</label>
                    <select name="filter_module" id="filter_module" class="span12 small chzn-done" onchange="this.form.submit()" style="display: none;">
                        <option value="">- Select Type -</option>
                        <option value="mod_breadcrumbs">Breadcrumbs</option>
                        <option value="mod_slider">Huge-IT Slider Module</option>
                        <option value="mod_lightbox">Lightbox</option>
                        <option value="mod_login">Login</option>
                        <option value="mod_menu">Menu</option>
                        <option value="mod_videogallery">Video Gallery  Module</option>
                    </select>
<div class="chzn-container chzn-container-single chzn-container-single-nosearch"  title="" id="filter_module_chzn">
	<span style="font-weight: bold;">Select The Video Gallery View</span>
	<div><b></b></div>
	<div class="chzn-drop">
            <div class="chzn-search"></div>
		<ul id="gallery-unique-options-list" style="margin-top:10px">
                    <li>
                        <select name="huge_it_sl_effects" id="huge_it_sl_effects">
                            <option <?php if($this->item->huge_it_sl_effects == '0'){ echo 'selected'; } ?>  value="0">Video Gallery/Content-Popup</option>
                            <option <?php if($this->item->huge_it_sl_effects == '1'){ echo 'selected'; } ?>  value="1">Content Video Slider</option>
                            <option <?php if($this->item->huge_it_sl_effects == '5'){ echo 'selected'; } ?>  value="5">Lightbox-Video Gallery</option>
                            <option <?php if($this->item->huge_it_sl_effects == '3'){ echo 'selected'; } ?>  value="3">Video Slider</option>
                            <option <?php if($this->item->huge_it_sl_effects == '4'){ echo 'selected'; } ?>  value="4">Thumbnails View</option>
                            <option <?php if($this->item->huge_it_sl_effects == '6'){ echo 'selected'; } ?>  value="6">Justified</option>
                            <option <?php if($this->item->huge_it_sl_effects == '7'){ echo 'selected'; } ?>  value="7">Blog Style Gallery</option>
                        </select>
                    </li>
                </ul>
                <div id="gallery-current-options-3" class="gallery-current-options <?php if ($this->item->huge_it_sl_effects == 3) {
    echo ' active';
} ?>">
                            <ul id="slider-unique-options-list">
                                <li>
                                    <label for="sl_width">Width</label>
                                    <input type="text" name="sl_width" id="sl_width" value="<?php echo $this->item->sl_width; ?>"  />
                                </li>
                                <li>
                                    <label for="sl_height">Height</label>
                                    <input type="text" name="sl_height" id="sl_height" value="<?php echo $this->item->sl_height; ?>" />
                                </li>
                                <li>
                                    <label for="pause_on_hover">Pause on hover</label>
                                    <input type="hidden" value="off" name="pause_on_hover" />					
                                    <input type="checkbox" name="pause_on_hover"  value="on" id="pause_on_hover"  <?php if ($this->item->pause_on_hover == 'on') {
    echo 'checked="checked"';
} ?> />
                                </li>
                                <li>
                                    <label for="videogallery_list_effects_s">Effects</label>
                                    <select name="videogallery_list_effects_s" id="videogallery_list_effects_s">
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'none') {
    echo 'selected';
} ?>  value="none">None</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'cubeH') {
    echo 'selected';
} ?>   value="cubeH">Cube Horizontal</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'cubeV') {
    echo 'selected';
} ?>  value="cubeV">Cube Vertical</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'fade') {
    echo 'selected';
} ?>  value="fade">Fade</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'sliceH') {
    echo 'selected';
} ?>  value="sliceH">Slice Horizontal</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'sliceV') {
    echo 'selected';
} ?>  value="sliceV">Slice Vertical</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'slideH') {
    echo 'selected';
} ?>  value="slideH">Slide Horizontal</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'slideV') {
    echo 'selected';
} ?>  value="slideV">Slide Vertical</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'scaleOut') {
    echo 'selected';
} ?>  value="scaleOut">Scale Out</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'scaleIn') {
            echo 'selected';
        } ?>  value="scaleIn">Scale In</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'blockScale') {
            echo 'selected';
        } ?>  value="blockScale">Block Scale</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'kaleidoscope') {
            echo 'selected';
        } ?>  value="kaleidoscope">Kaleidoscope</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'fan') {
            echo 'selected';
        } ?>  value="fan">Fan</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'blindH') {
            echo 'selected';
        } ?>  value="blindH">Blind Horizontal</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'blindV') {
            echo 'selected';
        } ?>  value="blindV">Blind Vertical</option>
                                        <option <?php if ($this->item->videogallery_list_effects_s == 'random') {
            echo 'selected';
        } ?>  value="random">Random</option>
                                    </select>
                                </li>

                                <li>
                                    <label for="sl_pausetime">Pause time</label>
                                    <input type="text" name="sl_pausetime" id="sl_pausetime" value="<?php echo $this->item->description; ?>"  />
                                </li>
                                <li>
                                    <label for="sl_changespeed">Change speed</label>
                                    <input type="text" name="sl_changespeed" id="sl_changespeed" value="<?php echo $this->item->param; ?>" />
                                </li>
                                <li>
                                    <label for="slider_position">Slider Position</label>
                                    <select name="sl_position" id="slider_position">
                                        <option <?php if ($this->item->sl_position == 'left') {
            echo 'selected';
        } ?>  value="left">Left</option>
                                        <option <?php if ($this->item->sl_position == 'right') {
            echo 'selected';
        } ?>   value="right">Right</option>
                                        <option <?php if ($this->item->sl_position == 'center') {
            echo 'selected';
        } ?>  value="center">Center</option>
                                    </select>
                                </li>
                            </ul>
                                    
                        </div>
    <script> 
        window.onload = function() {
        setView();
    };
    
    function setView(){
    var display_type = document.getElementById('display_type');
    
    var content_per_page = document.getElementById('content_per_page1');
    var selected = display_type.options[display_type.selectedIndex].value; 
        if(selected == 2) {
            content_per_page.setAttribute("style", "display: none;");
         
        }else {
             content_per_page.setAttribute("style", "display: block;");
      }}</script>
                <div id="videogallery-current-options" class="videogallery-current-options <?php if($this->item->huge_it_sl_effects == 0){ echo ' active'; }  ?>">
                    <ul style="margin: 0px;padding:0px">
                        <li>
                            <label>Displaying Content</label>
                            <select id="display_type" name="display_type" onchange="setView()">
                                <option <?php if($this->item->display_type == 0){ echo 'selected'; } ?>   value="0">Pagination</option>
                                <option <?php if($this->item->display_type == 1){ echo 'selected'; } ?>   value="1">Load More</option>
                                <option <?php if($this->item->display_type == 2){ echo 'selected'; } ?>   value="2">Show All</option>
                            </select>
                        </li>
                        <li id="content_per_page1">
                            <label for="content_per_page">Videos Per Page</label>
                            <input type="text" name="content_per_page" id="content_per_page" value="<?php echo $this->item->content_per_page; ?>" class="text_area" />
                        </li>
                    </ul>
                </div>
                 
                    <hr class="hr-condensed" style = "border-bottom: 1px solid #ccc" >
                    <div class="chzn-search"></div>
                    
                    <div class="filter-select hidden-phone" style="padding: 0px">
                            <h4 >Shortcodes:</h4>
                            <div class="inside" style="width: 100%;  margin: 0 52px 0 -22px;">
                                <ul>
                                    <li>
                                        <div class="shortcodeText"><p>Copy &amp; paste the shortcode directly into any Joomla article.</p></div>
                                        <textarea class="full" readonly="readonly" style="width: 100%">[huge_it_videogallery_id="<?php echo $this->item->id; ?>"]</textarea>
                                    </li>

                                </ul>
                            </div>
                              <div class="inside" style="width: 100%;  margin: 0 52px 0 -22px;">
                                    <ul>
                                        <li>
                                            <div class="shortcodeText"><p>Copy & paste this code into a template file to include the video gallery within your theme.</p></div>
                                            <textarea class="full" readonly="readonly">&lt;?php echo huge_it_videogallery_id(<?php echo $this->item->id; ?>); ?&gt;</textarea>
                                        </li>

                                    </ul>
                                </div>
                        </div>
		</div>
		</div>
				
			
					
					</div>
					</div>
	</div>
	<div id="video-toggle-sidebar"></div>
</div>


     <div class="clr"></div>   
        
	</div>
	
	<div id="video-main-container" class="span10 video-toggle-main">
        <div id="post-body-content" >
            <ul id="images-list" style="padding: 0px">
                <?php

           function get_youtube_id_from_url($url){						
						if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
							return $match[1];
						}
					}
                ?>
                <?php $j = 2; ?>
                <?php foreach ($this->prop as $key => $rowimages) : ?>
                    <?php
                    if ($rowimages->sl_type == '') {
                        $rowimages->sl_type = 'video';
                    }
                 else{ ?>
                            <li <?php
                                if ($j % 2 == 0) {
                                    echo "class='has-background'";
                                }$j++;
                                ?>  >
                                <input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>" />
                                <?php
                                if (strpos($rowimages->image_url, 'youtube') !== false || strpos($rowimages->image_url, 'youtu') !== false) {
                                    $liclass = "youtube";
                                    $video_thumb_url = get_youtube_id_from_url($rowimages->image_url);
                                    $thumburl = '<img src="http://img.youtube.com/vi/' . $video_thumb_url . '/mqdefault.jpg" alt="" />';
                                } else if (strpos($rowimages->image_url, 'vimeo') !== false) {
                                    $liclass = "vimeo";
                                    $vimeo = $rowimages->image_url;
                                    $vimeo = explode("/", $vimeo);
                                    $imgid = end($vimeo);
                                    
                                    $hash = unserialize(get('http://vimeo.com/api/v2/video/' . $imgid . '.php'));
                                    
                                    //$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . $imgid . ".php"));
                                    $imgsrc = $hash[0]['thumbnail_large'];
                                    $thumburl = '<img src="' . $imgsrc . '" alt="" />';
                                }
//										
                                ?> 
            <?php if (isset($thumburl)) { ?>
                                    <div class="image-container" style = "width: 20%;">	
                <?php echo $thumburl; ?>
                                        <div class="play-icon <?php echo $liclass; ?>"></div>
                                        <div>
                                            <input type="hidden" name="imagess<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->image_url; ?>" />
                                        </div>
                                    </div> <?php } ?>
                                <div class="image-options">
                                    <div> <input type="hidden" value="<?= $rowimages->image_url ?>" name="videoUrl"  style="width: 10px;"/>
                                        <input hidden="" id= "image_url<?php echo $rowimages->id; ?>" name="image_url<?php echo $rowimages->id; ?>" value='<?php echo $rowimages->image_url; ?>'/>
                                        <label for="titleimage<?php echo $rowimages->id; ?>">Title:</label>
                                        <input   type="text" id="titleimage<?php echo $rowimages->id; ?>" name="titleimage<?php echo $rowimages->id; ?>" id="titleimage<?php echo $rowimages->id; ?>"  value="<?php echo $rowimages->name; ?>" s>
                                    </div>
                                    <div class="description-block">
                                        <label for="im_description<?php echo $rowimages->id; ?>">Description:</label>
                                        <textarea id="im_description<?php echo $rowimages->id; ?>" name="im_description<?php echo $rowimages->id; ?>" ><?php echo $rowimages->description; ?></textarea>
                                    </div>
                                    <div class="link-block">
                                        <label for="sl_url<?php echo $rowimages->id; ?>">URL:</label>
                                        <input class=" url-input" type="text" id="sl_url<?php echo $rowimages->id; ?>" name="sl_url<?php echo $rowimages->id; ?>"  value="<?php echo $rowimages->sl_url; ?>" >
                                        <label class="long" for="sl_link_target<?php echo $rowimages->id; ?>">
                                            <span>Open in new tab</span>
                                            <input type="hidden" name="sl_link_target<?php echo $rowimages->id; ?>" value="" />
                                            <input  <?php
                                    if ($rowimages->link_target == 'on') {
                                        echo 'checked="checked"';
                                    }
                                    ?>  class="link_target" type="checkbox" id="sl_link_target<?php echo $rowimages->id; ?>" name="sl_link_target<?php echo $rowimages->id; ?>" />
                                        </label>
                                    </div>
                                    <div class="remove-image-container">
                                        <a style = "float:right" class="removeVideoColor" href="index.php?option=com_videogallerylite&view=videogallerylite&layout=edit&id=<?php echo $this->item->id ?>&task=videogallerylite.deleteProject&removeslide=<?php echo $rowimages->id; ?>">Remove Video</a>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </li>
            <?php
                 }
     endforeach;
?>
            </ul>  

            <div style=" position:absolute; width:1px; height:1px; top:0px; overflow:hidden">
                <textarea id="tempimage" name="tempimage" class="mce_editable"></textarea><br />
            </div>
            <?php
            $editor->display('description', 'sss', '0', '0', '0', '0');
            ?>
        </div>
        </div>
      <div class="clr"></div>     
    </div>

    <div>
        <input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
    </div>

</form>
