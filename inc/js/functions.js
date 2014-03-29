//initializeeader
var sourceURL = 'http://universeos.org';


var usernames = [];
var privateKeys = [];
var messageKeys = [];

var openDialogueInterval;

var focus = true;

                $(document).ready(function(){
                        
                        
                        //init draggable windows
                        init.GUI();
                        
                        //init bootstrap popover
                        $('.bsPopOver').popover();
                        
                        //init bootstrap alert
                        $(".alert").alert();
                    
                });
//privacy


	function initPrivacy(){
		

                                    $('.privacyPublicTrigger').click(function(){

                                        if($(this).is(':checked')){

                                            $('.uncheckPublic').prop('checked', false);

                                        }

                                    });

                                    $('.privacyCustomTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckCustom').prop('checked', false);
                                        }
                                    });


                                    $('.privacyHiddenTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckHidden').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyOnlyMeTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckOnlyMe').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyBuddyTrigger').click(function(){
                                    	
                                    	var buddyTriggerId = '.privacyBuddyTrigger';
                                        if($(this).is(':checked')){
                                        	if($(this).data('privacytype') == "edit")
                                            	$(buddyTriggerId+'_see').prop('checked', true);
                                        }else{
                                        	if($(this).data('privacytype') == "see")
                                            	$(buddyTriggerId+'_edit').prop('checked', false);
                                        	if($(this).data('privacytype') == "edit")
                                            	$(buddyTriggerId+'_see').prop('checked', false);
                                        }
                                    	$('.privacyShowBuddy').show();
                                    });
                                    
                                    $('.privacyGroupTrigger').click(function(){
                                    	$('.privacyShowGroups').show();
                                    	var groupTriggerId = '.privacyGroupTrigger_'+$(this).data('groupid');
                                        if($(this).is(':checked')){
                                        	if($(this).data('privacytype') == "edit")
                                            	$(groupTriggerId+'_see').prop('checked', true);
                                        }else{
                                        	if($(this).data('privacytype') == "see")
                                            	$(groupTriggerId+'_edit').prop('checked', false);
                                        	if($(this).data('privacytype') == "edit")
                                            	$(groupTriggerId+'_see').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.uncheckOnlyMe').click(function(){
                                        if($(this).is(':checked')){
                                            $('.privacyOnlyMeTrigger').prop('checked', false);
                                        }
                                    });
                                    $('.privacyHiddenTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckHidden').prop('checked', false);
                                        }
                                    });
                                    $('.privacyCustomTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckCustom').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.checkPrev').click(function(){
                                        //prev see check
                                    });

	}
    
              
//window functions
              var init = new function(){
              	
              	
              	this.draggableApplications = function(){
              		
                            $(".fenster").not('.ui-draggable').draggable({
                                    cancel: '.inhalt',
                                    containment: '#bodywrap',
                                    scroll: false,
                                    drag: function(){
                                        //disable textmarking
                                        $('*').disableSelection();
                                    },
                                    stop: function(){
                                        //enable textmarking
                                        $('*').enableSelection();
                                    }
                            });
              	};
              	this.resizableApplications = function(){
                            $(".fenster").not('.ui-resizable').resizable({
                                    handles: 'n, e, s, w, ne, se, sw, nw',
                                    containment: '#bodywrap',
                                    start: function(){
                                        //disable textmarking
                                        $('*').disableSelection();
                                        if($(this) != undefined){
	                                        //bring window to front 
	                                        $(this).css('z-index', 9999);
	                                        $(this).css('position', 'absolute');
                                        }
                                    },
                                    stop: function(){
                                        //enable textmarking
                                        $('*').enableSelection();
                                    }
                            });
              	};
              	this.applicationOnTop = function(){
                        $('.fenster').children().mousedown(function(){
							
                           	if($(this) != undefined){
                           		
                  			$(".fenster").css('z-index', 999);
                            $(this).parent(".fenster").css('z-index', 9999); 
                            $(this).parent(".fenster").css('position', 'absolute');
                            }
                        });
              	};
              	this.setApplicationsToStartupSizes = function(){
              		//old creepy way to initalize windows => in future => css media width
                	
                	
                    var oneSixthWidth = ($(document).width())/6;
                    var oneSixthHeight = $(document).height()/6;
                    
                    var offsetTop = oneSixthHeight/2;
                    var offsetRight = oneSixthWidth/2;
                    var offsetLeft = offsetRight;
                    
                    var widthSm = oneSixthWidth;
                    var heightSm = oneSixthHeight*4;
                    
                    var widthBig = oneSixthWidth*3;
                    var heightBig = heightSm;
                    
                    
                    
                            
                        $("#buddylist").css({
                        'top' : offsetTop,
                        'right' : offsetRight+20,
                        'width' : widthSm,
                        'height' : heightBig,
                        'z-index' : '9998'
                            });
                        
                        $("#feed").css({
                        'top' : offsetTop+20,
                        'right' : offsetRight,
                        'width' : widthSm,
                        'height' : heightBig,
                        'z-index' : '9997'
                            });
                            
                            
                        $("#chat").css({
                        'top' : offsetTop,
                        'left' : offsetLeft,
                        'width' : widthBig,
                        'height' : heightBig,
                        'z-index' : '997'
                            });
                            
                        $("#filesystem").css({
                        'top' : offsetTop+20,
                        'left' : offsetLeft+20,
                        'width' : widthBig,
                        'height' : heightBig,
                        'z-index' : '998'
                            });
                            
                        $("#reader").css({
                        'top' : offsetTop+40,
                        'left' : offsetLeft+40,
                        'width' : widthBig,
                        'height' : heightBig,
                        'z-index' : '999'
                            });
              	};
              	
              	this.dashBox = function(){
		            //init dashcloses 
					$('.dashBox .dashClose').click(function(){
						$(this).parent('.dashBox').slideUp();
					});	
              	};
              	
              	this.toolTipper = function(){
                
                

	                $(document).mousemove(function(event){
	                    window.mouseX = event.pageX;
	                    window.mouseY = event.pageY;
	                    $('.mousePop').hide();
	                });
	                
	                
	                
	                //initialize mousePop(tooltip)
	                $('.tooltipper').mouseenter(function(){
	                    
	                    var type = $(this).attr("data-popType");
	                    var id = $(this).attr("data-typeId");
	                    var text = $(this).attr("data-text");
	                    mousePop(type, id, text);
	                }).mouseleave(function(){
	                    $('.mousePop').hide();
	                });
              		
              	};
              	this.search = function(){
              		//init search
					$("#searchField").keyup(function()
					{
						
						delay(function(){
							var search;
							
							search = $("#searchField").val();
							if (search.length > 1)
							{
								$.ajax(
								{
									type: "POST",
									url: "modules/suggestions/dockSearch.php",
									data: "search=" + search,
									success: function(message)
									{
										$("#suggest").empty();
								  		if (message.length > 1)
										{						
											$("#suggest").append(message);
										}
									}
								});
							}
							else
							{
								$("#suggest").empty();
							}
							
						}, 500 );
					});
              };
              	
              	//this function is called to initialzie GUI
              	//all needed functions are collected here
              	this.GUI = function(){
              		this.draggableApplications();
              		this.resizableApplications();
              		this.applicationOnTop();
              		this.setApplicationsToStartupSizes();
              		
              		this.dashBox();
              		
              		this.toolTipper();
              		this.search();
              		
              		dashBoard.init();
              		//fade in applications
                    $("#filesystem:hidden").fadeIn(3000);
                    $("#buddylist:hidden").fadeIn(3000);
                    
                    $("#feed:hidden").fadeIn(3000);
                    $("#chat:hidden").fadeIn(3000);
                        
              	};
              	
             };
              // function Rabbit(adjective) {
				  // this.adjective = adjective;
				  // this.speak = function(line) {
				    // print("The ", this.adjective, " rabbit says '", line, "'");
				  // };
			  // }
			  // var killerRabbit = new Rabbit("killer");
			  // killerRabbit.speak("GRAAAAAAAAAH!");
              function Tab(parentId) {
				  this.parentId = parentId; //id in which tabs are loaded
				  this.index = 1;
				  this.init = function(firstTitle, firstType, firstContent) {
				  
				  	var content =  '<div class="tabs">';
				  			content += '<ul class="tabBar">';
				  				content += '<li class="active" id="1">'+firstTitle+'</li>';
				  			content += '</ul>';
				  		
				  			content += '<div class="tab active" id="1">';
				  			content += '</div>';
				  		content += '</div>';
				  
				  };
				  
				  this.addTab = function(title, type, content){
				  	this.index++;
				  	$(this.parentSelector).children('.tabs .tabBar').append('<li class="active" id="1">'+firstTitle+'</li>');
				  	$(this.parentSelector).children('.tabs').append('<div class="tab active" id="'+this.index+'"></div>');
				  };
				  
				  this.updateTab = function(id, title, type, content){
				  	
				  };
				  this.deleteTab = function(id){
				  	
				  };
			  };
              
			  var application = new function(){
			  	this.create = function(id, title, type, content, style){
			  		var windowStyle = '';
			  		if(type === 'html'){
			  			content = content;
			  		}
			  		
			  		if(typeof style['width'] != 'undefined'){
			  			windowStyle +='width:'+style['width']+';';
			  		}
			  		// if(typeof style['height'] != 'undefined'){
			  			// console.log('height:'+style['height']+';');
			  		// }
// 			  		
			  		
			  		
			  		var output = '<div class="fenster" id="'+id+'" style="'+windowStyle+'">';
			  			output += '<header class="titel">';
	        				output += '<p>'+title+'&nbsp;</p>';
	        				output += '<p class="windowMenu">';
	        					output += '<a href="javascript:hideApplication(\''+id+'\');"><img src="./gfx/icons/close.png" width="16"></a>';
	        					output += '<a href="#" onclick="moduleFullscreen(\''+id+'\');" class="fullScreenIcon"><img src="./gfx/icons/fullScreen.png" width="16"></a>';
	        				output += '</p>';
        				output += '</header>';
        				output += '<div class="inhalt autoflow" id="'+id+'Main">'+content+'</div>';
    
			  		
			  		$('#bodywrap').append(output);
			  		
              		init.draggableApplications();
              		init.resizableApplications();
              		application.onTop('calendarFenster');
			  	};
			  	
			  	this.onTop = function(id){
			  		
                  $(".fenster").css('z-index', 998);
                  $("#"+id+"").css('z-index', 999);
                  $("#"+id+"").css('position', 'absolute');
			  		
			  	};
			  	
			  	this.show = function(id){
                  applicationOnTop(id);
                  $("#" + id +"").show();
			  	};
			  	
			  	this.hide = function(id){
                  $("#" + id +"").hide();
			  	};
			  	
			  	this.fullscreen = function(id){
                  $("#" + id +"").toggle();
			  	};
			  	
			  	this.returnFromFullScreen = function(id){
              	$('#'+id+' .fullScreenIcon').attr("onClick","moduleFullscreen('"+id+"')");
                  var returnFullScreenCSS = {
                        'position' : 'absolute',
                        'top' : window.fullScreenOldMarginY,
                        'left' : window.fullScreenOldMarginX,
                        'width' : window.fullScreenOldX,
                        'height' : window.fullScreenOldY
                       };
                  $("#" + id + "").css(returnFullScreenCSS);
			  	};
			  };
			  
			  var tasks = new function(){
			  	
			  	this.getData = function(taskId){
			  		var res;
			  		$.ajax({
				      url:"api.php?action=getTaskData",
				      async: false,  
					  type: "POST",
					  data: { 
					  	taskId : taskId
					  	 },
				      success:function(data) {
				         res = $.parseJSON(data); 
				      }
				   });
				   return res;
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
              			console.log($(this).serialize());
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
			  		var result;
				    $.ajax({
				      url:"api.php?action=getTasks",
				      async: false,  
					  type: "POST",
					  data: { 
					  	 startStamp: startStamp,
					  	 stopStamp: stopStamp,
					  	 privacy: privacy
					  	 },
				      success:function(data) {
				      	if(data){
				        	result = $.parseJSON(data); 
				      	}
				      }
				   });
				   
				   return result;
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
			  		$.ajax({
				      url:"api.php?action=markTaskAsDone",
				      async: false,  
					  type: "POST",
					  data: { 
					  	eventid : id,
					  	 },
				      success:function(data) {
				         result = data; 
				      }
				   });
				   $('.task_'+id).addClass('doneTask');
				   if(!calendar.showDoneTasks){
				   	$('.task_'+id).hide();
				   }
			  	};
			  	this.markAsPending = function(id){
			  		$.ajax({
				      url:"api.php?action=markTaskAsPending",
				      async: false,  
					  type: "POST",
					  data: { 
					  	eventid : id,
					  	 },
				      success:function(data) {
				         result = data; 
				      }
				   });
				  $('.task_'+id).removeClass('doneTask');
				  $('.task_'+id).show();
			  	};
			  	this.update = function(){
			  		updateDashbox('task');
			  	};
			  };
			  
			  var calendar = new function(){
			  	
			  	this.todayTimeObject = new Date();
			  	this.ShownDay; //defines the starttime of shown intervall(first day of month, first day of week, day)
				this.view = 'month'; //defines type of view(month, week or day)
				this.listType = 'boxes';
			  	this.shownTimeObject;
			  	this.loader;
			  	this.showDoneTasks = false; // defines if tasks with status "done" are shown
			  	
			  	this.show = function(){
			  		if($('#calendarFenster').length === 0){
			  			calendar.init();
			  		}else{
			  			application.show('calendarFenster');
			  		}
			  	};
			  	
			  	this.init = function(){
			  		
			  		var html = '<div id="calendar">';
			  				html += '<header>';
			  					html += '<div class="btn-group pull-right" id="calendarViewDetail">';
			  						html += '<a href="#" class="btn" id="prev">&lt;&lt;</a>';
			  						html += '<a href="#" class="btn" id="text"></a>';
			  						html += '<a href="#" class="btn" id="next">>></a>';
			  					html += '</div>';
			  					html += '<div class="btn-group pull-left" id="calendarView">';
			  						html += '<a href="#" class="btn" id="day">Day</a>';
			  						html += '<a href="#" class="btn" id="week">Week</a>';
			  						html += '<a href="#" class="btn" id="month">Month</a>';
			  					html += '</div>';
			  					html += '<div class="btn-group pull-left" id="calendarListType" style="margin-left:30px;">';
			  						html += '<a href="#" class="btn active" id="boxes"><i class="icon-th"></i></a>';
			  						html += '<a href="#" class="btn" id="list"><i class="icon-th-list"></i></a>';
			  					html += '</div>';
			  					html += '<div class="btn pull-right" id="headerToday" style="margin-right:30px;">Today</div>';
			  				html += '</header>';
				  			html += '<div id="main">';
								html += '<header>';
									html += '<span>Monday</span>';
									html += '<span>Tuesday</span>';
									html += '<span>Wednesday</span>';
									html += '<span>Thursday</span>';
									html += '<span>Friday</span>';
									html += '<span>Saturday</span>';
									html += '<span>Sunday</span>';
								html += '</header>';
								html += '<div class="calendarFrame">';
						
								html += '</div>';
							html += '</div>';
							html += '<div id="side" class="leftNav">';
							
								html += '<ul id="calendars">';
									html += '<li class="header">Calendars</li>';
									
									html += '<li><input type="checkbox" data-value="h" checked>&nbsp;Me</li>';
									html += '<li><input type="checkbox" data-value="p">&nbsp;Public</li>';
									html += '<li><input type="checkbox" data-value="f" checked>&nbsp;Friends</li>';
									
									//load groups into calendar list
									var userGroups = groups.get();
									if(userGroups){
										$.each(groups.get(), function( index, value ) {
											html += '<li><input type="checkbox" data-value="'+value+'">&nbsp;<img src="./gfx/icons/group.png" height="14">'+groups.getTitle(value)+'</li>';
										});
									}
								html += '</ul>';
								html += '<ul id="taskList">';
									html += '<li class="header"><input type="checkbox" id="showTasks" onclick="calendar.toggleTasks();">&nbsp;Show Tasks<a href="#" class="pull-right" onclick="tasks.addForm('+this.todayTimeObject.getTime()/1000+')"><i class="icon-plus icon-white"></i></a></li>';
									html += '<li style="display:none;"><input type="checkbox" id="hideDoneTasks" onclick="calendar.toggleDoneTasks();" checked>&nbsp;hide done</li>';
								html += '</ul>';
								html += '<ul id="events">';
									html += '<li class="header">Events<a href="#" class="pull-right" onclick="events.addForm('+this.todayTimeObject.getTime()/1000+')"><i class="icon-plus icon-white"></i></a></li>';
									//events will apend to this list
								html += '</ul>';
								
							html += '</div>';
						html += '</div>';
						
			  			application.create('calendarFenster', 'Calendar', 'html', html,{width: ($(document).width()*0.9)+"px", dfgh:  ($(document).height()*0.8)+"px"});
			  			
			  			
						$('#calendars .header').click(function(){
							$('#side #calendars li').not('.header').slideToggle();
						});
			  			
			  			$('#calendars input[type=checkbox]').click(function(){
			  				calendar.loadEvents();
			  			});
			  			
			  			$('#calendarListType #boxes').click(function(){
			  				calendar.toggleListType('boxes');
			  			});
			  			
			  			$('#calendarListType #list').click(function(){
			  				calendar.toggleListType('list');
			  			});
			  			
			  			
			  			this.loadMonth();
			  	};
			  	
			  	this.toggleListType = function(type){
			  		
			  		//show days as boxes
			  		if(type == 'boxes'){
			  			
			  			$('#main').mouseenter(function(){
			  				$(this).children('header').slideDown();
			  			});
			  			
			  			$('#main').mouseleave(function(){
			  				$(this).children('header').slideUp();
			  			});
			  			
			  			
			  			$('.calendarFrame').removeClass('list');
			  			$('#calendarListType #list').removeClass('active');
			  			
			  			$('.calendarFrame').addClass('boxes');
			  			$('#calendarListType #boxes').addClass('active');
			  			this.listType = 'boxes';
			  			
			  		//show days in list
			  		}else if(type == 'list'){
			  			
			  			$('#main').unbind('mouseenter mouseleave');
			  			
			  			$('#main>header').hide();
			  			
			  			$('.calendarFrame').removeClass('boxes');
			  			$('#calendarListType #boxes').removeClass('active');
			  			
			  			$('.calendarFrame').addClass('list');
			  			$('#calendarListType #list').addClass('active');
			  			this.listType = 'list';
			  		}else if(type == 'day'){
			  			
			  			$('#main').unbind('mouseenter mouseleave');
			  			$('#main>header').hide();
			  			
			  			$('.calendarFrame').removeClass('boxes');
			  			$('.calendarFrame').removeClass('list');
			  			$('#calendarListType #boxes').removeClass('active');
			  			$('#calendarListType #list').removeClass('active');
			  			
			  			$('.calendarFrame').addClass('day');
			  			//$('#calendarListType #list').addClass('active');
			  			
			  			
			  		}
			  	};
			  	
			  	this.getPrivacy = function(){
			  		var privacy = [];
			  		$('#calendars input[type=checkbox]:checked').each(function(){
			  			 privacy.push($(this).data('value'));
			  		});
			  		
			  		return privacy.join(';');
			  	};
			  	
			  	this.loadTasks = function(){
			  		$('.calendarFrame .day').each(function(){
			  			var startstamp = $(this).data("timestamp");
			  			
			  			var taskList = tasks.get(startstamp, startstamp+86400, calendar.getPrivacy());
						var list = '';
					
							if(taskList){
								$.each( taskList, function( key, value ) {
									  if($('#taskDetail_'+value.id).length === 0){
									  	  var style = '';
									  	  var taskClass = 'task_'+value.id;
									  	  var checked;
									  	  if(value.status == 'done'){
									  	  	if(!calendar.showDoneTasks)
									  	  		style = 'display:none;';
									  	  	taskClass += ' doneTask';
									  	  	checked = 'checked="checked"';
									  	  }else{
									  	  	checked = '';
									  	  }
										  var d = new Date(value.timestamp*1000);
										  list += '<li data-taskId="'+value.id+'" style="'+style+'" class="'+taskClass+' task">&nbsp;<input type="checkbox" class="eventBox" data-eventid="'+value.id+'" '+checked+'>&nbsp;'+value.title+'<br>'+d.getHours()+':'+d.getMinutes()+'<span class="caret" onclick="$(\'#taskDetail_'+value.id+'\').toggle();"></span></li>';
										  list += '<li class="taskDetail '+taskClass+'" id="taskDetail_'+value.id+'" style="'+style+'"><i class="icon-pencil icon-white" onclick="tasks.show('+value.id+', '+value.editable+');"></i>'+value.description+'</li>';
										
									  }
								 });
							}
							
						$(this).children('.eventList').append(list);
			  			
			  			
			  		});
			  		
			  		$('.task .eventBox').click(function(){
			  			if($(this).is(':checked')){
			  				tasks.markAsDone($(this).data('eventid'));
			  			}else{
			  				tasks.markAsPending($(this).data('eventid'));
			  			}
			  			console.log($(this).is(':checked'));
			  		});
			  		
			  		console.log('tasks loaded');
			  	};
			  	
			  	this.toggleTasks = function(){
			  		if($('#showTasks').is(':checked')){
						$('#side #taskList li').not('.header').slideDown();
			  			calendar.loadTasks();
			  		}else{
			  			
						$('#side #taskList li').not('.header').slideUp();
			  			$('.task').remove();
			  			$('.taskDetail').remove();
			  		}
			  	};
			  	
			  	this.toggleDoneTasks = function(){
			  		if($('#hideDoneTasks').is(':checked')){
			  			$('.doneTask').hide();
			  			calendar.showDoneTasks = false;
			  		}else{
			  			$('.doneTask').show();
			  			calendar.showDoneTasks = true;
			  		}
			  	};
			  	
			  	this.loadEvents = function(){
			  		if($('#showTasks').is(':checked')){
			  			calendar.loadTasks();
			  		}
			  		$('.calendarFrame .day').each(function(){
			  			var startstamp = $(this).data("timestamp");
			  			
			  			var appointments = events.get(startstamp, startstamp+86400, calendar.getPrivacy());
						var list = '';
					
							if(appointments){
								$.each( appointments, function( key, value ) {
								  if($('#eventDetail_'+value.id).length === 0){
								  	
								  	  //create date objects
									  var startDate = new Date(value.startStamp*1000);
									  var endDate = new Date(value.stopStamp*1000);
									  
									  var startTime = calendar.beautifyDate(startDate.getHours())+':'+calendar.beautifyDate(startDate.getMinutes());
									  var stopTime = calendar.beautifyDate(endDate.getHours())+':'+calendar.beautifyDate(endDate.getMinutes());
									  
									  var title = startTime+'&nbsp;'+value.title;
									  
									  if(startTime == '00:00' && stopTime == '23:59'){
									  	title = value.title;
									  	startTime = 'All Day';
									  	stopTime = '';
									  }
									  
									  
									  list += '<li data-eventId="'+value.id+'" onclick="$(\'#eventDetail_'+value.id+'\').toggle();">'+title+'</li>';
									  list += '<li class="eventDetail" id="eventDetail_'+value.id+'" onclick="events.show('+value.id+', '+privacy.authorize(value.privacy, value.user)+');"><i class="icon-pencil"></i>'+startTime+' - '+stopTime+'<br>'+value.place+'</li>';
									
								  }
								 });
							}
							
						$(this).children('.eventList').append(list);
			  			
			  			
			  			
			  		});
			  		
			  		console.log('events loaded into mainframe..');
			  	};
			  	
				this.appendDayToCalender = function(time){
				
					var selection;
					var dayDateObject = new Date(time * 1000);
					
					var today = new Date();
						today.setHours(0,0,0,0);
						
					var month = this.beautifyDate(dayDateObject.getMonth()+1);
					var date = this.beautifyDate(dayDateObject.getDate());
					
					var dayClass = ''; //css class that is added to .day
					
					
						if(today.getTime()/1000 == time){
							dayClass = 'today';
						}else{
							dayClass = '';
						}
						
						if(dayDateObject.getDay() == 0 || dayDateObject.getDay() == 6){
							dayClass += ' weekend';
						}
						
					var day  = '<div class="day '+dayClass+'" data-timestamp="'+dayDateObject.getTime()/1000+'">';
							day += '<header>';
								day += date+'.'+month;
								day += '<div class="dropdown">';
									day += '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
										day += '<i class=\"icon-cog\"></i>';
										day += '<span class="caret"></span>';
									day += '</a>';
									day += '<ul class="dropdown-menu">';
										day += '<li><a href="#" class="header">Options</a></li>';
										day += '<li><a href="#" title="Add Event" onclick="events.addForm('+time+')">Add Event</a></li>';
									
										day += '<li><a href="#" title="Add Task" onclick="tasks.addForm('+time+')">Add Task</a></li>';
									day += '</ul>';
								day += '</div>';
							day += '</header>';
						day += '<ul class="eventList"></ul>';
						day += '</div>';
					
					$('.calendarFrame').append(day);
				};
				 
				this.loadMonth = function(date){
					
			  		//tidy up calendar
					$('.calendarFrame').html('');
					$('.calendarFrame').removeClass('dayView');
					$('.calendarFrame').removeClass('weekView');
					
					//add class for monthview
					$('.calendarFrame').addClass('monthView');
					
					//add class for boxes
					$('.calendarFrame').addClass('boxes');
					
					
					
					
					if(!date){
						var d = new Date();
					}else if(typeof date == 'object'){
						var d = date;
					}else{
						var d = new Date(date);
					}
					this.shownTimeObject = d;
					
					var firstDayOfMonth = new Date(d.getFullYear(), d.getMonth(), 1, 0, 0, 0);
					var lastSecondOfMonth = new Date(d.getFullYear(), d.getMonth() + 1, 0, 0, 0, 0);
					var month = lastSecondOfMonth.getMonth();
					clearTimeout(this.loader);
					this.loader = setTimeout(function(){calendar.loadEventsIntoSide(firstDayOfMonth);}, 1000);
					this.updateViewDetail('month', this.getMonthName(d.getMonth()));
					
					var firstDayOfMonthWeekday = firstDayOfMonth.getDay();
					
						firstSecondOfMonth = (firstDayOfMonth.getTime()/1000);
						lastSecondOfMonth = (lastSecondOfMonth.getTime()/1000);
						this.shownDay = firstSecondOfMonth;
					//get unixtime of today, 0:00
					var today = new Date(d.getFullYear(),d.getMonth(), d.getDate(),0,0,0);
					var unixTimeToday = today.getTime()/1000;
					var daysInMonth = (lastSecondOfMonth-firstSecondOfMonth)/86400;
					
					//define offset of days of the last month
					var offset = new Array("6", "0", "1", "2", "3", "4", "5");
					
					var loadedDays = 0;
					var offSetStartTime = firstSecondOfMonth-(offset[firstDayOfMonthWeekday]*86400); //get starttime for first offset day
					
					
					//append offset
					while(loadedDays < offset[firstDayOfMonthWeekday]){
						this.appendDayToCalender(offSetStartTime);
						loadedDays++;
						offSetStartTime = offSetStartTime+86400;
					}
					
					daysInCalender = 30; //forgets day offset if first of month is not a monday
					loadedDays = 0;
					var whileTime = firstSecondOfMonth;
					while(loadedDays < daysInCalender){
						
						
						this.appendDayToCalender(whileTime);
						whileTime = whileTime+86400;
						loadedDays++;
					}
					$('.dropdown-toggle').dropdown();//init day dropdowns
					this.loader = setTimeout(calendar.loadEvents, 1000);
			  };
			  	
			  	this.loadWeek = function(startStamp){
			  		//tidy up calendar
					$('.calendarFrame').html('');
					$('.calendarFrame').removeClass('dayView');
					$('.calendarFrame').removeClass('monthView');
					
					//add class for weekview
					$('.calendarFrame').addClass('weekView');
					
					
					
					if(startStamp == undefined){
						var d = new Date(); //now
					}else{
						var d = new Date(startStamp*1000);
					}
					
					this.shownTimeObject = d;
			  		this.updateViewDetail('week', d);
			  		
					var firstSecondOfWeek = this.getMonday(d);
					var lastSecondOfWeek = new Date(((firstSecondOfWeek.getTime()/1000)+604799)*1000);
					
			  		
					
					var loadTime = firstSecondOfWeek.getTime()/1000;
					
					
					var html = '';
					for(var daysLoaded = 0; daysLoaded < 7; daysLoaded++){
						
						this.appendDayToCalender(loadTime);
						loadTime = loadTime+86400;
					}
					this.loader = setTimeout(function() {calendar.loadEvents();}, 1000);
			  		
			  };
			  	
			  	this.loadDay = function(date){
			  		//tidy up calendar
					$('.calendarFrame').html('');
					$('.calendarFrame').removeClass('weeView');
					$('.calendarFrame').removeClass('monthView');
					
					//add class for dayview
					$('.calendarFrame').addClass('dayView');
					
					calendar.toggleListType('day');
					
			  		date.setHours(0);
			  		date.setMinutes(0);
			  		date.setSeconds(0);
			  		date.setMilliseconds(0);
					
					var startStamp = date.getTime()/1000;
					var stopStamp = date.getTime()/1000;
					
					var html = '';
					
					html += '<ul class="eventList">';
					for (var i=0;i<=23;i++){
						html += '<li>'+i+'</li>';
					}
					
					
					
					
			  			var appointments = events.get(startStamp, startStamp+86400);
						var list = '';
					
							if(appointments){
								$.each( appointments, function( key, value ) {
								  if($('#eventDetailDay_'+value.id).length === 0){
								  	
									  var startDate = new Date(value.startStamp*1000);
									  var endDate = new Date(value.stopStamp*1000);
									  
									  
									  var top = ((value.startStamp-startStamp)/3600)*30;
									  
									  var height = ((value.stopStamp-value.startStamp)/3600)*30;
									  
									  
									  
									  list += '<li class="event" data-eventId="'+value.id+'" onclick="$(\'#eventDetail_'+value.id+'\').toggle();" style="top: '+top+'px; height: '+height+'">'+startDate.getHours()+':'+startDate.getMinutes()+'&nbsp;'+value.title+'</li>';
									  
								  }
								 });
							}
							
							html += list;
							
					
					
					html += '</ul>';
					
					$('.calendarFrame').html(html);
					
					clearTimeout(this.loader);
					this.loader = setTimeout(function() {calendar.loadEvents();}, 1000);
					this.updateViewDetail('day', this.shownTimeObject);
			  	};
			  	
			  	this.updateViewDetail = function(type, dateObj){
			  		
			  		if(type == 'month'){
			  			
			  			
				  		$('#calendarView .btn').removeClass('active');
				  		$('#month').addClass('active');
			  			
			  			
			  			$('#calendarViewDetail .btn').unbind('click');
			  			
						$('#calendarViewDetail #prev').click(function(){
							calendar.shownTimeObject.setMonth(calendar.shownTimeObject.getMonth()-1);
			  				calendar.loadMonth(calendar.shownTimeObject);
						});
						
						$('#calendarViewDetail #next').click(function(){
							calendar.shownTimeObject.setMonth(calendar.shownTimeObject.getMonth()+1);
			  				calendar.loadMonth(calendar.shownTimeObject);
							
						});
			  			
						$('#calendarViewDetail #text').text(this.getMonthName(calendar.shownTimeObject.getMonth()));
						
						
			  		}else if(type == 'week'){
			  			
				  		$('#calendarView .btn').removeClass('active');
				  		$('#week').addClass('active');
				  		
						
						
			  			$('#calendarViewDetail .btn').unbind('click');
			  			
						$('#calendarViewDetail #prev').click(function(){
							calendar.shownTimeObject.setSeconds(-(7*86400));
							console.log('prev');
			  				calendar.loadWeek(calendar.shownTimeObject.getTime()/1000);
						});
						
						$('#calendarViewDetail #next').click(function(){
							calendar.shownTimeObject.setSeconds(+(7*86400));
			  				calendar.loadWeek(calendar.shownTimeObject.getTime()/1000);
							
						});
						console.log(calendar.shownTimeObject);
						var nextWeek = new Date(calendar.shownTimeObject.getTime()+(7*86400000));
						
						$('#calendarViewDetail #text').html(calendar.shownTimeObject.getDate()+'.'+calendar.shownTimeObject.getMonth()+1+' - '+nextWeek.getDate()+'.'+nextWeek.getMonth()+1);
						
			  		}else if(type == 'day'){
			  			
				  		$('#calendarView .btn').removeClass('active');
				  		$('#day').addClass('active');
				  		
						$('#calendarViewDetail #text').html(calendar.shownTimeObject.getDate()+'.'+calendar.shownTimeObject.getMonth()+1);
						
						
						
			  			$('#calendarViewDetail .btn').unbind('click');
			  			
						$('#calendarViewDetail #prev').click(function(){
							calendar.shownTimeObject.setSeconds(-86400);
			  				calendar.loadDay(calendar.shownTimeObject);
						});
						
						$('#calendarViewDetail #next').click(function(){
							calendar.shownTimeObject.setSeconds(+86400);
			  				calendar.loadDay(calendar.shownTimeObject);
						});
						
			  		}
			  		
			  		$('#headerAdd').click(function(){
			  			events.addForm(calendar.shownTimeObject.getTime()/1000);
			  		});
			  		
					$('#headerToday').click(function(){
						var d = new Date();
						
						if(calendar.view == 'month'){
							calendar.loadMonth(d);
						}else if(calendar.view == 'week'){
			  				calendar.loadWeek(d.getTime()/1000);
						}else if(calendar.view == 'day'){
							
						}
					});
					
					
			  		$('#calendarView .btn').unbind('click');
			  		
			  		
			  		$('#calendarView #month').click(function(){
			  				calendar.loadMonth(calendar.shownTimeObject);
			  				
			  		});
			  		
			  		$('#calendarView #week').click(function(){
			  			
			  			calendar.loadWeek(calendar.shownTimeObject.getTime()/1000);
			  			
			  		});
			  		
			  		$('#calendarView #day').click(function(){
			  			
			  			calendar.loadDay(calendar.shownTimeObject);
			  			
			  		});
			  		
			  	};
			  	
			  	this.getMonthName = function(month){
					var monthName = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
					return monthName[month];
					
			  	};
			  	
			  	this.loadMonthsIntoSide = function(date){
			  		var d = new Date(date);
			  		d.setMonth(0);
			  		d.setDate(1);
			  		d.setHours(0);
			  		d.setMinutes(0);
			  		d.setSeconds(0);
			  		d.setMilliseconds(0);
			  		
					var monthHTML = '<li class="header">Events</header>';
					
					
					for(var monthCounter=0; monthCounter < 12; monthCounter++){
						var monthClass = '';
						if(monthCounter === month){
							monthClass = 'current';
						}
						monthHTML += '<li class="'+monthClass+'" onclick="calendar.loadMonth('+d.getTime()+');">'+this.getMonthName(monthCounter)+'</li>';
						
						d.setMonth( d.getMonth( ) + 1 );
					}
					
					//apend month and trigger to load month into sidebar
					$('#side #events').html(monthHTML);
					
					$('#months .header').click(function(){
						$('#side #events li').not('.header').slideToggle();
					});
			  		
			  		
			  	};
			  	
			  	this.loadEventsIntoSide = function(date){
			  		console.log('side');
			  		var d = new Date(date);
			  		d.setMonth(0);
			  		d.setDate(1);
			  		d.setHours(0);
			  		d.setMinutes(0);
			  		d.setSeconds(0);
			  		d.setMilliseconds(0);
			  		
			  		//first sec of year
			  		var startStamp = d.getTime()/1000;
			  		
			  		
			  		//last sec of year
			  		d.setFullYear(d.getFullYear()+1);
			  		d.setSeconds(-1);
			  		var stopStamp = d.getTime()/1000;
			  		
			  		
			  			var appointments = events.get(startStamp, stopStamp);
						var list = '';
					
							if(appointments){
								$.each( appointments, function( key, value ) {
									console.log(value);
								  if($('#sideEvent_'+value.id).length === 0){
								  	
									  var startDate = new Date(value.startStamp*1000);
									  var endDate = new Date(value.stopStamp*1000);
									  list += '<li data-eventId="'+value.id+'" onclick="events.show('+value.id+', '+privacy.authorize(value.privacy, value.user)+');" id="sideEvent_'+value.id+'">'+calendar.beautifyDate(startDate.getDate())+'.'+calendar.beautifyDate(startDate.getMonth()+1)+'&nbsp;'+value.title+'</li>';
									
								  }
								 });
							}
					//apend month and trigger to load month into sidebar
					$('#side #events').append(list);
					$('#events .header').unbind('click');
					$('#events .header').click(function(){
						$('#side #events li').not('.header').not('.eventDetail').slideToggle();
					});
			  		
			  		
			  	};
			  	
			  	this.beautifyDate =function(value){
			  		if(value < 10){
			  			value = '0'+value;
			  		}
			  		return value;
				};
				
				this.getAppointmentsForDay = function(time){
					var array = [];
					array[0] = 'startStamp';
					
					return array;
				};
				
				
				this.getNextMonth = function(month){
					var ret;
					switch(month){
						default: 
							ret = month+1;
						break;
						case 11:
							ret = 0;
						break;
							
					}
					return ret;
				};
				
				this.getLastMonth = function(month){
					var ret;
					switch(month){
						default: 
							ret = month-1;
						break;
						case 0:
							ret = 11;
						break;
							
					}
					return ret;
				};
				this.getMonday = function(d){
				  d = new Date(d);
				  var d = new Date(d.setHours(0));
				  var d = new Date(d.setMinutes(0));
				  var d = new Date(d.setSeconds(0));
				  var d = new Date(d.setMilliseconds(0));
				  var day = d.getDay(),
				      diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
				  return new Date(d.setDate(diff));
				};
				
				
			  };
			  var events = new function(){
			  	
			  	this.getData = function(eventId){
			  		var res;
			  		$.ajax({
				      url:"api.php?action=getEventData",
				      async: false,  
					  type: "POST",
					  data: { 
					  	eventId : eventId
					  	 },
				      success:function(data) {
				         res = $.parseJSON(data); 
				      }
				   });
				   return res;
			  	};
			  	
			  	this.create = function(startStamp, stopStamp, title, place, privacyShow, privacyEdit){
			  		
				    $.ajax({
				      url:"doit.php?action=loadPrivacySettings",
				      async: false,  
					  type: "POST",
					  data: { 
					  	startStamp : startStamp,
					  	stopStamp : stopStamp,
					  	title : title,
					  	place : place,
					  	privacyShow : privacyShow,
					  	privacyEdit : privacyEdit
					  	 },
				      success:function(data) {
				         result = data; 
				      }
				   });
				   
			  	};
			  	this.addForm = function(startstamp){
			  		var d = new Date(startstamp*1000);
			  		console.log(d.getMonth());
			  		var formattedDate = calendar.beautifyDate((d.getMonth())+1)+'/'+calendar.beautifyDate(d.getDate())+'/'+d.getFullYear();
			  		
			  		var content  = '<table class="formTable">';
			  				content += '<form id="createEvent" method="post">';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Title:';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += '<input type="text" name="title" id="eventTitle">';
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Place:';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += '<input type="text" name="place" id="eventPlace">';
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Day:';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += '<input type="text" name="startDate" id="startDate" class="startDate datepicker" value="'+formattedDate+'" style="width: 72px;">';
					  		    	content += '&nbsp;<input type="text" name="startTime" id="startTime" class="startTime eventTime" value="15:30" style="width: 37px;"><span class="endDate">&nbsp;to&nbsp;</span>';
					  		    	content += '&nbsp;<input type="text" name="endTime" id="endTime" class="endTime eventTime" value="16:30" style="width: 37px;">';
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'All-Day:';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += '<input type="checkbox" name="allDay" id="eventAllDay" value="true" onclick="$(\'.eventTime\').toggle();">';
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
			  			$('#createEvent').submit();
  					};
  					
  					//create modal
              		modal.create('Create New Event', content, [onSubmit, 'Save']);
              		
              		//init datepicker in modal
              		$('.datepicker').datepicker();
              		
              		$('#createEvent').submit(function(e){
              			e.preventDefault();
              			console.log($(this).serialize());
              			if($('#eventTitle').val().length > 0 && $('#startDate').val().length > 0 && $('#endTime').val().length > 0){
              				
	              			$.post("api.php?action=createEvent",$(this).serialize(),function(data){
					            if(data.length === 0){
					            	calendar.loadEvents();
					            	calendar.loadEventsIntoSide(new Date(calendar.shownTimeObject.getFullYear(), calendar.shownTimeObject.getMonth(), 1, 0, 0, 0));
					            	jsAlert('','The Event has been added');
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
			  	
			  	this.show = function(eventId, editable){
			  		var eventData = events.getData(eventId);
			  		
			  		var startDate = new Date(eventData.startStamp*1000);
			  		var  stopDate = new Date(eventData.stopStamp*1000);
			  		console.log('hours:'+startDate.getHours()+'	min:'+startDate.getMinutes());
			  		console.log('hours:'+stopDate.getHours()+'	min:'+stopDate.getMinutes());
			  		
			  		
			  		var allDay, 	//contains checkbox or check-image
			  			editableToken;
			  		
			  		//generate formstuff from eventdata
			  		if(editable){
			  			editableToken = 'contentEditable';
			  			var checked;
			  			if(editable == 'true')
			  				checked = 'checked="checked"';
			  			
			  			allDay = '<input type="checkbox" name="allDay" id="eventAllDay" value="true" onclick="$(\'.eventTime\').toggle();" '+checked+'>';
			  		}else{
			  			if(editable != 'true')
			  				allDay = 'x';
			  			else
			  				allDay = '';
			  			
			  		}
			  		
			  		var startTime = calendar.beautifyDate(startDate.getHours())+':'+calendar.beautifyDate(startDate.getMinutes());
			  		var stopTime  = calendar.beautifyDate(stopDate.getHours())+':'+calendar.beautifyDate(stopDate.getMinutes());
			  		
			  		var content  = '<table class="formTable">';
			  				content += '<form id="updateEvent" method="post">';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Title:';
					  		    	content += '</td>';
					  		    	content += '<td '+editableToken+' id="title">';
					  		    	content += eventData.title;
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Place:';
					  		    	content += '</td>';
					  		    	content += '<td '+editableToken+' id="place">';
					  		    	content += eventData.place;
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Day:';
					  		    	content += '</td>';
					  		    	content += '<td><span id="startDate" '+editableToken+' class="datepicker">';
					  		    	content += startDate.getMonth()+1+'/'+startDate.getDate()+'/'+startDate.getFullYear();
					  		    	content += '</span>&nbsp;<span id="startTime" '+editableToken+'>'+startTime+'</span>&nbsp;to&nbsp;<span '+editableToken+' id="stopDate" class="datepicker">';
					  		    	content += stopDate.getMonth()+1+'/'+stopDate.getDate()+'/'+stopDate.getFullYear();
					  		    	content += '</span>&nbsp;<span id="stopTime" '+editableToken+'>'+stopTime+'</span>';
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'All-Day:';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += allDay;
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>';
					  		    	content += 'Privacy:';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += privacy.show(eventData.privacy, editable);
					  		    	content += '</td>';
					  		    content += '</tr>';
			  		    	content += '</form>';
			  		    content += '</table>';
			  		var onSubmit = function() {
			  			$('#updateEvent').submit();
  					};
  					
  					//create modal
              		modal.create('Event '+eventData.title, content, [onSubmit, 'Save']);
              		
              		//init datepicker in modal
              		$('.datepicker').datepicker();
              		
              		$('#updateEvent').submit(function(e){
              			e.preventDefault();
              			if($('#title').text().length > 0 && $('#startDate').text().length > 0){
              				
              				var privacy = $('.blueModal .privacySettings  :input').serialize();
              				
              				var searchString  = 'eventId='+encodeURIComponent(eventId);
              					searchString += '&title='+encodeURIComponent($('.blueModal #title').text());
              					searchString += '&place='+encodeURIComponent($('.blueModal #place').text());
              					searchString += '&startDate='+encodeURIComponent($('.blueModal #startDate').text());
              					searchString += '&startTime='+encodeURIComponent($('.blueModal #startTime').text());
              					searchString += '&endTime='+encodeURIComponent($('.blueModal #stopTime').text());
              					searchString += '&allDay='+encodeURIComponent($('.blueModal #eventAllDay').is(':checked'));
              					searchString += '&'+privacy;
              					
	              			$.post("api.php?action=updateEvent",searchString,function(data){
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
			  	
			  	this.get = function(startStamp, stopStamp, privacy){
			  		var result;
				    $.ajax({
				      url:"api.php?action=getEvents",
				      async: false,  
					  type: "POST",
					  data: { 
					  	 startStamp: startStamp,
					  	 stopStamp: stopStamp,
					  	 privacy: privacy
					  	 },
				      success:function(data) {
				      	if(data){
				        	result = $.parseJSON(data); 
				      	}
				      }
				   });
				   
				   return result;
			  	};
			  };
			  
			  var privacy = new function(){
			  	
			  	this.load = function(selector, val, editable){
			  		if(typeof editable == 'undefined')
			  			editable = false;
			  		
			  		
			  		$.post("doit.php?action=loadPrivacySettings", {
	                       val:val, editable : editable
	                       }, function(result){
		                   		$(selector).html(result);
	                       }, "html");
			  		
			  		
			  	};
			  	this.show = function(val, editable){

			  		if(typeof editable == 'undefined')
			  			editable = false;
			  			
				    var result="";
				    
				    $.ajax({
				      url:"doit.php?action=loadPrivacySettings",
				      async: false,  
					  type: "POST",
					  data: { val : val, editable : editable },
				      success:function(data) {
				         result = data; 
				      }
				   	});
				   return result;
			  	};
			  	
			  	//checks if user is authorized, to edit an item with privacy.
			  	this.authorize = function(privacy, author){
			  		if(author == localStorage.currentUser_userid)
			  			return true;
			  			
			  		var result;
				    $.ajax({
				      url:"api.php?action=authorize",
				      async: false,  
					  type: "POST",
					  data: { 
					  	 privacy: privacy,
					  	 author: author
					  	 },
				      success:function(data) {
				      	if(data){
				        	result = data;
				      	}
				      }
				   });
				   if(parseInt(result) === 1)
				   		return true;
				   else
				   		return false;
			  	};
			  	
			  };
			  var groups = new function(){
			  	
			  	this.get = function(){
			  		
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=getGroups",
				      async: false,  
					  type: "POST",
					  data: { val : 'val' },
				      success:function(data) {
				         result = data; 
				      }
				   	});
				   	console.log(typeof result);
				   	if(result != null){
				   		return $.parseJSON(result);
				   	}
			  	};
			  	this.getTitle = function(groupId){
			  		
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=getGroupTitle",
				      async: false,  
					  type: "POST",
					  data: { groupId : groupId },
				      success:function(data) {
				         result = data; 
				      }
				   	});
				   	if(result){
				   		return result;
				   	}
			  		
			  	};
			  	
			  };


              function applicationOnTop(id){
                  $(".fenster").css('z-index', 999);
                  $("#"+id+"").css('z-index', 9999);
                  $("#"+id+"").css('position', 'absolute');
              }

              function showApplication(id){
                  applicationOnTop(id);
                  $("#" + id +"").show();
              }
              function hideApplication(id){
                  $("#" + id +"").hide();
              }
              function toggleApplication(id){
                  $("#" + id +"").toggle();
              }
              
              function moduleFullscreen(moduleId){
              	
              	//$('#'+moduleId+' .fullScreenIcon').html('rofl');
              	$('#'+moduleId+' .fullScreenIcon').attr("onClick","moduleReturnFullScreen('"+moduleId+"')");
              	
              	 window.fullScreenOldX = $('#'+moduleId).width();
              	 window.fullScreenOldY = $('#'+moduleId).height();
              	 var position = $('#'+moduleId).position();
              	 window.fullScreenOldMarginX = position.left;
              	 window.fullScreenOldMarginY = position.top;
                  
                  var fullscreenCss = {
                        'position' : 'absolute',
                        'top' : '5px',
                        'bottom' : '10px',
                        'left' : '5px',
                        'right' : '5px',
                        'width' : 'auto',
                        'height' : 'auto'
                       };
                  $("#" + moduleId + "").css(fullscreenCss);
              }
              
              function moduleReturnFullScreen(moduleId){
              	$('#'+moduleId+' .fullScreenIcon').attr("onClick","moduleFullscreen('"+moduleId+"')");
                  var returnFullScreenCSS = {
                        'position' : 'absolute',
                        'top' : window.fullScreenOldMarginY,
                        'left' : window.fullScreenOldMarginX,
                        'width' : window.fullScreenOldX,
                        'height' : window.fullScreenOldY
                       };
                  $("#" + moduleId + "").css(returnFullScreenCSS);
                  
              	
              }
              
              function jsAlert(type, message){
	              	var alertClass;
	              	if(empty(type)){
	              		alertClass = 'alert-info';
	              	}else if(type == 'success'){
	              		alertClass = 'alert-success';
	              	}else if(type == 'error'){
	              		alertClass = 'alert-error';
	              	}
	              	
	              	$('#alerter').append('<div class="alert '+alertClass+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>');
	              	$('.alert').delay(8000).fadeOut(function(){
	              		$(this).remove();
	              	});
              }
              
              var files = new function(){
              	
              	this.fileIdToFileTitle = function(fileId){
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=fileIdToFileTitle",
				      async: false,  
					  type: "POST",
					  data: { fileId : fileId },
				      success:function(data) {
				         result = data; 
				      }
				   });
				   return result;
              	};
              	
              };
              
              var elements = new function(){
              	
              	this.elementIdToElementTitle = function(elementId){
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=elementIdToElementTitle",
				      async: false,  
					  type: "POST",
					  data: { elementId : elementId },
				      success:function(data) {
				         result = data; 
				      }
				   });
				   return result;
              	};
              	
              };
              
              var folders = new function(){
              	
              	this.folderIdToFolderTitle = function(folderId){
				    var result="";
				    
				    $.ajax({
				      url:"api.php?action=folderIdToFolderTitle",
				      async: false,  
					  type: "POST",
					  data: { folderId : folderId },
				      success:function(data) {
				         result = data; 
				      }
				   });
				   return result;
              	};
              	
              };
              
              var filesystem =  new function() {
              	
              	this.openShareModal = function(type, typeId){
              		
              		var title;
              		var content;
              		var kickstarterURL;
              		var embedURL;
              		switch(type){
              			case 'file':
              				var fileTitle = files.fileIdToFileTitle(typeId);
              				title = 'Share "'+fileTitle+'"';
              				kickstarterURL = sourceURL+'/out/kickstarter/files/?id='+typeId;
              				embedURL = sourceURL+'/out/?file='+typeId; //should be the same like fileBrowserURL 
              			break;
              			case 'element':
              				var elementTitle = elements.elementIdToElementTitle(typeId);
              				title = 'Share "'+elementTitle+'"';
              				kickstarterURL = sourceURL+'/out/kickstarter/elements/?id='+typeId;
              				embedURL = sourceURL+'/out/?element='+typeId; //should be the same like fileBrowserURL 
              			break;
              		}
              		
              		var facebook = 'window.open(\'http://www.facebook.com/sharer/sharer.php?u='+kickstarterURL+'&t='+fileTitle+'\', \'facebook_share\', \'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no\');';
              		var twitter = 'window.open(\'http://www.twitter.com/share?url='+kickstarterURL+'\', \'twitter_share\', \'height=320, width=640, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no\');';
					var googleplus = "window.open('https://plus.google.com/share?url="+kickstarterURL+"','', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;";
              		
              		content = '<ul class="shareList">';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #facebook\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Facebook <img src="gfx/startPage/facebook.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #twitter\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Twitter <img src="gfx/startPage/twitter.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #googleplus\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Google+ <img src="gfx/startPage/googleplus.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #embed\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Embed Code <img src="gfx/startPage/wikipedia.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #url\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">URL <img src="gfx/startPage/wikipedia.png"></li>';
              		content += '</ul>';
              		
              		content += '<ul class="shareBox">';
              			content += '<li id="facebook"><center><a target="_blank" href="#" onclick="'+facebook+'" class="btn btn-success"><img src="gfx/startPage/facebook.png" height="20"> Click Here To Share</a></center></li>';
              			content += '<li id="url"><center><textarea>'+kickstarterURL+'</textarea></center>Just place the HTML code for your Filebrowser wherever<br> you want the Browser to appear on your site.</li>';
              			content += '<li id="embed"><center><textarea><iframe src="'+embedURL+'"></iframe></textarea></center>Just place the HTML code for your Filebrowser wherever<br> you want the Browser to appear on your site.</li>';
              			content += '<li id="googleplus"><center><a href="#" onclick="'+googleplus+'" class="btn btn-success"><img src="gfx/startPage/googleplus.png" height="20"> Click Here To Share</a></center></li>';
              			content += '<li id="twitter"><center><a href="#" onclick="'+twitter+'" class="btn btn-success"><img src="gfx/startPage/twitter.png" height="20"> Click Here To Share</a></center></li>';
              		content += '</ul>';
              		
              		
              		modal.create(title, content);
              	};
              	
              };
              
              
              var modal =  new function() {
			    this.html;
			    this.create = function (title, content, action) {
			    	this.html = '';
			    	this.html += '<div class="blueModal border-radius container">';
	            		this.html += '<header>';
	            			this.html += title;
	            			this.html += '<a class="modalClose" onclick="$(\'.blueModal\').remove();">X</a>';
	            		this.html += '</header>';
	            		this.html += '<div class="content">';
	            		this.html += content;
	            		this.html += '</div>';
	            		this.html += '<footer>';
	            		
	                 		this.html += '<a href="#" onclick="$(\'.blueModal\').remove(); return false;" class="btn pull-left">Close</a>';
	                 		if(typeof action !== 'undefined'){
	                			this.html += '<a href="#" id="action" class="btn btn-primary pull-right">&nbsp;&nbsp;'+action[1]+'&nbsp;&nbsp;</a>';
	                 		}
	            		
	            		this.html += '</footer>';
	            		
	            	this.html += '</div>';
            		$('.blueModal').remove();
            		$('#popper').append(this.html);
            		
            		if(typeof action !== 'undefined'){
	            		$('.blueModal #action').click(function(){
	            			action[0]();
	            		});
            		}
			    };
			};
       
       
//encryption functions

	function symEncrypt(key, message){
		var msg;
		var output;
	    msg = CryptoJS.AES.encrypt(message, key);
	    return String(msg);
	}

	function symDecrypt(key, message){
            var msg;
	    msg = CryptoJS.AES.decrypt(message, key);
	    var output = CryptoJS.enc.Utf8.stringify(msg);
	    return String(output);
	}
	
	function generateAsymKeyPair(){
      var keySize = 1024;
      var crypt;
      var ret = [];
      crypt = new JSEncrypt({default_key_size: keySize});

        crypt.getKey(function () {
      		ret['privateKey'] = crypt.getPrivateKey();
      		ret['publicKey'] = crypt.getPublicKey();
        });
      
      
	}
	
	function asymEncrypt(publicKey, message){
		
		  var encrypt = new JSEncrypt();
		  
          encrypt.setPublicKey(publicKey);
          
          return encrypt.encrypt(message);
          
	}
	
	function asymDecrypt(privateKey, encryptedMessage){
            var message;
            var decrypt = new JSEncrypt();
            decrypt.setPrivateKey(privateKey);
            message = decrypt.decrypt(encryptedMessage);
            return message;
	}
	
	function getSalt(type, itemId, key){
			var encryptedSalt = '';
			$.ajax({
			  url:"api.php?action=getSalt",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     encryptedSalt = data; 
			  }
			});
	    	var salt = symDecrypt(key, encryptedSalt); //encrypt salt using key
	    	return salt;
		
	}
	
	function createSalt(type, itemId, receiverType, receiverId, salt){
			var ret;
			$.ajax({
			  url:"api.php?action=createSalt",
			  async: false,  
			  type: "POST",
			  data: { type: type, itemId: itemId, receiverType: receiverType, receiverId: receiverId, salt: salt },
			  success:function(data) {
			     ret = data; 
			  }
			});
			
			return ret;
	}
	
	var hash = new function(){
		this.MD5 = function(string){
			var hash = CryptoJS.MD5(string);
			return hash.toString(CryptoJS.enc.Hex);
		};
		this.SHA512 = function(string){
			var hash = CryptoJS.SHA512(string);
			return hash.toString(CryptoJS.enc.Hex);
		};
	};
	
	//different to the one in the guest.js (the one in the guest.js needs to be deleted and the functions on offline page(login, updatepass need to be checked...))
	var cypher = new function(){
	
	this.generateRand = function(){
		return hash.SHA512(randomString(64, '#aA'));
	};
	
	this.getKey = function(type, typeId, shaPass){
		var salt = getSalt(type, typeId, shaPass);
	    var response = hash.SHA512(shaPass+salt);
	    return response;
	};
	
	/*returns pwHash/salt and keyHash/salt. for a user*/
	this.createKeysForUser = function(pass){
		var shaPass = hash.SHA512(pass);
		
		var authSaltDecrypted = cypher.generateRand();
		var keySaltDecrypted = cypher.generateRand();
		
	    var authHash = hash.SHA512(shaPass+authSaltDecrypted);
	    var keyHash = hash.SHA512(shaPass+keySaltDecrypted);
		
		var authSaltEncrypted = symEncrypt(shaPass, authSaltDecrypted);
		var keySaltEncrypted = symEncrypt(shaPass, keySaltDecrypted);
		
		var result = new Object();
			result['authHash'] = authHash;
			result['keyHash'] = keyHash;
			result['authSaltEncrypted'] = authSaltEncrypted;
			result['keySaltEncrypted'] = keySaltEncrypted;
		
		return result;
		
	};
	
	this.getPrivateKey = function(type, itemId){
	    	var privateKey;
            var index = type+'_'+itemId;
            if(typeof privateKeys[index] === 'undefined'){
                console.log(index);
                    var encryptedKey = '';
			$.ajax({
			  url:"api.php?action=getPrivateKey",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     encryptedKey = data; 
			  }
			});
		
				var shaPass = localStorage.currentUser_shaPass;
			
			var salt = getSalt('privateKey', itemId, shaPass);
			console.log(salt);
		    var keyHash = hash.SHA512(shaPass+salt);
		    console.log(keyHash);
			
	    	privateKey = symDecrypt(keyHash, encryptedKey); //encrypt private Key using password
                privateKeys[index] = privateKey;
            }else{
                
                privateKey = privateKeys[index];
                
            }
	    	return privateKey;
	    };
};

	var sec =  new function() {
		
				//standard password cypher used in processRegistration(), login() and updatePassword();
			    this.passwordCypher = function (password, type, itemId, salt) {
			    	
			    	//md5 has to be replaced with more secure hashing function
					var passwordHashMD5  = hash.MD5(password);
					
					if(type.length > 0){
						var salt = getSalt(type, itemId, passwordHashMD5); //get auth salt, using md5 hash as key
					}
					
				   	
				   	var passwordHash = hash.SHA512(salt+passwordHashMD5);;
				   	
    				var keyHash =  hash.SHA512(passwordHashMD5+salt);
				    
				    return [passwordHash, passwordHashMD5, keyHash, salt];
			    };
			    
			    this.getPrivateKey = function(type, itemId, salt, password){
			    	return getPrivateKey(type, itemId, salt, password);
			    };
			    
			    this.symEncrypt = function(key, message){
			    	
			    	return symEncrypt(key,message);
			    	
			    };
			    this.symDecrypt = function(key, message){
			    	
			    	return symDecrypt(key,message);
			    	
			    };
			    
			    this.randomString = function(){
			    	return hash.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
			    	
			    };
		};
	
    function getPrivateKey(type, itemId, salt, password){
            
	    var privateKey;
            var index = type+'_'+itemId;
            if(typeof privateKeys[index] === 'undefined'){
                console.log(index);
                    var encryptedKey = '';
			$.ajax({
			  url:"api.php?action=getPrivateKey",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     encryptedKey = data; 
			  }
			});
		
			if(typeof password === 'undefined'){
				var password = localStorage.currentUser_passwordHashMD5;
			}
				console.log(password);

		    var keyHash = hash.SHA512(password+salt);
			
	    	privateKey = symDecrypt(keyHash, encryptedKey); //encrypt private Key using password
                privateKeys[index] = privateKey;
            }else{
                
                privateKey = privateKeys[index];
                
            }
	    return privateKey;
	}
	
	function getPublicKey(type, itemId){
			var key = '';
			$.ajax({
			  url:"api.php?action=getPublicKey",
			  async: false,  
			  type: "POST",
			  data: { type : type, itemId : itemId },
			  success:function(data) {
			     key = data; 
			  }
			});
	    	return key;
	}
	
	
    function storeMessageKey(messageId, key){
        messageKeys[messageId] = key;
    }
    
    function getStoredKey(messageId){
        return messageKeys[messageId];
    }
    
    function isStored(messageId){
        if(messageKeys[messageId] !== undefined){
            return true;
        }else{
            return false;
        }
    }
    
    
              
//general functions
        

//       function mousePop(type, id, html){
//           if($("#mousePop_"+type+id).length == 0){
//               
//            $("#popper").load("doit.php?action=mousePop&type=&id=&html", { 
//                'type': type,
//                'id':id,
//                'html':html
//            });
//           }
//       }

	  function empty(value){
	  	if(value.length == 0) {
	  		return true;
	  	}else{
	  		return false;
	  	}
	  }
	  
	  //updates 
      function updatePictureStatus(userId, borderColor){
          $('.userPicture_'+userId).css('border-color', borderColor);
      }

      function showContent(content, title){
        showApplication('reader');
        createNewTab('reader_tabView', title,'','showContent.php?content='+content,true);return true;
          
      }
      
      function mousePop(type, id, html){
            $('.mousePop').remove();
            if($('#mousePop_'+type+id).length == 0){   
            $("#popper").load("doit.php?action=mousePop&type=&id=&html", {
                'type': type,
                'id':id,
                'html':html
            });
            }
       }
       
       
       function getCaretPosition(editableDiv) {
            var caretPos = 0, containerEl = null, sel, range;
            if (window.getSelection) {
                sel = window.getSelection();
                if (sel.rangeCount) {
                    range = sel.getRangeAt(0);
                    if (range.commonAncestorContainer.parentNode == editableDiv) {
                        caretPos = range.endOffset;
                    }
                }
            } else if (document.selection && document.selection.createRange) {
                range = document.selection.createRange();
                if (range.parentElement() == editableDiv) {
                    var tempEl = document.createElement("span");
                    editableDiv.insertBefore(tempEl, editableDiv.firstChild);
                    var tempRange = range.duplicate();
                    tempRange.moveToElementText(tempEl);
                    tempRange.setEndPoint("EndToEnd", range);
                    caretPos = tempRange.text.length;
                }
            }
            return caretPos;
        }
       
       
       
       
       function getElementsByClassName(node,classname) {
		  if (node.getElementsByClassName) { // use native implementation if available
		    return node.getElementsByClassName(classname);
		  } else {
		    return (function getElementsByClass(searchClass,node) {
		        if ( node == null )
		          node = document;
		        var classElements = [],
		            els = node.getElementsByTagName("*"),
		            elsLen = els.length,
		            pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)"), i, j;
		
		        for (i = 0, j = 0; i < elsLen; i++) {
		          if ( pattern.test(els[i].className) ) {
		              classElements[j] = els[i];
		              j++;
		          }
		        }
		        return classElements;
		    })(classname, node);
		  }
		}


                //loads URL into an iFrame
                function loadIframe(iframeName, url) {
                    $('#' + iframeName).html('');
                    var $iframe = $('#' + iframeName);
                    if ( $iframe.length ) {
                        $iframe.attr('src',url);   
                        return false;
                    }
                    return true;
                }
//api connection stuff
	function useridToUsername(id){
		if(usernames[id] == undefined){
			
		    var result="";
		    $.ajax({
		      url:"api.php?action=useridToUsername",
		      async: false,  
			  type: "POST",
			  data: { request : id },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   usernames[id] = result;
		   return result;
		}else{
			return usernames[id];
		}
		
	}
	
	function usernameToUserid(username){
			
		    var result="";
		    $.ajax({
		      url:"api.php?action=usernameToUserid",
		      async: false,  
			  type: "POST",
			  data: { username : username },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   usernames[result] = username;
		   return result;
		
	}
	
	function getUserCypher(id){
		var result="";
		    $.ajax({
		      url:"api.php?action=getUserCypher",
		      async: false, 
			  type: "POST",
			  data: { userid : id },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   
		   return result;
	}
	
	function getUserSalt(id){
		//returns user salt (aes encrypted with pw hash)
		
		
		var result="";
		    $.ajax({
		      url:"api.php?action=getUserSalt",
		      async: false, 
			  type: "POST",
			  data: { userid : id },
		      success:function(data) {
		         result = data; 
		      }
		   });
		   
		   return result;
	}
                
//reload


//feed
	function feedLoadMore(destination ,type, user, limit){
	    $.get("doit.php?action=feedLoadMore&user="+user+"&limit="+limit+"&type="+type,function(data){
	    	$(destination).append(data);
		},'html');
	}
       
       
    function reloadFeed(type){
        console.log('reloadFeed - initialised...');
        if(type === "friends"){
            $.post('api.php?action=checkForFeeds&type=friends', function(data) {
                console.log('reloadFeed - check for new feeds');
                if(data === "1"){
                    console.log('reloadFeed - no new feeds');
                }else{
                    console.log('reloadFeed - loading new feeds..');
                    
                    
                    $(".feedMain").slideDown("200", function () {
                        $(this).load("doit.php?action=reloadMainFeed");
                        console.log('reloadFeed - new feed loaded');
                    });
                }
            });
        }
    }

//filesystem
	function openUploadTab(element){
	
        showApplication('filesystem');
        createNewTab('fileBrowser_tabView', 'Upload File','','modules/filesystem/upload.php?element='+element,true);return true;
	}
	
	function initUploadify(id, uploader, element, timestamp, token){
		
	    $(function() {
	            $(id).uploadify({
	                    'formData'     : {
	                            'timestamp' : timestamp,
	                            'token'     : token,
	                            'element'     : element
	                    },
	                    'swf'      : 'inc/plugins/uploadify/uploadify.swf',
	                    'uploader' : uploader,
				        'onUploadSuccess' : function(file, data, response) {
				        	
				        	if(response){
				        		eval(data); //no esta bien! que?
				        	}
				        },
	                    'onUploadError' : function(file, errorCode, errorMsg, errorString) {
	                        alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
	                    }
	            });
	    });
                                
	}
	
//reader
    function toggleProfileTabs(id){
        $(".profileSlider").hide();
        $("#" + id + "").slideDown();
    }                
    function toggleGroupTabs(id){
        $(".groupSlider").hide();
        $("#" + id + "").slideDown();
    }


    function openFolder(folderId){
        showApplication('filesystem');
        addAjaxContentToTab('Universe', 'modules/filesystem/fileBrowser.php?reload=1&folder='+folderId);
        return false;
        
    }
    
    function openElement(elementId, title){
        showApplication('filesystem');
        createNewTab('fileBrowser_tabView', title,'','modules/filesystem/showElement.php?element='+elementId,true);return true;
    }

    function openFile(type, typeId, title, typeInfo, extraInfo1, extraInfo2){
        
        title = 'Open '+title;
        
        //bring reader to front
        showApplication('reader');
        
        
        //Link types
        if(type == 'youTube'){
        	if(extraInfo1 == undefined){
        		var extraInfo1 = '';
        	}
        	if(extraInfo2 == undefined){
        		var extraInfo2 = '';
        	}
        	if(typeInfo == undefined){
        		var typeInfo = '';
        	}
        	
        	var playlist = extraInfo1;
        	var row = extraInfo2;
        	var vId = typeInfo;
        	var linkId = typeId;
        	if(linkId.length == 0){
            	createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&typeInfo='+vId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1',true);	
        	}else{
        		
            	createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&typeInfo='+vId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1',true);
            
        	}return false;
        }
        
        if(type == 'RSS'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=RSS&linkId='+typeId,true);
            return false;
        }
        
        if(type == 'wikipedia'){
        	//typeId needs to be changed to title
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=wiki&title='+typeId,true);
            return false;
        }
        
        //real files
        if(type == 'UFF'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=UFF&fileId='+typeId,true);
            return false;
        }
        if(type == 'document' ||type == 'application/pdf' ||type == 'text/plain'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?fileId='+typeId,true);
            return false;
        }
        if(type == 'video' ||type == 'video/mp4' ||type == 'video/quicktime'  ){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=video&fileId='+typeId,true);
            return false;
        }
        if(type == 'audio' ||type == 'audio/wav' ||type == 'audio/mpeg'  ){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=audio&fileId='+typeId,true);
            return false;
        }
        if(type == 'image/png' ||type == 'image/jpeg' || type == 'image'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=image&fileId='+typeId,true);
            return false;
            
        }else{
            alert(type);
            return false;
        }
        return false;
    }
    
    //zoom functions for pictures
    function zoomIn(element){
       var PictureWidth = $("#viewedPicture_"+element).width();
       var newWidth = PictureWidth*1.25;
       $("#viewedPicture_"+element).css("width", newWidth);
    }
    
    function zoomOut(element){
       var PictureWidth = $("#viewedPicture_"+element).width();
       var newWidth = PictureWidth/1.25;
       $("#viewedPicture_"+element).css("width", newWidth);
    }
    
	//UFF
		//what you see is what you get            
		function initWysiwyg(id, readOnly){
		    if(readOnly == 'false'){
		        readOnly = false;
		    }
		    if(readOnly == 'true'){
		        readOnly = true;
		    }
		    
		    var config = {
		        
			extraPlugins: 'autogrow',
		        // toolbarGroups: [
		                                    // { name: 'document',	   groups: [ 'mode', 'document' ] },			
		                                    // { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },			
		                                    // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		                                    // { name: 'links' }],
		        removePlugins: 'resize',
		        readOnly: readOnly,
		        autoGrow_onStartup: true,
		                    on: {
		                        instanceReady: function() {
		
		                                    //add eventlistener for onchange
		                                    this.document.on("keyup", function () {
		
		                                        //if changed update file
		                                        // ich muss ein lastudated feld zur db und eine javascript-lastupdated variable erstellen, um konflike zu vermiden
		                                        var input = $('.uffViewer_'+id).val();
		                                        $.post("doit.php?action=writeUff", {
		                                            id:id,
		                                            input:input
		                                            });
		                                    });
		                        }
		                    }
		            };
		
		    $('.uffViewer_'+id).ckeditor(config);
		}
		
		    
		
		function initUffReader(id, content, readOnly){
		    initWysiwyg(id, readOnly);
		    
		    $('.uffViewer_'+id).val(content);
		}
    
    
    //opens articles out of the universe wiki
    //located in reader cause it will be placed there in future
    function openUniverseWikiArticle(title){
    	openURL("http://wiki.universeos.org/index.php?title="+title, title);
    }
    
    function openURL(url, title){
    		url = encodeURI(url);
    		url = 'modules/reader/browser/?url='+url;
            createNewTab('reader_tabView',title,'',url,true);
            showApplication('reader');   
            return false;
    }
    
    
//IM CHAT  
//IM CHAT  
//IM CHAT

       function openChatDialoge(username){
            showApplication('chat');   
            
            	//check if dialoge allready exists
                if($("#test_"+ username +"").length == 0){
                	
                	userid = usernameToUserid(username);
                    createNewTab('chat_tabView1',username,'',"modules/chat/chatreload.php?buddy="+username+"",true);
                    
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
       
    function universeText(str){
    	//http://growingtech.blogspot.de/2012/06/replace-smiley-code-with-images-in-chat.html
    	var replacement = { 
    		":(": "<a class=\"smiley smiley1\"></a>",
    		":|": '<a class="smiley smiley2"></a>',
    		";)": '<a class="smiley smiley3"></a>',
    		":P": '<a class="smiley smiley4"></a>',
    		":D": '<a class="smiley smiley5"></a>',
    		":)": '<a class="smiley smiley6"></a>',
    		":(": '<a class="smiley smiley7"></a>',
    		":-*": '<a class="smiley smiley8"></a>',
    		
    	};
    	var string = str;
	    string = escape(string);
	    for (var val in replacement)
	        string = string.replace(new RegExp(escape(val), "g"), replacement[val]);
	        string.replace(/\[itemthumb type=(\S*) typeId=(\S*)]/g, '<a href="$1">$2<\/a>');
	    string = unescape(string);
	    return string;
    }
    
       
    function addStrToChatInput(buddy, string){
        $('#chatInput_'+buddy).val($('#chatInput_'+buddy).val() + string);
    }
              
    function chatSetKey(userid){

		if(localStorage.key[userid]){
	  		jsAlert('', 'The key already has been set.');
		}else{
			localStorage.key[userid] = $('#chatKeyInput_'+userid).val();
			$('#chatCryptionMarker_'+userid).val('true');
		  	$('#chatKeySettings_'+userid).html('<a href="#" onclick="chatDeactivateKey(\''+userid+'\'); return false;">deactivate key</a>');
			
	  		jsAlert('', 'The key for your buddy '+userid+' has been set.');
			
            chatDecrypt(userid);
            
            $('#toggleKey_'+userid+' .lockIcon').addClass('locked');
            $('#chatKeySettings_'+userid).hide();
            
		}
    }
    
    function chatDeactivateKey(userid){
    	localStorage.key[userid] = '';
			$('#chatCryptionMarker_'+userid).val('false');
		$('#chatKeySettings_'+userid).html('<form action="" method="post" target="submitter" onsubmit="chatSetKey(\''+userid+'\'); return false;"><input type="password" name="key" placeholder="type key" id="chatKeyInput_'+userid+'"></form>');
	    jsAlert('', 'The key has been removed');
        $('#toggleKey_'+userid+' .lockIcon').removeClass('locked');
    }
    
    function updatePassword(oldPassword, newPassword, userid){
    	
		//cypher old password
		var shaPass_old = hash.SHA512(oldPassword);
		var passwordHash_old = cypher.getKey('auth', userid, shaPass_old);
		var privateKey = cypher.getPrivateKey('user', localStorage.currentUser_userid);
		console.log(privateKey);            
		var keysNew = cypher.createKeysForUser(newPassword);
	    console.log(keysNew);
	    privateKey = symEncrypt(keysNew['keyHash'], privateKey); //encrypt privatestring, using the password hash
	    
	    $.post("api.php?action=updatePassword", {
		    oldPassword:passwordHash_old,
	        password:keysNew['authHash'],
	        authSalt:keysNew['authSaltEncrypted'],
	        keySalt:keysNew['keySaltEncrypted'],
	        privateKey:privateKey 
	    	}, function(result){
		    	if(result == 1){
		    		
	    			parent.localStorage.currentUser_passwordHashMD5 = passwordHashMD5New;
		    		jsAlert('', 'Your password has been changed');
		    	}else{
		    		jsAlert('', result);
		    	}
	    	}, "html");
	    
	    
    }
    
    function toggleKey(username){
    	
    	if(localStorage.key[username]){
		  	$('#chatKeySettings_'+username).html('<a href="#" onclick="chatDeactivateKey(\''+username+'\'); return false;">deactivate key</a>');	
    	}else{
		    $('#chatKeySettings_'+username).html('<form action="" method="post" target="submitter" onsubmit="chatSetKey(\''+username+'\'); return false;"><input type="password" name="key" placeholder="type key" id="chatKeyInput_'+username+'"></form>');	
    	}				
    	
    	if($("#chatKeySettings_" + username +" ").is(":visible")){
          $("#chatKeySettings_" + username +" ").hide("slow");
    	}else{
          $("#chatKeySettings_" + username +" ").show("slow");
    		
    	}
    }
    
    function chatSubmit(username){
        $("#chatWindow_" + username + "").load("modules/chat/chatreload.php?buddy=" + username + "&reload=1");
    }
    
    function chatMessageSubmit(userid){
    	
    	console.log('getPublicKey');
    	var publicKey = getPublicKey('user', userid); //get public key of receiver
    	console.log('getRandKey');
    	var randKey = Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2); //generate random key
   	
    	console.log('symEncrypt:'+randKey);
    	var message = symEncrypt(randKey, $('#chatInput_'+userid).val()); //encrypt message semitrically

    	console.log('symkey'+publicKey+randKey);
    	var symKey = asymEncrypt(publicKey, randKey); //random generated key for symetric encryption

    	console.log('messsage');    	
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
	                var randKey = asymDecrypt(privateKey, message[0]);
	                
	                
	    		}
	    		
	    		
                if(randKey !== null){
                    //encrypt message with random key
	    			console.log('sym');
                    var content = htmlentities(symDecrypt(randKey, message[1]));
	    		
	    		}else{
	    			content = 'The key is not stored anymore';
	    		}
	    		
	    		
	    		$(this).html(content);
	    		$(this).removeClass('chatMessage_'+userid);
	    	});
	    	return true;
    }
    
    function replaceLinks(){
    	
    	$('body').html($(this).html().replaceAll("/(b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|])/ig","<a href='#' onclick='$1'>$1</a>"));
        
    }
    
function initDashClose(){
	//init dashcloses
	$('.dashBox .dashClose').click(function(){
		$(this).parent('.dashBox').slideUp();
	});	
	
}
    
//dashboard

var dashBoard = new function(){
	
	
	
	this.view = 'up'; // up or down 
	
	this.init = function(){
		$('#dashBoard a, #dashBoard li').not('.disableToggling').click(function(){dashBoard.slideUp();});
    	$("#dashBoard").draggable({
    		axis: "y", 
    		cancel : '#dashBoxFrame',
    		containment: "#dashGrid",
    		stop: function( event, ui ) {
    			if(parseInt($('#dashBoard').css('top').replace(/[^-\d\.]/g, '')) > 191)
    				$('#dashBoard').css('top', '191px');
    		}
    	});
	
	};
	
	this.slideDown = function(){
		
			$('#dashBoard').animate({top: 190}, 750, function() {
				$('#dashBoard').removeClass('up');
				$('#dashBoardBG').removeClass('up');
				$('#dashBoard footer a i').removeClass('icon-arrow-down');
				$('#dashBoard footer a i').addClass('icon-arrow-up');
			});
			this.view = 'down';
	};
	this.slideUp = function(){
		
			$('#dashBoard').animate({top: 0}, 750, function() {
				$('#dashBoard').addClass('up');
				$('#dashBoardBG').addClass('up');
				$('#dashBoard footer a i').removeClass('icon-arrow-up');
				$('#dashBoard footer a i').addClass('icon-arrow-down');
			});
			this.view = 'up';
	};
	this.toggle = function(){
		if(this.view === 'up'){
			this.slideDown();
		}else if(this.view === 'down'){
			this.slideUp();
		}
	};
};

function updateDashbox(type){
	$('.dashBox#'+type+'Box').load('modules/desktop/updateDashboard.php?type='+type, function(){
		
		initDashClose();
	});
	
	
	
}

function toggleDashboard(){
	$('#dashboard:visible').slideUp();
	$('#dashboard:hidden').slideDown();
}
    
    
//group
function groupMakeUserAdmin(groupId, userId){
	
	$.post( "doit.php?action=groupMakeUserAdmin&groupId="+groupId+"&userId="+userId, function( data ) {
	  if(data == true){
	  	jsAlert('', 'The admin has been added.');
	  }
	});
	
}
//old index functions


	//rightclick
                function clearMenu() { //used to make the menu disappear
                    //this function should be used at the beginning of any function that is called from the menu
                    var cssObj = {
                        'display' : 'none'
                       };
                    $(".rightclick").css(cssObj);
                }
              
                function showMenu(id) {
                    /*  check whether the event is a right click 
                    *  because different browser (ahem IE) assign different numbers to the keys to
                    *  your mouse buttons and different values to the event, you'll have to do some evaluation
                    */
                    var rightclick; //will be set to true or false
                    if (event.button) {
                        rightclick = (event.button == 2);
                    } else if (e.button) {
                        rightclick = (event.which == 3);
                    }

                    if(rightclick) { //if the secondary mouse botton was clicked
                        $(".rightclick").hide();
                        var menu = document.getElementById("rightClick" + id + "");
                        var Event = event;
                        menu.style.position = "fixed"; //show menu
                        menu.style.display = "block"; //show menu
                        menu.style.left  = Event.clientX + "px";
                        menu.style.top = Event.clientY + "px";

                        
                        $(".rightclick").css('z-index', '99999');
                    }
                }
                
                
	//the rest

              function showModuleMail() {
                    $.get("modules/mail/index.php",function(data){
                          $('#bodywrap').append(data);
                    },'html');
                }
                
              function showModuleCalender() {
                    $.get("modules/calender/index.php",function(data){
                          $('#bodywrap').append(data);
                    },'html');
                }
                
              function showModuleSettings() {
                    $.get("modules/settings/index.php",function(data){
                          $('#bodywrap').append(data);
                          applicationOnTop('settings');
                    },'html');
                    
                }
              
              function updateUserActivity() {
              	$("#loader").load("doit.php?action=updateUserActivity");
              }
              
              function closeModuleSettings() {
              	$("#invisibleSettings").hide("slow");
              }
              function openModule(moduleId) {
              	$("#invisible" + moduleId + "").toggle("slow");
              }
              
              function openModuleMail() {
              	$("#invisiblemail").show("slow");
              }
              
              function closeModuleMail() {
              	$("#invisiblemail").hide("slow");
              }
                
              function play(){
              	$("#jquery_jplayer_2").jPlayer("play");
              } 
                 
              function playPlaylist(playlist, row, fileId){
                  
	              alert("lol a" + fileId + " b" + playlist + " c" + row + " ");
	              $("#dockplayer").load("./player/dockplayer.php?file=" + fileId +"&reload=1&playList=" + playlist +"&row=" + row + "");
	              play();
              }
              function playFileDock(fileId){
              	$("#dockplayer").load("./player/dockplayer.php?file=" + fileId +"&reload=1");
              }
                
              function nextPlaylistItem(playList, row){
             	  $("#playListPlayer").load("playListplayer.php?playList=" + playList +"&row=" + row +"");
              }

			  function removeFav(type, typeId){
			  	if($.post("doit.php?action=removeFav", { type: type, typeId: typeId } )){
			  		jsAlert('', 'Your favorite has been removed.');
			  		updateDashbox('fav');
			  	}
			  }
			  
              function addBuddy(userId) {
              	$("#loader").load("addbuddy.php?user=" + userId +"");
              }
                
              function showSubComment(commentId) {
                  $("#comment" + commentId + "").load("showComment.php?id=" + commentId +"");
                  $("#comment" + commentId + "").toggle("slow");
              }
              function showfeedComment(feedId) {
                  $("#feed" + feedId + "").load("showComment.php?type=feed&feedid=" + feedId +"");
                  $("#feed" + feedId + "").toggle("slow");
              }
              function loader(id, link){
                  $("#" + id + "").load("" + link + "");
              }
              
              
              
              function deleteFromPersonals(id){
                  $("#loader").load("doit.php?action=deleteFromPersonals&id=" + id + "");
              }
              
              
              

              function showGroup(groupId){
                  showApplication('reader');
                  createNewTab('reader_tabView',"" + groupId + "",'',"./group.php?id=" + groupId + "",true);
                  return false;
              }
              
              function showProfile(userId){
                  showApplication('reader');
                  createNewTab('reader_tabView',useridToUsername(userId),'',"./profile.php?user=" + userId + "",true);
                  return false;
              }
                
              function showPlaylist(id){
              	popper('doit.php?action=showPlaylist&id='+id);
              }
                
              function startPlayer(type, typeid){
              $("#dockplayer").load("player/dockplayer.php?reload=1&" + type +"=" + typeid + "");
              }
              
              
              function popper(url) {
              $("#loader").load("" + url +"");
                }
                
                
              function swapApplication(app, link){
              
              $("#" + app +":hidden").load("" + url +"");    
              }
              
              
              function closeDockMenu(){
                $("#dockMenu").hide("fast");
              }
              
              function updateUserActivity() {
              $("#loader").load("doit.php?action=updateUserActivity");
                }
                
              function clock() {
                var now = new Date();

                    var hours = now.getHours();
                    var minutes = now.getMinutes();
                    var pad = "00";

                    var minutesRoundedOne = "" + minutes;
                    var minutesRoundedTwo = pad.substring(0, pad.length - minutesRoundedOne.length)+''+minutesRoundedOne;
                    var hoursRoundedOne = "" + hours;
                    var hoursRoundedTwo = pad.substring(0, pad.length - hoursRoundedOne.length) + hoursRoundedOne;

                var outStr = hoursRoundedTwo+':'+minutesRoundedTwo;
                $('#clockDiv').html(outStr);
                setTimeout('clock()',1000);
              }
              
              var settings =  new function() {
			    this.userid = localStorage.currentUser_userid;
			    
			    this.submitPassword = function () {
			    	if($('#newPassword').val() === $('#newPasswordRepeat').val() && $('#newPassword').val().length > 0){ 
			    		updatePassword($('#oldPassword').val(), $('#newPassword').val(), this.userid);
			    		$('.changePassword').slideUp();
			    	}else if($('#newPassword').val().length === 0){
			    		jsAlert('', 'The password is to short');
			    	}else{
			    		jsAlert('The passwords dont match');
			    	}
			    };
			};
              
              
             


//PLUGINS
//PLUGINS
//PLUGINS

                // textarea autogrow from figovo.com - thx
                function autoGrowField(f, max) {
                   /* Default max height */
                   var max = (typeof max == 'undefined')?1000:max;
                   /* Don't let it grow over the max height */
                   if (f.scrollHeight > max) {
                      /* Add the scrollbar back and bail */
                      if (f.style.overflowY != 'scroll') { f.style.overflowY = 'scroll'; }
                      return;
                   }
                   /* Make sure element does not have scroll bar to prevent jumpy-ness */
                   if (f.style.overflowY != 'hidden') { f.style.overflowY = 'hidden'; }
                   /* Now adjust the height */
                   var scrollH = f.scrollHeight;
                   if( scrollH > f.style.height.replace(/[^0-9]/g,'') ){
                      f.style.height = scrollH+'px';
                   }
                }






                /*
                 * AutoSuggest
                 * Copyright 2009-2010 Drew Wilson
                 * www.drewwilson.com
                 * code.drewwilson.com/entry/autosuggest-jquery-plugin
                 *
                 * Version 1.4   -   Updated: Mar. 23, 2010
                 *
                 * This Plug-In will auto-complete or auto-suggest completed search queries
                 * for you as you type. You can add multiple selections and remove them on
                 * the fly. It supports keybord navigation (UP + DOWN + RETURN), as well
                 * as multiple AutoSuggest fields on the same page.
                 *
                 * Inspied by the Autocomplete plugin by: J�rn Zaefferer
                 * and the Facelist plugin by: Ian Tearle (iantearle.com)
                 *
                 * This AutoSuggest jQuery plug-in is dual licensed under the MIT and GPL licenses:
                 *   http://www.opensource.org/licenses/mit-license.php
                 *   http://www.gnu.org/licenses/gpl.html
                 */

                (function($){
                        $.fn.autoSuggest = function(data, options) {
                                var defaults = { 
                                        asHtmlID: false,
                                        startText: "Enter Name Here",
                                        emptyText: "No Results Found",
                                        preFill: {},
                                        limitText: "No More Selections Are Allowed",
                                        selectedItemProp: "value", //name of object property
                                        selectedValuesProp: "value", //name of object property
                                        searchObjProps: "value", //comma separated list of object property names
                                        queryParam: "q",
                                        retrieveLimit: false, //number for 'limit' param on ajax request
                                        extraParams: "",
                                        matchCase: false,
                                        minChars: 1,
                                        keyDelay: 400,
                                        resultsHighlight: true,
                                        neverSubmit: false,
                                        selectionLimit: false,
                                        showResultList: true,
                                        start: function(){},
                                        selectionClick: function(elem){},
                                        selectionAdded: function(elem){},
                                        selectionRemoved: function(elem){ elem.remove(); },
                                        formatList: false, //callback function
                                        beforeRetrieve: function(string){ return string; },
                                        retrieveComplete: function(data){ return data; },
                                        resultClick: function(data){},
                                        resultsComplete: function(){}
                                };  
                                var opts = $.extend(defaults, options);	 	

                                var d_type = "object";
                                var d_count = 0;
                                if(typeof data == "string") {
                                        d_type = "string";
                                        var req_string = data;
                                } else {
                                        var org_data = data;
                                        for (k in data) if (data.hasOwnProperty(k)) d_count++;
                                }
                                if((d_type == "object" && d_count > 0) || d_type == "string"){
                                        return this.each(function(x){
                                                if(!opts.asHtmlID){
                                                        x = x+""+Math.floor(Math.random()*100); //this ensures there will be unique IDs on the page if autoSuggest() is called multiple times
                                                        var x_id = "as-input-"+x;
                                                } else {
                                                        x = opts.asHtmlID;
                                                        var x_id = x;
                                                }
                                                opts.start.call(this);
                                                var input = $(this);
                                                input.attr("autocomplete","off").addClass("as-input").attr("id",x_id).val(opts.startText);
                                                var input_focus = false;

                                                // Setup basic elements and render them to the DOM
                                                input.wrap('<ul class="as-selections" id="as-selections-'+x+'"></ul>').wrap('<li class="as-original" id="as-original-'+x+'"></li>');
                                                var selections_holder = $("#as-selections-"+x);
                                                var org_li = $("#as-original-"+x);				
                                                var results_holder = $('<div class="as-results" id="as-results-'+x+'"></div>').hide();
                                                var results_ul =  $('<ul class="as-list"></ul>');
                                                var values_input = $('<input type="hidden" class="as-values" name="as_values_'+x+'" id="as-values-'+x+'" />');
                                                var prefill_value = "";
                                                if(typeof opts.preFill == "string"){
                                                        var vals = opts.preFill.split(",");					
                                                        for(var i=0; i < vals.length; i++){
                                                                var v_data = {};
                                                                v_data[opts.selectedValuesProp] = vals[i];
                                                                if(vals[i] != ""){
                                                                        add_selected_item(v_data, "000"+i);	
                                                                }		
                                                        }
                                                        prefill_value = opts.preFill;
                                                } else {
                                                        prefill_value = "";
                                                        var prefill_count = 0;
                                                        for (k in opts.preFill) if (opts.preFill.hasOwnProperty(k)) prefill_count++;
                                                        if(prefill_count > 0){
                                                                for(var i=0; i < prefill_count; i++){
                                                                        var new_v = opts.preFill[i][opts.selectedValuesProp];
                                                                        if(new_v == undefined){ new_v = ""; }
                                                                        prefill_value = prefill_value+new_v+",";
                                                                        if(new_v != ""){
                                                                                add_selected_item(opts.preFill[i], "000"+i);	
                                                                        }		
                                                                }
                                                        }
                                                }
                                                if(prefill_value != ""){
                                                        input.val("");
                                                        var lastChar = prefill_value.substring(prefill_value.length-1);
                                                        if(lastChar != ","){ prefill_value = prefill_value+","; }
                                                        values_input.val(","+prefill_value);
                                                        $("li.as-selection-item", selections_holder).addClass("blur").removeClass("selected");
                                                }
                                                input.after(values_input);
                                                selections_holder.click(function(){
                                                        input_focus = true;
                                                        input.focus();
                                                }).mousedown(function(){ input_focus = false; }).after(results_holder);	

                                                var timeout = null;
                                                var prev = "";
                                                var totalSelections = 0;
                                                var tab_press = false;

                                                // Handle input field events
                                                input.focus(function(){			
                                                        if($(this).val() == opts.startText && values_input.val() == ""){
                                                                $(this).val("");
                                                        } else if(input_focus){
                                                                $("li.as-selection-item", selections_holder).removeClass("blur");
                                                                if($(this).val() != ""){
                                                                        results_ul.css("width",selections_holder.outerWidth());
                                                                        results_holder.show();
                                                                }
                                                        }
                                                        input_focus = true;
                                                        return true;
                                                }).blur(function(){
                                                        if($(this).val() == "" && values_input.val() == "" && prefill_value == ""){
                                                                $(this).val(opts.startText);
                                                        } else if(input_focus){
                                                                $("li.as-selection-item", selections_holder).addClass("blur").removeClass("selected");
                                                                results_holder.hide();
                                                        }				
                                                }).keydown(function(e) {
                                                        // track last key pressed
                                                        lastKeyPressCode = e.keyCode;
                                                        first_focus = false;
                                                        switch(e.keyCode) {
                                                                case 38: // up
                                                                        e.preventDefault();
                                                                        moveSelection("up");
                                                                        break;
                                                                case 40: // down
                                                                        e.preventDefault();
                                                                        moveSelection("down");
                                                                        break;
                                                                case 8:  // delete
                                                                        if(input.val() == ""){							
                                                                                var last = values_input.val().split(",");
                                                                                last = last[last.length - 2];
                                                                                selections_holder.children().not(org_li.prev()).removeClass("selected");
                                                                                if(org_li.prev().hasClass("selected")){
                                                                                        values_input.val(values_input.val().replace(","+last+",",","));
                                                                                        opts.selectionRemoved.call(this, org_li.prev());
                                                                                } else {
                                                                                        opts.selectionClick.call(this, org_li.prev());
                                                                                        org_li.prev().addClass("selected");		
                                                                                }
                                                                        }
                                                                        if(input.val().length == 1){
                                                                                results_holder.hide();
                                                                                 prev = "";
                                                                        }
                                                                        if($(":visible",results_holder).length > 0){
                                                                                if (timeout){ clearTimeout(timeout); }
                                                                                timeout = setTimeout(function(){ keyChange(); }, opts.keyDelay);
                                                                        }
                                                                        break;
                                                                case 9: case 188:  // tab or comma
                                                                        tab_press = true;
                                                                        var i_input = input.val().replace(/(,)/g, "");
                                                                        if(i_input != "" && values_input.val().search(","+i_input+",") < 0 && i_input.length >= opts.minChars){	
                                                                                e.preventDefault();
                                                                                var n_data = {};
                                                                                n_data[opts.selectedItemProp] = i_input;
                                                                                n_data[opts.selectedValuesProp] = i_input;																				
                                                                                var lis = $("li", selections_holder).length;
                                                                                add_selected_item(n_data, "00"+(lis+1));
                                                                                input.val("");
                                                                        }
                                                                case 13: // return
                                                                        tab_press = false;
                                                                        var active = $("li.active:first", results_holder);
                                                                        if(active.length > 0){
                                                                                active.click();
                                                                                results_holder.hide();
                                                                        }
                                                                        if(opts.neverSubmit || active.length > 0){
                                                                                e.preventDefault();
                                                                        }
                                                                        break;
                                                                default:
                                                                        if(opts.showResultList){
                                                                                if(opts.selectionLimit && $("li.as-selection-item", selections_holder).length >= opts.selectionLimit){
                                                                                        results_ul.html('<li class="as-message">'+opts.limitText+'</li>');
                                                                                        results_holder.show();
                                                                                } else {
                                                                                        if (timeout){ clearTimeout(timeout); }
                                                                                        timeout = setTimeout(function(){ keyChange(); }, opts.keyDelay);
                                                                                }
                                                                        }
                                                                        break;
                                                        }
                                                });

                                                function keyChange() {
                                                        // ignore if the following keys are pressed: [del] [shift] [capslock]
                                                        if( lastKeyPressCode == 46 || (lastKeyPressCode > 8 && lastKeyPressCode < 32) ){ return results_holder.hide(); }
                                                        var string = input.val().replace(/[\\]+|[\/]+/g,"");
                                                        if (string == prev) return;
                                                        prev = string;
                                                        if (string.length >= opts.minChars) {
                                                                selections_holder.addClass("loading");
                                                                if(d_type == "string"){
                                                                        var limit = "";
                                                                        if(opts.retrieveLimit){
                                                                                limit = "&limit="+encodeURIComponent(opts.retrieveLimit);
                                                                        }
                                                                        if(opts.beforeRetrieve){
                                                                                string = opts.beforeRetrieve.call(this, string);
                                                                        }
                                                                        $.getJSON(req_string+"?"+opts.queryParam+"="+encodeURIComponent(string)+limit+opts.extraParams, function(data){ 
                                                                                d_count = 0;
                                                                                var new_data = opts.retrieveComplete.call(this, data);
                                                                                for (k in new_data) if (new_data.hasOwnProperty(k)) d_count++;
                                                                                processData(new_data, string); 
                                                                        });
                                                                } else {
                                                                        if(opts.beforeRetrieve){
                                                                                string = opts.beforeRetrieve.call(this, string);
                                                                        }
                                                                        processData(org_data, string);
                                                                }
                                                        } else {
                                                                selections_holder.removeClass("loading");
                                                                results_holder.hide();
                                                        }
                                                }
                                                var num_count = 0;
                                                function processData(data, query){
                                                        if (!opts.matchCase){ query = query.toLowerCase(); }
                                                        var matchCount = 0;
                                                        results_holder.html(results_ul.html("")).hide();
                                                        for(var i=0;i<d_count;i++){				
                                                                var num = i;
                                                                num_count++;
                                                                var forward = false;
                                                                if(opts.searchObjProps == "value") {
                                                                        var str = data[num].value;
                                                                } else {	
                                                                        var str = "";
                                                                        var names = opts.searchObjProps.split(",");
                                                                        for(var y=0;y<names.length;y++){
                                                                                var name = $.trim(names[y]);
                                                                                str = str+data[num][name]+" ";
                                                                        }
                                                                }
                                                                if(str){
                                                                        if (!opts.matchCase){ str = str.toLowerCase(); }				
                                                                        if(str.search(query) != -1 && values_input.val().search(","+data[num][opts.selectedValuesProp]+",") == -1){
                                                                                forward = true;
                                                                        }	
                                                                }
                                                                if(forward){
                                                                        var formatted = $('<li class="as-result-item" id="as-result-item-'+num+'"></li>').click(function(){
                                                                                        var raw_data = $(this).data("data");
                                                                                        var number = raw_data.num;
                                                                                        if($("#as-selection-"+number, selections_holder).length <= 0 && !tab_press){
                                                                                                var data = raw_data.attributes;
                                                                                                input.val("").focus();
                                                                                                prev = "";
                                                                                                add_selected_item(data, number);
                                                                                                opts.resultClick.call(this, raw_data);
                                                                                                results_holder.hide();
                                                                                        }
                                                                                        tab_press = false;
                                                                                }).mousedown(function(){ input_focus = false; }).mouseover(function(){
                                                                                        $("li", results_ul).removeClass("active");
                                                                                        $(this).addClass("active");
                                                                                }).data("data",{attributes: data[num], num: num_count});
                                                                        var this_data = $.extend({},data[num]);
                                                                        if (!opts.matchCase){ 
                                                                                var regx = new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + query + ")(?![^<>]*>)(?![^&;]+;)", "gi");
                                                                        } else {
                                                                                var regx = new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + query + ")(?![^<>]*>)(?![^&;]+;)", "g");
                                                                        }

                                                                        if(opts.resultsHighlight){
                                                                                this_data[opts.selectedItemProp] = this_data[opts.selectedItemProp].replace(regx,"<em>$1</em>");
                                                                        }
                                                                        if(!opts.formatList){
                                                                                formatted = formatted.html(this_data[opts.selectedItemProp]);
                                                                        } else {
                                                                                formatted = opts.formatList.call(this, this_data, formatted);	
                                                                        }
                                                                        results_ul.append(formatted);
                                                                        delete this_data;
                                                                        matchCount++;
                                                                        if(opts.retrieveLimit && opts.retrieveLimit == matchCount ){ break; }
                                                                }
                                                        }
                                                        selections_holder.removeClass("loading");
                                                        if(matchCount <= 0){
                                                                results_ul.html('<li class="as-message">'+opts.emptyText+'</li>');
                                                        }
                                                        results_ul.css("width", selections_holder.outerWidth());
                                                        results_holder.show();
                                                        opts.resultsComplete.call(this);
                                                }

                                                function add_selected_item(data, num){
                                                        values_input.val(values_input.val()+data[opts.selectedValuesProp]+",");
                                                        var item = $('<li class="as-selection-item" id="as-selection-'+num+'"></li>').click(function(){
                                                                        opts.selectionClick.call(this, $(this));
                                                                        selections_holder.children().removeClass("selected");
                                                                        $(this).addClass("selected");
                                                                }).mousedown(function(){ input_focus = false; });
                                                        var close = $('<a class="as-close">&times;</a>').click(function(){
                                                                        values_input.val(values_input.val().replace(","+data[opts.selectedValuesProp]+",",","));
                                                                        opts.selectionRemoved.call(this, item);
                                                                        input_focus = true;
                                                                        input.focus();
                                                                        return false;
                                                                });
                                                        org_li.before(item.html(data[opts.selectedItemProp]).prepend(close));
                                                        opts.selectionAdded.call(this, org_li.prev());	
                                                }

                                                function moveSelection(direction){
                                                        if($(":visible",results_holder).length > 0){
                                                                var lis = $("li", results_holder);
                                                                if(direction == "down"){
                                                                        var start = lis.eq(0);
                                                                } else {
                                                                        var start = lis.filter(":last");
                                                                }					
                                                                var active = $("li.active:first", results_holder);
                                                                if(active.length > 0){
                                                                        if(direction == "down"){
                                                                        start = active.next();
                                                                        } else {
                                                                                start = active.prev();
                                                                        }	
                                                                }
                                                                lis.removeClass("active");
                                                                start.addClass("active");
                                                        }
                                                }

                                        });
                                }
                        };
                })(jQuery);  	

				var delay = (function(){
				  var timer = 0;
				  return function(callback, ms){
				    clearTimeout (timer);
				    timer = setTimeout(callback, ms);
				  };
				})();