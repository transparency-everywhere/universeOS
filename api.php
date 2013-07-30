<?php
session_start();
include_once("inc/config.php");
include_once("inc/functions.php");

$action = $_GET[action];



switch($action){
    case messengerLogin:
        $username = save($_POST[username]);
        $loginSQL = mysql_query("SELECT username, password FROM user WHERE username='$username'");
        $loginData = mysql_fetch_array($loginSQL);
        
        
        $dbPassword = $loginData[password];
        $dbPassword = hash('sha1', $dbPassword);
        if($_POST[password] == $dbPassword){
        echo"true";
        }else{
            echo"false";
        }
    break;
    case loadBuddyList:
        $username = save($_POST[username]);
        $hash = $_POST[hash];
        
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$username'");
        $loginData = mysql_fetch_array($loginSQL);
        $userid = $loginData[userid];
        $dbPassword = $loginData[password];
        $dbPassword = hash('sha1', $dbPassword);
        if($hash == $dbPassword){?>
            <table width="100%" cellspacing="0">
            <?
            $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='$userid' && request='0'");
            while($buddylistData = mysql_fetch_array($buddylistSql)) {
                $blUserSql = mysql_query("SELECT * FROM user WHERE userid='$buddylistData[buddy]'");
                $blUserData = mysql_fetch_array($blUserSql);

                //sets a hasttag for this 
                $userRow = md5("$blUserData[lastactivity],$userRow");
                if(!empty($buddylistData[alias])){
                    $username = $buddylistData[alias];
                } else{
                $username = htmlspecialchars($blUserData[username]);
                    }
                if($i%2 == 0) {
                    $bgClass="rowDark";

                } else {
                    $bgClass="rowBright";
                }
                ?>
                            <tr class="<?=$bgClass;?>" height="45" valign="center" onclick="openChat('<?=$username;?>');">
                                <td>&nbsp;<?=showUserPicture($blUserData['userid'], "30", "total");?></td>
                                <td><?=$username;?></td>
                                <td>&nbsp;a</td>        
                            </tr>
            <?
            $i++;
            }
            $_SESSION[reloadBuddylist] = "$userRow";
            ?>
            </table>
        <?}
    break;
    case loadChatPreview:
    ?>
    <div class="chatPreview" id="chatPreview_<?=$_GET[buddy];?>" onclick="showChatDialoge('<?=$_GET[buddy];?>')">
        <?=$_GET[buddy];?>
    </div>
    <?
    break;
    case loadChatDialoge:
        $buddy = save($_GET[buddy]);
        $user = save($_GET[user]);
        $hash = save($_GET[hash]);
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$user'");
        $loginData = mysql_fetch_array($loginSQL);
        $userid = $loginData[userid];
        $dbPassword = $loginData[password];
        $dbPassword = hash('sha1', $dbPassword);
        if($hash == $dbPassword){
        
        
        
    ?>
    <div class="chatWindow" id="chatWindow_<?=$buddy;?>">
        <div class="header">
           <span style="position: absolute; margin-top: 1em; margin-left: 0.5em; color:#A8A8A8;" onclick="togglePage('chatIntro');">back</span>
            <center> <?=$buddy;?></center>
        </div>
        <div class="chatDialoge_<?=$buddy;?> content">
        <?
        $buddySql = mysql_query("SELECT userid, username FROM user WHERE username='$buddy'");
        $buddyData = mysql_fetch_array($buddySql);
        $buddy = $buddyData[userid];
        $chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddy' OR sender='$buddy' && receiver='$userid' ORDER BY timestamp DESC LIMIT 0, 30");
        while($chatData = mysql_fetch_array($chatSQL)) {

            if($chatData[receiver] == $userid && $chatData[read] == "0"){
            mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
            }
            if($chatData[sender] == $userid && $chatData[seen] == "0"){
            mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
            }

            $sender = $chatData[sender];
            $whileid = $userid;
            if($sender == $whileid){
            $authorid =  $userData[userid];
            $authorName = $userData[username];
            $reverse = NULL;
            $css = 'outcome';
            $signatureCss = 'signatureOutcome';
            } else {

            $authorid =  $buddyData[userid];
            $authorName = $buddyData[username]; 
            $css = 'income'; 
            $signatureCss = 'signatureIncome';
            $reverse = "1";
            }
            if($chatData[crypt] == "1"){
                if(isset($_SESSION[$intWindows])){
                $message = universeDecode("$chatData[text]", "$_SESSION[$intWindows]");
            }else{
                $message = "<s>crypted</s>";
            }} else{
                $message = $chatData[text];
            } ?>
            <div class="chatMessage <?=$css;?>">
                <div class="chatSignature">
                <?=showUserPicture($authorid, "15", "total");?><?=$authorName;?>
                </div>
                <div class="messageContainer">
                <?=$message;?>
                </div>
            </div>
        <? }?>
        </div>
        <form id="messageForm_<?=$buddy;?>">
        <div class="footer systemGray">
            <center><input type="text" id="message_<?=$buddy;?>" style="width: 70%; margin-top: 0.5em; font-size: 13pt;" placeholder="write a message"></center>
        </div>
        </form>
    </div>
    <script>
        function handle<?=$buddy;?>_submit(){
            //get value from input[type=text]
            var message = $("#message_<?=$buddy;?>").val();
            if(message.length > 0){
                sendMessage('<?=$buddyData[username];?>', message);
                //reload 
                reloadDialoge('<?=$buddyData[username];?>');
                $("#message_<?=$buddy;?>").val('');
                
            }else{
                alert("you have to type a message ;)");
            } 
            return false;
        }
        $("#messageForm_<?=$buddy;?>").on("submit",handle<?=$buddy;?>_submit);
    </script>
    <?
    }
    break;
    case getBuddyFromMessageId:
        //get request data
        $user = save($_POST[username]); 
        $messageId = save($_POST[message]);
        $hash = save($_POST[password]);
        //get the login data
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$user'");
        $loginData = mysql_fetch_array($loginSQL);
        $userid = $loginData[userid];
        
        //get messageData
        $messageSQL = mysql_query("SELECT sender, receiver FROM messages WHERE id='$messageId'");
        $messageData = mysql_fetch_array($messageSQL);
        //check if buddy is sender or receiver
        if($messageData[sender] == $userid){
            $buddyId = $messageData[receiver];
        }else if($messageData[receiver] == $userid){
            $buddyId = $messageData[sender];
        }
        
        //getting the userdata of the buddy
        $userSQL = mysql_query("SELECT username FROM user WHERE userid='$buddyId'");
        $userData = mysql_fetch_array($userSQL);
        echo"$userData[username]";
    break;
    case checkForMessages:
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
        
        //uservalidation
        if($hash == $dbPassword){
            
            $chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' OR receiver='$userid'ORDER BY id DESC LIMIT 1");
            $chatData = mysql_fetch_array($chatSQL);
            
            if($chatData[receiver] == $userid && $chatData[read] == "0"){
                $return = 1;
            }
            if($chatData[sender] == $userid && $chatData[seen] == "0"){
                $return = 1;
            }
            
        }
    echo $return;
    break;
    
    //returns the javascript functions to load/reload the dialoges on client
    case loadNewChatDialoges:
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
    case reloadChatDialoge:
        $buddy = save($_GET[buddy]);
        $user = save($_GET[user]);
        $hash = save($_GET[hash]);
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$user'");
        $loginData = mysql_fetch_array($loginSQL);
        $userid = $loginData[userid];
        $dbPassword = $loginData[password];
        $dbPassword = hash('sha1', $dbPassword);
        if($hash == $dbPassword){
        $buddySql = mysql_query("SELECT userid, username FROM user WHERE username='$buddy'");
        $buddyData = mysql_fetch_array($buddySql);
        $buddy = $buddyData[userid];
        $chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddy' OR sender='$buddy' && receiver='$userid' ORDER BY timestamp DESC LIMIT 0, 30");
        while($chatData = mysql_fetch_array($chatSQL)) {

            if($chatData[receiver] == $userid && $chatData[read] == "0"){
            mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
            }
            if($chatData[sender] == $userid && $chatData[seen] == "0"){
            mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
            }

            $sender = $chatData[sender];
            $whileid = $userid;
            if($sender == $whileid){
            $authorid =  $userData[userid];
            $authorName = $userData[username];
            $reverse = NULL;
            $css = 'outcome';
            $signatureCss = 'signatureOutcome';
            } else {

            $authorid =  $buddyData[userid];
            $authorName = $buddyData[username]; 
            $css = 'income'; 
            $signatureCss = 'signatureIncome';
            $reverse = "1";
            }
            if($chatData[crypt] == "1"){
                $message = "<s>crypted</s>";
            } else{
                $message = $chatData[text];
            } ?>
            <div class="chatMessage <?=$css;?>">
                <div class="chatSignature">
                <?=showUserPicture($authorid, "15", "total");?><?=$authorName;?>
                </div>
                <div class="messageContainer">
                <?=$message;?>
                </div>
            </div>
        <? }
        }
    break;
    case sendMessage:
        $user = save($_GET[user]); 
        $message = save($_POST[msg]);
        $hash = save($_POST[pwd]);
        $buddy = save($_GET[buddy]);
        $loginSQL = mysql_query("SELECT userid, username, password FROM user WHERE username='$user'");
        $loginData = mysql_fetch_array($loginSQL);
        $userid = $loginData[userid];
        $dbPassword = $loginData[password];
        $dbPassword = hash('sha1', $dbPassword);
        if($dbPassword == $hash){
        $buddySql = mysql_query("SELECT userid FROM user WHERE username='$buddy'");
        $buddyData = mysql_fetch_array($buddySql);
        $buddyId = $buddyData[userid];
        $time = time();
        if(mysql_query("INSERT INTO messages (`sender`,`receiver`,`timestamp`,`text`,`read`,`crypt`) VALUES('$userid', '$buddyId', '$time', '$message', '0', '');")){
            echo$buddyId;
        }

        }
    break;
    case checkUsername:
        
    $user = save($_POST[username]);
    $sql = mysql_query("SELECT username FROM user WHERE username='$user'");
    $data = mysql_fetch_array($sql);
    
        if(empty($data[username])){
            echo"1";
        }else{
            echo"1";
        }
        
        
    break;
    //is used for mobile app => no captcha!
    case processRegistration:
    $user = save($_POST[username]);
    $sql = mysql_query("SELECT username FROM user WHERE username='$user'");
    $data = mysql_fetch_array($sql);
    
    if(empty($data[username])){
          
        $password = md5($_POST[password]);
        $time = time();
        mysql_query("INSERT INTO `user` (`password`, `username`, `email`, `regdate`, `lastactivity`) VALUES ('$password', '$_POST[username]', '', '$time', '$time')");

        $userid = mysql_insert_id();
 
        
        //create user folder(name=userid) in folder userFiles
        $userFolder = createFolder("2", $userid, $userid, "h");
        
        //create folder for userpics in user folder
        $pictureFolder = createFolder($userFolder, "userPictures", $userid, "h");
        
        //create thumb folders || NOT LISTED IN DB!
        $path3 = ".//upload//userFiles//$userid//userPictures//thumb";
        $path4 = ".//upload//userFiles//$userid//userPictures//thumb//25";
        $path5 = ".//upload//userFiles//$userid//userPictures//thumb//40";
        $path6 = ".//upload//userFiles//$userid//userPictures//thumb//300";
        mkdir($path3);  //Creates Thumbnail Folder
        mkdir($path4); //Creates Thumbnail Folder
        mkdir($path5); //Creates Thumbnail Folder
        mkdir($path6); //Creates Thumbnail Folder
        
        
        //create Element "myFiles" in userFolder
        $myFiles = createElement($userFolder, "myFiles", "myFiles", $userid, "h");
        
        //create Element "user pictures" to collect profile pictures
        $pictureElement = createElement($userFolder, "profile pictures", "image", $userid, "p");


        mysql_query("UPDATE user SET homefolder='$userFolder', myFiles='$myFiles', profilepictureelement='$pictureElement' WHERE userid='$userid'");

        echo"1";
    }else{
        echo"Ooops.. Something went wrong.";
    }
        
    break;
    //is used for universeOS registration form
    case processSiteRegistration:
    $user = save($_POST[username]);
    $sql = mysql_query("SELECT username FROM user WHERE username='$user'");
    $data = mysql_fetch_array($sql);
    
    if(empty($data[username]) && validateCapatcha("$_POST[captcha]")){
          
        $password = md5($_POST[password]);
        $time = time();
        mysql_query("INSERT INTO `user` (`password`, `username`, `email`, `regdate`, `lastactivity`) VALUES ('$password', '$_POST[username]', '', '$time', '$time')");

        $userid = mysql_insert_id();
        
        
        //create user folder(name=userid) in folder userFiles
        $userFolder = createFolder("2", $userid, $userid, "h");
        
        //create folder for userpics in user folder
        $pictureFolder = createFolder($userFolder, "userPictures", $userid, "h");
        
        //create thumb folders || NOT LISTED IN DB!
        $path3 = ".//upload//userFiles//$userid//userPictures//thumb";
        $path4 = ".//upload//userFiles//$userid//userPictures//thumb//25";
        $path5 = ".//upload//userFiles//$userid//userPictures//thumb//40";
        $path6 = ".//upload//userFiles//$userid//userPictures//thumb//300";
        mkdir($path3);  //Creates Thumbnail Folder
        mkdir($path4); //Creates Thumbnail Folder
        mkdir($path5); //Creates Thumbnail Folder
        mkdir($path6); //Creates Thumbnail Folder
        
        
        //create Element "myFiles" in userFolder
        $myFiles = createElement($userFolder, "myFiles", "myFiles", $userid, "h");
        
        //create Element "user pictures" to collect profile pictures
        $pictureElement = createElement($pictureFolder, "profile pictures", "image", $userid, "p");


        mysql_query("UPDATE user SET homefolder='$userFolder', myFiles='$myFiles', profilepictureelement='$pictureElement' WHERE userid='$userid'");

        echo"1";
    }else{
        if(!validateCapatcha("$_POST[captcha]")){
            echo"The captcha was wrong.";
        }else{
            echo"Ooops.. Something went wrong.";  
        }
    }
        
    break;
    case checkForFeeds:
        
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


                    //get all users which are in the buddylist
                    $buddies = buddyListArray();
                    $buddies[] = $_SESSION[userid];
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
                if(!in_array($feedData[id], $token)){
                    if(empty($return)){
                    $return = true;
                    }

                }
            }
        if(!$return){
            echo"1";
        }
        
        break;
    case showFeed:
        $username = save($_POST[username]);
        $hash = $_POST[hash];
        if(checkMobileAuthentification($username, $hash)){
        showFeed('','','1');
        }
    break;
    case submitFeedEntry:
        $username = save($_POST[user]); 
        $message = save($_POST[msg]);
        $hash = save($_POST[pwd]);
        if(checkMobileAuthentification($username, $hash)){
            $userid = usernameToUserid($username);
            addFeed($userid, $message, feed);
            return true;
        }
        
    break;
    case showFeedComments:
        $username = save($_POST[username]);
        $hash = $_POST[hash];
        if(checkMobileAuthentification($username, $hash)){
            
            $commentid = $_GET[feedid];
            showFeedComments($commentid);
            
        }
        
    break;
        
}