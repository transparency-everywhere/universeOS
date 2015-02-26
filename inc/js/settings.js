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
        
        var     output =    '<ul>';
                output +=       '<li><a href="">General</a></li>';
                output +=       '<li><a href="">Privacy</a></li>';
                output +=       '<li><a href="">Buddylist</a></li>';
                output +=       '<!-- <li><a href="">Security</a></li> -->';
                output +=       '<!-- <li><a href="">Services</a></li> -->';
                output +=   '</ul>'
        this.applicationVar = new application('settings');
	this.applicationVar.create('Settings', 'url', 'modules/settings/index.php',{width: 2, height:  5, top: 0, left: 0});
	
    };
    this.show = function(){
        if(this.applicationVar)
            settings.applicationVar.show();
        else
            this.init();
    };
    
    this.updateProfileInfo = function(realname, city, hometown, birthdate, school, university, work){
        
        return api.query('api/user/updateProfileInfo/', { realname:realname,city:city,hometown:hometown, birthdate:birthdate, school:school, university:university, work:work});
    };
    
    this.showUpdatePrivacyForm = function(){
        
        //profile info
        
    };
    
    this.showUpdateBuddylistForm = function(){
        var settings_buddylist = buddylist.getBuddies();
        var output = '<h2 style="margin-top:0">Buddylist</h2>';
        output += '<ul>';
        $.each(settings_buddylist, function(index, value){
           output += '<li>'; 
           output += User.showPicture(value);
           output += useridToUsername(value);
           
           output += '<a href="#" onclick="deleteBuddy(\''+value+'\');">x</a>'; 
           output += '</li>'; 
        });
        output += '<ul>';
        
        $('#settingsFrame').html(output);
        
    }
    
    this.showUpdateProfileForm = function(){
        
        var user_info = User.getProfileInfo(User.userid);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = 'Update Profile';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        //Realname, City, Hometown, Birthdate, School, University, Work
        var field0 = [];
        field0['caption'] = 'Realname';
        field0['inputName'] = 'realname';
        field0['type'] = 'text';
        field0['value'] = user_info['realname'];
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'City';
        field1['inputName'] = 'city';
        field1['type'] = 'text';
        field1['value'] = user_info['place'];
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Hometown';
        field2['inputName'] = 'hometown';
        field2['type'] = 'text';
        field2['value'] = user_info['home'];
        fieldArray[2] = field2;
        
        var field3 = [];
        field3['caption'] = 'Birthdate';
        field3['inputName'] = 'birthdate';
        field3['type'] = 'text';
        field3['value'] = user_info['birthdate'];
        fieldArray[3] = field3;
        
        var field4 = [];
        field4['caption'] = 'School';
        field4['inputName'] = 'school';
        field4['type'] = 'text';
        field4['value'] = user_info['school1'];
        fieldArray[4] = field4;
        
        var field5 = [];
        field5['caption'] = 'University';
        field5['inputName'] = 'university';
        field5['type'] = 'text';
        field5['value'] = user_info['university1'];
        fieldArray[5] = field5;
        
        var field6 = [];
        field6['caption'] = 'Work';
        field6['inputName'] = 'work';
        field6['type'] = 'text';
        field6['value'] = user_info['employer'];
        fieldArray[6] = field6;
        
        var field7 = [];
        field7['caption'] = '';
        field7['inputName'] = 'buttons';
        field7['type'] = 'html';
        field7['value'] = '<div id=\'buttons\'><input type=\'submit\' value=\'save\' class=\'button pull-right\'></div>';
        fieldArray[7] = field7;
        
        $('#settingsFrame').html('<div id="updateProfileFormContainer"></div>');
        
       // formModal.init('Update Profile', '<div id="updateProfileFormContainer"></div>', modalOptions);
        gui.createForm('#updateProfileFormContainer',fieldArray, options);
        
        $('#updateProfileFormContainer .dynForm').submit(function(e){
            e.preventDefault();
            var birthdate = '09.07.1991';
            settings.updateProfileInfo($('#updateProfileFormContainer #realname').val(), $('#updateProfileFormContainer #city').val(), $('#updateProfileFormContainer #hometown').val(), birthdate, $('#updateProfileFormContainer #school').val(), $('#updateProfileFormContainer #university').val(), $('#updateProfileFormContainer #work').val());
            
        });
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
