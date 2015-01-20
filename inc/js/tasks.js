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
		if(typeof startstamp === undefined)
			var d = new Date(startstamp*1000);
		else
			var d = new Date();
			
		
		var formattedDate = calendar.beautifyDate((d.getMonth())+1)+'/'+calendar.beautifyDate(d.getDate())+'/'+d.getFullYear();
		
		var content  = '<table class="formTable">';
				content += '<form id="createTask" method="post">';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Title:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<input type="text" name="title" id="taskTitle">';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Description:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<textarea name="description" id="taskDescription"></textarea>';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Day:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<input type="text" name="date" id="date" class="date datepicker" value="'+formattedDate+'" style="width: 72px;">';
		  		    	content += '&nbsp;<input type="text" name="time" id="time" class="time eventTime" value="15:30" style="width: 37px;">';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Status:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += '<select name="status">';
		  		    	content += '<option value="pending">Pending</option>';
		  		    	content += '<option value="done">Done</option>';
		  		    	content += '</select>';
		  		    	content += '</td>';
		  		    content += '</tr>';
		  		    content += '<tr>';
		  		    	content += '<td>';
		  		    	content += 'Privacy:';
		  		    	content += '</td>';
		  		    	content += '<td>';
		  		    	content += privacy.show('f//f', true);
		  		    	content += '</td>';
		  		    content += '</tr>';
		    	content += '</form>';
		    content += '</table>';
		var onSubmit = function() {
			$('#createTask').submit();
		};
		
		//create modal
              modal.create('Create New Task', content, [onSubmit, 'Save']);

              //init datepicker in modal
              $('.datepicker').datepicker();

              $('#createTask').submit(function(e){
                      e.preventDefault();
                      if($('#taskTitle').val().length > 0 && $('#date').val().length > 0 && $('#time').val().length > 0){
                              $.post("api.php?action=createTask",$(this).serialize(),function(data){
                                            if(data.length === 0){
                                              calendar.loadTasks();
                                              jsAlert('','The Task has been added.');
                                              $('.blueModal').slideUp();
                                              updateDashbox('task');
                                          }else{
                                              jsAlert('', data);
                                          }
                                      });

                      }else{
                              jsAlert('', 'You need to fill out all the fields.');
                      }


                      return false;
              });
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
                                                              jsAlert('', 'The event has been updated.');
                                                              $('.blueModal').slideUp();
                                                          }else{
                                                              jsAlert('', data);
                                                          }
                                                      });

                                      }else{
                                              jsAlert('', 'You need to fill out all the fields.');
                                      }


                                      return false;
                              });
	};
	this.create = function(user, timestamp, title, description, privacy){
		
	    $.ajax({
	      url:"api.php?action=createTask",
	      async: false,  
		  type: "POST",
		  data: { 
		  	user : user,
		  	timestamp : timestamp,
		  	title : title,
		  	description : description,
		  	privacy : privacy
		  	 },
	      success:function(data) {
	         result = data; 
	      }
	   });
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
