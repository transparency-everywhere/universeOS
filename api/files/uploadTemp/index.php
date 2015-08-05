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


//upload temp_file
$file = $_FILES['Filedata'];

$user = getUser();
$filesClass = new files();
$id = $filesClass->uploadTempfile($file, $_POST['element'], '', $privacy, $user);

$li = "<li data-fileid=\"$id\">     <img src=\"gfx/icons/fileIcons/".$filesClass->getFileIcon($filesClass->getMime($file['name']))."\" height=\"16\">     ".$file['name']."      <input type=\"hidden\" name=\"uploadedFiles[]\" value=\"$id\">    <i class=\"icon-remove pointer pull-right\" onclick=\"$(this).parent(\\'li\\').remove()\"></i></li>";

//add file to filelist in the uploader
echo'$(".tempFilelist").append(\''.$li.'\');';
?>