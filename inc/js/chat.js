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
        this.applicationVar.create('Chat', 'html', '<div id="chatFrame"></div>',{width: 2, height:  2, top: 0, left: 5});
        
        
	this.tabs = new tabs('#chatFrame');
        this.tabs.init();
	this.tabs.addTab('Home', '',gui.loadPage('modules/chat/chat.php'));
    };
};

//IM CHAT  
//IM CHAT  
//IM CHAT - needs to be put in own var
function chatMessageSubmit(userid){
    	
    	var publicKey = getPublicKey('user', userid); //get public key of receiver
    	
    	var randKey = Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2); //generate random key
   	
    	var message = sec.symEncrypt(randKey, $('#chatInput_'+userid).val()); //encrypt message semitrically

    	var symKey = sec.asymEncrypt(publicKey, randKey); //random generated key for symetric encryption
   	
    	var message = symKey+'////message////'+message; //message = symetric Key + sym encoded message with key = symKey

    	$('#chatInput_'+userid).val(message);
    	
    	
    	if(localStorage.key[userid]){
    		console.log(localStorage.key[userid]);
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
           			$('#chatInput_'+userid).val('');
                    
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

            //check if randKey is stored, if not get randKey from message, using the asym privateKey
            if(isStored(id)){
                    randKey = getStoredKey(id);
            }else{


            var privateKey = cypher.getPrivateKey('user', localStorage.currentUser_userid);


            //encrypt random key with privateKey
            var randKey = sec.asymDecrypt(privateKey, message[0]);


            }


    if(randKey !== null){
        //encrypt message with random key
                    console.log('sym');
        var content = htmlentities(sec.symDecrypt(randKey, message[1]));

            }else{
                    content = 'The key is not stored anymore';
            }


            $(this).html(content);
            $(this).removeClass('chatMessage_'+userid);
    });
    return true;
}

function openChatDialoge(username){
      chat.applicationVar.show();
      
      	//check if dialoge allready exists
          if($("#test_"+ username +"").length == 0){
          	
          	userid = usernameToUserid(username);
                chat.tabs.addTab(username, '',gui.loadPage("modules/chat/chatreload.php?buddy="+username+""));
              
              openDialogueInterval = window.setInterval("chatDecrypt(userid)", 500);
          }else{
          	//if dialoge doesnt exists => bring dialoge to front..
          	
          	

          }
 }
 
  
function chatLoadMore(username, limit){
     $.get("doit.php?action=chatLoadMore&buddy="+username+"&limit="+limit,function(data){
              $('.chatMainFrame_'+username).append(data);
      },'html');
 }