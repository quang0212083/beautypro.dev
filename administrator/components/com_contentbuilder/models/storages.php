<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');

CBCompat::requireModel();

class ContentbuilderModelStorages extends CBModel
{
    /**
     * Items total
     * @var integer
     */
    private $_total = null;

    /**
     * Pagination object
     * @var object
     */
    private $_pagination = null;

    function  __construct($config) {
        parent::__construct($config);

        $mainframe = JFactory::getApplication();
        $option = 'com_contentbuilder';

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);

        $filter_order     = $mainframe->getUserStateFromRequest(  $option.'storages_filter_order', 'filter_order', '`name`', 'cmd' );
        $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'storages_filter_order_Dir', 'filter_order_Dir', 'desc', 'word' );

        $this->setState('storages_filter_order', $filter_order);
        $this->setState('storages_filter_order_Dir', $filter_order_Dir);

        $filter_state = $mainframe->getUserStateFromRequest( $option.'storages_filter_state', 'filter_state', '', 'word' );
        $this->setState('storages_filter_state', $filter_state);
    }

    function setPublished()
    {
        $cids = JRequest::getVar('cid', array(), '', 'array');
        JArrayHelper::toInteger($cids);
        $this->_db->setQuery( ' Update #__contentbuilder_storages '.
                '  Set published = 1 Where id In ( ' . implode(',',$cids)  . ')');
        $this->_db->query();

    }

    function setUnpublished()
    {
        $cids = JRequest::getVar('cid', array(), '', 'array');
        JArrayHelper::toInteger($cids);
        $this->_db->setQuery( ' Update #__contentbuilder_storages '.
                '  Set published = 0 Where id In ( ' . implode(',',$cids)  . ')');
        $this->_db->query();
    }

    /*
     *
     * MAIN LIST AREA
     * 
     */

    private function buildOrderBy() {
        $mainframe = JFactory::getApplication();
        $option = 'com_contentbuilder';

        $orderby = '';
        $filter_order     = $this->getState('storages_filter_order');
        $filter_order_Dir = $this->getState('storages_filter_order_Dir');

        /* Error handling is never a bad thing*/
        if(!empty($filter_order) && !empty($filter_order_Dir) ) {
            $orderby = ' ORDER BY '.$filter_order.' '.$filter_order_Dir;
        }

        return $orderby;
    }


    /**
     * @return string The query
     */
    private function _buildQuery(){

        $where = '';

        // PUBLISHED FILTER SELECTED?
        $filter_state = '';
        if($this->getState('storages_filter_state') == 'P' || $this->getState('storages_filter_state') == 'U')
        {
            $published = 0;
            if($this->getState('storages_filter_state') == 'P')
            {
                $published = 1;
            }

            $and = ' And';

            $filter_state .= ' published = ' . $published;
        }

        if($filter_state != '')
        {
            $where = ' Where ';
        }

        return 'Select SQL_CALC_FOUND_ROWS * From #__contentbuilder_storages ' . $where . $filter_state . $this->buildOrderBy();
    }

    function saveOrder()
    {
        $items	= JRequest::getVar( 'cid', array(), 'post', 'array' );
        JArrayHelper::toInteger($items);

        $total		= count( $items );
        $row		= $this->getTable('storage');
        $groupings	= array();

        $order		= JRequest::getVar( 'order', array(), 'post', 'array' );
        JArrayHelper::toInteger($order);

        // update ordering values
        for( $i=0; $i < $total; $i++ ) {
            $row->load( $items[$i] );
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    $this->setError($row->getError());
                    return false;
                }
            } // if
        } // for


        $row->reorder();
    }

    /**
    * Gets the currencies
    * @return array List of products
    */
    function getData()
    {
        // Lets load the data if it doesn't already exist
        if (empty( $this->_data ))
        {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList( $query, $this->getState('limitstart'), $this->getState('limit') );
        }

        return $this->_data;
    }

    function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
    }

    function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
    }

}
