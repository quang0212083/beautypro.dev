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

class ContentbuilderViewStorages extends CBView
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
        
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            JToolBarHelper::addNew();
            JToolBarHelper::editList();
        }
        
        if(version_compare(CBJOOMLAVERSION, '3.0', '<')){
            JToolBarHelper::title(   '<img src="components/com_contentbuilder/views/logo_right.png" alt="" align="top" /> <span style="display:inline-block; vertical-align:middle"> :: ' . JText::_( 'COM_CONTENTBUILDER_STORAGES' ) . '</span>', 'logo_left.png' );
        }else{
            JToolBarHelper::title(   'ContentBuilder :: ' . JText::_( 'COM_CONTENTBUILDER_STORAGES' ) . '</span>', 'logo_left.png' );
        }
        
        JToolBarHelper::deleteList();
        
        if(version_compare($version->getShortVersion(), '3.0', '<')){
            JToolBarHelper::editListX();
            JToolBarHelper::addNewX();
        }

        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            JToolBarHelper::preferences('com_contentbuilder');
        }
        
        // Get data from the model
        $items = $this->get( 'Data');
        $pagination = $this->get('Pagination');

        $state = $this->get( 'state' );
        $lists['order_Dir'] = $state->get( 'storages_filter_order_Dir' );
        $lists['order'] = $state->get( 'storages_filter_order' );
        $lists['state']	= JHTML::_('grid.state', $state->get( 'storages_filter_state' ) );
        $lists['limitstart'] = $state->get( 'limitstart' );
        
        $ordering = ($lists['order'] == 'ordering');

        $this->assignRef('ordering', $ordering);
        $this->assignRef( 'lists', $lists );

        $this->assignRef( 'items', $items );
        $this->assignRef( 'pagination', $pagination );
        parent::display($tpl);
    }
}
