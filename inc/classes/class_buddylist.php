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

class buddylist{
    
        function addBuddy($buddyid, $user=false){
   		if(!$user){
   			$user = getUser();
   		}
		
		$user = save($user);
		$buddy = save($buddyid);
		$timestamp = time();
        $dbClass = new db();
        $checkData = $dbClass->query("SELECT * FROM buddylist WHERE (owner='".$user."' && buddy='$buddy') OR (buddy='".$user."' && owner='$buddy')");
        if(!isset($checkData['owner'])){
        	$message = true;
            if($buddy == $user){
				//buddy = user
                $message = false;
            }
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
		
		
		$timestamp = time();
		$db = new db();
                
                $values['request'] = '0';
                $db->update('buddylist', $values, array('owner', $buddy, '&&', 'buddy', $user));
                
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
		$db = new db();
                $db->delete('buddylist', array('owner', $buddy, '&&', 'buddy', $user));
                $db->delete('buddylist', array('buddy', $buddy, '&&', 'owner', $user));
                
                return true;
	}
        function deleteBuddy($buddy, $user=false){
                     if(!$user){
                             $user = getUser();
                     }
                    $db = new db();
                    $db->delete('buddylist', array('owner', $buddy, '&&', 'buddy', $user));
                    $db->delete('buddylist', array('buddy', $buddy, '&&', 'owner', $user));
                    return true;
        }
        function getOpenRequests($user=NULL){
             if(empty($user)){
                 $user= getUser();
             }
                    $db = new db();
                    $friendRequests = $db->shiftResult($db->select('buddylist', array('buddy', $user, '&&', 'request', '1')), 'buddy');
                    
                    foreach($friendRequests AS $friendRequestData){
                             $userid = $friendRequestData['owner'];
                             $return[$userid] = useridToUsername($userid);
                     }

                    return $return; 
        }

        function getChecksum($user=NULL, $request=0){
            $checksum = '';
            foreach($this->buddyListArray() AS $data){
                 $checksum .= $data;

            }
            return md5($checksum);
        }

        function buddyListArray($user=NULL, $request=0){
        //returns all buddies of $user
             if(empty($user)){
                 $user= getUser();
             }else{
                 //check privacy rights!
                 $privacy = new userPrivacy($user);
                 if(!$privacy->proofRight('buddylist')){
                     return array();
                 }
             }
             $db = new db();
             $buddyListQuery = $db->shiftResult($db->select('buddylist', array('owner', $user, '&&', 'request', $request)), 'owner');
             $buddies = array();
             foreach($buddyListQuery AS $buddylistData){
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
            $db = new db();
            $userData = $db->select('user', array('userid', getUser()), array('buddySuggestions'));
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
				
                                $db = new db();
				//script needs to access db again, to parse also timestamp
				//into the json string. the timestamp will be used to delete
				//old suggestions after a while
				
		        
                                $userData = $db->select('user', array('userid', getUser()), array('buddySuggestions'));
                                $buddySuggestions = $userData['buddySuggestions'];
				
				$notSuggest = json_decode($userData['buddySuggestions'],true);
			
				$userObj['user'] = $user;
				$userObj['timestamp'] = time();
				
				$notSuggest[] = $userObj;
				
                                $values['buddySuggestions'] = json_encode($notSuggest);
                                
                                if($db->update('user', $values, array('userid', getUser()))){
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


