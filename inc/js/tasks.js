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


var tasks = new function(){
    	this.getData = function(taskId){
	   return api.query('api.php?action=getTaskData', {taskId: taskId});
	};
	
	this.addForm = function(startstamp){
		if(typeof startstamp !== 'undefined')
			var d = new Date(startstamp*1000);
		else
			var d = new Date();
			
		
		var formattedDate = calendar.beautifyDate((d.getMonth())+1)+'/'+calendar.beautifyDate(d.getDate())+'/'+d.getFullYear();
		
                     
                var formModal = new gui.modal();

                var fieldArray = [];
                var options = [];
                options['headline'] = '';
                options['buttonTitle'] = 'Save';
                options['noButtons'] = true;

                var field0 = [];
                field0['caption'] = 'Title';
                field0['required'] = true;
                field0['inputName'] = 'taskTitle';
                field0['type'] = 'text';
                fieldArray[0] = field0;

                var field1 = [];
                field1['caption'] = 'taskDescreption';
                field1['inputName'] = 'taskDescription';
                field1['type'] = 'text';
                fieldArray[1] = field1;


                var field = "<input type='text' name='date' id='date' class='date datepicker' value='"+formattedDate+"' style='width: 72px;'>";
		field += "&nbsp;<input type='text' name='time' id='time' class='time eventTime' value='15:30' style='width: 37px;'>";

                var field2 = [];
                field2['caption'] = 'Day';
                field2['inputName'] = 'day';
                field2['type'] = 'html';
                field2['value'] = field;
                fieldArray[2] = field2;
                
                var captions = ['Pending', 'Done'];
                var type_ids = ['pending', 'done'];

                var field3 = [];
                field3['caption'] = 'Status';
                field3['inputName'] = 'status';
                field3['values'] = type_ids;
                field3['captions'] = captions;
                field3['type'] = 'dropdown';
                fieldArray[3] = field3;
                
                var field4 = [];
                field4['caption'] = 'Privacy';
                field4['type'] = 'privacy';
                field4['value'] = 'f//f';
                fieldArray[4] = field4;
                
                var modalOptions = {};
                modalOptions['buttonTitle'] = 'Create Task';



                modalOptions['action'] = function(){
                    //@redone
                    if($('#taskTitle').val().length > 0 && $('#createTaskFormContainer #date').val().length > 0 && $('#createTaskFormContainer #time').val().length > 0){
                        
                        var startDate = new Date($('#createTaskFormContainer #date').val()+"-"+$('#createTaskFormContainer #time').val());
                        
                        var startTime = startDate.getTime()/1000;
                                                            
                        tasks.create(User.userid, startTime, $('#taskTitle').val(), $('#taskDescription').val(), $('#createTaskFormContainer #status').val(), $('#createTaskFormContainer #privacyField :input').serialize(), function(){
                            
                                            calendar.loadTasks();
                                            gui.alert('The Task has been added.');
                                            $('.blueModal').slideUp();
                                            updateDashbox('task');
                        });
                    }else{
                           gui.alert('You need to fill out all the fields.');
                    }
                };
                
                formModal.init('Create Task', '<div id="createTaskFormContainer"></div>', modalOptions);
                gui.createForm('#createTaskFormContainer',fieldArray, options);
                                        
		
              //init datepicker in modal
              $('.datepicker').datepicker();
	};
	
	this.get = function(startStamp, stopStamp, privacy){
	   
	   return api.query('api.php?action=getTasks',{startStamp: startStamp, stopStamp: stopStamp, privacy: privacy});
           
	};
	
	this.show = function(taskId, editable){
		
		var taskData = tasks.getData(taskId);
		
		var date = new Date(taskData.timestamp*1000);
		
		
		var editableToken;
		
		
		var status = taskData.status;
		//generate formstuff from eventdata
		if(editable){
			editableToken = 'contentEditable';
			var checked;
			if(editable == true){
				
				checked = 'checked="checked"';
				status = '<select name="status" id="status">';
						if(taskData.status == 'pending'){
			  		    	status += '<option value="pending">Pending</option>';
			  		    	status += '<option value="done">Done</option>';
						}else{
			  		    	status += '<option value="done">Done</option>';
			  		    	status += '<option value="pending">Pending</option>';
						}
					status += '</select>';
			}
			
		}else{
		}
		
		var time = calendar.beautifyDate(date.getHours())+':'+calendar.beautifyDate(date.getMinutes());
		
		
		
		
		var content  = '<table class="formTable">';
				content += '<form id="updateTask" method="post">';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Title:';
		  		    	content += '</td>';
		  		    	content += '<td '+editableToken+' id="title">';
		  		    	content += taskData.title;
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Description:';
		  		    	content += '</td>';
		  		    	content += '<td '+editableToken+' id="description">';
		  		    	content += taskData.description;
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Day:';
		  		    	content += '</td>';
		  		    	content += '<td><div id="date" class="datepicker" '+editableToken+'>';
		  		    	content += date.getMonth()+1+'/'+date.getDate()+'/'+date.getFullYear();
		  		    	content += '</div>&nbsp;<div id="time" '+editableToken+'>'+time+'</div>';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Status:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += status;
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Privacy:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += privacy.show(taskData.privacy, editable);
		  		    	content += '</td>';
		  		    content += '</tr>';
		    	content += '</form>';
		    content += '</table>';
		
		var onSubmit = function() {
			$('#updateTask').submit();
		};
		
		//create modal
              modal.create('Task '+taskData.title, content, [onSubmit, 'Save']);

              //init datepicker in modal
              $(".datepicker").datepicker({
                                  dateFormat: 'dd/mm/yy',
                                  showOn: "button",
                                  buttonImage: "images/calendar.gif",
                                  buttonImageOnly: true,
                                  onClose: function(dateText, inst) {
                                      $(this).parent().find("[contenteditable=true]").focus().html(dateText).blur();
                                  }
                              });

                              $('#updateTask').submit(function(e){
                                      e.preventDefault();
                                      if($('#title').text().length > 0 && $('#date').text().length > 0 && $('#time').text().length > 0){

                                              var privacy = $('.blueModal .privacySettings  :input').serialize();

                                              var searchString  = 'taskId='+encodeURIComponent(taskId);
                                                      searchString += '&title='+encodeURIComponent($('.blueModal #title').text());
                                                      searchString += '&description='+encodeURIComponent($('.blueModal #description').text());
                                                      searchString += '&date='+encodeURIComponent($('.blueModal #date').justtext());
                                                      searchString += '&status='+encodeURIComponent($('.blueModal #status').val());
                                                      searchString += '&time='+encodeURIComponent($('.blueModal #time').text());
                                                      searchString += '&'+privacy;

                                              $.post("api.php?action=updateTask",searchString,function(data){

                                                          if(empty(data)){
                                                              gui.alert('The task has been updated.', 'Tasks');
                                                              $('.blueModal').slideUp();
                                                          }else{
                                                              gui.alert(data, 'Events', 'error');
                                                          }
                                                      });

                                      }else{
                                              gui.alert('You need to fill out all the fields.', 'Tasks', 'error');
                                      }


                                      return false;
                              });
	};
	this.create = function(user, timestamp, title, description, status, privacy, callback){
            var result="";
            $.ajax({
                url:'api/calendar/tasks/create/',
                async: false,
                type: "POST",
                data: $.param({user : user, timestamp: timestamp, title: title, description: description, status: status})+'&'+privacy,
                success:function(data) {
                   result = data;
                   if(typeof callback === 'function'){
                       callback(); //execute callback if var callback is function
                   }
                }
            });
            return result;
        };
	this.markAsDone = function(id){
                api.query('api.php?action=markTaskAsDone', {eventid : id});
                $('.task_'+id).addClass('doneTask');
                if(!calendar.showDoneTasks){
                    $('.task_'+id).hide();
                }
	};
	this.markAsPending = function(id){
            api.query('api.php?action=markTaskAsPending', {eventid : id});
            $('.task_'+id).removeClass('doneTask');
            $('.task_'+id).show();
	};
	this.update = function(){
		updateDashbox('task');
	};
};
