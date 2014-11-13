<?php
      session_start();
  include_once("../../inc/config.php");
  include_once("../../inc/functions.php");
    if($_GET['subaction'] == "add"){
    if(isset($_POST['submit'])){
        $description = $_POST['description'];
        $title = $_POST['title'];
        $privacy = $_POST['privacy'];
        $userlist = $_POST['users'];
        if((isset($description)) && (isset($title)) && (isset($privacy))){
        mysql_query("INSERT INTO `groups` (`title`, `description`, `public`, `admin`) VALUES ('$title', '$description', '$public', '$userid');");
        $groupId = mysql_insert_id();
        if(isset($userlist)){
        foreach ($userlist as &$value) {
        mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$groupId', 'user', '$value', '$time', '$userid');");
        }}
        mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('$groupId', 'user', '$userid', '$time', '$userid', '0');");
        jsAlert("worked:)");
    }else{
        jsAlert("please fill out everything");
    }}
    ?>
    
         <center>
             Add Group
         </center>
         <form action="modules/settings/groups.php&subaction=add" method="post" target="submitter">
         <table>
             <tr>
                 <td>&nbsp;</td>
             </tr>
             <tr>
                 <td>Groupname</td>
                 <td><input type="text" name="title"></td>
             </tr>
             <tr>
                 <td><input type="radio" name="privacy" value="0">Public</td>
                 <td><input type="radio" name="privacy" value="1">Private</td>
             </tr>
             <tr>
                 <td>Description</td>
                 <td><textarea name="description"></textarea></td>
             </tr>
             <tr>
                 <td>Invite Buddies:</td>
                 <td>
                     <div style="width: 200px; height: 100px; overflow: scroll;">
                         <table cellspacing="0">
        <?
        $buddylistSql = mysql_query("SELECT * FROM buddylist WHERE owner='".$_SESSION['userid']."' && request='0'");
        while($buddylistData = mysql_fetch_array($buddylistSql)) {
            
            $userClass = new user($buddylistData['buddy']);
            if(!empty($buddylistData['alias'])){
                $username = $buddylistData['alias'];
            } else{
                $username = $userClass->getUsername();
                }
            if($i%2 == 0) {
                $bgcolor="FFFFFF";
            } else {    
                $bgcolor="e5f2ff";
            }
            $i++;
            ?>
                             <tr bgcolor="#<?=$bgcolor;?>">
                                 <td><input type="checkbox" name="users[]" value="<?=$buddylistData['buddy'];?>"></td>
                                 <td><?=$username;?></td>
                             </tr>
            <?}?> 
                         </table>
                     </div>
                 </td>
             </tr>
             <tr>
                 <td>
                     <input type="submit" value="add" name="submit">
                 </td>
             </tr>
         </table>
         </form>
     
     <?
    }else if(empty($_GET['subaction'])){
?>
     
         <h2>Groups</h2>
         <p><a href="javascript: loader('settingsFrame', 'modules/settings/groups.php?subaction=add');" class="btn">Add group</a></p>
         <div>
             <p>Joined Groups</p>
             <ul>
             </ul>
         </div>
     <?
   
}