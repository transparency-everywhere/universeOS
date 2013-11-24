<?php
require_once("inc/config.php");
require_once("inc/functions.php");

//place of the playlist we are right now, useless if shuffle=1

$playList = save($_GET['playList']);
$row = $_GET['row'];
if($row == 0){
    mysql_query("UPDATE playlist SET played = played + 1 WHERE id='$playList'");
}
$i = "0";
  $playListSql = mysql_query("SELECT * FROM playlist WHERE id='$playList'");
  $playListData = mysql_fetch_array($playListSql);
                    
                    //get all the files and put them into $rightlink
                    $queryFiles = commaToOr("$playListData[files]", "id");    
                    $playListFolderSql = mysql_query("SELECT * FROM files WHERE $queryFiles");
                    while($playListFolderData = mysql_fetch_array($playListFolderSql)){
                        $rightLink[] = "playPlaylist('$playList', '$row', '$playListFileData[id]')";
                    $i++;
                    }
                    
                    //get all the link and put them into $rightlink
                    $queryLinks = commaToOr("$playListData[links]", "id");
                    $playListLinkSql = mysql_query("SELECT * FROM links WHERE $queryLinks");
                    while($playListLinkData = mysql_fetch_array($playListLinkSql)){
                        
                        if($playListLinkData[type] == "youTube"){
                        $vId = youTubeURLs($playListLinkData[link]);
                        $link = "./modules/reader/showfile.php?type=youTube&vId=$vId&playList=$playList&row=$_GET[row]";
                        $link2 = "doit.php?action=showYoutube&id=$vId&playList=$playList&row=$_GET[row]";
                        }
                        
                        $rightLink[] = "
                        if($('#playListFrame_$playList').length == 0){
                        	alert('ufta');
                        	openFile('youTube', '', '".addslashes($playListData[title])."', '$vId', '$playList', '$row');
                        }
                        if($('#playListiFrame_$playList').length > 0){
                        	
                        loadIframe('playListiFrame_$playList', '$link2');
                        }
                        ";
                        $i++;
                    }
                    
                    //get all the youTubesongs which are not links and put them into $rightlink
                    $array = explode(";", $playListData[youTube]);
                    foreach ($array as &$value){
                        $vId = "$value";
                        $link = "./modules/reader/showfile.php?type=youTube&vId=$vId&playList=$playList&row=$_GET[row]";
                        $link2 = "doit.php?action=showYoutube&id=$vId&playList=$playList&row=$_GET[row]";
                        $title = addslashes($playListData['title']);
                        $rightLink[] = "
                        if($('#playListFrame_$playList').length == 0){
                        	openFile('youTube', '', '$title', '$vId', '$playList', '$row');
                        }
                        if($('#playListiFrame_$playList').length > 0){
                        loadIframe('playListiFrame_$playList', '$link2');
                        }
                        ";
                        $i++;
                    }
?>
<script>
    $(document).ready( function(){
    <?=$rightLink[$row];?> 
    });
</script>
