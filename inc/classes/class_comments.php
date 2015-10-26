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
class comments {
	
function addComment($type, $itemid, $author, $message){
     $type = sanitizeText($type);
     $message = sanitizeText($message);
     $itemid = sanitizeText($itemid);
     
     $time = time();
     $db = new db();
     $values['type'] = $type;
     $values['typeid'] = $itemid;
     $values['author'] = $author;
     $values['timestamp'] = time();
     $values['text'] = $message;
     $values['privacy'] = 'p';
     $commentId = $db->insert('comments', $values);
     
	$personalEvents = new personalEvents();
     	if($type == "feed"){
                $db = new db();
                //fügt Benachrichtigung für den Author des Feeds hinzu, falls ein anderer User einen Kommentar erstellt
                
                $feedData = $db->select('feed', array('id', $itemid));
                if(getUser() !== $feedData['author']){
                    $personalEvents->create($feedData['author'],getUser(),'comment','feed',$itemid);
		}
	}
	else if($type == "profile"){
         	$personalEvents->create($itemid,getUser(),'comment','profile',$itemid);
        }
   }

function delete($commentId){
    $commentData = $this->getData($comment_id);
    if(authorize($commentData['privacy'], "show", $commentData['author'])){
        $db = new db();
        $db->delete('comments', array('id', $comment_id));
    }
    
}
   
function deleteComments($type, $itemid){
        $db = new db();
        
           if($db->delete('comments', array('type', $type, '&&', 'typeid', $itemid))){
               return true;
           }
    }
   
function countComment($type, $itemid){
//        $result = mysql_query("SELECT `typeid` FROM `comments` WHERE type='".mysql_real_escape_string($type)."' && typeid='".mysql_real_escape_string($itemid)."'");
//        $num_rows = mysql_num_rows($result);
        $db = new db();
        $data = $db->query("SELECT COUNT(`typeid`) AS number FROM `comments` WHERE type='".mysql_real_escape_string($type)."' && typeid='".mysql_real_escape_string($itemid)."'");
        return $data['number'];
    }
    
public function loadComments($type, $item_id){
        $db = new db();
        return $db->shiftResult($db->select('comments', array('type', $type, '&&', 'typeid', $item_id)),"typeid");
    }
    //old
function showComments($type, $itemid) {
    if(proofLogin()){?>
        <div id="<?=$type;?>Comment_<?=$itemid;?>">
            <div class="shadow commentRow">
              <center>
                  <form action="showComment.php" method="post" id="addComment" target="submitter">
                      <table>
                          <tr>
                              <td style="padding: 0 10px;"><?=showUserPicture($_SESSION['userid'], "30");?></td>
                              <td style="vertical-align:middle;"><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%!important;"></td>
                              <td style="padding: 0 10px;"><input type="submit" value="send" class="button" name="submitComment" style="width:auto;"></td>
                              <td width="10"></td>

                          </tr><input type="hidden" name="itemid" value="<?=$itemid;?>"><input type="hidden" name="user" value="<?=$_SESSION['userid'];?>"><input type="hidden" name="type" value="feed">
                      </table>
                  </form>
              </center>
            </div>
    <?php
    }
    
    
    if($type == "comment"){
        $comments = $this->get($type, $itemid);
        foreach($comments AS $comment_data) {
            $personalEvents->clean('comment', $comment_data['type'], $comment_data['id']);

            $contextMenu = new contextMenu('comment', $comment_data['id']);
            $item = new item('comment', $comment_data['id']);
        ?>
            <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="<?=$type;?>Comment" style="background-color: #FFF;">
                <?=userSignature($comment_data['author'], $comment_data['timestamp']);?>
                <div style="padding: 8px; "><?=$comment_data['text'];?></div>

                <div style="padding: 8px; ">
                    <div>
                        <span style="float:left;">
                        <?=$item->showScore();?>
                        </span>
                        <span style="float:left;">
                        <?=$contextMenu->showItemSettings();?>
                        </span>
                    </div>
                </div>
            </div>
            <?php
            }

    }else{
        $comments = $this->get($type, $itemid);
        foreach($comments AS $comment_data) {
            $jsId = $comment_data['id'];
           ?>
           <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="<?=$type;?>Comment">
               <?=userSignature($comment_data['author'], $comment_data['timestamp']);
               $contextMenu = new contextMenu('comment', $comment_data['id']);
               $item = new item('comment', $comment_data['id']);
               ?>
               <div style="padding: 15px; ">
                   <?=$comment_data['text'];?>
               </div>
               <div style="padding: 15px; margin-bottom: 20px;">
                   <div>
                       <div style="float:left;">
                           <?=$item->showScore();?>
                       </div>
                   </div>
                   <a href="javascript:showSubComment(<?=$jsId;?>);" class="btn btn-mini" style="float: right; margin-right: 30px; color: #606060;">
                       <i class="icon icon-comment"></i>
                   </a>
                   <?=$contextMenu->showItemSettings();?>
               </div>
           <div class="shadow subComment" id="comment<?=$jsId;?>" style="display: none;"></div>
       </div>
       <?php
       }
    
    }
    echo"</div>";
}

public function getData($comment_id){
    $db = new db();
    return $db->select('comments', array('id', $comment_id));
}

function get($type, $itemId){
    $db = new db();
    return $db->shiftResult($db->query("SELECT * FROM comments WHERE type='$type' && typeid='$itemId' ORDER BY timestamp DESC"), 'type');;
}

//@del
function showFeedComments($feedid){ 
    ?>
    <div id="feedComment_<?=$feedid;?>">
    <div class="shadow comments">
    <?php
    if(proofLogin()){
        ?>
    <div class="shadow commentRow" id="feedfeed" style="margin:5px;">
      <center> 
          <form action="showComment.php" method="post" id="addComment" target="submitter">
              <table>
                  <tr>
                      <td style="padding: 0 10px;"><?=showUserPicture($_SESSION['userid'], "30");?></td>
                      <td style="vertical-align:middle;"><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%!important;"></td>
                      <td style="padding: 0 10px;"><input type="submit" value="send" class="button" name="submitComment" style="width:auto;"></td>
                      <td width="10"></td>

                  </tr><input type="hidden" name="itemid" value="<?=$feedid;?>"><input type="hidden" name="user" value="<?=$_SESSION['userid'];?>"><input type="hidden" name="type" value="feed">
              </table>
          </form>
      </center>
    </div>
        <?php }else{
            echo"Please log in to write a comment";
        } ?>
        


    <?php
    $comments = $this->get('feed', $feedid);
    foreach($comments AS $comment_data){
        $contextMenu = new contextMenu('comment', $comment_data['id']);
        $item = new item('comment', $comment_data['id']);
        ?>
        <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="feedComment">
        <?=userSignature($comment_data['author'], $comment_data['timestamp']);?>
            <div style="margin: 7px;">
                <br>
                <?=$comment_data['text'];?><br><br>
                <div style="padding: 15px;">
                    <div style="margin-bottom:15px;">
                        <span style="float:left;margin-right:15px;">
                        <?php
                        $item->showScore();
                        ?>
                        </span>
                        <span style="float:left;"><?=$contextMenu->showItemSettings();?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    echo"</div>";
    echo"</div>";
    }
}


    