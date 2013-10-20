<?
if(empty($_SESSION[userid])){
session_start();
}
if($_SESSION[userid]){
require_once("inc/config.php");
require_once("inc/functions.php");
echo"<script>";
$userid = $_SESSION['userid'];
updateActivity($userid);
$time = time();

//UFF Viewer
        if(!empty($_SESSION[openUffs])){
            $openUffs = $_SESSION[openUffs];
            $openUffs = explode(";", $_SESSION["openUffs"]);
            foreach($openUffs AS &$file){
                $caller = "UFFsum_$file";
                $filePath = getFullFilePath($file);
                $checksum = md5_file($filePath);
                if($_SESSION[$caller] != $checksum){?>
        
        
                    if($('.uffViewer_<?=$file;?>').length > 0){
                            $('.uffViewer_<?=$file;?>').html(function(){
                                var caretPosition = getCaretPosition(this);
                                
                                $.get('doit.php?action=loadUff&id=<?=$file;?>&noJq=true', function(uffContent) {
                                    $('.uffViewer_<?=$file;?>').val(uffContent);
                                });
                                
        
        
                            });
                            //load
                    }else{
        
                        //delete cookie from openUffs
                        $.post("doit.php?action=removeUFFcookie", {
                                   id:'<?=$file;?>'
                                   });
                    }
                <?
                }
            }
        }



//check if the buddylists needs to be reloaded
$buddies = buddyListArray();

if(!empty($buddies) && ($_SESSION[buddyListReload] < (time()-60))){
echo"$('#buddyListFrame').load('buddylist.php?reload=1');";
$_SESSION['buddyListReload']  = time();
}



//check for new msg
$newMessagesSql = mysql_query("SELECT sender FROM  `messages` WHERE  receiver='$_SESSION[userid]' AND  `read`='0'  ORDER BY timestamp DESC LIMIT 0, 3");
$newMessagesData = mysql_fetch_array($newMessagesSql);


$newMessagesSql2 = mysql_query("SELECT receiver FROM  `messages` WHERE  sender='$_SESSION[userid]' AND  `seen`='0'  ORDER BY timestamp DESC LIMIT 0, 3");
$newMessagesData2 = mysql_fetch_array($newMessagesSql2);
    if(isset($newMessagesData2[sender])){
        $UserSql = mysql_query("SELECT * FROM user WHERE userid='$newMessagesData2[receiver]'");
        $UserData = mysql_fetch_array($UserSql);
        $user = $UserData[username];
        $newMessageOn = $newMessagesData2[receiver];
    }
    
    if(isset($newMessagesData[sender])){
    $checkUserSql = mysql_query("SELECT username, lastactivity FROM user WHERE userid='$newMessagesData[sender]'");
    $checkUserData = mysql_fetch_array($checkUserSql);
    $buddy = $checkUserData[username];
    }
    
    //see if the sender is still active or not
    if(($time - $checkUserData[lastactivity]) < 60){
        $newMessagesOn = $newMessagesData[sender];
        unset($newMessages);
    }else{
        $newMessages = "$newMessagesData[sender]";
        unset($newMessagesOn);
    }
//check for friend request
$friendRequestSql = mysql_query("SELECT * FROM buddylist WHERE buddy='$_SESSION[userid]' && request='1' LIMIT 0, 3");
$friendRequestData = mysql_fetch_array($friendRequestSql);
 $newFriends = $friendRequestData[buddy];
 
//check for group invitation
$newGroupSql = mysql_query("SELECT * FROM  `groupAttachments` WHERE  item='user' AND  `validated`='0' AND itemId='$userid' ORDER BY timestamp DESC LIMIT 0, 3");
$newGroupData = mysql_fetch_array($newGroupSql);
 $newGroup = $newGroupData[item];
 
 
        echo "$(document).ready(function(){";
        
        
//load new chat window if recent user is active
if(isset($newMessagesOn)){
    $newMessageUserSql = mysql_query("SELECT userid, username FROM user WHERE username='$buddy'");
    $newMessageUserData = mysql_fetch_array($newMessageUserSql);
    ?>
      updateDashbox('message');
      
      $('#chat').show();
      
      $("#test_<?=str_replace(" ","_",$newMessageUserData[username]);?>").load("modules/chat/chatt.php?buddy=<?=str_replace(" ","_",$buddy);?>&reload=1&initter=1");
        
        if($("#test_<?=str_replace(" ","_",$newMessageUserData[username]);?>").length == 0){
            createNewTab('chat_tabView1','<?=$newMessageUserData[username];?>','','modules/chat/chatt.php?buddy=<?=str_replace(" ","_",$newMessageUserData[username]);?>',true);
            return false
        }
       $("#chatInput_<?=$newMessageUserData[userid];?>").click( function(){
           $('#loader').load("doit.php?action=updateMessageStatus&buddy=<?=$newMessageUserData[userid];?>");
       });
       
        var isOldTitle = true;
        var oldTitle = "universeOS";
        var newTitle = "<?=$newMessageUserData[username];?> wrote you";
        var titleInterval = null;
        function changeTitle() {
            document.title = isOldTitle ? oldTitle : newTitle;
            isOldTitle = !isOldTitle;
        }
        titleInterval = setInterval(changeTitle, 700);

        $(window).focus(function () {
            clearInterval(titleInterval);
            $("title").text(oldTitle);
           $('#loader').load("doit.php?action=updateMessageStatus&buddy=<?=$newMessageUserData[userid];?>");
        });
<? }


$personalEventSql = mysql_query("SELECT * FROM personalEvents WHERE owner='$_SESSION[userid]' AND seen='0'");
$personalEvents = mysql_num_rows($personalEventSql);


if(!empty($newMessages) OR !empty($newFriends) OR !empty($newGroup) OR !empty($personalEvents)){
        echo"$('#personalFeed').load('personalFeed.php')";
}else{
    echo "$('#newMessages').html('');";
    echo "$('#openFriendRequests').html('');";
    echo "$('#appAlerts').html('');";
}
    echo"});";
      
    echo"</script>";
    
} ?>