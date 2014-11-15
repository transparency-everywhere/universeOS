<?
require_once("inc/config.php");
require_once("inc/functions.php");
if(empty($_SESSION['userid'])){
    session_start();
}

//request limitator untill reload.php is tidy
if(!proofLogin() && $_SESSION['personalFeed'] > (time()-60)){
	die();
}

?>
<style>
    #personalFeed{
        position:absolute;
        bottom: 60px;
        left: 0px;
    }
    
</style>
<?
$i = "1";
$friendRequestSql = mysql_query("SELECT * FROM buddylist WHERE buddy='".$_SESSION['userid']."' && request='1'");
while($friendRequestData = mysql_fetch_array($friendRequestSql)){
	$session .= "buddyRequest $friendRequestData[id]";
	
    $friendRequestSql2 = mysql_query("SELECT * FROM user WHERE userid='".$friendRequestData['owner']."'");
    $friendRequestData2 = mysql_fetch_array($friendRequestSql2);
?>
    <script>
        if($("#friendRequest_<?=$friendRequestData['owner'];?>").length == '0'){
            $('#systemAlerts').append('<li id="friendRequest_<?=$friendRequestData['owner'];?>"><?=showUserPicture("$friendRequestData2[userid]", '15', '', unescaped);?><div class="messageMain"><a href="#" onclick="showProfile(\'<?=$friendRequestData2[userid];?>\');"><?=$friendRequestData2[username];?></a> wants to be your friend</div><div class="messageButton"><a href="doit.php?action=requestpositive&buddy=<?=$friendRequestData2[userid];?>" target="submitter" class="btn btn-info btn-mini" style="margin-right:25px;" onclick="$(\'#friendRequest_<?=$newGroupData[id];?>\').remove();">Add to Buddylist</a><a href="doit.php?action=requestnegative&buddy=<?=$friendRequestData2[userid];?>" class="btn btn-mini" target="submitter" onclick="$(\'#friendRequest_<?=$friendRequestData2[id];?>\').remove();">Decline</a></div></li>');
        }
    </script>
<?
$i++;
}
$newMessagesSql = mysql_query("SELECT * FROM  `messages` WHERE  receiver='$_SESSION[userid]' AND  `read`='0'  ORDER BY timestamp DESC LIMIT 0, 3");
while($newMessagesData = mysql_fetch_array($newMessagesSql)){
	$session .= "newMessage $newMessagesData[id]";
    
        $text = substr($newMessagesData['text'], 0, 100);

    $newMessagesSql2 = mysql_query("SELECT userid, username FROM user WHERE userid='$newMessagesData[sender]'");
    $newMessagesData2 = mysql_fetch_array($newMessagesSql2);
?>
    
    <script>
        if($("#message_<?=$newMessagesData['sender'];?>").length == 0){
            $('#systemAlerts').append('<li id="message_<?=$newMessagesData['sender'];?>"><?=showUserPicture("$newMessagesData[sender]", '15', '', true);?><div class="messageMain"><a href="#" onclick="showProfile(\'<?=$newMessagesData2[username];?>\');"><?=$newMessagesData2[username];?></a> <?=$text;?></div><div class="messageButton"><a onclick="openChatDialoge(\'<?=$newMessagesData2[username];?>\'); $(\'#message_<?=$newMessagesData[sender];?>\').remove();" class="btn btn-info btn-mini" style="margin-right:25px;"><i class="icon-envelope icon-white"> </i> Show</a><a class="btn btn-mini" href="doit.php?action=updateMessageStatus&buddy=<?=$newMessagesData2[userid];?>" target="submitter" onclick="$(\'#message_<?=$newMessagesData[sender];?>\').remove();">Ignore</a></div></li>');
        }
    </script>
<? 
$i++;
}
$newGroupSql = mysql_query("SELECT * FROM  `groupAttachments` WHERE  item='user' AND  `validated`='0' AND itemId='$userid' ORDER BY timestamp DESC LIMIT 0, 3");
while($newGroupData = mysql_fetch_array($newGroupSql)){
	$session .= "newGroup $newGroupData[id]";

    $newGroupSql2 = mysql_query("SELECT * FROM groups WHERE id='$newGroupData[group]'");
    $newGroupData2 = mysql_fetch_array($newGroupSql2);
        $newGroupSql3 = mysql_query("SELECT userid, username FROM user WHERE userid='$newGroupData[author]'");
        $newGroupData3 = mysql_fetch_array($newGroupSql3);
?>
    <script>
        if($("#groupRequest_<?=$newGroupData['id'];?>").length == 0){
            $('#systemAlerts').append('<li id="groupRequest_<?=$newGroupData[id];?>"><?=showUserPicture("$newGroupData3[userid]", '15', '', true);?><div class="messageMain"><a href="#" onclick="showProfile(\'<?=$newGroupData3[userid];?>\');"><?=$newGroupData3[username];?></a> Invited you into the  group <a href="#" onclick="reader.tabs.addTab(\'<?=$newGroupData2[title];?>\', \'\',gui.loadPage(\'group.php?id=<?=$newGroupData2['id'];?>\')); return false;"><?=$newGroupData2[title];?></a></div><div class="messageButton"><a href="doit.php?action=joinGroup&id=<?=$newGroupData[id];?>" class="btn btn-info btn-mini" target="submitter" style="margin-right:25px;" onclick="$(\'#groupRequest_<?=$newGroupData[id];?>\').remove();">Join</a><a href="doit.php?action=declineGroup&id=<?=$newGroupData[id];?>" class="btn btn-mini" target="submitter" onclick="$(\'#groupRequest_<?=$newGroupData[id];?>\').remove();">Decline</a></div></li>');
        }
    </script>
<? 
$i++;
}
$personalEventSql = mysql_query("SELECT * FROM personalEvents WHERE owner='$_SESSION[userid]' AND seen='0'");
while($personalEventData = mysql_fetch_array($personalEventSql)){
	$session .= "newPersonalEvent $personalEventData[id]";
    $newEventSql2 = mysql_query("SELECT username FROM user WHERE userid='$personalEventData[user]'");
    $newEventData2 = mysql_fetch_array($newEventSql2);
    $countEvents++;

	//comments
    if($personalEventData['event'] == "comment"){
        if($personalEventData['info'] == "feed"){
            $description = "Has commented your post.";
            $link = "reader.tabs.addTab(\'Comment\', \'\',gui.loadPage(\'modules/reader/showComment.php?type=$personalEventData[info]&itemid=$personalEventData[eventId]\')); return false";
        }else if($personalEventData['info'] == "profile"){
            $description = "Has commented in your profile.";
            $link = "showProfile(\'".$_SESSION['userid']."\');";
        }
    }
	//events
    if($personalEventData['event'] == "event"){
    	$events = new events($personalEventData['eventId']);
		$eventData = $events->getData($personalEventData['eventId']);
		
		
        $description = 'Invited you to the event "<a href="#" onclick="events.show(\\\''.$personalEventData['eventId'].'\\\');">'.$eventData['title'].'</a>"';
		
		if(!empty($eventData['place']))
			$description .= ' at '.$eventData['place'];
		
		$link = "events.joinForm(\'".$personalEventData['eventId']."\');";
    	
    }

    ?>
    <script>
        if($("#personalEvent_<?=$personalEventData['event'];?>_<?=$personalEventData['info'];?>_<?=$personalEventData['eventId'];?>").length == 0){
            $('#systemAlerts').append('<li id="personalEvent_<?=$personalEventData['event'];?>_<?=$personalEventData['info'];?>_<?=$personalEventData['eventId'];?>"><?=showUserPicture("$personalEventData[user]", '15', '', true);?><div class="messageMain"><a href="#" onclick="showProfile(\'<?=$personalEventData[user];?>\');"><?=$newEventData2[username];?></a> <?=$description;?></div><div class="messageButton"><a class="btn btn-info btn-mini" target="submitter" style="margin-right:25px;" onclick="deleteFromPersonals(\'<?=$personalEventData[id];?>\');<?=$link;?>$(\'#personalEvent_<?=$personalEventData[event];?>_<?=$personalEventData[info];?>_<?=$personalEventData[eventId];?>\').remove();">Show</a><a href="#" class="btn btn-mini" target="submitter" onclick="deleteFromPersonals(\'<?=$personalEventData[id];?>\');$(\'#personalEvent_<?=$personalEventData[event];?>_<?=$personalEventData[info];?>_<?=$personalEventData[eventId];?>\').remove();">Ignore</a></div></li>');
        }
    </script>
<?
$i++;
}

$friendRequestSql = mysql_query("SELECT buddy, request FROM buddylist WHERE buddy='$_SESSION[userid]' && request='1'");
$countFriendRequests = mysql_num_rows($friendRequestSql);

$zero = 0;
$messagesSql = mysql_query("SELECT * FROM  `messages` WHERE  receiver='$_SESSION[userid]' AND  `read`='0'");
$countMessages = mysql_num_rows($messagesSql);

$countgroup = mysql_num_rows($newGroupSql);


$amount = ($countFriendRequests + $countMessages + $countgroup + $countEvents);

if($i > 1){
	$_SESSION['personalFeed'] = time();
}

    echo"<script>";
    
//check for new fbuddyactions
    
    $buddyActions = $countFriendRequests + $countgroup;
    
if($buddyActions != "0"){
    echo "$('#openFriendRequests').html('$buddyActions');";
}

//check for new messages
if($countMessages != "0"){
    echo "$('#newMessages').html('$countMessages');";
}

if($appAlerts != "0"){
    echo "$('#appAlerts').html('$countEvents');";
}
    echo"</script>";


//if($amount != "0"){
//$text = $amount;
//}
?>