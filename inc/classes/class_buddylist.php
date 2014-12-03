<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class buddylist {
    
    function addBuddy($buddyid, $user=false){
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddyid);
		$timestamp = time();
        $check = mysql_query("SELECT * FROM buddylist WHERE (owner='".$user."' && buddy='$buddy') OR (buddy='".$user."' && owner='$buddy')");
		$checkData = mysql_fetch_array($check);
		
        if(!isset($checkData['owner'])){
        	$message = true;
            if($buddy == $user){
				//buddy = user
                $message = false;
            }
            $dbClass = new db();
            $requestData = $dbClass->select('user', array('userid', $buddy));
			
            if($requestData['priv_buddyRequest'] == "1"){
                $request = "1";
            } else{
                $request = "0";
            }
			
	       if($message){
                   $values['owner'] = $user;
                   $values['buddy'] = $buddy;
                   $values['timestamp'] = $time;
                   $values['request'] = $request;
	        
	        //if privacy settings dont need allowance, the user needs to be added on the buddies buddylist
	        if($request == "0"){
                   $values['owner'] = $buddy;
                   $values['buddy'] = $user;
                   $values['timestamp'] = $time;
                   $values['request'] = $request;
	        }
                $db = new db();
                $db->insert('buddylist', $values);
	        
	        $message = true;
	       }

        } else {
        	///already there
            $message = false;
        }
		return $message;
		
	}
	function replyRequest($buddy, $user=false){
		
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddy);
		
		
        $value = "0";
		$timestamp = time();
		$db = new db();
                
                mysql_query("UPDATE buddylist SET request='$value' WHERE owner='".mysql_real_escape_string($buddy)."' && buddy='".$user."'");
                
                $values['owner'] = $user;
                $values['buddy'] = save($buddy);
                $values['timestamp'] = $time;
                $values['request'] = '0';
                $db->insert('buddylist', $values);
                
                
                return true;
	}
	function denyRequest($buddy, $user=false){
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddy);
		
                mysql_query("DELETE FROM buddylist WHERE owner='".$buddy."' && buddy='".$user."'");
                mysql_query("DELETE FROM buddylist WHERE owner='".$user."' && buddy='".$buddy."'");
                return true;
	}
        function deleteBuddy($buddy, $user=false){
                     if(!$user){
                             $user = getUser();
                     }

                     mysql_query("DELETE FROM `buddylist` WHERE owner='".save($user)."' AND buddy='".save($buddy)."'");
                     mysql_query("DELETE FROM `buddylist` WHERE buddy='".save($user)."' AND owner='".save($buddy)."'");

        }
        function getOpenRequests($user=NULL){
             if(empty($user)){
                 $user= getUser();
             }

                     $friendRequestSql = mysql_query("SELECT owner FROM buddylist WHERE buddy='".$user."' && request='1'");
                     while($friendRequestData = mysql_fetch_array($friendRequestSql)){
                             $userid = $friendRequestData['owner'];
                             $return[$userid] = useridToUsername($userid);
                     }

                     return $return; 
        }

   function buddyListArray($user=NULL, $request=0){
   //returns all buddies of $user
        if(empty($user)){
            $user= getUser();
        }
        
        $buddies = array();
        $buddylistSql = mysql_query("SELECT buddy FROM buddylist WHERE owner='$user' && (request='$request')");
        while($buddylistData = mysql_fetch_array($buddylistSql)) {
            $buddies[] = $buddylistData['buddy'];
        }
        
        return $buddies;
       
   }
   
   function friendsYouMayKnow(){
       //returns an array with people that match the buddylist of >2 friends
       
       //load all buddies of user into an array
       //request is used for sql-injection to also
       //get buddies who didn't answered the
       //request
       $buddies = $this->buddyListArray('', "1' OR request='0");
       
	   $notSuggest = $this->getNotSuggestList(); //get array of users that will not be suggested
       
       //return every single buddy
       foreach($buddies AS &$buddy){
           
           //get every buddy of this buddy
           $buddiesOfBuddy = $this->buddyListArray($buddy);
           
           
           //return every single buddy of this buddy
           foreach($buddiesOfBuddy AS &$buddyOfBuddy){
               //the most counted userid will be the userid
               //of the user who send the request so it has
               //to be removed from the whole array
               //
               //all buddies which are allready in the users
               //buddylist also need to be removed
               if($buddyOfBuddy != getUser() && !in_array($buddyOfBuddy, $buddies) && $buddyOfBuddy != 0 && !in_array($buddyOfBuddy, $notSuggest)){
                    $finalArray[] = $buddyOfBuddy;
               }
           }
           
           
       }
       
       //gives out the value inside the array which occures most
       $c = array_count_values($finalArray); 
       $return = array_search(max($c), $c);

       return $return;
       
   }

	function getNotSuggestList(){
         $userSql = mysql_query("SELECT buddySuggestions FROM user WHERE userid='".getUser()."'");
         $userData = mysql_fetch_array($userSql);
		 $buddySuggestions = $userData['buddySuggestions'];
		 
		 if(!empty($buddySuggestions)){
		 	
		 	$buddySuggestions = json_decode($buddySuggestions, true);
		 
			foreach($buddySuggestions AS $buddySuggestion){
				$return[] = $buddySuggestion['user'];
			}
			
			return $return;
			
		 }
	}
        function addToNotSuggestList($user){
		
		$user = save(intval($user));
		
		$notSuggest = $this->getNotSuggestList();
		
			if(!in_array($user, $notSuggest)){
				
				//script needs to access db again, to parse also timestamp
				//into the json string. the timestamp will be used to delete
				//old suggestions after a while
				
		        $userSql = mysql_query("SELECT buddySuggestions FROM user WHERE userid='".getUser()."'");
		        $userData = mysql_fetch_array($userSql);
				
				$notSuggest = json_decode($userData['buddySuggestions'],true);
			
				$userObj['user'] = $user;
				$userObj['timestamp'] = time();
				
				$notSuggest[] = $userObj;
				
		        if(mysql_query("UPDATE user SET buddySuggestions='".json_encode($notSuggest)."' WHERE userid='".getUser()."'")){
		        	return true;
		       	}
			}
	}
	
	function showBuddySuggestions($frame=true){
		
		$mayKnow = $this->friendsYouMayKnow();
		if(!empty($mayKnow)){
			if($frame){
				echo"<div id=\"buddySuggestions\">";
			}
			echo"<header>";
				echo"you may know";
				echo"<a id=\"closeSuggestions\" onclick=\"$('#buddySuggestions').hide();\">x</a>";
			echo"</header>";
			echo'<div>';
				echo"<a href=\"#\" onclick=\"showProfile('$mayKnow')\">";
				echo"";
				echo"<span>&nbsp;";
				echo stripslashes(showUserPicture($mayKnow, 16, NULL, true));
				echo"</span>";
				echo useridToUsername($mayKnow);
				echo'<a href="#" onclick="buddylist.addBuddy('.$mayKnow.');" class="btn btn-success btn-mini pull-right" target="submitter">add</a>';
				echo'<a href="doit.php?action=addToNotSuggestList&user='.$mayKnow.'" class="btn btn-mini pull-right" style="margin-right:5px;" target="submitter">later</a>';
				echo"</a>";
			echo'</div>';
			if($frame){
			echo"</div>";
			}
			
		}else{
			echo"<script>$('#buddySuggestions').hide();</script>";
		}
	}

  
   
   function buddy($userid){
   	//checks if user with id=$userid is buddy
   	//of user with id=$_SESSION[userid]
   	
   	$buddies = $this->buddyListArray($userid);
	
	if(in_array($_SESSION['userid'], $buddies) OR $userid == $_SESSION['userid']){
		return true;
	}
   }
}


