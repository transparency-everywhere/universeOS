<?php
      session_start();
  include_once("../../inc/config.php");
  include_once("../../inc/functions.php");
    if(isset($_POST[submit])){
        $newAlias = htmlspecialchars($_POST[alias]);
        mysql_query("UPDATE buddylist SET alias='$newAlias' WHERE owner='$_SESSION[userid]' && buddy='$_POST[buddy]'");
        jsAlert("worked:)");
    }
?>
         <div id="content">
             
             
         <h2>Your Buddies</h2>
	         <?
	         
	            if(isset($_GET[delete])){
	                mysql_query("DELETE FROM buddylist WHERE owner='$_SESSION[userid]' && buddy='".mysql_real_escape_string($_GET['buddy'])."' LIMIT 1");
	                mysql_query("DELETE FROM buddylist WHERE owner='".mysql_real_escape_string($_GET['buddy'])."' && buddy='$_SESSION[userid]' LIMIT 1");
	                jsAlert("worked :(");
	            }
			 echo"<ul>";
			 $buddies = buddyListArray();
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
	                		<i class="icon-remove"></i>
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