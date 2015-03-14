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
	
        //removes current user from group
        this.leave = function(groupId){
                api.query('api/groups/leave/', { group_id : groupId });
                gui.alert('', 'You left the group');
        };
        
        this.show = function(groupId){
            
                  reader.applicationVar.show();
                    reader.tabs.addTab("" + groupId + "", '',gui.loadPage("./group.php?id=" + groupId + ""));
              
                  return false;
        };
       
        
        this.getUsers = function(group_id){
            
            return api.query('api/groups/getUsers/', {'group_id':group_id});
            
        };
        this.getPlaylists = function(group_id){
            
        };
        this.showProfile = function(group_id){
            var groupdata = this.getData(group_id);
            var buttonText = '', buttonClass = '', buttonAction = '', adminButton = '', onClick = '';
            if(User.inGroup(group_id) !== -1){
                buttonText = 'Leave Group';
                buttonClass = 'button';
                onClick = 'groups.leave('+group_id+')';
            }else{
                
                if(groupdata.public == '0'){
                    
                    //show message "this group is private?"
                    
                }else{
                    
                    buttonText = 'Join Group';
                    buttonClass = 'button';
                    onClick = 'groups.join('+group_id+')';
                    
                }
                
            }
            
            if(groupdata.isAdmin){
                adminButton = '<a href="#" class="button" onclick="settings.showGroupAdminForm('+group_id+');">Admin</a>';
            }
            
            

            var buttons = '<a href="#" onclick="'+onClick+'" class="button">'+buttonText+'</a>'+adminButton;

            var output   = '<div class="profile">';
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
                                output += '<li data-type="activity"><img src="gfx/profile/sidebar_fav.svg"/>Favorites</li>';
                                output += '<li data-type="files"><img src="gfx/profile/sidebar_files.svg"/>Files</li>';
                                output += '<li data-type="playlists"><img src="gfx/profile/sidebar_playlist.svg"/>Playlists</li>';
                                output += '<li class="openChat"><img src="gfx/chat_icon.svg"/>Open Chat</li>';
                            output += '</ul>';
                            output += '</div>';
                        output += '<div class="profileMain">';
                            output += '<ul class="profileMainNav">';
                                output += '<li class="active" data-type="activity">Activity</li>';

                                output += '<li data-type="info">Info</li>';
                                output += '<li data-type="members">Members</li>';
                            output += '</ul>';
                            output += '<div class="content">';

                                output += '<div class="profile_tab favorites_tab">';
                                    output += fav.show(1);
                                output += '</div>';
                                output += '<div class="profile_tab files_tab">'+filesystem.showFileBrowser(groupdata['homeFolder'])+'</div>';
                                output += '<div class="profile_tab playlists_tab">';

                                output += '</div>';


                                output += '<div class="profile_tab activity_tab" style="display:block"></div>';



                            output += '</div>';
                        //output += '</div>';
                    output  += '</div>';
                output  += '</div>';

            reader.tabs.addTab('Profle', 'html', output);

            //load feed
            var profileFeed = new Feed('group', '.activity_tab', group_id);

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
              if($('#updateGroupFormContainer')){
                  groups.showUpdateGroupForm(groupId);
              };
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
        this.getUsers = function(groupId){
            
            return api.query('api/groups/getUsers/', { group_id : groupId });
        
        };
        this.showPicture = function(group_id){
            
            return "<div class=\"userPicture userPicture_2\" style=\"background: url('data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABipJREFUeNrMWEuIHFUUve\/Vq6qe7ulvejLG+IkMBhfibCISmaBoRMgyG0lExA9kJUYXom4Cuo4oghj8bASFRBFEN0EDulGiWYgbPxCN0UkmM5Ppme7qrs+r9zy3ujsxk2SsDiakhuqurnqv7rnn3nfufSOstXS9HJKuo0M9eujnkSYYMFkrehRpwmnJc+UmT8mtjiBXG\/tdLzG\/usKSNYZSjBWjgLkSDziwHF7liPGS58z4rtwuASbR1o0ScxyAtbgSZkadwMzAKDXGvRdLvvM0fk+BkYyBgi93j\/uFvYud6KUgTD4nMTIYm58OvNxzZHFd2T3kK7FjbrlHnSglAzDZYyFofdm7s1l2P44TvS1K7ffOCIAUxzYXlgGYDfXiuzo1O46f6ZAD48gXkvI84pOLXbDm+tWi+858O7zHGBGJnICUq5ycYDhH5IORNrtOt3rkcJJgavovZ7JQKUHLQczPpz0l7kNID+cGY1OdO0quK55oBSGAGZJCkk4vDjEbFhjd6cVU8JyHlLSH8yqZipLcYDgl7ko0A7Fk0vSyoPkjBdA4MVNhbHLnsbKUW4FLcZKWU5Mt7P+cxdFLEtE0Jr\/CK30ZDy9xwMHUjjCYjBCKRig3yuRcTTi6eG9EgnLTDhiJGEFs1AiFEupuT8DCHSOU1nkS+cufSnOupsFxDOfDI4z\/HT6MoMBiEGEwlCNkn+J8eYS8+XoNMYdYyiylhjqkemEveyxAp+\/65xKCgV0ihD\/g5hF8P5ADy5\/Il29Wo2N9Yo3g90ewLXE9tKPmF+YyFXWVS41KlZLB6vK8AvmeB71IB2LWn4S\/F\/BxNEO\/pkTSfsxaGSoh+5imhrTuZddxnFCnG0AYJa77NpTMCovIEPbCDvXQqGTqqhMKQweAfADz+iHM+BTHrE2fx8\/X12DlCEa+NQyDMRrlQVIUhaj4IQkWRegP32OfpLQXtxBSOuQ4NgPDkh\/FmgooQDoxAJlkYSwUXEID9QaG18HSPrmq8IC9WSmc3WBSx3FMCoU00TFZjENpoIQdBwhjOT9NvraTwyJlP77nGyqwhheIrDbRQckyaNIszBwCZg\/XB+MknuOxcRzBnkYKyHMtxv\/YAwsUR8PRumWiWnxVZrHXZJFXGhLBoJqVsRlHUiNOEhqmQF4pywUm87rfz25tlItvbmhWjiZa72x3wyyNmBH2OYpi1CO95ebJ2rf1SvE53NrMOHTGms2hM2sciWaPzURpzN8+US8\/ibBs60ax3+r0aCXooRl3ETvTlzVLWZM1u7BMYaI310pjr21cX9mnHOenpXb3Czj0VdCLj+F9xlEyHxj2AH5w4m26sVl9rDRWeBxJOrXUDmi5E1IPS9KFVRexYCCZx2KoZGi40FwttNq0sNSm0phb9T13plwszJTGx6hU8I5g\/mdLK8FHwHRmtY6J6Vc+zF7oKsWdHLNx6w3N2l6wscvodHKhFVALLHA+eBjDydgX6svTPkxUDWZZt+QAfK1cpGppjKSSJ1c64XunF1v7sdXpmEEzkIHJehSgnGzUnm1US\/t6UVyfX+oQ5wSvGFCNJd\/XDJY9MULC93OqX2p05oWl6niRJmrjqKHyx9kzS49gEfwiYUgNe49mvfx2pVTYc+LUPC21e+SjNy64Kktc1gNO4gv0dcRVKKB0HocWxlorQRbGjetr0xO18penF5fvBeiTquh7LMO7ce757cQsxWgrx3w3A6i1pqtxcKi5of977izVK6WbEKodSOwDKoCyAtV0u9NBX2u4iUZepnQ1\/x1g0340fDDfDbrUMYI5I7VzIqH3T5goQlyL2HFZ3kQLuiYHA+jAnBI2fuo2h9T9E6g9lloHjmskFFbVNfy\/RGQYCNEzt7vh3XVJ6q\/Q1rY0nNLUuDwapnYD+KrwXgxj\/NUN9hWH5eLfKFrQRkPLZUWzFU9MnerZGrYqFHRT+0HTp0+gD2Usngru+fheNxBF5mqSWxy6sIfk5eUOnhWpb2COjawqM7xljQfPeI5Gji7COd72riA7gnZCAbwNmKUEmvNHG7Fz+t2ngxkOp9DgRUxKY2DYrALDYJvUZzHBuTAwLFelBj87OyApxULq4mYKMLzXy17ERv4RYACDOhC2dc0EDgAAAABJRU5ErkJggg=='); border-color: red; width: 20px;height:  20px;background-size: 100%;border-radius:10px\"><\/div>";

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
                    jsAlert('', 'The group has been updated');
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
                    if(data != '1'){
                        gui.alert('The group could not be created', 'Create Group');
                    }else{
                        
                        jsAlert('', 'The group has been created');
                        $('.blueModal').remove();
                    }
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

        
