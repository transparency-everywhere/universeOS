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


//handler for form submit in upload.php
//adds privacy and removes temp status
//from temp files
//set privacy
$customShow = $_POST['privacyCustomSee'];
$customEdit = $_POST['privacyCustomEdit'];

$privacy = exploitPrivacy($_POST['privacyPublic'], $_POST['privacyHidden'], $customEdit, $customShow);

$files = $_POST['uploadedFiles'];
$successfullUploadedFiles = 0;
foreach($files AS $file){
        $woff .= $file;
        $fileClass = new file($file);
        if($fileClass->validateTempFile($privacy)){
            $successfullUploadedFiles++;
        }else{
            $filesWithError[] = $file; //add fileid to error list
        }
}
echo'<script> filesystem.tabs.removeTab(' + uploaderTabId + '); elements.open(\'' + element + '\', \'' + elementTabId + '\'); </script>';
jsAlert("The files have successfully been added to the Element.");
			
				
			
				
            
?>