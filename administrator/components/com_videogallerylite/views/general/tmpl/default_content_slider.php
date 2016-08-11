<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 

defined('_JEXEC') or die;
jimport('joomla.html.html.tabs');
?>
   <div class="span10" style="width:100%">

       <div class="back">
        <fieldset class="form-horizontal">
            <legend class="legend" ><?php echo JText::_('Slider Container'); ?></legend>
            <?php foreach ($this->form->getFieldset('SliderContainer') as $field): ?>
                <div class="control-group  options-block-title-wrapper first">
                    <?php
                    echo "<div class='control-label'>" . $field->label. "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset>
    </div>
       <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Link Button'); ?></legend>
                <?php foreach ($this->form->getFieldset('SliderContainerLinkButton') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset></div>
       
        <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Title'); ?></legend>
                <?php foreach ($this->form->getFieldset('SliderContainerTitle') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset></div>

    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Description'); ?></legend>
                <?php foreach ($this->form->getFieldset('SliderContainerDescription') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset></div>
     
      
    </div> 
 