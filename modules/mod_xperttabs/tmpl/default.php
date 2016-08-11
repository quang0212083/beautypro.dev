<?php
/**
 * @package Xpert Tabs
 * @version 3.7
 * @author ThemeXpert http://www.themexpert.com
 * @copyright Copyright (C) 2009 - 2011 ThemeXpert
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */

?>
<!--Xpert Tabs 3.3 by ThemeXpert(www.themexpert.com)- Start-->
<div id="<?php echo $module_id;?>" class="txtabs-wrap <?php echo $params->get('mod_style','style1');?>">

    <?php if($tabs_position == 'top') echo $tabs_title;?>

    <div class="txtabs-content">
    <?php for($i=0; $i<$tabs; $i++): ?>

            <?php $class = ($i == 0) ? ' active in' : '';?>

            <div class="txtabs-pane<?php echo $class; ?> <?php echo $transition; ?>" id="<?php echo $module_id . '-'. $i; ?>">
                <div class="txtabs-pane-in clearfix">
                    <?php echo $items[$i]->introtext; ?>

                    <?php if( $content_source != 'module' AND $params->get('readmore', 1) ) :?>
                        <p class="txtabs-readon">
                            <a class="btn" href="<?php echo $items[$i]->link; ?>">
                                <span> <?php echo $params->get('readmore_label'); ?> </span>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

    <?php endfor; ?>
    </div>

    <?php if($tabs_position == 'bottom') echo $tabs_title;?>

</div>
<!--Xpert Tabs 3.3 by ThemeXpert- End-->