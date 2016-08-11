<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class contentbuilder_com_breezingforms{

    public $properties = null;
    public $elements = null;
    private $total = 0;
    public $exists = false;
    
    function __construct($id, $published = true){
        $db = JFactory::getDBO();
        
        $db->setQuery("SET SESSION group_concat_max_len = 9999999");
        $db->query();
        
        $db->setQuery("Select * From #__facileforms_forms Where id = ".intval($id)." ".($published ? 'And published = 1' : '')." Order By `ordering`");
        $this->properties = $db->loadObject();
        if($this->properties instanceof stdClass){
            $this->exists = true;
            $db->setQuery("Select * From #__facileforms_elements Where `type` <> 'Sofortueberweisung' And `type` <> 'PayPal' And `type` <> 'Static Text/HTML' And `type` <> 'Rectangle' And `type` <> 'Image' And `type` <> 'Tooltip' And `type` <> 'Query List' And `type` <> 'Icon' And `type` <> 'Graphic Button' And `type` <> 'Regular Button' And `type`<> 'Unknown' And `type` <> 'Summarize' And `type` <> 'ReCaptcha' And form = ".intval($id)." And published = 1 Order By `ordering`");
            $this->elements = $db->loadAssocList();
            $elements = array();
            
            $radio_buttons = array();
            foreach($this->elements As $element){
                if (!isset($radio_buttons[$element['name']])) {
                    $radio_buttons[$element['name']] = true;
                    $elements[] = $element;
                }
            }
           
            $this->elements = $elements;
        }
    }
    
    public function synchRecords(){
        
        if(!is_object($this->properties)) return;
        
        $db = JFactory::getDBO();
        $db->setQuery("Select r.`id` 
                From 
                (
                    #__facileforms_records As r,
                    #__contentbuilder_forms As f
                )
                Left Join 
                (
                    #__contentbuilder_records As cr
                ) 
                On 
                (
                    r.form = '".intval($this->properties->id)."' And
                    f.`reference_id` = r.form And
                    cr.`type` = 'com_breezingforms' And
                    cr.`reference_id` = r.form And
                    cr.record_id = r.id
                )
                Where
                f.`type` = 'com_breezingforms' And
                f.`reference_id` = '".intval($this->properties->id)."' And
                r.form = f.`reference_id` And
                cr.`record_id` Is Null");
        
        
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            $reference_ids = $db->loadColumn();
        }else{
            $reference_ids = $db->loadResultArray();
        }
        
        if(is_array($reference_ids)){
            foreach($reference_ids As $reference_id){
                $db->setQuery("Select `id` From #__contentbuilder_records Where `type` = 'com_breezingforms' And `reference_id` = " . intval($this->properties->id) . ' And `record_id` = ' . intval($reference_id));
                $res = $db->loadResult();
                if(!$res){
                    $db->setQuery("Insert Into #__contentbuilder_records (`type`,`record_id`,`reference_id`) Values ('com_breezingforms','".intval($reference_id)."', '".intval($this->properties->id)."')");
                    $db->query();
                }
            }
        }
    }
    
    public static function getNumRecordsQuery($form_id, $user_id){
        return 'Select count(id) From #__facileforms_records Where form = '.intval($form_id).' And user_id = ' . intval($user_id);
    }
    
    public function getUniqueValues($element_id, $where_field = '', $where = ''){
        $db = JFactory::getDBO();
        $where_add = '';
        if($where_field != '' && $where != ''){
           $db->setQuery("Select Distinct s.`record` From #__facileforms_subrecords As s, #__facileforms_records As r Where r.form = ".$this->properties->id." And r.id = s.record And s.`element` = ".intval($where_field)." And s.`value` <> '' And s.`value` = ".$db->Quote($where)."  Order By s.`value`");
           
           jimport('joomla.version');
           $version = new JVersion();
           if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $l = $db->loadColumn();
           }else{
                $l = $db->loadResultArray();
           }
           
           if(count($l)){
            $where_fields = '';
            foreach($l As $ll){
                $where_fields .= $db->Quote($ll).',';
            }
            $where_fields = rtrim($where_fields,',');
            $where_add = " And r.`id` In (".$where_fields.") ";
           }
        }
        $db->setQuery("Select Distinct s.`value` From #__facileforms_subrecords As s, #__facileforms_records As r Where r.form = ".$this->properties->id." And r.id = s.record And s.`element` = ".intval($element_id)." And s.`value` <> '' $where_add  Order By s.`value`");
        
        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '3.0', '>=')){
            return $db->loadColumn();
        }else{
            return $db->loadResultArray();
        }
    }
    
    public function getAllElements(){
        $db = JFactory::getDBO();
        $db->setQuery("Select * From #__facileforms_elements Where form = ".intval($this->properties->id)." And published = 1 Order By `ordering`");
        $e = $db->loadAssocList();
        $elements = array();
        if($e){
            foreach($e As $element){
                if($element['name'] != 'bfFakeName'  &&
                   $element['name'] != 'bfFakeName2' &&
                   $element['name'] != 'bfFakeName3' &&
                   $element['name'] != 'bfFakeName4' &&
                   $element['name'] != 'bfFakeName5' &&
                   $element['name'] != 'bfFakeName6' )
                {
                    $elements[$element['id']] = $element['name'];
                }
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
         
         $db->setQuery("Select metakey, metadesc, author, robots, rights, xreference From #__contentbuilder_records Where `type` = 'com_breezingforms' And reference_id = ".$db->Quote($this->properties->id)." And record_id = " . $db->Quote($record_id));
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
         
         try{
            $db->setQuery("Select * From #__facileforms_records Where id = " . $record_id);
            $obj = $db->loadObject();
         }catch(Exception $e){
             $obj = null;
         }
         
         $data->created_id = 0;
         $data->created = '';
         $data->created_by = '';
         $data->modified_id = 0;
         $data->modified = '';
         $data->modified_by = '';
         if($obj){
            $data->created_id = $obj->user_id;
            $data->created = $obj->submitted;
            $data->created_by = $obj->user_full_name != '-' ? $obj->user_full_name : '';
            $data->modified_id = 0;
            $data->modified = '';
            $data->modified_by = '';
         }
         return $data;
    }

    public function getRecord(
            $record_id,
            $published_only = false,
            $own_only = -1,
            $show_all_languages = false
    ){
        if(!is_object($this->properties)) return array();
        $db = JFactory::getDBO();

        /////////////
        // we need all elements, so they will be searchable through having
        $db->setQuery("Select id, `title`, `name`, `type` From
                #__facileforms_elements
                Where
                form = ".$this->properties->id."
                And
                published = 1
                And
                `name` <> 'bfFakeName'
                And
                `name` <> 'bfFakeName2'
                And
                `name` <> 'bfFakeName3'
                And
                `name` <> 'bfFakeName4'
                And
                `name` <> 'bfFakeName5'
                And
                `name` <> 'bfFakeName6'
        ");
        $elements = $db->loadAssocList();
        /////////////

        /////////////
        // Swapping rows to columns
        $selectors = '';
        foreach($elements As $element){
            if($element['type'] == 'Radio Button' || $element['type'] == 'Checkbox'){
                $selectors .= "GROUP_CONCAT( ( Case When s.`name` = '{$element['name']}' Then s.`value` End ) Order By s.`id` SEPARATOR ', ' ) As `col{$element['id']}Value`,";
            }else{
                $selectors .= "GROUP_CONCAT( ( Case When s.`element` = '{$element['id']}' Then s.`value` End ) Order By s.`id` SEPARATOR ', ' ) As `col{$element['id']}Value`,";
            }
        }
        $selectors = rtrim($selectors, ',');
        ////////////

        $db->setQuery("
            Select
                ".($selectors ? $selectors . ',' : '')."
                joined_records.rating_sum / joined_records.rating_count As colRating,
                joined_records.rating_count As colRatingCount,
                joined_records.rating_sum As colRatingSum
            From
                #__facileforms_subrecords As s,
                #__facileforms_records As r
                ".($published_only || !$show_all_languages || $show_all_languages ? " Left Join #__contentbuilder_records As joined_records On ( joined_records.`type` = 'com_breezingforms' And joined_records.record_id = r.id And joined_records.reference_id = r.form ) " : "")."
                
            Where
                r.id = " . $db->Quote(intval($record_id)) . " And
                joined_records.`type` = 'com_breezingforms'
                ".(!$show_all_languages ? " And ( joined_records.sef = ".$db->Quote(JRequest::getCmd('lang',''))." Or joined_records.sef = '' Or joined_records.sef is Null ) " : '')."
                ".($show_all_languages ? " And ( joined_records.id is Null Or joined_records.id Is Not Null ) " : '')."
                ".(intval($own_only) > -1 ? ' And r.user_id='.intval($own_only) . ' ' : '')."
                ".($published_only ? " And joined_records.published = 1 " : '')."
            And
                r.form = ".$this->properties->id."
            And
                s.record = r.id
            And
                r.archived = 0
            Group By s.record
        ");

        $out = array();
        $colValues = null;
        try{
            $colValues = $db->loadAssoc();
        }catch(Exception $e){
            
        }

        if($colValues){
            $i = 0;
            foreach($elements As $element){
                $out[$i] = new stdClass();
                $out[$i]->recElementId = $element['id'];
                $out[$i]->recTitle = $element['title'];
                $out[$i]->recName = $element['name'];
                $out[$i]->recType = $element['type'];
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

        /////////////
        // we need all elements, so they will be searchable through having
        $db->setQuery("Select id, `type`, `name` From
                #__facileforms_elements
                Where
                form = ".$this->properties->id."
                And
                published = 1
                And
                `name` <> 'bfFakeName'
                And
                `name` <> 'bfFakeName2'
                And
                `name` <> 'bfFakeName3'
                And
                `name` <> 'bfFakeName4'
                And
                `name` <> 'bfFakeName5'
                And
                `name` <> 'bfFakeName6'
        ");
        $elements = $db->loadAssocList();
        /////////////

        /////////////
        // Swapping rows to columns
        $selectors = '';
        $bottom = '';
        $force = '';
        $radio_buttons = array();
        
        foreach($elements As $element){
            // filtering the ids above, we have them already, but we need all the other fields,
            // so we can search for their values from the fontend
            if(!in_array($element['id'], $ids)){
                
                /// CASTING FOR BEING ABLE TO SORT THE WAY DEDIRED
                // In BreezingForms, we have to cast on selection level, since casting in order by is not allowed
                $cast_open = '';
                $cast_close = '';
                
                if(isset($order_types['col'.$element['id']])){
                    switch($order_types['col'.$element['id']]){
                        case 'CHAR':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Char) ';
                            break;
                        case 'DATETIME':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Datetime) ';
                            break;
                        case 'DATE':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Date) ';
                            break;
                        case 'TIME':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Time) ';
                            break;
                        case 'UNSIGNED':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Unsigned) ';
                            break;
                        case 'DECIMAL':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Decimal(64,2)) ';
                            break;
                    }
                }
                $forcefield = false;
                if(isset($force_filter[$element['id']])){
                    $forcefield = true;
                }
                if($element['type'] == 'Checkbox' || $element['type'] == 'Checkbox Group' || $element['type'] == 'Select List'){
                    $radio_buttons[$element['id']] = $element['name'];
                    if(!$forcefield){
                        $bottom .= $cast_open."Trim( Both ', ' From GROUP_CONCAT( ( Case When s.`name` = '{$element['name']}' Then s.`value` Else '' End ) Order By s.`id` SEPARATOR ', ' ) )".$cast_close." As `col{$element['id']}`,";
                    }else{
                         $force .= $cast_open."Trim( Both ', ' From GROUP_CONCAT( ( Case When s.`name` = '{$element['name']}' Then s.`value` Else '' End ) Order By s.`id` SEPARATOR ', ' ) )".$cast_close." As `col{$element['id']}`,";
                    }
                }else{
                    if(!$forcefield){
                        $bottom .= $cast_open."max( case when s.`element` = '{$element['id']}' then s.`value` end )".$cast_close." As `col{$element['id']}`,";
                    }else{
                        $force .= $cast_open."max( case when s.`element` = '{$element['id']}' then s.`value` end )".$cast_close." As `col{$element['id']}`,";
                    }
                }
            }
        }
        
        // we want the visible ids on top, so they will be shown as supposed, as the list view will filter out the hidden ones
        foreach($ids As $id){
            
            if(!isset($act_as_registration[$id])){
                
                /// CASTING FOR BEING ABLE TO SORT THE WAY DEDIRED
                // In BreezingForms, we have to cast on selection level, since casting in order by is not allowed
                $cast_open = '';
                $cast_close = '';
                
                if(isset($order_types['col'.$id])){
                    switch($order_types['col'.$id]){
                        case 'CHAR':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Char) ';
                            break;
                        case 'DATETIME':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Datetime) ';
                            break;
                        case 'DATE':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Date) ';
                            break;
                        case 'TIME':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Time) ';
                            break;
                        case 'UNSIGNED':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Unsigned) ';
                            break;
                        case 'DECIMAL':
                            $cast_open = 'Cast(';
                            $cast_close = ' As Decimal(64,2)) ';
                            break;
                    }
                }
                
                $type = '';
                $name = '';
                foreach($elements As $element){
                    if($element['id'] == $id){
                        $type = $element['type'];
                        $name = $element['name'];
                        break;
                    }
                }
                
                if($type == 'Checkbox' || $type == 'Checkbox Group' || $type == 'Select List'){
                   $selectors .= $cast_open."Trim( Both ', ' From GROUP_CONCAT( ( Case When s.`name` = '$name' Then s.`value` Else '' End ) Order By s.`id` SEPARATOR ', ' ) )".$cast_close." As `col$id`,";
                }else{
                    $selectors .= $cast_open."max( case when s.`element` = '".intval($id)."' then s.`value` end )".$cast_close." As `col$id`,";
                }
                
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
        
        $selectors = $selectors.$force.($filter ? $bottom : '');
        $selectors = rtrim($selectors, ',');
        ////////////

        ///////////////
        // preparing the search, since we have a key/value storage, we must search by HAVING
        $strlen = 0;
        if(function_exists('mb_strlen')){
            $strlen = mb_strlen($filter);
        } else {
            $strlen = strlen($filter);
        }

        $search = '';
        if($filter && $strlen > 0 && $strlen <= 1000){
            $length = count($searchable_elements);
            $search .= "( (colRecord = " . $db->Quote($filter) . ") Or ";
            $search .= " ( (r.user_full_name = " . $db->Quote($filter) . ") ) ";
            if($strlen > 2){
                foreach($searchable_elements As $searchable_element){
                    if(!$form->filter_exact_match){
                        
                        $limited = explode('|',str_replace(' ','|', $filter));
                        $limited_count = count($limited);
                        $limited_count = $limited_count > 10 ? 10 : $limited_count;
                        for($x = 0; $x < $limited_count; $x++){
                            $search .= " Or (`col".intval($searchable_element)."` Like " . $db->Quote('%'.$limited[$x].'%') . ") ";
                        }
                    }else{
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
            $search = ' HAVING (' . $search . ') ';
        }
        //////////////////
        
        $db->setQuery("
            Select
                SQL_CACHE SQL_CALC_FOUND_ROWS
                ".(intval($published) > -1 ? "joined_records.published As colPublished," : "")."
                s.record As colRecord,
                joined_records.rating_sum / joined_records.rating_count As colRating,
                joined_records.rating_count As colRatingCount,
                joined_records.rating_sum As colRatingSum,
                joined_records.rand_date As colRand,
                ".($selectors ? $selectors . ',' : '')."
                joined_articles.article_id As colArticleId,
                r.user_full_name As colAuthor
            From
                (
                    #__facileforms_subrecords As s,
                    #__facileforms_records As r,
                    #__contentbuilder_records As joined_records
                )
                
                Left Join (
                    #__contentbuilder_articles As joined_articles,
                    #__contentbuilder_forms As forms,
                    #__content As content
                ) On (
                    joined_articles.`type` = 'com_breezingforms' And
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
                r.form = ".$this->properties->id." And
                ".($article_category_filter > -1 ? ' content.catid = ' . intval($article_category_filter) . ' And ' : '')."
                joined_records.reference_id = r.form And
                joined_records.record_id = r.id And
                joined_records.`type` = 'com_breezingforms'

                ".(!$show_all_languages ? " And ( joined_records.sef = ".$db->Quote(JRequest::getCmd('lang',''))." Or joined_records.sef = '' Or joined_records.sef is Null ) " : '')."
                ".($show_all_languages ? " And ( joined_records.id is Null Or joined_records.id Is Not Null ) " : '')."
                ".($lang_code !== null ? " And joined_records.lang_code = ".$db->Quote($lang_code) : '')."
                ".(intval($own_only) > -1 ? ' And r.user_id='.intval($own_only) . ' ' : '')."
                ".(intval($state) > 0 ? " And list.record_id = r.id And list.state_id = " . intval($state) : "")."
                ".($published_only ? " And joined_records.published = 1 " : '')."
                
            And
                s.record = r.id
            And
                r.archived = 0
            Group By s.record $search ".($order ? " Order By `".($order == 'colRating' && $form !== null && $form->rating_slots == 1 ? 'colRatingCount' : $order)."` " : ' Order By '.($init_order_by == -1 ? 'colRecord' : "`".$init_order_by."`" ).' '.($init_order_by2 == -1 ? '' : ',' . "`".$init_order_by2."`").' '.($init_order_by3 == -1 ? '' : ',' . "`".$init_order_by3."`").' '.( $order_Dir ? ( strtolower($order_Dir) == 'asc' ? 'asc' : 'desc') : 'asc' ).' ')." ".( $order ? ( strtolower($order_Dir) == 'asc' ? 'asc' : 'desc') : '' )."
        ", $limitstart, $limit  );
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
                if($element['name'] != 'bfFakeName'  &&
                   $element['name'] != 'bfFakeName2' &&
                   $element['name'] != 'bfFakeName3' &&
                   $element['name'] != 'bfFakeName4' &&
                   $element['name'] != 'bfFakeName5' &&
                   $element['name'] != 'bfFakeName6' )
                {
                    $elements[$element['id']] = $element['title'] . ' ('.$element['name'].')';
                }
            }
        }
        return $elements;
    }

    public function getElementNames(){
        $elements = array();
        if($this->elements){
            foreach($this->elements As $element){
                if($element['name'] != 'bfFakeName'  &&
                   $element['name'] != 'bfFakeName2' &&
                   $element['name'] != 'bfFakeName3' &&
                   $element['name'] != 'bfFakeName4' &&
                   $element['name'] != 'bfFakeName5' &&
                   $element['name'] != 'bfFakeName6' )
                {
                    $elements[$element['id']] = $element['name'];
                }
            }
        }
        return $elements;
    }

    public function getElementLabels(){
        $elements = array();
        if($this->elements){
            foreach($this->elements As $element){
                if($element['name'] != 'bfFakeName'  &&
                   $element['name'] != 'bfFakeName2' &&
                   $element['name'] != 'bfFakeName3' &&
                   $element['name'] != 'bfFakeName4' &&
                   $element['name'] != 'bfFakeName5' &&
                   $element['name'] != 'bfFakeName6' )
                {
                    $elements[$element['id']] = $element['title'];
                }
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
        $db->setQuery("Select `id`,`title`,`name` From #__facileforms_forms Where published = 1 Order By `ordering`");
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
        $db->setQuery("Select `type`, `flag1` From #__facileforms_elements Where id = " . intval($element_id));
        $result = $db->loadAssoc();
        if(is_array($result)){
            switch($result['type']){
                case 'Radio Group':
                case 'Radio Button':
                    return true;
                break;
                case 'Checkbox Group':
                case 'Checkbox':
                    return true;
                break;
                case 'Select List':
                    return true;
                break;
            }
        }
        
        return false;
    }
    
    public function getGroupDefinition($element_id){
        $return = array();
        $db = JFactory::getDBO();
        $db->setQuery("Select data2 From #__facileforms_elements Where `type` Not In ('Radio Button', 'Checkbox') And id = " . intval($element_id));
        $result = $db->loadResult();
        if($result){
            
            $result = self::execPHP($result);
            
            $lines = explode("\n", str_replace("\r",'',$result));
            foreach($lines As $line){
                $cols = explode(";", $line);
                if(count($cols) == 3){
                    $return[$cols[2]] = $cols[1];
                }
            }
            return $return;
        }else{
            $db->setQuery("Select `name` From #__facileforms_elements Where id = " . intval($element_id));
            $name = $db->loadResult();
            if($name){
                $db->setQuery("Select `data1` From #__facileforms_elements Where `type` In ('Radio Button', 'Checkbox') And name = " . $db->Quote(trim($name)));
                
                jimport('joomla.version');
                $version = new JVersion();
                if(version_compare($version->getShortVersion(), '3.0', '>=')){
                    $values = $db->loadColumn();
                }else{
                    $values = $db->loadResultArray();
                }
                
                foreach($values As $value){
                    $return[$value] = '';
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
        $db = JFactory::getDBO();
        $db->setQuery("Update #__facileforms_records Set user_id = " . intval($user_id) . ", username = " . $db->Quote($username) . ", user_full_name = " . $db->Quote($fullname) . " Where id = " . $db->Quote($record_id));
        $db->query();
    }
    
    public function saveRecord($record_id, array $cleaned_values){
        $record_id = intval($record_id);
        $db = JFactory::getDBO();
        $insert_id = 0;
        if(!$record_id){
            $username = '-';
            $user_full_name = '-';
            if(JFactory::getUser()->get('id',0) > 0){
                $username = JFactory::getUser()->get('username','');
                $user_full_name = JFactory::getUser()->get('name','');
            }
            jimport('joomla.environment.browser');
            $date = JFactory::getDate();
            jimport('joomla.version');
            $version = new JVersion();
            if(version_compare($version->getShortVersion(), '3.0', '<')){
                $now = $date->toMySQL();
            }else{
                $now = $date->toSql();
            }
            $db->setQuery("Insert Into #__facileforms_records (
                `submitted`,
                `form`,
                `title`,
                `name`,
                `ip`,
                `browser`,
                `opsys`,
                `user_id`,
                `username`,
                `user_full_name`
            ) Values (
                '".$now."',
                ".$db->Quote($this->properties->id).",
                ".$db->Quote($this->properties->title).",
                ".$db->Quote($this->properties->name).",
                ".$db->Quote($_SERVER['REMOTE_ADDR']).",
                ".$db->Quote(JBrowser::getInstance()->getAgentString()).",
                ".$db->Quote(JBrowser::getInstance()->getPlatform()).",
                ".$db->Quote(JFactory::getUser()->get('id',0)).",
                ".$db->Quote($username).",
                ".$db->Quote($user_full_name)."
            )");
           $db->query();
           $insert_id = $db->insertid();
        }
        foreach($cleaned_values As $id => $value){
            $isGroup = $this->isGroup($id);
            
            if(!is_array($value) && !$isGroup){
                if($insert_id){
                    $db->setQuery("Select `title`,`name`,`type` From #__facileforms_elements Where id = " . intval($id));
                    $the_element = $db->loadAssoc();
                    $db->setQuery(
                    "Insert Into #__facileforms_subrecords
                        (
                            `record`,
                            `value`,
                            `element`,
                            `title`,
                            `name`,
                            `type`
                        )
                        Values
                        (
                            $insert_id,
                            ".$db->Quote($value).",
                            ".$db->Quote($id).",
                            ".$db->Quote($the_element['title']).",
                            ".$db->Quote($the_element['name']).",
                            ".$db->Quote($the_element['type'])."
                        )"
                    );
                    $db->query();
                }else{
                    $db->setQuery("
                        Delete From 
                            #__facileforms_subrecords
                        Where
                            element = ".$db->Quote($id)."
                        And
                            record = ".$db->Quote(intval($record_id))."
                    ");
                    $db->query();
                    $db->setQuery("Select `title`,`name`,`type` From #__facileforms_elements Where id = " . intval($id));
                    $the_element = $db->loadAssoc();
                    $db->setQuery(
                    "Insert Into #__facileforms_subrecords
                        (
                            `record`,
                            `value`,
                            `element`,
                            `title`,
                            `name`,
                            `type`
                        )
                        Values
                        (
                            ".$db->Quote(intval($record_id)).",
                            ".$db->Quote($value).",
                            ".$db->Quote($id).",
                            ".$db->Quote($the_element['title']).",
                            ".$db->Quote($the_element['name']).",
                            ".$db->Quote($the_element['type'])."
                        )"
                    );
                    $db->query();
                }
                
            }else{
                if($insert_id){
                    $record_id = $insert_id;
                }
                // assuming comma seperated value if defined as group but no array based group value given
                if($isGroup && !is_array($value)){
                    $ex = explode(',', $value);
                    $value = array();
                    foreach($ex As $content){
                        $value[] = trim($content);
                    }
                }
                $del = array();
                $groupdef = $this->getGroupDefinition($id);
                $db->setQuery("Select `title`,`name`,`type` From #__facileforms_elements Where id = " . intval($id));
                $the_element = $db->loadAssoc();
                
                foreach($groupdef As $groupval => $grouplabel){
                    if(!in_array($groupval, $value)){
                        $del[] = $db->Quote($groupval);
                    }else{
                        $db->setQuery("Select id From #__facileforms_subrecords Where `value` = " . $db->Quote($groupval) ." And record = ".$db->Quote($record_id)." And element = " . $db->Quote($id));
                        $exists = $db->loadResult();
                        if(!$exists){
                            $db->setQuery("Insert Into #__facileforms_subrecords (`value`, record, element, `title`, `name`, `type`) Values (".$db->Quote($groupval).",".$db->Quote($record_id).",".$db->Quote($id).",".$db->Quote($the_element['title']).",".$db->Quote($the_element['name']).",".$db->Quote($the_element['type']).")");
                            $db->query();
                        }
                    }
                }
                if(count($del)){
                    $db->setQuery("Delete From #__facileforms_subrecords Where `value` In (" . implode(',',$del).") And record = ".$db->Quote($record_id)." And element = " . $db->Quote($id));
                    $db->query();
                }
                /**
                 * Restore the input order based on the group definition
                 */
                foreach($groupdef As $groupval => $grouplabel){
                    $db->setQuery("Select id From #__facileforms_subrecords Where `value` = " . $db->Quote($groupval) ." And record = ".$db->Quote($record_id)." And element = " . $db->Quote($id));
                    $old_id = $db->loadResult();
                    $db->setQuery("Select `title`,`name`,`type` From #__facileforms_elements Where id = " . intval($id));
                    $the_element = $db->loadAssoc();
                    if($old_id){
                        $db->setQuery("Insert Into #__facileforms_subrecords (`value`, record, element, `title`, `name`, `type`) Values (".$db->Quote($groupval).",".$db->Quote($record_id).",".$db->Quote($id).",".$db->Quote($the_element['title']).",".$db->Quote($the_element['name']).",".$db->Quote($the_element['type']).")");
                        $db->query();
                        $db->setQuery("Delete From #__facileforms_subrecords Where id = " . $old_id);
                        $db->query();
                    }
                }
            }
        }
        
        if($insert_id){
            return $insert_id;
        }
        return $record_id;
    }
    
    function delete($items, $form_id){
        $db = JFactory::getDBO();
        JArrayHelper::toInteger($items);
        if(count($items)){
            jimport('joomla.filesystem.file');
            $db->setQuery("Delete From #__facileforms_records Where id In (".implode(',',$items).")");
            $db->query();
            $db->setQuery("Select `value` From #__facileforms_subrecords Where `type` = 'File Upload' And record In (".implode(',',$items).")");
            
            jimport('joomla.version');
            $version = new JVersion();
            if(version_compare($version->getShortVersion(), '3.0', '>=')){
                $files = $db->loadColumn();
            }else{
                $files = $db->loadResultArray();
            }
            
            foreach($files As $file){
                $_values = explode("\n", $file);
                foreach($_values As $_value){
                    if(strpos(strtolower($_value), '{cbsite}') === 0){
                        $_value = str_replace(array('{cbsite}','{CBSite}'), array(JPATH_SITE, JPATH_SITE), $_value);
                    }
                    if(JFile::exists($_value)){
                        JFile::delete($_value);
                    }
                }
            }
            $db->setQuery("Delete From #__facileforms_subrecords Where record In (".implode(',',$items).")");
            $db->query();
        }
        return true;
    }
    
    function isOwner($user_id, $record_id){
        $db = JFactory::getDBO();
        $db->setQuery("Select id From #__facileforms_records Where id = " . intval($record_id) . " And user_id = " . intval($user_id));
        return $db->loadResult() !== null ? true : false;
    }
}