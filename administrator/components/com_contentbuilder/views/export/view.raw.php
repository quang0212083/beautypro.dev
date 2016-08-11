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

class ContentbuilderViewExport extends CBView
{
    function display($tpl = null)
    {
        // Get data from the model
        $data = $this->get('Data');
        $this->assignRef( 'data', $data );
        parent::display($tpl);
    }
}
