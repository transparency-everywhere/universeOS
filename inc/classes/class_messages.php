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
class message{
    function send($receiver, $text, $crypted, $sender=NULL){

        if($sender == NULL)
                $sender = getUser();
        
        $userClass = new user();
        $userClass->updateActivity($sender);
        
        $buddy = $receiver;
        if($crypted == "true"){
            $crypt = "1";
        }else{
            $crypt = "0";
        }

                $message = addslashes($text);
        if(mysql_query("INSERT INTO `messages` (`sender`,`receiver`,`timestamp`,`text`,`read`,`crypt`) VALUES('$sender', '$buddy', '".time()."', '$text', '0', '$crypt');")){
                return mysql_insert_id();
        }
        $postCheck = 1;
    }
    function markAsRead($buddy, $user){

                    $user = save($user);
                    $buddy = save($buddy);

                    mysql_query("UPDATE `messages` SET `read`='1' WHERE `sender` ='$buddy' AND `receiver` ='$user';");
    }	
    function getLast($userid){
            $userid = save($userid);
            
            $userClass = new user();
            $userClass->updateActivity($userid);
            $chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' OR receiver='$userid' ORDER BY timestamp DESC LIMIT 1");
            $chatData =  mysql_fetch_array($chatSQL);


            if($chatData['read'] == 0){
                    return $chatData['id'];
            }
    }
	function getUnseenMessageAuthors($userid){;
		$chatSQL = mysql_query("SELECT * FROM `messages` WHERE (`sender`='$userid' AND `seen`='0') OR (`read`='0' AND `receiver`='$userid')");
		while($chatData =  mysql_fetch_array($chatSQL)){
			if($chatData['sender'] == $userid){
				if(!in_array($chatData['receiver'], $return))
					$return[] = $chatData['receiver'];
			}else if($chatData['receiver'] == $userid){
				
				if(!in_array($chatData['sender'], $return))
					$return[] = $chatData['sender'];
			}
		}
		return $return;
	}
	function getMessages($userid, $buddyId, $limit){
		$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddyId' OR sender='$buddyId' && receiver='$userid' ORDER BY timestamp DESC LIMIT $limit");
		while($chatData =  mysql_fetch_array($chatSQL)){
			$id = $chatData['id'];
			$return[$id] = $chatData;
		}
		return $return;
	}
	function showMessages($userid, $buddyId, $limit){
		$buddyClass =  new user($buddyId);
		$buddyData = $buddyClass->getData();
                
                $userClass = new user($userid);
		$userData = $userClass->getData($userid);
		
				
		
		
		$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddyId' OR sender='$buddyId' && receiver='$userid' ORDER BY timestamp DESC LIMIT $limit");
		while($chatData = mysql_fetch_array($chatSQL)) {
	    
		    if($chatData['receiver'] == getUser() && $chatData['read'] == "0"){
		    mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='".$chatData['id']."'");
		    }
		    if($chatData['sender'] == $_SESSION['userid'] && $chatData['seen'] == "0"){
		    mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='".$chatData['id']."'");
		    }
		    
		    $sender = $chatData['sender'];
		    $whileid = getUser();
		    if($sender == $userid){
			    $receiver = $buddyId;
			    $authorid =  $userData['userid'];
			    $class = 'incoming';
		    } else {
			    $authorid =  $buddyData['userid'];
			    $receiver = $userid;
			    $class = 'outgoing';
		    }
		    
			$authorName = useridToUsername($authorid);
			$buddyName = str_replace(" ","_",$buddyData['username']); //replace spaces with _ to get classname
			
			
			//check if message is crypted
		    if($chatData['crypt'] == "1"){
		    	
				$messageClasses = "cryptedChatMessage_$buddyId";
		        $message = $chatData['text'];
			} else{
				
				$messageClasses = "";
		        $message = $chatData['text'];
		    }
		    $message = universe::universeText($message);
			
			//show message
		              echo '<div class="box-shadow chatMessage '.$class.'">';
		              	echo '<span class="username">';
						echo showUserPicture($authorid, "15");
						echo $authorName;
		              	echo "</span>";
                                $guiClass = new gui();
		              	echo'<span class="timestamp pull-right">'.$guiClass->universeTime($chatData['timestamp']).'</span>';
		              	echo'<span class="chatMessage_'.$buddyId.' '.$messageClasses.'" data-sender="'.$authorid.'" data-receiver="'.$receiver.'" data-id="'.$chatData['id'].'" data-decrypted="false">'.$message.'</span>';
		              echo'</div>';
			}
	
	}
   //gets all unseen messages for receiver=user
   function getLastMessages($user=NULL){
   	if($user == NULL){
   		$user = getUser();
   	}else{
   		$user = save($user);
   	}
   	$listedUsers[] = $user;
	$newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  receiver='$user' OR sender='$user' ORDER BY timestamp DESC LIMIT 0, 5");
	while($newMessagesData = mysql_fetch_array($newMessagesSql)){
		$session .= "newMessage ".$newMessagesData['id'];
		
		
		//each sender is only listed once
		if(!in_array($newMessagesData['sender'], $listedUsers)){
	    $text = substr($newMessagesData['text'], 0, 100);

		//define everything that is important	    
		$return['messageId'] = $newMessagesData['id'];
		$return['sender'] = $newMessagesData['sender'];
		$return['receiver'] = $newMessagesData['receiver'];
		$return['timestamp'] = $newMessagesData['timestamp'];
		$return['text'] = $newMessagesData['text'];
		
		$return['seen'] = $newMessagesData['seen'];
		$return['read'] = $newMessagesData['read'];
		$return['senderUsername'] = useridToUsername($newMessagesData['sender']);
		
		//add sender too users array
		$listedUsers[] = $newMessagesData['sender'];
		
		//add all return data to returner array
		$returner[] = $return;
			
		}
	}
	
	return $returner;
	
   }
        
   function getMessageData($messageId){
   	$newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  id='".mysql_real_escape_string($messageId)."'");
	return mysql_fetch_array($newMessagesSql);
   }
   
}

	
	
	
	
	
	
        