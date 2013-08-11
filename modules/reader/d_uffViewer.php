<?

session_start();
$includepath = "../../inc";
$includepath = "$path$includepath";
include("$includepath/config.php");
include_once("$includepath/functions.php");
$id = $_GET[id];

$fileData = mysql_query("SELECT * FROM files WHERE id='$id'");
$fileData = mysql_fetch_array($fileData);

if(authorize($fileData[privacy], "edit", $fileData[owner])){
    $readOnly = "false";
}else{
    $readOnly = "true";
}

$title = $fileData[title];
$activeUsers = explode(";", $fileData[var1]);
?>
    <head>
    </head>
    
<!--this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js-->    
<iframe src="modules/reader/UFF/javascript.php?fileId=<?=$id;?>&readOnly=<?=$readOnly;?>" style="display:none;"></iframe>
<table class="gray-gradient" width="100%" style="position: absolute;">
    <tr height="40">
        <td width="40%">&nbsp;&nbsp;<?=$title;?></td>
        <td width="10%"></td>
        <td width="40%" align="right"><?=$bar;?>&nbsp;oooo</td>
        <td width="10%"></td>
    </tr>
</table>
<div class="uffViewerNav">
    <div style="margin: 10px;">
        <ul>
            <li style="font-size: 11pt; margin-bottom: 05px;"><i class="icon-user"></i>&nbsp;<strong>Active Users</strong></li>
            <?
            //show active users
            foreach($activeUsers AS &$activeUser){
                if(!empty($activeUser)){
                echo"<li onclick=\"openProfile($activeUser);\" style=\"cursor: pointer;\">";
                showUserPicture($activeUser, "11");
                echo "&nbsp;";
                echo useridToUsername($activeUser);
                echo"</li>";
                }
            }
            ?>
        </ul>
    </div>
</div>
<div class="uffViewerMain">
    <textarea class="uffViewer_<?=$id;?> WYSIWYGeditor" id="editor1" onkeyup="alert('ey');">
    </textarea>
</div>