<?php
session_start();
require_once("../../inc/config.php");
require_once("../../inc/functions.php");
$buddy = $_GET['buddy'];
if(proofLogin()){
	
	
	
    
$buddyName = str_replace("_"," ",$buddy); //get username of receiver
$userid = getUser();
$buddyid = user::usernameToUserid($buddyName);

$buddyData = user::getData($buddyid);

$userClass = new user();
$userData = $userClass->getData($userid);
$buddy = $buddyid;

$intWindows = "$buddyid.key";
if(isset($_SESSION[$intWindows])){
    $lockIcon = "locked.png";
    $cryptText = "<a href=\"doit.php?action=chatUnsetCrypt&buddy=$buddyid&buddyname=$buddyName\" target=\"submitter\">&nbsp;deactivate key</a>";
}else{
    $lockIcon = "lock.png";
}
$messageClass = new message();
$messageClass->markAsRead($buddyid, $userid);
if(empty($_GET['initter'])){
 ?>
      <div class="chatMainFrame">
          <header class="grayBar">
          	  <!-- toggle description key box -->
              <span><a href="javascript: toggleKey('<?=$buddyid;?>');" id="toggleKey_<?=$buddyid;?>"><i class="lockIcon"></i></a></span>
              
              <!-- buddydata -->
              <span><?=showUserPicture($buddyData['userid'], 20);?></span>
              <span><a href="#" onclick="showProfile(<?=$buddyData['userid'];?>); return false;"><?=$buddyData['username'];?></a></span>
          </header>
          <!-- box for caht encription key -->
          <div id="chatKeySettings_<?=$buddyid;?>" class="chatKeySettings">
          </div>
          <div id="test_<?=$buddyName;?>" class="dialoge">
<? } ?>          
		  <script>
		   	chatDecrypt('<?=$buddyid;?>');
		  </script>
          <div class="chatMainFrame_<?=$buddyName;?>">
			  <?
			  $messageClass->showMessages($userid, $buddyid, "0,10");
		      unset($intWindows);?>
              <div onclick="chatLoadMore('<?=$buddyid;?>', '1');">...load more</div>
          </div>
          <?
          if(empty($_GET[initter])){ ?>
              
          </div>
      </div>
      <div class="chatAdditionalSettings" onclick="$(this).hide(); return true;">
          <ul>
              <li><a class="smiley smiley1" onclick="addStrToChatInput('<?=$buddyid;?>', ':\'(');"></a><a class="smiley smiley2" onclick="addStrToChatInput('<?=$buddyid;?>', ':|');"></a><a class="smiley smiley3" onclick="addStrToChatInput('<?=$buddyid;?>', ';)');"></a><a class="smiley smiley4" onclick="addStrToChatInput('<?=$buddyid;?>', ':P');"></a></li>
              <li><a class="smiley smiley5" onclick="addStrToChatInput('<?=$buddyid;?>', ':D');"></a><a class="smiley smiley6" onclick="addStrToChatInput('<?=$buddyid;?>', ':)');"></a><a class="smiley smiley7" onclick="addStrToChatInput('<?=$buddyid;?>', ':(');"></a><a class="smiley smiley8" onclick="addStrToChatInput('<?=$buddyid;?>', ':-*');"></a></li>
              <li><a href="#" onclick="popper('doit.php?action=chatSendItem&buddy=<?php echo $buddyData['userid'];?>');">Send File</a></li>
          </ul>
      </div>
      <footer class="blackGradient">
          <center style="margin-top: 6px;">
              <form action="#" method="post" target="submitter"  autocomplete="off" onsubmit="chatMessageSubmit('<?=$buddyid;?>'); return false;">
                  <a class="btn" onclick="$('.chatAdditionalSettings').toggle();">
                      <i class="icon-plus"></i>
                  </a>
                  <input type="text" placeholder="type a message..." name="message" class="input border-radius chatInput" id="chatInput_<?=$buddyid;?>" style="">
				  <input type="hidden" name="cryption" value="false" id="chatCryptionMarker_<?=$buddyid;?>">
                  <input type="submit" value="Send" class="btn">
              </form>
          </center>
      </footer>
<script>
    $("#toggleKey<?=$buddyid;?>").click(function () {
    $("#toggleValue<?=$buddyid;?>").show("slow");
    });
</script>
<?}}?>