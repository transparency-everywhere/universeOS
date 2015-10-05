<?php

include('classes/class_user.php');
include('classes/class_debug.php');
  $userid = getUser();
  $time = time();

function filename_safe($name) { 
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|'); 
    return str_replace($except, '', $name); 
}


function chmod_R($path, $filemode, $dirmode) { 
    if (is_dir($path) ) { 
        if (!chmod($path, $dirmode)) { 
            $dirmode_str=decoct($dirmode); 
            print "Failed applying filemode '$dirmode_str' on directory '$path'\n"; 
            print "  `-> the directory '$path' will be skipped from recursive chmod\n"; 
            return; 
        } 
        $dh = opendir($path); 
        while (($file = readdir($dh)) !== false) { 
            if($file != '.' && $file != '..') {  // skip self and parent pointing directories 
                $fullpath = $path.'/'.$file; 
                chmod_R($fullpath, $filemode,$dirmode); 
            } 
        } 
        closedir($dh); 
    } else { 
        if (is_link($path)) { 
            print "link '$path' is skipped\n"; 
            return; 
        } 
        if (!chmod($path, $filemode)) { 
            $filemode_str=decoct($filemode); 
            print "Failed applying filemode '$filemode_str' on file '$path'\n"; 
            return; 
        } 
    } 
}

  
function sanitizeText($data){
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
                // Remove really unwanted tags
                $old_data = $data;
                $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

        // we are done...
        return $data;
}
  
/**
  * Validates if SESSION of last generated Captcha is equal to the submitted value
  *
  * @param string $value      Contains a user-provided query.
  *
  * @return bool Contains the returned rows from the query.
  */
function validateCapatcha($value){
     
     $sessionValue = $_SESSION['lastCaptcha'];
	 
        //define crypt
        $value = sha1($value);
        $value = sha1("$value deine mutter lutscht riesengrosse schwaenze");
        
	if($sessionValue === sha1($value)){
            return true;
        }
	
 }
 
function commaToOr($string, $type){
        //converts Strings with Values, which are separeted with commas into SQL conform STRINGS
        $string = explode(";", $string);
        foreach($string as &$value){
            if(empty($deddl)){
                $return = "$type='$value'";
                $deddl = "checked";
            }else{
            
            $return = "$type='$value' OR $return";

            }
        }
        return $return;
    }
    
function shorten($text, $chars_limit)
{
    // Check if length is larger than the character limit
    if (strlen($text) > $chars_limit)
    {
        // If so, cut the string at the character limit
        $new_text = substr($text, 0, $chars_limit);
        // Trim off white space
        $new_text = trim($new_text);
        // Add at end of text ...
        return $new_text . "...";
    }
    // If not just return the text as is
    else
    {
    return $text;
    }
}
function array_delete($array, $element) {
    return array_diff($array, array($element));
}

function jsonRemoveUnicodeSequences($struct) {
   return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
}

/** 
* Clean comments of json content and decode it with json_decode(). 
* Work like the original php json_decode() function with the same params 
* 
* @param   string  $json    The json string being decoded 
* @param   bool    $assoc   When TRUE, returned objects will be converted into associative arrays. 
* @param   integer $depth   User specified recursion depth. (>=5.3) 
* @param   integer $options Bitmask of JSON decode options. (>=5.4) 
* @return  string 
*/ 
function json_clean_decode($json, $assoc = false, $depth = 512, $options = 0) { 

    // search and remove comments like /* */ and // 
    $json = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)#", '', $json);

    if(version_compare(phpversion(), '5.4.0', '>=')) { 
        $json = json_decode($json, $assoc, $depth, $options); 
    } 
    elseif(version_compare(phpversion(), '5.3.0', '>=')) { 
        $json = json_decode($json, $assoc, $depth); 
    } 
    else { 
        $json = json_decode($json, $assoc); 
    } 

    return $json; 
}


include('classes/class_api.php');

include('classes/class_image.php');
 
include('classes/class_xml.php');

include('classes/class_rss.php');

include('classes/class_salt.php');

include('classes/class_gui.php');

include('classes/class_messages.php');

include('classes/class_userPrivacy.php');

   
//fav
//fav
//fav
include('classes/class_fav.php');

//personal Events
//personal Events
//personal Events
include('classes/class_personalEvents.php');
    
//comments
//comments
//comments	
include('classes/class_comments.php');

//groups
//groups
//groups
include('classes/class_groups.php');

//basic universe stuff
include('classes/class_item.php');

include('classes/class_buddylist.php');

include('classes/class_feed.php');

include('classes/class_universe.php');
   
include('classes/class_db.php');
    
include('classes/class_privacy.php');
 
include('classes/class_playlists.php');
    
include('classes/class_files.php');

include('classes/class_youtube.php');
    
include('classes/class_links.php');
	
include('classes/class_shortcuts.php');

include('classes/class_folder.php');

include('classes/class_element.php');

include('classes/class_fileSystem.php');

include('classes/class_UFF.php');

include('classes/class_contextMenu.php');

include('classes/class_dashboard.php');

include('classes/class_signatures.php');

include('classes/class_sessionHashes.php');

include('classes/class_sec.php');

include('classes/class_events.php');

include('classes/class_tasks.php');

include('classes/class_im.php');

include('classes/class_handlers.php');




?>