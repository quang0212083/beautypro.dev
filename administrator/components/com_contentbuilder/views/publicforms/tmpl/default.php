<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.version');
$version = new JVersion();
$th = 'td';
if (version_compare($version->getShortVersion(), '1.6', '>=')) {
    $th = 'th';
}
if($this->page_heading){
?>
<h1 class="contentheading">
    <?php echo JFactory::getDocument()->getTitle(); ?>
</h1>
<?php
}
?>
<form action="" method="get" id="adminForm" name="adminForm">

    <?php
    if($this->show_tags){
    ?>
    <?php echo JText::_( 'COM_CONTENTBUILDER_FILTER_TAG' ); ?>: 
    <select name="filter_tag" onchange="document.adminForm.submit();">
        <option value=""> - <?php echo htmlentities(JText::_('COM_CONTENTBUILDER_FILTER_TAG_ALL'), ENT_QUOTES, 'UTF-8')?> - </option>
    <?php
    foreach($this->tags As $tag){
    ?>
        <option value="<?php echo htmlentities($tag->tag, ENT_QUOTES, 'UTF-8')?>"<?php echo strtolower($this->lists['filter_tag']) == strtolower($tag->tag) ? ' selected="selected"' : ''; ?>><?php echo htmlentities($tag->tag, ENT_QUOTES, 'UTF-8')?></option>
    <?php
    }
    ?>
    </select>
    <br/>
    <?php
    }
    ?>
    <table class="category" width="100%" border="0" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            
            <?php
            if($this->show_id){
            ?>
            
            <<?php echo $th; ?> width="5" class="sectiontableheader">
                <?php echo JText::_( 'COM_CONTENTBUILDER_ID' ); ?>
                <?php //echo JHTML::_('grid.sort', JText::_( 'COM_CONTENTBUILDER_ID' ), 'id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </<?php echo $th; ?>>
            
            <?php
            }
            ?>
            
            <<?php echo $th; ?> style="width: 200px !important;" class="sectiontableheader">
                <?php echo JText::_( 'COM_CONTENTBUILDER_VIEW_NAME' ); ?>
                <?php // echo JHTML::_('grid.sort', JText::_( 'COM_CONTENTBUILDER_VIEW_NAME' ), 'name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </<?php echo $th; ?>>
            
            <?php
            if($this->show_tags){
            ?>
            
            <<?php echo $th; ?> class="sectiontableheader">
                <?php echo JHTML::_('grid.sort', JText::_( 'COM_CONTENTBUILDER_TAG' ), 'tag', $this->lists['order_Dir'], $this->lists['order'] ); ?>
            </<?php echo $th; ?>>
            
            <?php
            }
            ?>
            
            <?php
            if($this->introtext){
            ?>
            
            <<?php echo $th; ?> class="sectiontableheader">
            <?php echo JText::_('COM_CONTENTBUILDER_INTROTEXT'); ?>
            </<?php echo $th; ?>>
            
            <?php
            }
            ?>
            
            <?php
            if($this->show_permissions){
            ?>
            
            <<?php echo $th; ?> class="sectiontableheader">
            <?php echo JText::_('COM_CONTENTBUILDER_ACCESS_VIEW'); ?>
            </<?php echo $th; ?>>
            
            <?php
            }
            ?>
            
            <?php
            if($this->show_permissions_new){
            ?>
            
            <<?php echo $th; ?> class="sectiontableheader">
            <?php echo JText::_('COM_CONTENTBUILDER_ACCESS_NEW'); ?>
            </<?php echo $th; ?>>
            
            <?php
            }
            ?>
            
            <?php
            if($this->show_permissions_edit){
            ?>
            
            <<?php echo $th; ?> class="sectiontableheader">
            <?php echo JText::_('COM_CONTENTBUILDER_ACCESS_EDIT'); ?>
            </<?php echo $th; ?>>
            
            <?php
            }
            ?>
            
        </tr>
    </thead>
    <?php
    $k = 0;
    $n = count( $this->items );
    for ($i=0; $i < $n; $i++)
    {
        $row = $this->items[$i];
        $link_ = htmlentities($row->name, ENT_QUOTES, 'UTF-8');
        if( ( $this->show_permissions && $this->perms[$row->id]['view'] ) || !$this->show_permissions ){
            $link = JRoute::_( 'index.php?option=com_contentbuilder&title='.contentbuilder::stringURLUnicodeSlug($row->name).'&controller=list&id='. $row->id );
            $link_ = '<a href="'.$link.'">'.htmlentities($row->name, ENT_QUOTES, 'UTF-8').'</a>';
        }
        ?>
        <tr class="<?php echo "row$k"; ?>">
            
            <?php
            if($this->show_id){
            ?>
            
            <td valign="top" >
                <?php echo $row->id; ?>
            </td>
            
            <?php
            }
            ?>
            
            <td valign="top" >
                <?php echo $link_;?>
            </td>
            
            <?php
            if($this->show_tags){
            ?>
            
            <td valign="top" >
                <?php echo $row->tag; ?>
            </td>
            
            <?php
            }
            ?>
            
            <?php
            if($this->introtext){
                // Search for the {readmore} tag and split the text up accordingly.
                $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
                $tagPos = preg_match($pattern, $row->intro_text);

                if ($tagPos == 0) {
                    $introtext = $row->intro_text;
                } else {
                    list($introtext, $fulltext) = preg_split($pattern, $row->intro_text, 2);
                }
            ?>
            <td>
                <?php echo $introtext; ?>
            </td>
            <?php
            }
            ?>
            
            <?php
            if($this->show_permissions){
            ?>
            
            <td valign="top" >
                <img width="16" height="16" alt="" src="<?php echo $this->perms[$row->id]['view'] ? JURI::root(true).'/components/com_contentbuilder/assets/images/tick.png' : JURI::root(true).'/components/com_contentbuilder/assets/images/untick.png'; ?>"/>
            </td>
            
            <?php
            }
            ?>
            
            <?php
            if($this->show_permissions && $this->show_permissions_new){
            ?>
            
            <td valign="top" >
                <img width="16" height="16" alt="" src="<?php echo $this->perms[$row->id]['new'] ? JURI::root(true).'/components/com_contentbuilder/assets/images/tick.png' : JURI::root(true).'/components/com_contentbuilder/assets/images/untick.png'; ?>"/>
            </td>
            
            <?php
            }
            ?>
            
            <?php
            if($this->show_permissions && $this->show_permissions_edit){
            ?>
            
            <td valign="top" >
                <img width="16" height="16" alt="" src="<?php echo $this->perms[$row->id]['edit'] ? JURI::root(true).'/components/com_contentbuilder/assets/images/tick.png' : JURI::root(true).'/components/com_contentbuilder/assets/images/untick.png'; ?>"/>
            </td>
            
            <?php
            }
            ?>
        </tr>
        <?php
        $k = 1 - $k;
    }
    
    $pages_links = $this->pagination->getPagesLinks();
    if( $pages_links ){
    ?>
        <tfoot>
            <tr>
                <td colspan="9"><?php echo $pages_links; ?></td>
            </tr>
        </tfoot>
    <?php
    }
    ?>

    </table>


<input type="hidden" name="option" value="com_contentbuilder" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid',0); ?>"/>
<input type="hidden" name="limitstart" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="publicforms" />
<input type="hidden" name="view" id="view" value="publicforms" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
<?php echo JHtml::_('form.token'); ?>
</form>

