<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.pane');

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');
require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'pane'.DS.'CBTabs.php');

CBCompat::requireView();

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'contentbuilder_helpers.php');

class ContentbuilderViewForm extends CBView
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
        $form     = $this->get('Form');
        $elements  = $this->get('Data');
        $all_elements  = $this->get('AllElements');
        $pagination   = $this->get('Pagination');
        $isNew        = ($form->id < 1);

        $text = $isNew ? JText::_( 'COM_CONTENTBUILDER_NEW' ) : JText::_( 'COM_CONTENTBUILDER_EDIT' );
        
        if(version_compare($version->getShortVersion(), '3.0', '<')){
            JToolBarHelper::title(   '<img src="components/com_contentbuilder/views/logo_right.png" alt="" align="top" /> <span style="display:inline-block; vertical-align:middle"> :: ' . ($isNew ? JText::_( 'COM_CONTENTBUILDER_FORM' ) : $form->name) .' : <small><small>[ ' . $text.' ]</small></small></span>', 'logo_left.png' );
        } else {
            JToolBarHelper::title(   'ContentBuilder :: ' . ($isNew ? JText::_( 'COM_CONTENTBUILDER_FORM' ) : $form->name) .' : <small><small>[ ' . $text.' ]</small></small></span>', 'logo_left.png' );
        }
        
        //JToolBarHelper::customX('linkable', 'default', '', JText::_('COM_CONTENTBUILDER_LINKABLE'), false);
        //JToolBarHelper::customX('not_linkable', 'default', '', JText::_('COM_CONTENTBUILDER_NOT_LINKABLE'), false);
        
        JToolBarHelper::apply();
        JToolBarHelper::save();
        
        if(version_compare($version->getShortVersion(), '3.0', '<')){
            JToolBarHelper::customX('list_include', 'default', '', JText::_('COM_CONTENTBUILDER_LIST_INCLUDE'), false);
            JToolBarHelper::customX('no_list_include', 'default', '', JText::_('COM_CONTENTBUILDER_NO_LIST_INCLUDE'), false);
            JToolBarHelper::customX('editable', 'edit', '', JText::_('COM_CONTENTBUILDER_EDITABLE'), false);
            JToolBarHelper::customX('not_editable', 'edit', '', JText::_('COM_CONTENTBUILDER_NOT_EDITABLE'), false);
            JToolBarHelper::customX('listpublish', 'publish', '', JText::_('COM_CONTENTBUILDER_PUBLISH'), false);
            JToolBarHelper::customX('listunpublish', 'unpublish', '', JText::_('COM_CONTENTBUILDER_UNPUBLISH'), false);
            JToolBarHelper::customX('saveNew', 'save', '', JText::_('COM_CONTENTBUILDER_SAVENEW'), false);
        } else {
            JToolBarHelper::custom('saveNew', 'save', '', JText::_('COM_CONTENTBUILDER_SAVENEW'), false);
            JToolBarHelper::custom('list_include', 'menu', '', JText::_('COM_CONTENTBUILDER_LIST_INCLUDE'), false);
            JToolBarHelper::custom('no_list_include', 'menu', '', JText::_('COM_CONTENTBUILDER_NO_LIST_INCLUDE'), false);
            JToolBarHelper::custom('editable', 'edit', '', JText::_('COM_CONTENTBUILDER_EDITABLE'), false);
            JToolBarHelper::custom('not_editable', 'edit', '', JText::_('COM_CONTENTBUILDER_NOT_EDITABLE'), false);
            JToolBarHelper::custom('listpublish', 'publish', '', JText::_('COM_CONTENTBUILDER_PUBLISH'), false);
            JToolBarHelper::custom('listunpublish', 'unpublish', '', JText::_('COM_CONTENTBUILDER_UNPUBLISH'), false);
            
        }
        //JToolBarHelper::deleteList();
        if ($isNew) {
            JToolBarHelper::cancel();
        } else {
            // for existing items the button is renamed `close`
            JToolBarHelper::cancel( 'cancel', 'Close' );
        }

        $state = $this->get( 'state' );
        $lists['order_Dir'] = $state->get( 'elements_filter_order_Dir' );
        $lists['order'] = $state->get( 'elements_filter_order' );
        $lists['limitstart'] = $state->get( 'limitstart' );

        $ordering = ($lists['order'] == 'ordering');

        jimport('joomla.version');
        $version = new JVersion();

        $gmap = array();
        if (version_compare($version->getShortVersion(), '1.6', '<')) {
            $acl = JFactory::getACL();
            $gmap = $acl->get_group_children_tree( null, 'USERS', false );
        }else{
            $db = JFactory::getDbo();
            $query = 'SELECT CONCAT( REPEAT(\'..\', COUNT(parent.id) - 1), node.title) as text, node.id as value'
                    . ' FROM #__usergroups AS node, #__usergroups AS parent'
                    . ' WHERE node.lft BETWEEN parent.lft AND parent.rgt'
                    . ' GROUP BY node.id'
                    . ' ORDER BY node.lft';
            $db->setQuery($query);
            $gmap = $db->loadObjectList();
        }
        
        $form->config = unserialize(cb_b64dec($form->config));
        
        $actionPlugins = $this->get('ListStatesActionPlugins');
        $verificationPlugins = $this->get('VerificationPlugins');
        $themePlugins = $this->get('ThemePlugins');
        
        $this->assignRef('list_states_action_plugins', $actionPlugins);
        $this->assignRef('verification_plugins', $verificationPlugins);
        $this->assignRef('theme_plugins', $themePlugins);
        $this->assignRef('gmap', $gmap);
        $this->assignRef('ordering', $ordering);
        $this->assignRef('form', $form);
        $this->assignRef('elements', $elements);
        $this->assignRef('all_elements', $all_elements);
        $this->assignRef('pagination', $pagination );
        parent::display($tpl);
    }
}
