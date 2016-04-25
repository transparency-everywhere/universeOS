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
    this.lastMessageReceived;
    this.openChatWindows = []; //array(userid1, userid2, userid3 etc...)
    
    this.generateDialogue = function(userid){
        var html = '        <div class="chatMainFrame">';
        html += '          <header>';
        html += '               <!-- toggle description key box -->';
//        html += '              <span><a href="#" id="toggleKey_'+userid+'" class="toggleKeySettings"><i class="icon icon-lock"></i></a></span>';
        html += '              <span><a href="#" onclick="showProfile('+userid+'); return false;">'+useridToUsername(userid)+'</a></span>';
        html += '          </header>';
        html += '          <!-- box for caht encription key -->';
        html += '          <div id="chatKeySettings_'+userid+'" class="chatKeySettings"></div>';
        html += '          <div class="attachmentMark"></div>';
        html += '          <div id="test_<?=$buddyName;?>" class="dialoge">';
        html += '               <div class="messageFrame chatMainFrame_'+userid+'">';
        html += '                   <div onclick="chatLoadMore(\''+userid+'\', \'1\');" class="loadMore">...load more</div>';
        html += '               </div>';
        html += '          </div>';
        html += '      </div>';
        html += '      <div class="chatAdditionalSettings" onclick="$(this).hide(); return true;">';
        html += '          <ul>';
        html += '              <li><a class="smiley emoticon-smile" data-code=":)"></a><a class="smiley emoticon-tongue" data-code=":p"></a><a class="smiley emoticon-wink" data-code=";)"></a><a class="smiley emoticon-surprised" data-code=":o"></a></li>';
        html += '              <li><a class="smiley emoticon-laugh" data-code=":D"></a><a class="smiley emoticon-cute" data-code=":3"></a><a class="smiley emoticon-sad" data-code=":("></a><a class="smiley emoticon-cry" data-code=":\'("></a></li>';
        html += "              <li><a href=\"#\" onclick=\"filesystem.attachItem($('.chatMainFrame_"+userid+"').parent().parent().find('.attachmentMark'));\" style=\"color:#FFF\">Attach File</a></li>";
        html += '          </ul>';
        html += '      </div>';
        html += '      <footer>';
        html += '          <center style="margin-top: 6px;">';
        html += '              <form action="#" method="post" autocomplete="off">';
        html += '                    <a class="btn pull-right" onclick="$(\'.chatAdditionalSettings\').toggle();">';
        html += '                        <i class="icon icon-paperclip"></i>';
        html += '                    </a>';
        html += '                    <input type="text" placeholder="type a message..." name="message" class="input border-radius chatInput pull-right" style="">';
        html += '                    <input type="hidden" name="cryption" value="false" id="chatCryptionMarker_'+userid+'">';
        html += '              </form>';
        html += '          </center>';
        html += '      </footer>';
        return html;
    };
    this.openDialogue = function(parameter) {
        if(!is_numeric(parameter)){
            var userid= usernameToUserid(parameter);
        }else{
            var userid = parameter;
        }
        
        var username = useridToUsername(userid);
        
        applications.show('chat');
      
      	//check if dialoge allready exists
        if($(".chatFrame_"+ userid).length == 0){

            var chatFrameHTML = chat.generateLeftFrame();
            var mainPage = im.generateDialogue(userid)
            chatFrameHTML += '<div class="chatRightFrame chatFrame_'+userid+'">'+mainPage+'</div>';
            
            userid = usernameToUserid(username);
            chat.tabs.addTab(username, '',chatFrameHTML);
        }else{
            //if dialoge doesnt exists => bring dialoge to front..
            var dialogue_tab_id = chat.tabs.getTabByTitle(username);

            im.loadDialogueIntoFrame(userid, 0, 500);
            chat.tabs.showTab(dialogue_tab_id);
        }
        
        
        this.loadDialogueIntoFrame(userid, 0, 500);
        
//        $('.chatFrame_'+userid).click(function(){
//            $(this).parent().find('.chatKeySettings').toggle();
//        });
        
        //openDialogueInterval = window.setInterval("chatDecrypt("+userid+")", 500);
        $('.chatAdditionalSettings a').unbind('click');
        $('.chatAdditionalSettings a').bind('click', function(){
            var code = $(this).attr('data-code');
            var $chatInput = $(this).parent().parent().parent().parent().find('.chatInput');
            $chatInput.val($chatInput.val()+code);
        });
        //init chat submit
        $(".chatFrame_"+ userid+' footer form').unbind('submit');
        $(".chatFrame_"+ userid+' footer form').bind('submit', function(e){
            e.preventDefault();
            var $chatInput = $(this).find('.chatInput');
            var message = $chatInput.val();
            var $itemThumb = $('.chatMainFrame_'+userid).parent().parent().find('.itemThumb');
            $itemThumb.each(function(){
                // data-itemtype="folder" data-itemid="3"
                var itemType = $(this).attr('data-itemtype');
                var itemId = $(this).attr('data-itemid');
                message += "[itemThumb type="+itemType+" typeId="+itemId+"]";
                $(this).remove();
            });
            console.log(message);
            im.submitMessage(message, userid, function(res){
                //update to prevent chat sync within reload function
                im.lastMessageReceived = res;
                $('#chatInput_'+userid).val(message);

                im.appendToDialogue(userid, [{
                        crypt: "0",
                        id: res,
                        protocoll: "",
                        read: "0",
                        receiver: userid,
                        seen: "0",
                        sender: getUser(),
                        text: $chatInput.val(),
                        timestamp: Date.now()
                }]);
                im.scrollToBottom(userid);
                $chatInput.val('').focus();
            });
        });
        
    };
    
    this.getMessages = function(userB, offset, limit){
        
        if(typeof userB === 'object'){
            var requests = [];
            $.each(userB,function(index, value){
                //you can also enter a single type instead of multiple values
                //$userA, $userB, $offset, $limit
                requests.push({user_b: value, offset:offset, limit:limit});
            });
                return api.query('api/IM/selectMessages/', { request: requests});
        }else
            return api.query('api/IM/selectMessages/',{request: [{user_b: userB, offset:offset, limit:limit}]})[0];
    };
    
    
    this.parseDialogue = function(messages){
        var output = '';
        $.each(messages, function(index, value){
            if(value['sender'] == getUser()){
                        var messageClass = 'incoming';
            }else{
                        var messageClass = 'outgoing';
            }
            
            
            var html = '';
            var messageClass = '';
            html += '<div class="box-shadow chatMessage '+messageClass+'" data-id="'+value['id']+'">';
                html += '<span class="username">';
                
                html += User.showPicture(value['sender']);
                
                html += useridToUsername(value['sender']);
                html += "</span>";
                html += '<span class="chatMessage decrypted">'+value['text']+'</span>';
            html += '</div>';
            
            output = html+output;
        });
        return output;
        
    };
    this.submitMessage = function(message, receiver_id, callback){
        
        var publicKeyReceiver = getPublicKey('user', receiver_id); //get public key of receiver
        //@sec:
    	var publicKeySender = getPublicKey('user', User.userid); //get public key of receiver
    	
    	var randKey = sec.rand();
   	
    	var message = sec.symEncrypt(randKey, message); //encrypt message semitrically

    	var symKeyReceiver = sec.asymEncrypt(publicKeyReceiver, randKey); //asym encrypt random generated key for symetric encryption
   	var symKeySender =  sec.asymEncrypt(publicKeySender, randKey); //asym encryptrandom generated key for symetric encryption
        
        
    	var message = symKeyReceiver+';;;'+symKeySender+'////message////'+message; //message = symetric Key + sym encoded message with key = symKey
    	
    	api.query('api/IM/addMessage/', {receiver_id:receiver_id, message:message},callback);
    };
    this.decryptMessage = function(content, userid){
        console.log('start...');
            if(empty(content))
                return 'empty';
            //split content into key and message
            var message = content.split("////message////");
            var messageKeys = message[0].split(';;;');
            
            if(content.indexOf("////message////") === -1){
                return content;
            }


            var privateKey = cypher.getPrivateKey('user', getUser());

                //the message contains two keys which are splitted into the var messageKeys above
                //if the first key(messageKeys[0]) doesn't work -> try to encrypt it with the other one(messageKeys[0])

                //encrypt random key with privateKey
                var randKey = sec.asymDecrypt(privateKey, messageKeys[0]);
               
                if(randKey === null)
                    var randKey = sec.asymDecrypt(privateKey, messageKeys[1]);


        if(randKey !== null){
            //encrypt message with random key
            var content = universeText(htmlentities(sec.symDecrypt(randKey, message[1])));
        }else{
            content = universeText('The key is not stored anymore');
        }

        console.log('...fin');
        return content;
    };
    this.loadDialogueIntoFrame = function(userid, offset, limit){
        var messages = this.getMessages(userid,offset, limit);
        html += '<a href="#" class="loadMore">load more</a>';
        var html = this.parseDialogue(messages);
        
        
        $('.chatMainFrame_'+userid).html(html);
        
            delay(function(){
                im.initDecryption(userid);
            },700);
    };
    this.initDecryption = function(userid){
        $('.chatMainFrame_'+userid+' .chatMessage.decrypted').each(function(){
            var text = $(this).html();
            $(this).html(im.decryptMessage(text, userid));
            $(this).removeClass('decrypted');
        });
    };
    //opens message either as a notification, as a chat window or updates the chatwindow
    this.openMessage = function(messageData){
        if(messageData.receiver == User.userid)
            this.openDialogue(messageData.sender);
        else if(messageData.sender == User.userid)
            this.openDialogue(messageData.receiver);
    };
    this.appendToDialogue = function(userid, messageData){
        
        var html = this.parseDialogue(messageData);
        $('.chatMainFrame_'+userid).append(html);
        this.initDecryption(userid);
    };
    this.prependToDialogue = function(userid, messageData){
        var html = this.parseDialogue(messageData);
        $('.chatMainFrame_'+userid+' .loadMore').remove();
        $('.chatMainFrame_'+userid).prepend(html);
        this.initDecryption(userid);
    };
    this.scrollToBottom = function(userid){
        $('.chatMainFrame_'+userid).parent().scrollTop($('.chatMainFrame_'+userid).parent()[0].scrollHeight);
        //$('.chatMainFrame_'+userid).animate({ scrollTop: $('.chatMainFrame_'+userid).attr("scrollHeight") - $('.chatMainFrame_'+userid).height() }, 500);
    };
    //proceeds data from reload function
    this.sync = function(data){
        $.each(data, function(key,value){
            if(parseInt(value.id) > parseInt(im.lastMessageReceived) || typeof im.lastMessageReceived === 'undefined'){
                im.lastMessageReceived = parseInt(value.id);
                session.updateSessionInfo('lastMessageReceived',parseInt(value.id));
            }
            
            im.openMessage(value);
        });
    };
};
