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

CBCompat::requireView();

class ContentbuilderViewForms extends CBView
{
    function display($tpl = null)
    {
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
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            JToolBarHelper::title(   '<img src="components/com_contentbuilder/views/logo_right.png" alt="" align="top" /> <span style="display:inline-block; vertical-align:middle"> :: ' . JText::_( 'COM_CONTENTBUILDER_FORMS' ) . '</span>', 'logo_left.png' );
        } else {
            JToolBarHelper::title(   'ContentBuilder :: ' . JText::_( 'COM_CONTENTBUILDER_FORMS' ) . '</span>', 'logo_left.png' );
        }
        
        if(version_compare($version->getShortVersion(), '3.0', '<')){
            JToolBarHelper::editListX();
            JToolBarHelper::addNewX();
            JToolBarHelper::customX('copy', 'copy', '', JText::_('COM_CONTENTBUILDER_COPY'));
        } else {
            JToolBarHelper::addNew();
            JToolBarHelper::custom('copy', 'copy', '', JText::_('COM_CONTENTBUILDER_COPY'));
            JToolBarHelper::editList();
        }
        
        JToolBarHelper::deleteList();
        
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            JToolBarHelper::preferences('com_contentbuilder');
        }
        
        // Get data from the model
        $items = $this->get( 'Data');
        $pagination = $this->get('Pagination');
        $tags = $this->get( 'Tags');
        
        $state = $this->get( 'state' );
        $lists['order_Dir'] = $state->get( 'forms_filter_order_Dir' );
        $lists['order'] = $state->get( 'forms_filter_order' );
        $lists['state']	= JHTML::_('grid.state', $state->get( 'forms_filter_state' ) );
        $lists['limitstart'] = $state->get( 'limitstart' );
        $lists['filter_tag'] = $state->get( 'forms_filter_tag' );
        
        $ordering = ($lists['order'] == 'ordering');

        $this->assignRef('ordering', $ordering);
        $this->assignRef( 'tags', $tags );
        $this->assignRef( 'lists', $lists );

        $this->assignRef( 'items', $items );
        $this->assignRef( 'pagination', $pagination );
        parent::display($tpl);
    }
}
