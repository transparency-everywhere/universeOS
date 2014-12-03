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
         //fügt Benachrichtigung für den Author des Feeds hinzu, falls ein anderer User einen Kommentar erstellt
         $feedSql = mysql_query("SELECT owner FROM userfeeds WHERE feedid='$itemid'");
         $feedData = mysql_fetch_array($feedSql);
         if($_SESSION['userid'] !== $feedData['owner']){
         	$personalEvents->create($feedData['owner'],$_SESSION['userid'],'comment','feed',$itemId);
		 }
	   }
	   else if($type == "profile"){
	   	
         	$personalEvents->create($itemid,$_SESSION['userid'],'comment','profile',$itemId);
       }
   }

   
    function deleteComments($type, $itemid){
           if(mysql_query("DELETE FROM `comments` WHERE `type`='$type' AND `typeid`='$itemid'")){
               return true;
           }
    }
   
    function countComment($type, $itemid){
        $result = mysql_query("SELECT * FROM comments WHERE type='$type' && typeid='$itemid' ORDER BY timestamp");
        $num_rows = mysql_num_rows($result);
        return $num_rows;
    }
    
function showComments($type, $itemid) {
    if(proofLogin()){?>
    <div id="<?=$type;?>Comment_<?=$itemid;?>">    
     <script>
     $('#addComment').submit(function() {
     return false;
     });
    </script>
    <div class="shadow commentRow">
      <center>
      <form action="showComment.php" method="post" id="addComment" target="submitter">
          <table>
              <tr>
                  <td><?=showUserPicture(getUser(), "25");?></td>
                  <td><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%; height:17px;"></td>
                  <td><input type="submit" value="send" class="btn btn-small" name="submitComment" style="margin-left:13px;"></td>
              </tr>
                <input type="hidden" name="itemid" value="<?=$itemid;?>">
                <input type="hidden" name="user" value="<?=$_SESSION['userid'];?>">
                <input type="hidden" name="type" value="<?=$type;?>">
          </table>
      </form>
      </center>
    </div>

    <?php
    }
    if($type == "comment"){
        $comment_sql = mysql_query("SELECT * FROM comments WHERE type='$type' && typeid='$itemid' ORDER BY timestamp DESC");
        while($comment_data = mysql_fetch_array($comment_sql)) {
            $contextMenu = new contextMenu('comment', $comment_data['id']);
            $item = new item('comment', $comment_data['id']);
        ?>
    
    
            <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="<?=$type;?>Comment" style="background-color: #FFF;">
                <?=userSignature($comment_data['author'], $comment_data['timestamp']);?>
                <br><?=$comment_data['text'];?><br><br>

                <div style="padding: 15px; ">
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
     $commentClass = new comments();
        
    $comment_sql = mysql_query("SELECT * FROM comments WHERE type='$type' && typeid='$itemid' ORDER BY timestamp DESC");
    while($comment_data = mysql_fetch_array($comment_sql)) {
    $jsId = $comment_data['id'];
    
    
    ?>
    <div class="shadow subComment commentBox<?=$comment_data['id'];?>" id="<?=$type;?>Comment">
    <?=userSignature($comment_data['author'], $comment_data['timestamp']);
    $contextMenu = new contextMenu('comment', $comment_data['id']);
    $item = new item('comment', $comment_data['id']);
    ?>
    <?=$contextMenu->showItemSettings();?>
    <?=$comment_data['text'];?>
        <div style="padding: 15px; margin-bottom: 20px;">
            <div>
                <div style="float:left;">
                <?=$item->showScore();?>
                </div>
            </div>
            <a href="javascript:showSubComment(<?=$jsId;?>);" class="btn btn-mini" style="float: right; margin-right: 30px; color: #606060;">
                <i class="glyphicon glyphicon-comment"></i>
            </a>
        </div>
        <div class="shadow subComment" id="comment<?=$jsId;?>" style="display: none;"></div>
    </div>
<?php
}}
echo"</div>";
}

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
                      <td width="10"></td>
                      <td><?=showUserPicture($_SESSION['userid'], "25");?></td>
                      <td style="vertical-align:middle;"><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%;"></td>
                      <td><input type="submit" value="send" class="btn btn-small" name="submitComment" style=""></td>
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

    $comment_sql = mysql_query("SELECT * FROM comments WHERE type='feed' && typeid='$feedid' ORDER BY timestamp DESC");
    while($comment_data = mysql_fetch_array($comment_sql)) {
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


    