<?php

/**
 * @copyright   Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Layouts
 * @contact	support+paycart@readybytes.in
 * @author 	Manish Trivedi  
 */

defined('_JEXEC') or die();

/**
 * List of Populated Variables
 * $displayData = have all required data 
 * $displayData->product_particulars 
 * 
 */

$product_particulars = $displayData->product_particulars;

if (count($product_particulars) <= 0 ) {
    return ;
}

$grand_total = 0;
?>

<table  border="1">
        <tr>
           <th> 
               <?php
                    echo JText::_('COM_PAYCART_PRODUCT');
               ?>
           </th>
           <th> 
               <?php
                    echo JText::_('COM_PAYCART_QUANTITY');
               ?>
           </th>
           <th> 
               <?php
                    echo JText::_('COM_PAYCART_PRICE');
               ?>
           </th>
        </tr>
    <?php
        
        foreach ($product_particulars as $particular) :
        
    ?>
            <tr>
                <td> 
                    <?php
                        echo $particular->title;
                    ?>
                </td>
                <td> 
                    <?php
                        echo $particular->quantity;
                    ?>
                </td>
                <td> 
                    <?php
                        $grand_total += $particular->total;
                        echo $particular->total;
                    ?>
                </td>
            </tr>
    <?php 
        endforeach;
    ?>
            <tr>
                <td colspan="2"> 
                    <?php
                        echo JText::_('COM_PAYCART_GRAND_TOTAL');
                    ?>
                </td>
                
                <td> 
                    <?php
                        echo $grand_total;
                    ?>
                </td>
                
            </tr>
</table>    




