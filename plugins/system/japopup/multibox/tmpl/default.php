<?php
/**
 * ------------------------------------------------------------------------
 * JA Popup Plugin for Joomla 25 & 34
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<a class="<?php echo $arrData['class'];?>" id="<?php echo $arrData['id'];?>" title="<?php echo $arrData['title'];?>" href="<?php echo $arrData['href'];?>" rel="<?php echo $arrData['rel'];?>" ><?php echo $arrData['content'];?></a>
<?php if (trim($arrData['desc'])!=''):?><span class="multiBoxDesc <?php echo $arrData['id'];?>"><?php echo $arrData['desc'];?></span><?php endif;?>