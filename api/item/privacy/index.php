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
                    //set privacy
                    $customShow = $_POST['privacyCustomSee'];
                    $customEdit = $_POST['privacyCustomEdit'];
                    
                    $privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);
                
                   
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
            