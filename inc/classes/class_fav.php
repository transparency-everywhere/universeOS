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
class fav {
    function show($user=NULL){ 
                            if($user == NULL){
                                    $user = getUser();
                            }

                            $userFavs = getUserFavs($user);
                                                    $i = 0;
                                                    $output;
                                            foreach($userFavs AS $filefdata){
                                                            $item = $filefdata['item'];
                                $type = $filefdata['type'];

                                //derive the table and the image from fav-type
                                if($type == "folder"){
                                    $typeTable = "folders";
                                    $img = "filesystem/folder.png";
                                    $link = "openFolder($item); return false;";
                                }else if($type == "element"){
                                    $typeTable = "elements";
                                    $img = "filesystem/element.png";
                                    $link = "openElement($item); return false;";
                                }else if($type == "file"){
                                    $typeTable = "files";
                                    $fileClass = new file($item);
                                    $fileType = $fileClass->getFileType();
                                    $filesClass = new files();
                                    $img = "fileIcons/".$filesClass->getFileIcon($fileType);

                                }else if($type == "link"){
                                    $typeTable = "links";
                                    $fileType = linkIdToFileType($item);
                                    $filesClass = new files();
                                    $img = "fileIcons/".$filesClass->getFileIcon($fileType);

                                }
                                $dbClass = new db();
                                $favFolderData = $dbClass->select($typeTable, array('id', $item));
                                if($filefdata['type'] == "folder"){
                                $filefdata['title'] = $filefdata['name'];
                                }
                                if($i%2 == 0){
                                    $color="FFFFFF";
                                }else {
                                    $color="e5f2ff";
                                }
                                $i++;

                                                            $output .= "<tr class=\"strippedRow\" onmouseup=\"showMenu('folder$filefdata[id]')\">";
                                                                    $output .= "<td onmouseup=\"showMenu(".$favFolderData['id'].")\" width=\"35\">&nbsp;<img src=\"./gfx/icons/$img\" height=\"20\"></td>";
                                                                    $output .= "<td onmouseup=\"showMenu(".$favFolderData['id'].")\"><a href=\"#\" onclick=\"$link\">".$favFolderData['name'].""."".$favFolderData['title']."/</a></td>";
                                        if($user == getUser()){

                                        $output .= "<td align=\"right\"><a class=\"btn btn-mini\" onclick=\"fav.remove('$type', '$item')\"><i class=\"icon-remove\"></i></a></td>";

                                        }

                                    $output .= "</tr>";
                             }
                                                       if($i == 0){
                                                                    $output .="<tr>";
                                                                            $output .="<td colspan=\"2\" style=\"padding: 5px; padding-top: 12px;\">";
                                                                            $output .="You don't have any favourites so far. Add folders, elements, files, playlists or other items to your favourites and they will appear here.";
                                                                            $output .="</td>";
                                                                    $output .="</tr>";
                                                       }

                                                       return $output;


    }
    function favTable($type){
       if($type == "folder"){
           $typeTable = "folders";
       }else if($type == "element"){
           $typeTable = "elements";
       }else if($type == "file"){
           $typeTable = "files";
       }
       
       echo $typeTable;
    }

    function addFav($type, $typeid, $userid=NULL){
        
        if(empty($userid))
            $userid = getUser();
        
        $check = mysql_query("SELECT type FROM fav WHERE type='$type' && item='$typeid'");
        $checkData = mysql_fetch_array($check);
        if(isset($checkData['type'])){
            echo "allready your favourite";
            return false;
        } else {
            mysql_query("INSERT INTO fav (`type` ,`item` ,`user` ,`timestamp`) VALUES('".save($type)."', '".save($typeid)."', '".$userid."', '".time()."');"); 
            return false;
        }
    }

    function remove($type, $item){
		
			if(mysql_query("DELETE FROM fav WHERE type='".mysql_real_escape_string($type)."' AND user='$_SESSION[userid]' AND item='".mysql_real_escape_string($item)."'")){
				return true;
			}
		
    }
}

    
