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

include('../../../inc/config.php');
include('../../../inc/functions.php');
    if(proofLogin()){
            if(isset($_POST['submit'])){
                
                    
                    //set privacy
                    $customShow = $_POST['privacyCustomSee'];
                    $customEdit = $_POST['privacyCustomEdit'];
                    
                    $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
                
                    //setting privacy
                    $groups = $_POST['groups'];
                    foreach ($groups as $group){
                        $Groups = "$group; $Groups";
                    }
                    if(empty($Groups)){
                        $Groups = "p";
                    }
                    //checking if upload is anonymous
                    if(!empty($_POST['anonymous'])){
                        $user = "0";
                    }else{
                        $user = $userid;
                    }
                    if(isset($_POST['hidden'])){
                        $Groups = "u";
                    }
                    if(isset($_POST['publ'])){
                        $Groups = "p";
                    }
                    if($_POST['type'] == "folder"){
                        mysql_query("UPDATE folders SET privacy='$privacy' WHERE id='".save($_POST['itemId'])."'");
                        if(!empty($_POST['hidden'])){
                            mysql_query("UPDATE folders SET creator='$user' WHERE id='".save($_POST['itemId'])."'");   
                        }
                        echo 1;
                    }
                    else if($_POST['type'] == "element"){
                        mysql_query("UPDATE elements SET privacy='$privacy' WHERE id='".save($_POST['itemId'])."'");
                        if(!empty($_POST['hidden'])){
                            mysql_query("UPDATE elements SET author='$user' WHERE id='".save($_POST['itemId'])."'");   
                        }
                        echo 1;
                    }
                    else if($_POST['type'] == "comment"){
                        mysql_query("UPDATE comments SET privacy='$privacy' WHERE id='".save($_POST['itemId'])."'");
                        if(!empty($_POST['hidden'])){
                            mysql_query("UPDATE commments SET author='$user' WHERE id='".save($_POST['itemId'])."'");   
                        }
                        echo 1;
                    }
                    else if($_POST['type'] == "feed"){
                        mysql_query("UPDATE feed SET privacy='$privacy' WHERE id='".save($_POST['itemId'])."'");
                       
                        echo 1;
                    }
                    else if($_POST['type'] == "file"){
                        mysql_query("UPDATE files SET privacy='$privacy' WHERE id='".save($_POST['itemId'])."'");
                        
                        echo 1;
                    }
                    else if($_POST['type'] == "link"){
                        mysql_query("UPDATE links SET privacy='$privacy' WHERE id='".save($_POST['itemId'])."'");
                        echo 1;
                    }
            }
            
            
            
            
            //get type
            if($_POST['type'] == "folder"){
                $privacySql = mysql_query("SELECT name, privacy FROM folders WHERE id='".save($_POST['itemId'])."'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "folder $privacyData[name]";
            }
            if($_POST['type'] == "element"){
                $privacySql = mysql_query("SELECT title, privacy FROM elements WHERE id='".save($_POST['itemId'])."'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "element $privacyData[title]";
            }
            
            if($_POST['type'] == "comment"){
                $privacySql = mysql_query("SELECT privacy FROM comments WHERE id='".save($_POST['itemId'])."'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your comments";
            }
            if($_POST['type'] == "feed"){
                $privacySql = mysql_query("SELECT privacy FROM feed WHERE id='".save($_POST['itemId'])."'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your feeds";
            }
            if($_POST['type'] == "file"){
                $privacySql = mysql_query("SELECT privacy FROM files WHERE id='".save($_POST['itemId'])."'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your files";
            }
            if($_POST['type'] == "link"){
                $privacySql = mysql_query("SELECT privacy FROM links WHERE id='".save($_POST['itemId'])."'");
                $privacyData = mysql_fetch_array($privacySql);
                $title = "one of your links";
            }
            
            //set values
            if($privacyData['privacy'] == "p"){
                $public = 'checked="checked"';
            }else if($privacyData['privacy'] == "u"){
                $hidden = 'checked="checked"';
            }else{
                $groupsArray = explode(";", $privacyData['privacy']);
            }
            ?>
            <form action="doit.php?action=changePrivacy&type=<?=$_GET['type'];?>&itemId=<?=$_GET['itemId'];?>" target="submitter" method="post">
            <div class="jqPopUp border-radius transparency" id="editPrivacy">
                
                <header>Set privacy of <?=$title;?><a class="jqClose" id="closePrivacy">X</a></header>
                <div class="jqContent">
                <?
                $privacyClass = new privacy($privacyData['privacy']);
                $privacyClass->showPrivacySettings();
                ?>
                </div>
                <footer>
                	<span class="pull-right"><input type="submit" name="submit" value="save" class="btn btn-info" style="margin-top: 15px;" id="submitPrivacy"></span>
                </footer>
            </div>
            </form>
            <script>
                $("#submitPrivacy").click(function () {
                $('#editPrivacy').slideUp();
                });
                $("#closePrivacy").click(function () {
                $('#editPrivacy').slideUp();
                });
            </script>
            <?}