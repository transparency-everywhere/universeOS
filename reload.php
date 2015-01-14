<?php
if(empty($_SESSION['userid'])){
session_start();
require_once("inc/config.php");
require_once("inc/functions.php");
}
$userid = getUser();
if(proofLogin()){
echo"<script>";

$userClass = new user();
$userClass->updateActivity($userid);
$time = time();

//UFF Viewer
        if(!empty($_SESSION['openUffs'])&&false){
            $openUffs = $_SESSION['openUffs'];
            $openUffs = explode(";", $_SESSION["openUffs"]);
            foreach($openUffs AS &$file){
                $caller = "UFFsum_$file";
                $classFile = new file($file);
                $filePath = $classFile->getFullFilePath();
                $checksum = md5_file($filePath);
                if($_SESSION[$caller] != $checksum){
                ?>


        
        
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


//check for new msg
$newMessagesSql = mysql_query("SELECT sender FROM  `messages` WHERE  receiver='".$_SESSION['userid']."' AND  `read`='0'  ORDER BY timestamp DESC LIMIT 0, 3");
$newMessagesData = mysql_fetch_array($newMessagesSql);



//check for friend request
$friendRequestSql = mysql_query("SELECT * FROM buddylist WHERE buddy='".$_SESSION['userid']."' && request='1' LIMIT 0, 3");
$friendRequestData = mysql_fetch_array($friendRequestSql);
 $newFriends = $friendRequestData['buddy'];
 
//check for group invitation
$newGroupSql = mysql_query("SELECT * FROM  `groupAttachments` WHERE  item='user' AND  `validated`='0' AND itemId='$userid' ORDER BY timestamp DESC LIMIT 0, 3");
$newGroupData = mysql_fetch_array($newGroupSql);
 $newGroup = $newGroupData['item'];
 
 
        echo "$(document).ready(function(){";
        
        
//load new chat window if recent user is active
if(isset($newMessagesOn)){
    $newMessageUserSql = mysql_query("SELECT userid, username FROM user WHERE username='$buddy'");
    $newMessageUserData = mysql_fetch_array($newMessageUserSql);
    ?>
      updateDashbox('message');
      
      $('#chat').show();
      applicationOnTop('chat');
      $("#test_<?=str_replace(" ","_",$newMessageUserData['username']);?>").load("modules/chat/chatreload.php?buddy=<?=str_replace(" ","_",$buddy);?>&reload=1&initter=1");
        
        
        //check if dialoge exists
        if($("#test_<?=str_replace(" ","_",$newMessageUserData['username']);?>").length == 0){
        	
        	im.openDialogue('<?=str_replace(" ","_",$newMessageUserData['username']);?>')	
        }
       $("#chatInput_<?=$newMessageUserData['userid'];?>").click( function(){
           $('#loader').load("doit.php?action=updateMessageStatus&buddy=<?=$newMessageUserData['userid'];?>");
       });
       
        var isOldTitle = true;
        var oldTitle = "universeOS";
        var newTitle = "<?=$newMessageUserData['username'];?> wrote you";
        var titleInterval = null;
        function changeTitle() {
            document.title = isOldTitle ? oldTitle : newTitle;
            isOldTitle = !isOldTitle;
        }
        titleInterval = setInterval(changeTitle, 700);

        $(window).focus(function () {
            clearInterval(titleInterval);
            $("title").text(oldTitle);
           $('#loader').load("doit.php?action=updateMessageStatus&buddy=<?=$newMessageUserData['userid'];?>");
        });
<?php }


if(!empty($newMessages) OR !empty($newFriends) OR !empty($newGroup)){
        echo"$('#personalFeed').load('personalFeed.php')";
}else{
    echo "$('#newMessages').html('');";
    echo "$('#openFriendRequests').html('');";
    echo "$('#appAlerts').html('');";
}
    echo"});";
      
    echo"</script>";
    $_SESSION['loggedOut'] = true;
}else{
	
	
    //reload page if session is expired
    $class_universe = new universe();
    $class_universe->proofSession();
} ?>