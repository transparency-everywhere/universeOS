<?php
if(!isset($_SESSION)){
    session_start();
}
include_once("inc/config.php");
include_once("inc/functions.php");
header('Access-Control-Allow-Origin: *');

$action = $_GET['action'];



switch($action){
	case 'authentificate':
                $user = new user();
		echo $user->login($_POST['username'], $_POST['password']);
		break;
        case 'proofLogin':
            echo proofLogin();
            break;
	case 'getUserCypher':
			if(empty($_POST['userid'])){
				$userid = usernameToUserid($_POST['username']);
			}else{
				$userid = $_POST['userid'];
			}
				$userClass = new user($userid);
				$userData = $userClass->getData();
			
				echo $userData['cypher'];
			
		break;
	case 'getUserSalt':
			if(empty($_POST['userid'])){
				$userid = usernameToUserid($_POST['username']);
			}else{
				$userid = $_POST['userid'];
			}
				$userClass = new user($userid);
				$userData = $userClass->getData();
			
				echo $userData['salt'];
			
		break;
	case 'updatePasswordAndCreateSignatures':
		
		$userid = save($_POST['userid']);
                $userClass = new user($userid);
		$userData = $userClass->getData();
		$password = save($_POST['password']);
		$oldPassword = save($_POST['oldPassword']);
		$salt = save($_POST['salt']);
		
		$privateKey = save($_POST['privateKey']);
		$publicKey = save($_POST['publicKey']);
		
		//check if current cypher is md5 and if password is correct
		if($userData['cypher'] == 'md5' && $oldPassword == $userData['password']){
			
			//store salt
                        $saltClass = new salt();
			$saltClass->create('auth', $userid, 'user', $userid, $salt);
	        
			//create signature
			$sig = new signatures();
			$sig->create('user', $userid, $privateKey, $publicKey);
                        
                        
                        $db = new db();
                        $values['password'] = $password;
                        $values['cypher'] = 'sha512';
                        $db->update('user', $values, array('userid', $userid));
			echo "1";
		}
	break;
	case 'update_sha512TOsha512_2':
		$userid = save($_POST['userid']);
                $userClass = new user($userid);
		$userData = $userClass->getData($userid);
		
		if($userData['cypher'] == 'sha512'){
			
			//update password string and  privatekey (encryption key has been changed)
			$result = updateUserPassword($_POST['oldPassword'], $_POST['newPassword'], $_POST['saltAuthNew'],  $_POST['saltKeyNew'], $_POST['newPrivateKey'], $userid);
			if($result == true){
				
                                $db = new db();
                                $values['cypher'] = 'sha512_2';
                                $db->update('user', $values, array('userid', $userid));
			}else{
				echo $result;
			}
		}
		break;
		
		
	case 'updatePassword':
		
		echo updateUserPassword($_POST['oldPassword'], $_POST['password'], $_POST['authSalt'], $_POST['keySalt'], $_POST['privateKey']);
		
	break;
		
		
		
   	case 'login':
		//old version
		if(empty($_POST['username']))
			$username = save($_GET['username']);
		else 
			$username = save($_POST['username']);
		
        $loginSQL = mysql_query("SELECT username, password FROM user WHERE username='$username'");
        $loginData = mysql_fetch_array($loginSQL);
        
        
        $dbPassword = $loginData['password'];
        $dbPassword = hash('sha1', $dbPassword);
		
		if(empty($_POST['signature']))
			$password = $_GET['signature'];
		else 
			$password = $_POST['signature'];
		
        if($password == $dbPassword){
        echo"true";
        }else{
            echo"false";
        }
    break;
        case 'messengerLogin':
		if(empty($_POST['username']))
			$username = save($_GET['username']);
		else 
			$username = save($_POST['username']);
		
        $loginSQL = mysql_query("SELECT username, password FROM user WHERE username='$username'");
        $loginData = mysql_fetch_array($loginSQL);
        
        
        $dbPassword = $loginData['password'];
        $dbPassword = hash('sha1', $dbPassword);
		
		if(empty($_POST['password']))
			$password = $_GET['password'];
		else 
			$password = $_POST['password'];
		
        if($password == $dbPassword){
        echo"true";
        }else{
            echo"false";
        }
    break;
	case 'getBuddylist':
            //api/buddies/get/
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
                        $buddyListClass = new buddylist();
			echo json_encode($buddyListClass->buddyListArray($_POST['userid']));
		}
		break;
	case 'getOpenRequests':
		$user = $_POST['userid'];
                $buddyListClass = new buddylist();
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
			echo json_encode($buddyListClass->getOpenRequests($user));
		}
		break;
	case 'replyFriendRequest':
		
			$user = $_POST['userid'];
			$buddy = $_POST['buddy'];
                        $buddyListClass = new buddylist();
			if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
				$buddyListClass->replyRequest($buddy, $user);
			}
			
		break;
	case 'denyFriendRequest':
	
			
			$user = $_POST['userid'];
			$buddy = $_POST['buddy'];
                        $buddyListClass = new buddylist();
			if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
				$buddyListClass->denyRequest($buddy, $user);
			}
				
	
		break;
	case 'addBuddy':
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
			$buddyListClass = new buddylist();
			if($buddyListClass->addBuddy($_POST['buddy'], $_POST['userid'])){
				echo true;
			}else{
				echo false;
			}
		}
		break;
	case 'chatGetMessages':
		
		$receiver = $_POST['receiver'];
		$userid = $_POST['userid'];
		$limit = $_POST['limit'];
		
		
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
                        $messageClass = new message();
			echo json_encode($messageClass->getMessages($userid, $receiver, $limit));
		}
		break;
	case 'markMessageAsRead':
		
		
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
			$messageClass = new message();
			echo $_POST['buddy'].$_POST['userid'];
			$messageClass->markAsRead($_POST['buddy'], $_POST['userid']);
			
		}
		
		break;
	case 'getLastMessage':
		// This action is used to synchronize the chat which uses a locally saved variable
		// to store the last message which is received. If this var is not equal to the
		// value which is echoed by this action the chat can reload.
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
                    $messageClass = new message();
			echo $messageClass->getLast($_POST['userid']);
		}
		
		break;
	case 'getUnseenMessageAuthors':
		// To load dialoges with unseen messages the authorid's of those messages are needed
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
                    $messageClass = new message();
                    echo json_encode($messageClass->getUnseenMessageAuthors($_POST['userid']));
		}
		
		break;
	case 'chatSendMessage':
		$receiver = $_POST['receiver'];
		$sender = $_POST['userid'];
		$message = $_POST['message'];
		
		
		if(proofLoginMobile($_POST['userid'], $_POST['hash'])){
                        $messageClass = new message();
			$message = $messageClass->send($receiver, $message, '0', $sender);
			if($message)
				echo $message;
		}
		
		break;
    case 'getBuddyFromMessageId':
        //get request data
        $user = save($_POST['username']); 
        $messageId = save($_POST['message']);
        $hash = save($_POST['password']);
        //get the login data
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$user'");
        $loginData = mysql_fetch_array($loginSQL);
        $userid = $loginData['userid'];
        
        //get messageData
        $messageSQL = mysql_query("SELECT sender, receiver FROM messages WHERE id='$messageId'");
        $messageData = mysql_fetch_array($messageSQL);
        //check if buddy is sender or receiver
        if($messageData['sender'] == $userid){
            $buddyId = $messageData['receiver'];
        }else if($messageData['receiver'] == $userid){
            $buddyId = $messageData['sender'];
        }
        
        //getting the userdata of the buddy
        $userSQL = mysql_query("SELECT username FROM user WHERE userid='$buddyId'");
        $userData = mysql_fetch_array($userSQL);
        echo $userData['username'];
    break;
    
    
    //returns the javascript functions to load/reload the dialoges on client
    case 'loadNewChatDialoges':
           //get request data
           $user = save($_POST[username]);
           $hash = save($_POST[password]);
           //get the login data
           $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$user'");
           $loginData = mysql_fetch_array($loginSQL);
           $userid = $loginData[userid];
        
            //secret password stuff
            $dbPassword = $loginData[password];
            $dbPassword = hash('sha1', $dbPassword);
           
           
            if($hash == $dbPassword){
            //check for new messages
            $newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  receiver='$userid' AND  `read`='0'  ORDER BY timestamp DESC LIMIT 0, 3");
            $newMessagesData = mysql_fetch_array($newMessagesSql);
                //reverse
                if(empty($newMessagesData[id])){
                    $newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  sender='$userid' AND  `seen`='0' ORDER BY timestamp DESC LIMIT 0, 3");
                    $newMessagesData = mysql_fetch_array($newMessagesSql);
                }
           if($newMessagesData[sender] == $userid){
                $buddyId = $newMessagesData[receiver];
           }else if($newMessagesData[receiver] == $userid){
                $buddyId = $newMessagesData[sender];
           }
           $buddySQL = mysql_query("SELECT username FROM user WHERE userid='$buddyId'");
           $buddyData = mysql_fetch_array($buddySQL);
           $buddyName = $buddyData[username];
           echo"$buddyName";
            }
    break;
	
	
	case 'getUserPicture':
		
		$api = new api();
		echo $api->getUserPicture($_POST['request']);
		
		break;
		
		
	case 'useridToUsername':
		$api = new api();
		echo $api->useridToUsername($_POST['request']);
		break;
		
		
	case 'searchUserByString':
		
		$api = new api();
		echo json_encode($api->searchUserByString($_POST['string'], '0,10'));
		break;
		
	case 'useridToRealname':
		$api = new api();
		echo $api->useridToRealname($_POST['request']);
		break;
		
		
	case 'usernameToUserid':
		echo usernameToUserid($_POST['username']);
		break;
		
		
	case 'getLastActivity':
	
	
       $api = new api();
	   echo $api->getLastActivity($_POST['request']);
		
		break;
		
		
	//checks if a username is taken
    case 'checkUsername':
        
    $user = save($_POST[username]);
    $sql = mysql_query("SELECT username FROM user WHERE username='$user'");
    $data = mysql_fetch_array($sql);
    
        if(empty($data[username])){
            echo"1";
        }else{
            echo"0";
        }
        
        
    break;
	
    //is used for universeIM registration form
    case 'processSiteRegistrationMobile':
            $classUser = new user();
	    $classUser->create($_POST['username'], $_POST['password'], $_POST['authSalt'], $_POST['keySalt'], $_POST['privateKey'], $_POST['publicKey']);
            echo "1";
			
    break;
	
    case 'checkForFeeds':
        
        $user = mysql_real_escape_string($_GET[user]);
        if(empty($user)){
            $user = $_SESSION[userid];
        }
        
        $type = mysql_real_escape_string($_GET[type]);
        
        
        //the limit is set to this value until it causes probs
        $limit = "0,30";
        
            switch($type){
                //shows every entry in the system
                case "public":

                    $where = "ORDER BY timestamp DESC LIMIT $limit"; //defines Query


                break;

                //shows just entries of buddies
                case "friends":

                    $buddyListClass = new buddylist();
                    //get all users which are in the buddylist
                    $buddies = $buddyListClass->buddyListArray();
                    $buddies[] = $_SESSION['userid'];
                    $buddies = join(',',$buddies);  
                    //push array with the user, which is logged in

                    $where = "WHERE author IN ($buddies) ORDER BY timestamp DESC LIMIT  $limit";

                break;

                //only shows entries of one user
                case "singleUser":

                    $where = "WHERE author='$user' ORDER BY timestamp DESC LIMIT  $limit";
                break;

                //only shows entries which are attached to a grouo $user => $groupId
                case "group":


                    $group = $user; //$user is used in this cased to pass the groupId
                    $where = "WHERE INSTR(`privacy`, '{$group}') > 0 ORDER BY timestamp DESC limit $limit";

                break;

                //only shows a single feed entry
                case "singleEntry":
                    $where = "WHERE id='$feedId'";
                    break;
            }
        
            //get specific feedsession in which the userids are saved
            
            $token = explode(';', $_SESSION["feedsession_$type"]);

            //proof if feedid is in list of allready loaded feeds
            $feedSql = mysql_query("SELECT id FROM feed $where");
            while($feedData = mysql_fetch_array($feedSql)) {
                //if new id occurs
                if(!in_array($feedData['id'], $token)){
                    if(empty($return)){
                    $return = true;
                    }

                }
            }
        if(!$return){
            echo"1";
        }
        
        break;
		
    case 'showFeed':
        $username = save($_POST['username']);
        $hash = $_POST[hash];
        if(checkMobileAuthentification($username, $hash)){
            $feedClass = new feed();
            $feedClass->show('','','1');
        }
    break;
	
    case 'submitFeedEntry':
        $username = save($_POST[user]); 
        $message = save($_POST[msg]);
        $hash = save($_POST[pwd]);
        if(checkMobileAuthentification($username, $hash)){
            $userid = usernameToUserid($username);
            addFeed($userid, $message, feed);
            return true;
        }
        
    break;
	
    case 'showFeedComments':
        $username = save($_POST['username']);
        $hash = $_POST[hash];
        if(checkMobileAuthentification($username, $hash)){
            
            $commentid = $_GET['feedid'];
            $commentClass = new comments();
            $commentClass->showFeedComments($commentid);
            
        }
        
    break;


//salts and signatures
	case 'createSalt':
		$type = $_POST['type'];
		$itemId = $_POST['itemId'];
		$receiverType = $_POST['type'];
		$receiverId = $_POST['receiverId'];
		$salt = $_POST['salt'];
		
		//store salt
                $saltClass = new salt();
		$saltClass->create($type, $itemId, $receiverType, $receiverId, $salt);
		
		break;
		
		
	case 'getSalt':
		$saltClass = new salt();
		echo $saltClass->get($_POST['type'], $_POST['itemId']);
		
		break;
		
		
	case 'getPublicKey':
		$type = $_POST['type'];
		$itemId = $_POST['itemId'];
		
		$signature = new signatures();
		$data = $signature->get($type, $itemId);
		echo $data['publicKey'];
		break;
		
		
	case 'getPrivateKey':
		$type = $_POST['type'];
		$itemId = $_POST['itemId'];
		
		$signature = new signatures();
		$data = $signature->get($type, $itemId);
		echo $data['privateKey'];
		break;
		
		
	case 'getPublicKey':
		$type = $_POST['type'];
		$itemId = $_POST['itemId'];
		
		$signature = new signatures();
		$data = $signature->get($type, $itemId);
		echo $data['privateKey'];
		break;
//events
	case 'createEvent':
		
		
		if($_POST['allDay'] == "true"){
		
			$startTime = strtotime($_POST['startDate']."-00:00")-3599;
			$stopTime = strtotime($_POST['startDate']."-23:59")-3599; //no idea why, but it works	
		}else{
			
			$startTime = strtotime($_POST['startDate']."-".$_POST['startTime']);
			$stopTime = strtotime($_POST['startDate']."-".$_POST['endTime']);
		}
		
        //set privacy
      	$customShow = $_POST['privacyCustomSee'];
        $customEdit = $_POST['privacyCustomEdit'];
        
        $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
		
		
		$events = new events();
		$events->create(getUser(), $startTime, $stopTime, $_POST['title'], $_POST['place'], $privacy, $_POST['users']);
		break;
	case 'updateEvent':
		
		if($_POST['allDay'] == "true"){
			
			$startTime = strtotime($_POST['startDate']."-00:00")-3599;
			$stopTime = strtotime($_POST['startDate']."-23:59")-3599; //no idea why, but it works
			
		}else{
			
			$startTime = strtotime($_POST['startDate']."-".$_POST['startTime']);
			$stopTime = strtotime($_POST['startDate']."-".$_POST['endTime']);
		}
		
        //set privacy
      	$customShow = $_POST['privacyCustomSee'];
        $customEdit = $_POST['privacyCustomEdit'];
        
        $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
		
		$events = new events();
		$events->update($_POST['eventId'], $startTime, $stopTime, $_POST['title'], $_POST['place'], $privacy);
		echo $_POST['allDay'];
		break;
	case 'joinEvent':
            //@old
		$events = new events();
		$events->joinEvent($_POST['originalEventId'], getUser(), $_POST['addToVisitors']);
		break;
	case 'getEvents':
            //@old
		
		$events = new events();
		echo json_encode($events->get(getUser(), $_POST['startStamp'], $_POST['stopStamp'], $_POST['privacy']));
		
		break;
	case 'getEventData':
            //@old
		
		
		$events = new events();
		echo json_encode($events->getData($_POST['eventId']));
		
		
		break;
	
	case 'createTask':
		
        //set privacy
      	$customShow = $_POST['privacyCustomSee'];
        $customEdit = $_POST['privacyCustomEdit'];
        $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
		
		//get timestamp
		$timestamp = strtotime($_POST['date']."-".$_POST['time']);
		
		$tasks = new tasks();
		$tasks->create(getUser(), $timestamp, $_POST['status'], $_POST['title'], $_POST['description'], $privacy);
		
		break;
	case 'updateTask':
		
        //set privacy
      	$customShow = $_POST['privacyCustomSee'];
        $customEdit = $_POST['privacyCustomEdit'];
        $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
		
		//get timestamp
		$timestamp = strtotime($_POST['date']."-".$_POST['time']);
		
		$tasks = new tasks();
		$tasks->update($_POST['taskId'],$_POST['user'], $timestamp, $_POST['status'], $_POST['title'], $_POST['description'], $privacy);
		
		break;
	case 'getTaskData':
		
		
		$events = new tasks();
		echo json_encode($events->getData($_POST['taskId']));
		
		
	break;
	case 'getTasks':
		
		$tasks = new tasks();
		echo json_encode($tasks->get(getUser(), $_POST['startStamp'], $_POST['stopStamp'], $_POST['privacy']));
		
		break;
	case 'markTaskAsDone':
		$tasks = new tasks();
		$tasks->changeStatus($_POST['eventid'], 'done');
		break;
	case 'markTaskAsPending':
		$tasks = new tasks();
		$tasks->changeStatus($_POST['eventid'], 'pending');
		break;
//filesystem
	case 'fileIdToFileTitle':
                $fileClass = new file($_POST['fileId']);
		echo $fileClass->getTitle();
		break;
	case 'elementIdToElementTitle':
            $element = new element($_POST['elementId']);
		echo $element->getName();
		break;
	case 'folderIdToFolderTitle':
                $classFolder = new folder($_POST['folderId']);
		echo $classFolder->getTitle();
		break;
		
//groups
	case 'getGroups':
		
		$groups = new groups();
		echo json_encode($groups->get());
		
		break;
	case 'getGroupTitle':
	
		$groups = new groups();
		echo $groups->getTitle($_POST['groupId']);
	
		break;
		
//privacy
	case 'authorize':
		//checks if user is authorized, to edit an item with privacy $_POST['privacy'].
		echo authorize($_POST['privacy'], 'edit', $_POST['author']);
		break;
}
