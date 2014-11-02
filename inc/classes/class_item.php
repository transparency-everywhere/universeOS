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
    function __construct($type, $typeid){
       $this->type = $type;
       $this->typeid = $typeid;
    }
    //put your code here
    function plusOne(){
       $type = $this->type;
       $typeid = $this->typeid;
       if($type == "comment"){
           mysql_query("UPDATE comments SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
       }else if($type == "feed"){
           mysql_query("UPDATE feed SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
       }
       if($type == "file"){
           mysql_query("UPDATE files SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
       }
       if($type == "folder"){
           mysql_query("UPDATE folders SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
       }
       if($type == "element"){
           mysql_query("UPDATE elements SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       if($type == "file"){
           mysql_query("UPDATE file SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       if($type == "link"){
           mysql_query("UPDATE links SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       //score++
       }
function minusOne(){
    
       $type = $this->type;
       $typeid = $this->typeid;
       if($type == "comment"){
           mysql_query("UPDATE comments SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
           
       }else if($type == "feed"){
           mysql_query("UPDATE feed SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
       }
       if($type == "file"){
           mysql_query("UPDATE files SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "folder"){
           mysql_query("UPDATE folders SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "element"){
           mysql_query("UPDATE elements SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "file"){
           mysql_query("UPDATE file SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
   
       }
       if($type == "link"){
           mysql_query("UPDATE links SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
   
       }
       } //score--
       
    
function showScore($reload=NULL) {
    
       $type = $this->type;
       $typeid = $this->typeid;
        if(proofLogin()){
               if($type == "comment"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM comments WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql);
               }
               else if($type == "feed"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM feed WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "folder"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM folders WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "element"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM elements WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "file"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM files WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "link"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM links WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               if(!isset($reload)){
                   $output =  "<div class=\"score$type$typeid\">";
               }
			   
			   
			   if($scoreData['score'] > 0){
			   	$class = "btn-success";
			   }else if($scoreData['score'] < 0){
			   	$class = "btn-warning";
			   }else{
			   	$class = '';
			   }
			   
			   $output .= '<div class="btn-toolbar" style="margin: 0px;">';
			   $output .= '<div class="btn-group">';
			   $output .="<a class=\"btn btn-mini\" href=\"doit.php?action=scoreMinus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-down\"></i></a>";
			   $output .= "<p class=\"btn btn-mini $class\" href=\"#\">$scoreData[score]</p>";
			   $output .= "<a class=\"btn btn-mini\" href=\"doit.php?action=scorePlus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-up\"></i></a>";
			   $output .= '</div>';
			   $output .= '</div>';
               if(!isset($reload)){
                   $output .=  "</div>";
               }
			   
			   return $output;
        }
           
       }
}



function showScore($type, $typeid, $reload=NULL) {
    debug::write('use of show score');
        if(proofLogin()){
               if($type == "comment"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM comments WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql);
               }
               else if($type == "feed"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM feed WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "folder"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM folders WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "element"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM elements WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "file"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM files WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "link"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM links WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               if(!isset($reload)){
                   $output =  "<div class=\"score$type$typeid\">";
               }
			   
			   
			   if($scoreData['score'] > 0){
			   	$class = "btn-success";
			   }else if($scoreData['score'] < 0){
			   	$class = "btn-warning";
			   }else{
			   	$class = '';
			   }
			   
			   $output .= '<div class="btn-toolbar" style="margin: 0px;">';
			   $output .= '<div class="btn-group">';
			   $output .="<a class=\"btn btn-mini\" href=\"doit.php?action=scoreMinus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-down\"></i></a>";
			   $output .= "<p class=\"btn btn-mini $class\" href=\"#\">$scoreData[score]</p>";
			   $output .= "<a class=\"btn btn-mini\" href=\"doit.php?action=scorePlus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-up\"></i></a>";
			   $output .= '</div>';
			   $output .= '</div>';
               if(!isset($reload)){
                   $output .=  "</div>";
               }
			   
			   return $output;
        }
           
       }
       
       
//shows a picture of element or folder if available
function showThumb($type, $itemId){
        switch($type){
            case 'folder':
                $elementSQL = mysql_query("SELECT id FROM elements WHERE folder='$itemId' ORDER BY RAND() LIMIT 0,1");
                $elementData = mysql_fetch_array($elementSQL);
                
                $path = showThumb("element", $elementData['id']);
                
                
            break;
            case 'element':
                
                $elementSQL = mysql_query("SELECT title, privacy FROM elements WHERE id='$itemId'");
                $elementData = mysql_fetch_array($elementSQL);
                if(authorize("$elementData[privacy]", "show")){
                    
                    //select random picture and show thumb of it
                    $fileSQL = mysql_query("SELECT * FROM files WHERE type IN ('image/jpeg', 'image/png') AND title LIKE '%thumb%' AND folder='$itemId' ORDER BY RAND() LIMIT 0,1");
                    $fileData = mysql_fetch_array($fileSQL);
                    if($fileData){
                        $path = "upload".getFilePath($fileData['id']);
                        $path = "$path/$fileData[title]";
                    }
                }
            break;
        }
        return "$path";
    }
    
function showChatThumb($input){
        
        $itemType = $input[1];
        $itemId = $input[2];
        
        $return = showItemThumb($itemType, $itemId);
        
        return $return;
}

//shows a box with item title, item link and settings
function showItemThumb($itemType, $itemId){
        $itemId = save($itemId);
        
        switch($itemType){
            case 'folder':
            
                $folderSQL = mysql_query("SELECT * FROM folders WHERE id='$itemId'");
                $folderData = mysql_fetch_array($folderSQL);

                $title = $folderData['name'];
                $shortTitle = $folderData['name'];
				
				//group folders
                if($folderData['folder'] == 3){
                	$title = getGroupName($folderData['name']).'´s Files';
					$shortTitle = $title;
                }
                

                //define link
                $link = "openFolder('$itemId')";

                //define icon
                $img = "filesystem/folder.png";

                //define info 1
                $info[0] = "path";
                $info[1] = $folderData['path'];
        
            break;
            case 'element':
                $elementSQL = mysql_query("SELECT title, type FROM elements WHERE id='$itemId'");
                $elementData = mysql_fetch_array($elementSQL);

                $title = $elementData['title'];
                $shortTitle = $elementData['title'];

                //define link
                $link = "openElement('$itemId')";

                //define icon
                $img = "filesystem/element.png";

                //define info 1
                $info[0] = "type";
                $info[1] = $elementData['type'];
            break;
            case 'file':
            
                $fileSQL = mysql_query("SELECT title, type, folder, size, filename FROM files WHERE id='$itemId'");
                $fileData = mysql_fetch_array($fileSQL);


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
                	$elementData = mysql_fetch_array(mysql_query("SELECT folder FROM elements WHERE id='$fileData[folder]'"));
                	$img = "../../".getFolderPath($elementData['folder'])."thumbs/".$fileData['filename'];
                	
                	//the column which normaly includes the icon needs to fill out the full width
                	$imgColumnStyle = "colspan=\"2\"";
                }else{
                $img = "fileIcons/";
                $img .= getFileIcon($fileData['type']);
                //define info 1
                $info[0] = "size";
                $info[1] = round($fileData['size']/(1024*1024), 2)." MB";
				}

            break;
            case 'link':
                $linkSQL = mysql_query("SELECT * FROM links WHERE id='$itemId'");
                $linkData = mysql_fetch_array($linkSQL);

                $title = $linkData['title'];
                $shortTitle = $linkData['title'];

                //define link
                $link = "openFile('$linkData[type]', '$linkData[typeId]', '$shortTitle')";

                //define linkIcon
                $img = "fileIcons/";
                $img .= getFileIcon($linkData[type]);

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
        
        
        $return = "<div class=\"itemThumb\">
            <table width=\"100%\" cellspacing=\"0\">
                <tr style=\"height: 30px;\" bgcolor=\"#F2F2F2\">
                    <td colspan=\"4\">&nbsp;<a href=\"#\" onclick=\"$link;return false;\" title=\"$title\"><strong>$shortTitle</strong></a></td>
                </tr>";
		
		//add spacer
		if(!empty($info[0]) || !empty($info[1])){
        $return .= "<tr style=\"height: 10px\">
                    <td></td>
                </tr>";
		}
         //add icon/thumbnail
         $return .= "<tr>
                    <td style=\"min-width: 34px;\" $imgColumnStyle>
                    	<img src=\"./gfx/icons/$img\"/>
                    </td>";
					
		//add information
		if(!empty($info[0]) || !empty($info[1])){
		$return .=  "<td>
                        <table class=\"eightPt\">
                            <tr>
                                <td style=\"text-align: right\">$info[0]:&nbsp;</td>
                                <td class=\"ellipsis\"><span class=\"ellipsis\">$info[1]</span></td>
                            </tr>
                        </table>
                    </td>";}
		$return .=  "</tr>
                <tr height=\"22px\">
                    <td bgcolor=\"#F2F2F2\">".showItemSettings($itemType, $itemId)."</td>
                    <td bgcolor=\"#F2F2F2\" align=\"right\">
                        <a href=\"#\" class=\"btn btn-mini disabled\" style=\"float: right; margin-right: 30px; color: #606060;\"><i class=\"icon-comment\"></i>&nbsp;(".countComment($itemType, $itemId).")</a>
                    </td>
                </tr>
            </table>
        </div>";
        return $return;
    }