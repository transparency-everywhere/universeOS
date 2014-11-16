<?php
session_start();

    $path = "../../";
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?
$includepath = "inc";
$includepath = "$path$includepath";
include("$includepath/config.php");
include("$includepath/functions.php");


//define head
switch($_GET[type]){
    case link:
        break;
    case youTube:
        if(isset($_GET['external'])){
            $vId = $_GET['id'];
            $youtubeClass = new youtube('', $vId);
            $title  = $youtubeClass->getTitle();
            
            //add dropdown with all available playlists to $bar
            
            if(proofLogin()){
            //get all the groups in which the current user is
            $userGroupsSql = mysql_query("SELECT `group` FROM `groupAttachments` WHERE item='user' AND itemId='$_SESSION[userid]'");
            while($userGroupsData = mysql_fetch_array($userGroupsSql)){
                $userGroups[] = "$userGroupsData[group]";
            }
            
            //add them to the query
            foreach($userGroups AS &$userGroup){
                    $query = "$query OR INSTR(`privacy`, '{$userGroup}') > 0";
            }
            
                //get playlists for user and groups
                $playListsSql = mysql_query("SELECT id, title FROM playlist WHERE user='$_SESSION[userid]' $query");
                while($playListsData = mysql_fetch_array($playListsSql)){
                    
                    //add them to dropdown
                    $options = "<option value=\"$playListsData[id]\">$playListsData[title]</option>$options";
                }
                //display Bar
                $bar .= "<div class=\"youTubePlayListSelect\"><form action=\"doit.php?action=addYouTubeItemToPlaylistVeryLongName&vId=$vId\" target=\"submitter\" method=\"post\"><select name=\"playlistId\">$options</select>&nbsp;<input type=\"submit\" name=\"submit\" value=\"add\" class=\"btn\" style=\"margin-top: -11px;\"></form>&nbsp;</div>";

            }
        }
        break;
        case 'image':
        $documentSQL = mysql_query("SELECT * FROM files WHERE id='$_GET[id]'");
        $documentData = mysql_fetch_array($documentSQL);
        $documentElementSQL = mysql_query("SELECT * FROM elements WHERE id='$documentData[folder]'");
        $documentElementData = mysql_fetch_array($documentElementSQL);
        $documentFolderSQL = mysql_query("SELECT * FROM folders WHERE id='$documentElementData[folder]'");
        $documentFolderData = mysql_fetch_array($documentFolderSQL);
        $folderPath = urldecode($documentFolderData[path]);
        $folderClass = new folder($documentFolderData['id']);
        $path = $folderClass->getPath();
            $score = "file";
            $title = $documentElementData['title'];
            $bar = "<a href=\"javascript: zoomIn();\" id=\"zoomIn\" class=\"btn\" title=\"zoom in\"><img src=\"./gfx/icons/zoomIn.png\" height=\"10\" border=\"0\"></a>&nbsp;<a id=\"zoomOut\" href=\"javascript: zoomOut();\" class=\"btn\" style=\"\" title=\"zoom out\"><img src=\"./gfx/icons/zoomOut.png\" height=\"10\" border=\"0\"></a>&nbsp;$download";

        break;
}

if($_GET[type] !== "youTube"){
$download = "<a href=\"doit.php?action=download&fileId=$_GET[id]\" target=\"submitter\" class=\"btn\" title=\"download file\"><img src=\"./gfx/icons/download.png\" alt=\"download\" height=\"10\"></a>";
}

if($_GET[type] == "link"){
    $linkSql = mysql_query("SELECT * FROM links WHERE id='$_GET[id]'");
    $linkData = mysql_fetch_array($linkSql);
    $score = "link";
}else if($_GET[type] == "youTube" && isset($_GET[external])){
    //empty if case needs to be here, because otherwise the else case would be effected
}else if($_GET[type] == "image"){
    //empty if case needs to be here, because otherwise the else case would be effected
}else{
	if(isset($_GET[id])){
	$documentSQL = mysql_query("SELECT * FROM files WHERE id='$_GET[id]'");
	$documentData = mysql_fetch_array($documentSQL);
	 $documentElementSQL = mysql_query("SELECT * FROM elements WHERE id='$documentData[folder]'");
	 $documentElementData = mysql_fetch_array($documentElementSQL);
	  $documentFolderSQL = mysql_query("SELECT * FROM folders WHERE id='$documentElementData[folder]'");
	  $documentFolderData = mysql_fetch_array($documentFolderSQL);
	  $folderPath = urldecode($documentFolderData[path]);
          $folderClass = new folder($documentFolderData['id']);
	  $path = $folderClass->getPath();
	    $score = "file";
	    $title = $documentElementData['title'];
	    $bar = $download;
	}
}
        if(isset($_GET[playList])){
            $title .= "<span id=\"togglePlayListTitle$_GET[playList]\" class=\"readerPlayListTitle\"></span>";
            $bar .= "<div id=\"togglePlayList$_GET[playList]\" class=\"readerPlayListToggle\"></div>";
        }
?>
<table class="gray-gradient" width="100%" style="position: absolute;">
    <tr height="40">
        <td width="40%">&nbsp;&nbsp;<?=$title;?></td>
        <td width="10%"></td>
        <td width="40%" align="right"><?=$bar;?>&nbsp;</td>
        <td width="10%">
<?
if($_GET[type] !== "youTube" && !isset($_GET[external])){
    $item = new item($score, $_GET['id']);   
    echo $item->showScore();
}?>
        </td>
        
    </tr>
</table>
<?
if($_GET[type] == "document"){
    ?>
<div class="windowContent">
    <iframe src="http://universeos.org/<?=$path;?><?=urldecode($documentData[filename]);?>" style="width: 99%; height:100%;"></iframe>
</div>
<? }else if($_GET[type] == "video"){
    ?>
<div class="windowContent"><iframe src="./modules/reader/player.php?id=<?=$_GET[id];?>" width="100%" height="85%"></iframe></div>

<?
}else if($_GET[type] == "link"){
    
        //show RSS File
        if($linkData[type] == "RSS"){
        $url = $linkData[link];
        $rss = new rss();
        ?>
        <div class="rssReader windowContent">
                <?=$rss->getRssfeed("$url","$linkData[title]","auto",10,3);?>
        </div>
        <?
        }
    }
    else if($_GET[type] == "image"){ ?>
        <div id="<?=$documentElementData[title];?>ImageViewer" class="readerImageTab">
            <center> 
                <img src="<?=$path;?>/<?=$documentData[title];?>" width="100%" id="viewedPicture">
            </center>
        </div>
        <div style="position: absolute; height: 120px; right:0px; bottom: 0px; left: 0px; overflow: auto; background: #000; color: #FFF;">
            <table style="width: 100%; height: 120px;" align="center" class="blackGradient">
                <tr>
                <?
                $documentSQL = mysql_query("SELECT id, title, folder FROM files WHERE folder='$documentElementData[id]'");
                while($documentData = mysql_fetch_array($documentSQL)){
                        $documentFolderSQL = mysql_query("SELECT path FROM folders WHERE id='$documentElementData[folder]'");
                        $documentFolderData = mysql_fetch_array($documentFolderSQL);
                    if($documentElementData['title'] == "profile pictures"){
                        $folderPath = urldecode($documentFolderData['path']);
                        $folderPath = "http://universeos.org/upload$folderPath/thumb/300/";
                    }else{
                        $folderClass = new folder($documentElementData['folder']);
                        $path = $folderClass->getPath()."thumbs/";
                    }?>
                        <td oncontextmenu="showMenu('image<?=$documentData['id'];?>'); return false;"><div id="viewerClick<?=$documentData[id];?>"><a href="#" onclick="filesystem.tabs.updateTabContent('Open <?=substr("$documentElementData[title]",0,10);?>' ,gui.loadPage('./modules/reader/showfile.php?type=image&id=<?=$documentData[id];?>')); ddAjaxContentToTab('Open <?=substr("$documentElementData[title]",0,10);?>','./modules/reader/showfile.php?type=image&id=<?=$documentData[id];?>');return false"><img src="<?=$path;?><?=$documentData[title];?>" height="100px"></a></div></td>   
                   <?php
                    $contextMenu = new contextMenu("image", $documentData['id'], $documentElementData['title']);
                    $contextMenu->showRightClick();
               }?>
                    <script>
                        $("#viewerClick<?=$documentData[id];?>").click(function (){
                            alert("lol");
                        });
                     function zoomIn(){
                        var PictureWidth = $("#viewedPicture").width();
                        var newWidth = PictureWidth*1.25;
                        $("#viewedPicture").css("width", newWidth);
                     }
                    </script>
               </tr>
             </table>
        </div>
        
   <?}else if($_GET[type] == "youTube"){
       if(isset($_GET[vId])){
       $id = "$_GET[vId]";
       }else{
       $id = "$_GET[id]";
       }
       ?>
        <div id="playListReaderTab" style="background: #000000;">
         <iframe src="doit.php?action=showYoutube&id=<?=$id;?>&playList=<?=$_GET[playList];?>&row=<?=$_GET[row];?>" width="100%" height="100%" id="playListLoaderFrame" name="playListLoaderFrame"></iframe><br>
        </div>
       <?
   }
?>
