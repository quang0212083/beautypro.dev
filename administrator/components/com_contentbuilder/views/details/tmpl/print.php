<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php JFactory::getDocument()->addStyleDeclaration($this->theme_css);?>
<?php JFactory::getDocument()->addScriptDeclaration($this->theme_js);?>
<div align="center">
<button class="button" onclick="window.print()"><?php echo JText::_('COM_CONTENTBUILDER_PRINT')?></button>
<button class="button" onclick="self.close()"><?php echo JText::_('COM_CONTENTBUILDER_CLOSE')?></button>
</div>
<h1 class="contentheading">
<?php echo $this->page_title; ?>
</h1>
<?php echo  $this->event->afterDisplayTitle;?>
<?php
if(JRequest::getInt('cb_show_author',1)){
?>

<?php if($this->created): ?>
<span class="small created-by"><?php echo JText::_('COM_CONTENTBUILDER_CREATED_ON');?> <?php echo JHTML::_('date', $this->created, JText::_('DATE_FORMAT_LC2')); ?></span>
<?php endif; ?>

<?php if($this->created_by): ?>
<span class="small created-by"><?php echo JText::_('COM_CONTENTBUILDER_BY');?> <?php echo $this->created_by; ?></span><br/>
<?php endif; ?>

<?php
}
?>

<br/>
<br/>

<?php echo $this->event->beforeDisplayContent; ?>
<?php echo $this->toc ?>
<?php echo $this->tpl ?>
<?php echo $this->event->afterDisplayContent; ?>


<?php
if(JRequest::getInt('cb_show_author',1)){
?>

<?php if($this->modified_by): ?>
<br/>

<?php if($this->modified): ?>
<span class="small created-by"><?php echo JText::_('COM_CONTENTBUILDER_LAST_UPDATED_ON');?> <?php echo JHTML::_('date', $this->modified, JText::_('DATE_FORMAT_LC2')); ?></span>
<?php endif; ?>

<span class="small created-by"><?php echo JText::_('COM_CONTENTBUILDER_BY');?> <?php echo $this->modified_by; ?></span>

<?php endif; ?>

<?php
}
?>