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


var im = new function(){
    this.lastMessageReceived = 1;
    this.openChatWindows = []; //array(userid1, userid2, userid3 etc...)
    
    //opens message either as a notification, as a chat window or updates the chatwindow
    this.openMessage = function(messageData){
        
    };
    this.openDialogue = function(parameter){
        if(is_numeric(parameter)){
            var username = usernameToUserid(parameter);
        }else{
            var username = parameter;
        }
        openChatDialoge(username);
    };
    
    //proceeds data from reload function
    this.sync = function(data){
        $.each(data, function(key,value){
            im.openMessage(value);
        });
    };
};

        
