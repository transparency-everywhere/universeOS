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
class feed {
    
    function loadFeedsFrom($startId, $type, $typeId=NULL){
        
        $where = $this->generateWhere($type, $typeId, '0', $startId);
        $db = new db();
        return $db->shiftResult($db->query("SELECT * FROM feed $where"), 'id');
        
    }
    function getHighestId($type, $typeId=NULL){
        
        $where = $this->generateWhere($type, $typeId, '0,1');
        
        $db = new db();
        $data=$db->shiftResult($db->query("SELECT `id` FROM feed $where"), 'id');
        return $data['id'];
        
    }
    function getData($feedId){
        $db = new db();
        return $db->select('feed', array('id', $feedId));
    }
    
    function generateWhere($type, $typeId=NULL, $limit=NULL, $lowestId){
        $type = save($type);
        $typeId = save($typeId);
        $limit = save($limit);
        $lowestId = save($lowestId);
        
        if(empty($limit)){
            $limit = "0,30";
        }
        
        $where = 'WHERE 1=1';
        
        if(!empty($lowestId)){
            $where .= ' AND `id`>\''.$lowestId.'\'';
        }
        
        switch($type){
            case 'global':
                
                $where .= " ORDER BY id DESC LIMIT $limit"; //defines Query
                break;
            case 'user':
                $where .= " AND author='$typeId' ORDER BY id DESC LIMIT  $limit";
                break;
            case 'friends':
                
                
                
                //get all users which are in the buddylist
                $buddylistClass = new buddylist();
                $buddies = $buddylistClass->buddyListArray();
                $buddies[] = getUser();
                $buddies = join(',',$buddies);  
                //push array with the user, which is logged in
                
                $where .= " AND author IN ($buddies) ORDER BY id DESC LIMIT  $limit";
                
                break;
            case 'group':
                
                $group = $typeId; //$user is used in this cased to pass the groupId
                $where .= " AND INSTR(`privacy`, '{$group}') > 0 ORDER BY id DESC LIMIT $limit";
                
                break;
        }
        return $where;
    }
    
    function load($type, $typeId=NULL, $limit=NULL){
        
        $where = $this->generateWhere($type, $typeId, $limit);
        
        $db = new db();
        return $db->shiftResult($db->query("SELECT * FROM feed $where"), 'id');
    }
    
    function delete($feed_id){
        $feedData = $this->getData($feed_id);
        if(authorize($feedData['privacy'], "show", $feedData['author'])){
            $db = new db();
            $db->delete('feed', array('id', $feed_id));
            return true;
        }
    }
    function deleteFeeds($type, $itemid){
       $db = new db();
      
       if($db->delete('feed', array('attachedItem', $type, '&&', 'attachedItemId', $itemid))){
           return true;
       }
   }
    
   
    function create($author, $feed, $validity, $type, $privacy, $attachedItem=NULL, $attachedItemId=NULL){
        $feed = sanitizeText($feed);
        $attachedItem = sanitizeText($attachedItem);
		
        if(!empty($attachedItem)&&!empty($attachedItemId)){
            $type = 'showThumb';
        }
        
       //if privacy==h feed is not shown anyway so an insert would be jabberuserless
       if($privacy != "h"){
       $time = time();
       $time = time();
       if(empty($validity)){
           $validity = ($time + $validity); 
       }
       
       if($validity == $time) {
           $validity = "0"; //otherwise validity = time() => feed will be deleted
       }
       
       $values['author'] = $author;
       $values['feed'] = $feed;
       $values['timestamp'] = $time;
       $values['validity'] = $validity;
       $values['type'] = $type;
       $values['attachedItem'] = $attachedItem;
       $values['attachedItemId'] = $attachedItemId;
       $values['privacy'] = $privacy;
       $db = new db();
       $id = $db->insert('feed', $values);
       
       return $id;
       
       }
    }

    
}


class itemFeed{
    function load($itemType, $itemId, $limit=NULL, $lowestId=NULL){
        
        if(empty($limit)){
            $limit = "0,30";
        }else{
            $limit = save($limit);
        }
        
        $where = 'WHERE 1=1';
        
        $where .= "`attachedItem`='".save($itemType)."' AND `attachedItemId`='".save($itemId)."'";
        
        if(!empty($lowestId)){
            $where .= ' AND `id`>\''.save($lowestId).'\'';
        }
                
        $where .= " ORDER BY `id` DESC LIMIT $limit"; //defines Query
    }
}