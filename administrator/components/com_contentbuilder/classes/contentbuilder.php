<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');

require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_contentbuilder'.DS.'classes'.DS.'joomla_compat.php');
require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'contentbuilder_helpers.php');

jimport('joomla.version');
$version = new JVersion();

if(version_compare($version->getShortVersion(), '1.7', '>=')){
    require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'plugin_helper.php');
} else {
    require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'plugin_helper15.php');
}

class contentbuilder{

    public static function makeSafeFolder($path)
    {
            //$ds = (DS == '\\') ? '\\/' : DS;
            $regex = array('#[^A-Za-z0-9\.:_\\\/-]#');
            return preg_replace($regex, '_', $path);
    }
    
    public static function getPagination($limitstart, $limit, $total){
        
        $pages_total = 0;
        $pages_current = 0;
        
        if($limit > $total) {
                $limitstart = 0;
        }

        if($limit < 1)
        {
            $limit = $total;
            $limitstart = 0;
        }
        
        if ($limitstart > $total - $limit) {
                $limitstart = max(0, (int)(ceil($total / $limit) - 1) * $limit);
        }

        if ($limit > 0)
        {
                $pages_total   = ceil($total / $limit);
                $pages_current = ceil(($limitstart + 1) / $limit);
        }
        
        $url  = JURI::getInstance()->toString();
        $query = JURI::getInstance()->getQuery(true);
        if(isset($query['start'])){
            unset($query['start']);
        }
        if(count($expl_url = explode('?',$url)) > 1){
            $impl = '';
            foreach($query As $key => $value){
                $impl .= $key.'='.$value.'&';
            }
            $impl = trim($impl,'&');
            $url = $expl_url[0] . '?' . $impl;
        }
        
        $open = JRoute::_($url.(strstr($url,'?') !== false ? '&' : '?'));
        $end = '';
        $begin = '';
        $disp = $limit;
        
        if( !is_int($limit/2) ){
            $disp = 10;
        }
       
        $start = $pages_current - ($disp/2);
        if($start < 1){
            $start = 1;
        }
        
        $stop = $pages_total;
        
        if (($start + $disp) > $pages_total) {
            $stop = $pages_total;
            if ($pages_total < $disp) {
                $start = 1;
            } else {
                $start = $pages_total - $disp + 1;
                $begin = '<li><span class="pagenav">...</span></li>';
            }
        } else {
            if($start > 1){
                $begin = '<li><span class="pagenav">...</span></li>';
            }
            $stop = $start + $disp - 1;
            $end = '<li><span class="pagenav">...</span></li>';
        }
        
        $c = '';
        
        if($pages_total > 1){
            ob_start();
        ?>
<div class="pagination">
    <ul>
        <li class="pagination-start">
            <?php echo $pages_current - 1 > 0 ? '<a title="'.JText::_('COM_CONTENTBUILDER_START').'" href="'.$open.'" class="pagenav">'.JText::_('COM_CONTENTBUILDER_START').'</a>' : '<span class="pagenav">'.JText::_('COM_CONTENTBUILDER_START').'</span>' ;?>
        </li>
        <li class="pagination-prev">
            <?php echo $pages_current - 1 > 0 ? '<a title="'.JText::_('COM_CONTENTBUILDER_PREV').'" href="'.$open.'start='.($limitstart - $limit).'" class="pagenav">'.JText::_('COM_CONTENTBUILDER_PREV').'</a>' : '<span class="pagenav">'.JText::_('COM_CONTENTBUILDER_PREV').'</span>' ;?>
        </li>
        <?php echo $begin;?>
        <?php
        for($i = $start; $i <= $stop; $i++){
            if($i != $pages_current){
        ?>
        <li><a title="<?php echo $i;?>" href="<?php echo $open;?>start=<?php echo ($i-1)*$limit;?>" class="pagenav"><?php echo $i;?></a></li>
        <?php
            }else{
        ?>
        <li><span class="pagenav"><?php echo $i;?></span></li>
        <?php
            }
        }
        ?>
        <?php echo $end;?>
        <li class="pagination-next"><?php echo $pages_current < $pages_total ? '<a title="'.JText::_('COM_CONTENTBUILDER_NEXT').'" href="'.$open.'start='.($pages_current * $limit).'" class="pagenav">'.JText::_('COM_CONTENTBUILDER_NEXT').'</a>' : '<span class="pagenav">'.JText::_('COM_CONTENTBUILDER_NEXT').'</span>' ;?></li>
        <li class="pagination-end"><?php echo $pages_total > 1 && $pages_current < $pages_total ? '<a title="'.JText::_('COM_CONTENTBUILDER_END').'" href="'.$open.'start='.(($pages_total-1) * $limit).'" class="pagenav">'.JText::_('COM_CONTENTBUILDER_END').'</a>' : '<span class="pagenav">'.JText::_('COM_CONTENTBUILDER_END').'</span>' ;?></li>
    </ul>
</div>
        <?php
            $c = ob_get_contents();
            ob_end_clean();
        }
        
        return $c;
    }
    
    public static function getRating($form_id, $record_id, $colRating, $rating_slots, $lang, $rating_allowed, $rating_count, $rating_sum){
        
        static $cssLoaded;
        
        if(!$cssLoaded){
            
        JFactory::getDocument()->addStyleDeclaration('.cbVotingDisplay, .cbVotingStarButtonWrapper {
	height: 20px;
	width: 100px;
}

.cbVotingStarButtonWrapper {
	position: absolute;
	z-index: 100;	
}

.cbVotingDisplay {
	background-image: url('.JURI::root(true).'/components/com_contentbuilder/assets/images/bg_votingStarOff.png);
	background-repeat: repeat-x;
        height: auto;
}

.cbVotingStars {
	position: relative;
	float: left;
	height: 20px;
	overflow: hidden;
	background-image: url('.JURI::root(true).'/components/com_contentbuilder/assets/images/bg_votingStarOn.png);
	background-repeat: repeat-x;
}

.cbVotingStarButton {
	display: inline;
	height: 20px;
	width: 20px;
	float: left;
	cursor: pointer;
}

.cbRating{ width: 30px; }

.cbRatingUpDown{text-align: center; width: 90px; }

.cbRatingImage {margin: auto; display: block;}

.cbRatingCount {text-align: center; font-size: 11px;}

.cbRatingVotes {text-align: center; font-size: 11px;} 

.cbRatingImage2{
    width: 30px;
    height: 30px;
    background-image: url('.JURI::root(true).'/components/com_contentbuilder/assets/images/thumbs_down.png);
    background-repeat: no-repeat;
}

.cbRatingImage{ 
    width: 30px;
    height: 30px;
    background-image: url('.JURI::root(true).'/components/com_contentbuilder/assets/images/thumbs_up.png);
    background-repeat: no-repeat;
}');
            
            $cssLoaded = true;
        }
        
        ob_start();
        if($rating_count){
            $percentage2 = round( ( $colRating / 5 ) * 100, 2 );
            $percentage3 = 100-$percentage2;
        }else{
            $percentage2 = 0;
            $percentage3 = 0;
        }
        $percentage = round( ($colRating / $rating_slots) * ($rating_slots*20) );
        if($rating_slots > 2){
        ?>
        <div class="cbVotingDisplay" style="width: <?php echo ($rating_slots*20);?>px;">
        <div class="cbVotingStarButtonWrapper">
        <?php
        }
        $rating_link = '';
        if($rating_allowed){
            if(JFactory::getApplication()->isSite()){
                $rating_link = JURI::root(true) . (JFactory::getApplication()->isAdmin() ? '/administrator' : (JRequest::getCmd('lang','') && CBCompat::getJoomlaConfig('config.sef') && CBCompat::getJoomlaConfig('config.sef_rewrite') ? '/'.JRequest::getCmd('lang','') : '') ).'/?option=com_contentbuilder&lang='.$lang.'&controller=ajax&format=raw&subject=rating&id='.$form_id.'&record_id='.$record_id;
            }else{
                $rating_link = 'index.php?option=com_contentbuilder&lang='.$lang.'&controller=ajax&format=raw&subject=rating&id='.$form_id.'&record_id='.$record_id;
            }
        }
        for($x = 1; $x <= $rating_slots;$x++){
            if($rating_link){
                if($rating_slots > 2){
        ?>
                <div onmouseout="document.getElementById('cbVotingStars<?php echo $record_id; ?>').style.width=<?php echo $percentage; ?>+'px';" onmouseover="document.getElementById('cbVotingStars<?php echo $record_id; ?>').style.width=(<?php echo $x;?>*20)+'px';" class="cbVotingStarButton" id="cbVotingStarButton_<?php echo $x;?>" onclick="cbRate('<?php echo $rating_link.'&rate='.$x; ?>','cbRatingMsg<?php echo $record_id; ?>');"></div>
        <?php
                }else if($rating_slots == 2){
        ?>
                <div class="cbRatingUpDown">
                    <div style="float: left;">
                        <div class="cbRatingImage" style="cursor:pointer;" onclick="cbRate('<?php echo $rating_link.'&rate=5'; ?>','cbRatingMsg<?php echo $record_id; ?>');"></div>
                        <div align="center" class="cbRatingCount"><?php echo $percentage2 ? $percentage2.'%' : '';?></div>
                    </div>
                    <div style="float: right;">
                        <div class="cbRatingImage2" style="cursor:pointer;" onclick="cbRate('<?php echo $rating_link.'&rate=1'; ?>','cbRatingMsg<?php echo $record_id; ?>');"></div>
                        <div align="center" class="cbRatingCount"><?php echo $percentage3 ? $percentage3.'%' : '';?></div>
                    </div>
                    <div style="clear: both;"></div>
                    <div align="center" class="cbRatingVotes"><?php echo $rating_count == 1 ? $rating_count . ' ' . JText::_('COM_CONTENTBUILDER_VOTES_SINGULAR') : $rating_count . ' ' . JText::_('COM_CONTENTBUILDER_VOTES_PLURAL');?></div>
                </div>
        <?php
                    break;
                }else{
        ?>
                <div class="cbRating">
                <div class="cbRatingImage" style="cursor:pointer;" onclick="cbRate('<?php echo $rating_link.'&rate='.$x; ?>','cbRatingMsg<?php echo $record_id; ?>');"></div>
                <div align="center" id="cbRatingMsg<?php echo $record_id; ?>Counter" class="cbRatingCount"><?php echo $rating_count;?></div>
                <div align="center" class="cbRatingVotes"><?php echo $rating_count == 1 ? JText::_('COM_CONTENTBUILDER_VOTES_SINGULAR') : JText::_('COM_CONTENTBUILDER_VOTES_PLURAL');?></div>
                </div>
        <?php
                }
            }else{
                if($rating_slots > 2){
        ?>
                <div class="cbVotingStarButton" style="cursor:default;" id="cbVotingStarButton_<?php echo $x;?>"></div>
        <?php
                }else if($rating_slots == 2){
        ?>
                <div class="cbRatingUpDown">
                    <div style="float: left;">
                        <div class="cbRatingImage" style="cursor:default;"></div>
                        <div align="center" class="cbRatingCount"><?php echo $percentage2 ? $percentage2.'%' : '';?></div>
                    </div>
                    <div style="float: right;">
                        <div class="cbRatingImage2" style="cursor:default;"></div>
                        <div align="center" class="cbRatingCount"><?php echo $percentage3 ? $percentage3.'%' : '';?></div>
                    </div>
                    <div style="clear: both;"></div>
                    <div align="center" class="cbRatingVotes"><?php echo $rating_count == 1 ? $rating_count . ' ' . JText::_('COM_CONTENTBUILDER_VOTES_SINGULAR') : $rating_count . ' ' . JText::_('COM_CONTENTBUILDER_VOTES_PLURAL');?></div>
                </div>
        <?php
                    break;
                }else{
        ?>
                <div class="cbRating">
                <div class="cbRatingImage" style="cursor:default;"></div>
                <div align="center" class="cbRatingCount"><?php echo $rating_count;?></div>
                <div align="center" class="cbRatingVotes"><?php echo $rating_count == 1 ? JText::_('COM_CONTENTBUILDER_VOTES_SINGULAR') : JText::_('COM_CONTENTBUILDER_VOTES_PLURAL');?></div>
                </div>
        <?php
                }
            }
        }
        if($rating_slots > 2){
        ?>
        </div>
        <div class="cbVotingStars" id="cbVotingStars<?php echo $record_id; ?>" style="width: <?php echo $percentage;?>px;"></div>
        <div style="clear: left;"></div>
        <div align="center" class="cbRatingVotes"><?php echo $rating_count == 1 ? $rating_count . ' ' . JText::_('COM_CONTENTBUILDER_VOTES_SINGULAR') : $rating_count . ' ' . JText::_('COM_CONTENTBUILDER_VOTES_PLURAL');?></div>
        </div>
        <?php
        }
        ?>
        <div style="display:none;" class="cbRatingMsg" id="cbRatingMsg<?php echo $record_id; ?>"></div>
        <?php
        $c = ob_get_contents();
        ob_end_clean();
        return $c;
    }
    
    public static function execPhpValue($code){
        if(strpos(strtolower(trim($code)), '$value') === 0){
            eval($code);
            return $value;
        }
        return $code;
    }
    
    public static function execPhp($result){
        $value = $result;
        if(strpos(trim($result), '<?php') === 0){
            
            $code = trim($result);
            
            if(function_exists('mb_strlen')){
                $p1 = 0;
                $l = mb_strlen($code);
                $c = '';
                $n = 0;
                while ($p1 < $l) {
                        $p2 = mb_strpos($code, '<?php', $p1);
                        if ($p2 === false) $p2 = $l;
                        $c .= mb_substr($code, $p1, $p2-$p1);
                        $p1 = $p2;
                        if ($p1 < $l) {
                                $p1 += 5;
                                $p2 = mb_strpos($code, '?>', $p1);
                                if ($p2 === false) $p2 = $l;
                                $n++;
                                $c .= eval(mb_substr($code, $p1, $p2-$p1));
                                $p1 = $p2+2;
                        } // if
                } // while
            }else{
                $p1 = 0;
                $l = strlen($code);
                $c = '';
                $n = 0;
                while ($p1 < $l) {
                        $p2 = strpos($code, '<?php', $p1);
                        if ($p2 === false) $p2 = $l;
                        $c .= substr($code, $p1, $p2-$p1);
                        $p1 = $p2;
                        if ($p1 < $l) {
                                $p1 += 5;
                                $p2 = strpos($code, '?>', $p1);
                                if ($p2 === false) $p2 = $l;
                                $n++;
                                $c .= eval(substr($code, $p1, $p2-$p1));
                                $p1 = $p2+2;
                        } // if
                } // while
            }
        }
        
        return $value;
    }
    
    public static function createBackendMenuItem($contentbuilder_form_id, $name, $update){
        jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.6', '<')){
            self::createBackendMenuItem15($contentbuilder_form_id, $name, $update);
        }
        else if( version_compare($version->getShortVersion(), '1.6', '>=') && version_compare($version->getShortVersion(), '3.0', '<') ){
            self::createBackendMenuItem16($contentbuilder_form_id, $name, $update);
        } else {
            self::createBackendMenuItem3($contentbuilder_form_id, $name, $update);
        }
    }

    public static function getLanguageCodes(){
        
        static $langs;
        
        if(is_array($langs)){
            return $langs;
        }
        
        $db = JFactory::getDBO();
        
        jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.6', '<')){
            
            $langs = array();
            $client =& JApplicationHelper::getClientInfo(0);
            
            jimport('joomla.filesystem.folder');
            $path = JLanguage::getLanguagePath($client->path);
            $dirs = JFolder::folders( $path );
            
            jimport('joomla.filesystem.folder');
            $path = JLanguage::getLanguagePath($client->path);
            $dirs = JFolder::folders( $path );

            foreach ($dirs as $dir)
            {
                    $files = JFolder::files( $path.DS.$dir, '^([-_A-Za-z]*)\.xml$' );
                    foreach ($files as $file)
                    {
                            $data = JApplicationHelper::parseXMLLangMetaFile($path.DS.$dir.DS.$file);

                            $language 	= substr($file,0,-4);

                            if (!is_array($data)) {
                                    continue;
                            }

                            // if current than set published
                            $params = JComponentHelper::getParams('com_languages');
                            //if ( $params->get($client->name, 'en-GB') == $language) {
                                $langs[] = $language;
                            //}
                    }
            }
            
            return $langs;
        }
        else{
            $db->setQuery("Select lang_code From #__languages Where published = 1 Order By ordering");
            $langs = CBCompat::loadColumn();
            return $langs;
        }
    }
    
    public static function applyItemWrappers($contentbuilder_form_id, array $items, $form){
        
        jimport('joomla.version');
        $version = new JVersion();

        if(version_compare($version->getShortVersion(), '1.7', '>=')){
            require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'plugin_helper.php');
        } else {
            // joomla 1.6 shares the 1.5 code with JPluginHelper
            require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'classes' . DS . 'plugin_helper15.php');
        }

        $article = JTable::getInstance('content');
        $registry = null;
        $onContentPrepare = '';
        
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
           $onContentPrepare = 'onContentPrepare';
           $registry = new JRegistry;
           $registry->loadString('{}');
        } elseif(version_compare($version->getShortVersion(), '1.6', '>=')){
           $onContentPrepare = 'onContentPrepare';
           $registry = new JRegistry;
           $registry->loadJSON('{}');
        } else {
           $registry = JComponentHelper::getParams('com_content');
           $onContentPrepare = 'onPrepareContent';
        }
        
        $db = JFactory::getDBO();
        $db->setQuery("Select reference_id, item_wrapper, wordwrap, `label`, `options` From #__contentbuilder_elements Where published = 1 And form_id = " . intval($contentbuilder_form_id));
        $wrappers = $db->loadAssocList();
        foreach($wrappers As $wrapper){
            foreach($items As $item){
                foreach($item As $key => $value){
                    if( $key ==  'col'.$wrapper['reference_id']){
                        $new_value = '';
                        
                        if(strpos(trim($wrapper['item_wrapper']), '$') === 0){
                            
                            $article->id = 0;
                            
                            $w = explode('$',$wrapper['item_wrapper'], 2);
                            if(count($w) != 2){
                                break;
                            }
                            
                            $w = explode('$',implode('$',$w));
                            
                            $removables = array();
                            
                            if(count($w) == 2){
                                JPluginHelper::importPlugin('content');
                            }else{
                                $size = count($w) - 1;
                                for($j = 0; $j < $size; $j++){
                                    $plgs = CBPluginHelper::importPlugin('content', $w[$j]);
                                    $removables = array_merge($removables, $plgs);
                                }
                            }
                            
                            $dispatcher = JDispatcher::getInstance();
                            
                            $article->text = trim($w[count($w)-1]) ? trim($w[count($w)-1]) : $value;
                            $article->text = str_replace('{value_inline}',$value,$article->text);
                            $recc = new stdClass();
                            $recc->recName = $wrapper['label'];
                            $recc->recValue = $value;
                            $recc->recElementId = $wrapper['reference_id'];
                            $recc->colRecord = $item->colRecord;
                            
                            if(version_compare($version->getShortVersion(), '1.6', '>=')){
                                $dispatcher->trigger($onContentPrepare, array('com_content.article',&$article, &$registry, 0, true, $form, $recc));
                            }else{
                                $dispatcher->trigger($onContentPrepare, array(&$article, &$registry, 0, true, $form, $recc));
                            }
                            
                            foreach($removables As $removable){
                                $dispatcher->detach($removable);
                            }
                            
                            if($article->text != $w[count($w)-1]){
                                $item->$key = $article->text;
                                break;
                            }else{
                                $item->$key = '';
                                break;
                            }
                        }
                        
                        $allow_html = false;
                        $options = unserialize(cb_b64dec($wrapper['options']));
                        
                        if($options instanceof stdClass){
                            if(isset($options->allow_html) && $options->allow_html){
                                $allow_html = true;
                            }
                        }
                        
                        if($wrapper['wordwrap'] && !$allow_html){
                            $new_value = self::allhtmlentities( contentbuilder_wordwrap( cbinternal($value), $wrapper['wordwrap'], "\n", true ) );
                        }else{
                            $new_value = $allow_html ? self::cleanString(cbinternal($value)) : self::allhtmlentities(cbinternal($value));
                        }
                        
                        if(strpos(trim($wrapper['item_wrapper']), '<?php') === 0){
                            $value = $new_value;
                            $code = trim($wrapper['item_wrapper']);
                            if(function_exists('mb_strlen')){
                                $p1 = 0;
                                $l = mb_strlen($code);
                                $c = '';
                                $n = 0;
                                while ($p1 < $l) {
                                        $p2 = mb_strpos($code, '<?php', $p1);
                                        if ($p2 === false) $p2 = $l;
                                        $c .= mb_substr($code, $p1, $p2-$p1);
                                        $p1 = $p2;
                                        if ($p1 < $l) {
                                                $p1 += 5;
                                                $p2 = mb_strpos($code, '?>', $p1);
                                                if ($p2 === false) $p2 = $l;
                                                $n++;
                                                $c .= eval(mb_substr($code, $p1, $p2-$p1));
                                                $p1 = $p2+2;
                                        } // if
                                } // while
                            }else{
                                $p1 = 0;
                                $l = strlen($code);
                                $c = '';
                                $n = 0;
                                while ($p1 < $l) {
                                        $p2 = strpos($code, '<?php', $p1);
                                        if ($p2 === false) $p2 = $l;
                                        $c .= substr($code, $p1, $p2-$p1);
                                        $p1 = $p2;
                                        if ($p1 < $l) {
                                                $p1 += 5;
                                                $p2 = strpos($code, '?>', $p1);
                                                if ($p2 === false) $p2 = $l;
                                                $n++;
                                                $c .= eval(substr($code, $p1, $p2-$p1));
                                                $p1 = $p2+2;
                                        } // if
                                } // while
                            }
                            $item->$key = $value;
                        } else if(trim($wrapper['item_wrapper']) != ''){
                            $item->$key = str_replace('{value}',$new_value,trim($wrapper['item_wrapper']));
                            $item->$key = str_replace('{webpath}', str_replace(array('{CBSite}','{cbsite}',JPATH_SITE),JURI::getInstance()->getScheme().'://'.JURI::getInstance()->getHost().(JURI::getInstance()->getPort() == 80 ? '' : ':'.JURI::getInstance()->getPort()).JURI::root(true),$value), $item->$key);
                        }else{
                            $item->$key = $new_value;
                        }
                        break;
                    }
                }
            }
        }
        return $items;
    }

    public static function createBackendMenuItem15($contentbuilder_form_id, $name, $update){
        $db = JFactory::getDBO();
        $parent_id = 0;
        $db->setQuery("Select id From #__components Where `option`='' And admin_menu_link='option=com_contentbuilder&viewcontainer=true'");
        $res = $db->loadResult();
        if($res){
            $parent_id = $res;
        }else{
            $db->setQuery(
                "Insert Into #__components
                 (
                    `name`,
                    `admin_menu_link`,
                    `admin_menu_alt`,
                    `option`,
                    `admin_menu_img`,
                    `iscore`
                 )
                 Values
                 (
                    'ContentBuilder Views',
                    'option=com_contentbuilder&viewcontainer=true',
                    'ContentBuilder',
                    '',
                    'components/com_contentbuilder/views/logo_icon_cb.png',
                    1
                 )
           ");
            $db->query();
            $parent_id = $db->insertid();
        }
        $db->setQuery("Select id From #__components Where admin_menu_link = 'option=com_contentbuilder&controller=list&id=".intval($contentbuilder_form_id)."'");
        $menuitem = $db->loadResult();
        if(!$update) return;
        $db->setQuery("Select count(published) From #__contentbuilder_elements Where form_id = ".intval($contentbuilder_form_id));
        if($db->loadResult()){
            if(!$menuitem){
                $db->setQuery(
                    "Insert Into #__components
                     (
                        `name`,
                        `admin_menu_link`,
                        `admin_menu_alt`,
                        `option`,
                        `admin_menu_img`,
                        `iscore`,
                        `parent`
                     )
                     Values
                     (
                        ".$db->Quote($name).",
                        'option=com_contentbuilder&controller=list&id=".intval($contentbuilder_form_id)."',
                        ".$db->Quote($name).",
                        'com_contentbuilder',
                        'components/com_contentbuilder/views/logo_icon_cb.png',
                        1,
                        '$parent_id'
                     )
               ");
            }else{
                $db->setQuery(
                    "Update #__components
                     Set
                     `name` = ".$db->Quote($name).",
                     `admin_menu_alt` = ".$db->Quote($name).",
                     `parent` = $parent_id
                     Where id = $menuitem
               ");
            }
            $db->query();
        }
    }

    public static function createBackendMenuItem16($contentbuilder_form_id, $name, $update){
        if(trim($name)){
            $db = JFactory::getDBO();

            $db->setQuery("Select component_id From #__menu Where `link`='index.php?option=com_contentbuilder' And parent_id = 1");
            $result = $db->loadResult();

            $db->setQuery("Select id From #__menu Where `link`='index.php?option=com_contentbuilder&viewcontainer=true' And parent_id = 1");
            $old_id = $db->loadResult();
            $parent_id = $old_id;
            
            if(!$old_id){
                $db->setQuery(
                        "insert into #__menu (" .
                        "`title`, alias, menutype, parent_id, " .
                        "link," .
                        "ordering, level, component_id, client_id, img, lft,rgt" .
                        ") " .
                        "values (" .
                        "'ContentBuilder Views', 'ContentBuilder Views', 'main', 1, " .
                        "'index.php?option=com_contentbuilder&viewcontainer=true'," .
                        "'0', 1, " . intval($result) . ", 1, 'components/com_contentbuilder/views/logo_icon_cb.png',( Select mlftrgt From (Select max(mlft.rgt)+1 As mlftrgt From #__menu As mlft) As tbone ),( Select mrgtrgt From (Select max(mrgt.rgt)+2 As mrgtrgt From #__menu As mrgt) As filet )" .
                        ")"
                );
                $db->query();
                $parent_id = $db->insertid();
                
                $db->setQuery("Select max(mrgt.rgt)+1 From #__menu As mrgt");
                $rgt = $db->loadResult();

                $db->setQuery("Update `#__menu` Set rgt = ".$rgt." Where `title` = 'Menu_Item_Root' And `alias` = 'root'");
                $db->query();
            }
            
            $db->setQuery("Select id From #__menu Where link = 'index.php?option=com_contentbuilder&controller=list&id=".intval($contentbuilder_form_id)."'");
            $menuitem = $db->loadResult();

            if(!$update) return;
            if(!$result) die("ContentBuilder main menu item not found!");
            
            $db->setQuery("Select id From #__menu Where alias = " . $db->Quote($name) . " And link Like 'index.php?option=com_contentbuilder&controller=list&id=%' And link <> 'index.php?option=com_contentbuilder&controller=list&id=".intval($contentbuilder_form_id)."'");
            $name_exists = $db->loadResult();

            if($name_exists){
                $name .= '_';
            }
            
            if (!$menuitem) {
                
                $db->setQuery(
                        "insert into #__menu (" .
                        "`title`, alias, menutype, parent_id, " .
                        "link," .
                        "ordering, level, component_id, client_id, img" .
                        ",lft,rgt) " .
                        "values (" .
                        "" . $db->Quote($name) . ", " . $db->Quote($name) . ", 'main', '$parent_id', " .
                        "'index.php?option=com_contentbuilder&controller=list&id=" . intval($contentbuilder_form_id) . "'," .
                        "'0', 1, " . intval($result) . ", 1, 'components/com_contentbuilder/views/logo_icon_cb.png'" .
                        ",( Select mlftrgt From (Select max(mlft.rgt)+1 As mlftrgt From #__menu As mlft) As tbone), ( Select mrgtrgt From (Select max(mrgt.rgt)+2 As mrgtrgt From #__menu As mrgt) As filet))"
                );
                $db->query();
                
                $db->setQuery("Select max(mrgt.rgt)+1 From #__menu As mrgt");
                $rgt = $db->loadResult();

                $db->setQuery("Update `#__menu` Set rgt = ".$rgt." Where `title` = 'Menu_Item_Root' And `alias` = 'root'");
                $db->query();
                
            } else {
               
                $db->setQuery(
                        "Update #__menu Set `title` = " . $db->Quote($name) . ", alias = " . $db->Quote($name) . ", `parent_id` = '$parent_id' Where id = $menuitem"
                );
                $db->query();
                
            }
        }
    }
    
    public static function createBackendMenuItem3($contentbuilder_form_id, $name, $update){
        if(trim($name)){
            $db = JFactory::getDBO();

            $db->setQuery("Select component_id From #__menu Where `link`='index.php?option=com_contentbuilder' And parent_id = 1");
            $result = $db->loadResult();

            $db->setQuery("Select id From #__menu Where `link`='index.php?option=com_contentbuilder&viewcontainer=true' And parent_id = 1");
            $old_id = $db->loadResult();
            $parent_id = $old_id;
            
            if(!$old_id){
                $db->setQuery(
                        "insert into #__menu (" .
                        "`title`, alias, menutype, parent_id, " .
                        "link," .
                        "level, component_id, client_id, img, lft,rgt" .
                        ") " .
                        "values (" .
                        "'ContentBuilder Views', 'ContentBuilder Views', 'main', 1, " .
                        "'index.php?option=com_contentbuilder&viewcontainer=true'," .
                        "1, " . intval($result) . ", 1, 'components/com_contentbuilder/views/logo_icon_cb.png',( Select mlftrgt From (Select max(mlft.rgt)+1 As mlftrgt From #__menu As mlft) As tbone ),( Select mrgtrgt From (Select max(mrgt.rgt)+2 As mrgtrgt From #__menu As mrgt) As filet )" .
                        ")"
                );
                $db->query();
                $parent_id = $db->insertid();
                
                $db->setQuery("Select max(mrgt.rgt)+1 From #__menu As mrgt");
                $rgt = $db->loadResult();

                $db->setQuery("Update `#__menu` Set rgt = ".$rgt." Where `title` = 'Menu_Item_Root' And `alias` = 'root'");
                $db->query();
            }
            
            $db->setQuery("Select id From #__menu Where link = 'index.php?option=com_contentbuilder&controller=list&id=".intval($contentbuilder_form_id)."'");
            $menuitem = $db->loadResult();

            if(!$update) return;
            if(!$result) die("ContentBuilder main menu item not found!");
            
            $db->setQuery("Select id From #__menu Where alias = " . $db->Quote($name) . " And link Like 'index.php?option=com_contentbuilder&controller=list&id=%' And link <> 'index.php?option=com_contentbuilder&controller=list&id=".intval($contentbuilder_form_id)."'");
            $name_exists = $db->loadResult();

            if($name_exists){
                $name .= '_';
            }
            
            if (!$menuitem) {
                
                $db->setQuery(
                        "insert into #__menu (" .
                        "`title`, alias, menutype, parent_id, " .
                        "link," .
                        "level, component_id, client_id, img" .
                        ",lft,rgt) " .
                        "values (" .
                        "" . $db->Quote($name) . ", " . $db->Quote($name) . ", 'main', '$parent_id', " .
                        "'index.php?option=com_contentbuilder&controller=list&id=" . intval($contentbuilder_form_id) . "'," .
                        "1, " . intval($result) . ", 1, 'components/com_contentbuilder/views/logo_icon_cb.png'" .
                        ",( Select mlftrgt From (Select max(mlft.rgt)+1 As mlftrgt From #__menu As mlft) As tbone), ( Select mrgtrgt From (Select max(mrgt.rgt)+2 As mrgtrgt From #__menu As mrgt) As filet))"
                );
                $db->query();
                
                $db->setQuery("Select max(mrgt.rgt)+1 From #__menu As mrgt");
                $rgt = $db->loadResult();

                $db->setQuery("Update `#__menu` Set rgt = ".$rgt." Where `title` = 'Menu_Item_Root' And `alias` = 'root'");
                $db->query();
                
            } else {
               
                $db->setQuery(
                        "Update #__menu Set `title` = " . $db->Quote($name) . ", alias = " . $db->Quote($name) . ", `parent_id` = '$parent_id' Where id = $menuitem"
                );
                $db->query();
                
            }
        }
    }

    public static function createDetailsSample($contentbuilder_form_id, $form, $plugin){
        if(!$contentbuilder_form_id || !is_object($form)){
            return;
        }
        
        JPluginHelper::importPlugin('contentbuilder_themes', $plugin);
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('getContentTemplateSample', array($contentbuilder_form_id, $form));
    
        return implode('', $results);
    }
    
    public static function createEmailSample($contentbuilder_form_id, $form, $html = false){
        if(!$contentbuilder_form_id || !is_object($form)){
            return;
        }
        $db = JFactory::getDBO();
        $out = '';
        if($html){
            $out = '<table border="0" width="100%"><tbody>'."\n";
        }
        $names = $form->getElementNames();
        foreach($names As $reference_id => $name){
            $db->setQuery("Select id, `type` From #__contentbuilder_elements Where published = 1 And form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
            $result = $db->loadAssoc();
            if( is_array($result) ){
                if($result['type'] != 'hidden'){
                    $out .= '{hide-if-empty '.$name.'}';
                    if($html){
                        $out .= '<tr><td width="20%" valign="top"><label>{'.$name.':label}</label></td><td>{'.$name.':value}</td></tr>'."\r\n";
                    }else{
                        $out .= '{'.$name.':label}: {'.$name.':value}';
                    }
                    $out .= "\r\n".'{/hide}';
                }
            }
        }
        if($html){
            $out .= '</tbody></table>'."\n";
        }
        return $out;
    }
    
    public static function createEditableSample($contentbuilder_form_id, $form, $plugin){
        if(!$contentbuilder_form_id || !is_object($form)){
            return;
        }
        
        JPluginHelper::importPlugin('contentbuilder_themes', $plugin);
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger('getEditableTemplateSample', array($contentbuilder_form_id, $form));
    
        return implode('', $results);
    }

    public static function synchElements($contentbuilder_form_id, $form){
        if(!$contentbuilder_form_id || !is_object($form)){
            return;
        }
        
        $db = JFactory::getDBO();
        $ids = array();
        $elements = $form->getElementLabels();
        
        foreach($elements As $reference_id => $title){
            // TODO: auto-type-recognition
            $options = new stdClass();
            $options->length = '';
            $options->maxlength = '';
            $options->password = 0;
            $options->readonly = 0;
            $options->seperator = ',';
            $ids[] = $db->Quote($reference_id);
            $db->setQuery("Select id, `type`, `options` From #__contentbuilder_elements Where form_id = ".intval($contentbuilder_form_id)." And reference_id = " . $db->Quote($reference_id));
            if(!is_array($db->loadAssoc())){
                $db->setQuery("Select Max(ordering) + 1 From #__contentbuilder_elements Where form_id = ".intval($contentbuilder_form_id));
                $ordering = $db->loadResult();
                
                $db->setQuery("Insert Into #__contentbuilder_elements (`label`,`form_id`,`reference_id`,`type`,`options`, `ordering`) Values (".$db->Quote($title).",".$db->Quote($contentbuilder_form_id).",".$db->Quote($reference_id).",'text','".cb_b64enc( serialize( $options ) )."', ".($ordering ? $ordering : 0).")");
                $db->query();
            }
        }
        // delete missing elements
        if(count($ids)){
            $db->setQuery("Delete From #__contentbuilder_elements Where form_id = ".intval($contentbuilder_form_id)." And reference_id Not In (".implode(',', $ids).")");
            $db->query();
        }
    }

    public static function getTypes(){
        
        $types = array();
        
        // built-in types
        if(JFile::exists(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_breezingforms' . DS . 'breezingforms.xml')){
            $types[] = 'com_breezingforms';
        }
        
        $types[] = 'com_contentbuilder';
        
        // Custom types
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');

        if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder')){
            JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder');
        }
        
        $def = '';
        
        if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'index.html', $def);
        if(!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types')){
            JFolder::create(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types');
        }
        
        if(!JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . 'index.html')) JFile::write(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . 'index.html', $def);
        
        $sourcePath = JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS;
        if (JFolder::exists($sourcePath) && @is_readable($sourcePath) && @is_dir($sourcePath) && $handle = @opendir($sourcePath)) {
            while (false !== ($file = @readdir($handle))) {
                if ($file != "." && $file != ".." && strtolower($file) != 'index.html' && strtolower($file) != '.cvs' && strtolower($file) != '.svn') {
                    $exploded = explode('.', $file);
                    unset($exploded[count($exploded)-1]);
                    $types[] = implode('.', $exploded);
                }
            }
            @closedir($handle);
        }
        return $types;
    }

    public static function getForms($type){
        if(JFile::exists(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'types' . DS . $type . '.php')){
            require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'types' . DS . $type . '.php');
            $class = 'contentbuilder_'.$type;
            if(class_exists($class)){
                return call_user_func(array($class, "getFormsList"));
            }
        }else if(JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . $type . '.php')){
            require_once(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . $type . '.php');
            $class = 'contentbuilder_'.$type;
            if(class_exists($class)){
                return call_user_func(array($class, "getFormsList"));
            }
        }

        return array();
    }

    public static function getForm($type, $reference_id){

        static $forms;
        
        if(JFile::exists(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'types' . DS . $type . '.php')){
            require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'types' . DS . $type . '.php');
            if(isset($forms[$type][$reference_id])){
                return $forms[$type][$reference_id];
            }
            $class = 'contentbuilder_'.$type;
            if(class_exists($class)){
                $form = new $class($reference_id);
                $forms = array();
                $forms[$type][$reference_id] = $form;
                return $form;
            }
        }else if(JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . $type . '.php')){
            require_once(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . $type . '.php');
            if(isset($forms[$type][$reference_id])){
                return $forms[$type][$reference_id];
            }
            $class = 'contentbuilder_'.$type;
            if(class_exists($class)){
                $form = new $class($reference_id);
                $forms = array();
                $forms[$type][$reference_id] = $form;
                return $form;
            }
        }
        return null;
    }

    public static function getListSearchableElements($contentbuilder_form_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select reference_id From #__contentbuilder_elements Where search_include = 1 And published = 1 And form_id = " . intval($contentbuilder_form_id));
        
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            return $db->loadColumn();
        }else{
            return $db->loadResultArray();
        }
    }

    public static function getListLinkableElements($contentbuilder_form_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select reference_id From #__contentbuilder_elements Where linkable = 1 And published = 1 And form_id = " . intval($contentbuilder_form_id));
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            return $db->loadColumn();
        }else{
            return $db->loadResultArray();
        }
    }
    
    public static function getListEditableElements($contentbuilder_form_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select reference_id From #__contentbuilder_elements Where editable = 1 And published = 1 And form_id = " . intval($contentbuilder_form_id));
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            return $db->loadColumn();
        }else{
            return $db->loadResultArray();
        }
    }
    
    public static function getListNonEditableElements($contentbuilder_form_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select reference_id From #__contentbuilder_elements Where ( editable = 0 Or published = 0 ) And form_id = " . intval($contentbuilder_form_id));
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            return $db->loadColumn();
        }else{
            return $db->loadResultArray();
        }
    }

    public static function getTemplate($contentbuilder_form_id, $record_id, array $record, array $elements_allowed, $quiet_skip = false){
        
        static $_template;
        
        $hash = md5($contentbuilder_form_id . $record_id . implode(',',$elements_allowed));
        
        if(is_array($_template) && isset($_template[$hash])){
            return $_template[$hash];
        }
        
        $db = JFactory::getDBO();
        $db->setQuery("Select `type`,reference_id,details_template, details_prepare, edit_by_type, act_as_registration, registration_name_field, registration_username_field, registration_email_field, registration_email_repeat_field, registration_password_field, registration_password_repeat_field From #__contentbuilder_forms Where id = " . intval($contentbuilder_form_id));
        $result = $db->loadAssoc();
        if(is_array($result) && $result['details_template']){
            
            $user = null;
            if( $result['act_as_registration'] ){
                $form = contentbuilder::getForm($result['type'], $result['reference_id']);
                $meta = $form->getRecordMetadata($record_id);
                $db->setQuery("Select * From #__users Where id = " . $meta->created_id);
                $user = $db->loadObject();
            }
            
            $_template = array();
            $labels = array();
            $allow_html = array();
            
            $db->setQuery("Select `label`,`reference_id`,`options` From #__contentbuilder_elements Where form_id = " . intval($contentbuilder_form_id));
            $labels_ = $db->loadAssocList();

            foreach($labels_ As $label_){
                $labels[$label_['reference_id']] = $label_['label'];
                $opts = unserialize(cb_b64dec($label_['options']));
                if($opts && ( ( isset($opts->allow_html) && $opts->allow_html ) || ( isset($opts->allow_raw) && $opts->allow_raw ) ) ){
                    $allow_html[$label_['reference_id']] = $opts;
                }
            }
            
            $template = $result['details_template'];
            $items = array();
                    
            $hasLabels = count($labels);
            
            foreach($record As $item){
                if(in_array($item->recElementId, $elements_allowed)){
                    $items[$item->recName] = array();
                    $items[$item->recName]['label'] = $hasLabels ? $labels[$item->recElementId] : $item->recTitle;
                    if( $result['act_as_registration'] && $user !== null ){
                        if($result['registration_name_field'] == $item->recElementId){
                             $item->recValue = $user->name;
                        }
                        else 
                        if($result['registration_username_field'] == $item->recElementId){
                            $item->recValue = $user->username;
                        }
                        else 
                        if($result['registration_email_field'] == $item->recElementId){
                            $item->recValue = $user->email;
                        }
                        else 
                        if($result['registration_email_repeat_field'] == $item->recElementId){
                            $item->recValue = '';
                        }
                        else 
                        if($result['registration_password_field'] == $item->recElementId){
                            $item->recValue = '';
                        }
                        else 
                        if($result['registration_password_repeat_field'] == $item->recElementId){
                            $item->recValue = '';
                        }
                    }
                    
                    $items[$item->recName]['value'] = ($item->recValue != '' ? $item->recValue : JText::_('COM_CONTENTBUILDER_NOT_AVAILABLE'));
                    $items[$item->recName]['id']    = $item->recElementId;
                    $regex = "/([\{]hide-if-empty ".$item->recName."[\}])(.*)([\{][\/]hide[\}])/isU";
            
                    if($item->recValue == ''){
                        $template = preg_replace($regex,"",$template);
                    }else{
                        $template = preg_replace($regex,'$2',$template);
                    }
                }
            }
            $item = null;
            $raw_items = $items;
            foreach($items As $key => $item){
                if(!isset($item['label']) || !isset($item['id'])) continue;
                $items[$key]['label'] = htmlentities($item['label'],ENT_QUOTES,'UTF-8');
                $items[$key]['value'] = isset($allow_html[$item['id']]) ? self::cleanString( $item['value'] ) : nl2br( self::allhtmlentities(cbinternal($item['value']) ) );
            }
            @eval($result['details_prepare']);
            foreach($items As $key => $item){
                if(!isset($item['label']) || !isset($item['id'])) continue;
                $template = str_replace('{'.$key.':label}',$item['label'], $template);
                $template = str_replace('{'.$key.':value}',$item['value'], $template);
                $template = str_replace('{webpath '.$key.'}', str_replace(array('{CBSite}','{cbsite}',JPATH_SITE),JURI::getInstance()->getScheme().'://'.JURI::getInstance()->getHost().(JURI::getInstance()->getPort() == 80 ? '' : ':'.JURI::getInstance()->getPort()).JURI::root(true),$raw_items[$key]['value']), $template);
            }
            
            $_template[$hash] = $template;
            return $template;
        } else {
            if($quiet_skip) return '';
            JError::raiseError(404, JText::_('COM_CONTENTBUILDER_TEMPLATE_NOT_FOUND'));
        }
        return '';
    }
    
    public static function allhtmlentities($string) {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        return self::cleanString($string);
    } 
    
    public static function cleanString($string){
        return str_replace(array('[',']','{','}','(',')','|'), array('&#91;','&#93;','&#123;','&#125;','&#40;','&#41;','&#124;'), $string);
    }
    
    public static function getFormElementsPlugins(){
        jimport('joomla.version');
        
        $db = JFactory::getDBO();
        
        $version = new JVersion();
        
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            $db->setQuery("Select `element` From #__extensions Where `folder` = 'contentbuilder_form_elements' And `enabled` = 1");
            $res = $db->loadColumn();
            return $res;
        } else
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            
            $db->setQuery("Select `element` From #__extensions Where `folder` = 'contentbuilder_form_elements' And `enabled` = 1");
            $res = $db->loadResultArray();
            return $res;
            
        } else {
            
            $db->setQuery("Select `element` From #__plugins Where `folder` = 'contentbuilder_form_elements' And `published` = 1");
            $res = $db->loadResultArray();
            return $res;
        }
        
        return array();
    }
    
    public static function getEmailTemplate($contentbuilder_form_id, $record_id, array $record, array $elements_allowed, $isAdmin){
        
        static $_template;
        
        $hash = md5(($isAdmin ? 'admin' : 'user').$contentbuilder_form_id . $record_id . implode(',',$elements_allowed));
        
        if(is_array($_template) && isset($_template[$hash])){
            return $_template[$hash];
        }
        
        $db = JFactory::getDBO();
        $db->setQuery("Select `name`,`type`,reference_id,email_template, email_admin_template, email_html, email_admin_html, act_as_registration, registration_name_field, registration_username_field, registration_email_field  From #__contentbuilder_forms Where id = " . intval($contentbuilder_form_id));
        $result = $db->loadAssoc();
        if(is_array($result)){
            
            $user = null;
            if( $result['act_as_registration'] ){
                $form = contentbuilder::getForm($result['type'], $result['reference_id']);
                $meta = $form->getRecordMetadata($record_id);
                $db->setQuery("Select * From #__users Where id = " . $meta->created_id);
                $user = $db->loadObject();
            }
            
            $_template = array();
            $labels = array();
            $allow_html = array();
            
            $db->setQuery("Select `label`,`reference_id`,`options` From #__contentbuilder_elements Where form_id = " . intval($contentbuilder_form_id));
            $labels_ = $db->loadAssocList();

            foreach($labels_ As $label_){
                $labels[$label_['reference_id']] = $label_['label'];
                $opts = unserialize(cb_b64dec($label_['options']));
                if($opts && isset($opts->allow_html) && $opts->allow_html){
                    $allow_html[$label_['reference_id']] = $opts;
                }
            }
            
            $template = $isAdmin ? $result['email_admin_template'] : $result['email_template'];
            $html = $isAdmin ? $result['email_admin_html'] : $result['email_html'];
            
            $items = array();
                    
            $hasLabels = count($labels);
            
            foreach($record As $item){
                if(in_array($item->recElementId, $elements_allowed)){
                    $items[$item->recName] = array();
                    $items[$item->recName]['label'] = $hasLabels ? $labels[$item->recElementId] : $item->recTitle;
                    if( $result['act_as_registration'] && $user !== null ){
                        if($result['registration_name_field'] == $item->recElementId){
                             $item->recValue = $user->name;
                        }
                        else 
                        if($result['registration_username_field'] == $item->recElementId){
                            $item->recValue = $user->username;
                        }
                        else 
                        if($result['registration_email_field'] == $item->recElementId){
                            $item->recValue = $user->email;
                        }
                    }
                    $items[$item->recName]['value'] = ($item->recValue != '' ? $item->recValue : JText::_('COM_CONTENTBUILDER_NOT_AVAILABLE'));
                    $items[$item->recName]['id']    = $item->recElementId;
                    $regex = "/([\{]hide-if-empty ".$item->recName."[\}])(.*)([\{][\/]hide[\}])/isU";
            
                    if($item->recValue == ''){
                        $template = preg_replace($regex,"",$template);
                    }else{
                        $template = preg_replace($regex,'$2',$template);
                    }
                }
            }
            $item = null;
            
            $template = str_replace(array('{RECORD_ID}','{record_id}'), $record_id, $template);
            $template = str_replace(array('{USER_ID}','{user_id}'), JFactory::getUser()->get('id'), $template);
            $template = str_replace(array('{USERNAME}','{username}'), JFactory::getUser()->get('username'), $template);
            $template = str_replace(array('{USER_FULL_NAME}','{user_full_name}'), JFactory::getUser()->get('name'), $template);
            $template = str_replace(array('{VIEW_NAME}','{view_name}'), $result['name'], $template);
            $template = str_replace(array('{VIEW_ID}','{view_id}'), $contentbuilder_form_id, $template);
            $template = str_replace(array('{IP}','{ip}'), $_SERVER['REMOTE_ADDR'], $template);
            
            foreach($items As $key => $item){
                $template = str_replace('{'.$key.':label}',$html ? htmlentities($item['label'],ENT_QUOTES,'UTF-8') : $item['label'], $template);
                $template = str_replace('{'.$key.':value}',isset($allow_html[$item['id']]) && $html ? (contentbuilder_is_internal_path($item['value']) ? basename($item['value']) : $item['value']) : nl2br(strip_tags((contentbuilder_is_internal_path($item['value']) ? basename($item['value']) : $item['value']))), $template);
                $template = str_replace('{webpath '.$key.'}', str_replace(array('{CBSite}','{cbsite}',JPATH_SITE),JURI::getInstance()->getScheme().'://'.JURI::getInstance()->getHost().(JURI::getInstance()->getPort() == 80 ? '' : ':'.JURI::getInstance()->getPort()).JURI::root(true),$item['value']), $template);
            }
            
            $_template[$hash] = $template;
            return $template;
        } else {
            '';
        }
        return '';
    }
    
    public static function getEditableTemplate($contentbuilder_form_id, $record_id, array $record, array $elements_allowed, $execPrepare = true){
        
        jimport('joomla.version');
        $version = new JVersion();
        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
            JHtml::_('behavior.framework');
        }
        
        $failed_values = JFactory::getSession()->get('cb_failed_values', null, 'com_contentbuilder.'.$contentbuilder_form_id);
        
        if($failed_values !== null){
            JFactory::getSession()->clear('cb_failed_values', 'com_contentbuilder.'.$contentbuilder_form_id);
        }
        
        $db = JFactory::getDBO();
        $db->setQuery("Select `type`, reference_id, editable_template, editable_prepare, edit_by_type, act_as_registration, registration_name_field, registration_username_field, registration_email_field, registration_email_repeat_field, registration_password_field, registration_password_repeat_field From #__contentbuilder_forms Where id = " . intval($contentbuilder_form_id));
        $result = $db->loadAssoc();
        if(is_array($result) && $result['editable_template']){
            
            $user = null;
            if( $result['act_as_registration'] ){
                if($record_id){
                    $form = contentbuilder::getForm($result['type'], $result['reference_id']);
                    $meta = $form->getRecordMetadata($record_id);
                    $db->setQuery("Select * From #__users Where id = " . $meta->created_id);
                    $user = $db->loadObject();
                }  else if( JFactory::getUser()->get('id',0) ) {
                    $db->setQuery("Select * From #__users Where id = " . JFactory::getUser()->get('id',0));
                    $user = $db->loadObject();
                }
            }
            
            $labels = array();
            $validations = array();
            
            if(!$result['edit_by_type']){
                $db->setQuery("Select `label`,`reference_id`,`validations` From #__contentbuilder_elements Where form_id = " . intval($contentbuilder_form_id));
                $labels_ = $db->loadAssocList();
                foreach($labels_ As $label_){
                    $labels[$label_['reference_id']] = $label_['label'];
                    $validations[$label_['reference_id']] = $label_['validations'];
                }
            }
            
            $hasLabels = count($labels);
            
            $form_type = $result['type'];
            $form_reference_id = $result['reference_id'];
            $form = self::getForm($form_type, $form_reference_id);
            $template = $result['editable_template'];
            $items = array();
            foreach($record As $item){
                if(in_array($item->recElementId, $elements_allowed)){
                    $items[$item->recName] = array();
                    $items[$item->recName]['id'] = $item->recElementId;
                    $items[$item->recName]['label'] = $hasLabels ? $labels[$item->recElementId] : $item->recTitle;
                    if( $result['act_as_registration'] && $user !== null ){
                        if($result['registration_name_field'] == $item->recElementId){
                             $item->recValue = $user->name;
                        }
                        else 
                        if($result['registration_username_field'] == $item->recElementId){
                            $item->recValue = $user->username;
                        }
                        else 
                        if($result['registration_email_field'] == $item->recElementId){
                            $item->recValue = $user->email;
                        }
                        else 
                        if($result['registration_email_repeat_field'] == $item->recElementId){
                            $item->recValue = $user->email;
                        }
                    }
                    $items[$item->recName]['value'] = ($item->recValue ? $item->recValue : '');
                }
            }
            
            // in case if there is no record given, provide the element data but an empty value
            $hasRecords = true;
            if(!count($record)){
                $hasRecords = false;
                $names = $form->getElementNames();
                if(!count($labels)){
                    $labels = $form->getElementLabels();
                }
                foreach($names As $elementId => $name){
                    if(!isset($items[$name])){
                        $items[$name] = array();
                    }
                    $items[$name]['id'] = $elementId;
                    $items[$name]['label'] = $labels[$elementId];
                    $items[$name]['value'] = '';
                }
            }
            $item = null;
            if($execPrepare){
                eval($result['editable_prepare']);
            }
            
            $the_init_scripts    = "\n".'<script type="text/javascript">'."\n".'<!--'."\n";
            
            foreach($items As $key => $item){
                $db->setQuery("Select * From #__contentbuilder_elements Where published = 1 And editable = 1 And reference_id = ".$db->Quote($item['id'])." And form_id = " . intval($contentbuilder_form_id) . " Order By ordering");
                $element = $db->loadAssoc();
                
                $autocomplete = '';
                
                if( $result['act_as_registration'] ){
                    
                    if($result['registration_name_field'] == $element['reference_id']){
                         $element['default_value'] = $user !== null ? $user->name : '';
                         $autocomplete = 'autocomplete="off" ';
                    }
                    else 
                    if($result['registration_username_field'] == $element['reference_id']){
                        $element['default_value'] = $user !== null ? $user->username : '';
                        $autocomplete = 'autocomplete="off" ';
                    }
                    else 
                    if($result['registration_email_field'] == $element['reference_id']){
                        $element['default_value'] = $user !== null ? $user->email : '';
                        $autocomplete = 'autocomplete="off" ';
                    }
                    else 
                    if($result['registration_email_repeat_field'] == $element['reference_id']){
                        $element['default_value'] = $user !== null ? $user->email : '';
                        $autocomplete = 'autocomplete="off" ';
                    }
                    else 
                    if($result['registration_password_field'] == $element['reference_id']){
                        $element['force_password'] = true;
                        $autocomplete = 'autocomplete="off" ';
                    }
                    else 
                    if($result['registration_password_repeat_field'] == $element['reference_id']){
                        $element['force_password'] = true;
                        $autocomplete = 'autocomplete="off" ';
                    }
                }
                
                if(!$element['default_value'] && !$hasRecords){
                    $element['default_value'] = $item['value'];
                }
                
                $asterisk = '';
                
                if(is_array($element)){
                    
                    if($element['type'] == 'captcha' || trim($element['validations']) != '' || trim($element['custom_validation_script']) != ''){
                        $asterisk = ' <span class="cbRequired" style="color:red;">*</span>';
                    }
                    
                    $options = unserialize(cb_b64dec($element['options']));
                    
                    $the_item = '';
                    
                    switch($element['type']){
                       case in_array($element['type'],self::getFormElementsPlugins()):
                           
                           $removables = array();
                           
                           $plgs = CBPluginHelper::importPlugin('contentbuilder_form_elements', $element['type']);
                           $removables = array_merge($removables, $plgs);
                           
                           $dispatcher = JDispatcher::getInstance();
                           $results = $dispatcher->trigger('onRenderElement', array($item, $element, $options, $failed_values, $result, $hasRecords));

                           if(count($results)){
                                $results = $results[0];
                           }
                           
                           foreach($removables As $removable){
                               $dispatcher->detach($removable);
                           }
                           
                           $the_item = $results;
                           break;
                       case '':
                       case 'text':
                           if(!isset($options->length)){
                               $options->length = '';
                           }
                           if(!isset($options->maxlength)){
                               $options->maxlength = '';
                           }
                           if(!isset($options->password)){
                               $options->password = '';
                           }
                           if(!isset($options->readonly)){
                               $options->readonly = '';
                           }
                           
                           $the_item = '<div class="cbFormField cbTextField"><input '.$autocomplete.''.($options->readonly ? 'readonly="readonly" ' : '').'style="'.($options->length ? 'width:'.$options->length.';' : '').'" '.($options->maxlength ? 'maxlength="'.intval($options->maxlength).'" ' : '').'type="'.(isset($element['force_password']) || $options->password ? 'password' : 'text').'" id="cb_'.$item['id'].'" name="cb_'.$item['id'].'" value="'.htmlentities( $failed_values !== null && isset($failed_values[$element['reference_id']]) ? $failed_values[$element['reference_id']] : ( $hasRecords ? $item['value'] : $element['default_value'] ),ENT_QUOTES,'UTF-8').'"/></div>';
                           break;
                       case 'textarea':
                           
                           if(!isset($options->width)){
                               $options->width = '';
                           }
                           if(!isset($options->height)){
                               $options->height = '';
                           }
                           if(!isset($options->maxlength)){
                               $options->maxlength = '';
                           }
                           if(!isset($options->readonly)){
                               $options->readonly = '';
                           }
                           if(!isset($options->allow_html)){
                               $options->allow_html = false;
                           }
                           if(!isset($options->allow_raw)){
                               $options->allow_raw = false;
                           }
                           if($options->allow_html || $options->allow_raw){
                               JImport( 'joomla.html.editor' );
                               $editor = JFactory::getEditor();
                               $the_item = '<div class="cbFormField cbTextArea">'.$editor->display('cb_'.$item['id'],htmlentities($failed_values !== null && isset($failed_values[$element['reference_id']]) ? $failed_values[$element['reference_id']] : ( $hasRecords ? $item['value'] : $element['default_value'] ),ENT_QUOTES,'UTF-8'), $options->width ? $options->width : '100%', $options->height ? $options->height : '550', '75', '20').'</div>';
                           }else{
                               $the_item = '<div class="cbFormField cbTextArea"><textarea '.($options->readonly ? 'readonly="readonly" ' : '').'style="'.($options->width || $options->height ? ($options->width ? 'width:'.$options->width.';' : '').($options->height ? 'height:'.$options->height.';' : '') : '').'" id="cb_'.$item['id'].'" name="cb_'.$item['id'].'">'.htmlentities($failed_values !== null && isset($failed_values[$element['reference_id']]) ? $failed_values[$element['reference_id']] : ( $hasRecords ? $item['value'] : $element['default_value'] ),ENT_QUOTES,'UTF-8').'</textarea></div>';
                           }
                           break;
                       case 'checkboxgroup':
                       case 'radiogroup':
                           //if(!isset($options->seperator)){
                           //    $options->seperator = ',';
                           //}
                           
                           $options->seperator = ',';
                           
                           if(!isset($options->horizontal)){
                               $options->horizontal = false;
                           }
                           if(!isset($options->horizontal_length)){
                               $options->horizontal_length = '';
                           }
                           if($form->isGroup($item['id'])){
                               $groupdef = $form->getGroupDefinition($item['id']);
                               $i = 0;
                               $sep = $options->seperator;
                               $group = explode($sep, $failed_values !== null && isset($failed_values[$element['reference_id']]) && is_array($failed_values[$element['reference_id']]) ? implode($sep, $failed_values[$element['reference_id']]) : ( $hasRecords ? $item['value'] : $element['default_value'] ) );
                               $groupSize = count($groupdef);
                               $groupSize = !$groupSize ? 1 : $groupSize;
                               $the_item = '<input name="cb_'.$item['id'].'[]" type="hidden" value="cbGroupMark"/>';
                               foreach($groupdef As $value => $label){
                                  $checked = '';
                                  $for = '';
                                  if($i != 0){
                                    $for = '_'.$i;
                                  }
                                  foreach($group As $selected_value){
                                      if(trim($value) == trim($selected_value)){
                                          $checked = ' checked="checked"';
                                          break;
                                      }
                                  }
                                  $the_item .= '<div style="'.($options->horizontal ? 'float: left;'.($options->horizontal_length ? 'width: '.$options->horizontal_length.';' : '').'display: inline; margin-right: 2px;' : '').'" class="cbFormField cbGroupField"><input id="cb_'.$item['id'].$for.'" name="cb_'.$item['id'].'[]" type="'.($element['type'] == 'checkboxgroup' ? 'checkbox' : 'radio').'" value="'.htmlentities(trim($value),ENT_QUOTES,'UTF-8').'"'.$checked.'/> <label for="cb_'.$item['id'].$for.'">'.htmlentities(trim($label), ENT_QUOTES, 'UTF-8').'</label> </div>';
                                  $i++;  
                               }
                               if($options->horizontal){
                                   $the_item .= '<div style="clear:both;"></div>';
                               }
                               
                           } else {
                               $the_item .= '<span style="color:red">ELEMENT IS NOT A GROUP</span>';
                           }
                           break;
                      case 'select':
                           //if(!isset($options->seperator)){
                           //    $options->seperator = ',';
                           //}
                           $options->seperator = ',';
                          
                           if(!isset($options->multiple)){
                               $options->multiple = 0;
                           }
                           if(!isset($options->length)){
                               $options->length = '';
                           }
                           
                           if($form->isGroup($item['id'])){
                               $groupdef = $form->getGroupDefinition($item['id']);
                               $i = 0;
                               $sep   = $options->seperator;
                               $multi = $options->multiple;
                               $group = explode($sep, $failed_values !== null && isset($failed_values[$element['reference_id']]) && is_array($failed_values[$element['reference_id']]) ? implode($sep, $failed_values[$element['reference_id']]) : ( $hasRecords ? $item['value'] : $element['default_value'] ) );
                               $the_item = '<input name="cb_'.$item['id'].'[]" type="hidden" value="cbGroupMark"/>';
                               $the_item .= '<div class="cbFormField cbSelectField"><select class="chzn-done" id="cb_'.$item['id'].'" '.($options->length ? 'style="width:'.$options->length.';" ' : '').'name="cb_'.$item['id'].'[]"'.($multi ? ' multiple="multiple"' : '').'>';
                               foreach($groupdef As $value => $label){
                                  $checked = '';
                                  foreach($group As $selected_value){
                                      if(trim($value) == trim($selected_value)){
                                          $checked = ' selected="selected"';
                                          break;
                                      }
                                  }
                                  $the_item .= '<option value="'.htmlentities(trim($value),ENT_QUOTES,'UTF-8').'"'.$checked.'>'.htmlentities(trim($label), ENT_QUOTES, 'UTF-8').'</option>';
                                  $i++;  
                               }
                               $the_item .= '</select></div>';
                               
                           } else {
                               $the_item .= '<span style="color:red">ELEMENT IS NOT A GROUP</span>';
                           }
                           break;
                     case 'upload':
                         $deletable = false;
                         if(isset($validations[$item['id']]) && $validations[$item['id']] == ''){
                             $deletable = true;
                         }
                         $the_item = '<div class="cbFormField cbUploadField">';
                         $the_item .= '<input type="file" id="cb_'.$item['id'].'" name="cb_'.$item['id'].'"/>';
                         if(trim($item['value']) != ''){
                            $the_item .= '<div>'.($deletable ? '<label for="cb_delete_'.$item['id'].'">'.JText::_('COM_CONTENTBUILDER_DELETE').'</label> <input type="checkbox" id="cb_delete_'.$item['id'].'" name="cb_delete_'.$item['id'].'" value="1"/> ' : '').htmlentities(basename($item['value']),ENT_QUOTES,'UTF-8').'</div><div style="clear:both;"></div>';
                         }
                         $the_item .= '</div>';
                         break;
                     case 'captcha':
                         
                         $the_item = '<div class="cbFormField cbCaptchaField">';
                         
                         if(JFactory::getApplication()->isSite())
                         {
                            $captcha_url = JURI::root(true).'/components/com_contentbuilder/images/securimage/securimage_show.php';
                         }
                         else
                         {
                            $captcha_url = JURI::root(true).'/administrator/components/com_contentbuilder/assets/images/securimage_show.php';
                         }
                         
                         $the_item .= '<img width="250" height="80" id="cbCaptcha" alt="captcha" src="'.$captcha_url.'?rand='.rand(0, getrandmax()).'"/>';
                         $the_item .= '<div>';
                         $the_item .= '<input autocomplete="off" id="cb_'.$item['id'].'" name="cb_'.$item['id'].'" type="text" maxlength="12" />';
                         $the_item .= '<img style="cursor: pointer; padding-left: 7px;" onclick="document.getElementById(\'cbCaptcha\').src = \''.$captcha_url.'?\' + Math.random(); blur(); return false" border="0" width="15" height="18" alt="refresh" src="'.JURI::root(true).'/components/com_contentbuilder/images/securimage/images/refresh.png"/>';
                         $the_item .= '</div>';
                         $the_item .= '</div>';
                         break;
                     case 'calendar':
                         
                           JHTML::_( 'behavior.calendar' );
                         
                           if(!isset($options->length)){
                               $options->length = '';
                           }
                           if(!isset($options->maxlength)){
                               $options->maxlength = '';
                           }
                           if(!isset($options->readonly)){
                               $options->readonly = '';
                           }
                           if(!isset($options->format)){
                               $options->format = '%Y-%m-%d';
                           }
                           if(!isset($options->transfer_format)){
                               $options->transfer_format = 'YYYY-mm-dd';
                           }
                           
                           $calval = htmlentities( $failed_values !== null && isset($failed_values[$element['reference_id']]) ? $failed_values[$element['reference_id']] : ( $hasRecords ? $item['value'] : $element['default_value'] ),ENT_QUOTES,'UTF-8');
                           $calval = contentbuilder_convert_date($calval, $options->transfer_format, $options->format);
                           
                           $the_item  = '<div class="cbFormField cbCalendarField"><input '.($options->readonly ? 'readonly="readonly" ' : '').'style="'.($options->length ? 'width:'.$options->length.';' : '').'" '.($options->maxlength ? 'maxlength="'.intval($options->maxlength).'" ' : '').'type="text" id="cb_'.$item['id'].'" name="cb_'.$item['id'].'" value="'.$calval.'"/> <button class="button cbFormField cbCalendarButton" id="cb_'.$item['id'].'_calendarButton">'.JText::_('COM_CONTENTBUILDER_CALENDAR_BUTTON_TEXT').'</button></div>';
                           $the_item .= '<script type="text/javascript">
                                        <!--
                                        Calendar.setup({
                                                inputField     :    "cb_'.$item['id'].'",
                                                ifFormat       :    "'.$options->format.'",
                                                button         :    "cb_'.$item['id'].'_calendarButton",
                                                align          :    "Bl",
                                                singleClick    :    true
                                            });
                                        //-->
                                        </script>'."\n";
                           break;
                     case 'hidden':
                         $the_item = '<input type="hidden" id="cb_'.$item['id'].'" name="cb_'.$item['id'].'" value="'.htmlentities($failed_values !== null && isset($failed_values[$element['reference_id']])  ? $failed_values[$element['reference_id']] : ( $hasRecords ? $item['value'] : $element['default_value'] ),ENT_QUOTES,'UTF-8').'"/>';
                         break;
                    }
                    
                    if($element['custom_init_script']){
                        $the_init_scripts .= $element['custom_init_script']."\n";
                    }

                    if($the_item){
                        $tip = 'hasTip';
                        $tip_prefix = htmlentities($item['label'],ENT_QUOTES,'UTF-8').'::';
                        
                        /* new joomla 3 tooltip styles maybe at a later point
                        if (JFactory::getApplication()->isSite() && version_compare($version->getShortVersion(), '3.0', '>=')) {
                            $tip = 'hasTooltip';
                            $tip_prefix = '';
                        }*/
                        
                        $template = str_replace('{'.$key.':label}','<label '.($element['hint'] ? 'class="editlinktip '.$tip.'" title="'.$tip_prefix.$element['hint'].'" ' : '').'for="cb_'.$item['id'].'">'.$item['label'].$asterisk.($element['hint'] ? ' <img style="cursor: pointer;" src="'.JURI::root(true).'/components/com_contentbuilder/images/icon_info.png" border="0"/>' : '').'</label>', $template);
                        $template = str_replace('{'.$key.':item}',$the_item, $template);
                    }
                }
            }
            
            return $template.$the_init_scripts."\n".'//-->'.'</script>'."\n";
        } else {
            JError::raiseError(404, JText::_('COM_CONTENTBUILDER_TEMPLATE_NOT_FOUND'));
        }
        return '';
    }
    
    public static function createArticle($contentbuilder_form_id, $record_id, array $record, array $elements_allowed, $title_field = '', $metadata = null, $config = array(), $full = false, $limited_options = true, $menu_cat_id = null){
        
        $tz = new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
        
        if(isset($config['publish_up']) && $config['publish_up'] && $config['publish_up'] != '0000-00-00 00:00:00'){
            $config['publish_up'] = JFactory::getDate($config['publish_up'], $tz);
            $config['publish_up'] = $config['publish_up']->format('Y-m-d H:i:s');
        }else{
            $config['publish_up'] = '0000-00-00 00:00:00';
        }
        
        if(isset($config['created']) && $config['created'] && $config['created'] != '0000-00-00 00:00:00'){
            $config['created'] = JFactory::getDate($config['created'], $tz);
            $config['created'] = $config['created']->format('Y-m-d H:i:s');
        }else{
            $config['created'] = '0000-00-00 00:00:00';
        }
        
        if(isset($config['publish_down']) && $config['publish_down'] && $config['publish_down'] != '0000-00-00 00:00:00'){
            $config['publish_down'] = JFactory::getDate($config['publish_down'], $tz);
            $config['publish_down'] = $config['publish_down']->format('Y-m-d H:i:s');
        }else{
            $config['publish_down'] = '0000-00-00 00:00:00';
        }
        
        $is15 = true;
        $version = new JVersion();
        if (version_compare($version->getShortVersion(), '1.6', '>=')) {
           $is15 = false; 
        }
        
        $tpl = self::getTemplate($contentbuilder_form_id, $record_id, $record, $elements_allowed, true);
        if(!$tpl) return 0;
        $db = JFactory::getDBO();
        $db->setQuery("Select * From #__contentbuilder_forms Where id = " . intval($contentbuilder_form_id)." And published = 1");
        $form = $db->loadAssoc();
        if(!$form){
            return 0;
        }
        
        if($is15 && $menu_cat_id !== null){
            if(intval($menu_cat_id) > -2){
                $menu_cat_id = explode(':',$menu_cat_id);
                if(count($menu_cat_id) == 2){
                    $form['default_category'] = $menu_cat_id[1];
                    $form['default_section'] = $menu_cat_id[0];
                }
            }
        } else if($menu_cat_id !== null && intval($menu_cat_id) > -2){
            $form['default_category'] = $menu_cat_id;
        }
        
        $user = null;
        if( $form['act_as_registration'] ){
            if($record_id){
                $form_ = contentbuilder::getForm($form['type'], $form['reference_id']);
                $meta = $form_->getRecordMetadata($record_id);
                $db->setQuery("Select * From #__users Where id = " . $meta->created_id);
                $user = $db->loadObject();
            }  else if( JFactory::getUser()->get('id',0) ) {
                $db->setQuery("Select * From #__users Where id = " . JFactory::getUser()->get('id',0));
                $user = $db->loadObject();
            }
        }
        
        $label = '';
        foreach($record As $rec){
            if($rec->recElementId == $title_field){
                
                if( $form['act_as_registration'] && $user !== null ){
                    if($form['registration_name_field'] == $rec->recElementId){
                         $rec->recValue = $user->name;
                         
                    }
                    else 
                    if($form['registration_username_field'] == $rec->recElementId){
                        $rec->recValue = $user->username;
                    }
                    else 
                    if($form['registration_email_field'] == $rec->recElementId){
                        $rec->recValue = $user->email;
                    }
                    else 
                    if($form['registration_email_repeat_field'] == $rec->recElementId){
                        $rec->recValue = $user->email;
                    }
                }
                
                $label = cbinternal($rec->recValue);
                break;
            }
        }
        
        // trying first element if no title field given
        if(!$label && !count($record)){
            $label = 'Unnamed';
        }else if(!$label && count($record)){
            $label = cbinternal($record[0]->recValue);
        }
        
        // Clean text for xhtml transitional compliance
        $introtext = '';
        $fulltext  = '';
        $tpl = str_replace('<br>', '<br />', $tpl);

        // Search for the {readmore} tag and split the text up accordingly.
        $pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
        $tagPos = preg_match($pattern, $tpl);

        if ($tagPos == 0) {
            $introtext = $tpl;
        } else {
            list($introtext, $fulltext) = preg_split($pattern, $tpl, 2);
        }
        
        // retrieve the publish state from the list view
        $state = 1;
        $db->setQuery("Select published, is_future, publish_up, publish_down From #__contentbuilder_records Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
        $state = $db->loadAssoc();
        $publish_up_record = $state['publish_up'];
        $publish_down_record = $state['publish_down'];
        $state = $state['is_future'] ? 1 : $state['published'];
        
        // save/update articles
        $alias = '';
        
        $db->setQuery("Select articles.`article_id`, content.`alias` From #__contentbuilder_articles As articles, #__content As content Where content.id = articles.article_id And (content.state = 1 Or content.state = 0) And articles.form_id = " . intval($contentbuilder_form_id) . " And articles.record_id = " . $db->Quote($record_id));
        $article = $db->loadAssoc();
        if(is_array($article)){
            $alias = $article['alias'];
            $article = $article['article_id'];
        }
        
        // params
        $attribs = '';
        $meta = '';
        $rules = '';
        $metakey = '';
        $metadesc = '';
        
        $created_by = 0;
        $created_by_alias = '';
        $created_article = null;
        
        $_now = JFactory::getDate();
        
        $created_up = $publish_up_record;
        $created_down = $publish_down_record;
        
        if(is_array($article) && isset($article['article_id']) && intval($form['default_publish_up_days']) != 0){
            // this will cause errors on 64bit systems, as strtotime's behavior is different for null dates
            // $date = JFactory::getDate(strtotime('now +'.intval($form['default_publish_up_days']).' days'));
            // fix as of forum post http://crosstec.de/forums/37-contentbuilder-general-forum-english/62084-64-bit-strtotime-bug.html#62084
            // thanks to user Fremmedkar
            $date = JFactory::getDate(strtotime( ((($created_up !== null) && ($created_up != '0000-00-00 00:00:00')) ? $created_up : $_now).' +'.intval($form['default_publish_down_days']).' days'));
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $created_up = $date->toSql();
            } else {
                $created_up = $date->toMySQL();
            }
        }
        
        $publish_up = $created_up;
        
        if(is_array($article) && isset($article['article_id']) && intval($form['default_publish_down_days']) != 0){
            //$date = JFactory::getDate(strtotime( ($created_up !== null ? $created_up : $_now).' +'.intval($form['default_publish_down_days']).' days'));
            $date = JFactory::getDate(strtotime( ($created_up !== null && $created_up != '0000-00-00 00:00:00' ? $created_up : $_now).' +'.intval($form['default_publish_down_days']).' days'));
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $created_down = $date->toSql();
            }else{
                $created_down = $date->toMySQL();
            }
        }
        
        $publish_down = $created_down;
        
        $featured = $form['default_featured'];
        
        $ignore_lang_code = '*';
        if($form['default_lang_code_ignore']){
            if(!$is15){
                $db->setQuery("Select lang_code From #__languages Where published = 1 And sef = " . $db->Quote(JRequest::getCmd('lang','')));
                $ignore_lang_code = $db->loadResult();
                if(!$ignore_lang_code){
                    $ignore_lang_code = '*';
                }
            }
            else
            {
                $ignore_lang_code = '';
                $codes = self::getLanguageCodes();
                foreach($codes As $code){
                    if(strstr(strtolower($code), strtolower(trim(JRequest::getCmd('lang','xxxxx')))) !== false){
                        $ignore_lang_code = $code;
                        break;
                    }
                }
            }
        }
        
        $language = $form['default_lang_code_ignore'] ? $ignore_lang_code : $form['default_lang_code'];
        
        if($is15){
            // an exception regarding limited options for language
            if (!isset($config['params'])){
                $registry = new JRegistry();
                $attr = array('language' => $language);
                $registry->loadArray($attr);
                $attribs = (string) $registry->toString();
            }
        }
        
        $access = $form['default_access'];
        
        $ordering = 0;
        
        if(!$is15 && $full){
            
            //$state = isset($config['state']) ? $config['state'] : $state;
            // change the state in the CB records as well if coming from article settings
            //if(isset($config['state'])){
            //    $db->setQuery("Update #__contentbuilder_records Set `published` = ".( intval($config['state'])  == 1 ? 1 : 0 )." Where reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
            //    $db->query();
            //}
            
            // limited
            $alias = isset($config['alias']) ? $config['alias'] : $alias;
            
            // limited
            $form['default_category'] = isset($config['catid']) ? $config['catid'] : $form['default_category'];
            
            // limited
            $access = isset($config['access']) ? $config['access'] : $access;
            
            // limited
            $featured = isset($config['featured']) ? $config['featured'] : 0;
            
            // limited
            $language = isset($config['language']) ? $config['language'] : $language;
            
            if($form['article_record_impact_language'] && isset($config['language'])){
                $db->setQuery("Select sef From #__languages Where published = 1 And lang_code = " . $db->Quote($config['language']));
                $sef = $db->loadResult();
                
                if($sef === null){
                    $sef = '';
                }
                
                $db->setQuery("Update #__contentbuilder_records Set sef = ".$db->Quote($sef).", lang_code = ".$db->Quote($config['language'])." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
                $db->query();
            }
            
            // limited
            $created_by_alias = isset($config['created_by_alias']) ? $config['created_by_alias'] : '';
            
            if($form['article_record_impact_publish'] && isset($config['publish_up']) && $config['publish_up'] != $publish_up){
               // check in strtotime due to php's different behavior on 64bit machines
               if(version_compare($version->getShortVersion(), '3.0', '>=')){
                   $___now = $_now->toSql();
               }else{
                   $___now = $_now->toMySQL();
               }
               $db->setQuery("Update #__contentbuilder_records Set ".(strtotime($config['publish_up'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $config['publish_up']) >= strtotime($___now) ? 'published = 0, is_future = 1, ' : '')." publish_up = ".$db->Quote($config['publish_up'])." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
               $db->query();
            }
            
            // limited
            $publish_up = isset($config['publish_up']) ? $config['publish_up'] : $publish_up;
            
            if($form['article_record_impact_publish'] && isset($config['publish_down']) && $config['publish_down'] != $publish_down){
                if(version_compare($version->getShortVersion(), '3.0', '>=')){
                   $___now = $_now->toSql();
                }else{
                   $___now = $_now->toMySQL();
                }
                $db->setQuery("Update #__contentbuilder_records Set ".(strtotime($config['publish_down'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $config['publish_down'] ) <= strtotime($___now) ? 'published = 0,' : '')."  publish_down = ".$db->Quote($config['publish_down'])." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
                $db->query();
            }
            
            // limited
            $publish_down = isset($config['publish_down']) ? $config['publish_down'] : $publish_down;
            
            // limited
            $metakey = isset($config['metakey']) ? $config['metakey'] : '';
            
            // limited
            $metadesc = isset($config['metadesc']) ? $config['metadesc'] : '';
            
            $robots = '';
            $author = '';
            $rights = '';
            $xreference = '';
            
            if(!$limited_options){
                
                // FULL
                $created_article = isset($config['created']) ? $config['created'] : null;

                // FULL
                if(JFactory::getApplication()->isAdmin()){
                    $created_by = isset($config['created_by']) ? $config['created_by'] : 0;
                }
                
                // FULL
                if (isset($config['attribs']) && is_array($config['attribs'])) {
                    $registry = new JRegistry();
                    $registry->loadArray($config['attribs']);
                    $attribs = (string) $registry;
                }

                // FULL
                if (isset($config['metadata']) && is_array($config['metadata'])) {
                    
                    if(isset($config['metadata']['robots'])){
                       $robots = $config['metadata']['robots'];
                    }
                    
                    if(isset($config['metadata']['author'])){
                       $author = $config['metadata']['author'];
                    }
                    
                    if(isset($config['metadata']['rights'])){
                       $rights = $config['metadata']['rights'];
                    }
                    
                    if(isset($config['metadata']['xreference'])){
                       $xreference = $config['metadata']['xreference'];
                    }
                    
                    $registry = new JRegistry();
                    $registry->loadArray($config['metadata']);
                    $meta = (string) $registry;
                }
            }

            $db->setQuery("Update #__contentbuilder_records Set robots = ".$db->Quote($robots).", author = ".$db->Quote($author).", rights = ".$db->Quote($rights).", xreference = ".$db->Quote($xreference).", metakey = ".$db->Quote($metakey).", metadesc = ".$db->Quote($metadesc)." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
            $db->query();
            
            // Trigger the onContentBeforeSave event.
            $isNew = true;
            $dispatcher = JDispatcher::getInstance();
            $table = JTable::getInstance('content');

            if ($article > 0) {
                $table->load($article);
                $isNew = false;
            }

            $dispatcher->trigger('onContentBeforeSave', array('com_content.article', &$table, $isNew));

        } else if($full) {
            
            //$state = isset($config['state']) ? $config['state'] : $state;
            // change the state in the CB records as well if coming from article settings
            //if(isset($config['state'])){
            //    $db->setQuery("Update #__contentbuilder_records Set `published` = ".( intval($config['state'])  == 1 ? 1 : 0 )." Where reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
            //    $db->query();
            //}
            
            // limited
            $alias = isset($config['alias']) ? $config['alias'] : $alias;
            
            // limited
            $form['default_category'] = isset($config['catid']) ? $config['catid'] : $form['default_category'];
            
            // limited
            $form['default_section'] = isset($config['sectionid']) ? $config['sectionid'] : $form['default_section'];
            
            // limited
            $access = isset($config['details']) && isset($config['details']['access']) ? $config['details']['access'] : $access;
            
            // limited
            $created_by_alias = isset($config['details']) && isset($config['details']['created_by_alias']) ? $config['details']['created_by_alias'] : '';
            
            // limited
            $ordering = isset($config['ordering']) && isset($config['ordering']) ? intval($config['ordering']) : 0;
            
            if($form['article_record_impact_publish'] && isset($config['details']) && isset($config['details']['publish_up']) && $config['details']['publish_up'] != $publish_up){
                if(version_compare($version->getShortVersion(), '3.0', '>=')){
                   $___now = $_now->toSql();
                }else{
                   $___now = $_now->toMySQL();
                }
                $db->setQuery("Update #__contentbuilder_records Set ".(strtotime($config['details']['publish_up'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $config['details']['publish_up']) >= strtotime($___now) ? 'published = 0,' : '')." publish_up = ".$db->Quote($config['details']['publish_up']).", is_future = 1 Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
                $db->query();
            }
            
            // limited
            $publish_up = isset($config['details']) && isset($config['details']['publish_up']) ? ($config['details']['publish_up'] == JText::_('Never') ? '0000-00-00 00:00:00' : $config['details']['publish_up']) : $publish_up;
            
            if($form['article_record_impact_publish'] && isset($config['details']) && isset($config['details']['publish_down']) && $config['details']['publish_down'] != $publish_down){
                if(version_compare($version->getShortVersion(), '3.0', '>=')){
                   $___now = $_now->toSql();
                }else{
                   $___now = $_now->toMySQL();
                }
                $db->setQuery("Update #__contentbuilder_records Set ".(strtotime($config['details']['publish_down'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $config['details']['publish_down']) <= strtotime($___now) ? 'published = 0,' : '')." publish_down = ".$db->Quote($config['details']['publish_down'])." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
                $db->query();
            }
            
            // limited
            $publish_down = isset($config['details']) && isset($config['details']['publish_down']) ? ($config['details']['publish_down'] == JText::_('Never') ? '0000-00-00 00:00:00' : $config['details']['publish_down']) : $publish_down;
            
            // limited
            $metakey = isset($config['meta']) && isset($config['meta']['keywords']) ? $config['meta']['keywords'] : '';
            
            // limited
            $metadesc = isset($config['meta']) && isset($config['meta']['description']) ? $config['meta']['description'] : '';
            
            $robots = '';
            $author = '';
            $rights = '';
            
            // an exception regarding limited options for language
            if (!isset($config['params'])){
                $ex = explode('-',$language);
                $sef = '';
                if(count($ex)){
                    $sef = strtolower($ex[0]);
                }
                if($form['article_record_impact_language']){
                    $db->setQuery("Update #__contentbuilder_records Set sef = ".$db->Quote($sef).", lang_code = ".$db->Quote($language)." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
                    $db->query();
                }
                $registry = new JRegistry();
                $attr = array('language' => $language);
                $registry->loadArray($attr);
                $attribs = (string) $registry->toString();
            }
            
            if(!$limited_options){
                // FULL
                $created_by = isset($config['details']) && isset($config['details']['created_by']) ? $config['details']['created_by'] : 0;
                
                // FULL
                $created_article = isset($config['details']) && isset($config['details']['created']) ? ($config['details']['created'] == JText::_('Never') ? '0000-00-00 00:00:00' : $config['details']['created']) : null;

                // FULL
                
                if (isset($config['params']) && is_array($config['params'])) {
                    if(isset($config['params']['language'])){
                        if($form['article_record_impact_language'] && $config['params']['language'] != $language){
                            $ex = explode('-',$config['params']['language']);
                            $sef = '';
                            if(count($ex)){
                                $sef = strtolower($ex[0]);
                            }
                            $db->setQuery("Update #__contentbuilder_records Set sef = ".$db->Quote($sef).", lang_code = ".$db->Quote($config['params']['language'])." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
                            $db->query();
                        }else{
                            $config['params']['language'] = $language;
                        }
                    }
                    $registry = new JRegistry();
                    $registry->loadArray($config['params']);
                    $attribs = (string) $registry->toString();
                }

                // FULL
                if (isset($config['meta']) && is_array($config['meta'])) {
                    
                    if(isset($config['meta']['robots'])){
                       $robots = $config['meta']['robots'];
                    }
                    
                    if(isset($config['meta']['author'])){
                       $author = $config['meta']['author'];
                    }
                    
                    if(isset($config['meta']['rights'])){
                       $rights = $config['meta']['rights'];
                    }
                    
                    $registry = new JRegistry();
                    $registry->loadArray($config['meta']);
                    $meta = (string) $registry->toString();
                }
            }
            
            $db->setQuery("Update #__contentbuilder_records Set robots = ".$db->Quote($robots).", author = ".$db->Quote($author).", rights = ".$db->Quote($rights).", xreference = '', metakey = ".$db->Quote($metakey).", metadesc = ".$db->Quote($metadesc)." Where `type` = ".$db->Quote($form['type'])." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = " . $db->Quote($record_id));
            $db->query();
            
            $dispatcher = JDispatcher::getInstance();
            $isNew = true;
            $dispatcher = JDispatcher::getInstance();
            $table = JTable::getInstance('content');

            $isNew = true;
            if ($article > 0) {
                $table->load($article);
                $isNew = false;
            }
            $dispatcher->trigger('onBeforeContentSave', array(&$table, $isNew));
        }
        
        $created_by = $created_by ? $created_by : $metadata->created_id;

        $date = JFactory::getDate();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            $created = $date->toSql();
        }else{
            $created = $date->toMySQL();
        }
        
        $created = $created_article ? $created_article : ($metadata->created ? $metadata->created : $created);
        
        if ($created && strlen(trim($created)) <= 10) {
            $created .= ' 00:00:00';
        }

        if (!$publish_up || $publish_up == '0000-00-00 00:00:00') {
            $publish_up = $created;
        }

        if (!$publish_down && !$article) {
            $publish_down = '0000-00-00 00:00:00';
        }
        
        $alias = $alias ? self::stringURLUnicodeSlug($alias) : self::stringURLUnicodeSlug($label);
        if(trim(str_replace('-','',$alias)) == '') {
                $datenow = JFactory::getDate();
                if(version_compare($version->getShortVersion(), '3.0', '>=')){
                    $alias = $datenow->format("%Y-%m-%d-%H-%M-%S");
                }else{
                    $alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
                }
        }
        
        // not existing, create
        if(!$article){
            
            $db->setQuery("Insert Into 
                    #__content 
                        (
                         `title`,
                         `alias`,
                         `introtext`,
                         `fulltext`,
                         `state`,
                         ".($is15 ? "`sectionid`," : '')."
                         `catid`,
                         `created`,
                         `created_by`,
                         `modified`,
                         `modified_by`,
                         `checked_out`,
                         `checked_out_time`,
                         `publish_up`,
                         `publish_down`,
                         `attribs`,
                         `version`,
                         `metakey`,
                         `metadesc`,
                         `metadata`,
                         `access`,
                         `created_by_alias`,
                         `ordering`
                         ".(!$is15 ? ',featured' : '')."
                         ".(!$is15 ? ',language' : '')."
                         ".(!$is15 ? ',xreference' : '')."
                        ) 
                    Values 
                        (
                          ".$db->Quote($label).",
                          ".$db->Quote($alias).",
                          ".$db->Quote($introtext).",
                          ".$db->Quote($fulltext).",
                          ".$db->Quote($state).",
                          ".($is15 ? intval($form['default_section'])."," : '')."
                          ".intval($form['default_category']).",
                          ".$db->Quote($created).",
                          ".$db->Quote($created_by ? $created_by : JFactory::getUser()->get('id',0)).",
                          '0000-00-00 00:00:00',
                          '0',
                          '0',
                          '0000-00-00 00:00:00',
                          ".$db->Quote($publish_up).",
                          ".$db->Quote($publish_down).",
                          ".$db->Quote($attribs).",
                          '1',
                          ".$db->Quote($metakey).",
                          ".$db->Quote($metadesc).",
                          ".$db->Quote($meta).",
                          ".$db->Quote($access).",
                          ".$db->Quote($created_by_alias).",
                          ".$db->Quote($ordering)."
                          ".(!$is15 ? ','.$db->Quote($featured) : '')."
                          ".(!$is15 ? ','.$db->Quote($language) : '')."
                          ".(!$is15 ? ','.$db->Quote(is_array($config) && isset($config['metadata']) && is_array($config['metadata']) && isset($config['metadata']['xreference']) ? $config['metadata']['xreference'] : '') : '')."
                        )
            ");
            $db->query();
            $article = $db->insertid();
            $datenow = JFactory::getDate();
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $___datenow = $datenow->toSql();
            }else{
                $___datenow = $datenow->toMySQL();
            }
            $db->setQuery("Insert Into #__contentbuilder_articles (`type`,`reference_id`,`last_update`,`article_id`,`record_id`,`form_id`) Values (".$db->Quote($form['type']).",".$db->Quote($form['reference_id']).",".$db->Quote($___datenow).",$article,".$db->Quote($record_id).",".intval($contentbuilder_form_id).")");
            $db->query();
            $db->setQuery("Update #__content Set introtext = concat('<div style=\'display:none;\'><!--(cbArticleId:$article)--></div>', introtext) Where id = $article");
            $db->query();
            
        // existing, update
        }else{
            
            $datenow = JFactory::getDate();
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $___datenow = $datenow->toSql();
            }else{
                $___datenow = $datenow->toMySQL();
            }
            $modified = $metadata->modified ? $metadata->modified : $___datenow;
            $modified_by = $metadata->modified_id ? $metadata->modified_id : JFactory::getUser()->get('id',0);
        
            if($full){
                $db->setQuery("Update 
                        #__content 
                            Set
                             `title` = ".$db->Quote($label).",
                             `alias` = ".$db->Quote($alias).",
                             `introtext` = ".$db->Quote('<div style=\'display:none;\'><!--(cbArticleId:'.$article.')--></div>'.$introtext).",
                             `fulltext` = ".$db->Quote($fulltext.'<div style=\'display:none;\'><!--(cbArticleId:'.$article.')--></div>').",
                             `state` = ".$db->Quote($state).",
                             ".($is15 ? "`sectionid` = ".intval($form['default_section'])."," : '')."
                             `catid` = ".intval($form['default_category']).",
                             `modified` = ".$db->Quote($modified).",
                             `modified_by` = ".$db->Quote($modified_by ? $modified_by : JFactory::getUser()->get('id',0)).",
                             `attribs` = ".$db->Quote($attribs).",
                             `metakey` = ".$db->Quote($metakey).",
                             `metadesc` = ".$db->Quote($metadesc).",
                             `metadata` = ".$db->Quote($meta).",
                             `version` = `version`+1,
                             `created` = ".$db->Quote($created).",
                             `created_by` = ".$db->Quote($created_by).",
                             `created_by_alias` = ".$db->Quote($created_by_alias).",
                             `publish_up` = ".$db->Quote($publish_up).",
                             `publish_down` = ".$db->Quote($publish_down).",
                             `access` = ".$db->Quote($access).",
                             `ordering` = ".$db->Quote($ordering)."
                             ".(!$is15 ? ',featured='.$db->Quote($featured) : '')."
                             ".(!$is15 ? ',language='.$db->Quote($language) : '')."
                             ".(!$is15 ? ',xreference='.$db->Quote(is_array($config) && isset($config['metadata']) && is_array($config['metadata']) && isset($config['metadata']['xreference']) ? $config['metadata']['xreference'] : '') : '')."
                        Where id = $article
                ");
            } else {
                $db->setQuery("Update 
                        #__content 
                            Set
                             `title` = ".$db->Quote($label).",
                             `alias` = ".$db->Quote($alias).",
                             `introtext` = ".$db->Quote('<div style=\'display:none;\'><!--(cbArticleId:'.$article.')--></div>'.$introtext).",
                             `fulltext` = ".$db->Quote($fulltext.'<div style=\'display:none;\'><!--(cbArticleId:'.$article.')--></div>').",
                             `state` = ".$db->Quote($state).",
                             `modified` = ".$db->Quote($modified).",
                             `modified_by` = ".$db->Quote($modified_by ? $modified_by : JFactory::getUser()->get('id',0)).",
                             `version` = `version`+1
                             ".(!$is15 ? ',language='.$db->Quote($language) : '')."
                        Where id = $article
                ");
            }
            $db->query();
            // here we do not limit to the form_id, as this is a record change and all articles should be notified
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $___datenow = $datenow->toSql();
            }else{
                $___datenow = $datenow->toMySQL();
            }
            $db->setQuery("Update #__contentbuilder_articles Set `last_update` = ".$db->Quote($___datenow)." Where `type` = ".$db->Quote($form['type'])." And form_id = ".intval($contentbuilder_form_id)." And reference_id = ".$db->Quote($form['reference_id'])." And record_id = ".$db->Quote($record_id));
            $db->query();
        }
        
        // Bind the rules.
        /*
        if ($full && !$is15 && $article && isset($config['rules']) && is_array($config['rules'])) {
            
            // Cleaning the rules
            $new = array();
            foreach($config['rules'] As $key => $the_rules){
                foreach($the_rules As $key2 => $the_rule){
                    if(!isset($new[$key])){
                        $new[$key] = array();
                    }
                    if(trim($the_rule) != ''){
                        $new[$key][$key2] = intval($the_rule);
                    }
                }
            }
            $config['rules'] = $new;
            
            $registry = new JRegistry();
            $registry->loadArray($config['rules']);
            $json_rules = (string) $registry;
            
            $cat_asset = JTable::getInstance('Asset');
            $cat_asset->loadByName('com_content.category.' . $form['default_category']);

            $article_asset = JTable::getInstance('Asset');
            $article_asset->loadByName('com_content.article.' . $article);
            
            if($cat_asset->id != $article_asset->parent_id){
                $article_asset->setLocation($cat_asset->id, 'last-child');
            }
            
            $article_asset->name = 'com_content.article.'.$article;
            $article_asset->rules = $json_rules;
            $article_asset->title = $label;
            
            $article_asset->store();
            
            $db->setQuery("Update #__content Set asset_id = " . $article_asset->id. " Where id = " . $article);
            $db->query();
        }*/
        
        if($article && $is15) {
            $row = JTable::getInstance('content');
            if($row->load($article)){
                $row->reorder('sectionid = '.(int) $form['default_section'].' And catid = '.(int) $form['default_category'].' AND state >= 0');
            }
        }else if($article){
            $row = JTable::getInstance('content');
            if($row->load($article)){
                $row->reorder('catid = '.(int) $form['default_category'].' AND state >= 0');
            }
        }
        
        if(!$is15){
            // cleaning cache
            // Trigger the onContentCleanCache event.
            $conf = JFactory::getConfig();
            $options = array(
                'defaultgroup' => 'com_content',
                'cachebase' => $conf->get('cache_path', JPATH_SITE . DS . 'cache')
            );
            $cache = JFactory::getCache('com_content');
            $cache->clean();
            $cache = JFactory::getCache('com_contentbuilder');
            $cache->clean();
            
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onContentCleanCache', $options);
            
            //// trigger onContentAfterSave event
            $isNew = true;
            $dispatcher = JDispatcher::getInstance();
            $table = JTable::getInstance('content');

            if ($article > 0) {
                $table->load($article);
                $isNew = false;
            }
            $dispatcher->trigger('onContentAfterSave', array('com_content.article', &$table, $isNew));

        }else{
            //if($article && $full){
                $frontpage = isset($config['frontpage']) ? $config['frontpage'] : $featured;
                if($frontpage !== null){
                    if(!$frontpage){
                        $query = 'Delete From #__content_frontpage Where content_id = '. (int) $article;
                        $db->setQuery($query);
                        $db->query();
                    }else{
                        $query = 'Select content_id From #__content_frontpage Where content_id = '. (int) $article;
                        $db->setQuery($query);
                        if(!$db->loadResult()){
                            $query = 'Insert Into #__content_frontpage (content_id) Values ('.(int) $article.')';
                            $db->setQuery($query);
                            $db->query();
                        }
                    }
                    JTable::addIncludePath(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_frontpage'.DS.'tables');
                    $fp = JTable::getInstance('frontpage', 'Table');
                    $fp->reorder();
                }
            //}
            
            $cache = JFactory::getCache('com_content');
            $cache->clean();
            $cache = JFactory::getCache('com_contentbuilder');
            $cache->clean();
            
            $isNew = true;
            $table = JTable::getInstance('content');
            if ($article > 0) {
                $table->load($article);
                $isNew = false;
            }
            $dispatcher = JDispatcher::getInstance();
            $dispatcher->trigger('onAfterContentSave', array(&$table, $isNew));
        }
        
        JPluginHelper::importPlugin('contentbuilder_listaction');
        $dispatcher = JDispatcher::getInstance();
        
        $result = $dispatcher->trigger('onAfterArticleCreation', array($contentbuilder_form_id, $record_id, $article));
        $msg = implode('',$result);
        
        if($msg){
            JFactory::getApplication()->enqueueMessage($msg);
        }
        
        return $article;
    }
    
    public static function setPermissions($form_id, $record_id = 0, $suffix = ''){
        
        $db = JFactory::getDBO();
        
        $db->setQuery("Select `type`, `reference_id` From #__contentbuilder_forms Where id = " . intval($form_id) . " And published = 1");
        $type = $db->loadAssoc();
        
        $num_records_query = '';
        if(is_array($type)){
            $reference_id = $type['reference_id'];
            $type = $type['type'];
            $_type = $type;
            if(JFile::exists(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'types' . DS . $type . '.php')){
                require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_contentbuilder' . DS . 'classes' . DS . 'types' . DS . $type . '.php');
                $type = 'contentbuilder_'.$type;
                if(class_exists($type)){
                    $num_records_query = call_user_func(array($type, 'getNumRecordsQuery'),$reference_id, JFactory::getUser()->get('id', 0));
                    //$num_records_query = $type::getNumRecordsQuery($reference_id, JFactory::getUser()->get('id', 0));
                }
            } else if(JFile::exists(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . $type . '.php')){
                require_once(JPATH_SITE . DS . 'media' . DS . 'contentbuilder' . DS . 'types' . DS . $type . '.php');
                $type = 'contentbuilder_'.$type;
                if(class_exists($type)){
                    $num_records_query = call_user_func(array($type, 'getNumRecordsQuery'),$reference_id, JFactory::getUser()->get('id', 0));
                    //$num_records_query = $type::getNumRecordsQuery($reference_id, JFactory::getUser()->get('id', 0));
                }
            }
        }
        
        /*
        $rec = 0;
        if(is_array($record_id)){
            $i = 0;
            $rec = '';
            $size = count($record_id);
            foreach($record_id As $rec_id){
                if($i+1 < $size){
                    $rec .= $db->Quote($rec_id).',';
                } else {
                    $rec .= $db->Quote($rec_id);
                }
                $i++;
            }
        } else {
           $rec = $db->Quote($record_id);
        }*/
        
        $db->setQuery("
            Select 
                forms.config,
                forms.verification_required_view,
                forms.verification_required_new,
                forms.verification_required_edit,
                forms.verification_days_view,
                forms.verification_days_new,
                forms.verification_days_edit,
                forms.verification_url_view,
                forms.verification_url_new,
                forms.verification_url_edit,
                contentbuilder_users.userid,
                forms.limit_add,
                forms.limit_edit,
                ".($num_records_query ? '('.$num_records_query.') ' : "'0'")." As amount_records,
                contentbuilder_users.verified_view,
                contentbuilder_users.verified_new,
                contentbuilder_users.verified_edit,
                contentbuilder_users.verification_date_view,
                contentbuilder_users.verification_date_new,
                contentbuilder_users.verification_date_edit,
                contentbuilder_users.limit_add As user_limit_add,
                contentbuilder_users.limit_edit As user_limit_edit,
                contentbuilder_users.published
                ".($record_id && !is_array($record_id) ? ',contentbuilder_records.edited' : ",'0' As edited")."
            From 
                #__contentbuilder_forms As forms
                Left Join 
                    #__contentbuilder_users As contentbuilder_users
                On ( contentbuilder_users.form_id = forms.id And contentbuilder_users.userid = ".JFactory::getUser()->get('id', 0)." )
                ".( $record_id && !is_array($record_id) ? "Left Join 
                    #__contentbuilder_records As contentbuilder_records
                On ( contentbuilder_records.`type` = ".$db->Quote(isset($_type) ? $_type : '')." And contentbuilder_records.reference_id = forms.reference_id And contentbuilder_records.record_id = ".$db->Quote($record_id)." )
                " : '')."
            Where 
                forms.id = " . intval($form_id) ."
            And
                forms.published = 1
        ");
        $result = $db->loadAssoc();
        
        $config = unserialize(cb_b64dec($result['config']));
        
        jimport('joomla.version');
        $version = new JVersion();
        $acl = JFactory::getACL();
        if(version_compare($version->getShortVersion(), '1.6', '<')){
            
            $has_own = false;
            
            //if(!$exclude_own){
                
                $user_type = JFactory::getUser()->get('usertype', 'public frontend');
                if(!$user_type){
                    $user_type = 'public frontend';
                }
                
                if($result['published'] !== null && !$result['published']){
                
                    $acl->addACL('com_contentbuilder_published', 'any', 'users', strtolower($user_type),null,null,'notok');
                
                } else {
                    
                    $acl->addACL('com_contentbuilder_published', 'any', 'users', strtolower($user_type),null,null,'ok');
                
                }
                
                if(intval($result['limit_edit']) > 0 && intval($result['user_limit_edit']) > 0 && $result['edited'] >= intval($result['user_limit_edit'])){
                
                    $acl->addACL('com_contentbuilder_limit_edit', 'edit', 'users', strtolower($user_type),null,null,'notok');
                
                } 
                else if(intval($result['limit_edit']) > 0 && intval($result['user_limit_edit']) <= 0 && $result['edited'] >= intval($result['limit_edit'])){
                
                    $acl->addACL('com_contentbuilder_limit_edit', 'edit', 'users', strtolower($user_type),null,null,'notok');
                
                } else {
                    
                    $acl->addACL('com_contentbuilder_limit_edit', 'edit', 'users', strtolower($user_type),null,null,'ok');
                }
                
                if(intval($result['limit_add']) > 0 && intval($result['user_limit_add']) > 0 && $result['amount_records'] >= intval($result['user_limit_add'])){
                
                    $acl->addACL('com_contentbuilder_limit_add', 'new', 'users', strtolower($user_type),null,null,'notok');
                
                } 
                else if(intval($result['limit_add']) > 0 && intval($result['user_limit_add']) <= 0 && $result['amount_records'] >= intval($result['limit_add'])){
                
                    $acl->addACL('com_contentbuilder_limit_add', 'new', 'users', strtolower($user_type),null,null,'notok');
                
                } else {
                    
                    $acl->addACL('com_contentbuilder_limit_add', 'new', 'users', strtolower($user_type),null,null,'ok');
                }
                
                $jdate = JFactory::getDate();
                
                if($result['verification_required_view']){
                    $days = floatval($result['verification_days_view']) * 86400;
                    $date = $result['verification_date_view'] !== null ? strtotime($result['verification_date_view'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $result['verification_date_view']) : 0;
                    $valid_until = $date + $days;
                    if(version_compare($version->getShortVersion(), '3.0', '>=')){
                        $now = strtotime($jdate->toSql());
                    }else{
                        $now = strtotime($jdate->toMySQL());
                    }
                    
                    if($result['verified_view']){
                        if($now < $valid_until || floatval($result['verification_days_view']) <= 0){
                            $acl->addACL('com_contentbuilder_verify', 'view', 'users', strtolower($user_type),null,null,'ok');
                        }else{
                            $acl->addACL('com_contentbuilder_verify', 'view', 'users', strtolower($user_type),null,null, trim($result['verification_url_view']) != '' ? trim($result['verification_url_view']) : 'notok');
                        }
                    }else{
                        $acl->addACL('com_contentbuilder_verify', 'view', 'users', strtolower($user_type),null,null, trim($result['verification_url_view']) != '' ? trim($result['verification_url_view']) : 'notok');
                    }
                }else{
                    $acl->addACL('com_contentbuilder_verify', 'view', 'users', strtolower($user_type),null,null,'ok');
                }
                
                if($result['verification_required_new']){
                    $days = floatval($result['verification_days_new']) * 86400;
                    $date = $result['verification_date_new'] !== null ? strtotime($result['verification_date_new'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $result['verification_date_new']) : 0;
                    $valid_until = $date + $days;
                    if(version_compare($version->getShortVersion(), '3.0', '>=')){
                        $now = strtotime($jdate->toSql());
                    }else{
                        $now = strtotime($jdate->toMySQL());
                    }
                    if($result['verified_new']){
                        if($now < $valid_until || floatval($result['verification_days_new']) <= 0){
                            $acl->addACL('com_contentbuilder_verify', 'new', 'users', strtolower($user_type),null,null,'ok');
                        }else{
                            $acl->addACL('com_contentbuilder_verify', 'new', 'users', strtolower($user_type),null,null,trim($result['verification_url_new']) != '' ? trim($result['verification_url_new']) : 'notok');
                        }
                    }else{
                        $acl->addACL('com_contentbuilder_verify', 'new', 'users', strtolower($user_type),null,null,trim($result['verification_url_new']) != '' ? trim($result['verification_url_new']) : 'notok');
                    }
                }else{
                    
                    $acl->addACL('com_contentbuilder_verify', 'new', 'users', strtolower($user_type),null,null,'ok');
                }
                
                if($result['verification_required_edit']){
                    $days = floatval($result['verification_days_edit']) * 86400;
                    $date = $result['verification_date_edit'] !== null ? strtotime($result['verification_date_edit'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $result['verification_date_edit']) : 0;
                    $valid_until = $date + $days;
                    if(version_compare($version->getShortVersion(), '3.0', '>=')){
                        $now = strtotime($jdate->toSql());
                    }else{
                        $now = strtotime($jdate->toMySQL());
                    }
                    if($result['verified_edit']){
                        if($now < $valid_until || floatval($result['verification_days_edit']) <= 0){
                            $acl->addACL('com_contentbuilder_verify', 'edit', 'users', strtolower($user_type),null,null,'ok');
                        }else{
                            $acl->addACL('com_contentbuilder_verify', 'edit', 'users', strtolower($user_type),null,null,trim($result['verification_url_edit']) != '' ? trim($result['verification_url_edit']) : 'notok');
                        }
                    }else{
                        $acl->addACL('com_contentbuilder_verify', 'edit', 'users', strtolower($user_type),null,null,trim($result['verification_url_edit']) != '' ? trim($result['verification_url_edit']) : 'notok');
                    }
                }else{
                    $acl->addACL('com_contentbuilder_verify', 'edit', 'users', strtolower($user_type),null,null,'ok');
                }
                
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['view']) && $config['own' . $suffix]['view']) {
                    $acl->addACL('com_contentbuilder', 'view', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['edit']) && $config['own' . $suffix]['edit']) {
                    $acl->addACL('com_contentbuilder', 'edit', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['delete']) && $config['own' . $suffix]['delete']) {
                    $acl->addACL('com_contentbuilder', 'delete', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['state']) && $config['own' . $suffix]['state']) {
                    $acl->addACL('com_contentbuilder', 'state', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['publish']) && $config['own' . $suffix]['publish']) {
                    $acl->addACL('com_contentbuilder', 'publish', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['fullarticle']) && $config['own' . $suffix]['fullarticle']) {
                    $acl->addACL('com_contentbuilder', 'fullarticle', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['listaccess']) && $config['own' . $suffix]['listaccess']) {
                    $acl->addACL('com_contentbuilder', 'listaccess', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['new']) && $config['own' . $suffix]['new']) {
                    $acl->addACL('com_contentbuilder', 'new', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['language']) && $config['own' . $suffix]['language']) {
                    $acl->addACL('com_contentbuilder', 'language', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id));
                    $has_own = true;
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['rating']) && $config['own' . $suffix]['rating']) {
                    $acl->addACL('com_contentbuilder', 'rating', 'users', strtolower($user_type), null, null, array('own' => true, 'form_id' => $form_id));
                    $has_own = true;
                }
            //}
            
            if(!$has_own){
                $db->setQuery("Select id, `name` From #__core_acl_aro_groups Where name <> 'ROOT' And name <> 'USERS'");
                $groups = $db->loadAssocList();
                if ($config) {
                    foreach ($groups As $group) {
                        $groupname = '';
                        
                        if (function_exists('mb_strtolower')) {
                            $groupname = mb_strtolower($group['name']);
                        } else {
                            $groupname = strtolower($group['name']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['listaccess']) && $config['permissions'.$suffix][$group['id']]['listaccess']) {
                            $acl->addACL('com_contentbuilder', 'listaccess', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['view']) && $config['permissions'.$suffix][$group['id']]['view']) {
                            $acl->addACL('com_contentbuilder', 'view', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['new']) && $config['permissions'.$suffix][$group['id']]['new']) {
                            $acl->addACL('com_contentbuilder', 'new', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['edit']) && $config['permissions'.$suffix][$group['id']]['edit']) {
                            $acl->addACL('com_contentbuilder', 'edit', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['delete']) && $config['permissions'.$suffix][$group['id']]['delete']) {
                            $acl->addACL('com_contentbuilder', 'delete', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['state']) && $config['permissions'.$suffix][$group['id']]['state']) {
                            $acl->addACL('com_contentbuilder', 'state', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['publish']) && $config['permissions'.$suffix][$group['id']]['publish']) {
                            $acl->addACL('com_contentbuilder', 'publish', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['fullarticle']) && $config['permissions'.$suffix][$group['id']]['fullarticle']) {
                            $acl->addACL('com_contentbuilder', 'fullarticle', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['language']) && $config['permissions'.$suffix][$group['id']]['language']) {
                            $acl->addACL('com_contentbuilder', 'language', 'users', $groupname, null, null, $group['id']);
                        }
                        if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['rating']) && $config['permissions'.$suffix][$group['id']]['rating']) {
                            $acl->addACL('com_contentbuilder', 'rating', 'users', $groupname, null, null, $group['id']);
                        }
                    }
                }
            }
            
        }else{
            
            JFactory::getSession()->clear('permissions'.$suffix, 'com_contentbuilder');
            $permissions = array();
            
            //if(!$exclude_own){
            
                $permissions['published'] = true;
                
                if($result['published'] !== null && !$result['published']){
                
                    $permissions['published'] = false;
                    
                }
                
                $permissions['limit_edit'] = true;
                
                if(intval($result['limit_edit']) > 0 && intval($result['user_limit_edit']) > 0 && $result['edited'] >= intval($result['user_limit_edit'])){
                
                    $permissions['limit_edit'] = false;
                    
                } 
                else if(intval($result['limit_edit']) > 0 && intval($result['user_limit_edit']) <= 0 && $result['edited'] >= intval($result['limit_edit'])){
                
                    $permissions['limit_edit'] = false;
                    
                }
            
                $permissions['limit_add'] = true;
                
                if(intval($result['limit_add']) > 0 && intval($result['user_limit_add']) > 0 && $result['amount_records'] >= intval($result['user_limit_add'])){
                
                    $permissions['limit_add'] = false;
                    
                } 
                else if(intval($result['limit_add']) > 0 && intval($result['user_limit_add']) <= 0 && $result['amount_records'] >= intval($result['limit_add'])){
                
                    $permissions['limit_add'] = false;
                    
                }
            
                $jdate = JFactory::getDate();
                
                $permissions['verify_view'] = true;
                if($result['verification_required_view']){
                    $days = floatval($result['verification_days_view']) * 86400;
                    $date = $result['verification_date_view'] !== null ? strtotime($result['verification_date_view'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $result['verification_date_view']) : 0;
                    $valid_until = $date + $days;
                    if(version_compare($version->getShortVersion(), '3.0', '>=')){
                        $now = strtotime($jdate->toSql());
                    }else{
                        $now = strtotime($jdate->toMySQL());
                    }
                    
                    if($result['verified_view']){
                        if($now < $valid_until || floatval($result['verification_days_view']) <= 0){
                            $permissions['verify_view'] = true;
                        }else{
                            $permissions['verify_view'] = trim($result['verification_url_view']) != '' ? trim($result['verification_url_view']) : false;
                        }
                    }else{
                        $permissions['verify_view'] = trim($result['verification_url_view']) != '' ? trim($result['verification_url_view']) : false;
                    }
                }
                
                $permissions['verify_new'] = true;
                if($result['verification_required_new']){
                    $days = floatval($result['verification_days_new']) * 86400;
                    $date = $result['verification_date_new'] !== null ? strtotime($result['verification_date_new'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $result['verification_date_new']) : 0;
                    $valid_until = $date + $days;
                    if(version_compare($version->getShortVersion(), '3.0', '>=')){
                        $now = strtotime($jdate->toSql());
                    }else{
                        $now = strtotime($jdate->toMySQL());
                    }
                    
                    if($result['verified_new']){
                        if($now < $valid_until || floatval($result['verification_days_new']) <= 0){
                            $permissions['verify_new'] = true;
                        }else{
                            $permissions['verify_new'] = trim($result['verification_url_new']) != '' ? trim($result['verification_url_new']) : false;
                        }
                    }else{
                        $permissions['verify_new'] = trim($result['verification_url_new']) != '' ? trim($result['verification_url_new']) : false;
                    }
                }
                
                
                $permissions['verify_edit'] = true;
                if($result['verification_required_edit']){
                    $days = floatval($result['verification_days_edit']) * 86400;
                    $date = $result['verification_date_edit'] !== null ? strtotime($result['verification_date_edit'] == '0000-00-00 00:00:00' ? '1976-03-07 22:10:00' : $result['verification_date_edit']) : 0;
                    $valid_until = $date + $days;
                    if(version_compare($version->getShortVersion(), '3.0', '>=')){
                        $now = strtotime($jdate->toSql());
                    }else{
                        $now = strtotime($jdate->toMySQL());
                    }
                    
                    if($result['verified_edit']){
                        if($now < $valid_until || floatval($result['verification_days_edit']) <= 0){
                            $permissions['verify_edit'] = true;
                        }else{
                            $permissions['verify_edit'] = trim($result['verification_url_edit']) != '' ? trim($result['verification_url_edit']) : false;
                        }
                    }else{
                        $permissions['verify_edit'] = trim($result['verification_url_edit']) != '' ? trim($result['verification_url_edit']) : false;
                    }
                }
            
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['view']) && $config['own' . $suffix]['view']) {
                    if (!isset($permissions['own'])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['view'] = array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['edit']) && $config['own' . $suffix]['edit']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['edit'] = array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['delete']) && $config['own' . $suffix]['delete']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['delete'] = array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['state']) && $config['own' . $suffix]['state']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['state'] = array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['publish']) && $config['own' . $suffix]['publish']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['publish'] = array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['fullarticle']) && $config['own' . $suffix]['fullarticle']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['fullarticle'] = array('own' => true, 'form_id' => $form_id, 'record_id' => $record_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['listaccess']) && $config['own' . $suffix]['listaccess']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['listaccess'] = array('own' => true, 'form_id' => $form_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['new']) && $config['own' . $suffix]['new']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['new'] = array('own' => true, 'form_id' => $form_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['language']) && $config['own' . $suffix]['language']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['language'] = array('own' => true, 'form_id' => $form_id);
                }
                if (isset($config['own' . $suffix]) && isset($config['own' . $suffix]['rating']) && $config['own' . $suffix]['rating']) {
                    if (!isset($permissions['own' . $suffix])) {
                        $permissions['own' . $suffix] = array();
                    }
                    $permissions['own' . $suffix]['rating'] = array('own' => true, 'form_id' => $form_id);
                }
            //}
            
            $db->setQuery("Select id From #__usergroups");
            $groups = $db->loadAssocList();
            
            foreach ($groups As $group) {
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['listaccess']) && $config['permissions'.$suffix][$group['id']]['listaccess']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['listaccess'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['view']) && $config['permissions'.$suffix][$group['id']]['view']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['view'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['new']) && $config['permissions'.$suffix][$group['id']]['new']) {
                    if(!isset($permissions[$group['id']])){
                    
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['new'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['edit']) && $config['permissions'.$suffix][$group['id']]['edit']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['edit'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['delete']) && $config['permissions'.$suffix][$group['id']]['delete']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['delete'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['state']) && $config['permissions'.$suffix][$group['id']]['state']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['state'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['publish']) && $config['permissions'.$suffix][$group['id']]['publish']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['publish'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['fullarticle']) && $config['permissions'.$suffix][$group['id']]['fullarticle']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['fullarticle'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['language']) && $config['permissions'.$suffix][$group['id']]['language']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['language'] = true;
                }
                if (isset($config['permissions'.$suffix][$group['id']]) && isset($config['permissions'.$suffix][$group['id']]['rating']) && $config['permissions'.$suffix][$group['id']]['rating']) {
                    if(!isset($permissions[$group['id']])){
                        $permissions[$group['id']] = array();
                    }
                    $permissions[$group['id']]['rating'] = true;
                }
            }
            
            JFactory::getSession()->set('permissions'.$suffix, $permissions, 'com_contentbuilder');
        } 
    }
    
    public static function stringURLUnicodeSlug($string)
    {
            // Replace double byte whitespaces by single byte (East Asian languages)
            $str = preg_replace('/\xE3\x80\x80/', ' ', $string);


            // Remove any '-' from the string as they will be used as concatenator.
            // Would be great to let the spaces in but only Firefox is friendly with this

            $str = str_replace('-', ' ', $str);

            // Replace forbidden characters by whitespaces
            $str = preg_replace( '#[:\#\*"@+=;!&\.%()\]\/\'\\\\|\[]#',"\x20", $str );

            // Delete all '?'
            $str = str_replace('?', '', $str);

            // Trim white spaces at beginning and end of alias and make lowercase
            $str = trim(JString::strtolower($str));

            // Remove any duplicate whitespace and replace whitespaces by hyphens
            $str =preg_replace('#\x20+#','-', $str);

            return $str;
    }
    
    public static function checkPermissions($action, $error_msg, $suffix = '', $auth = false){
        
        $allowed = false;
        
        jimport('joomla.version');
        $version = new JVersion();
        
        if(version_compare($version->getShortVersion(), '1.6', '<')){
            $user = JFactory::getUser();
            
            if($user->guest){
                $user->usertype = 'public frontend'; 
                $user->gid      = JFactory::getAcl()->get_group_id ( 'public frontend', 'ARO' );
            }
            
            $user_return = $user->authorize('com_contentbuilder', $action);
            
            $published_return = $user->authorize('com_contentbuilder_published', 'any');
            if($published_return !== 'ok'){
                if(!$auth){
                    //JError::raiseError(403, $error_msg);
                    JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                }else{
                    return false;
                }
            }
            
            switch($action){
                case 'edit':
                    $edit_return = $user->authorize('com_contentbuilder_limit_edit', $action);
                    if($edit_return !== 'ok'){
                        if(!$auth){
                            //JError::raiseError(403, $error_msg);
                            JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                        }else{
                            return false;
                        }
                    }
                    break;
            }
            
            switch($action){
                case 'new':
                    $add_return = $user->authorize('com_contentbuilder_limit_add', $action);
                    if($add_return !== 'ok'){
                        if(!$auth){
                            //JError::raiseError(403, $error_msg);
                            JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                        }else{
                            return false;
                        }
                    }
                    break;
            }
            
            switch($action){
                case 'edit':
                case 'new':
                case 'view':
                case 'delete':
                    $myaction = $action == 'delete' ? 'edit' : $action;
                    
                    $verify_return = $user->authorize('com_contentbuilder_verify', $myaction);
                    if($verify_return !== 'ok'){
                        if($verify_return === 'notok'){
                            if(!$auth){
                                //JError::raiseError(403, $error_msg);
                                JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                            }else{
                                return false;
                            }
                        }
                        else if($verify_return !== 'notok'){
                            if(!$auth){
                                JFactory::getApplication()->redirect($verify_return);
                            } else {
                                return false;
                            }
                        }
                    }
                    break;
            }
            
            if($user->guest){
                $user->usertype = ''; 
                $user->gid      = 0;
            }
            
            if(is_array($user_return) && isset($user_return['own']) && $user_return['own']){
                $db = JFactory::getDBO();
                
                static $typeref;
                
                if(is_array($typeref)){
                    $typerefid = $typeref[intval($user_return['form_id'])];
                }else{
                    $db->setQuery("Select `type`, `reference_id` From #__contentbuilder_forms Where id = " . intval($user_return['form_id']));
                    $typerefid = $db->loadAssoc();
                    $typeref[intval($user_return['form_id'])] = $typerefid;
                }
                
                if(is_array($typerefid)){
                    $form = self::getForm($typerefid['type'], $typerefid['reference_id']);
                    if($form && (!isset($user_return['record_id']))){
                        $allowed = true;
                    }else{
                        if(is_array($user_return['record_id'])){
                            foreach($user_return['record_id'] As $recid){
                               $db->setQuery("Select session_id From #__contentbuilder_records Where `record_id` = ".$db->Quote($recid)." And `type` = ".$db->Quote($typerefid['type'])." And `reference_id` = ".$db->Quote($typerefid['reference_id'])."");
                               $session_id = $db->loadResult();
                               if($form && $session_id != JFactory::getSession()->getId() && !$form->isOwner(JFactory::getUser()->get('id',0), $recid)){
                                    $allowed = false;
                                    break;
                                } else {
                                    $allowed = true;
                                }
                            }
                        }else{
                            $db->setQuery("Select session_id From #__contentbuilder_records Where `record_id` = ".$db->Quote($user_return['record_id'])." And `type` = ".$db->Quote($typerefid['type'])." And `reference_id` = ".$db->Quote($typerefid['reference_id'])."");
                            $session_id = $db->loadResult();
                            if($form && ( $session_id == JFactory::getSession()->getId() || ( JFactory::getUser()->get('id',0) && $form->isOwner(JFactory::getUser()->get('id',0), $user_return['record_id']) ) ) ){
                                $allowed = true;
                            }
                        }
                    }
                }
                
            }else{
                if(!is_array($user_return) && $user_return){
                    $allowed = true;
                }
            }
            if (!$allowed) {
                if(!$auth){
                    //JError::raiseError(403, $error_msg);
                    JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                }else{
                    return false;
                }
            }
            
        }else{
           
            $permissions = JFactory::getSession()->get('permissions'.$suffix, array(), 'com_contentbuilder');
           
            $published_return = $permissions['published'];
            if(!$published_return){
                if(!$auth){
                    //JError::raiseError(403, $error_msg);
                    JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                }else{
                    return false;
                }
            }
            
            switch($action){
                case 'edit':
                    $edit_return = $permissions['limit_edit'];
                    if(!$edit_return){
                        if(!$auth){
                            //JError::raiseError(403, $error_msg);
                            JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                        }else{
                            return false;
                        }
                    }
                    break;
            }
            
            switch($action){
                case 'new':
                    $add_return = $permissions['limit_add'];
                    if(!$add_return){
                        if(!$auth){
                            //JError::raiseError(403, $error_msg);
                            JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                        }else{
                            return false;
                        }
                    }
                    break;
            }
            
            switch($action){
                case 'edit':
                case 'new':
                case 'view':
                case 'delete':
                    $myaction = $action == 'delete' ? 'edit' : $action;
                    $verify_return = $permissions['verify_'.$myaction];
                    
                    if($verify_return !== true){
                        
                        if($verify_return === false){
                            
                            if(!$auth){
                                //JError::raiseError(403, $error_msg);
                                JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
                            }else{
                                return false;
                            }
                        }
                        else if(is_string ($verify_return)){
                            if(!$auth){
                                JFactory::getApplication()->redirect($verify_return);
                            } else {
                                return false;
                            }
                        }
                    }
                    break;
            }
            
            if(!isset($permissions['own'.$suffix])){
                $gids = array();
                
                $groups = JAccess::getGroupsByUser(JFactory::getUser()->get('id',0));
                
                foreach($groups As $gid){
                    $gids[] = $gid;
                }
                
                foreach($permissions As $group_id => $group_action){
                    if(isset($group_action[$action]) && $group_action[$action] && in_array($group_id, $gids)){
                        $allowed = true;
                        break;
                    }
                }
                
            }else{
                
                if(isset($permissions['own'.$suffix][$action])){
                    $user_return = $permissions['own'.$suffix][$action];
                    if(is_array($user_return) && isset($user_return['own']) && $user_return['own']){
                        $db = JFactory::getDBO();
                        
                        static $typeref;
                
                        if(is_array($typeref)){
                            $typerefid = $typeref[intval($user_return['form_id'])];
                        }else{
                            $db->setQuery("Select `type`, `reference_id` From #__contentbuilder_forms Where id = " . intval($user_return['form_id']));
                            $typerefid = $db->loadAssoc();
                            $typeref[intval($user_return['form_id'])] = $typerefid;
                        }
                        if(is_array($typerefid)){
                            $form = self::getForm($typerefid['type'], $typerefid['reference_id']);
                            if($form && (!isset($user_return['record_id']))){
                                $allowed = true;
                            }else{
                                if(is_array($user_return['record_id'])){
                                    foreach($user_return['record_id'] As $recid){
                                       $db->setQuery("Select session_id From #__contentbuilder_records Where `record_id` = ".$db->Quote($recid)." And `type` = ".$db->Quote($typerefid['type'])." And `reference_id` = ".$db->Quote($typerefid['reference_id'])."");
                                       $session_id = $db->loadResult();
                                       if($form && $session_id != JFactory::getSession()->getId() && !$form->isOwner(JFactory::getUser()->get('id',0), $recid)){
                                            $allowed = false;
                                            break;
                                        } else {
                                            $allowed = true;
                                        }
                                    }
                                }else{
                                    
                                    $db->setQuery("Select session_id From #__contentbuilder_records Where `record_id` = ".$db->Quote($user_return['record_id'])." And `type` = ".$db->Quote($typerefid['type'])." And `reference_id` = ".$db->Quote($typerefid['reference_id'])."");
                                    $session_id = $db->loadResult();
                                    
                                    if($form && ( $user_return['record_id'] == false || $session_id == JFactory::getSession()->getId() || ( $form->isOwner(JFactory::getUser()->get('id',0), $user_return['record_id']) ) ) ){
                                        $allowed = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if(!$allowed){
               if(!$auth){
                //JError::raiseError(403, $error_msg);
                JFactory::getApplication()->redirect('index.php', $error_msg, 'error');
               }else{
                   return false;
               }
            }
        }
        if($auth){
            return true;
        }
    }
    
    public static function authorize($action){
        return self::checkPermissions($action, '', '', true);
    }
    
    public static function authorizeFe($action){
        return self::checkPermissions($action, '', '_fe', true);
    }
    
    public static function getListStates($id){
        $db = JFactory::getDBO();
        $db->setQuery("Select * From #__contentbuilder_list_states where form_id = " . intval($id) . " And published = 1 Order By id");
        $list_states = $db->loadAssocList();
        return $list_states;
    }
    
    public static function getStateColors($items, $id){
        $out = array();
        $db = JFactory::getDBO();
        $imp = '';
        $itemcnt = count($items);
        $i = 0;
        foreach($items As $item){
            $imp .= $db->Quote($item->colRecord).($i+1 < $itemcnt ? ',' : '');
            $i++;
        }
        if($imp){
            $db->setQuery("Select states.color, records.record_id From #__contentbuilder_list_states As states, #__contentbuilder_list_records As records Where states.published = 1 And states.id = records.state_id And records.record_id In (".$imp.") And records.form_id = ".intval($id)." And states.form_id = " . intval($id));
            $colors = $db->loadAssocList();
            foreach($colors As $color){
                $out[$color['record_id']] = $color['color'];
            }
        }
        return $out;
    }
    
    public static function getStateTitles($items, $id){
        $out = array();
        $db = JFactory::getDBO();
        $imp = '';
        $itemcnt = count($items);
        $i = 0;
        foreach($items As $item){
            $imp .= $db->Quote($item->colRecord).($i+1 < $itemcnt ? ',' : '');
            $i++;
        }
        if($imp){
            $db->setQuery("Select states.title, records.record_id From #__contentbuilder_list_states As states, #__contentbuilder_list_records As records Where states.published = 1 And states.id = records.state_id And records.record_id In (".$imp.") And records.form_id = ".intval($id)." And states.form_id = " . intval($id));
            $colors = $db->loadAssocList();
            foreach($colors As $color){
                $out[$color['record_id']] = $color['title'];
            }
        }
        return $out;
    }
    
    public static function getRecordsPublishInfo($items, $type, $reference_id){
        $out = array();
        $db = JFactory::getDBO();
        if($reference_id){
            
            $imp = '';
            $itemcnt = count($items);
            $i = 0;
            
            foreach($items As $item){
                $imp .= $db->Quote($item->colRecord).($i+1 < $itemcnt ? ',' : '');
                $i++;
            }
            
            if($imp){
                $db->setQuery("Select records.published, records.record_id From #__contentbuilder_records As records Where `type` = ".$db->Quote($type)." And reference_id = ".$db->Quote($reference_id)." And records.record_id In (".$imp.")");
                $published = $db->loadAssocList();
                foreach($published As $publish){
                    $out[$publish['record_id']] = $publish['published'];
                }
            }
        }
        return $out;
    }
    
    public static function getRecordsLanguage($items, $type, $reference_id){
        $out = array();
        $db = JFactory::getDBO();
        if($reference_id){
            
            $imp = '';
            $itemcnt = count($items);
            $i = 0;
            
            foreach($items As $item){
                $imp .= $db->Quote($item->colRecord).($i+1 < $itemcnt ? ',' : '');
                $i++;
            }
            
            if($imp){
                $db->setQuery("Select records.lang_code, records.record_id From #__contentbuilder_records As records Where reference_id = ".$db->Quote($reference_id)." And records.record_id In (".$imp.")");
                $codes = $db->loadAssocList();
                foreach($codes As $code){
                    $out[$code['record_id']] = $code['lang_code'];
                }
            }
        }
        return $out;
    }
}