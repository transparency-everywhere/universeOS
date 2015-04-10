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
        $data=$db->query("SELECT `id` FROM feed $where");
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
        return $db->shiftResult($db->query("SELECT * FROM feed $where"));
    }
    
    
    //@del
    function d_show($type, $user=NULL, $limit=NULL, $feedId=NULL){
        
        if(empty($limit)){
            $limit = "0,30";
        }
        
        switch($type){
            
            //shows every entry in the system
            case "public":
                
                $where = "ORDER BY timestamp DESC LIMIT $limit"; //defines Query
                $needStructure = "1"; //defines if whole HTML structure is needed
                
                
            break;
        
            //shows just entries of buddies
            case "friends":
                
                
                //get all users which are in the buddylist
                $buddylistClass = new buddylist();
                $buddies = $buddylistClass->buddyListArray();
                $buddies[] = getUser();
                $buddies = join(',',$buddies);  
                //push array with the user, which is logged in
                
                $where = "WHERE author IN ($buddies) ORDER BY timestamp DESC LIMIT  $limit";
                $needStructure = "";
                
            break;
        
            //only shows entries of one user
            case "singleUser":
            
                $where = "WHERE author='$user' ORDER BY timestamp DESC LIMIT  $limit";
                $needStructure = "";
            break;
        
            //only shows entries which are attached to a grouo $user => $groupId
            case "group":
                
                
                $group = $user; //$user is used in this cased to pass the groupId
                $where = "WHERE INSTR(`privacy`, '{$group}') > 0 ORDER BY timestamp DESC limit $limit";
                $needStructure = "";
                
            break;
        
            //only shows a single feed entry
            case "singleEntry":
                $where = "WHERE id='$feedId'";
                break;
        }
        
        
        
        if(!empty($where)){
            
        $allreadyLoaded = explode(';',$_SESSION["feedsession_$type"]);
        
        $feedSql = mysql_query("SELECT * FROM feed $where");
        while($feedData = mysql_fetch_array($feedSql)) {
            
            if(!in_array($feedData['id'], $allreadyLoaded)){
                $token[] = $feedData['id'];
            }
            
            unset($attachment);
            
            switch($feedData['type']){
                case 'showThumb':
                $item = new item($feedData['attachedItem'], $feedData['attachedItemId']);
                $attachment = $item->showItemThumb();
                break;
            }
            
            
            
           
                
        $commentClass = new comments();
        $contextMenu = new contextMenu('feed', $feedData['id']);
        
        if($needStructure == "1")
            echo'<div id="add"></div>';
        
        echo '<div class="feedEntry feedNo'.$feedData['id'].'">';
            echo userSignature($feedData['author'], $feedData['timestamp']);
            echo $contextMenu->showItemSettings();
            echo '<div style="padding: 10px;">'.nl2br(universe::universeText(htmlspecialchars($feedData['feed']))).$text.'</div>';
            
            if(!empty($attachment)){
                echo '<div style="padding: 10px;padding-top: 0;">';
                echo $attachment;
                echo '</div>';
            }?>
            <div style="padding: 15px;">
                <div>
                    <?php
                    $item = new item("feed", $feedData['id']);
                    echo $item->showScore();
                    ?>
                </div>
            </div>
            <a href="javascript:comments.loadFeedComments(<?=$feedData['id'];?>);" class="btn btn-mini" style="float: left; margin-top: -41px; margin-left: 80px; color: #dcdcdc"><i class="icon icon-comment"></i></a>
            <div class="shadow" id="feed<?=$feedData['id'];?>" style="display:none;"></div>
            
        </div>
                <?php
                //feeds like the login feed are deleted after the validity passed
                if($feedData['validity'] == TRUE) {
                $time = time();
                if($feedData['validity'] < $time){
                    $db = new db();
                    $db->delete('feed', array('id', $feedData['id']));
                }}
                unset($text);
    }
    $_SESSION["feedsession_$type"] = implode(';', $token);
        
        
    }}
    
    
    function deleteFeeds($type, $itemid){
       $db = new db();
      
       if($db->delete('feed', array('attachedItem', $type, '&&', 'attachedItemId', $itemid))){
           return true;
       }
   }
    
   
    function create($author, $feed, $validity, $type, $privacy, $attachedItem=NULL, $attachedItemId=NULL){
        $feed = mysql_real_escape_string($feed);
		
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
    function d_add($owner, $feed, $type, $feedLink1, $feedLink2, $validity=NULL, $groups=NULL){
       $time = time();
       if(empty($validity)){
           $validity = ($time + $validity); 
       }
       
       if($validity == $time) {
           $validity = "0"; //otherwise validity = time() => feed will be deleted
       }
       if(isset($groups)){
           $privacy = $groups;
       }
       $values['owner'] = $owner;
       $values['timestamp'] = $time;
       $values['validity'] = $validity;
       $values['feed'] = $feed;
       $values['protocoll_type'] = $type;
       $values['feedLink1'] = $feedLink1;
       $values['feedLink2'] = $feedLink2;
       $values['privacy'] = $privacy;
       $db = new db();
       $db->insert('userfeeds', $values);
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