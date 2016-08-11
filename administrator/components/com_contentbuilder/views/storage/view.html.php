<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');

jimport('joomla.html.pane');

CBCompat::requireView();

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder_helpers.php');

class ContentbuilderViewStorage extends CBView
{
    function display($tpl = null)
    {
        JHTML::_('behavior.tooltip');

        $document = JFactory::getDocument();
        $document->addScript( JURI::root(true) . '/administrator/components/com_contentbuilder/assets/js/jscolor/jscolor.js' );

        echo '
        <style type="text/css">
        .icon-48-logo_left { background-image: url(../administrator/components/com_contentbuilder/views/logo_left.png); }
        </style>
        ';
        jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            echo '<link rel="stylesheet" href="'.JURI::root(true).'/administrator/components/com_contentbuilder/views/bluestork.fix.css" type="text/css" />';
        }
        $tables     = $this->get('DbTables');
        $form     = $this->get('Storage');
        $elements  = $this->get('Data');
        $pagination   = $this->get('Pagination');
        $isNew        = ($form->id < 1);

        $text = $isNew ? JText::_( 'COM_CONTENTBUILDER_NEW' ) : JText::_( 'COM_CONTENTBUILDER_EDIT' );
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            JToolBarHelper::title(   '<img src="components/com_contentbuilder/views/logo_right.png" alt="" align="top" /> <span style="display:inline-block; vertical-align:middle"> :: ' . ($isNew ? JText::_( 'COM_CONTENTBUILDER_STORAGES' ) : $form->title) .' : <small><small>[ ' . $text.' ]</small></small></span>', 'logo_left.png' );
        } else {
            JToolBarHelper::title(   'ContentBuilder :: ' . ($isNew ? JText::_( 'COM_CONTENTBUILDER_STORAGES' ) : $form->title) .' : <small><small>[ ' . $text.' ]</small></small></span>', 'logo_left.png' );
        }
        
        JToolBarHelper::apply();
        JToolBarHelper::save();
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            JToolBarHelper::customX('saveNew', 'save', '', JText::_('COM_CONTENTBUILDER_SAVENEW'), false);
            JToolBarHelper::customX('listpublish', 'publish', '', JText::_('COM_CONTENTBUILDER_PUBLISH'), false);
            JToolBarHelper::customX('listunpublish', 'unpublish', '', JText::_('COM_CONTENTBUILDER_UNPUBLISH'), false);
            JToolBarHelper::customX('listdelete', 'delete', '', JText::_('COM_CONTENTBUILDER_DELETE_FIELDS'), false);
        } else {
            JToolBarHelper::custom('saveNew', 'save', '', JText::_('COM_CONTENTBUILDER_SAVENEW'), false);
            JToolBarHelper::custom('listpublish', 'publish', '', JText::_('COM_CONTENTBUILDER_PUBLISH'), false);
            JToolBarHelper::custom('listunpublish', 'unpublish', '', JText::_('COM_CONTENTBUILDER_UNPUBLISH'), false);
            JToolBarHelper::custom('listdelete', 'delete', '', JText::_('COM_CONTENTBUILDER_DELETE_FIELDS'), false);
        }
        
        //JToolBarHelper::deleteList();
        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            // for existing items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', 'Close' );
        }

        $state = $this->get( 'state' );
        $lists['order_Dir'] = $state->get( 'fields_filter_order_Dir' );
        $lists['order'] = $state->get( 'fields_filter_order' );
        $lists['limitstart'] = $state->get( 'limitstart' );

        $ordering = ($lists['order'] == 'ordering');

        $this->assignRef('ordering', $ordering);
        $this->assignRef('form', $form);
        $this->assignRef('elements', $elements);
        $this->assignRef('tables', $tables);
        $this->assignRef('pagination', $pagination );
        parent::display($tpl);
    }
}
