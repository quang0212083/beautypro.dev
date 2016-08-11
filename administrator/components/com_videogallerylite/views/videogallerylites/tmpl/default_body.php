<?php
/**
 * @package  Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 


defined('_JEXEC') or die;
foreach ($this->gallery as $i => $item) : ?>
	 <tr  style="height: 38px; <?php if($item->id %2 == 0) echo "background:#F0F0F0 "?>">
                <td style="height: 41px;">
            <?php echo "<span style='margin-left:11px'>". JHtml::_('grid.id', $i, $item->id)."</span>"?>
                    <?php echo "<span style='position:relative;top:3px'>".$this->escape($item->id)."</div>"; ?>
	   
                </td>
		<td style="height: 41px;">
            <?php  $id = $item->id; ?>
                <a href="<?php echo JURI::base().'index.php?option=com_videogallerylite&view=videogallerylite&id='.$id ?>"> 
                    <?php echo $this->escape($item->name); ?>
                </a>
		</td>
		    <td style="height: 41px;">
            <?php  $id = $item->id; ?>
                    <span>
                   [huge_it_videogallery_id="<?php echo $this->escape($item->id); ?>"] 
                    </span>
		</td>
                 <td style="height: 41px;"> <?php echo $item->count?></td>
       </tr>
<?php endforeach; ?>
<?php
       foreach ($this->other as $i => $item) : ?>
        <tr style="height: 38px; <?php if($item->id %2 == 0) echo "background:#F0F0F0 "?>">
                <td style="height: 41px;" >
             <?php echo "<span style='margin-left:11px'>". JHtml::_('grid.id', $i, $item->id)."</span>"?>
	    <?php echo "<span style='position:relative;top:3px'>".$this->escape($item->id)."</div>"; ?>
                </td>
		<td style="height: 41px;" >
              <?php  $id = $item->id; ?>
                <a href="<?php echo JURI::base().'index.php?option=com_videogallerylite&view=videogallerylite&id='.$id ?>"> 
                    <?php echo $this->escape($item->name); ?>
                </a>
		</td>
		    <td style="height: 41px;">
            <?php  $id = $item->id; ?>
                    <span>
                   [huge_it_videogallery_id="<?php echo $this->escape($item->id); ?>"] 
                    </span>
		</td>
                 <td style="height: 41px;"> <?php echo $item->count?></td>
       </tr>
<?php endforeach; ?>