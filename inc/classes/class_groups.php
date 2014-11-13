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
		if(empty($userid))
			$userid = getUser();
		
		$sql = mysql_query("SELECT `group` FROM `groupAttachments` WHERE `item`='user' AND `validated`='1' AND `itemId`='".mysql_real_escape_string($userid)."'");
		while($data = mysql_fetch_array($sql)){
			$groups[] = $data['group'];
		}
		return $groups;
	}
	public function getTitle($groupId){
		$data = mysql_fetch_array(mysql_query("SELECT `title` FROM `groups` WHERE id='".save($groupId)."'"));
		return $data['title'];
	}
        function userJoinGroup($group, $user=NULL){

                        $userid = getUser();

                        if(empty($user)){
                                $user = $userid;
                        }

                mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$group', 'user', '$user', '$time', '$userid');");

                }

        function userLeaveGroup($group, $user=NULL){
                        if(mysql_query("DELETE FROM `groupAttachments` WHERE group='$group' AND item='user' AND itemId='".save($user)."'")){
                                return true;
                        }
                }

        function getGroups($userid=NULL){
                        //moved to class groups->get();
                        $groups = new Groups();
                        return $groups->get($userid);

                }
                
        function getGroupData($groupId){
                        $data = mysql_fetch_array(mysql_query("SELECT * FROM groups WHERE id='".mysql_real_escape_string($groupId)."'"));
                        return $data;
                }

        function getGroupName($groupId){
                        $data = mysql_fetch_array(mysql_query("SELECT title FROM groups WHERE id='".mysql_real_escape_string($groupId)."'"));
                        return $data['title'];
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
                    //insert group into db    
                    mysql_query("INSERT INTO `groups` (`title`, `description`, `public`, `admin`) VALUES ('$title', '$description', '$privacy', '$userid');");
                    $groupId = mysql_insert_id();

                        //add users to group
                        if(isset($users)){
                        foreach ($users as &$user) {

                        mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`) VALUES ('$groupId', 'user', '$user', '$time', '$userid');");


                        }}
                        $folderCLass = new folder();
                        $groupFolder = $folderCLass->create("3", $groupId, $userid, "$groupId//$groupId");
                        $element = new element();
                        $groupElement = $element->create($groupFolder, $title, "other", $userid,  "$groupId//$groupId");
                        mysql_query("UPDATE `groups` SET `homeFolder`='$groupFolder', `homeElement`='$groupElement' WHERE id='$groupId'");

                                //add user which added group to group and validate
                                 mysql_query("INSERT INTO `groupAttachments` (`group`, `item`, `itemId`, `timestamp`, `author`, `validated`) VALUES ('$groupId', 'user', '$userid', '$time', '$userid', '1');");

                                        return true;

                        }else{
                        jsAlert("please fill out everything");
                    }

          }

        function deleteUserFromGroup($userid, $groupid){

                if(mysql_query("DELETE FROM groupAttachments WHERE `group`='".save($groupid)."' AND `item`='user' AND `itemId`='".save($userid)."'")){
                        return true;
                }
          }

        function update($groupId, $privacy, $description, $membersInvite){

                        if(mysql_query("UPDATE groups SET public='$privacy', description='$description', membersInvite='$membersInvite' WHERE id='$groupId'")){
                                return true;
                        }

          }

        function makeUserAdmin($groupId, $userId){
                        $groupData = $this->getGroupData($groupId);

                        $adminString = $groupData['admin'];

                        //proof if user is allready admin
                        $admins = explode($adminString, ";");
                        if(!in_array("$userId", $admins)){
                                $adminString = "$adminString;$userId";

                                if(mysql_query("UPDATE `groups` SET `admin`='$adminString' WHERE id='".save($groupId)."'")){
                                        return true;
                                }

                        }

          }
        function groupRemoveAdmin($groupId, $userId){

                        $groupData = $this->getGroupData($groupId);

                        $adminString = $groupData['admin'];

                        //proof if user is allready admin
                        $admins = explode($adminString, ";");
          }
}


