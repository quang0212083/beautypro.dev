<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.version');
$version = new JVersion();

if(version_compare($version->getShortVersion(), '1.7', '>=')){
    require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'plugin_helper.php');
} else {
    require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'plugin_helper15.php');
}

$plugins = contentbuilder::getFormElementsPlugins();

$removables = array();

$plgs = CBPluginHelper::importPlugin('contentbuilder_form_elements', $this->element->type);
$removables = array_merge($removables, $plgs);

$dispatcher = JDispatcher::getInstance();
$results = $dispatcher->trigger('onSettingsDisplay', array($this->element->options));

if(count($results)){
    $results = $results[0];
}

foreach($removables As $removable){
    $dispatcher->detach($removable);
}

$the_item = $results;

$is_plugin = false;
?>
<style type="text/css">
    label { display: inline; }
</style>
<?php
$cbcompat = new CBCompat();
echo $cbcompat->initPane(
    array(
        'tab0' => JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS' ),
        'tab1' => JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_SCRIPTS' )
    )
);
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    
    <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE' ); ?>
    <select name="type_selection" onchange="document.getElementById('type_change').value='1';document.getElementById('task').value='save';document.adminForm.submit();">
        <option value="text"<?php echo $this->element->type=='text' || $this->element->type=='' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_TEXT' ); ?></option>
        <option value="textarea"<?php echo $this->element->type=='textarea' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_TEXTAREA' ); ?></option>
        <option value="checkboxgroup"<?php echo $this->element->type=='checkboxgroup' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_CHECKBOXGROUP' ); ?></option>
        <option value="radiogroup"<?php echo $this->element->type=='radiogroup' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_RADIO' ); ?></option>
        <option value="select"<?php echo $this->element->type=='select' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_SELECT' ); ?></option>
        <option value="upload"<?php echo $this->element->type=='upload' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_UPLOAD' ); ?></option>
        <option value="calendar"<?php echo $this->element->type=='calendar' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_CALENDAR' ); ?></option>
        <option value="hidden"<?php echo $this->element->type=='hidden' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_HIDDEN' ); ?></option>
        <option value="captcha"<?php echo $this->element->type=='captcha' ? ' selected="selected"' : ''; ?>><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_CAPTCHA' ); ?></option>
        <?php
        foreach($plugins As $plugin){
        ?>
        <option value="<?php echo $plugin;?>"<?php echo $this->element->type==$plugin ? ' selected="selected"' : ''; ?>><?php echo $plugin; ?></option>
        <?php
        }
        ?>
    </select>
    <button onclick="document.getElementById('task').value='save';"><?php echo JText::_('COM_CONTENTBUILDER_SAVE');?></button>
    <!--<button onclick="parent.document.getElementById('sbox-window').close();return false;"><?php echo JText::_('COM_CONTENTBUILDER_CANCEL');?></button>-->
    
    <hr/>
    
<div class="col100">
<?php
echo $cbcompat->startPane("view-pane");
echo $cbcompat->startPanel(JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS' ), "tab0");
?>
<h3><?php echo htmlentities($this->element->label, ENT_QUOTES, 'UTF-8');?></h3>
<?php
switch( $this->element->type ){
    case is_array($the_item) && in_array($this->element->type, $plugins):  
        $is_plugin = true;
?>
    <fieldset class="adminform">
        <legend><?php echo $the_item['element_type']; ?></legend>
        <table class="admintable" width="95%">
            <?php
            if(isset($the_item['has_hint']) && $the_item['has_hint']){
            ?>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
            <?php
            }
            ?>
        </table>
        <?php
        echo $the_item['settings'];
        ?>
    </fieldset>    
    <input type="hidden" name="field_type" value="<?php echo $this->element->type;?>" />
<?php
    break;
    case 'captcha':
?>
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_CAPTCHA' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="captcha" />
<?php
    break;
    case 'upload':
?>
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_UPLOAD' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="allowed_file_extensions">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_ALLOWED_FILE_EXTENSIONS' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="allowed_file_extensions" id="allowed_file_extensions" value="<?php echo htmlentities(isset($this->element->options->allowed_file_extensions) && $this->element->options->allowed_file_extensions ? $this->element->options->allowed_file_extensions : 'zip, rar, 7z, pdf, doc, xls, ppt, jpg, jpeg, png, gif', ENT_QUOTES, 'UTF-8');?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="max_filesize">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_MAX_FILESIZE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="max_filesize" id="max_filesize" value="<?php echo htmlentities(isset($this->element->options->max_filesize) && $this->element->options->max_filesize ? $this->element->options->max_filesize : '2M', ENT_QUOTES, 'UTF-8');?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="upload_directory">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_UPLOAD_DIRECTORY' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="upload_directory" id="upload_directory" value="<?php echo htmlentities(isset($this->element->options->upload_directory) && $this->element->options->upload_directory ? $this->element->options->upload_directory : '', ENT_QUOTES, 'UTF-8');?>"/>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="upload" />
<?php
    break;
    case 'checkboxgroup':
    case 'radiogroup':
    case 'select':
?>
    <fieldset class="adminform">
        <legend><?php echo $this->element->type == 'checkboxgroup' ? JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_CHECKBOXGROUP' ) : $this->element->type == 'select' ? JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_SELECT' ) : JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_RADIO' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
               <?php
               $groupCnt = count($this->group_definition);
               if( $groupCnt ){
                   $def = array();
                   if(isset($this->element->default_value)){
                       $def = explode($this->element->options->seperator, $this->element->default_value);
                   }
               ?>
               
               <tr>
                    <td width="100" align="left" class="key">
                        <label>
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_DEFAULT_VALUE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                    <?php
                    foreach($this->group_definition As $key => $value){
                    ?>
                    <input <?php echo in_array($key, $def) ? 'checked="checked" ' : ''; ?>type="checkbox" name="default_value[]" id="default_value<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8'); ?>"/>
                    <label for="default_value<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlentities($value, ENT_QUOTES, 'UTF-8'); ?></label>
                    <br/>
                    <?php
                    }
                    ?>
                    </td>
               </tr>
               
               <?php
               }
               
               if($this->element->type == 'select'){
               ?>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="multiple">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_MULTIPLE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="checkbox" name="multiple" id="multiple" value="1"<?php echo isset($this->element->options->multiple) && $this->element->options->multiple ? ' checked="checked"' : ''; ?>/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="length">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_LENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="length" id="length" value="<?php echo isset($this->element->options->length) ?  $this->element->options->length : '';?>"/>
                    </td>
               </tr>
               <?php
               }
               if($this->element->type == 'checkboxgroup' || $this->element->type == 'radiogroup'){
               ?>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="horizontal">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_HORIZONTAL' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="checkbox" name="horizontal" id="horizontal" value="1"<?php echo isset($this->element->options->horizontal) && $this->element->options->horizontal ? ' checked="checked"' : '';?>/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="horizontal_length">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_HORIZONTAL_LENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width: 95%" type="text" name="horizontal_length" id="horizontal_length" value="<?php echo isset($this->element->options->horizontal_length) ? $this->element->options->horizontal_length : '';?>"/>
                    </td>
               </tr>
               <?php
               }
               ?>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="seperator">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_SEPERATOR' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="seperator" id="seperator" value="<?php echo isset($this->element->options->seperator) ? htmlentities($this->element->options->seperator, ENT_QUOTES, 'UTF-8') : ',';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="allow_encoding">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_ALLOW_ENCODING' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="radio" name="allow_encoding" id="allow_encoding" value="0"<?php echo ( !isset($this->element->options->allow_html) || !$this->element->options->allow_html ) && ( !isset($this->element->options->allow_raw) || !$this->element->options->allow_raw ) ? ' checked="checked"' :'';  ?>/> <label for="allow_encoding"><?php echo JText::_('COM_CONTENTBUILDER_FILTER_ALL'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding1" value="1"<?php echo isset($this->element->options->allow_html) && $this->element->options->allow_html ? ' checked="checked"' : '';?>/> <label for="allow_encoding1"><?php echo JText::_('COM_CONTENTBUILDER_HTML'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding2" value="2"<?php echo isset($this->element->options->allow_raw) && $this->element->options->allow_raw ? ' checked="checked"' : '';?>/> <label for="allow_encoding2"><?php echo JText::_('COM_CONTENTBUILDER_RAW'); ?></label>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="<?php echo $this->element->type; ?>" />
<?php
        break;
    case 'textarea':
?>
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_TEXTAREA' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="default_value">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_DEFAULT_VALUE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width: 95%; height: 100px;" name="default_value" id="default_value" ><?php echo isset($this->element->default_value) ? htmlentities($this->element->default_value, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="width">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_WIDTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="width" id="width" value="<?php echo isset($this->element->options->width) ? $this->element->options->width : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="height">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_HEIGHT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="height" id="height" value="<?php echo isset($this->element->options->height) ? $this->element->options->height : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="maxlength">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_MAXLENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="maxlength" id="maxlength" value="<?php echo isset($this->element->options->maxlength) ? $this->element->options->maxlength : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="readonly">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_READONLY' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="checkbox" name="readonly" value="1" id="readonly""<?php echo isset($this->element->options->readonly) ? ( intval($this->element->options->readonly) ? ' checked="checked"' : '' ) : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="allow_encoding">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_ALLOW_ENCODING' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="radio" name="allow_encoding" id="allow_encoding" value="0"<?php echo ( !isset($this->element->options->allow_html) || !$this->element->options->allow_html ) && ( !isset($this->element->options->allow_raw) || !$this->element->options->allow_raw ) ? ' checked="checked"' :'';  ?>/> <label for="allow_encoding"><?php echo JText::_('COM_CONTENTBUILDER_FILTER_ALL'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding1" value="1"<?php echo isset($this->element->options->allow_html) && $this->element->options->allow_html ? ' checked="checked"' : '';?>/> <label for="allow_encoding1"><?php echo JText::_('COM_CONTENTBUILDER_HTML'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding2" value="2"<?php echo isset($this->element->options->allow_raw) && $this->element->options->allow_raw ? ' checked="checked"' : '';?>/> <label for="allow_encoding2"><?php echo JText::_('COM_CONTENTBUILDER_RAW'); ?></label>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="textarea" />
    <?php
    break;
    case 'calendar':
?>

    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_CALENDAR' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="default_value">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_DEFAULT_VALUE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="default_value" id="default_value" value="<?php echo isset($this->element->default_value) ? htmlentities($this->element->default_value, ENT_QUOTES, 'UTF-8') : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="length">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_LENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="length" id="length" value="<?php echo isset($this->element->options->length) ?  $this->element->options->length : '100%';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="maxlength">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_MAXLENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="maxlength" id="maxlength" value="<?php echo isset($this->element->options->maxlength) ? $this->element->options->maxlength : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="format">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_FORMAT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="format" id="format" value="<?php echo isset($this->element->options->format) ? $this->element->options->format : '%Y-%m-%d';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="transfer_format">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_TRANSFER_FORMAT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="transfer_format" id="transfer_format" value="<?php echo isset($this->element->options->transfer_format) ? $this->element->options->transfer_format : 'YYYY-mm-dd';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="readonly">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_READONLY' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="checkbox" name="readonly" value="1" id="readonly""<?php echo isset($this->element->options->readonly) ? ( intval($this->element->options->readonly) ? ' checked="checked"' : '' ) : '';?>"/>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="calendar" />
<?php
    break;
    case '':
    case 'text':
?>

    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_TEXT' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="default_value">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_DEFAULT_VALUE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="default_value" id="default_value" value="<?php echo isset($this->element->default_value) ? htmlentities($this->element->default_value, ENT_QUOTES, 'UTF-8') : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="hint">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_HINT' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:100px;" name="hint" id="hint"><?php echo isset($this->element->hint) ? htmlentities($this->element->hint, ENT_QUOTES, 'UTF-8') : '';?></textarea>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="length">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_LENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="length" id="length" value="<?php echo isset($this->element->options->length) ?  $this->element->options->length : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="maxlength">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_MAXLENGTH' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="maxlength" id="maxlength" value="<?php echo isset($this->element->options->maxlength) ? $this->element->options->maxlength : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="password">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_PASSWORD' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="checkbox" name="password" value="1" id="password""<?php echo  isset($this->element->options->password) ? ( intval($this->element->options->password) ? ' checked="checked"' : '' ) : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="readonly">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_READONLY' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="checkbox" name="readonly" value="1" id="readonly""<?php echo isset($this->element->options->readonly) ? ( intval($this->element->options->readonly) ? ' checked="checked"' : '' ) : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="allow_encoding">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_ALLOW_ENCODING' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="radio" name="allow_encoding" id="allow_encoding" value="0"<?php echo ( !isset($this->element->options->allow_html) || !$this->element->options->allow_html ) && ( !isset($this->element->options->allow_raw) || !$this->element->options->allow_raw ) ? ' checked="checked"' :'';  ?>/> <label for="allow_encoding"><?php echo JText::_('COM_CONTENTBUILDER_FILTER_ALL'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding1" value="1"<?php echo isset($this->element->options->allow_html) && $this->element->options->allow_html ? ' checked="checked"' : '';?>/> <label for="allow_encoding1"><?php echo JText::_('COM_CONTENTBUILDER_HTML'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding2" value="2"<?php echo isset($this->element->options->allow_raw) && $this->element->options->allow_raw ? ' checked="checked"' : '';?>/> <label for="allow_encoding2"><?php echo JText::_('COM_CONTENTBUILDER_RAW'); ?></label>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="text" />
<?php
        break;
        case 'hidden':
?>

    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_TYPE_HIDDEN' ); ?></legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="default_value">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_DEFAULT_VALUE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="default_value" id="default_value" value="<?php echo isset($this->element->default_value) ? htmlentities($this->element->default_value, ENT_QUOTES, 'UTF-8') : '';?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="allow_encoding">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_ALLOW_ENCODING' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input type="radio" name="allow_encoding" id="allow_encoding" value="0"<?php echo ( !isset($this->element->options->allow_html) || !$this->element->options->allow_html ) && ( !isset($this->element->options->allow_raw) || !$this->element->options->allow_raw ) ? ' checked="checked"' :'';  ?>/> <label for="allow_encoding"><?php echo JText::_('COM_CONTENTBUILDER_FILTER_ALL'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding1" value="1"<?php echo isset($this->element->options->allow_html) && $this->element->options->allow_html ? ' checked="checked"' : '';?>/> <label for="allow_encoding1"><?php echo JText::_('COM_CONTENTBUILDER_HTML'); ?></label>
                        <input type="radio" name="allow_encoding" id="allow_encoding2" value="2"<?php echo isset($this->element->options->allow_raw) && $this->element->options->allow_raw ? ' checked="checked"' : '';?>/> <label for="allow_encoding2"><?php echo JText::_('COM_CONTENTBUILDER_RAW'); ?></label>
                    </td>
               </tr>
        </table>
    </fieldset>    
    <input type="hidden" name="field_type" value="hidden" />
<?php
        break;
}

echo $cbcompat->endPanel();
if( $this->element->type != 'captcha' ){
echo $cbcompat->startPanel( JText::_( 'COM_CONTENTBUILDER_ELEMENT_OPTIONS_SCRIPTS' ), "tab1" );
?>
    <h3><?php echo htmlentities($this->element->label, ENT_QUOTES, 'UTF-8');?></h3>
    <?php
    if( ($is_plugin && $the_item['show_validation_settings']) || !$is_plugin ){
    ?>
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_VALIDATION' ); ?> (PHP)</legend>
        <table class="admintable" width="95%">
                <tr>
                    <td width="100" align="left" class="key">
                        <label for="validation_message">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_VALIDATION_MESSAGE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <input style="width:95%;" type="text" name="validation_message" id="validation_message" value="<?php echo htmlentities($this->element->validation_message, ENT_QUOTES, 'UTF-8');?>"/>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="validations">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_SELECT_VALIDATIONS' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <select style="width: 95%;height: 100px;" multiple="multiple" name="validations[]" id="validations">
                         <?php
                         $selected_validations = explode(',', $this->element->validations);
                         foreach($this->validations As $validation){
                         ?>
                            <option <?php echo in_array($validation, $selected_validations) ? 'selected="selected" ' : ''; ?>value="<?php echo htmlentities($validation, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlentities($validation, ENT_QUOTES, 'UTF-8'); ?></option>
                         <?php
                         }
                         ?>
                        </select>
                    </td>
               </tr>
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="custom_validation_script">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_VALIDATION_CODE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:200px;" name="custom_validation_script" id="custom_validation_script"><?php echo htmlentities($this->element->custom_validation_script, ENT_QUOTES, 'UTF-8');?></textarea>
                    </td>
               </tr>
        </table>
    </fieldset>
    <?php
    }

    if( ($is_plugin && $the_item['show_init_code_settings']) || !$is_plugin ){
    ?>
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_INIT' ); ?> (JS)</legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="custom_init_script">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_INIT_CODE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:200px;" name="custom_init_script" id="custom_init_script"><?php echo htmlentities($this->element->custom_init_script, ENT_QUOTES, 'UTF-8');?></textarea>
                    </td>
               </tr>
        </table>
    </fieldset>
    <?php
    }
    if( ($is_plugin && $the_item['show_action_code_settings']) || !$is_plugin ){
    ?>
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_ACTION' ); ?> (PHP)</legend>
        <table class="admintable" width="95%">
               <tr>
                    <td width="100" align="left" class="key">
                        <label for="custom_action_script">
                            <?php echo JText::_( 'COM_CONTENTBUILDER_ELEMENT_ACTION_CODE' ); ?>:
                        </label>
                    </td>
                    <td align="left">
                        <textarea style="width:95%;height:200px;" name="custom_action_script" id="custom_action_script"><?php echo htmlentities($this->element->custom_action_script, ENT_QUOTES, 'UTF-8');?></textarea>
                    </td>
               </tr>
        </table>
    </fieldset>
    <?php
    }
    ?>
<?php
echo $cbcompat->endPanel();
}
echo $cbcompat->endPane();
?>
</div>
    

<input type="hidden" name="option" value="com_contentbuilder" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="type_change" id="type_change" value="0" />
<input type="hidden" name="id" value="<?php echo $this->element->form_id; ?>" />
<input type="hidden" name="element_id" value="<?php echo $this->element->id; ?>" />
<input type="hidden" name="tmpl" value="component" />
<input type="hidden" name="tabStartOffset" value="0" />
<input type="hidden" name="controller" value="elementoptions" />
<input type="hidden" name="ordering" value="<?php echo $this->element->ordering ;?>" />
<input type="hidden" name="published" value="<?php echo $this->element->published ;?>" />
<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">
if(typeof Joomla != 'undefined'){

    $$('tab0').addEvent('click', function(){document.adminForm.tabStartOffset.value = 0;});
<?php
if( $this->element->type != 'captcha' ){
?>
    $$('tab1').addEvent('click', function(){document.adminForm.tabStartOffset.value = 1;});
<?php
}
?>
    
}else{
    $('tab0').addEvent('click', function(){document.adminForm.tabStartOffset.value = 0;});
<?php
if( $this->element->type != 'captcha' ){
?>
    $('tab1').addEvent('click', function(){document.adminForm.tabStartOffset.value = 1;});
<?php
}
?>
}
</script>