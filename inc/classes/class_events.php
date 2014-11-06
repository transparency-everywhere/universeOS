<?php

/**
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

@author nicZem for Tranpanrency-everywhere.com
 */


class events{
	
	public function create($user, $startStamp, $stopStamp, $title, $place, $privacy, $users, $originalEventId=0){
		$invitedUsers = $users;
		mysql_query("INSERT INTO `events` (`user`, `startStamp`, `stopStamp`, `title`, `place`, `privacy`, `invitedUsers`, `originalEventId`) VALUES ('".save($user)."', '".save($startStamp)."', '".save($stopStamp)."', '".save($title)."', '".save($place)."', '$privacy', '".save($invitedUsers)."', '".save($originalEventId)."');");
		
		//add personalEvents for each user in array $users
		if(!empty($users)){
			$users = explode(',', $users);
			$personalEvents = new personalEvents();
			foreach($users AS $user){
				if($user != 0)
	         		$personalEvents->create($user,$_SESSION['userid'],'event','invitation',mysql_insert_id());
			}
		}
	}
	
	public function joinEvent($originalEventId, $user, $addToVisitors=true){
				
		$originalEventData = $this->getData($originalEventId);
		
		//create new event
		$this->create($user, $originalEventData['startStamp'], $originalEventData['stopStamp'], $originalEventData['title'], $originalEventData['place'], 'h', '', $originalEventId);

		//update original event
		if($addToVisitors){
				
				//add user to inviteduser string
				$invitedUsers = $user.','.$originalEventData['invitedUsers'];
			
				//update db
				$db = new db();
				$db->update('events', array('invitedUsers'=>$invitedUsers), array('id', $originalEventId));
	
		}
	}
	
	public function update($eventId, $startStamp, $stopStamp, $title, $place, $privacy){
		
		$db = new db();
		$db->update('events', array('title'=>$title, 'place'=>$place, 'privacy'=>$privacy, 'startStamp'=>$startStamp,  'stopStamp'=>$stopStamp), array('id', $eventId));
	}
	
	public function get($user=NULL, $startStamp, $stopStamp, $privacy=NULL){
		
		if($privacy != NULL){
			$privacy = explode(';', $privacy);
			$privacy = implode('|', $privacy);
			$privacyQuery = "AND privacy REGEXP '$privacy'";
		}
		
		$sql = mysql_query("SELECT * FROM `events` WHERE `startStamp`>'".save($startStamp)."' AND `stopStamp`<'".save($stopStamp)."' AND `user`='".save($user)."' $privacyQuery");
		while($data = mysql_fetch_array($sql)){
			$data['editable'] = authorize($data['privacy'], 'edit', $data['user']);
			$arr[] = $data;
		}
		return $arr;
	}
	
	public function getData($eventId){
		$sql = mysql_query("SELECT * FROM `events` WHERE id='".save($eventId)."'");
		$data = mysql_fetch_array($sql);
		
		return $data;
	}
}
