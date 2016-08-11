<?php
/**
 * @package     ContentBuilder
 * @author      Markus Bopp
 * @link        http://www.crosstec.de
 * @license     GNU/GPL
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

function contentbuilder_is_valid_date($value, $format = 'YYYY-mm-dd'){
    
    $format = $format = str_replace('%', '', $format);
    
    if(strlen($value) >= 4 && strlen($format)){

        // find separator. Remove all other characters from $format
        $separator_only = str_replace(array('m','d','y'),'', strtolower($format));
        $separator = $separator_only[0]; // separator is first character

        $separator_only2 = preg_replace("/[0-9]/",'', strtolower($value));
        $separator2 = $separator_only2[0]; // separator is first character
        
        if($separator && strlen($separator_only) == 2){
            
            $value_exploded  = explode($separator2, $value);
            $format_exploded = explode($separator, $format);
            
            $yearindex = 0;
            $monthindex = 0;
            $dayindex = 0;
            
            $i = 0;
            foreach($format_exploded As $form){
                if(strstr(strtolower($form), 'y') !== false){
                    $yearindex = $i;
                }
                if(strstr(strtolower($form), 'm') !== false){
                    $monthindex = $i;
                }
                if(strstr(strtolower($form), 'd') !== false){
                    $dayindex = $i;
                }
                $i++;
            }
            
            if(!is_numeric($value_exploded[$monthindex])){
                return false;
            }
            
            if(!is_numeric($value_exploded[$dayindex])){
                return false;
            }
            
            if(!is_numeric($value_exploded[$yearindex])){
                return false;
            }
            
            if(@checkdate($value_exploded[$monthindex], $value_exploded[$dayindex], $value_exploded[$yearindex])){
                return true;
            }
        }
    }
    return false;
}
function contentbuilder_convert_date($value, $srcFormat = 'YYYY-mm-dd', $format = 'YYYY-mm-dd'){
    
    $format    = str_replace('%', '', $format);
    $srcFormat = str_replace('%', '', $srcFormat);
    
    if(strlen($value) >= 4 && strlen($format)){

        // find separator. Remove all other characters from $format
        $separator_only = str_replace(array('m','d','y'),'', strtolower($format));
        $separator = $separator_only[0]; // separator is first character

        $separator_only2 = preg_replace("/[0-9]/",'', strtolower($value));
        $separator2 = $separator_only2[0]; // separator is first character
        
        $separator_only3 = str_replace(array('m','d','y'),'', strtolower($srcFormat));
        $separator3 = $separator_only2[0]; // separator is first character
        
        if($separator && strlen($separator_only) == 2){
            
            $value_exploded  = explode($separator2, $value);
            $format_exploded = explode($separator, $format);
            $srcformat_exploded = explode($separator3, $srcFormat);
            
            $srcyearindex = 0;
            $srcmonthindex = 0;
            $srcdayindex = 0;
            
            $yearindex = 0;
            $monthindex = 0;
            $dayindex = 0;
            
            $i = 0;
            foreach($srcformat_exploded As $form){
                if(strstr(strtolower($form), 'y') !== false){
                    $srcyearindex = $i;
                }
                if(strstr(strtolower($form), 'm') !== false){
                    $srcmonthindex = $i;
                }
                if(strstr(strtolower($form), 'd') !== false){
                    $srcdayindex = $i;
                }
                $i++;
            }
            
            $i = 0;
            foreach($format_exploded As $form){
                if(strstr(strtolower($form), 'y') !== false){
                    $yearindex = $i;
                }
                if(strstr(strtolower($form), 'm') !== false){
                    $monthindex = $i;
                }
                if(strstr(strtolower($form), 'd') !== false){
                    $dayindex = $i;
                }
                $i++;
            }
            
            if(!is_numeric($value_exploded[$srcmonthindex])){
                return $value;
            }
            
            if(!is_numeric($value_exploded[$srcdayindex])){
                return $value;
            }
            
            if(!is_numeric($value_exploded[$srcyearindex])){
                return $value;
            }
            
            if(strlen(intval($value_exploded[$srcyearindex])) < 4){
                $yearlen = strlen(intval($value_exploded[$srcyearindex]));
                if($yearlen == 3){
                    $value_exploded[$srcyearindex] = '0'.intval($value_exploded[$srcyearindex]);
                }
                else if($yearlen == 2){
                    $value_exploded[$srcyearindex] = '00'.intval($value_exploded[$srcyearindex]);
                }
                else if($yearlen == 1){
                    $value_exploded[$srcyearindex] = '000'.intval($value_exploded[$srcyearindex]);
                }
            }
            
            if(strlen(intval($value_exploded[$srcmonthindex])) < 2){
                $yearlen = strlen(intval($value_exploded[$srcmonthindex]));
                if($yearlen == 1){
                    $value_exploded[$srcmonthindex] = '0'.intval($value_exploded[$srcmonthindex]);
                }
            }
            
            if(strlen(intval($value_exploded[$srcdayindex])) < 2){
                $yearlen = strlen(intval($value_exploded[$srcdayindex]));
                if($yearlen == 1){
                    $value_exploded[$srcdayindex] = '0'.intval($value_exploded[$srcdayindex]);
                }
            }
            
            
            $out_value_exploded = array();
            
            $out_value_exploded[intval($yearindex)] = $value_exploded[$srcyearindex];
            $out_value_exploded[intval($monthindex)] = $value_exploded[$srcmonthindex];
            $out_value_exploded[intval($dayindex)] = $value_exploded[$srcdayindex];
            
            ksort($out_value_exploded);
            
            $out = '';
            foreach($out_value_exploded As $valex){
                $out .= $valex.$separator;
            }
            
            $out = rtrim($out, $separator);
            
            return $out;
        }
    }
    return $value;
}
/**
 * includes a scands chars fix from user jajusain
 * 
 * POST:
 * http://crosstec.de/en/forums/37-contentbuilder-general-forum/63712-scands-bug.html?limit=6&start=12#63770
 *
 * @param type $path
 * @return boolean 
 */

function contentbuilder_is_url($url=FALSE) {
    $info = parse_url($url);
    return ((isset($info['scheme']) && $info['scheme']=='http')||(isset($info['scheme'])&&$info['scheme']=='https')||(isset($info['scheme'])&&$info['scheme']=='ftp'))&&isset($info['host'])&&$info['host']!="";
}


function contentbuilder_is_internal_path($path){
    
    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');
    
    if(strpos(strtolower($path), '{cbsite}') === 0){
        $path = str_replace(array('{cbsite}','{CBSite}'), array(JPATH_SITE, JPATH_SITE), $path);
    }
    
    if(contentbuilder_is_url($path)){
        return false;
    }
    
    if(  trim($path) && ( @realpath( $path ) !== false || strpos(strtolower($path), strtolower(JPATH_SITE)) === 0 ) && strpos($path,DS) !== false ){
        return true;
    }
    
    return false;
}
function cbinternal($value){
    $nl = '';
    $out = '';
    $values = explode("\n", $value);
    $length = count($values);
    $i = 0;
    foreach($values As $_value){
       if($i+1 < $length){
           $nl = "\n";
       }else{
           $nl = '';
       }
       $out .= ( contentbuilder_is_internal_path($_value) ? basename($_value) : $_value ) . $nl;
       $i++;
    }
    
    return $out;
}
function contentbuilder_is_email ($email, $checkDNS = false) {
	//      Check that $email is a valid address
	//              (http://tools.ietf.org/html/rfc3696)
	//              (http://tools.ietf.org/html/rfc2822)
	//              (http://tools.ietf.org/html/rfc5322#section-3.4.1)
	//              (http://tools.ietf.org/html/rfc5321#section-4.1.3)
	//              (http://tools.ietf.org/html/rfc4291#section-2.2)
	//              (http://tools.ietf.org/html/rfc1123#section-2.1)

	//      the upper limit on address lengths should normally be considered to be 256
	//              (http://www.rfc-editor.org/errata_search.php?rfc=3696)
	if (strlen($email) > 256)       return false;   //      Too long

	//      Contemporary email addresses consist of a "local part" separated from
	//      a "domain part" (a fully-qualified domain name) by an at-sign ("@").
	//              (http://tools.ietf.org/html/rfc3696#section-3)
	$index = strrpos($email,'@');

	if ($index === false)           return false;   //      No at-sign
	if ($index === 0)                       return false;   //      No local part
	if ($index > 64)                        return false;   //      Local part too long

	$localPart              = substr($email, 0, $index);
	$domain                 = substr($email, $index + 1);
	$domainLength   = strlen($domain);

	if ($domainLength === 0)        return false;   //      No domain part
	if ($domainLength > 255)        return false;   //      Domain part too long

	//      Let's check the local part for RFC compliance...
	//
	//      local-part      =       dot-atom / quoted-string / obs-local-part
	//      obs-local-part  =       word *("." word)
	//              (http://tools.ietf.org/html/rfc2822#section-3.4.1)
	if (preg_match('/^"(?:.)*"$/', $localPart) > 0) {
		$dotArray[]     = $localPart;
	} else {
		$dotArray       = explode('.', $localPart);
	}

	foreach ($dotArray as $localElement) {
		//      Period (".") may...appear, but may not be used to start or end the
		//      local part, nor may two or more consecutive periods appear.
		//              (http://tools.ietf.org/html/rfc3696#section-3)
		//
		//      A zero-length element implies a period at the beginning or end of the
		//      local part, or two periods together. Either way it's not allowed.
		if ($localElement === '')                                                                               return false;   //      Dots in wrong place

		//      Each dot-delimited component can be an atom or a quoted string
		//      (because of the obs-local-part provision)
		if (preg_match('/^"(?:.)*"$/', $localElement) > 0) {
			//      Quoted-string tests:
			//
			//      Note that since quoted-pair
			//      is allowed in a quoted-string, the quote and backslash characters may
			//      appear in a quoted-string so long as they appear as a quoted-pair.
			//              (http://tools.ietf.org/html/rfc2822#section-3.2.5)
			$groupCount     = preg_match_all('/(?:^"|"$|\\\\\\\\|\\\\")|(\\\\|")/', $localElement, $matches);
			array_multisort($matches[1], SORT_DESC);
			if ($matches[1][0] !== '')                                                                      return false;   //      Unescaped quote or backslash character inside quoted string
			if (preg_match('/^"\\\\*"$/', $localElement) > 0)                       return false;   //      "" and "\" are slipping through - note: must tidy this up
		} else {
			//      Unquoted string tests:
			//
			//      Any ASCII graphic (printing) character other than the
			//      at-sign ("@"), backslash, double quote, comma, or square brackets may
			//      appear without quoting.  If any of that list of excluded characters
			//      are to appear, they must be quoted
			//              (http://tools.ietf.org/html/rfc3696#section-3)
			//
			$stripped = '';
			//      Any excluded characters? i.e. <space>, @, [, ], \, ", <comma>
			if (preg_match('/[ @\\[\\]\\\\",]/', $localElement) > 0)
			//      Check all excluded characters are escaped
			$stripped = preg_replace('/\\\\[ @\\[\\]\\\\",]/', '', $localElement);
			if (preg_match('/[ @\\[\\]\\\\",]/', $stripped) > 0)    return false;   //      Unquoted excluded characters
		}
	}

	//      Now let's check the domain part...

	//      The domain name can also be replaced by an IP address in square brackets
	//              (http://tools.ietf.org/html/rfc3696#section-3)
	//              (http://tools.ietf.org/html/rfc5321#section-4.1.3)
	//              (http://tools.ietf.org/html/rfc4291#section-2.2)
	if (preg_match('/^\\[(.)+]$/', $domain) === 1) {
		//      It's an address-literal
		$addressLiteral = substr($domain, 1, $domainLength - 2);
		$matchesIP              = array();

		//      Extract IPv4 part from the end of the address-literal (if there is one)
		if (preg_match('/\\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $addressLiteral, $matchesIP) > 0) {
			$index = strrpos($addressLiteral, $matchesIP[0]);

			if ($index === 0) {
				//      Nothing there except a valid IPv4 address, so...
				return true;
			} else {
				//      Assume it's an attempt at a mixed address (IPv6 + IPv4)
				if ($addressLiteral[$index - 1] !== ':')                        return false;   //      Character preceding IPv4 address must be ':'
				if (substr($addressLiteral, 0, 5) !== 'IPv6:')          return false;   //      RFC5321 section 4.1.3

				$IPv6 = substr($addressLiteral, 5, ($index ===7) ? 2 : $index - 6);
				$groupMax = 6;
			}
		} else {
			//      It must be an attempt at pure IPv6
			if (substr($addressLiteral, 0, 5) !== 'IPv6:')                  return false;   //      RFC5321 section 4.1.3
			$IPv6 = substr($addressLiteral, 5);
			$groupMax = 8;
		}

		$groupCount     = preg_match_all('/^[0-9a-fA-F]{0,4}|\\:[0-9a-fA-F]{0,4}|(.)/', $IPv6, $matchesIP);
		$index          = strpos($IPv6,'::');

		if ($index === false) {
			//      We need exactly the right number of groups
			if ($groupCount !== $groupMax)                                                  return false;   //      RFC5321 section 4.1.3
		} else {
			if ($index !== strrpos($IPv6,'::'))                                             return false;   //      More than one '::'
			$groupMax = ($index === 0 || $index === (strlen($IPv6) - 2)) ? $groupMax : $groupMax - 1;
			if ($groupCount > $groupMax)                                                    return false;   //      Too many IPv6 groups in address
		}

		//      Check for unmatched characters
		array_multisort($matchesIP
		[1], SORT_DESC);
		if ($matchesIP[1][0] !== '')                                                            return false;   //      Illegal characters in address

		//      It's a valid IPv6 address, so...
		return true;
	} else {
		//      It's a domain name...

		//      The syntax of a legal Internet host name was specified in RFC-952
		//      One aspect of host name syntax is hereby changed: the
		//      restriction on the first character is relaxed to allow either a
		//      letter or a digit.
		//              (http://tools.ietf.org/html/rfc1123#section-2.1)
		//
		//      NB RFC 1123 updates RFC 1035, but this is not currently apparent from reading RFC 1035.
		//
		//      Most common applications, including email and the Web, will generally not permit...escaped strings
		//              (http://tools.ietf.org/html/rfc3696#section-2)
		//
		//      Characters outside the set of alphabetic characters, digits, and hyphen MUST NOT appear in domain name
		//      labels for SMTP clients or servers
		//              (http://tools.ietf.org/html/rfc5321#section-4.1.2)
		//
		//      RFC5321 precludes the use of a trailing dot in a domain name for SMTP purposes
		//              (http://tools.ietf.org/html/rfc5321#section-4.1.2)
		$matches        = array();
		$groupCount     = preg_match_all('/(?:[0-9a-zA-Z][0-9a-zA-Z-]{0,61}[0-9a-zA-Z]|[a-zA-Z])(?:\\.|$)|(.)/', $domain, $matches);
		$level          = count($matches[0]);

		if ($level == 1)                                                                                        return false;   //      Mail host can't be a TLD

		$TLD = $matches[0][$level - 1];
		if (substr($TLD, strlen($TLD) - 1, 1) === '.')                          return false;   //      TLD can't end in a dot
		if (preg_match('/^[0-9]+$/', $TLD) > 0)                                         return false;   //      TLD can't be all-numeric

		//      Check for unmatched characters
		array_multisort($matches[1], SORT_DESC);
		if ($matches[1][0] !== '')                                                                      return false;   //      Illegal characters in domain, or label longer than 63 characters

		//      Check DNS?
		if ($checkDNS && function_exists('checkdnsrr')) {
			if (!(checkdnsrr($domain, 'A') || checkdnsrr($domain, 'MX'))) {
				return false;   //      Domain doesn't actually exist
			}
		}

		//      Eliminate all other factors, and the one which remains must be the truth.
		//              (Sherlock Holmes, The Sign of Four)
		return true;
	}
}
if(!function_exists('mb_wordwrap')){
    function mb_wordwrap($str, $width=74, $break="\r\n")
    {
        // Return short or empty strings untouched
        if(empty($str) || mb_strlen($str, 'UTF-8') <= $width)
            return $str;

        $br_width  = mb_strlen($break, 'UTF-8');
        $str_width = mb_strlen($str, 'UTF-8');
        $return = '';
        $last_space = false;

        for($i=0, $count=0; $i < $str_width; $i++, $count++)
        {
            // If we're at a break
            if (mb_substr($str, $i, $br_width, 'UTF-8') == $break)
            {
                $count = 0;
                $return .= mb_substr($str, $i, $br_width, 'UTF-8');
                $i += $br_width - 1;
                continue;
            }

            // Keep a track of the most recent possible break point
            if(mb_substr($str, $i, 1, 'UTF-8') == " ")
            {
                $last_space = $i;
            }

            // It's time to wrap
            if ($count > $width)
            {
                // There are no spaces to break on!  Going to truncate :(
                if(!$last_space)
                {
                    $return .= $break;
                    $count = 0;
                }
                else
                {
                    // Work out how far back the last space was
                    $drop = $i - $last_space;

                    // Cutting zero chars results in an empty string, so don't do that
                    if($drop > 0)
                    {
                        $return = mb_substr($return, 0, -$drop);
                    }

                    // Add a break
                    $return .= $break;

                    // Update pointers
                    $i = $last_space + ($br_width - 1);
                    $last_space = false;
                    $count = 0;
                }
            }

            // Add character from the input string to the output
            $return .= mb_substr($str, $i, 1, 'UTF-8');
        }
        return $return;
    }
}
if(function_exists('mb_strlen')){
    function contentbuilder_wordwrap($str, $width = 75, $break = "\n", $cut = false, $charset = null){
        return mb_wordwrap($str, $width, $break, $cut, $charset);
    }
}else{
    function contentbuilder_wordwrap($str, $width = 75, $break = "\n", $cut = false, $charset = null){
        return wordwrap($str, $width, $break, $cut);
    }
}

class contentbuilder_helpers{

    public static function listIncludeInList($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->list_include ? $imgY : $imgX;
        $task = $row->list_include ? 'no_list_include' : 'list_include';
        $alt = $row->list_include ? JText::_('COM_CONTENTBUILDER_LIST_INCLUDED') : JText::_('COM_CONTENTBUILDER_NO_LIST_INCLUDED');
        $action = $row->list_include ? JText::_('COM_CONTENTBUILDER_NO_LIST_INCLUDE') : JText::_('COM_CONTENTBUILDER_LIST_INCLUDE');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }
        return $href;
    }

    public static function listIncludeInSearch($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->search_include ? $imgY : $imgX;
        $task = $row->search_include ? 'no_search_include' : 'search_include';
        $alt = $row->search_include ? JText::_('COM_CONTENTBUILDER_SEARCH_INCLUDED') : JText::_('COM_CONTENTBUILDER_NO_SEARCH_INCLUDED');
        $action = $row->search_include ? JText::_('COM_CONTENTBUILDER_NO_SEARCH_INCLUDE') : JText::_('COM_CONTENTBUILDER_SEARCH_INCLUDE');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }

        return $href;
    }

    public static function listLinkable($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->linkable ? $imgY : $imgX;
        $task = $row->linkable ? 'not_linkable' : 'linkable';
        $alt = $row->linkable ? JText::_('COM_CONTENTBUILDER_LINKABLE') : JText::_('COM_CONTENTBUILDER_NOT_LINKABLE');
        $action = $row->linkable ? JText::_('COM_CONTENTBUILDER_NOT_LINKABLE') : JText::_('COM_CONTENTBUILDER_LINKABLE');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }

        return $href;
    }

    public static function listEditable($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->editable ? $imgY : $imgX;
        $task = $row->editable ? 'not_editable' : 'editable';
        $alt = $row->editable ? JText::_('COM_CONTENTBUILDER_EDITABLE') : JText::_('COM_CONTENTBUILDER_NOT_EDITABLE');
        $action = $row->editable ? JText::_('COM_CONTENTBUILDER_NOT_EDITABLE') : JText::_('COM_CONTENTBUILDER_EDITABLE');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }

        return $href;
    }
    
    public static function listVerifiedView($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->verified_view ? $imgY : $imgX;
        $task = $row->verified_view ? 'not_verified_view' : 'verified_view';
        $alt = $row->verified_view ? JText::_('COM_CONTENTBUILDER_VERIFIED_VIEW') : JText::_('COM_CONTENTBUILDER_VERIFIED_VIEW');
        $action = $row->verified_view ? JText::_('COM_CONTENTBUILDER_VERIFIED_VIEW') : JText::_('COM_CONTENTBUILDER_VERIFIED_VIEW');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }

        return $href;
    }
    
    public static function listVerifiedNew($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->verified_new ? $imgY : $imgX;
        $task = $row->verified_new ? 'not_verified_new' : 'verified_new';
        $alt = $row->verified_new ? JText::_('COM_CONTENTBUILDER_VERIFIED_NEW') : JText::_('COM_CONTENTBUILDER_VERIFIED_NEW');
        $action = $row->verified_new ? JText::_('COM_CONTENTBUILDER_VERIFIED_NEW') : JText::_('COM_CONTENTBUILDER_VERIFIED_NEW');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }

        return $href;
    }
    
    public static function listVerifiedEdit($row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {

        $img = $row->verified_edit ? $imgY : $imgX;
        $task = $row->verified_edit ? 'not_verified_edit' : 'verified_edit';
        $alt = $row->verified_edit ? JText::_('COM_CONTENTBUILDER_VERIFIED_EDIT') : JText::_('COM_CONTENTBUILDER_VERIFIED_EDIT');
        $action = $row->verified_edit ? JText::_('COM_CONTENTBUILDER_VERIFIED_EDIT') : JText::_('COM_CONTENTBUILDER_VERIFIED_EDIT');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = '
                    <a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
                    <img src="templates/'.JFactory::getApplication()->getTemplate().'/images/admin/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }else{
            $href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $prefix . $task . '\')" title="' . $action . '">
		<img src="images/' . $img . '" border="0" alt="' . $alt . '" /></a>'
            ;
        }

        return $href;
    }
    
    public static function publishButton($published, $url_publish, $url_unpublish, $imgY = 'tick.png', $imgX = 'publish_x.png', $allowed = true) {

        $img = $published ? $imgY : $imgX;
        $url = $published ? $url_unpublish : $url_publish;
        $alt = $published ? JText::_('PUBLISH') : JText::_('UNPUBLISH');
        $action = $published ? JText::_('PUBLISH') : JText::_('UNPUBLISH');

        jimport('joomla.version');
        $version = new JVersion();
        if(version_compare($version->getShortVersion(), '1.6', '>=')){
            $href = ($allowed ? '<a href="'.$url.'" title="' . $action . '">' : '').'
                     <img src="'.JURI::root(true).'/components/com_contentbuilder/images/_' . $img . '" border="0" alt="' . $alt . '" />'.
                    ($allowed ? '</a>' : '')
            ;
        }else{
            $href = ($allowed ? '<a href="'.$url.'" title="' . $action . '">' : '').
                    '<img src="'.JURI::root(true).'/components/com_contentbuilder/images/' . $img . '" border="0" alt="' . $alt . '" />'.
                    ($allowed ? '</a>' : '')
            ;
        }

        return $href;
    }

}

