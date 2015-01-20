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
	
        this.show = function(groupId){
            
                  reader.applicationVar.show();
                    reader.tabs.addTab("" + groupId + "", '',gui.loadPage("./group.php?id=" + groupId + ""));
              
                  return false;
        };
        this.create = function(title, type, description, invitedUsers){
            var callback = function(data){
                       if(data != '1'){
                           gui.alert('The group could not be created', 'Create Group');
                       };
            };
            api.query('api/groups/create/', { title : title, type: type, description: description, invitedUsers: JSON.stringify(invitedUsers) }, callback);
        };
	this.get = function(){
			  		
				    var result = api.query('api.php?action=getGroups', { val : 'val' });
				    
				    if(result != null){
				   		return $.parseJSON(result);
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
                var callback = function(){
                    jsAlert('', 'The group has been created');
                    $('.blueModal').remove();
                };
                var invitedUsers = [];
                $('.blueModal .invitedBuddy').each(function(){
                   invitedUsers.push($(this).val()); 
                });
                groups.create($('.blueModal #title').val(), $('.blueModal #type').val(), $('.blueModal #description').val(), invitedUsers);
            };
            formModal.init('Update CreateGroup', '<div id="createGroupFormContainer"></div>', modalOptions);
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

        
