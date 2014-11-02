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

@author nicZem for Tranpanrency-everywhere.com
 */


class tasks{
	public function create($user, $timestamp, $status, $title, $description, $privacy){
		$values['user'] = $user;
		$values['timestamp'] = $timestamp;
		$values['status'] = $status;
		$values['title'] = $title;
		$values['description'] = $description;
		$values['privacy'] = $privacy;
		
		$db = new db();
		$db->insert('tasks', $values);
	}
	public function update($id, $user, $timestamp, $status, $title, $description, $privacy){
		
		$db = new db();
		$db->update('tasks', array('user'=>$user, 'timestamp'=>$timestamp, 'status'=>$status, 'title'=>$title, 'description'=>$description,  'privacy'=>$privacy), array('id',$id));
	
	}
	public function changeStatus($id, $status){
		$eventData=$this->getData($id);
		if(authorize($eventData['privacy'], 'edit', $eventData['user'])){
			
			$db = new db();
			$db->update('tasks', array('status'=>$status), array('id',$id));
		
		}
	}
	public function getData($id){
		
		
		$query = "WHERE `id`='".save($id)."'";
		$data = mysql_fetch_array(mysql_query("SELECT * FROM `tasks` $query"));
		return $data;
	}
	public function get($user=NULL, $startStamp, $stopStamp, $privacy=NULL){
		
		if($privacy != NULL){
			$privacy = explode(';', $privacy);
			$privacy = implode('|', $privacy);
			$privacyQuery = "AND privacy REGEXP '$privacy'";
		}
		
		$sql = mysql_query("SELECT * FROM `tasks` WHERE `timestamp`>'".save($startStamp)."' AND `timestamp`<'".save($stopStamp)."' AND `user`='".save($user)."' $privacyQuery");
		while($data = mysql_fetch_array($sql)){
			$data['editable'] = authorize($data['privacy'], 'edit', $data['user']);
			$arr[] = $data;
		}
		return $arr;
	}
}
