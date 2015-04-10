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
class playlist{
    
    public $id;

    function __construct($id=NULL){
        $this->setId($id);
    }
    
    function setId($id=NULL){
        if($id != NULL){
            $this->id = $id;
        }
    }
    function getGroupPlaylistArray($type='show', $group_id=NULL){
        $query = "(INSTR(`privacy`, '{$group_id}') > 0)";
        
        
        $db = new db();
        $result = $db->shiftResult($db->query('SELECT `id`, `title`, `privacy`, `user` FROM `playlists` WHERE '.$query), 'id');
        $ids = array();
        $titles[] = array();
        foreach($result AS $playListsData){
            if(authorize($playListsData['privacy'], $type, $playListsData['user'])){
	            $ids[] = $playListsData['id'];
	            $titles[] = $playListsData['title'];
            }
        }
			
	$return['ids'] = $ids;
	$return['titles'] = $titles;
	
	return $return;
    }
    function getUserPlaylistArray($type='show', $userId=NULL){
		if($userId == null){
			$userId = getUser();
		}
		$db = new db();
                $userGroupsQuery = $db->select('groupAttachments', array('item', 'user', '&&', 'itemId', $userId), array('group'));
                
                
                
		//get all the groups in which the current user is
                foreach($userGroupsQuery AS $userGroupsData){
                    $userGroups[] = $userGroupsData['group'];
                }

                //add them to the query
                foreach($userGroups AS &$userGroup){
                        $query = "$query OR (INSTR(`privacy`, '{$userGroup}') > 0)";
                }
		
		$buddylistClass = new buddylist();
			//get playlists from friends
				$buddies = $buddylistClass->buddyListArray();
                $buddies = join(',',$buddies);
                $query .= "OR (INSTR(`user`, '{$buddies}') > 0)";
        
            //get playlists for user and groups
            $playListsSql = mysql_query("SELECT id, title, privacy, user FROM playlists WHERE user='".getUser()."' $query");
            while($playListsData = mysql_fetch_array($playListsSql)){
                if(authorize($playListsData['privacy'], $type, $playListsData['user'])){
	                $ids[] = $playListsData['id'];
	                $titles[] = $playListsData['title'];
				}
            }
			
			$return['ids'] = $ids;
			$return['titles'] = $titles;
			
			return $return;
        
		
    }
    function select(){
        $db = new db();
        return $db->select('playlists', array('id', $this->id));
    }
    function getPlaylistTitle($playlistId=NULL){
               if(empty($playlistId))
                   $playlistId = $this->id;
                   $db = new db();
            $data = $db->select('playlists', array('id', $playlistId), array('title'));
            return $data['title'];
    }
    
    public function create($options){
       $title = $options['title'];
       $element = $options['element'];
       $privacy = $options['privacy'];
       if(empty($options['items'])){
            $items = array();
       }else{
            $items = $options['items'];
       }
       
       
       echo '...creating file';
       $files = new files();
       $file_id = $files->createFile($element, $title, $title.'.json', '', $privacy);
       echo 'file created...';
       
       $values['title'] = $title;
       $values['file_id'] = $file_id;
       $values['user'] = getUser();
       $values['privacy'] = $privacy;
       
       $db = new db();
       $db->insert('playlists', $values);
       
       $playlist_content = json_encode(array('info'=>$values, 'items'=>$items));
       
       $files->updateFileContent($file_id, $playlist_content);
       
       
       
       
    }
    
    
    public function pushItem($item){
        
        $playlistData = $this->select();
        $fileClass = new file($playlistData['file_id']);
        $playlistObject = json_decode($fileClass->read());
        
        $item['order_id'] = count($playlistObject->items);
        
        $playlistObject->items[] = $item;
        
        $fileClass->overwrite(json_encode($playlistObject));
        
    }
    
    //removes item from playlist
    //@param order_id order_id of the item that shall be deleted
    public function removeItem($order_id){
        
        $playlistData = $this->select();
        $fileClass = new file($playlistData['file_id']);
        $playlistObject = json_decode($fileClass->read());
        
        $items = $playlistObject->items;
        
        //get array element with order_id = $order_id
        foreach($items AS $key=>$item){
            if($item->order_id == $order_id){
                
                //key of the element that needs to be deleted
                $key_to_delete = $key;
            }
        }
        
        echo $key_to_delete;
        
        //delete element from array
        unset($items[$key_to_delete]);
        
        //resort array
        rsort($items);
        
        //resort order_ids and push item to final result
        foreach($items AS $key=>$item){
            $item->order_id = $key;
            $result[] = $item;
        }
        
        
        $playlistObject->items = $result;
        
        
        $fileClass->overwrite(json_encode($playlistObject));
    }
    
}