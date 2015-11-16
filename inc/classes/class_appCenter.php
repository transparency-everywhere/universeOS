<?php
/*
This file is published by transparency-everywhere with the best deeds.
Check transparency-everywhere.com for further information.
Licensed under the CC License, Version 4.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

https://creativecommons.org/licenses/by/4.0/legalcode

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

       @author nicZem for transparency-everywhere.com
 */
class appCenter{
    public function getApps(){
        $db = new db();
        return $db->shiftResult($db->select('appCenterApps', array('temp',0)),'id');
    }
    
    public function getAppDetailsForUser(){
        $db = new db();
        $userApps  = $this->getAppsForUser();
        
        $appString = implode(',', $userApps);
        
        return $db->shiftResult($db->query("SELECT * FROM appCenterApps WHERE TEMP='0' AND id IN ($appString)"),'id');
    }
    public function getAppsForUser(){
        $results = array();
        
        $db = new db();
        foreach($db->shiftResult($db->select('userApps', array('user_id',getUser()), array('app_id')),'app_id') AS $result){
            $results[] = $result['app_id'];
        }
        return $results;
    }
}


class appCenterApp{
    /**
    * APP ID.
    *
    * @var int
    */
    public $ID;
    public function __construct($appCenterId = NULL){
        
    }
    
    public function prepareAppCreation(){
        
        
        //insert row to get insertID
        $db = new db();
        $insertId = $db->insert('appCenterApps', array('temp'=>true));
        
        //create archive and folder
        $class_folders = new folder();
        $appFolderId = $class_folders->create(uniConfig::$appCenter_folder_id, $insertId, getUser(), 'h');
        $element = new element();
        $appArchiveId = $element->create($appFolderId, 'package', 'application', getUser(), 'h');
        
        //update row with ids
        $rows['archive_id'] = $appArchiveId;
        $rows['folder_id'] = $appFolderId;
        $db->update('appCenterApps', $rows, array('id',$insertId ));
        
        return array('id'=>$insertId, 'archiveId'=>$appArchiveId);
    }
    
    public function getData($appId){
        
        $db = new db();
        return $db->select('appCenterApps', array('id',$appId));
    }
    
    public function addAppToUserDashboard($parameters){
        $appCenter = new appCenter;
        $userApps = $appCenter->getAppsForUser();
        
        if(!is_numeric($parameters['app_id'])){
            log_error('no nummeric value given in addAppToUserDashboard:'.$parameters['app_id']);
            return false;
        }
        
        //check if user already added the app
        if(in_array($parameters['app_id'], $userApps)){
            log_error('user tries to add allready added app.'.$parameters['app_id']);
            return false;
        }
        
        $db = new db();
        return $db->insert('userApps', array('app_id'=>$parameters['app_id'], 'user_id'=>getUser()));
    }
    
    public function removeAppFromUserDashboard($parameters){
        $db = new db();
        return $db->delete('userApps', array('app_id', $parameters['app_id'], '&&' , 'user_id', getUser()));
    }
    
    //updates temp db row
    //extracts zip archive to application_main folder
    public function create($appId, $arguments){
        
        $insertRows = array();
        foreach($arguments AS $argumentTitle=>$argumentValue){
            if(in_array($argumentTitle, array('title', 'version', 'description', 'entryPoint', 'privacy', 'archiveFileId', 'iconFileId'))){
                $insertRows[$argumentTitle] = $argumentValue;
            }
        }
        //camelCase
        if($insertRows['entryPoint']){
            $insertRows['entry_point'] = $insertRows['entryPoint'];
            unset($insertRows['entryPoint']);
        }
        //archive_file_id
        if($insertRows['archiveFileId']){
            $insertRows['archive_file_id'] = $insertRows['archiveFileId'];
            unset($insertRows['archiveFileId']);
        }
        //archive_file_id
        if($insertRows['iconFileId']){
            $insertRows['icon_file_id'] = $insertRows['iconFileId'];
            unset($insertRows['iconFileId']);
        }
        
        $insertRows['temp'] = 0;
        
        $insertRows['creation_timestamp'] = time();
        
        $appData = $this->getData($appId);
        
        $folder = new folder($appData['folder_id']);
        $application_mainFolderPath = universeBasePath . '/' . $folder->getPath().'main';
        
        
        //delete old mainFolderPath and create new
        system('/bin/rm -rf ' . escapeshellarg($application_mainFolderPath));
        mkdir($application_mainFolderPath,0755);
        
        //get filepath
        $file = new file($arguments['archiveFileId']);
        $archive_filePath =  universeBasePath.'/'.$file->getFullFilePath();
        
        //extract zip
        $zip = new ZipArchive;
        if ($zip->open($archive_filePath) === TRUE) {
            echo $zip->extractTo($application_mainFolderPath);
            $zip->close();
        }else{
            log_error('zip hasn\'t ben created');
            return false;
        }
        
        //update dp row
        $db = new db();
        $db->update('appCenterApps', $insertRows, array('id', $appId));
    }
    public function removeTempApplications(){
        
    }
}