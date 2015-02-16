<?php

include('classes/class_user.php');
include('classes/class_debug.php');
  $userid = getUser();
  $time = time();

  
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
include('classes/class_playlists_new.php');
    
include('classes/class_files.php');

include('classes/class_youtube.php');
    
include('classes/class_links.php');
	
include('classes/class_internLink.php');

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




?>