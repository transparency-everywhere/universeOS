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
class fileSystem {
    
    function showFileBrowser($folder, $folderQuery=NULL, $elementQuery=NULL, $rightClick=true, $subpath=NULL){
            echo'<table cellspacing="0" class="filetable">';

            //if folder is empty => execute folder- and elementQuery
            if(empty($folder)){

                if(isset($folderQuery)){
                    $query = $folderQuery;
                }

                if(isset($folderQuery)){
                    $query2 = $elementQuery;
                }


            }else{

                            //get userfolder
                            $userData = getUserData();
                            $userfolder = $userData['homefolder'];


                            //special folder handlers

                            //userFolder
                            if($folder == "2"){
                                    $folder = $userfolder;
                                    $parentFolderData['folder'] = 1;
                            }

                            if($folder == $userfolder){
                                    $parentFolderData['folder'] = 1;
                            }


                $query = "WHERE folder='$folder' ORDER BY name ASC";
                $query2 = "WHERE folder='$folder' ORDER BY name ASC";
                $shortCutQuery = "WHERE parentType='folder' AND parentId='$folder'";
            }


            if(!empty($query)){
                    if(!empty($folder) && ($folder !== "1")){
                            if($parentFolderData['folder'] !== 1)
                                    $parentFolderData = mysql_fetch_array(mysql_query("SELECT folder FROM folders WHERE id='".mysql_real_escape_string($folder)."'"));
                            ?>
                        <tr class="strippedRow" height="30">
                            <td width="30">&nbsp;<img src="<?=$subpath;?>gfx/icons/filesystem/folder.png" height="22"></td>
                            <td><a href="<?=$subpath;?>out/?folder=<?=$parentFolderData['folder'];?>" onclick="openFolder('<?=$parentFolderData['folder'];?>'); return false;">...</a></td>
                            <td width="50px">
                            </td>
                            <td width="50px"></td>
                        </tr>
                            <?php
                    }
            $filefsql = mysql_query("SELECT * FROM folders $query");
            while($filefdata = mysql_fetch_array($filefsql)) {
            if(authorize($filefdata['privacy'], "show", $filefdata['creator'])){

                            $name = $filefdata['name'];
                    //special folder handlers
                    if($folder == 3){
                            $groupsClass = new groups();
                            $name = $groupsClass->getGroupName($filefdata['name']).'´s Files';
                            }

            ?>
                <tr class="strippedRow" oncontextmenu="showMenu('folder<?=$filefdata['id'];?>'); return false;" height="30">
                    <td width="30"><?php
                    if($rightClick){
                        $contextMenu = new contextMenu("folder", $filefdata['id'], $filefdata['name'], $filefdata['creator']);
                        echo $contextMenu->showRightClick();
                    }?>&nbsp;<img src="<?=$subpath;?>gfx/icons/filesystem/folder.png" height="22"></td>
                    <td><a href="<?=$subpath;?>out/?folder=<?=$filefdata['id'];?>" onclick="openFolder('<?=$filefdata['id'];?>'); return false;"><?=$name;?></a></td>
                    <td width="50px">
                            <?php
                            if($rightClick){
                                echo $contextMenu->showItemSettings();
                            }
                            ?>
                    </td>
                    <td width="50px">
                            <?php
                            $item = new item("folder", $filefdata['id']);
                            echo $item->showScore();?></td>
                </tr>
                <?php
                }}}
                $filedsql = mysql_query("SELECT * FROM elements $query2");
                while($fileddata = mysql_fetch_array($filedsql)) {

                    $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='$folder'");
                    $fileFolderData = mysql_fetch_array($filefolderSQL);
                    $title = $fileddata['title'];
                    $title10 = substr("$title", 0, 10);
                    $title15 = substr("$title", 0, 25);

                    if(authorize($fileddata['privacy'], "show", $fileddata['author'])){
                        $item = new item("element", $fileddata['id']);
                        echo "<tr class=\"strippedRow\" oncontextmenu=\"showMenu('element".$fileddata['id']."'); return false;\" height=\"30\">";
                                echo "<td width=\"30\">&nbsp;<img src=\"$subpath"."gfx/icons/filesystem/element.png\" height=\"22\"></td>";
                                echo "<td><a href=\"$subpath"."out/?element=".$fileddata['id']."\" onclick=\"openElement('".$fileddata['id']."', '".addslashes($title10)."'); return false;\">$title15</a></td>";
                                echo "<td width=\"80px\">";
                                            if($rightClick){
                                                $contextMenu = new contextMenu("element", $fileddata['id'], $title10, $fileddata['author']);
                                                echo $contextMenu->showItemSettings();
                                                            }
                                echo "</td>";
                                echo "<td width=\"50px\">".$item->showScore()."</td>";
                        echo "</tr>";
                        if($rightClick){
                            echo $contextMenu->showRightClick();
                        }

                    }
                
                }

                $shortCutSql = mysql_query("SELECT * FROM internLinks $shortCutQuery");
                while($shortCutData = mysql_fetch_array($shortCutSql)){
                    if($shortCutData['type'] == "folder"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT name, privacy FROM folders WHERE id='".$shortCutData['typeId']."'"));

                        $title = $shortCutItemData['name'];
                        $image = "folder.png";
                        $link = "openFolder('".$shortCutData['typeId']."'); return false;";

                    }else if($shortCutData['type'] == "element"){

                        $shortCutItemData = mysql_fetch_array(mysql_query("SELECT title, privacy FROM elements WHERE id='".save($shortCutData[typeId])."'"));

                        $title = $shortCutItemData['title'];
                        $image = "element.png";
                        $link = "openElement('".$shortCutData['typeId']."', '".addslashes(substr($shortCutItemData['title'],0,10))."'); return false;";
                    }
                    
                    $contextMenu = new contextMenu("internLink", $shortCutData['id']);

                    echo'<tr class="strippedRow">';
                        echo"<td>";
                            echo"&nbsp;<img src=\"$subpath"."/gfx/icons/filesystem/$image\" height=\"22\"><i class=\"shortcutMark\"> </i>";
                        echo"</td>";
                        echo"<td>";
                            echo"<a href=\"$subpath"."out/?$shortCutData[type]=$shortCutData[typeId]\" onclick=\"$link\">$title</a>";
                        echo"</td>";
                        echo"<td>";
                            echo $contextMenu->showItemSettings();
                        echo"</td>";

                    echo"</tr>";



                }


                echo"</table>";

    }

function showMiniFileBrowser($folder=NULL, $element=NULL, $level, $showGrid=true, $select=NULL){
		
		//$level is used to give the list a regular margin
		//each time a new list is loaded inside the old one
		if(empty($level)){
			$level = 0;
		}
		$margin = $level*10;
		if(!empty($margin)){
			$style = "style=\"padding-left: $margin"."px;\"";
		}
		$level++;
		
		//define which buttons are shown
		$showFolderButton = true;
		$showElementButton = true;
		$showFilebutton = true;
		
		
		if($select == "folder"){
			$showElementButton = false;
			$showFilebutton = false;
		}
		if($select == "element"){
			$showFolderButton = false;
			$showFilebutton = false;
		}
		if($select == "file"){
			$showElementButton = false;
			$showFilebutton = false;
		}
		
        	
		if($showGrid){
        echo'<ul class="miniFileBrowser">';
		echo'<li class="choosenItem"></li>';
		echo'<li class="change" onclick="$(\'.miniFileBrowser .strippedRow\').slideDown();">change</li>';
		}
		
        //if folder is empty => load file list
        if($folder !== NULL && !empty($folder)){
            
            $query = "WHERE folder='$folder' ORDER BY name ASC";
            $query2 = "WHERE folder='$folder' ORDER BY name ASC";

	        	
	        $filefsql = mysql_query("SELECT * FROM folders $query");
	        while($filefdata = mysql_fetch_array($filefsql)) {
	        	
		        if(authorize($filefdata['privacy'], "show", $filefdata['creator'])){
				$action['folders'] = "$('.folder$filefdata[id]LoadingFrame').loadOuter('doit.php?action=loadMiniBrowser&folder=$filefdata[id]&level=$level&select=$select');return false;";
		        $trigger['folders'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/filesystem/folder.png\' alt=\'folder\' height=\'32px\'>&nbsp;$filefdata[name]<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'folder\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$filefdata[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
				
					
		        ?>
	            <li class="strippedRow" <?=$style;?>>
	                <span>&nbsp;<img src="./gfx/icons/filesystem/folder.png" height="14"></span>
	                <span><a href="#" onclick="<?=$action['folders'];?>"><?=$filefdata['name'];?>/</a></span>
	            <?
	            if($showFolderButton){
	            ?>
	                <span class="trigger"><a href="#" onclick="<?=$trigger['folders'];?>" class="btn btn-mini"><i class="icon-ok"></i></a>&nbsp;</span>
				<? } ?>
	            </li>
	            <!-- frame in which the folder data is loaded, if loadFolderDataIntoMiniBrowser() is called -->
	            <li class="folder<?=$filefdata[id];?>LoadingFrame" style="display: none;"></li>
	            <!-- keep strippedrow working-->
	            <li class="strippedRow" style="display: none;"></li>
	            <?php
	            }
			}
			
	        $filedsql = mysql_query("SELECT * FROM elements $query2");
	        while($fileddata = mysql_fetch_array($filedsql)) {
	
	        $filefolderSQL = mysql_query("SELECT * FROM folders WHERE id='$folder'");
	        $fileFolderData = mysql_fetch_array($filefolderSQL);
	        $title = $fileddata['title'];
	        $title10 = substr("$title", 0, 10);
	        $title15 = substr("$title", 0, 25);
	
		        if(authorize($fileddata[privacy], "show", $fileddata[author])){
		        	
				
				$action['elements'] = "$('.element$fileddata[id]LoadingFrame').loadOuter('doit.php?action=loadMiniBrowser&element=$fileddata[id]&level=$level&select=$select');return false;";
		        $trigger['elements'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/filesystem/element.png\' alt=\'folder\' height=\'32px\'>&nbsp;$title<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'element\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$fileddata[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
						
		        ?>
		            <li class="strippedRow" <?=$style;?>>
		                <span>&nbsp;<img src="./gfx/icons/filesystem/element.png" height="14"></span>
		                <span><a href="#" onclick="<?=$action['elements'];?>"><?=$title15;?></a></span>
		        		<?
	           			 if($showElementButton){
	            		?>
	                    <span class="trigger"><a href="#" onclick="<?=$trigger['elements'];?>" class="btn btn-mini"><i class="icon-ok"></i></a>&nbsp;</span>
	                    <? } ?>
		            </li>
		            <!-- frame in which the element data is loaded, if loadElementDataIntoMiniBrowser() is called -->
		            <li class="element<?=$fileddata[id];?>LoadingFrame" style="display: none;"></li>
		            <!-- keep strippedrow working-->
		            <li class="strippedRow" style="display: none;"></li>
		            <?php
	            }
			}
		}else if(isset($element)){
			
			
			
	        //shows list of files which are in the element $element or which meets criteria of $fileQuery
	        //if git=1 => only basic information without itemsettings etc.
	            $query = "folder='".mysql_real_escape_string($element)."'";
	        
	        
	            $fileListSQL = mysql_query("SELECT * FROM files WHERE $query");
	            while($fileListData = mysql_fetch_array($fileListSQL)) {
	                
	                if(authorize($fileListData['privacy'], "show", $fileListData['owner'])){
	                	
	                	
	                $title10 = substr($fileListData['title'], 0, 10);
                        $classFiles = new files();
	                $image = $classFiles->getFileIcon($fileListData['type']);
					
					$action['files'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$fileListData[type]\' height=\'32px\'>&nbsp;$fileListData[title]<input type=\'hidden\' class=\'choosenType\' name=\'type\' value=\'file\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$fileListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					$trigger['files'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$fileListData[type]\' height=\'32px\'>&nbsp;$fileListData[title]<input type=\'hidden\' class=\'choosenType\' name=\'type\' value=\'file\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$fileListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					
					
					
	                    ?>
	                    <li class="strippedRow" <?=$style;?>>
	                        <span>&nbsp;<img src="./gfx/icons/fileIcons/<?=$image;?>" alt="<?=$fileListData['type'];?>" height="14px"></span>
	                        <span><a href="#" onclick="<?=$action['files'];?>"><?=substr($fileListData['title'],0,30);?></a></span>
	                        
			        		<?
		           			 if($showFileButton){
		            		?>
	                        <span class="trigger"><a href="#" onclick="<?=$trigger['files'];?>" class="btn btn-mini"><i class="icon-ok"></i></a>&nbsp;</span>
	                        <? } ?>
	                    </li>
	                    
	                    <?php
	
	            }}
	            $linkListSQL = mysql_query("SELECT * FROM links WHERE $query");
	            while($linkListData = mysql_fetch_array($linkListSQL)) {
	            	
					
					
	                $title10 = substr("$linkListData[title]", 0, 10);
					
					$action['links'] = "alert('lol'); return false";
			$classFiles = new files();
	                $image = $classFiles->getFileIcon($linkListData['type']);
	                
					$action['links'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$linkListData[type]\' height=\'32px\'>&nbsp;$linkListData[title]<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'link\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$linkListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					$trigger['links'] = "$('.miniFileBrowser .choosenItem').html('<img src=\'./gfx/icons/fileIcons/$image\' alt=\'$fileListData[type]\' height=\'32px\'>&nbsp;$linkListData[title]<input type=\'hidden\' name=\'type\' class=\'choosenType\' value=\'link\'><input type=\'hidden\' name=\'typeId\' class=\'choosenTypeId\' value=\'$linkListData[id]\'>');  $('.miniFileBrowser .change').show(); $('.miniFileBrowser .strippedRow').slideUp();";
					
					
	                    
	                ?>
	                <li class="strippedRow" <?=$style;?>>
	                    <span>&nbsp;<img src="./gfx/icons/fileIcons/<?=$image;?>" alt="<?=$linkListData['type'];?>" height="14px"></span>
	                    <span><a href="#" onclick="<?=$action['links'];?>"><?=substr($linkListData['title'],0,30);?></a></span>
			        	<?
		           			 if($showFileButton){
		            	?>
	                    <span class="trigger"><a href="#" onclick="<?=$trigger['files'];?>" class="btn btn-mini"><i class="icon-ok icon-white"></i></a></span>
	                    <? } ?>
	                </li>
	                <?php
	                } 
			
			
			
			
			
			
			
			
			
			
		}
		if($showGrid){
        echo"</ul>";
		}
		
		
		
		
		
	}

}


function openFile($fileId=NULL, $linkId=NULL, $type=NULL, $title=NULL, $typeInfo=NULL, $extraInfo1=NULL, $extraInfo2=NULL,  $extraInfo3=NULL, $extraInfo4=NULL, $extraInfo5=NULL, $subpath){
        
        
        
        
        //choose if function needs to handle real file
        //or a link
        
        //youtube, wiki, rss etc. actually needs a linkId
        //but in this case the handler for links still needs
        //to be called
        if(!empty($linkId) || $type == "wiki" || $type == "RSS" || $type == "youtTube")
        	$isLink = true;
		if(!empty($fileId)) 
			$isFile = true;
		
		
		
       	if($isFile){
            //get filedata
            $fileQuery = mysql_query("SELECT * FROM files WHERE id='$fileId'");
            $fileData = mysql_fetch_array($fileQuery);
			
			$privacy = $fileData['privacy'];
			$user = $fileData['owner'];
			
			$elementQuery = mysql_query("SELECT * FROM elements WHERE id='$fileData[folder]'");
			$elementData = mysql_fetch_array($elementQuery);
            
          	$title = $fileData['title'];
			$element = $fileData['folder'];
			$elementTitle = $elementData['title'];
			if($fileData['download']){
				$download = "<a href=\"./out/download/?fileId=$fileId\" target=\"submitter\" class=\"btn btn-mini\" title=\"download file\"><img src=\"$subpath"."./gfx/icons/download.png\" alt=\"download\" height=\"10\"></a>";
			}
            $filename = $fileData['filename'];
            $fileClass = new file($fileId);
            $path = $subpath.$fileClass->getPath();
            
                $item = new item("file", $fileId);
    		$score = $item->showScore();
            
            //check type if type is undefined get type from db
            if($type == NULL){
            $type = $fileData['type'];
            }
            
        }
        if($isLink){
        	//extra handler for extarnal links
			if(empty($linkId))
			
				//check if link is "external" or if data needs to be taken out of the db
				//if $type = wiki, linkId is empty but its still an external source
				if(is_int($linkId) || $type == "wiki")  
				$extarnal = true;
			
				if(!$extarnal){
		            //get linkdata
		            $linkQuery = mysql_query("SELECT * FROM links WHERE id='$linkId'");
		            $linkData = mysql_fetch_array($linkQuery);
					$privacy = $linkData['privacy'];
					$user = $linkData['author'];
			
		            
		            $title = $linkData['title'];
		            $item = new item("link", $linkId);
                            $score = $item->showScore();
					
		            
		            //define type if type is undefined
		            if(empty($type)){
		            $type = $linkData['type'];
		            }
					if($type == "youTube"){
						//generate link out of youtubelink
                                                $youtubeClass = new youtube($linkData[link]);
						$vId = $youtubeClass->getId();
                                                
					}
				}
        }
		
		if($type == "youTube"){
			
			//GET YOUTUBE VIDEO ID
			
			if(!empty($vId)){
			$vId = "$typeInfo";
			}
			//if $vId is still empty the youtube videoId is passed
			//through $typeInfo
			else{
			$vId = "$typeInfo";
			}
			
			//get title from youtube server
			if(empty($title)){
                                $classYoutube = new youtube('', $vId);
				$title = $classYoutube->getTitle();
			}
			
			//optional options
			$playlist = $extraInfo1;
			$row = $extraInfo2;
			
			//add playlistdropdown to add video
                        // to a playlist to the header
                        if(proofLogin()){
                            //get all the playlists
                            $playlistClass = new playlists();
                            $playlists = $playlistClass->getUserPlaylistArray('', 'edit');
                            //init form and select
                            $options = "<form action=\"doit.php?action=addYouTubeItemToPlaylistVeryLongName&vId=$vId\" target=\"submitter\" method=\"post\"><select name=\"playlistId\">";
                            foreach ($playlists['ids'] as $key => $id){
				
				
                                //add options to dropdown
                                $options .= "<option value=\"$id\">".$playlists['titles'][$key]."</option>";
                            
                            }
                            //close select, form and and submit button
                            $options .= "</select><input type=\"submit\" name=\"submit\" value=\"add\" class=\"btn btn-mini\" style=\"margin-top: -11px;\"></form>";
			}
			$controls = $options;
			
			//extraInfo1 is a playlist
			if(!empty($extraInfo1)){
				//id of fileWindow needs to have playlistId inside
				//so that playlistplayer.php can proof if playlist
				//is already opened
				$fileWindowId = "playListFrame_$extraInfo1";
				$iframeId = "playListiFrame_$extraInfo1";
				$titleClass .= "playlist_$extraInfo1";
				
				//title needs to contain span with playlistid, so it can
				//be updated from the iframe, in which the youtubevideo
				//is located(doit => showYoutube)
	            $title = "<span id=\"togglePlayListTitle_$extraInfo1\" class=\"readerPlayListTitle\">".addslashes($title)."</span>";
			
			
				$bar .= "<div id=\"togglePlayList_$extraInfo1\" class=\"readerPlayListToggle\"></div>";
				
				
				
			}
		}
		
		if(authorize($privacy, 'show', $user) OR $type == "youTube" OR $type == "wiki"){
	        if($type == "image/jpeg" || $type == "image/png" || $type == "image" ){
	        	
				$type = 'image';
				
	        	//add zoom buttons to header
	    		$bar = "<a href=\"javascript: zoomIn('$element');\" id=\"zoomIn\" class=\"btn btn-mini\" title=\"zoom in\"><img src=\"$subpath"."gfx/icons/zoomIn.png\" height=\"10\" border=\"0\"></a>&nbsp;<a id=\"zoomOut\" href=\"javascript: zoomOut('$element');\" class=\"btn btn-mini\" style=\"\" title=\"zoom out\"><img src=\"$subpath"."gfx/icons/zoomOut.png\" height=\"10\" border=\"0\"></a>";
	        }
			
			if($type == "audio/mpeg")
				$type = 'audio';
			$classFiles = new files();
	        $icon = $classFiles->getFileIcon($type);
	        $icon = "<img src=\"$subpath"."gfx/icons/fileIcons/$icon\" height=\"20\">";
	        
	        $output .= "<header class=\"gray-gradient\">";
	        $output .= $icon;
	        $output .= "<span class=\"title\">$title $type</span>";
			$output .= "<span class=\"controls\">$controls</span>";
			$output .= "<span class=\"bar\">$bar</span>";
			$output .= "<span class=\"score\">$score</span>";
	        $output .= "<span class=\"download\">$download</span>";
	        $output .= "</header>";
	        $output .= "<div class=\"fileWindow\" id=\"$fileWindowId\">";
	        
	        switch($type){
	            //link types
	            case youTube:
				  
				      
					  
					  //DISPLAY VIDEO
					  //define content
				      $output .= '<div class="iframeFrame" style="background: #000000;">';
					  //show an iframe which loads the openyoutube...() function via the doit.php
				      $output .= "<iframe src=\"doit.php?action=showYoutube&id=$vId&playList=$playlist&row=$row\" id=\"$iframeId\" name=\"playListLoaderFrame\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"></iframe>";
				      $output .= '</div>';
					
					
					
	                break;
	            case wiki:
					
		       		$title = urlencode($title);
		            $wikiUrl = "http://en.wikipedia.org/w/index.php?title=$title&printable=yes";
					
					
	                $output .= "<div class=\"iframeFrame\">";
		            $output .= "<iframe frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"  src=\"$wikiUrl\"></iframe>";
		            $output .= "</div>";
	                break;
	            case RSS:
					
					
					if(!empty($linkData[link])){
			        	$url = $linkData[link];
						$title = $linkData[title];
					}else{
						$url = $typeInfo;
						$title = $extraInfo1;
					}
			        $output .= "<div class=\"rssReader windowContent\">";
                                $rss = new rss();
			        $output .= $rss->getRssfeed("$url","$title","auto",10,3);
			        $output .= "</div>";
					
	                break;
	            
	            //real file types
	            //documents
				case UFF:
							if(authorize($fileData['privacy'], "edit", $fileData['owner'])){
							    $readOnly = "false";
							}else{
							    $readOnly = "true";
							}
							
							$title = $fileData['title'];
							$activeUsers = explode(";", $fileData['var1']);
							//this iframe is used to handle all the onload, onsubmit, onkeyup events, its necessary because of the fact that the dhtml-goddies tab script parses the damn js
							//dirty solution!!!
							$output .= "<iframe src=\"modules/reader/UFF/javascript.php?fileId=$fileId&readOnly=$readOnly\" style=\"display:none;\"></iframe>";
							$output .= "<div class=\"uffViewerNav\">";
								$output .= "<div style=\"margin: 10px;\">";
									$output .= "<ul>";
							            $output .= '<li style="font-size: 11pt; margin-bottom: 05px;"><i class="icon-user"></i>&nbsp;<strong>Active Users</strong></li>';
							            //show active users
							            foreach($activeUsers AS &$activeUser){
							                if(!empty($activeUser)){
							                $output .= "<li onclick=\"openProfile($activeUser);\" style=\"cursor: pointer;\">";
							                //$output .= showUserPicture($activeUser, "11");
							                $output .=  "&nbsp;";
							                $output .=  useridToUsername($activeUser);
							                $output .= "</li>";
							                }
							            }
									$output .= "</ul>";
								$output .= "</div>";
							$output .= "</div>";
							//document frame
							$output .= "<div class=\"uffViewerMain\">";
								$output .= "<textarea class=\"uffViewer_$fileId WYSIWYGeditor\" id=\"editor1\">";
								$output .= "</textarea>";
							$output .= "</div>";
					break;
	            case 'text/plain':
	                
	                if($subpath == '../')
	                	$filePath = urldecode("$path");
					else
						$filePath = urldecode("../../$path");
	
	                $file = fopen($filePath, 'r');
	                $output .= nl2br(htmlentities(fread($file, filesize($filePath))));
	                fclose($file);
	                
	                break;
	            
	            case 'application/pdf':
	                $output .= "<div class=\"iframeFrame\">";
	                $output .= "<iframe src=\"./$path\" frameborder=\"0\" marginwidth=\"0\" marginheight=\"0\" scrolling=\"auto\"></iframe>";
	                $output .= "</div>";
	                
	                break;
	            
	            
	            //pictures
	            case 'image':
					
					$output .= "<div id=\"ImageViewer_$element\" class=\"readerImageTab\">";
					$output .= "<center>";
					$output .= "<img src=\"$path\" width=\"100%\" id=\"viewedPicture_$element\">";
					$output .= "</center>";
					$output .= "</div>";
					
					$output .= '<div style="position: absolute; height: 120px; right:0px; bottom: 0px; left: 0px; overflow: auto; background: #000; color: #FFF;">';
	
						$output .= '<table style="width: 100%; height: 120px;" align="center" class="blackGradient">';
						
						$output .= '<tr>';
						
						
				        $documentSQL = mysql_query("SELECT id, title, folder, privacy, owner FROM files WHERE folder='$elementData[id]' AND type IN('image/png','image/jpeg','image')");
				        while($documentData = mysql_fetch_array($documentSQL)){
	        				if(authorize($documentData['privacy'], "show", $documentData['owner'])){
						        //$documentFolderSQL = mysql_query("SELECT path FROM folders WHERE id='$elementData[folder]'");
						        //$documentFolderData = mysql_fetch_array($documentFolderSQL);
                                                        $folderClass = new folder($elementData['folder']);
						        if($elementData['title'] == "profile pictures"){
                                                                
						        	$thumbPath = $subpath.$folderClass->getPath();    
						        	$thumbPath = "$thumbPath/thumb/300/";
						        }else{
						        	$thumbPath = $subpath.$folderClass->getPath()."thumbs/";
						        }
								
								
						        $output .= "<td onmouseup=\"showMenu('image$documentData[id]')\" oncontextmenu=\"showMenu('image$documentData[id]'); return false;\"><div id=\"viewerClick$documentData[id]\"><a href=\"#\" onclick=\"addAjaxContentToTab('Open ".substr("$elementTitle", 0, 10)."','./modules/reader/openFile.php?type=image&fileId=$documentData[id]');return false\"><img src=\"$thumbPath$documentData[title]\" height=\"100px\"></a></div></td>";   
				       		}
				        }
						$output .= "</tr>";
						$output .= "</table>";
						$ouput .="</div>";
					
					
					
					
	                break;
	            
	            case 'video':
					$output .= "<video src=\"$path\" controls>";
	                break;
	            
	            case 'image/tiff':
	                break;
	            
	            //audio
	            case 'audio':
					$output .= "<audio controls>
								  <source src=\"$path\" type=\"audio/mpeg\">
								</audio>";
	                break;
	            
	        }
	        $output .= "</div>";
	    	}
        
        return $output;
    }