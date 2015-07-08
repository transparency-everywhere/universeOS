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


class groups{
	public function get($userid=NULL){
            $groups;
            if(empty($userid)){
                    $userid = getUser();
            }else{
                 //check privacy rights!
                 $privacy = new userPrivacy($userid);
                 if(!$privacy->proofRight('groups')){
                     return array();
                 }
            }
            $db = new db();
            $userGroups = $db->shiftResult($db->select('groupAttachments', array('item', 'user', '&&', 'validated','1','&&','itemId',$userid), array('group')), 'group');
            
            foreach($userGroups AS $data){
                    $groups[] = $data['group'];
            }
            return $groups;
	}
	public function getTitle($groupId){
            $db = new db();
            $data = $db->select('groups', array('id', $groupId), array('title'));
            return $data['title'];
	}
        public function getMembers($groupId){
            $db = new db();
            $users = $db->shiftResult($db->select('groupAttachments', array('group', $groupId, 'AND', 'validated', '1')), 'group');
            
            foreach($users AS $userData){
                $members[] = $userData['itemId'];
            }
            
            return $members;
        }
        
        
        public function updateGroupPicture($groupId, $fileArray){
            $groupData = $this->getGroupData($groupId);
            $fileClass = new files();
            $fileId = $fileClass->addFile($fileArray, $groupData['groupPictureElement'], $groupData['homeFolder'], 'p', $groupId);

            $file = new file($fileId);
            $fileData = $file->getFileData();

            $db = new db();
            $db->update('groups', array('groupPicture'=>$fileData['filename']), array('id',$groupId));

            echo '<script>';
            echo 'parent.settings.showUpdateProfileForm();';
            echo '</script>';
        }
        
        public function getOpenRequests($userid=NULL){
            if(empty($userid))
                    $userid = getUser();
            $newGroupSql = mysql_query("SELECT * FROM  `groupAttachments` WHERE  item='user' AND  `validated`='0' AND itemId='$userid' ORDER BY timestamp DESC LIMIT 0, 3");
            while($newGroupData = mysql_fetch_array($newGroupSql)){
                $return[] = array('group_id'=>$newGroupData['group'],'author'=>$newGroupData['author']);
            }
            
            return $return;
        }
        
        function userJoinGroup($group, $user=NULL){

                        $userid = getUser();

                        if(empty($user)){
                                $user = $userid;
                        }

                        
                        $groupsClass = new groups();
                        $groupData = $groupsClass->getGroupData($group);

                        $db = new db();
                        if($groupData["public"] == "1"){
                                $checkUpData = $db->select('groupAttachments', array('group', $group,'AND','item','user','AND','itemId',$user));
                                if(is_array($checkUpData)){
                                    
                                    $db->update('groupAttachments', array('validated'=>1), array('group', $group,'AND','item','user','AND','itemId',$user));

                                }else{
                                    $values['group'] = $group;
                                    $values['item'] = 'user';
                                    $values['itemId'] = $user;
                                    $values['timestamp'] = time();
                                    $values['author'] = $user;
                                    $values['validated'] = 1;
                                    $db->insert('groupAttachments', $values);
                                }

                        }else{
                                $db->update('groupAttachments',array('validated'=>1), array('group', $group,'AND','item','user','AND','itemId',$user));

                        }
                    
                }
                
        function sendRequestToUser($group, $user, $userid){
            $db = new db();
        
        
            $values['group'] = $group;
            $values['item'] = 'user';
            $values['itemId'] = $user;
            $values['timestamp'] = time();
            $values['author'] = $userid;
            $values['validated'] = 0;
            $db->insert('groupAttachments', $values);

        }

        function userLeaveGroup($group, $user=NULL){
                    if(empty($user))
                        $user = getUser();
                    
                    
                    $db = new db();
                   
                    if( $db->delete('groupAttachments', array('group', $group, '&&', 'item', 'user', '&&', 'itemId', $user))){
                                return true;
                        }
                }

        function getGroups($userid=NULL){
                        //moved to class groups->get();
                        $groups = new Groups();
                        return $groups->get($userid);

                }
                
        function getGroupData($groupId){
                    $db = new db();
                    $data = $db->select('groups', array('id', $groupId));
                    if($data['admin'] == getUser()){
                        $data['isAdmin'] = true;
                    }
                    return $data;
                }

        function getGroupName($groupId){
                    $db = new db();
                    $data = $db->select('groups', array('id', $groupId), array('title'));
                    return $data['title'];
        }
                
          
        function getPicture($groupId){

                $groupData = $this->getGroupData($groupId);

                //check if user is standard user
                if(empty($groupData['groupPicture'])){
                        $src = universeBasePath.'/gfx/standardusersm.png';
                }else{
                        $src = universeBasePath.'/upload/groupFiles/'.$groupId.'/'.$groupData['groupPicture'];
                }
                $mime = mime_content_type($src);

                $file = fopen($src, 'r');
                $output = base64_encode(fread($file, filesize($src)));
                return 'data:'.$mime.';base64,'.$output;
        }

        function countGroupMembers($groupId){
                $total = mysql_query("SELECT COUNT(*) FROM `groupAttachments` WHERE `group`='$groupId' AND `item`='user' AND `validated`='1' "); 
                $total = mysql_fetch_array($total); 
                return $total[0];
            }

        function createGroup($title, $privacy, $description, $users){

                    $userid = getUser();

                    //check if nessecary informations are given
                    if((isset($description)) && (isset($title)) && (isset($privacy))){
                        $db = new db();
                        $values['title'] = $title;
                        $values['description'] = $description;
                        $values['public'] = $privacy;
                        $values['admin'] = $userid;
                    //insert group into db    
                    $groupId = $db->insert('groups', $values);

                        //add users to group
                        if(isset($users)){
                        foreach ($users as &$user) {
                            unset($values);
                            $values['group'] = $groupId;
                            $values['item'] = 'user';
                            $values['itemId'] = $user;
                            $values['timestamp']= time();
                            $values['author'] = $userid;
                            $db->insert('groupAttachments', $values);
                        }}
                        $folderCLass = new folder();
                        $groupFolder = $folderCLass->create("3", $groupId, $userid, "$groupId//$groupId", false);
                        
                        //create thumb folders || NOT LISTED IN DB!
                        $path3 = universeBasePath."//upload//groupFiles//$groupId//thumb";
                        $path4 = universeBasePath."//upload//groupFiles//$groupId//thumb//25";
                        $path5 = universeBasePath."//upload//groupFiles//$groupId//thumb//40";
                        $path6 = universeBasePath."//upload//groupFiles//$groupId//thumb//300";
                        mkdir($path3);  //Creates Thumbnail Folder
                        mkdir($path4); //Creates Thumbnail Folder
                        mkdir($path5); //Creates Thumbnail Folder
                        mkdir($path6); //Creates Thumbnail Folder
                        
                        
                        $element = new element();
                        $groupElement = $element->create($groupFolder, "groupElement", "groupElement", $userid,  "$groupId//$groupId");
                        
                        //picture element
                        $groupPictureElement = $element->create($groupFolder, "groupPicture", "groupPicture", $userid,  "$groupId//$groupId");
                        
                        unset($values);
                        $values['homeFolder'] = $groupFolder;
                        $values['homeElement'] = $groupElement;
                        $values['groupPictureElement'] = $groupPictureElement;
                        $db->update('groups', $values, array('id', $groupId));
                        
                                //add user which added group to group and validate
                                unset($values);
                                $values['group'] = $groupId;
                                $values['item'] = 'user';
                                $values['itemId'] = $userid;
                                $values['timestamp'] = $time;
                                $values['author'] = $userid;
                                $values['validated'] = '1';
                                $db->insert('groupAttachments', $values);
                                return true;

                        }else{
                        jsAlert("please fill out everything");
                    }

          }

        function deleteUserFromGroup($userid, $groupid){
            $db = new db();
            if($db->delete('groupAttachments', array('group', $groupid, 'AND', 'item', 'user', 'AND', 'itemId', $userid))){
                    return true;
            }
          }

        function update($groupId, $privacy, $description, $membersInvite){
            if(is_string($membersInvite)){
                $membersInvite = ($membersInvite === 'true');
            }
            
                    $db = new db();
                    $values['public'] = $privacy;
                    $values['description'] = $description;
                    
                    $values['membersInvite'] = $membersInvite;
                    
                    if($db->update('groups', $values, array('id', $groupId))){
                            return true;
                    }

          }

        function makeUserAdmin($groupId, $userId){
                    $db = new db();
                        $groupData = $this->getGroupData($groupId);

                        $adminString = $groupData['admin'];
                        //proof if user is allready admin
                        $admins = explode(";",$adminString);
                        if(!in_array("$userId", $admins)&& //check if $userId is allready admin
                          (in_array(getUser(), $admins))){ //also check if current user is admin
                                $adminString = "$adminString;$userId";
                                $values['admin'] = $adminString;
                                
                                if($db->update('groups', $values, array('id', $groupId))){
                                        return true;
                                }

                        }

          }
        function groupRemoveAdmin($groupId, $userId){

                        $groupData = $this->getGroupData($groupId);

                        $adminString = $groupData['admin'];

                        $admins = explode(";", $adminString);
                        
                        if(in_array(getUser(), $admins)){
                            //proof if user is allready admin
                            $newString = str_replace($userId, '', $adminString);
                            $newString = str_replace(';;', ';', $newString);
                            
                            $db = new db();
                            $values['admin'] = $newString;
                            $db->update('groups', $values, array('id', $groupId));
                            return true;
                        }else{
                            return false;
                        }
          }
          
        function delete($groupId){
                        $groupData = $this->getGroupData($groupId);

                        $adminString = $groupData['admin'];

                        $admins = explode(";", $adminString);
                        
                        if(in_array(getUser(), $admins)){
                            $db = new db();
                            $db->delete('groupAttachments', array('group', $groupId));
                            
                            $db->delete('groups', array('id', $groupId));
                            
                        }
        }
}


