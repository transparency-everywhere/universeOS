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


var groups = new function(){
    
        this.updateGUI = function(groupId){
            dashBoard.updateDashBox('group');
            
            //check if profile is opened
            $('.groupProfile').each(function(){
                if($(this).attr('data-groupid') == groupId){
                    $(this).replaceWith(groups.generateProfile(groupId));
                }
            });
            
            //check if settings are opened
                //overview
                if($('#settingsGroups').length > 0){
                    settings.showGroupsForm();
                }
                //admin
                $('#groupAdminFormContainer').each(function(){
                    if($(this).attr('data-groupid') == groupId){
                        
                        settings.showGroupAdminForm(groupId);
                    }
                });
        };
	
        //removes current user from group
        this.leave = function(groupId){
                api.query('api/groups/leave/', { group_id : groupId });
                this.updateGUI(groupId);
                gui.alert('', 'You left the group');
        };
        
        this.show = function(groupId){
            this.showProfifile(groupId);
            return false;
        };
       
        
        this.getUsers = function(group_id){
            
            return api.query('api/groups/getUsers/', {'group_id':group_id});
            
        };
        
        this.generateMemberList = function(group_id){
            var groupData = this.getData(group_id);
            var usersInGroup = this.getUsers(group_id);
            var listItems = [];
            var buttons = '';
            if(usersInGroup)
                $.each(usersInGroup, function(index, value){
                    if(groupData.isAdmin)
                        buttons = "<a href='#' class='button' onclick='groups.removeUser(\""+group_id+"\",\""+value+"\"); return false' style='position: absolute;right: 10px;margin-top: -45px;'>Remove User</a>";
                    listItems.push({
                        'text':"<div onclick='User.showProfile("+value+")'><span class='icon blue-user' style='display:inline-block;height: 25px;width: 25px;margin-bottom: -6px;margin-left: 3px;'></span><span class='username' style='font-size:18px; padding-top: 5px; display:inline-block;height: 25px;margin-top: 5px;'>"+useridToUsername(value)+"</span></div>",
                        'buttons':buttons});
                });
                return gui.generateGrayList(listItems);
        };
        
        this.generateProfile = function(group_id){
            var groupdata = this.getData(group_id);
            var buttons='',buttonText = '', buttonClass = '', buttonAction = '', adminButton = '', onClick = '';
            if(User.inGroup(group_id) !== -1){
                buttonText = 'Leave Group';
                buttonClass = 'button';
                onClick = "groups.leave('"+group_id+"'); $(this).text('group left');";
            }else{
                
                if(groupdata.public == '0'){
                    
                    //show message "this group is private?"
                    
                }else{
                    
                    buttonText = 'Join Group';
                    buttonClass = 'button';
                    onClick = "groups.join('"+group_id+"'); $(this).text('request sent');";
                    
                }
                
            }
            
            if(groupdata.isAdmin)
                adminButton = '<a href="#" class="button" onclick="settings.showGroupAdminForm('+group_id+');">Admin</a>';
            
            
            if(!empty(buttonText))
                buttons = '<a href="#" onclick="'+onClick+'" class="button">'+buttonText+'</a>'+adminButton;

            var output   = '<div class="profile groupProfile_'+group_id+'" data-groupid="'+group_id+'">';
                    output += '<header>';
                            output += groups.showPicture(group_id);
                            output += '<div class="main">';

                                output += '<span class="userName">';
                                    output += groupdata.title;
                                output += '</span>';
                                output += '<span class="description">';
                                    output += groupdata.description;
                                output += '</span>';
                            output += '</div>';

                            output += '<div class="buttons">';
                            output += buttons;
                            output += '</div>';

                    output  += '</header>';
                    //output  += '<div class="">';
                        output += '<div class="profileNavLeft leftNav dark">';
                            output += '<ul>';
                                output += '<li data-type="activity"><span class="icon blue-heart"></span><span class="icon white-heart white"></span>Favorites</li>';
                                output += '<li data-type="files"><span class="icon blue-file"></span><span class="icon white-file white"></span>Files</li>';
                                output += '<li data-type="playlists"><span class="icon blue-playlist"></span><span class="icon white-playlist white"></span>Playlists</li>';
//                                output += '<li class="openChat"><img src="gfx/chat_icon.svg"/>Open Chat</li>';
                            output += '</ul>';
                            output += '</div>';
                        output += '<div class="profileMain">';
                            output += '<ul class="profileMainNav">';
                                output += '<li class="active" data-type="activity">Activity</li>';

                                output += '<li data-type="info">Info</li>';
                                output += '<li data-type="members">Members</li>';
                            output += '</ul>';
                            output += '<div class="content">';
                                
                                output += '<div class="profile_tab files_tab">'+filesystem.showFileBrowser(groupdata['homeFolder'])+'</div>';
                                output += '<div class="profile_tab playlists_tab">';
                                    var groupPlaylists = playlists.getGroupPlaylists(group_id);
                                    $.each(groupPlaylists['ids'], function(index, id){
                                        
                                    });
                                output += '</div>';


                                output += '<div class="profile_tab activity_tab" style="display:block"></div>';
                                output += '<div class="profile_tab members_tab">'+groups.generateMemberList(group_id)+'</div>';



                            output += '</div>';
                        //output += '</div>';
                    output  += '</div>';
                output  += '</div>';
                return output;
        };
        this.showProfile = function(group_id){
            var output = this.generateProfile(group_id);
            reader.show();
            reader.tabs.addTab('Profile', 'html', output);
            
            //load feed
            var profileFeed = new Feed('group', '.groupProfile_'+group_id+' .activity_tab', group_id);

            $('.profileMainNav li, .profileNavLeft li').click(function(){
                var type = $(this).attr('data-type');
                $(this).parent().parent().parent().find('.profileMainNav li').removeClass('active');
                $(this).parent().parent().parent().find('.profileNavLeft li').removeClass('active');
                $(this).addClass('active');
                $(this).parent().parent().parent().find('.content .profile_tab').hide();
                $(this).parent().parent().parent().find('.content .'+type+'_tab').show();
            });

        };
        
        this.create = function(title, type, description, invitedUsers, callback){
            api.query('api/groups/create/', { title : title, type: type, description: description, invitedUsers: JSON.stringify(invitedUsers) }, callback);
        };
	this.get = function(userid){
            if(typeof userid === 'undefined'){
                var requestData = {}
            }else{
                requestData = {userid:userid};
            }
				    var result = api.query('api/groups/get/', requestData);
				    
            if(result != null){
		return result;
            }
	};
        this.getData = function(groupId){
            
            return api.query('api/groups/getData/', { group_id : groupId });
            
        };
        this.getPublicGroups = function(){
            var groupId = 0;
            return api.query('api/groups/getPublicGroups/', { group_id : groupId });
            
        };
        this.getGroupArray = function(userid){
            
            var groupIds = this.get(userid);

            var groupArray = [];
            if(groupIds === undefined){
                return groupArray;
            } else {
                $.each(groupIds, function(key, value){
                    var data = groups.getData(value);
                    groupArray.push({type:'group', itemId: data['id'], title:data['title'], timestamp: ''});
                });
                return groupArray;
            }
        };
        this.getPublicGroupArray = function(userid){
            
            var groupIds = this.getPublicGroups();

            var groupArray = [];
            if(groupIds === undefined){
                return groupArray;
            } else {
                $.each(groupIds, function(key, value){
                    var data = groups.getData(value);
                    groupArray.push({type:'group', itemId: data['id'], title:data['title'], timestamp: ''});
                });
                return groupArray;
            }
        };
        this.deleteUserFromGroup = function(groupId, userId){
            
            
            callback = function(){
              gui.alert('The user has been removed'); 
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              }
            };
            api.query('api/groups/removeUser/', { group_id : groupId, user_id: userId },callback);
            
        };
	this.getTitle = function(groupId){
			  		
				    var result = api.query('api.php?action=getGroupTitle', {groupId : groupId});
				    
				   	if(result){
				   		return result;
				   	}
			  		
			  	};
        this.update = function(groupId, title, description, type, membersInvite){
            
            callback = function(){
              gui.alert('The group has been updated');
            };
            api.query('api/groups/update/', { group_id : groupId, title: title, description: description, type: type, members_invite: membersInvite },callback);
        };
        this.makeUserAdmin = function(groupId, userId){
            callback = function(){
              gui.alert('The admin has been added');
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              }
            };
            api.query('api/groups/makeUserAdmin/', { group_id : groupId, user_id: userId },callback);
        };
        this.removeFromAdmins = function(groupId, userId){
            callback = function(){
              gui.alert('The admin has been removed');
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              }
              
            };
            api.query('api/groups/removeFromAdmins/', { group_id : groupId, user_id: userId },callback);
            
        };
        this.removeUser = function(groupId, userId){
            alert('not implemented yet!');
        };
        this.getUsers = function(groupId){
            
            return api.query('api/groups/getUsers/', { group_id : groupId });
        
        };
        this.showPicture = function(group_id){
            var pic = api.query('api/groups/getPicture/',{group_id: group_id});
            return "<div class=\"userPicture\" style=\"background: url('"+pic+"'); border-color: red; width: 20px;height:  20px;background-size: 100%;border-radius:10px\"><\/div>";

        };
        
        
        
        this.verifyGroupRemoval = function(groupId){
            
            var confirmParameters = {};
            confirmParameters['title'] = 'Delete Group';
            confirmParameters['text'] = 'Are you sure to delete this grouü?';
            confirmParameters['submitButtonTitle'] = 'Delete';
            confirmParameters['submitFunction'] = function(){
                var callback = function(){
                    $('.blueModal').hide();
                    gui.alert('The group has been removed');
                };
                api.query('api/groups/delete/', { group_id : groupId }, callback);
            };
            confirmParameters['cancelButtonTitle'] = 'Cancel';
            confirmParameters['cancelFunction'] = function(){
                //alert('cancel');
            };

            gui.confirm(confirmParameters);
        };
        this.generateMemberOptionsButton = function(group_id, user_id, admins){
            var output = '';
            output += "<div class='btn-group'>";
                output += "<button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>";
                    output += "Action <span class='caret'></span>";
                output += '</button>';
             
                output += "<ul class='dropdown-menu' role='menu'>";
                    if($.inArray(''+user_id, admins) === -1){
                      output += "<li><a href='#' onclick='groups.makeUserAdmin("+group_id+","+user_id+");'>Make Admin</a></li>";
                    }else{
                      output += "<li><a href='#' onclick='groups.removeFromAdmins("+group_id+","+user_id+");'>Remove from Admins</a></li>";
                    }
                    
                    
                  output += "<li class='divider'></li>";
                    output += "<li><a href='#' onclick='groups.deleteUserFromGroup("+group_id+","+user_id+");'>Remove from group</a></li>";
                output += '</ul>';
            output += '</div>';
            
            
            return output;
        };
        this.showUpdateGroupForm = function(group_id){
            var formModal = new gui.modal();
            var groupData = groups.getData(group_id);
            var admins = groupData['admin'];
            
            var admins = admins.split(';');

            var fieldArray = [];
            var options = [];
            options['headline'] = '';
            options['buttonTitle'] = 'Save';
            options['noButtons'] = true;

            var field0 = [];
            field0['caption'] = 'Title';
            field0['inputName'] = 'title';
            field0['type'] = 'text';
            field0['value'] = groupData['title'];
            fieldArray[0] = field0;

            var captions = ['Public', 'Private'];
            var type_ids = ['1', '0'];

            var field1 = [];
            field1['caption'] = 'Type';
            field1['inputName'] = 'type';
            field1['values'] = type_ids;
            field1['captions'] = captions;
            field1['type'] = 'dropdown';
            field1['preselected'] = groupData['public'];
            fieldArray[1] = field1;
            
            var checked = '';
            if(groupData['membersInvite'] === '1'){
                checked = 'checked';
            }
            
            var field2 = [];
            field2['caption'] = '';
            field2['value'] = "<input type='checkBox' name='membersInvite' id='membersInvite' value='1' "+checked+"> Allow members to invite users";
            field2['type'] = 'html';
            fieldArray[2] = field2;

            var field3 = [];
            field3['caption'] = 'Description';
            field3['inputName'] = 'description';
            field3['type'] = 'text';
            field3['value'] = groupData['description'];
            fieldArray[3] = field3;
            
            var buddies = groups.getUsers(group_id);
            var html = '<ul>';
            $.each(buddies,function(index, value){
                html += "<li>"+useridToUsername(value)+" "+groups.generateMemberOptionsButton(group_id, value, admins)+"</li>";
            });
            html += '<ul>';
            
            var field4 = [];
            field4['caption'] = 'Users';
            field4['type'] = 'html';
            field4['value'] = html;
            fieldArray[4] = field4;
            
            var field5 = [];
            field5['caption'] = 'Delete Group';
            field5['inputName'] = 'deleteGroup';
            field5['value'] = 'Delete Group';
            field5['type'] = 'button';
            field5['actionFunction'] = 'groups.verifyGroupRemoval(\''+group_id+'\')';
            fieldArray[5] = field5;
            
            


            var modalOptions = {};
            modalOptions['buttonTitle'] = 'Update Group';

            modalOptions['action'] = function(){
                var callback = function(){
                    gui.alert('', 'The group has been updated');
                    $('.blueModal').remove();
                };
                
                
                //needs to be done
                
                
                
                var invitedUsers;
                groups.update(group_id, $('.blueModal #title').val(), $('.blueModal #description').val(), $('.blueModal #type').val(),  $('.blueModal #membersInvite').is(':checked'));
            };
            formModal.init('Update Group', '<div id="updateGroupFormContainter"></div>', modalOptions);
            gui.createForm('#updateGroupFormContainter',fieldArray, options);
            $('.dropdown-toggle').dropdown();
        };
        
        
        this.showCreateGroupForm = function(){
            var formModal = new gui.modal();

            var fieldArray = [];
            var options = [];
            options['headline'] = '';
            options['buttonTitle'] = 'Save';
            options['noButtons'] = true;

            var field0 = [];
            field0['caption'] = 'Title';
            field0['inputName'] = 'title';
            field0['type'] = 'text';
            fieldArray[0] = field0;

            var captions = ['Public', 'Private'];
            var type_ids = ['1', '0'];

            var field1 = [];
            field1['caption'] = 'Type';
            field1['inputName'] = 'type';
            field1['values'] = type_ids;
            field1['captions'] = captions;
            field1['type'] = 'dropdown';
            fieldArray[1] = field1;

            var field2 = [];
            field2['caption'] = 'Description';
            field2['inputName'] = 'description';
            field2['type'] = 'text';
            fieldArray[2] = field2;

            
            var buddies = buddylist.getBuddies();
            var html = '';
            if(buddies.length === 0){
                html += 'You have no user in your buddylist';
            }
            html += '<ul>';
            $.each(buddies,function(index, value){
                html += "<li><input type='checkbox' class='invitedBuddy' value='"+value+"'> "+useridToUsername(value)+"</li>";
            });
            html += '<ul>';
            var field3 = [];
            field3['caption'] = 'Invite Users';
            field3['type'] = 'html';
            field3['value'] = html;
            fieldArray[3] = field3;



            var modalOptions = {};
            modalOptions['buttonTitle'] = 'Create Group';

            modalOptions['action'] = function(){
                
                var callback = function(data){
                        gui.alert( 'The group has been created');
                        $('.blueModal').remove();
                        groups.updateGUI();
                };
                var invitedUsers = [];
                $('.blueModal .invitedBuddy').each(function(){
                   invitedUsers.push($(this).val()); 
                });
                
                
                
                groups.create($('.blueModal #title').val(), $('.blueModal #type').val(), $('.blueModal #description').val(), invitedUsers, callback);
                
            };
            formModal.init('Create Group', '<div id="createGroupFormContainer"></div>', modalOptions);
            gui.createForm('#createGroupFormContainer',fieldArray, options);
        };
        
        //join public group or accept invitation
        this.join = function(group_id){
            var callback = function(){
                $('#profileWrap.group_'+group_id+' #joinButton .btn').hide();
                $('#favTab_Group').load('doit.php?action=showUserGroups');
                updateDashbox('group');
            };
            api.query('api/groups/join/', { group_id : group_id}, callback);
        };
        
        //decline invitation
        this.declineRequest = function(group_id){
            alert('needs to be written');
        };
	
        
        
};

        
