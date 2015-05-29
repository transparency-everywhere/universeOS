//        This file is published by transparency - everywhere with the best deeds.
//        Check transparency - everywhere.com for further information.
//        Licensed under the CC License, Version 4.0 (the "License");
//        you may not use this file except in compliance with the License.
//        You may obtain a copy of the License at
//        
//        https://creativecommons.org/licenses/by/4.0/legalcode
//        
//        Unless required by applicable law or agreed to in writing, software
//        distributed under the License is distributed on an "AS IS" BASIS,
//        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//        See the License for the specific language governing permissions and
//        limitations under the License.
//        @author nicZem for Tranpanrency - everywhere.com
        

var comments = new function(){
    this.create = function(type, itemid, comment, callback){
        api.query('api/item/comments/create/', {type: type, itemid: itemid, comment: comment},callback);
    };
    this.init = function(){
        
        $('.addComment').submit(function(e){
            e.preventDefault();
            var type = $(this).attr('data-type');
            var item_id = $(this).attr('data-item');
            comments.create(type, item_id, $(this).find('.commentField').val(),function(){
                comments.reload(type, item_id);
            });
        });
        
    };
    this.load = function(type, item_id){
        return api.query('api/item/comments/load/', {'type':type, 'item_id':item_id});
    };
    
    this.reload = function(type, item_id){
        $("#comment_" + type + "_"+item_id).html(comments.generate(type, item_id));
        comments.init();
    };
    
    
    
    this.generate = function(type, item_id){
        var loadedComments = comments.load(type,item_id);
        
        var html = '<div id="'+type+'Comment_'+item_id+'">';
        html += '<div class="shadow commentRow">';
            html += '<center>';
                html += '<form data-type="'+type+'" data-item="'+item_id+'" class="addComment" id="addComment" target="submitter">';
                    html += '<table>';
                        html += '<tr>';
                            html += '<td style="padding: 0 10px;">'+User.showPicture(User.userid)+'</td>';
                            html += '<td style="vertical-align:middle;"><input type="text" name="comment" placeholder="write commenta.." class="commentField" style="width: 100%!important;"></td>';
                            html += '<td style="padding: 0 10px;"><input type="submit" value="send" class="button" name="submitComment" style="width:auto;"></td>';
                            html += '<td width="10"></td>'

                        html += '</tr><input type="hidden" name="itemid" value="<?=$itemid;?>"><input type="hidden" name="user" value="'+User.userid+'"><input type="hidden" name="type" value="feed">';
                    html += '</table>';
                html += '</form>';
            html += '</center>';
        html += '</div>';
        
        
        
        $.each(loadedComments, function(index, commentData){
            
            html += '<div class="shadow subComment commentBox'+commentData['id']+'" id="'+commentData['type']+'Comment" style="background-color: #FFF;">';
                html += User.showSignature(commentData.author, commentData.timestamp);
                html += '<div style="padding: 8px; ">'+commentData['text']+'</div>';

                html += '<div style="padding: 8px; ">';
                    html += '<div>';
                        html += '<span style="float:left;">';
                        html + item.showScoreButton(type,item_id);
                        html += '</span>';
                        html += '<span style="float:left;">';
                        html + item.showItemSettings(type,item_id);
                        html += '</span>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';
            
        });
        html += '</div>';
        return html;
    };
    
  this.loadSubComments = function(commentId){
      
    $("#comment" + commentId + "").html(comments.generate('comment', commentId));
    $("#comment" + commentId + "").toggle("slow");
    comments.init();
  };
  this.loadComments = function(type, itemId){
    console.log("#comment_" + type + "_"+itemId);
    $("#comment_" + type + "_"+itemId).html(comments.generate(type, itemId));
    $("#comment_" + type + "_"+itemId).toggle("slow");
    comments.init();
  };
  this.loadFeedComments = function(feedId){
      
    $("#feed" + feedId + "").html('<div id="comment_feed_'+feedId+'">'+comments.generate('feed', feedId)+'</div>');
    $("#feed" + feedId + "").toggle("slow");
    comments.init();
  };
};
