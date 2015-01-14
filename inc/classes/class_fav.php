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
        $userClass = new user($user);
        $userFavs = $userClass->getFav();
        $i = 0;
        $output = '';
        
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
                                    $classLinks = new link();
                                    $fileType = $classLinks->getType($item);
                                    $filesClass = new files();
                                    $img = "fileIcons/".$filesClass->getFileIcon($fileType);

                                }
                                $dbClass = new db();
                                $favFolderData = $dbClass->select($typeTable, array('id', $item));
                                    if(isset($favFolderData['name'])){
                                        $favFolderData['title'] = $favFolderData['name'];
                                    }else{
                                        $favFolderData['name'] = ''; //fix so the notice 'undefined index' won't be shown anymore
                                    }
                                    
                                if($i%2 == 0){
                                    $color="FFFFFF";
                                }else {
                                    $color="e5f2ff";
                                }
                                $i++;

                                                            $output .= "<tr class=\"strippedRow\" onmouseup=\"showMenu('folder".$filefdata['id']."')\">";
                                                                    $output .= "<td onmouseup=\"showMenu(".$favFolderData['id'].")\" width=\"35\">&nbsp;<img src=\"./gfx/icons/$img\" height=\"20\"></td>";
                                                                    $output .= "<td onmouseup=\"showMenu(".$favFolderData['id'].")\"><a href=\"#\" onclick=\"$link\">".$favFolderData['name'].""."".$favFolderData['title']."/</a></td>";
                                        if($user == getUser()){

                                        $output .= "<td align=\"right\"><a class=\"btn btn-mini\" onclick=\"fav.remove('$type', '$item')\"><i class=\"icon icon-minus\"></i></a></td>";

                                        }

                                    $output .= "</tr>";
        }
        if($i == 0){
            $output .="<tr style=\"display:table-row; background: none; padding-top: 0px;\">";
                $output .="<td colspan=\"2\" style=\"padding: 5px; padding-top: 0px;\">";
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
        
        
        $db = new db();
        $checkData = $db->insert('fav', array('type', $type, '&&', 'item', $typeid), array('type'));
        
        if(isset($checkData['type'])){
            echo "allready your favourite";
            return false;
        } else {
            $db = new db();
            
            $values['type'] = $type;
            $values['item'] = $typeid;
            $values['user'] = $userid;
            $values['timestamp'] = time();
            
            $db->insert('fav', $values);
            return true;
        }
    }

    function remove($type, $item){
        $db = new db();
        if($db->delete('table', array('type', $type, '&&', 'item', $item))){
            return true;
        }	
    }
}

    
