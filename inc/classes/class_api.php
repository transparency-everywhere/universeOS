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
       @author pabst for transparency-everywhere.com
 */

class api{
        public function handleRequest($request, $function){
            foreach($request AS $parameters){
                $result[] = $function($parameters);
            }
            echo json_encode($result);
            return true;
        }
	public function useridToUsername($request){
		if(is_numeric($request)){
			//only a single request
			return useridToUsername($request);
                }else {
			//array of requests
			$userids = json_decode($request,true);
			
			foreach($userids as $userid){
				$ret[$userid] = useridToUsername($userid);
			}
			return json_encode($ret);
		}
	}
	public function useridToRealname($request){
		if(is_numeric($request))
			//only a single request
			return useridToRealname($request);
		else {
			//array of requests
			$userids = json_decode($request,true);
			
			foreach($userids as $userid){
				$ret[$userid] = useridToRealname($userid).' ';
			}
			
			return json_encode($ret);
			
		}
	}
	public function searchUserByString($string, $limit){
		$q = save($string);
		$k = save($limit);
                $db = new db();
                $results = $db->shiftResult($db->query("SELECT userid, username, realname FROM user WHERE username LIKE '%$q%' OR realname LIKE '%$q%' OR email='$q' OR userid='$q' LIMIT 0,10"), 'userid');
	
		foreach($results AS $suggestData) {
			
			
			if(!isset($return[$userid])){		//only return every user once
				
				$userid = $suggestData['userid'];
				$array[] = $suggestData['username'];
				$array[] = $suggestData['realname'];
				
				$return[$userid] = $array;		//add user data tu return array
				
			}
		}
		
		return $return;
	}
	
	//returns base64 string with userdata
	public function getUserPicture($request){
		//single userid
		if(is_numeric($request)){
			$userids[] = save($request);
			$numeric = true;
		}else {
			$numeric = false;
			//array of requests
			$userids = $request;
		}
			foreach($userids AS $userid){
				$userClass = new user($userid);
				$userData = $userClass->getData();
			
				//check if user is standard user
				if(empty($userData['userPicture'])){
					$src = 'gfx/standardusersm.png';
				}else{
					$src = 'upload/userFiles/'.$userid.'/userPictures/thumb/300/'.$userData['userPicture'];
				}
				$mime = mime_content_type($src);
				
                                $file = fopen(universeBasePath.'/'.$src, 'r');
                                $output = base64_encode(fread($file, filesize($src)));
				$return[$userid] = 'data:'.$mime.';base64,'.$output;
				
			}
			
			if($numeric){
				return $return[$request];
			}else{
				return json_encode($return);
			}
			
		
	
	}
	public function getLastActivity($request){
		
		//single userid
		if(is_numeric($request)){
			$userids[] = save($request);
			$numeric = true;
		}else {
			//array of requests
			$userids = json_decode($request,true);
		}
			foreach($userids AS $userid){
				
				$userid = save($userid);
                                $db = new db();
                                $data = $db->select('user', array('userid', $userid), array('lastactivity'));
                                
				$diff = time() - $data['lastactivity'];
				if($diff < 90){
					$return[$userid] =  1;
				}else{
					$return[$userid] =  0;
				}
				
			}
			
			if($numeric){
				return $return[$request];
			}else{
				return json_encode($return);
			}
			
		
	}
}