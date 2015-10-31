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
class item {
    public $type;
    public $typeid;
    function __construct($type=NULL, $typeid=NULL){
           $this->type = escape::sql($type);
           $this->typeid = escape::sql($typeid);
        }
        //put your code here
    function plusOne(){
           $type = $this->type;
           $typeid = $this->typeid;
           
           if($type == "comment"){
               $tableName = 'comments';
           }else if($type == "feed"){
               $tableName = 'feed';
           }
           if($type == "file"){
               $tableName = 'files';
           }
           if($type == "folder"){
               $tableName = 'folders';
           }
           if($type == "element"){
               $tableName = 'elements';

           }
           if($type == "file"){
               $tableName = 'file';

           }
           if($type == "link"){
               $tableName = 'links'; 
           }
           //score++
           $db = new db();
           $db->query("UPDATE `$tableName` SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
           return $this->getScore();
           }
    function minusOne(){

           $type = $this->type;
           $typeid = $this->typeid;
           
           if($type == "comment"){
               $tableName = 'comments';
           }else if($type == "feed"){
               $tableName = 'feed';
           }
           if($type == "file"){
               $tableName = 'files';
           }
           if($type == "folder"){
               $tableName = 'folders';
           }
           if($type == "element"){
               $tableName = 'elements';

           }
           if($type == "file"){
               $tableName = 'file';

           }
           if($type == "link"){
               $tableName = 'links'; 

           }
           //score--
           $db = new db();
           $db->query("UPDATE `$tableName` SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
           
           return $this->getScore();
           }

    function getScore(){
        
           $type = $this->type;
           $typeid = $this->typeid;
           
                   if($type == "comment"){
                       $table = 'comments';
                   }
                   else if($type == "feed"){
                       
                       $table = 'feed';
                   }
                   else if($type == "folder"){
                       $table = 'folders';
                   }
                   else if($type == "element"){
                       $table = 'elements';
                   }
                   else if($type == "file"){
                       $table = 'files';
                   }
                   else if($type == "link"){
                       $table = 'links';
                   }
                   
                   $db = new db();
                   $scoreData = $db->select($table, array('id', $typeid), array('id', 'votes', 'score'));
                   
                   return $scoreData['score'];
           
    }
           
    function showScore($reload=NULL) {

           $type = $this->type;
           $typeid = $this->typeid;
            if(proofLogin()){
                   $score = $this->getScore();
                   
                   if(!isset($reload)){
                       $output =  "<div class=\"score$type$typeid\">";
                   }


                               if($score > 0){
                                    $class = "btn-success";
                               }else if($score < 0){
                                    $class = "btn-warning";
                               }else{
                                    $class = '';
                               }
                               $output .= '<div class="score">';
                               $output .= "<a class=\"btn btn-xs\" href=\"#\" onclick=\"item.minusOne('".$type."', '".$typeid."');\"><i class=\"icon icon-dislike\"></i></a>";
                               $output .= "<a class=\"btn btn-xs $class counter\" href=\"#\">".$score."</a>";
                               $output .= "<a class=\"btn btn-xs\" href=\"#\" onclick=\"item.plusOne('".$type."', '".$typeid."');\"><i class=\"icon icon-like\"></i></a>";
                               $output .= '</div>';
                   if(!isset($reload)){
                       $output .=  "</div>";
                   }

                               return $output;
            }

           }

    //shows a picture of element or folder if available
    function showThumb(){
           $type = $this->type;
           $typeid = $this->typeid;
           
           $db = new db();
            switch($type){
                case 'folder':
                    
                    $path = showThumb("folder", $itemId);


                break;
                case 'element':

                    $elementData = $db->select('elements', array('id', $itemId), array('title','privacy'));
                    if(authorize($elementData['privacy'], "show")){

                        //select random picture and show thumb of it
                        $fileData = $db->query("SELECT * FROM files WHERE type IN ('image/jpeg', 'image/png') AND title LIKE '%thumb%' AND folder='$itemId' ORDER BY RAND() LIMIT 0,1");
                        if($fileData){
                            $fileClass = new file($fileData['id']);
                            $path = "upload".$fileClass->getPath();
                            $path = "$path/$fileData[title]";
                        }
                    }
                break;
            }
            return "$path";
        }
    //shows a box with item title, item link and settings
    function showItemThumb($itemType=NULL,$itemId=NULL){
        if(empty($itemType))
           $itemType = $this->type;
        if(empty($itemId))
           $itemId = $this->typeid;
        
        else{
           $itemId = save($itemId);
           $itemType = save($itemType);
            
        }
           $db = new db();
            switch($itemType){
                case 'folder':
                    $folderClass = new folder($itemId);
                    
                    $folderData = $folderClass->getFolderData();
                    
                    $title = $folderData['name'];
                    $shortTitle = $folderData['name'];

                                    //group folders
                    if($folderData['folder'] == 3){
                        $groupsClass = new groups();
                        $title = $groupsClass->getGroupName($folderData['name']).'´s Files';
                        $shortTitle = $title;
                    }


                    //define link
                    $link = "openFolder('$itemId')";

                    //define icon
                    $img = "folder";

                    //define info 1
                    $info[0] = "path";
                    $info[1] = '/'.$folderClass->getPath(false);

                break;
                case 'collection':
                case 'element':
                    $elementData = $db->select('elements', array('id', $itemId), array('title', 'type'));

                    $title = $elementData['title'];
                    $shortTitle = $elementData['title'];

                    //define link
                    $link = "openElement('$itemId')";

                    //define icon
                    $img = "filesystem";

                    //define info 1
                    $info[0] = "type";
                    $info[1] = $elementData['type'];
                break;
                case 'file':
                    $fileData = $db->select('files', array('id', $itemId), array('title', 'type', 'folder', 'size', 'filename'));
                    
                    
                    $title = $fileData['title'];
                    $shortTitle = $fileData['title'];
                    //shorten filename
                    if(strlen($title) > 15){
                        $shortTitle = substr("$title", 0, 8)."(...)".substr("$title", -4);
                    }

                    //define link
                    $link = "openFile('$fileData[type]', '$itemId', '$shortTitle')";

                    //define fileIcon

                    //if image no info is shown. the complete first row is used to preview image
                    if($fileData['type'] == "image/jpg" || $fileData['type'] == "image/jpeg" || $fileData['type'] == "image/png"){
                            $img = "file";

                            //the column which normaly includes the icon needs to fill out the full width
                            $imgColumnStyle = "colspan=\"2\"";
                            
                            
                            $info[0] = "size";
                            $info[1] = round($fileData['size']/(1024*1024), 2)." MB";
                    }else{
                    $img = "file";
                    //define info 1
                    $info[0] = "size";
                    $info[1] = round($fileData['size']/(1024*1024), 2)." MB";
                                    }

                break;
                case 'link':
                    $linkData = $db->select('links', array('id', $itemId));

                    $title = $linkData['title'];
                    $shortTitle = $linkData['title'];

                    //define link
                    $link = "openFile('$linkData[type]', '".$linkData['typeId']."', '$shortTitle')";

                    //define linkIcon
                    $img = "link";

                    //define info 1
                    $info[0] = "type";
                    $info[1] = $linkData['type'];
                break;
                case 'group':
                break;
                case 'playlist':
                break;
            }

            if(strlen($title) > 15){
                $shortTitle = substr("$title", 0, 11)."(...)";
            }


            $return = "<div class=\"itemThumb\" data-itemtype=\"$itemType\" data-itemid=\"$itemId\">
                <table width=\"100%\" cellspacing=\"0\">";

             //add icon/thumbnail
             $return .= "<tr>
                        <td style=\"width: 35px;\" valign=\"top\" $imgColumnStyle>
                            <i class=\"icon blue-$img\"></i>
                        </td>";
                    //add information
                    if(!empty($info[0]) || !empty($info[1])){
                        $return .=  "<td>
                                <table>
                                    <tr>
                                        <td colspan=\"2\"><a href=\"#\" onclick=\"$link;return false;\" title=\"$title\"><strong>$shortTitle</strong></a></td>
                                    </tr>
                                    <tr>
                                        <td style=\"text-align: right\">$info[0]:&nbsp;</td>
                                        <td class=\"ellipsis\"><span class=\"ellipsis\">$info[1]</span></td>
                                    </tr>
                                </table>
                            </td>";

                    }
                    $return .=  "</tr>
                </table>
            </div>";
            return $return;
        }
    function showChatThumb($input){

           $type = $this->type;
           $typeid = $this->typeid;
            $itemType = $input[1];
            $itemId = $input[2];
            $item = new item($itemType, $itemId);
            $return = $item->showItemThumb($itemType, $itemId);

            return $return;
    }
    
	
    function protect(){
               $type = escape::sql($this->type);
               $typeId = escape::sql($this->typeid);
                    if(hasRight('protectFileSystemItems')){
                            $type = $type;
                            $typeId = $typeId;

                            switch($type){
                                    case 'folder':

                                            $table = 'folders';

                                            break;
                                    case 'element':

                                            $table = 'elements';

                                            break;
                                    case 'file':

                                            $table = 'files';
                                            break;
                                    case 'link':

                                            $table = 'links';


                                            break;
                            }

                            if(isset($table)){

                                $db = new db();
                                
                                
                                            
                                $itemData = $db->select($table, array('privacy'), array('id',$typeId));
                                
                                $privacy = $itemData['privacy'];
                                $values['privacy'] = $privacy;
                                if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
                                            $privacy .= ";PROTECTED";
                                }
                                            
                                $db->update($table, $values, array('id', $typeId));
                            }
                    }else{
                            jsAlert("You dont have the rights to protect an Item.");
                    }
            }
    function removeProtection(){

                    if(hasRight('editProtectedFilesystemItem')){

                        $type = $this->type;
                        $typeId = $this->typeid;

                            switch($type){
                                    case 'folder':

                                            $table = 'folders';

                                            break;
                                    case 'element':

                                            $table = 'elements';

                                            break;
                                    case 'file':

                                            $table = 'files';
                                            break;
                                    case 'link':

                                            $table = 'links';


                                            break;
                            }
                            if(isset($table)){
                               

                                $db = new db();
                                $itemData = $db->select($table, array('privacy'), array('id',$typeId));
                                
                                $privacy = $itemData['privacy'];
                                $privacy = str_replace(";PROTECTED", "", $privacy); 
                                $values['privacy'] = $privacy;
                                $db->update($table, $values, array('id', $typeId));
                            }
                    }else{
                            jsAlert("You do not have the rights to edit protected files.");
                    }
            }

    function makeUndeletable(){

                    if(hasRight('undeletableFilesystemItems')){

                            $type = $this->type;
                            $typeId = $this->typeid;
                            switch($type){
                                    case 'folder':
                                            $db = new db();
                                            $folderData = $db->query('folders', array('id', $typeId), array('privacy'));

                                            $privacy = $folderData['privacy'];
                                            if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
                                                    $privacy .= ";UNDELETABLE";
                                            }

                                            $table = 'folders';
                                            break;
                                    case 'element':
                                            $db = new db();
                                            $elementData = $db->query('elements', array('id', $typeId), array('privacy'));
                                            
                                            $privacy = $elementData['privacy'];
                                            if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
                                                    $privacy .= ";UNDELETABLE";
                                            }

                                            $table = 'elements';
                                            break;
                                    case 'file':
                                            $db = new db();
                                            $fileData = $db->query('files', array('id', $typeId), array('privacy'));
                                            $privacy = $fileData['privacy'];
                                            if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
                                                    $privacy .= ";UNDELETABLE";
                                            }


                                            $table = 'files';
                                            break;
                                    case 'link':
                                            $db = new db();
                                            $fileData = $db->query('links', array('id', $typeId), array('privacy'));


                                            $privacy = $linkData['privacy'];
                                            if(end(explode(";", $privacy)) != "UNDELETABLE" && end(explode(";", $privacy)) != "PROTECTED"){
                                                    $privacy .= ";UNDELETABLE";
                                            }


                                            $table = 'links';
                                            break;
                            }
                            if(isset($table)){
                                $values['privacy'] = $privacy;

                                $db = new db();
                                $db->update($table, $values, array('id', $typeId));
                            }
                    }else{
                            jsAlert("You do not have the right to make Items undeletable.");
                    }
            }
    function makeDeletable(){
                    if(hasRight("editUndeletableFilesystemItems")){

                    $type = $this->type;
                    $typeId = $this->typeid;
                    switch($type){
                            case 'folder':
                                    $table = 'folders';
                                    break;
                            case 'element':
                                    $table = 'elements';
                                    break;
                            case 'file':
                                    $table = 'files';
                                    break;
                            case 'link':
                                    $table = 'links';
                                break;
                    }




                    if(isset($table)){

                        $db = new db();
                        $itemData = $db->select($table, array('id', $typeId), array('privacy'));

                        $privacy = $itemData['privacy'];
                        $privacy = str_replace(";UNDELETABLE", "", $privacy);

                        $values['privacy'] = $privacy;

                        $db->update($table, $values, array('id', $typeId));
                    }
                    }else{
                            jsAlert("You dont have the right to edit undeletable Items.");
                    }
            }
}


       
    
