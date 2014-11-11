<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class element {
    public $id;
    
    function __construct($id=NULL){
        if($id != NULL){
            $this->id = $id;
        }
            
    }
    //put your code here
    
    function create($folder, $title, $type, $user, $privacy){
    	$title = mysql_real_escape_string($title);
        $time = time();
        mysql_query("INSERT INTO `elements` (`title`, `folder`, `type`, `author`, `timestamp`, `privacy`) VALUES ('$title', '$folder', '$type', '$user', '$time', '$privacy');");
        return mysql_insert_id();
    }
    
    function getData($elementId=NULL){
        if($elementId == NULL)
            $elementId = $this->id;
        
        $query = mysql_query("SELECT * FROM `elements` WHERE id='".save($elementId)."'");
        $data = mysql_fetch_array($query);
		
        return $data;
    }
        
    
    function delete($elementId=NULL){
        if($elementId == NULL)
            $elementId = $this->id;
         
            $checkElementSql = mysql_query("SELECT privacy, folder, author FROM elements WHERE id='$elementId'");
            $checkElementData = mysql_fetch_array($checkElementSql);
			
			//deleting all  atached items
			//this should be after the deletion of the mysql row of the file
			//but the functions need fileinformations
			$files = $this->getFiles($elementId);
			$links = $this->getLinks($elementId);
			
                    
                    if(authorize($checkElementData['privacy'], "edit", $checkElementData['author'])){

					//delete all Files
					foreach($files AS &$fileId){
						deleteFile($fileId);
					}
					
					//delete all links
                                        $linkClass = link();
					foreach($links AS &$linkId){
                                            $linkClass->deleteLink($linkId);
					}
					//delete all comments
                                        $commentClass = new comments();
                                        $commentClass->deleteComments("element", $elementId);
					//delete all feeds
                                        $feedClass = new feed();
                                        $feedClass->deleteFeeds("element", $elementId);
                                //delete all shortcuts
                                $shortcutClass = new shortcut();
                                $shortcutClass->deleteShortcuts("element", $elementId);


                        if(mysql_query("DELETE FROM elements WHERE id='$elementId'")){


                            return true;
                        }else{
                            return false;
                        }


                    }
               
    }
	
	function getFiles($elementId){
        if($elementId == NULL)
            $elementId = $this->id;
		$fileSQL = mysql_query("SELECT id FROM files WHERE folder='".mysql_real_escape_string($elementId)."'");
		while($fileData = mysql_fetch_array($fileSQL)){
			$return[] = $fileData[id];
		}
		
		return $return;
	}
	
	function getLinks($elementId){
            if($elementId == NULL)
                $elementId = $this->id;
		$linkSQL = mysql_query("SELECT id FROM links WHERE folder='".mysql_real_escape_string($elementId)."'");
		while($linkData = mysql_fetch_array($linkSQL)){
			$return[] = $linkData[id];
		}
		
		return $return;
	}
	function getName($elementId=NULL){
            if($elementId == NULL)
                $elementId = $this->id;
            
            $checkElementSql = mysql_query("SELECT title FROM elements WHERE id='".mysql_real_escape_string($elementId)."'");
            $checkElementData = mysql_fetch_array($checkElementSql);
			
			return $checkElementData['title'];
	}
        
	
    function showFileList($element=NULL, $fileQuery=NULL, $git=NULL, $subpath=NULL){
            if($element == NULL){
                $element = $this->id;
            }
            //shows list of files which are in the element $element or which meets criteria of $fileQuery
            //if git=1 => only basic information without itemsettings etc.
            if(empty($element)){
                $query = $fileQuery;
            }else{
                $query = "folder='".mysql_real_escape_string($element)."'";
                $shortCutQuery = "WHERE parentType='element' AND parentId='$element'";
            }

                    $i = 0;
                $fileListSQL = mysql_query("SELECT * FROM files WHERE $query");
                while($fileListData = mysql_fetch_array($fileListSQL)) {
                    $i++;
                    if(authorize($fileListData['privacy'], "show", $fileListData['owner'])){
                    $title10 = substr("$fileListData[title]", 0, 10);
                    $link = "openFile('$fileListData[type]', '$fileListData[id]', '$title10');";
                    if($fileListData['type'] == "audio/mpeg"){
                        $rightLink = "startPlayer('file', '$fileListData[id]')";
                        $image = "../music.png";
                    }
                    else if($fileListData['type'] == "video/mp4"){
                        //define link for openFileFunction
                        $openFileType = "video";

                        //define openFile function
                        $link = "openFile('$openFileType', '$fileListData[id]', '$title10');";
                        $rightLink = "createNewTab('reader_tabView','See $title10','','./modules/reader/player.php?id=$fileListData[id]',true);return false";
                    }
                    else if($fileListData['type'] == "UFF"){
                    //standard from know on (19.02.2013)

                        //define link for openFileFunction
                        $openFileType = "UFF";

                        //define openFile function
                        $link = "openFile('$openFileType', '$fileListData[id]', '$title10');";
                    }
                    else if($fileListData['type'] == "text/plain" OR $fileListData['type'] == "application/pdf" OR $fileListData['type'] == "text/x-c++"){
                    //standard from know on (19.02.2013)

                        //define link for openFileFunction
                        $openFileType = "document";

                        //define openFile function
                        $link = "openFile('$openFileType', '$fileListData[id]', '$title10');";
                    }
                    else if($fileListData['type'] == "image/jpeg" OR $fileListData['type'] == "image/png" OR $fileListData['type'] == "image/gif"){
                    //if a image is opened the tab is not named after the file
                    //it is named after the parent element, because images are
                    //shown in a gallery with all the images listed in the parent
                    //element
                    $elementData = mysql_fetch_array(mysql_query("SELECT title FROM elements WHERE id='$fileListData[folder]'"));
                    $elementTitle10 = substr("$elementData[title]", 0,10);



                        //define link for openFileFunction
                        $openFileType = "image";

                        //define openFile function
                        $link = "openFile('$openFileType', '$fileListData[id]', '$elementTitle10');";
                    }
                    $image = getFileIcon($fileListData['type']);
                        ?>
                        <tr class="strippedRow file_<?=$fileListData[id];?>" oncontextmenu="showMenu('file<?=$fileListData['id'];?>'); return false;" height="40px">
                            <td width="30px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$fileListData['type'];?>" height="22"></td>
                            <td><a href="<?=$subpath;?>out/?file=<?=$fileListData[id];?>" onclick="<?=$link;?> return false"><?=substr($fileListData[title],0,30);?></a></td>
                            <td width="80" align="right">
                                    <? if($fileListData['download']){ ?>
                                        <a href="./out/download/?fileId=<?=$fileListData['id'];?>" target="submitter" class="btn btn-mini" title="download file"><i class="icon-download"></i></a>
                                    <? } 
                                    if(!$git){
                                        $contextMenu = new contextMenu('file', "$fileListData[id]");
                                        echo $contextMenu->showItemSettings();
                                    }?>
                            </td>
                            <td width="50"><?=showScore(file, $fileListData['id']);?></td>
                        </tr>
                        <?php
                        if(!$git){
                            $contextMenu = new contextMenu("file", $fileListData['id'], $title10, $openFileType);
                            $contextMenu->showRightClick();
                        }

                }}
                $linkListSQL = mysql_query("SELECT * FROM links WHERE $query");
                while($linkListData = mysql_fetch_array($linkListSQL)) {
                    $title10 = substr($linkListData['title'], 0, 10);

                    $link = "$link&id=$linkListData[id]";
                    if($linkListData['type'] == "youTube"){
                        $youtubeClass = new youtube($linkListData['link']);
                        $vId = $youtubeClass->getId();
                        $link = "openFile('youTube', '$linkListData[id]', '$title10', '$vId');";
                    }

                    if($linkListData['type'] == "audio/mp3"){
                        $rightLink = "startPlayer('file', '$fileListData[id]')";
                    }

                    if($linkListData['type'] == "RSS"){
                        $link = "openFile('RSS', '$linkListData[id]', '$title10');";
                    }
                    $image = getFileIcon($linkListData['type']);


                        $i++;
                    ?>
                    <tr class="strippedRow link_<?=$linkListData['id'];?>" oncontextmenu="showMenu('link<?=$linkListData['id'];?>'); return false;" height="40px">
                        <td width="65px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$linkListData['type'];?>" height="22px"></td>
                        <td><a href="#" onclick="<?=$link;?>"><?=substr($linkListData['title'],0,30);?></a></td>
                        <td width="70" align="right">
                            <?php
                            if(!$git){
                                    $contextMenu = new contextMenu('link', $linkListData['id']);
                                    $contextMenu->showItemSettings();
                            }
                            ?>
                            </td>
                        <td><?=showScore(link, $linkListData['id']);?></td>
                    </tr>
                    <?php
                        if(!$git){
                            $contextMenu = new contextMenu("link", $linkListData['id'], $title10, $linkListData['type']);
                            echo $contextMenu->showRightClick();
                        }
                    }



                    $shortCutSql = mysql_query("SELECT * FROM internLinks $shortCutQuery");
                    while($shortCutData = mysql_fetch_array($shortCutSql)){
                        $i++;
                        if($shortCutData['type'] == "file"){

                            $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy, type FROM files WHERE id='$shortCutData[typeId]'"));
                            $title10 = substr($shortCutItemData['title'], 0,10);
                            $title = $shortCutItemData['title'];
                            if($shortCutItemData['type'] == "UFF"){
                            //standard from know on (19.02.2013)

                                //define link for openFileFunction
                                $openFileType = "UFF";

                                //define openFile function
                                $link = "openFile('$openFileType', '$shortCutItemData[typeId]', '$title10');";
                            }
                            else if($shortCutItemData['type'] == "text/plain" OR $shortCutItemData['type'] == "application/pdf"){
                            //standard from know on (19.02.2013)

                                //define link for openFileFunction
                                $openFileType = "document";

                                //define openFile function
                                $link = "openFile('$openFileType', '$shortCutItemData[typeId]', '$title10');";
                            }






                        }else if($shortCutData['type'] == "link"){

                            $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, link, privacy, type FROM links WHERE id='$shortCutData[typeId]'"));
                            $title10 = substr($shortCutItemData['title'], 0,10);
                            $title = $shortCutItemData['title'];
                            if($shortCutItemData['type'] == "youTube"){
                                $youtubeClass = new youtube($shortCutItemData['link']);
                                $vId = $youtubeClass->getId();
                                $link = "openFile('youTube', '$vId', '$title10');";
                            }

                            if($shortCutItemData['type'] == "RSS"){
                                $link = "openFile('RSS', '$shortCutData[typeId]', '$title10');";
                            }
                        }


                        $image = getFileIcon($shortCutItemData['type']);

                        echo'<tr>';
                            echo'<td>';
                                echo"&nbsp;<img src=\"$subpath"."gfx/icons/fileIcons/$image\" height=\"22\"><i class=\"shortcutMark\"> </i>";
                            echo"</td>";
                            echo'<td colspan="3">';
                                echo"<a href=\"./out/?$shortCutData[type]=$shortCutData[typeId]\" onclick=\"$link return false\">$title</a>";
                            echo"</td>";
                        echo'</tr>';
                    }
                                            if($i == 0){

                                echo'<tr class="strippedRow" style="height: 20px;">';
                                    echo'<td colspan="3">';
                                        echo'This Element is empty.';
                                    echo'</td>';
                                echo'</tr>';
                                            }

        }

}
