<?php
/**
 * @package  Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
jimport('joomla.form.formfield');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>

<?php
class JFormFieldJSColor extends JFormField {

    protected $type = 'jscolor';

    public function getInput() {

        $type_ = $this->element['type_'];
          
        JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/admin.style.css');
        JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/simple-slider1.css');
        JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/simple-slider_sl.css'); 
        JHtml::stylesheet(Juri::root() . 'media/com_videogallerylite/style/style2-os.css'); 
        $doc = JFactory::getDocument();
//        $doc->addScript("http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js");
        $doc->addScript("http://code.jquery.com/ui/1.10.4/jquery-ui.js");
        $doc->addScript(JURI::root(true) . "/media/com_videogallerylite/js/simple-slider.js");
        $doc->addScript(JURI::root(true) . "/media/com_videogallerylite/js/admin.js");
        $doc ->addScript(JURI::root(true) ."/media/com_videogallerylite/elements/jscolor/jscolor.js");
             

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('id, name, value');
        $query->from('#__huge_it_videogallery_params');
        $query->where('name="' . $this->element['name'] . '"');
        $db->setQuery($query);
        $results = $db->loadAssocList();
        
     
        $query1 = $db->getQuery(true);
        $query1->select('*');
        $query1->from('#__huge_it_videogallery_params');
        $db->setQuery($query1);
        $results2 = $db->loadAssocList();
      
        $class = $this->element['class'];
        $labelclass = $this->element['labelclass'];
        $for = $this->element['for'];
        $name = $this->element['name'];
        $id = $this->element['id'];
        $data_slider_values= $this->element['data-slider-values'];
        $this->element['class'] = trim($class);
        $class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $name = $this->element['name'] ? 'name="' . (string) $this->element['name'] . '"' : '';
        $id = $this->element['id'] ? 'id="' . (string) $this->element['id'] . '"' : '';
        $value = $this->element['value'] ? 'value="' . (string) $this->element['value'] . '"' : '';
        
         if ($type_ == "text") {
            return '<input type="text" name="' . $this->name . '" id="' . $this->id . '" value= "'.$results[0]['value'] . '"  class="inputClass"/>';
        }elseif ($type_ == "textColor") {
            return '<input  class="color" type="text" name="' . $this->name . '" id="' . $this->id . '" value="'.$results[0]['value'] . '"   class="inputClass"/>';
        }  elseif ($type_ == "checkbox") {
            $on = "on";
            $off = "off";
            $checked = $results[0]['value']== 'on' ? 'checked' : '';
            return '<input  onclick = "this.value = (this.checked ? \''.$on.'\': \''.$off.'\')" type="checkbox" name="' . $this->name . '" id="' . $this->id . '" value= "'.($checked == 'checked' ? $on : $off  ). '" ' .  $class . $checked . ' />';
        }  elseif ($type_== "zoomImageStyle") {
            return '<select class = "text" name="' . $this->name . '" id="' . $this->id . '"><option value="light" '.($results[0]['value'] =="light" ? "selected" : "" ).'>Light</option>
		<option value="dark" '.($results[0]['value'] =="dark" ? "selected" : "" ).'>Dark</option></select><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span> ';
            
        } else if ($type_ == "thumbsPositionList") {
             return '<select class = "text" name="' . $this->name . '" id="' . $this->id . '"><option value="before" '.($results[0]['value'] =="before" ? "selected" : "" ).'>Before Description</option>
		    <option value="after" '.($results[0]['value'] == "after" ? "selected" : "" ).'>After Description</option></select>';
        }
        else if ($type_ == "light_box_style") {
             return '<select class = "text" name="' . $this->name . '" id="' . $this->id . '">'
                     . '<option value="1" '.($results[0]['value'] =="1" ? "selected" : "" ).'>1</option>'
		     . '<option value="2" '.($results[0]['value'] == "2" ? "selected" : "" ).'>2</option>'
                     . ' <option value="3" '.($results[0]['value'] == "3" ? "selected" : "" ).'>3</option>'
                     . ' <option value="4" '.($results[0]['value'] == "4" ? "selected" : "" ).'>4</option>'
                     . ' <option value="5" '.($results[0]['value'] == "5" ? "selected" : "" ).'>5</option>'
                     . '</select><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span>';
        }
        elseif($type_ == "slider_title_text_align"){
         return '<select  class = "text" name="' . $this->name . '" id="' . $this->id . '">
		    <option value="justify" '.($results[0]['value'] == "justify" ? "selected" : "" ).'>Full width</option>'
                 . '<option value="center" '.($results[0]['value'] =="center" ? "selected" : "" ).'>Center</option>'
                 . '<option value="left" '.($results[0]['value'] =="left" ? "selected" : "" ).'>Left</option>'
                 . '<option value="right" '.($results[0]['value'] =="right" ? "selected" : "" ).'>Right</option>'
                 . '</select>';
        }
        elseif ($type_ == "opacity" ) {

            return '<div style="float: left; margin-top:0px !important;   position: relative;  display: block;  height: 28px !important;" class="slider-container">'
                    . '<input value= "' . $results[0]['value'] . '"  name="' . $this->name . '"  id="light_box_opacity" data-slider-highlight="true"  data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true" style="display: none;"/>'
                    . '<span class="box">' .$results[0]['value'] . ' %</span></div>';
        }
         elseif ($type_== "TransitionList") {
            return '<select   class = "text" name="' . $this->name . '" id="' . $this->id . '"><option value="elastic" '.($results[0]['value'] =="elastic" ? "selected" : "" ).'>Elastic</option>
                <option value="fade" '.($results[0]['value'] =="fade" ? "selected" : "" ).'>Fade</option>
		<option value="none" '.($results[0]['value'] =="none" ? "selected" : "" ).'>None</option></select><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span>';
       }
        elseif ($type_== "NavigationDots") {
            return '<select class = "text"  name="' . $this->name . '" id="' . $this->id . '">'
                    . '<option value="none" '.($results[0]['value'] =="none" ? "selected" : "" ).'>Dont Show</option>'
                    . '<option value="top" '.($results[0]['value'] =="top" ? "selected" : "" ).'>Top</option>'
                    .'<option value="bottom" '.($results[0]['value'] =="bottom" ? "selected" : "" ).'>Bottom</option>'
                    . '</select><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span>';
	}
        elseif($type_ == "navigationTypeArrow"){
            $path = JURI::root().'media/com_videogallerylite/images/Front_images/';
            return '<div class="has-height has-background" style="clear:both;padding:10px 0px 0px 0px;">
                        <div style = "float: left;width: 100%;position: absolute;left: 0;">
                            <ul id="arrows-type">
                                    <li '.($results[0]['value'] == 1 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.simple.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '"  value="1" '.($results[0]['value'] == 1 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 2 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.circle.shadow.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="2" '.($results[0]['value'] == 2 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 3 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.circle.simple.dark.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="3" '.($results[0]['value'] == 3 ? "checked=checked"  : "").'>
                                    </li>

                                    <li '.($results[0]['value'] == 4 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.cube.dark.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="4" '.($results[0]['value'] == 4 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 5 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.light.blue.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="5" '.($results[0]['value'] == 5 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 6 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.light.cube.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="6" '.($results[0]['value'] == 6 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 8 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="8" '.($results[0]['value'] == 8 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 9 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.circle.blue.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="9" '.($results[0]['value'] == 9 ? "checked=checked"  : "").'>
                                    </li>	
                                    <li '.($results[0]['value'] == 10 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.circle.green.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="10" '.($results[0]['value'] == 10 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 11 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.blue.retro.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="11" '.($results[0]['value'] == 11 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 12 ? "class=active" : "" ).'>
                                            <div class="image-block">
                                                    <img src="'.$path.'/arrows/arrows.green.retro.png" alt="" />
                                            </div>
                                            <input type="radio" name="' . $this->name . '" value="12" '.($results[0]['value'] == 12 ? "checked=checked"  : "").'>
                                    </li>	
                                    <li '.($results[0]['value'] == 13 ? "class=active" : "" ).'>
                                                    <div class="image-block">
                                                           <img src="'.$path.'/arrows/arrows.red.circle.png" alt="" />
                                                    </div>
                                            <input type="radio" name="' . $this->name . '" value="13" '.($results[0]['value'] == 13 ? "checked=checked"  : "").'>
                                    </li>	
                                    <li '.($results[0]['value'] == 14 ? "class=active" : "" ).'>
                                                    <div class="image-block">
                                                            <img src="'.$path.'/arrows/arrows.triangle.white.png" alt="" />
                                                    </div>
                                            <input type="radio" name="' . $this->name . '" value="14" '.($results[0]['value'] == 14 ? "checked=checked"  : "").'>
                                    </li>	
                                    <li '.($results[0]['value'] == 15 ? "class=active" : "" ).'>
                                                    <div class="image-block">
                                                            <img src="'.$path.'/arrows/arrows.ancient.png" alt="" />
                                                    </div>
                                            <input type="radio" name="' . $this->name . '" value="15" '.($results[0]['value'] == 15 ? "checked=checked"  : "").'>
                                    </li>
                                    <li '.($results[0]['value'] == 16 ? "class=active" : "" ).'>
                                                    <div class="image-block">
                                                            <img src="'.$path.'/arrows/arrows.black.out.png" alt="" />
                                                    </div>
                                            <input type="radio" name="' . $this->name . '" value="16" '.($results[0]['value'] == 16 ? "checked=checked"  : "").'>
                                    </li>							
                            </ul>
                        </div>
							</div>';
        }
      elseif ($type_ == "number"){ 
            return '<input   type="number"  id ="' . $this->id . '" name="' . $this->name . '" value="' . $results[0]['value'] . '" '.$class.'   '.$labelclass.' style = "width: 103px !important;"/><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span>';
        }
             if ($type_ == "option_list") {
                      $html = '<select  class = "text" id="' . $this->id . '" name="' . $this->name . '" >';
            foreach ($results2 as $i => $res) {
                if ($this->value == $results2[$i]['name']) {
                    $html.= '<option name="' . $this->name . '" value="' . $results2[$i]['name'] . '"  selected="selected">' .$results2[$i]['name'] . '</option>';
                } else {
                    $html.= '<option name="' . $this->name . '" value="' . $results2[$i]['name']. '" >' . $results2[$i]['name'] . '</option>';
                }
            }
            $html.= '</select>';
            return $html;
        }
           elseif ($type_ == "radio_lightbox") {
            $checked = 'checked';
            $html = '<div style="float: left;">
			<table>
				<tbody>
				  <tr>
					<td style="width:25px"><input type="radio" value="1" id="slideshow_title_top-left" name="' . $this->name . '" ' . ($results[0]['value'] == "1" ? $checked : '') . ' </td>
					<td style="width:25px"><input type="radio" value="2" id="slideshow_title_top-center" name="' . $this->name . '" ' . ($results[0]['value'] == "2" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="3" id="slideshow_title_top-right" name="' . $this->name . '" ' . ($results[0]['value'] == "3" ? $checked : '') . '  /></td>
				  </tr>
				  <tr>
					<td style="width:25px"><input type="radio" value="4" id="slideshow_title_middle-left" name="' . $this->name . '" ' . ($results[0]['value'] == "4" ? $checked : '') . '/> </td>
					<td style="width:25px"><input type="radio" value="5" id="slideshow_title_middle-center" name="' . $this->name . '" ' . ($results[0]['value'] == "5" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="6" id="slideshow_title_middle-right" name="' . $this->name . '"  ' . ($results[0]['value'] == "6" ? $checked : '') . '/></td>
				  </tr>
				  <tr>
					<td style="width:25px"><input type="radio" value="7" id="slideshow_title_bottom-left" name="' . $this->name . '" ' . ($results[0]['value'] == "7" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="8" id="slideshow_title_bottom-center" name="' . $this->name . '" ' . ($results[0]['value'] == "8" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="9" id="slideshow_title_bottom-right" name="' . $this->name . '" ' . ($results[0]['value'] == "9" ? $checked : '') . '/></td>
				  </tr>
				</tbody>	
			</table>
		 </div>';

            return $html;
        
    
        }
       elseif ($type_ == "radio") {
            $checked = 'checked';
            $html = '<div style="float: left;">
			<table>
				<tbody>
				  <tr>
					<td style="width:25px"><input type="radio" value="left-top" id="slideshow_title_top-left" name="' . $this->name . '" ' . ($results[0]['value'] == "left-top" ? $checked : '') . ' </td>
					<td style="width:25px"><input type="radio" value="center-top" id="slideshow_title_top-center" name="' . $this->name . '" ' . ($results[0]['value'] == "center-top" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="right-top" id="slideshow_title_top-right" name="' . $this->name . '" ' . ($results[0]['value'] == "right-top" ? $checked : '') . '  /></td>
				  </tr>
				  <tr>
					<td style="width:25px"><input type="radio" value="left-middle" id="slideshow_title_middle-left" name="' . $this->name . '" ' . ($results[0]['value'] == "left-middle" ? $checked : '') . '/> </td>
					<td style="width:25px"><input type="radio" value="center-middle" id="slideshow_title_middle-center" name="' . $this->name . '" ' . ($results[0]['value'] == "center-middle" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="right-middle" id="slideshow_title_middle-right" name="' . $this->name . '"  ' . ($results[0]['value'] == "right-middle" ? $checked : '') . '/></td>
				  </tr>
				  <tr>
					<td style="width:25px"><input type="radio" value="left-bottom" id="slideshow_title_bottom-left" name="' . $this->name . '" ' . ($results[0]['value'] == "left-bottom" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="center-bottom" id="slideshow_title_bottom-center" name="' . $this->name . '" ' . ($results[0]['value'] == "center-bottom" ? $checked : '') . ' /></td>
					<td style="width:25px"><input type="radio" value="right-bottom" id="slideshow_title_bottom-right" name="' . $this->name . '" ' . ($results[0]['value'] == "right-bottom" ? $checked : '') . '/></td>
				  </tr>
				</tbody>	
			</table>
		 </div>';

            return $html;
        
    
}elseif ($type_ == "light_box_overlayclose" || $type_ == 'light_box_loop' || $type_ == 'light_box_slideshowauto' || $type = 'light_box_fixed') {
        return '<input  value = "true" type="checkbox" name="' . $this->name . '" id="' . $this->id . '"  ' .  $class .  ' checked/>';
        } elseif ($type_ == "light_box_maxwidth"){ 
        return '<input   type="number"  id ="' . $this->id . '" name="' . $this->name . '" value="768" '.$class.'   '.$labelclass.' style = "width: 103px !important;"/><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span>';
        } elseif ($type_ == "light_box_maxheight"){ 
        return '<input   type="number"  id ="' . $this->id . '" name="' . $this->name . '" value="500" '.$class.'   '.$labelclass.' style = "width: 103px !important;"/><span style = "padding:10px 5px 7px 8px;font-size:15px">px</span>';
        }
       
}}