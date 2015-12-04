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
		$values['status'] = sanitizeText($status);
		$values['title'] = sanitizeText($title);
		$values['description'] = sanitizeText($description);
		$values['privacy'] = $privacy;
		
		$db = new db();
		$db->insert('tasks', $values);
	}
	public function update($id, $user, $timestamp, $status, $title, $description, $privacy){
		
		$db = new db();
		$db->update('tasks', array('user'=>$user, 'timestamp'=>$timestamp, 'status'=>sanitizeText($status), 'title'=>sanitizeText($title), 'description'=>sanitizeText($description),  'privacy'=>$privacy), array('id',$id));
	
	}
	public function changeStatus($id, $status){
		$eventData=$this->getData($id);
		if(authorize($eventData['privacy'], 'edit', $eventData['user'])){
			
			$db = new db();
			$db->update('tasks', array('status'=>  sanitizeText($status)), array('id',$id));
		
		}
	}
	public function getData($id){
        $db = new db();
		return $db->select('tasks', array('id', $id));
	}
	public function delete($id){

		//@sec
        $db = new db();
		return $db->delete('tasks', array('id', $id));
	}
	public function get($user=NULL, $startStamp, $stopStamp, $privacy=NULL){
		$arr = array();
                $privacyQuery;
		if($privacy != NULL){
			$privacy = explode(';', $privacy);
			$privacy = implode('|', $privacy);
			$privacyQuery = "AND privacy REGEXP '$privacy'";
		}
		$db = new db();
                if($startStamp+$stopStamp > 0){
                    $timeQuery = "`timestamp`>'".save($startStamp)."' AND `timestamp`<'".save($stopStamp)."'";
                }else{
                    $timeQuery = '1 = 1';
                }
                $taskSQL = $db->shiftResult($db->query("SELECT * FROM `tasks` WHERE $timeQuery  AND `user`='".save($user)."' $privacyQuery"),'user');
                foreach($taskSQL AS $data){
                    
			$data['editable'] = authorize($data['privacy'], 'edit', $data['user']);
			$arr[] = $data;
		}
		return $arr;
	}
}
