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
    
    function show($type, $user=NULL, $limit=NULL, $feedId=NULL){
        
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
        if($needStructure == "1"){ ?><div id="add"></div><?php } ?>
        <div id="realFeed" class="feedNo<?=$feedData['id'];?>">
            <?=userSignature($feedData['author'], $feedData['timestamp']);?>
            <div style="padding: 10px;"><?=nl2br(universe::universeText(htmlspecialchars($feedData['feed'])));?><?=$text;?></div><br>
            <div style="padding: 15px;">
                <?=$attachment;?>
            </div>
            <div style="padding: 15px;">
                <div>
                    <?php
                    $item = new item("feed", $feedData['id']);
                    echo $item->showScore();
                    ?>
                    <div style="float:right; position: absolute; margin-top: -24px; margin-left: 108px;">
                        <?=$contextMenu->showItemSettings();?>
                    </div>
                </div>
            </div>
            <a href="javascript:showfeedComment(<?=$feedData['id'];?>);" class="btn btn-mini" style="float: right; margin-top: -38px; margin-right: 15px; color: #606060"><i class="glyphicon glyphicon-comment"></i>&nbsp;(<?=$commentClass->countComment("feed", $feedData[id]);?>)</a>
            <div class="shadow" id="feed<?=$feedData['id'];?>" style="display:none;"></div>
            
        </div>
                <?php
                //feeds like the login feed are deleted after the validity passed
                if($feedData['validity'] == TRUE) {
                $time = time();
                if($feedData['validity'] < $time){
                    mysql_query("DELETE FROM feed WHERE id='$feedData[id]'");
                }}
                unset($text);
    }
    $_SESSION["feedsession_$type"] = implode(';', $token);
        
        
    }}
    
    
   function deleteFeeds($type, $itemid){
       if(mysql_query("DELETE FROM feed WHERE attachedItem='$type' AND attachedItemId='$itemid'")){
           return true;
       }
   }
    
   
    function create($author, $feed, $validity, $type, $privacy, $attachedItem=NULL, $attachedItemId=NULL){
        $feed = mysql_real_escape_string($feed);
		
       //if privacy==h feed is not shown anyway so an insert would be jabberuserless
       if($privacy != "h"){
           
       $time = time();
       if(empty($validity)){
           $validity = ($time + $validity); 
       }
       
       if($validity == $time) {
           $validity = "0"; //otherwise validity = time() => feed will be deleted
       }
       mysql_query("INSERT INTO `feed` (`id`, `author`, `feed`, `timestamp`, `validity`, `type`, `attachedItem`, `attachedItemId`, `privacy`) VALUES (NULL, '$author', '$feed', '$time', '$validity', '$type', '$attachedItem', '$attachedItemId', '$privacy');");
       $id = mysql_insert_id();
       
       return $id;
       
       }
    }
   function add($owner, $feed, $type, $feedLink1, $feedLink2, $validity=NULL, $groups=NULL){
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
       mysql_query("INSERT INTO userfeeds (`owner`,`timestamp`,`validity`,`feed`,`protocoll_type`,`feedLink1`,`feedLink2`,`privacy`) VALUES('$owner', '$time', '$validity', '$feed', '$type', '$feedLink1', '$feedLink2', '$privacy');");
    }
    
    
    
}