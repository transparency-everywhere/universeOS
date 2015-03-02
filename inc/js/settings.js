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
        
        var     output = '<div>';
                    output += "<div class='leftNav dark' style='top: 0; background: #37474f;'>";
                    output +=    '<ul>';
                    output +=       '<li onclick="settings.showUpdateProfileForm();">General</li>';
                    output +=       '<li onclick="settings.showUpdatePrivacyForm();">Privacy</li>';
                    output +=       '<li onclick="settings.showUpdateBuddylistForm();">Buddylist</li>';
                    output +=       '<li onclick="settings.showGroupsForm();">Groups</li>';
                    output +=       '<!-- <li><a href="">Security</a></li> -->';
                    output +=       '<!-- <li><a href="">Services</a></li> -->';
                    output +=   '</ul>';
                    output += '</div>';
                    output += "<div class='frameRight' style='top:0px;' id='settingsFrame'>";
                    output += '</div>';
                output += '</div>';
                
                
                
        this.applicationVar = new application('settings');
	this.applicationVar.create('Settings', 'html', output,{width: 2, height:  5, top: 0, left: 0});
	this.showUpdateProfileForm()
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
    this.updatePrivacy = function(values){
        api.query('api/user/updatePrivacy/', {values: values});
    };
    
    
    this.showUpdatePrivacyForm = function(){
        
        var captions = this.getPrivacyCaptions();
        var types = this.getPrivacyTypes();
        
        var output = '<div id="privacySettings">';
            
            
            $.each(types, function(index, value){
                output += '<h3>'+captions[index]+'</h3>';
                output += '<ul id="'+value+'">';
                    output += '<li data-value="p"><div><span class="icon white-check"></span>Everyone</div></li>';
                    output += '<li data-value="f"><div><span class="icon white-check"></span>Friends</div></li>';
                    output += '<li data-value="h"><div><span class="icon white-check"></span>Only Me</div></li>';
                output += '</ul>';
            });
            
            output += '<a href="#" class="antiButton pull-left">Cancel</a>';
            output += '<a href="#" class="button pull-right" id="savePrivacy">Save</a>';
            
            output += '</div>';
            
            
        $('#settingsFrame').html(output);
        this.applyPrivacyToForm(User.getPrivacy());
        
        $('#savePrivacy').click(function(){
            settings.updatePrivacy(settings.getPrivacyFormValues());
        });
        
        $('#privacySettings ul li').click(function(){
            $(this).parent().children().removeClass('active');
            $(this).addClass('active');
        });
        
    };
    this.getPrivacyCaptions = function(){
        
        var types = [];
        types[0] = 'Who can send you messages';
        types[1] = 'Who can see your profile information';
        types[2] = 'Who can see your realname';
        types[3] = 'Who can see your buddies';
        types[4] = 'Who can see in which groups you are';
        types[5] = 'Who can see the activity in your profile';
        types[6] = 'Who can see the favorites in your profile';
        types[7] = 'Who can see the files in your profile';
        types[8] = 'Who can see the playlists in your profile';
        
        return types;
    }
    this.getPrivacyTypes = function(){
        var types = [];
        types[0] = 'receive_messages';
        types[1] = 'info';
        types[2] = 'profile_realname';
        types[3] = 'buddylist';
        types[4] = 'groups';
        types[5] = 'profile_activity';
        types[6] = 'profile_fav';
        types[7] = 'profile_files';
        types[8] = 'profile_playlists';
        return types;
    };
    this.getPrivacyFormValues = function(){
        
        var types = this.getPrivacyTypes();
        var values = {};
        $.each(types, function(index, value){
            values[value] = $('#settingsFrame #'+value+' li.active').attr('data-value');
        });
        return values;
    };
    this.applyPrivacyToForm = function(values){
        
        
        var types = this.getPrivacyTypes();
        var privacyValues = values;
        $.each(types, function(index, value){
            $('#settingsFrame #'+value+' li').each(function(){
               if($(this).attr('data-value') == privacyValues[value]){
                   $(this).addClass('active');
               }
            });
        });
    };
    
    this.showUpdateBuddylistForm = function(){
        var settings_buddylist = buddylist.getBuddies();
        var output = '<div id="settingsBuddylist"><h2 style="margin-top:0">Buddylist</h2>';
        output += '<ul class="grayList">';
        $.each(settings_buddylist, function(index, value){
           output += '<li><div>'; 
           output += User.showPicture(value);
           output += useridToUsername(value);
           
           output += '</div><button class="button" onclick="buddylist.removeBuddy(\''+value+'\');">Unfriend</button></li>'; 
        });
        output += '</ul></div>';
        
        $('#settingsFrame').html(output);
        
    };
    this.showUpdateProfileForm = function(){
        
        var user_info = User.getProfileInfo(User.userid);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = 'Update Profile';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        //Realname, City, Hometown, Birthdate, School, University, Work
        
        
        var field0 = [];
        field0['caption'] = 'Picture';
        field0['type'] = 'html';
        field0['value'] = "<div id='userpicture_area'></div><a href='#' class='button' id='changePicture'>Change Picture</a>";
        field0['caption_position'] = 'left';
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Realname';
        field1['inputName'] = 'realname';
        field1['type'] = 'text';
        field1['value'] = user_info['realname'];
        field1['caption_position'] = 'left';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'City';
        field2['inputName'] = 'city';
        field2['type'] = 'text';
        field2['value'] = user_info['place'];
        field2['caption_position'] = 'left';
        fieldArray[2] = field2;
        
        var field3 = [];
        field3['caption'] = 'Hometown';
        field3['inputName'] = 'hometown';
        field3['type'] = 'text';
        field3['value'] = user_info['home'];
        field3['caption_position'] = 'left';
        fieldArray[3] = field3;
        
        var field4 = [];
        field4['caption'] = 'Birthdate';
        field4['inputName'] = 'birthdate';
        field4['type'] = 'text';
        field4['value'] = user_info['birthdate'];
        field4['caption_position'] = 'left';
        fieldArray[4] = field4;
        
        var field5 = [];
        field5['caption'] = 'School';
        field5['inputName'] = 'school';
        field5['type'] = 'text';
        field5['value'] = user_info['school1'];
        field5['caption_position'] = 'left';
        fieldArray[5] = field5;
        
        var field6 = [];
        field6['caption'] = 'University';
        field6['inputName'] = 'university';
        field6['type'] = 'text';
        field6['value'] = user_info['university1'];
        field6['caption_position'] = 'left';
        fieldArray[6] = field5;
        
        var field7 = [];
        field7['caption'] = 'Work';
        field7['inputName'] = 'work';
        field7['type'] = 'text';
        field7['value'] = user_info['employer'];
        field7['caption_position'] = 'left';
        fieldArray[7] = field7;
        
        var field8 = [];
        field8['caption'] = '';
        field8['inputName'] = 'buttons';
        field8['type'] = 'html';
        field8['value'] = '<div id=\'buttons\'><input type=\'submit\' value=\'save\' class=\'button pull-right\'></div>';
        fieldArray[8] = field8;
        
        $('#settingsFrame').html('<div id="updateProfileFormContainer"></div>');
        
       // formModal.init('Update Profile', '<div id="updateProfileFormContainer"></div>', modalOptions);
        gui.createForm('#updateProfileFormContainer',fieldArray, options);
        
        $('#updateProfileFormContainer .dynForm').submit(function(e){
            e.preventDefault();
            var birthdate = '09.07.1991';
            settings.updateProfileInfo($('#updateProfileFormContainer #realname').val(), $('#updateProfileFormContainer #city').val(), $('#updateProfileFormContainer #hometown').val(), birthdate, $('#updateProfileFormContainer #school').val(), $('#updateProfileFormContainer #university').val(), $('#updateProfileFormContainer #work').val());
            
        });
        
        //load userpicture
        $('#userpicture_area').html(User.showPicture(User.userid));
    };
    this.showUpdatePictureForm = function(){
        var output = '';
        output += '<h2>Update your userpicture</h2>';
        output += '<h3>Current userpicture</h3>';
        output += User.showPicture(User.userid);
        output += '<h3>Upload new userpicture</h3>';
        output += '';
        output += '<a href="#" class="antiButton pull-left">Cancel</a>';
        output += '<a href="#" class="button pull-right">Change Picture</a>';
        $('#settingsFrame').html(output);
    };
    this.showGroupsForm = function(){
        var output = '<div id="settingsBuddylist">';
        
        
            output += '<ul class="grayList">';
            var profile_groups = groups.get(User.userid);
            if(typeof profile_groups !== 'undefined'){
                $.each(profile_groups, function(index, value){
                    output += '<li onclick="groups.show('+value+')"><div><span class="icon icon-group"></span>';
                    output += '<span class="username" style="font-size:18px; padding-top: 5px;">'+groups.getTitle(value)+'</span>';
                    output += '</div><button class="button" onclick="groups.leave(\''+value+'\');">Leave</button></li>';
                });
            }
            output += '</ul></div>';
        $('#settingsFrame').html(output);
    };
    this.showGroupAdminForm = function(group_id){
        var user_info = User.getProfileInfo(User.userid);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = 'Update Group';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        
        
        
        //grouppicture
        var field0 = [];
        field0['caption'] = 'Picture';
        field0['type'] = 'html';
        field0['value'] = "<div id='userpicture_area'></div><a href='#' class='button' id='changePicture'>Change Picture</a>";
        field0['caption_position'] = 'left';
        fieldArray[0] = field0;
        
        var field1 = [];
        field1['caption'] = 'Title';
        field1['inputName'] = 'title';
        field1['type'] = 'text';
        field1['value'] = groupData['title'];
        field1['caption_position'] = 'left';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Description';
        field2['inputName'] = 'description';
        field2['type'] = 'text';
        field2['value'] = groupData['description'];
        field2['caption_position'] = 'left';
        fieldArray[2] = field2;
        
        //public
        var field3 = [];
        field3['caption'] = 'Public';
        field3['inputName'] = 'public';
        field3['type'] = 'text';
        field3['value'] = groupData['public'];
        field3['caption_position'] = 'left';
        fieldArray[3] = field3;
        
        var field4 = [];
        field4['caption'] = 'Admin';
        field4['inputName'] = 'admin';
        field4['type'] = 'text';
        field4['value'] = groupData['admin'];
        field4['caption_position'] = 'left';
        fieldArray[4] = field4;
        
        var field5 = [];
        field5['caption'] = 'Are members allowed to invite other users?';
        field5['inputName'] = 'membersInvite';
        field5['type'] = 'text';
        field5['value'] = groupData['membersInvite'];
        field5['caption_position'] = 'top';
        fieldArray[5] = field5;
        
        var field8 = [];
        field8['caption'] = '';
        field8['inputName'] = 'buttons';
        field8['type'] = 'html';
        field8['value'] = '<div id=\'buttons\'><input type=\'submit\' value=\'save\' class=\'button pull-right\'></div>';
        fieldArray[8] = field8;
        
        $('#settingsFrame').html('<div id="updateProfileFormContainer"></div>');
        
       // formModal.init('Update Profile', '<div id="updateProfileFormContainer"></div>', modalOptions);
        gui.createForm('#updateProfileFormContainer',fieldArray, options);
        
        $('#updateProfileFormContainer .dynForm').submit(function(e){
            e.preventDefault();
            var birthdate = '09.07.1991';
            settings.updateProfileInfo($('#updateProfileFormContainer #realname').val(), $('#updateProfileFormContainer #city').val(), $('#updateProfileFormContainer #hometown').val(), birthdate, $('#updateProfileFormContainer #school').val(), $('#updateProfileFormContainer #university').val(), $('#updateProfileFormContainer #work').val());
            
        });
        
        //load userpicture
        $('#userpicture_area').html(User.showPicture(User.userid));
        //members
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
