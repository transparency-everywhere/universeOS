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
                    output +=       '<li data-function="settings.showUpdateProfileForm();"><span class="icon blue-gear"></span><span class="icon white-gear white"></span>General</li>';
                    output +=       '<li data-function="settings.showUpdatePrivacyForm();"><span class="icon blue-eye"></span><span class="icon white-eye white"></span>Privacy</li>';
                    output +=       '<li data-function="settings.showUpdateBuddylistForm();"><span class="icon blue-user"></span><span class="icon white-user white"></span>Buddylist</li>';
                    output +=       '<li data-function="settings.showGroupsForm();"><span class="icon blue-group"></span><span class="icon white-group white"></span>Groups</li>';
                    output +=       '<li data-function="settings.showSecurityOverview();"><span class="icon blue-lock"></span><span class="icon white-lock white"></span>Security</li>';
                    output +=   '</ul>';
                    output += '</div>';
                    output += "<div class='frameRight' style='top:0px;' id='settingsFrame'>";
                    output += '</div>';
                output += '</div>';
                
                
                
        this.applicationVar = new application('settings');
	this.applicationVar.create('Settings', 'html', output,{width: 6, height:  5, top: 0, left: 3});
	this.showUpdateProfileForm();
        
        $('.leftNav li').click(function(){
            var functionName = $(this).attr('data-function');
            $(this).parent().children('li').removeClass('active');
            $(this).addClass('active');
            eval(functionName);
        });
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
        api.query('api/user/updatePrivacy/', {values: values}, function(){
            gui.alert('Your privacy has been updated', 'Settings');
        });
    };
    
    
    this.showUpdatePrivacyForm = function(){
        settings.show();
        var captions = this.getPrivacyCaptions();
        var types = this.getPrivacyTypes();
        
        var output = '<div id="privacySettings"><h2 style="margin-top:0">Privacy</h2>';
            
            
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
        settings.show();
        var settings_buddylist = buddylist.getBuddies();
        var output = '<div id="settingsBuddylist"><h2 style="margin-top:0">Buddylist</h2>';
        if(settings_buddylist.length === 0){
            
            output += '<h3>You don\'t have anyone on your Buddylist so far.</h3>';
            output += '<h3>Use the search to look for people you know</h3>';
            
        }else{
            var listItems = [];
            
            $.each(settings_buddylist, function(index, value){
               var text = User.showPicture(value)+'<h3 style="margin-top: 20px">'+useridToUsername(value)+'</h3><span style="font-size:14px;">'+User.getRealName(value)+'</span>'+item.showItemSettings('user', value);
               listItems.push({'text':text, 'buttons':'<button class="button" onclick="buddylist.removeBuddy(\''+value+'\');">Unfriend</button>'});
            });
            output += gui.generateGrayList(listItems);
        }
        output += '</div>';
        $('#settingsFrame').html(output);
        
    };
    this.showSecurityOverview = function(){
        settings.show();
        var output = '<div id="settingsSecurity"><h2 style="margin-top:0">Change Password</h2>';
        output += '<a href="#" class="button" id="changePasswordButton">Change Password</a>';
        output += '</div>';
        
        $('#settingsFrame').html(output);
        
        $('#changePasswordButton').click(function(){
           settings.showUpdatePasswordForm(); 
        });
        
    };
    this.showUpdatePasswordForm = function(){
         settings.show();
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
        field1['caption'] = 'Old Password';
        field1['inputName'] = 'old_password';
        field1['type'] = 'password';
        field1['caption_position'] = 'left';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'New Password';
        field2['inputName'] = 'new_password';
        field2['type'] = 'password';
        field2['caption_position'] = 'left';
        fieldArray[2] = field2;
        
        var field3 = [];
        field3['caption'] = 'Repeat New Password';
        field3['inputName'] = 'password';
        field3['type'] = 'text';
        field3['caption_position'] = 'left';
        fieldArray[3] = field3;
        
        $('#settingsFrame').html('<div id="updatePasswordContainer"></div>');
        
       // formModal.init('Update Profile', '<div id="updateProfileFormContainer"></div>', modalOptions);
        gui.createForm('#updatePasswordContainer',fieldArray, options);
        
        $('#updateProfileFormContainer .dynForm').submit(function(e){
            e.preventDefault();
            settings.updateProfileInfo($('#updateProfileFormContainer #realname').val(), $('#updateProfileFormContainer #city').val(), $('#updateProfileFormContainer #hometown').val(), birthdate, $('#updateProfileFormContainer #school').val(), $('#updateProfileFormContainer #university').val(), $('#updateProfileFormContainer #work').val());
            
        });
        
        
        
    };
    this.showUpdateProfileForm = function(){
        settings.show();
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
        fieldArray[6] = field6;
        
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
        
        
        //init datepicker in modal
        $('#updateProfileFormContainer #birthdate').datepicker();
        
        $('#updateProfileFormContainer .dynForm').submit(function(e){
            e.preventDefault();
            var birthdate = $('#updateProfileFormContainer #birthdate').val();
            settings.updateProfileInfo($('#updateProfileFormContainer #realname').val(), $('#updateProfileFormContainer #city').val(), $('#updateProfileFormContainer #hometown').val(), birthdate, $('#updateProfileFormContainer #school').val(), $('#updateProfileFormContainer #university').val(), $('#updateProfileFormContainer #work').val());
            gui.alert('Your changes have been saved.','Settings');
        });
        
        //load userpicture
        $('#userpicture_area').html(User.showPicture(User.userid));
        
        $('#changePicture').click(function(){
            settings.showUpdatePictureForm();
        });
    };
    this.showUpdatePictureForm = function(){
        settings.show();
        var output = '<div>';
        output += '<style>';
        
        output += '#settingsFrame h3{display:block!important;clear:left!important;} #settingsFrame .userPicture{border-radius:0!important; width:120px!important;height:120px!important;} #settingsFrame #buttons{margin-top:50px;} #settingsFrame .userPicture{float: none;}';
        output += '</style>';
        output += '<form target="submitter" onsubmit="alert(\'sdrft\');" action="api/user/uploadUserPicture/" id="userForm" enctype="multipart/form-data" method="post">';
        
        output += '<h2>Current userpicture</h2>';
        output += User.showPicture(User.userid);
        output += '<h2 style="padding-top:50px">Upload new userpicture</h2>';
        output += '<input name="userpicture" type="file">';
        output += '</form>';
        output += '<div id="buttons">';
        output += '<a href="#" class="antiButton pull-left" id="cancelButton">Cancel</a>';
        output += '<a href="#" class="button pull-right" id="changeButton">Change Picture</a>';
        output += '</div></div>';
        $('#settingsFrame').html(output);
      
        $('#settingsFrame #cancelButton').click(function(){
            settings.showUpdateProfileForm();
        });
      
        $('#settingsFrame #changeButton').click(function(){
            $('#settings #userForm').submit();
        });
    };
    this.showUpdateGroupPictureForm = function(group_id){
        settings.show();
        var output = '<div>';
        output += '<style>';
        
        output += '#settingsFrame h3{display:block!important;clear:left!important;} #settingsFrame .userPicture{border-radius:0!important; width:120px!important;height:120px!important;} #settingsFrame #buttons{margin-top:50px;} #settingsFrame .userPicture{float: none;}';
        output += '</style>';
        output += '<form target="submitter" action="api/groups/uploadGroupPicture/" id="userForm" enctype="multipart/form-data" method="post">';
        
        output += '<h2>Current group picture</h2>';
        output += groups.showPicture(group_id);
        output += '<h2 style="padding-top:50px">Upload new userpicture</h2>';
        output += '<input name="groupPicture" type="file">';
        output += '<input name="group" type="hidden" value="'+group_id+'">';
        output += '</form>';
        output += '<div id="buttons">';
        output += '<a href="#" class="antiButton pull-left" id="cancelButton">Cancel</a>';
        output += '<a href="#" class="button pull-right" id="changeButton">Change Picture</a>';
        output += '</div></div>';
        $('#settingsFrame').html(output);
      
        $('#settingsFrame #cancelButton').click(function(){
            settings.showGroupAdminForm(group_id);
        });
      
        $('#settingsFrame #changeButton').click(function(){
            $('#settings #userForm').submit();
        });
    };
    this.showGroupsForm = function(){
        settings.show();
        var output = '<div id="settingsGroups"><h2 style="margin-top:0">Groups</h2>';
        
            var profile_groups = groups.get(User.userid);
        if(typeof profile_groups === 'undefined'){
            
            output += '<h3>You are not in any Group so far.</h3>';
            output += '<h3>Search for Groups or create a new one:</h3>';
            output += '<a href="#" class="button" onclick="groups.showCreateGroupForm();">Create Group</a>';
            
        }else{
            var listItems = [];
            
            if(typeof profile_groups !== 'undefined'){
                
                $.each(profile_groups, function(index, value){
                    var groupdata = groups.getData(value);
                    var adminButton = '';
                    if(groupdata.isAdmin){
                        adminButton = '<a href="#" class="button" onclick="settings.showGroupAdminForm('+value+'); return false">Admin</a>';
                    }
                    listItems.push({'text':'<div onclick="groups.showProfile('+value+')"><span class="icon icon-group"></span><span class="username" style="font-size:18px; padding-top: 5px;">'+groups.getTitle(value)+'</span>', 'buttons':'<a href="#" class="button" onclick="groups.leave(\''+value+'\'); return false">Leave</a>'+adminButton});
                });
            }
            output += gui.generateGrayList(listItems);
        }
            output += '</div>';
        $('#settingsFrame').html(output);
    };
    this.showGroupAdminForm = function(group_id){
        settings.show();
        var groupData = groups.getData(group_id);
        
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
        
        //@old
        //overwrite old value, can be removed in future
        if(groupData['public'] === 'public'){
            groupData['public'] = '1';
        }else if(groupData['public'] === 'private'){
            groupData['public'] = '0';
        }
        
        var captions = ['Public', 'Private'];
        var type_ids = ['1', '0'];

        var field3= [];
        field3['caption'] = 'Type';
        field3['inputName'] = 'type';
        field3['values'] = type_ids;
        field3['captions'] = captions;
        field3['type'] = 'dropdown';
        field3['preselected'] = groupData['public'];
        fieldArray[3] = field3;
        
        var field4 = [];
        field4['caption'] = 'Admin';
        field4['inputName'] = 'admin';
        field4['type'] = 'html';
        field4['value'] = useridToUsername(groupData['admin']);
        field4['caption_position'] = 'left';
        fieldArray[4] = field4;
        
        var field5 = [];
        field5['caption'] = 'Are members allowed to invite other users?';
        field5['inputName'] = 'membersInvite';
        field5['type'] = 'checkbox';
        if(groupData['membersInvite'] == '1'){
            field5['checked'] = true;
        }
        field5['caption_position'] = 'top';
        fieldArray[5] = field5;
        
        //members list
        var field6 = [];
        field6['caption'] = 'Members:';
        field6['inputName'] = 'list';
        field6['type'] = 'html';
        field6['value'] = groups.generateMemberList(group_id);
        field6['caption_position'] = 'top';
        fieldArray[6] = field6;
        
        var field7 = [];
        field7['caption'] = '';
        field7['inputName'] = 'buttons';
        field7['type'] = 'html';
        field7['value'] = '<div id=\'buttons\'><input type=\'submit\' value=\'save\' class=\'button pull-right\'></div>';
        fieldArray[7] = field7;
        
        options['action'] = function(){
                var callback = function(){
                    gui.alert('The group has been updated');
                    $('.blueModal').remove();
                };
                
                
                //needs to be done
                
                
                
                var invitedUsers;
                groups.update(group_id, $('#groupAdminFormContainer #title').val(), $('#groupAdminFormContainer #description').val(), $('#groupAdminFormContainer #type').val(),  $('#groupAdminFormContainer #membersInvite').is(':checked'));
        };
        
        $('#settingsFrame').html('<div id="groupAdminFormContainer" data-groupid="'+group_id+'"></div>');
        
       // formModal.init('Update Profile', '<div id="updateProfileFormContainer"></div>', modalOptions);
        gui.createForm('#groupAdminFormContainer',fieldArray, options);
        
        
        //load userpicture
        $('#userpicture_area').html(groups.showPicture(group_id));
        
        $('#changePicture').click(function(){
            settings.showUpdateGroupPictureForm(group_id);
        });
    };
    this.submitPassword = function () {
            if($('#newPassword').val() === $('#newPasswordRepeat').val() && $('#newPassword').val().length > 0){ 
                    updatePassword($('#oldPassword').val(), $('#newPassword').val(), localStorage.currentUser_userid);
                    $('.changePassword').slideUp();
            }else if($('#newPassword').val().length === 0){
                    gui.alert('The password is to short', 'Security', 'error');
            }else{
                    gui.alert('The passwords don\'t match', 'Security', 'error');
            }
    };

};
