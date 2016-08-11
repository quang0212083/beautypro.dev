<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class contentbuilder_com_contentbuilder{

    public $properties = null;
    public $elements = null;
    public $view_elements = null;
    private $total = 0;
    private $bytable = false;
    public $exists = false;
    
    
    function __construct($id){
        $db = JFactory::getDBO();
        
        $db->setQuery("Select * From #__contentbuilder_storages Where id = ".intval($id)." And published = 1 Order By `ordering`");
        $this->properties = $db->loadObject();
        if($this->properties instanceof stdClass){
            $this->exists = true;
            $this->bytable = $this->properties->bytable == 1 ? '' : '#__';
            
            $db->setQuery("Select * From #__contentbuilder_storage_fields Where storage_id = ".intval($id)." And published = 1 Order By `ordering`");
            $this->elements = $db->loadAssocList();
        }
    }
    
    public function synchRecords(){
        if(!is_object($this->properties)) return;
        
        $db = JFactory::getDBO();
        $db->setQuery("
            
                Select r.id
                
                From 
                
                ".$this->bytable.$this->properties->name." As r
                
                Where r.id Not In (
                    Select record_id From #__contentbuilder_records As cr Where cr.`type` = 'com_contentbuilder' And cr.reference_id = '".intval($this->properties->id)."' And cr.record_id = r.id
                ) 
        ");
        
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            $reference_ids = $db->loadColumn();
        }else{
            $reference_ids = $db->loadResultArray();
        }
        
        if(is_array($reference_ids)){
            foreach($reference_ids As $reference_id){
                $db->setQuery("Select `id` From #__contentbuilder_records Where `type` = 'com_contentbuilder' And `reference_id` = " . intval($this->properties->id) . ' And `record_id` = ' . intval($reference_id));
                $res = $db->loadResult();
                if(!$res){
                    $db->setQuery("Insert Into #__contentbuilder_records (`type`,`record_id`,`reference_id`) Values ('com_contentbuilder','".intval($reference_id)."', '".intval($this->properties->id)."')");
                    $db->query();
                }
            }
        }
    }
    
    public static function getNumRecordsQuery($form_id, $user_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select `name`,`bytable` From #__contentbuilder_storages Where id = " . intval($form_id));
        $res = $db->loadAssoc();
        $res['bytable'] = $res['bytable'] == 1 ? '' : '#__';
        if(is_array($res)){
            return 'Select count(id) From '.$res['bytable'].$res['name'].' Where user_id = ' . intval($user_id);
        }
        return '';
    }
    
    public function getUniqueValues($element_id, $where_field = '', $where = ''){
        $db = JFactory::getDBO();
        $db->setQuery("Select `name` From #__contentbuilder_storage_fields Where id = ".intval($element_id)." And storage_id = ".intval($this->properties->id)." And published = 1 Order By `ordering`");
        $name = $db->loadResult();
        $where_add = '';
        if($where_field != '' && $where != ''){
            $db->setQuery("Select `name` From #__contentbuilder_storage_fields Where id = ".intval($where_field)." And storage_id = ".intval($this->properties->id)." And published = 1 Order By `ordering`");
            $where_name = $db->loadResult();
            if($where_name){
                $where_add = " And `".$where_name."` = " . $db->Quote($where) . " ";
            }
        }
        if($name){
            $db->setQuery("Select Distinct `".$name."` From ".$this->bytable.$this->properties->name . " Where `".$name."` <> '' ".$where_add." Order By `".$name."`");
            jimport('joomla.version');
            $version = new JVersion();
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                return $db->loadColumn();
            }else{
                return $db->loadResultArray();
            }
        }
        return array();
    }
    
    public function getAllElements(){
        $db = JFactory::getDBO();
        $db->setQuery("Select * From #__contentbuilder_storage_fields Where storage_id = ".intval($this->properties->id)." And published = 1 Order By `ordering`");
        $e = $db->loadAssocList();
        $elements = array();
        if($e){
            foreach($e As $element){
                $elements[$element['id']] = $element['name'];
            }
        }
        return $elements;
    }

    public function getReferenceId(){
        if($this->properties){
            return $this->properties->id;
        }
        return 0;
    }

    public function getTitle(){
        if($this->properties){
            return $this->properties->title . ' ('.$this->properties->name.')';
        }
        return '';
    }

    public function getRecordMetadata($record_id){
         $data = new stdClass();
         $db = JFactory::getDBO();
         $db->setQuery("Select metakey, metadesc, author, robots, rights, xreference From #__contentbuilder_records Where `type` = 'com_contentbuilder' And reference_id = ".$db->Quote($this->properties->id)." And record_id = " . $db->Quote($record_id));
         $metadata = $db->loadObject();
         
         $data->metadesc = '';
         $data->metakey = '';
         $data->author = '';
         $data->rights = '';
         $data->robots = '';
         $data->xreference = '';
         if($metadata){
             $data->metadesc = $metadata->metadesc;
             $data->metakey = $metadata->metakey;
             $data->author = $metadata->author;
             $data->rights = $metadata->rights;
             $data->robots = $metadata->robots;
             $data->xreference = $metadata->xreference;
         }
         $obj = null;
         try{
            $db->setQuery("Select * From ".$this->bytable.$this->properties->name." Where id = " . $record_id);
            $obj = $db->loadObject();
         }catch(Exception $e){
             
         }
         $data->created_id = 0;
         $data->created = '';
         $data->created_by = '';
         $data->modified_id = 0;
         $data->modified = '';
         $data->modified_by = '';
         if($obj){
            $data->created_id = $obj->user_id;
            $data->created = $obj->created;
            $data->created_by = strpos($this->bytable,'#__') !== 0 ? '' : $obj->created_by;
            $data->modified_id = $obj->modified_user_id;
            $data->modified = $obj->modified;
            $data->modified_by = strpos($this->bytable,'#__') !== 0 ? '' : $obj->modified_by;
         }
         return $data;
    }

    public function getRecord(
            $record_id,
            $published_only = false,
            $own_only = -1,
            $show_all_languages = false
    ){

        $db = JFactory::getDBO();

        $i = 0;
        $elSize = count($this->elements);
        $selectors = '';
        foreach($this->elements As $element){
            $selectors .= "r.`".$element['name']."` As `col".$element['id']."Value`".($i + 1 < $elSize ? ',' : '');
            $i++;
        }

        $db->setQuery("
            Select
                ".($selectors ? $selectors . ',' : '')."
                joined_records.rating_sum / joined_records.rating_count As colRating,
                joined_records.rating_count As colRatingCount,
                joined_records.rating_sum As colRatingSum
            From
                ".$this->bytable.$this->properties->name." As r
                ".($published_only || !$show_all_languages || $show_all_languages ? " Left Join #__contentbuilder_records As joined_records On ( joined_records.`type` = 'com_contentbuilder' And joined_records.record_id = r.id And joined_records.reference_id = r.storage_id ) " : "")."
                
            Where
                r.id = " . $db->Quote(intval($record_id)) . " And
                joined_records.`type` = 'com_contentbuilder'
                ".(!$show_all_languages ? " And ( joined_records.sef = ".$db->Quote(JRequest::getCmd('lang',''))." Or joined_records.sef = '' Or joined_records.sef is Null ) " : '')."
                ".($show_all_languages ? " And ( joined_records.id is Null Or joined_records.id Is Not Null ) " : '')."
                ".(intval($own_only) > -1 ? ' And r.user_id='.intval($own_only) . ' ' : '')."
                ".($published_only ? " And joined_records.published = 1 " : '')."
            And
                r.storage_id = ".$this->properties->id."
        ");

        $out = array();
        $colValues = $db->loadAssoc();

        if($colValues){
            $i = 0;
            foreach($this->elements As $element){
                $out[$i] = new stdClass();
                $out[$i]->recElementId = $element['id'];
                $out[$i]->recTitle = $element['title'];
                $out[$i]->recName = $element['name'];
                $out[$i]->recType = '';
                $out[$i]->recRating = $colValues['colRating'];
                $out[$i]->recRatingCount = $colValues['colRatingCount'];
                $out[$i]->recRatingSum = $colValues['colRatingSum'];
                $out[$i]->recValue = '';
                if(isset($colValues['col'.$element['id'].'Value'])){
                    $out[$i]->recValue = $colValues['col'.$element['id'].'Value'];
                }
                $i++;
            }
        }
        return $out;
    }

    public function getListRecords(
            array $ids,
            $filter = '',
            $searchable_elements = array(),
            $limitstart = 0,
            $limit = 0,
            $order = '',
            $order_types = array(),
            $order_Dir = 'asc',
            $record_id = 0,
            $published_only = false,
            $own_only = -1,
            $state = 0,
            $published = -1,
            $init_order_by = -1,
            $init_order_by2 = -1,
            $init_order_by3 = -1,
            $force_filter = array(),
            $show_all_languages = false,
            $lang_code = null,
            $act_as_registration = array(),
            $form = null,
            $article_category_filter = -1
    ){

        if(!count($ids)){
            return array();
        }

        $db = JFactory::getDBO();

        $selectors = '';
        $bottom = '';
        $names = array();
        foreach($this->elements As $element){
            // filtering the ids above, we have them already, but we need all the other fields,
            // so we can search for their values from the fontend
            if(!in_array($element['id'], $ids)){
                $bottom .= "r.`".$element['name']."` As `col".$element['id']."`,";
            }
            $names[$element['id']] = $element['name'];
        }
        
        // we want the visible ids on top, so they will be shown as supposed, as the list view will filter out the hidden ones
        foreach($ids As $id){
            if(!isset($act_as_registration[$id])){
                $selectors .= "r.`".$names[$id]."` As `col".$id."`,";
            }else{
                switch($act_as_registration[$id]){
                    case 'registration_name_field':
                        $selectors .= "joined_users.`name` As `col".$id."`,";
                        break;
                    case 'registration_email_field':
                        $selectors .= "joined_users.`email` As `col".$id."`,";
                        break;
                    case 'registration_username_field':
                        $selectors .= "joined_users.`username` As `col".$id."`,";
                        break;
                }
            }
        }
        
        $selectors = $selectors.$bottom;
        $selectors = rtrim($selectors, ',');

        ///////////////
        // preparing the search
        $strlen = 0;
        if(function_exists('mb_strlen')){
            $strlen = mb_strlen($filter);
        } else {
            $strlen = strlen($filter);
        }

        $search = '';
        if($strlen > 0 && $strlen <= 1000){
            $length = count($searchable_elements);
            $search .= "( (colRecord = " . $db->Quote($filter) . ") ";
            $search .= " Or ( ( r.created_by Like ".$db->Quote('%'.$filter.'%')." ) ) ";
            $search .= " Or ( ( r.modified_by Like ".$db->Quote('%'.$filter.'%')." ) ) ";
            if($strlen > 2){
                foreach($searchable_elements As $searchable_element){
                    if(!$form->filter_exact_match){
                        $limited = explode('|',str_replace(' ','|', $filter));
                        $limited_count = count($limited);
                        $limited_count = $limited_count > 10 ? 10 : $limited_count;
                        for($x = 0; $x < $limited_count; $x++){
                            $search .= " Or (`col".intval($searchable_element)."` Like  " . $db->Quote('%'.$limited[$x].'%') . ") ";
                        }
                    } else {
                        $search .= " Or (`col".intval($searchable_element)."` Like " . $db->Quote('%'.$filter.'%') . ") ";
                    }
                }
            }
            $search .= ' ) ';
        }
        
        foreach($force_filter As $filter_record_id => $terms){
            
            if($cnt = count($terms)){
            
                if($search){
                    $search .= ' And ';
                }

                $search .= '( ';
                
                if(count($terms) == 3 && strtolower($terms[0]) == '@range'){
                    
                    $ex = explode('to', $terms[2]);
                    
                    switch(trim(strtolower($terms[1]))){
                        case 'number':
                            if(count($ex) == 2){
                               if(trim($ex[0])){
                                $search .= '(Convert(Trim(`col'.intval($filter_record_id).'`),  Decimal) >= ' . $db->Quote(trim($ex[0])) . ' And Convert(Trim(`col'.intval($filter_record_id).'`), Decimal) <= ' . $db->Quote(trim($ex[1])) . ')'; 
                               }else{
                                $search .= '(Convert(Trim(`col'.intval($filter_record_id).'`), Decimal) <= ' . $db->Quote(trim($ex[1])) . ')'; 
                               }
                            }
                            else if(count($ex) > 0){
                               $search .= '(Convert(Trim(`col'.intval($filter_record_id).'`),  Decimal) >= ' . $db->Quote(trim($ex[0])) . ' )'; 
                             
                            }
                            break;
                        case 'date':
                            if(count($ex) == 2){
                                
                                //if(trim($ex[0])){
                                //    $search .= '(Convert(Trim(`col'.intval($filter_record_id).'`),  Datetime) >= ' . $db->Quote(trim($ex[0])) . ' And Convert(Trim(`col'.intval($filter_record_id).'`), Datetime) <= ' . $db->Quote(trim($ex[1])) . ')'; 
                                //}else{
                                //    $search .= '(Convert(Trim(`col'.intval($filter_record_id).'`), Datetime) <= ' . $db->Quote(trim($ex[1])) . ')'; 
                                //}
                                
                                if(trim($ex[0])){
                                    if ($db->Quote(trim($ex[1])) == "''") {
                                        $search .= '(Convert(Trim(`col' . intval($filter_record_id) . '`), Datetime) >= ' . $db->Quote(trim($ex[0])) . ')';
                                    } else {
                                        $search .= '(Convert(Trim(`col' . intval($filter_record_id) . '`), Datetime) >= ' . $db->Quote(trim($ex[0])) . ' And Convert(Trim(`col' . intval($filter_record_id) . '`), Datetime) <= ' . $db->Quote(trim($ex[1])) . ')';
                                    }
                                } else {
                                    $search .= '(Convert(Trim(`col' . intval($filter_record_id) . '`), Datetime) <= ' . $db->Quote(trim($ex[1])) . ')';
                                }
                            }
                            else if(count($ex) > 0){
                               $search .= '(Convert(Trim(`col'.intval($filter_record_id).'`),  Datetime) >= ' . $db->Quote(trim($ex[0])) . ' )'; 
                             
                            }
                            break;
                    }
                    
                }
                else if(count($terms) == 2 && strtolower($terms[0]) == '@match'){
                    
                    $ex = explode(';', $terms[1]);
                    $size = count($ex);
                    $i = 0;
                    foreach($ex As $groupval){
                       $search .= ' ( Trim(`col'.intval($filter_record_id).'`) Like '.$db->Quote('%'.trim($groupval).'%').' ) '; 
                       if($i + 1 < $size){
                          $search .= ' Or '; 
                       }
                       $i++;
                    }
                    
                }
                else{
                    $i = 0;
                    foreach($terms As $term){
                        $search .= 'Trim(`col'.intval($filter_record_id).'`) Like ' . $db->Quote(trim($term));
                        if($i + 1 < $cnt){
                            $search .= ' Or ';
                        }
                        $i++;
                    }
                }

                $search .= ')';
            }
        }
        
        if($search){
            $search = ' Having (' . $search . ') ';
        }
        //////////////////
        
        /// CASTING FOR BEING ABLE TO SORT THE WAY DEDIRED
        if(isset($order_types[$order])){
            switch($order_types[$order]){
                case 'CHAR':
                    $order = " Cast(`".$order."` As Char) ";
                    break;
                case 'DATETIME':
                    $order = " Cast(`".$order."` As Datetime) ";
                    break;
                case 'DATE':
                    $order = " Cast(`".$order."` As Date) ";
                    break;
                case 'TIME':
                    $order = " Cast(`".$order."` As Time) ";
                    break;
                case 'UNSIGNED':
                    $order = " Cast(`".$order."` As Unsigned) ";
                    break;
                case 'DECIMAL':
                    $order = " Cast(`".$order."` As Decimal(64,5)) ";
                    break;
                default:
                    $order = " `".$order."` ";
            }
        }else if($order){
            $order = " `".$order."` ";
        }
        
        if(isset($order_types[$init_order_by]) && $init_order_by != -1){
            switch($order_types[$init_order_by]){
                case 'CHAR':
                    $init_order_by = " Cast(`".$init_order_by."` As Char) ";
                    break;
                case 'DATETIME':
                    $init_order_by = " Cast(`".$init_order_by."` As Datetime) ";
                    break;
                case 'DATE':
                    $init_order_by = " Cast(`".$init_order_by."` As Date) ";
                    break;
                case 'TIME':
                    $init_order_by = " Cast(`".$init_order_by."` As Time) ";
                    break;
                case 'UNSIGNED':
                    $init_order_by = " Cast(`".$init_order_by."` As Unsigned) ";
                    break;
                case 'DECIMAL':
                    $init_order_by = " Cast(`".$init_order_by."` As Decimal(64,5)) ";
                    break;
                default:
                    $init_order_by = " `".$init_order_by."` ";
            }
        }else if($init_order_by != -1){
            $init_order_by = " `".$init_order_by."` ";
        }
        
        if($init_order_by2 != -1){
            $init_order_by2 = " `".$init_order_by2."` ";
        }
       
        if($init_order_by3 != -1){
            $init_order_by3 = " `".$init_order_by3."` ";
        }
        
        // SORT CASTING END
        
        $db->setQuery("
            Select
                SQL_CALC_FOUND_ROWS
                joined_records.published As colPublished,
                joined_records.rating_sum / joined_records.rating_count As colRating,
                joined_records.rating_count As colRatingCount,
                joined_records.rating_sum As colRatingSum,
                joined_records.rand_date As colRand,
                r.id As colRecord,
                ".($selectors ? $selectors . ',' : '')."
                joined_articles.article_id As colArticleId,
                r.created_by As colAuthor,
                r.modified_by As colModifiedBy
            From
                (
                    ".$this->bytable.$this->properties->name." As r,
                    #__contentbuilder_records As joined_records
                )
                
                Left Join (
                    #__contentbuilder_articles As joined_articles,
                    #__contentbuilder_forms As forms,
                    #__content As content
                ) On (
                    joined_articles.`type` = 'com_contentbuilder' And
                    joined_articles.reference_id = ".$this->properties->id." And
                    joined_records.reference_id = joined_articles.reference_id And
                    joined_records.record_id = joined_articles.record_id And
                    joined_records.`type` = joined_articles.`type` And
                    joined_articles.form_id = forms.id And
                    joined_articles.article_id = content.id And
                    (content.state = 1 Or content.state = 0)
                )
                ".(count($act_as_registration) ? '
                Left Join (
                    #__users As joined_users
                ) On (
                    r.user_id = joined_users.id
                )' : '' )."
                
                ".(intval($state) > 0 ? ", #__contentbuilder_list_records As list" : "")."
                Where
                ".(intval($published) == 0 ? "(joined_records.published Is Null Or joined_records.published = 0) And" : "")."
                ".(intval($published) == 1 ? "joined_records.published = 1 And" : "")."
                ".($record_id ? ' r.id = ' . $db->Quote($record_id) . ' And ' : '')."
                ".($article_category_filter > -1 ? ' content.catid = ' . intval($article_category_filter) . ' And ' : '')."
                joined_records.reference_id = r.storage_id And
                joined_records.record_id = r.id And
                joined_records.`type` = 'com_contentbuilder'
                ".(!$show_all_languages ? " And ( joined_records.sef = ".$db->Quote(JRequest::getCmd('lang',''))." Or joined_records.sef = '' Or joined_records.sef is Null ) " : '')."
                ".($show_all_languages ? " And ( joined_records.id is Null Or joined_records.id Is Not Null ) " : '')."
                ".($lang_code !== null ? " And joined_records.lang_code = ".$db->Quote($lang_code) : '')."
                ".(intval($own_only) > -1 ? ' And r.user_id='.intval($own_only) . ' ' : '')."
                ".(intval($state) > 0 ? " And list.record_id = r.id And list.state_id = " . intval($state) : "")."
                ".($published_only ? " And joined_records.published = 1 " : '')."
            
        Group By r.id $search  ".($order ? " Order By ".($order == 'colRating' && $form !== null && $form->rating_slots == 1 ? 'colRatingCount' : $order)." " : ' Order By '.($init_order_by == -1 ? 'colRecord' : $init_order_by).' '.($init_order_by2 == -1 ? '' : ',' . $init_order_by2).' '.($init_order_by3 == -1 ? '' : ',' . $init_order_by3).' '.( $order_Dir ? ( strtolower($order_Dir) == 'asc' ? 'asc' : 'desc' ) : 'asc' ).' ')." ".( $order ? ( strtolower($order_Dir) == 'asc' ? 'asc' : 'desc') : '' )."
        ", $limitstart, $limit );
        
        $return = $db->loadObjectList();
        //echo $db->getErrorMsg();
        //exit;
        $db->setQuery('SELECT FOUND_ROWS();');
        $this->total = $db->loadResult();
        return $return;
    }

    public function getListRecordsTotal(array $ids, $filter = '', $searchable_elements = array()){
        if(!count($ids)){
            return 0;
        }
        return $this->total;
    }

    public function getElements(){
        $elements = array();
        if($this->elements){
            foreach($this->elements As $element){
                $elements[$element['id']] = $element['title'] . ' ('.$element['name'].')';
            }
        }
        return $elements;
    }

    public function getElementNames(){
        $elements = array();
        if($this->elements){
            foreach($this->elements As $element){
                $elements[$element['id']] = $element['name'];
            }
        }
        return $elements;
    }

    public function getElementLabels(){
        $elements = array();
        if($this->elements){
            foreach($this->elements As $element){
                $elements[$element['id']] = $element['title'];
            }
        }
        return $elements;
    }

    public function getPageTitle(){
        return $this->properties->title;
    }

    public static function getFormsList(){
        $list = array();
        $db = JFactory::getDBO();
        $db->setQuery("Select `id`,`title`,`name` From #__contentbuilder_storages Where published = 1 Order By `ordering`");
        $rows = $db->loadAssocList();
        foreach($rows As $row){
            $list[$row['id']] = $row['title'] . ' ('.$row['name'].')';
        }
        return $list;
    }
    
    /**
     *
     * NEW AS OF Content Builder
     * 
     */
    
    public function isGroup($element_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select is_group From #__contentbuilder_storage_fields Where id = " . intval($element_id));
        $result = $db->loadResult();
        
        if($result){
            return true;
        }
        
        return false;
    }
    
    public function getGroupDefinition($element_id){
        $return = array();
        $db = JFactory::getDBO();
        $db->setQuery("Select group_definition From #__contentbuilder_storage_fields Where id = " . intval($element_id));
        $result = $db->loadResult();
        if($result){
            
            $result = self::execPHP($result);
            
            $lines = explode("\n", str_replace("\r",'',$result));
            foreach($lines As $line){
                $cols = explode(";", $line);
                if(count($cols) == 2){
                    $return[$cols[1]] = $cols[0];
                }
            }
            return $return;
        }
        return array();
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
    
    public function saveRecordUserData($record_id, $user_id, $fullname, $username){
        if( intval($user_id) <= 0 ){
            return;
        }
        $db = JFactory::getDBO();
        $db->setQuery("Update ".$this->bytable.$this->properties->name." Set user_id = " . intval($user_id) . ", created_by = " . $db->Quote($fullname) . " Where id = " . $db->Quote($record_id));
        $db->query();
    }
    
    public function saveRecord($record_id, array $cleaned_values){
        $record_id = intval($record_id);
        $db = JFactory::getDBO();
        $insert_id = 0;
        $user_id = 0;
        $username = '';
        $user_full_name = '';
        $names = array();
        
        foreach($this->elements As $element){
            if(isset($cleaned_values[$element['id']])){
                $names[$element['id']] = array('name' => $element['name'], 'value' => '');
            }
        }
        
        if(JFactory::getUser()->get('id',0) > 0){
            $username = JFactory::getUser()->get('username','');
            $user_full_name = JFactory::getUser()->get('name','');
            $user_id = JFactory::getUser()->get('id',0);
        }
        
        $date = JFactory::getDate();
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            $now = $date->toSql();
        }else{
            $now = $date->toMySQL();
        }
        
        foreach($cleaned_values As $id => $value){
            
            $outVal = '';
            
            $isGroup = $this->isGroup($id);
            
            if(!$isGroup){
                
                if(is_array($value)){
                    $value = implode(', ', $value);
                }
                
                $outVal = $value;
                
            } else {
                
                if(!is_array($value)){
                    $ex = explode(',', $value);
                    $value = array();
                    foreach($ex As $content){
                        $value[] = trim($content);
                    }
                }
                
                $groupdef = $this->getGroupDefinition($id);
                
                foreach($groupdef As $groupval => $grouplabel){
                    if(in_array($groupval, $value)){
                        $outVal .= $groupval . ', ';
                    }
                }
            }
            
            $outVal = rtrim($outVal);
            
            $names[$id]['value'] = rtrim($outVal,',');
        }
        
        if(!$record_id){
           
           $the_keys = '';
           $the_values = '';
           $cnt = count($names);
           
           $i = 0;
           foreach($names As $id => $keys){
               $the_keys   .= '`'.$keys['name'].'`'.($i+1<$cnt ? ',' : '');
               $the_values .= $db->Quote($keys['value']).($i+1<$cnt ? ',' : '');
               $i++;
           }
           
           if($the_keys){
               $the_keys = ','.$the_keys;
           }
           
           if($the_values){
               $the_values = ','.$the_values;
           }
            
           $db->setQuery("Insert Into ".$this->bytable.$this->properties->name." (
                `created`,
                `user_id`,
                `created_by`
                $the_keys
            ) Values (
                '".$now."',
                ".$db->Quote($user_id).",
                ".$db->Quote($user_full_name)."
                $the_values
            )");
           $db->query();
           $record_id = $db->insertid();
           
        } else {
           
           $the_values = '';
           $cnt = count($names);
           
           $i = 0;
           foreach($names As $id => $keys){
               $the_values .= '`'.$keys['name'].'` = '.$db->Quote($keys['value']).($i+1<$cnt ? ',' : '');
               $i++;
           }
           
           if($the_values){
               $the_values = ','.$the_values;
           }
           
           $db->setQuery("Update ".$this->bytable.$this->properties->name." Set
               `modified` = '".$now."',
               `modified_user_id` = ".$db->Quote($user_id).",
               `modified_by` = ".$db->Quote($user_full_name)."
               $the_values
               Where
               id = $record_id
           ");
           $db->query();
        }
        
        return $record_id;
    }
    
    function delete($items, $form_id){
        $db = JFactory::getDBO();
        JArrayHelper::toInteger($items);
        if(count($items)){
            $db->setQuery("Select reference_id From #__contentbuilder_elements Where `type` = 'upload' And form_id = " . intval($form_id));
            
            jimport('joomla.version');
            $version = new JVersion();
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $refs = $db->loadColumn();
            }else{
                $refs = $db->loadResultArray();
            }
            if(count($refs)){
                $db->setQuery("Select `name` From #__contentbuilder_storage_fields Where id In (".implode(',',$refs).")");
                jimport('joomla.version');
                $version = new JVersion();
                if(version_compare($version->getShortVersion(), '3.0', '>=')){
                    $names = $db->loadColumn();
                }else{
                    $names = $db->loadResultArray();
                }
                if(count($names)){
                    $_names = '';
                    foreach($names As $name){
                        $_names .= "`".$name."`,";
                    }
                    $_names = rtrim($_names, ',');
                    if($_names != ''){
                        jimport('joomla.filesystem.file');
                        $db->setQuery("Select $_names From ".$this->bytable.$this->properties->name." Where id In (".implode(',',$items).")");
                        $upload_fields = $db->loadAssocList();
                        $length = count($upload_fields);
                        for($i = 0; $i < $length; $i++){
                            foreach($upload_fields[$i] As $_value){
                                if(strpos(strtolower($_value), '{cbsite}') === 0){
                                    $_value = str_replace(array('{cbsite}','{CBSite}'), array(JPATH_SITE, JPATH_SITE), $_value);
                                }
                                if(JFile::exists($_value)){
                                    JFile::delete($_value);
                                }
                            }
                        }
                    }
                }
            }
            $db->setQuery("Delete From ".$this->bytable.$this->properties->name." Where id In (".implode(',',$items).")");
            $db->query();
        }
        return true;
    }
    
    function isOwner($user_id, $record_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select id From ".$this->bytable.$this->properties->name." Where id = " . intval($record_id) . " And user_id = " . intval($user_id));
        return $db->loadResult() !== null ? true : false;
    }
}