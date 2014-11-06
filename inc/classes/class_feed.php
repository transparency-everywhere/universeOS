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
    //put your code here
}
    function showFeed($feedid, $type=NULL, $mobile=NULL) {
        if($mobile == 1){
            $subPath = '1';
        }
        $where = "ORDER BY timestamp DESC LIMIT 30"; //defines Query
        $needStructure = "1"; //defines if whole HTML structure is needed
        if($type == "single"){
            $where = "WHERE owner='$feedid' ORDER BY timestamp DESC LIMIT 0,1";
            $needStructure = "";
        }
        $feedSql = mysql_query("SELECT * FROM userfeeds $where");
        while($feedData = mysql_fetch_array($feedSql)) {
            if($feedData['owner'] == "$_SESSION[userid]"){
                $ownerLink = "<a href=\"doit.php?action=deleteFeed&feedId=$feedData[feedid]\" target=\"submitter\"><img src=\"gfx/trash.gif\" alt=\"delete\" style=\"position: absolute; margin-left: 60px; margin-top: -20px;\"></a>";
            }else{
                unset($ownerLink);
            }
            
            
            
            // in the table elements the elements have a "title", in the table folders the folders have a "name" that realy sucks!
            if($feedData['protocoll_type'] == "fileUpload"){
                $folderAddSql = mysql_query("SELECT id, title FROM elements WHERE id='$feedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"createNewTab('fileBrowser_tabView','".$folderAddData['title']."','','modules/filesystem/showelement.php?element=".$folderAddData['id']."',true);return false\"> ".$folderAddData[title]."</a>";
                }if($feedData['protocoll_type']=="elementAdd"){
                $folderAddSql = mysql_query("SELECT id, name FROM folders WHERE id='$feedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=".$folderAddData['id']."&reload=1');return false\"> ".$folderAddData['name']."/</a>";   
                }if($feedData['protocoll_type']=="folderAdd"){
                $folderAddSql = mysql_query("SELECT id, name FROM folders WHERE id='$feedData[feedLink2]'");
                $folderAddData = mysql_fetch_array($folderAddSql);
                $text = "<a href=\"#\" onclick=\"addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?folder=".$folderAddData[id]."&reload=1');return false\"> ".$folderAddData[name]."/</a>";   
                }
$commentClass = new comments();
$contextMenu = new contextMenu('feed', "$feedData[feedid]");
if($needStructure == "1"){ ?><div id="add"></div><? } ?>
    <div id="realFeed" class="feedNo<?=$feedData['feedid'];?>">
        <?=userSignature($feedData['owner'], $feedData['timestamp']);?>
        <div style="padding: 10px;">
            <?=nl2br(htmlspecialchars($feedData['feed']));?><?=$text;?>
        </div><br>
        <div style="padding: 15px;">
            <div>
                <?=showScore("feed", $feedData['feedid']);?>
                <div style=" width: 150px; float:right; margin-top: -24px;">
                    <?=$contextMenu->showItemSettings();?>
                </div>
            </div>
        </div>
        <a href="javascript:showfeedComment(<?=$feedData['feedid'];?>);" class="btn btn-mini" style="float: right; margin-top: -29px; margin-right: 5px; color: #606060">
            comments&nbsp;(<?=$commentClass->countComment("feed", $feedData[feedid]);?>)
        </a>
        <div class="shadow" id="feed<?=$feedData[feedid];?>" style="padding:15px; display:none;"></div>
    </div>
    <?php
                //feeds like the login feed are deleted after the validity passed
                if($feedData['validity'] == TRUE) {
                $time = time();
                if($feedData['validity'] < $time){
                    mysql_query("DELETE FROM userfeeds WHERE feedid='$feedData[feedid]'");
                }}
                unset($text);
    }
    ?>
    <div>...load more</div>
    <?php
    
    }
    
   function addFeed($owner, $feed, $type, $feedLink1, $feedLink2, $validity=NULL, $groups=NULL){
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
    
    function createFeed($author, $feed, $validity, $type, $privacy, $attachedItem=NULL, $attachedItemId=NULL){
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
    
    
   function deleteFeeds($type, $itemid){
       if(mysql_query("DELETE FROM feed WHERE attachedItem='$type' AND attachedItemId='$itemid'")){
           return true;
       }
   }
    
    function showFeedNew($type, $user=NULL, $limit=NULL, $feedId=NULL){
        
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
                
                $attachment = showItemThumb($feedData['attachedItem'], $feedData['attachedItemId']);
                break;
            }
            
            
            
           
                
        $commentClass = new comments();
        $contextMenu = new contextMenu('feed', $feedData['id']);
        if($needStructure == "1"){ ?><div id="add"></div><? } ?>
        <div id="realFeed" class="feedNo<?=$feedData['id'];?>">
            <?=userSignature($feedData['author'], $feedData['timestamp']);?>
            <div style="padding: 10px;"><?=nl2br(universe::universeText(htmlspecialchars($feedData['feed'])));?><?=$text;?></div><br>
            <div style="padding: 15px;">
                <?=$attachment;?>
            </div>
            <div style="padding: 15px;">
                <div>
                    <?=showScore("feed", $feedData['id']);?>
                    <div style="float:right; position: absolute; margin-top: -24px; margin-left: 108px;">
                        <?=$contextMenu->showItemSettings();?>
                    </div>
                </div>
            </div>
            <a href="javascript:showfeedComment(<?=$feedData['id'];?>);" class="btn btn-mini" style="float: right; margin-top: -38px; margin-right: 15px; color: #606060"><i class="icon-comment"></i>&nbsp;(<?=$commentClass->countComment("feed", $feedData[id]);?>)</a>
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
    
    