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

jsAlert(authorize($fileData[privacy], "edit", $fileData[owner])."asd");

$title = $fileData[title];
$activeUsers = explode(";", $fileData[var1]);
?>
    <head>
        <style>
            .uffViewerMain{
                position: absolute;
                top: 61px;
                right: 150px;
                bottom: 0px;
                left: 0px;
                overflow: auto;
            }
            
            .uffViewerMain ol{
                list-style-type:decimal;
            }
            
            .uffViewerMain li{
                height: auto;
            }

            .uffViewerNav{
                position: absolute;
                top: 61px;
                right: 0px;
                bottom: 0px;
                width: 150px;
                background: #FFFFFF;
                border-left: 1px solid #c9c9c9;
            }
            
            .uffDocumentSettings{
                position: absolute;
                margin-top: 40px;
                background: #E5E5E5;
                height: 24px;
                padding: 3px;
                border-bottom: 1px solid #c9c9c9;
                border-right: 1px solid #c9c9c9;
            }
        </style>
    </head>
    
<!--this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js-->    
<iframe src="modules/reader/UFF/javascript.php?fileId=<?=$id;?>&readOnly=<?=$readOnly;?>" style="display:none;"></iframe>


<table class="gray-gradient" width="100%" style="position: absolute;">
    <tr height="40">
        <td width="40%">&nbsp;&nbsp;<?=$title;?>aaaa</td>
        <td width="10%"></td>
        <td width="40%" align="right"><?=$bar;?>&nbsp;</td>
        <td width="10%"></td>
    </tr>
</table>
<div class="uffDocumentSettings">
    asas
</div>
<div class="uffViewerNav">
    <div style="margin: 10px;">
        <ul>
            <li style="font-size: 11pt; margin-bottom: 05px;"><strong>Active Usersa</strong></li>
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
    <style>
        .wysiwyg{
            position: absolute;
            top: 61px;
            right: 151px;
            bottom: 0px;
            left: 0px;
            width: auto!important;
        }
        .wysiwygIframe{
            /* Firefox */
            height: -moz-calc(100% - 27px);
            /* WebKit */
            height: -webkit-calc(100% - 27px);
            /* Opera */
            height: -o-calc(100% - 27px);
            /* Standard */
            height: calc(100% - 27px);
        }
        
    </style>
<div>
<textarea name="wysiwyg" id="wysiwyg" class=" uffViewerMain uffViewer_<?=$id;?>" style="position: absolute; right: 200px; top: 40px;"></textarea>
</div>
<?PHP

//writeUffFile($id, "komm schooooon, was");
//echo showUffFile($id);
//
//if(empty($_GET[action])){
//    
//}else if($_GET[action] == "addFile"){
//    
//}else if($_GET[action] == "editFile"){
//    
//}else if($_GET[action] == "viewFile"){
//    
//}
?>
</div>-->