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
	                mysql_query("DELETE FROM buddylist WHERE owner='$_SESSION[userid]' && buddy='".mysql_real_escape_string($_GET[buddy])."' LIMIT 1");
	                mysql_query("DELETE FROM buddylist WHERE owner='".mysql_real_escape_string($_GET[buddy])."' && buddy='$_SESSION[userid]' LIMIT 1");
	                jsAlert("worked :(");
	            }
			 echo"<table>";
			 $buddies = buddyListArray();
	         	foreach($buddies AS $buddy){
	            $blUserSql = mysql_query("SELECT username FROM user WHERE userid='$buddy'");
	            $blUserData = mysql_fetch_array($blUserSql);
	         ?>
	             <tr>
	                <td><?=showUserPicture($buddyEditData[buddy], 30);?></td>
	                <td></td>
	                <form action="modules/settings/index.php?action=friends&reload=1" target="submitter" method="post"><?=$userpicture;?>&nbsp;
	                    <input type="hidden" name="buddy" value="<?=$buddyEditData[buddy];?>"></td>
	                <td><?=$blUserData[username];?><a href="#" onclick="confirmation(<?=$buddyEditData[buddy];?>);"><img src="./gfx/delete_2.png" width="16"></a>
	                    <input type="submit" name="submit" value="save">
	                </form></td>
	             </tr>
	         <? }
			 echo"</table>"; ?>
         </div>
         <script type="text/javascript">
function confirmation(id) {
	var answer = confirm("Are you sure to delete this buddy?")
	if (answer){
            
		$("#submitter").load("modules/settings/buddylist.php?reload=1&delete=1&buddy=" + id +"");
		return false;
	}
	else{
            return false;
	}
}
</script>