<?php

//This file is published by transparency-everywhere with the best deeds.
//Check transparency-everywhere.com for further information.
//Licensed under the CC License, Version 4.0 (the "License");
//you may not use this file except in compliance with the License.
//You may obtain a copy of the License at
//
//https://creativecommons.org/licenses/by/4.0/legalcode
//
//Unless required by applicable law or agreed to in writing, software
//distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//See the License for the specific language governing permissions and
//limitations under the License.
//
//@author nicZem for Tranpanrency-everywhere.com

include('../../../../inc/config.php');
include('../../../../inc/functions.php');
 
                    $element = save($_POST['element']);
                    $title = save($_POST['title']);
                    $filename = save($_POST['filename']);
                    //set privacy
                    $customShow = $_POST['privacyCustomSee'];
                    $customEdit = $_POST['privacyCustomEdit'];
                    
                    $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
                    $user = getUser();


                    //upload file
                    $elementSQL = mysql_query("SELECT folder, title FROM elements WHERE id='$element'");
                    $elementData = mysql_fetch_array($elementSQL);
					
					    
                    $title10 = addslashes(substr($elementData['title'], 0, 10));
                    echo 'inititalizing class: <br>';
                    $folderClass = new folder($elementData['folder']);
                    echo 'path: <br>';
                    $path = universeBasePath.'/'.$folderClass->getPath();
                    echo $path;
                    $filename = "$filename.UFF";
                    $folder = $element;
                    $timestamp = time();
                    
                    
                    $ourFileName = "$path$filename";
                    
                    $ourFileHandle = fopen($ourFileName, 'w') or jsAlert("can\'t open file");
                    fclose($ourFileHandle);
                    if(mysql_query("INSERT INTO `files` (`id`, `folder`, `title`, `size`, `timestamp`, `filename`, `language`, `type`, `owner`, `votes`, `score`, `privacy`) VALUES (NULL, '$folder', '$title', '', '$timestamp', '$filename', '', 'UFF', '$user', '0', '0', '$privacy');")){
                      
                        //add feed
                        $fileId = mysql_insert_id();
                        $feed = "has created a new UFF-file";
                        
                        $feedClass = new feed();
                        $feedClass->create($user, $feed, "", "showThumb", $privacy, "file", $fileId);
                        
                       
                        jsAlert("your file has been created");
                        ?>
                        <script>
		        
                                   parent.filesystem.tabs.updateTabContent('<?=$title10;?>' ,parent.gui.loadPage('modules/filesystem/showElement.php?element=<?=$element;?>&reload=1'));
                            
                        </script>
		                <?
                    }
?>