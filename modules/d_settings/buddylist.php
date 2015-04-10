<?php
      session_start();
  include_once("../../inc/config.php");
  include_once("../../inc/functions.php");
?>
         <div id="content">
             
         <h2>Your Buddies</h2>
	         <?
			 echo"<ul>";
                         $buddylistData = new buddylist();
			 $buddies = $buddylistData->buddyListArray();
			 	$i = 0;
	         	foreach($buddies AS $buddy){
	            $blUserSql = mysql_query("SELECT username FROM user WHERE userid='$buddy'");
	            $blUserData = mysql_fetch_array($blUserSql);
	         ?>
	             <li style="font-size: 20px;vertical-align: bottom; margin: 0 5px;" class="buddy_<?=$buddy;?>">
	                <?=showUserPicture($buddy, 22);?>
	                <input type="hidden" name="buddy" value="<?=$buddyEditData['buddy'];?>">
	                	<?=$blUserData['username'];?>
	                	<a href="#" onclick="deleteBuddy('<?=$buddy;?>');" title="delete user form Buddylist" class="btn btn-small" style="margin-left: 15px;">
	                		<i class="icon icon-thrash"></i>
	                	</a>
	               </li>
	         <?$i++; }
				if($i == 0){
					echo"<li>You need to add users to your Buddylist.</li>";
				}
			 echo"</ul>"; ?>
         </div>
         <script type="text/javascript">
function deleteBuddy(id) {
	var answer = confirm("Are you sure to delete this buddy?")
	if (answer){
            
		$("#submitter").load("doit.php?action=deleteBuddy&buddy=" + id +"");
		return false;
	}
	else{
            return false;
	}
}
</script>