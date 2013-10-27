<?
require_once("../../inc/config.php");
require_once("../../inc/functions.php");
if(proofLogin()){
	
$userid = getUser();
$buddyName = str_replace("_"," ",$buddy); //get username of receiver
$buddyData = getUserData(usernameToUserid($buddyName));

//get userdata of 
$userData = getUserData($userid);
$buddy = $buddyData[userid];
$buddyName = str_replace(" ","_",$buddyData[username]);
$intWindows = "$buddy.key";
if(isset($_SESSION[$intWindows])){
    $lockIcon = "locked.png";
    $cryptText = "<a href=\"doit.php?action=chatUnsetCrypt&buddy=$buddy&buddyname=$buddyName\" target=\"submitter\">&nbsp;deactivate key</a>";
}else{
    $lockIcon = "lock.png";
}

if(empty($_GET[initter])){
 ?>
      <div class="chatMainFrame">
          <header class="grayBar">
          	  <!-- toggle description key box -->
              <span><a href="javascript: toggleKey('<?=$buddyName;?>');" id="toggleKey_<?=$buddyName;?>"><i class="lockIcon"></i></a></span>
              
              <!-- buddydata -->
              <span><?=showUserPicture($buddyData[userid], 20);?></span>
              <span><a href="#" onclick="showProfile(<?=$buddyData[userid];?>); return false;"><?=$buddyData[username];?></a></span>
          </header>
          <!-- box for caht encription key -->
          <div id="chatKeySettings_<?=$buddyName;?>" class="chatKeySettings">
   
          </div>
          <div id="test_<?=$buddyName;?>" style="position: absolute; top: <?=(30+$top);?>px; right: 0px; bottom:40px; left: 0px; overflow: auto;">
<? } ?>          
<script>
chatEncrypt('<?=$buddyName;?>');
	
</script>
          <div class="chatMainFrame_<?=$buddyName;?>">
<?
function loadConversation($buddy, $limit, $user=NULL){
	if(empty($user)){
		$user = getUser();
	}
}

function showMessages($userid, $buddyId, $limit){
	$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddy' OR sender='$buddy' && receiver='$userid' ORDER BY timestamp DESC LIMIT 0, 10");
while($chatData = mysql_fetch_array($chatSQL)) {
    
    if($chatData['receiver'] == getUser() && $chatData['read'] == "0"){
    mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
    }
    if($chatData['sender'] == $_SESSION['userid'] && $chatData['seen'] == "0"){
    mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
    }
    
    $sender = $chatData[sender];
    $whileid = getUser();
    if($sender == $whileid){
	    $authorid =  $userData[userid];
	    $authorName = $userData[username];
	    $authorImage = $userData[userPicture];
	    $reverse = NULL;
	    $css = 'messageOut'; 
	    $css = 'margin-right: 15px; margin-left: 5px;';
    } else {
	    $authorid =  $buddyData[userid];
	    $authorName = $buddyData[username];
	    $authorImage = $buddyData[userPicture]; 
	    $css = 'margin-left: 15px; margin-right: 5px;';
	    $reverse = "1";   
    }
	//check if message is crypted
    if($chatData[crypt] == "1"){
		$messageClasses = "cryptedChatMessage_$buddyName";
        $message = $chatData[text];
	} else{
		$messageClasses = "";
        $message = $chatData[text];
    }
	
    $message = universeText($message);
    ?>
              <div class="box-shadow space-top chatText" style="<?=$css;?> padding: 10px; padding-bottom: 10px;">
              	<span style="position: absolute; margin-top: -20px; color: #c0c0c0;">
              		<?=showUserPicture($authorid, "15");?><?=$authorName;?>
              	</span>
              	<span class="<?=$messageClasses;?>"><?=$message;?></span>
              </div>
<? }
}
$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$userid' && receiver='$buddy' OR sender='$buddy' && receiver='$userid' ORDER BY timestamp DESC LIMIT 0, 10");
while($chatData = mysql_fetch_array($chatSQL)) {
    
    if($chatData['receiver'] == getUser() && $chatData['read'] == "0"){
    mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
    }
    if($chatData[sender] == $_SESSION[userid] && $chatData['seen'] == "0"){
    mysql_query("UPDATE `messages` SET  `seen`='1' WHERE  id='$chatData[id]'");
    }
    
    $sender = $chatData[sender];
    $whileid = $_SESSION[userid];
    if($sender == $whileid){
    $authorid =  $userData[userid];
    $authorName = $userData[username];
    $authorImage = $userData[userPicture];
    $reverse = NULL;
    $css = 'messageOut'; 
    $css = 'margin-right: 15px; margin-left: 5px;';
    } else {
    
    $authorid =  $buddyData[userid];
    $authorName = $buddyData[username];
    $authorImage = $buddyData[userPicture]; 
    $css = 'margin-left: 15px; margin-right: 5px;';
    $reverse = "1";   
    }
	//check if message is crypted
    if($chatData[crypt] == "1"){
    	
		$messageClasses = "cryptedChatMessage_$buddyName";
        $message = $chatData[text];
	} else{
		
		$messageClasses = "";
        $message = $chatData[text];
    }
    $message = universeText($message);
    ?>
              <div class="box-shadow space-top chatText" style="<?=$css;?> padding: 10px; padding-bottom: 10px;">
              	<span style="position: absolute; margin-top: -20px; color: #c0c0c0;">
              		<?=showUserPicture($authorid, "15");?><?=$authorName;?>
              	</span>
              	<span class="<?=$messageClasses;?>"><?=$message;?></span>
              </div>
<? } 
unset($intWindows);?>
              <div onclick="chatLoadMore('<?=$buddyName;?>', '1');">...load more</div>
          </div>
          <?
          if(empty($_GET[initter])){ ?>
              
          </div>
      </div>
      <div class="chatAdditionalSettings" onclick="$(this).hide(); return true;">
          <ul>
              <li><a class="smiley smiley1" onclick="addStrToChatInput('<?=$buddy;?>', ':\'(');"></a><a class="smiley smiley2" onclick="addStrToChatInput('<?=$buddy;?>', ':|');"></a><a class="smiley smiley3" onclick="addStrToChatInput('<?=$buddy;?>', ';)');"></a><a class="smiley smiley4" onclick="addStrToChatInput('<?=$buddy;?>', ':P');"></a></li>
              <li><a class="smiley smiley5" onclick="addStrToChatInput('<?=$buddy;?>', ':D');"></a><a class="smiley smiley6" onclick="addStrToChatInput('<?=$buddy;?>', ':)');"></a><a class="smiley smiley7" onclick="addStrToChatInput('<?=$buddy;?>', ':(');"></a><a class="smiley smiley8" onclick="addStrToChatInput('<?=$buddy;?>', ':-*');"></a></li>
              <li><a href="#" onclick="popper('doit.php?action=chatSendItem&buddy=<?=$buddyData[userid];?>');">Send File</a></li>
          </ul>
      </div>
      <footer class="blackGradient">
          <center style="margin-top: 6px;">
              <form action="doit.php?action=chatSendMessage&buddy=<?=$buddy;?>&buddyname=<?=$buddyName;?>" method="post" target="submitter"  autocomplete="off" onsubmit="chatMessageSubmit('<?=$buddyName;?>', '<?=$buddy;?>');">
                  <a class="btn" onclick="$('.chatAdditionalSettings').toggle();">
                      <i class="icon-plus"></i>
                  </a>
                  <input type="text" placeholder="type a message..." name="message" class="input border-radius chatInput" id="chatInput_<?=$buddy;?>" style="">
				  <input type="hidden" name="cryption" value="false" id="chatCryptionMarker_<?=$buddyName;?>">
                  <input type="submit" value="Send" class="btn">
              </form>
          </center>
      </footer>
<script>
    $("#toggleKey<?=$buddy;?>").click(function () {
    $("#toggleValue<?=$buddy;?>").show("slow");
    });
</script>
<?}}?>