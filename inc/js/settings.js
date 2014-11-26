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
        

var settings = new function(){
    this.init = function(){
        
        this.applicationVar = new application('settings');
	this.applicationVar.create('Settings', 'url', 'modules/settings/index.php',{width: 2, height:  5, top: 0, left: 0});
	
    };
    this.show = function(){
        settings.applicationVar.show();
    };
    
			    this.submitPassword = function () {
			    	if($('#newPassword').val() === $('#newPasswordRepeat').val() && $('#newPassword').val().length > 0){ 
			    		updatePassword($('#oldPassword').val(), $('#newPassword').val(), localStorage.currentUser_userid);
			    		$('.changePassword').slideUp();
			    	}else if($('#newPassword').val().length === 0){
			    		jsAlert('', 'The password is to short');
			    	}else{
			    		jsAlert('The passwords dont match');
			    	}
			    };
    
};
