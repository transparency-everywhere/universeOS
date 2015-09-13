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


var chat = new function(){
    this.tabs;
    this.init = function(){
        this.applicationVar = new application('chat');
        this.applicationVar.create('Chat', 'html', '<div id="chatFrame"></div>',{width: 4, height:  7, top: 3, left: 3, hidden:true});
        
        
	this.tabs = new tabs('#chatFrame');
        this.tabs.init();
	this.tabs.addTab('Home', '',gui.loadPage('modules/chat/chat.php'));
    };
    this.show = function(){
        this.applicationVar.show();
    };
    //type - user or group
    this.getMessages = function(type, itemId, limit, offset){
        
    };
    this.generateLeftFrame = function(){
        var html;
        html = '<div class="chatLeftFrame">';
            html += '<header>';
            html += '</header>';
        
        html += '</div>';
        return html;
    };
    this.openDialogue = function(userid){
        var username = useridToUsername(userid);
        
        applications.show('chat');
      
        var mainPage = gui.loadPage("modules/chat/chatreload.php?buddy="+username+"");
      
      
      	//check if dialoge allready exists
        if($(".chatFrame_"+ userid).length == 0){

        var chatFrameHTML = chat.generateLeftFrame();
            chatFrameHTML += '<div class="chatRightFrame chatFrame_'+userid+'">'+mainPage+'</div>';
            
            userid = usernameToUserid(username);
            chat.tabs.addTab(username, '',chatFrameHTML);
            openDialogueInterval = window.setInterval("chatDecrypt("+userid+")", 500);
            
        }else{
            //if dialoge doesnt exists => bring dialoge to front..
            var dialogue_tab_id = chat.tabs.getTabByTitle(username);
            
            $('.chatRightFrame.chatFrame_'+userid).html(mainPage);
            chat.tabs.showTab(dialogue_tab_id);


        }
        $('.chatAdditionalSettings a').unbind('click');
        $('.chatAdditionalSettings a').bind('click', function(){
            var code = $(this).attr('data-code');
            var $chatInput = $(this).parent().parent().parent().parent().find('.chatInput');
            $chatInput.val($chatInput.val()+code);
        });
    };
};

//IM CHAT  
//IM CHAT  
//IM CHAT - needs to be put in own var
function chatMessageSubmit(userid){
    	
    	var publicKeyReceiver = getPublicKey('user', userid); //get public key of receiver
        //@sec:
    	var publicKeySender = getPublicKey('user', User.userid); //get public key of receiver
    	
    	var randKey = Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2); //generate random key
   	
    	var message = sec.symEncrypt(randKey, $('#chatInput_'+userid).val()); //encrypt message semitrically

    	var symKeyReceiver = sec.asymEncrypt(publicKeyReceiver, randKey); //asym encrypt random generated key for symetric encryption
   	var symKeySender =  sec.asymEncrypt(publicKeySender, randKey); //asym encryptrandom generated key for symetric encryption
        
        
    	var message = symKeyReceiver+';;;'+symKeySender+'////message////'+message; //message = symetric Key + sym encoded message with key = symKey

    	$('#chatInput_'+userid).val(message);
    	
    	
    	if(localStorage.key[userid]){
    		 $('#chatInput_'+userid).val(CryptoJS.AES.encrypt($('#chatInput_'+userid).val(), localStorage.key[userid]));
 		     //set cryption marker true so the php script could mark the message as crypted
    		 $('#chatCryptionMarker_'+userid).val('true'); 
    		 
    	}else{
    		 $('#chatCryptionMarker_'+userid).val('false'); 
    	}
    	
    	var message = $('#chatInput_'+userid).val();
    	$.post("api.php?action=chatSendMessage", {
           userid:localStorage.currentUser_userid,
           receiver: userid,
           message: message
           }, 
           function(result){
                var res = result;
                if(res.length !== 0){
                    
                    storeMessageKey(res, randKey);
                    
           			$('#chatInput_'+userid).val(message);
           			var buddyName = useridToUsername(userid);
           			
           			$('#test_'+buddyName).load('modules/chat/chatreload.php?buddy='+buddyName+'&initter=1');
           			$('#chatInput_'+userid).val('').focus();
                    
                }else{
                    alert('There was an error sending the message.');
                }
           }, "html");
 } 

function chatDecrypt(userid){
    	
    	
    $('.chatMessage_'+userid).each(function(){
        
        

            //clear intervall which calls this function
            if($('.chatMessage_'+userid).length !== 0){

                    window.clearInterval(openDialogueInterval);

            }

            var content = $(this).html();
            var id = $(this).data('id');



            if(localStorage.key[userid]){
                    content = CryptoJS.AES.decrypt(content, localStorage.key[userid]);
                    content = content.toString(CryptoJS.enc.Utf8);
                    $(this).removeClass('.cryptedChatMessage_'+userid);
            }


            //split content into key and message
            var message = content.split("////message////");
            var messageKeys = message[0].split(';;;');

            //check if randKey is stored, if not get randKey from message, using the asym privateKey
            if(isStored(id)){
                    randKey = getStoredKey(id);
            }else{


                var privateKey = cypher.getPrivateKey('user', localStorage.currentUser_userid);

                //the message contains two keys which are splitted into the var messageKeys above
                //if the first key(messageKeys[0]) doesn't work -> try to encrypt it with the other one(messageKeys[0])

                //encrypt random key with privateKey
                var randKey = sec.asymDecrypt(privateKey, messageKeys[0]);
               
                if(randKey === null)
                    var randKey = sec.asymDecrypt(privateKey, messageKeys[1]);


            }


    if(randKey !== null){
        //encrypt message with random key
        var content = htmlentities(sec.symDecrypt(randKey, message[1]));

            }else{
                    content = 'The key is not stored anymore';
            }

            
            $(this).html(universeText(content));
            $(this).removeClass('chatMessage_'+userid);
    });
    return true;
}
  
function chatLoadMore(username, limit){
     $.get("doit.php?action=chatLoadMore&buddy="+username+"&limit="+limit,function(data){
              $('.chatMainFrame_'+username).append(data);
      },'html');
 }