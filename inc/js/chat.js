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


//also see im.js

var chat = new function(){
    this.tabs;
    this.init = function(){
        this.applicationVar = new application('chat');
        this.applicationVar.create('Chat', 'html', '<div id="chatFrame"></div>',{width: 4, height:  7, top: 3, left: 3, hidden:true});
        
        
	this.tabs = new tabs('#chatFrame');
        this.tabs.init();
        var html = '<center>';
            html += '       <span class="icon blue-comment" style="height: 90px;width: 90px; margin-top:5px;margin-right: -17px;"></span>';
            html += '                <h2 style="margin-top:0;">Chat</h2>';
            html += '                <h3>Click on a user in your buddylist to open a dialogue</h3>';
            html += '            </center>'
	this.tabs.addTab('Home', '',html);
    };
    this.show = function(){
        this.applicationVar.show();
    };
    this.generateLeftFrame = function(){
        var html;
        html = '<div class="chatLeftFrame">';
            html += '<header>';
            html += '</header>';
        
        html += '</div>';
        return html;
    };
};
  
function chatLoadMore(username, limit){
     $.get("doit.php?action=chatLoadMore&buddy="+username+"&limit="+limit,function(data){
              $('.chatMainFrame_'+username).append(data);
      },'html');
 }