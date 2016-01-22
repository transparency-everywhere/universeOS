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
        
               
var calendar = new function(){
	
	this.todayTimeObject = new Date();
	this.ShownDay; //defines the starttime of shown intervall(first day of month, first day of week, day)
	this.view = 'month'; //defines type of view(month, week or day)
	this.listType = 'boxes';
	this.shownTimeObject;
	this.loader;
        this.usedColors = 0; //no of used colors from the color array, returned from getColor
        this.colorDB = {}; //colors are saved with privacy value (e.g. calendar.colorDB['f'] could be 0 which would be the first value from the array, returned by calendar.getColor())
	this.showDoneTasks = false; // defines if tasks with status "done" are shown
	this.applicationVar;
        this.getColor = function(){
            calendar.usedColors++;
            var colors = ['ffa000', 'ff5722', 'b71c1c', '880e4f', '4a148c', '311b92', '0d459c', '0656b1', '006064', '164c2c', '2a5619', '645b0f', '4e342e'];
            return colors[calendar.usedColors];
        };
	this.show = function(){
			  		applications.show('calendar');
			  	};
	
	this.init = function(){
			  		
			  		var html = '<div id="calendarMainFrame">';
			  				html += '<header>';
			  					html += '<div class="pull-right" id="calendarViewDetail">';
			  						html += '<a href="#" class="button" id="prev">&lt;&lt;</a>';
			  						html += '<a href="#" class="button" id="text"></a>';
			  						html += '<a href="#" class="button" id="next">>></a>';
			  					html += '</div>';
			  					html += '<div class="pull-left" id="calendarView">';
			  						html += '<a href="#" class="button grayButton" id="day">Day</a>';
			  						html += '<a href="#" class="button grayButton" id="week">Week</a>';
			  						html += '<a href="#" class="button grayButton" id="month">Month</a>';
			  					html += '</div>';
//			  					html += '<div class=" pull-left" id="calendarListType" style="margin-left:30px;">';
//			  						html += '<a href="#" class="button active" id="boxes"><i class="icon-th"></i></a>';
//			  						html += '<a href="#" class="button" id="list"><i class="icon-th-list"></i></a>';
//			  					html += '</div>';
			  					html += '<a class="button pull-right" id="headerToday" style="margin-right:5px;">Today</a>';
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
							html += '<div id="side" class="leftNav dark">';
							
								html += '<ul id="calendars">';
									html += '<li class="header">Calendars<span class="toggleTrigger icon white-chevron-down pull-right"></span></li>';
									
                                                                        //load general privacies (public only me etc)
									html += '<li class="calendarPrivacyTrigger active" data-value="h">&nbsp;My Calendar <span class="icon white-check white"></span><span class="icon blue-check"></span></li>';
									html += '<li class="calendarPrivacyTrigger" data-value="p">&nbsp;Public <span class="icon white-check white"></span><span class="icon blue-check"></span></li>';
									html += '<li class="calendarPrivacyTrigger active" data-value="f">&nbsp;Friends <span class="icon white-check white"></span><span class="icon blue-check"></span></li>';
									
									//load groups into calendar list
									var userGroups = groups.get();
									if(userGroups){
										$.each(groups.get(), function( index, value ) {
											html += '<li class="calendarPrivacyTrigger" data-value="'+value+'"><i class="icon white-group" style="height:14px; width: 14px;"></i>'+groups.getTitle(value)+' <span class="icon white-check white"></span><span class="icon blue-check"></span></li>';
										});
									}
								html += '</ul>';
								html += '<ul id="taskList">';
									html += '<li class="header" onclick="calendar.toggleTasks();">Tasks<span class="toggleTrigger icon white-chevron-down pull-right white"></span><span class="toggleTrigger icon blue-chevron-down pull-right"></span><a href="#" class="pull-right" onclick="tasks.addForm('+this.todayTimeObject.getTime()/1000+')"><i class="icon white-plus white"></i><i class="icon blue-plus"></i></a></li>';
									html += '<li style="display:none;"><input type="checkbox" id="hideDoneTasks" onclick="calendar.toggleDoneTasks();" checked>&nbsp;hide done</li>';
								html += '</ul>';
                                                                
								html += '<ul id="events">';
									html += '<li class="header">Events<span class="toggleTrigger icon white-chevron-down pull-right white"></span><span class="toggleTrigger icon blue-chevron-down pull-right"></span><a href="#" class="pull-right" onclick="events.addForm('+this.todayTimeObject.getTime()/1000+')"><i class="icon white-plus white"></i><i class="icon blue-plus"></a></li>';
									//events will apend to this list
								html += '</ul>';
								
							html += '</div>';
						html += '</div>';
						
                                                this.applicationVar = new application('calendar');
			  			this.applicationVar.create('Calendar', 'html', html,{width: 8, height:  8, top: 2, left: 2});
			  			
			  			
                                                $('#side .toggleTrigger').click(function(){
                                                    $(this).toggleClass('white-chevron-down white-chevron-up');
                                                });
                                                
						$('#calendars .header').click(function(){
							$('#side #calendars li').not('.header').slideToggle();
						});
			  			
                                                
                                                
			  			$('#calendars li').click(function(){
                                                        $(this).toggleClass('active');
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
			  			
			  			$('.calendarFrame').removeClass('list');
			  			$('#calendarListType #list').removeClass('active');
			  			
			  			$('.calendarFrame').addClass('boxes');
			  			$('#calendarListType #boxes').addClass('active');
                                                
			  			$('#main>header').show();
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
			  		$('#calendars .calendarPrivacyTrigger.active').each(function(){
			  			 privacy.push($(this).data('value'));
			  		});
			  		
			  		return privacy.join(';');
			  	};
	
        this.filterTasksAndEvents = function(startstamp, objects){
            var results = [];
            $.each(objects, function(index, value){
                if(typeof value.timestamp !== 'undefined'){
                    var timestamp = value.timestamp;
                }
                if(typeof value.startStamp !== 'undefined'){
                    var timestamp = value.startStamp;
                }
                var stopstamp = timestamp;
                if(typeof value.stopStamp !== 'undefined'){
                    stopstamp = value.stopStamp;
                }
                if((timestamp>startstamp)&&(timestamp<(startstamp+86400))||(stopstamp>startstamp)&&(stopstamp<(startstamp+86400))){
                    results.push(value);
                }
            });
            return results;
        };
	this.loadTasks = function(){
                                        var firstTimestamp = this.getFirstTimestamp();
                                        var lastTimestamp = this.getLastTimestamp();
                                        var loadedTasks = tasks.get(firstTimestamp, lastTimestamp, calendar.getPrivacy());
                                        
			  		$('.calendarFrame .day').each(function(){
			  			var startstamp = $(this).data("timestamp");
			  			
                                                var taskList = calendar.filterTasksAndEvents(startstamp, loadedTasks);
			  			//var taskList = tasks.get(startstamp, startstamp+86400, calendar.getPrivacy());
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
										  list += '<li class="taskDetail '+taskClass+'" id="taskDetail_'+value.id+'" style="'+style+'"><i class="icon white-pencil" onclick="tasks.show('+value.id+', '+value.editable+');"></i>'+value.description+'</li>';
										
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
			  		});
			  		
			  	};
	
        this.getFirstTimestamp = function(){
            return $('.calendarFrame .day').first().data('timestamp');
        };
        this.getLastTimestamp = function(){
            return $('.calendarFrame .day').last().data('timestamp')+86400;
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
	
        this.getEventColor = function(privacy){
            if(privacy.indexOf('//') === -1){
                
            }else{
                privacy = privacy.substr('//')[0];
                
            }
            if(typeof calendar.colorDB[privacy] === 'undefined'){
                var color = calendar.getColor();
                calendar.colorDB[privacy] = color;
                return '#'+color;
            }else{
                return '#'+calendar.colorDB[privacy];
            }
        };
        
        //loads colors for active privacy values
        this.loadColorsIntoSide = function(){
            
            $('.calendarPrivacyTrigger').each(function(){
                if($(this).hasClass('active')){
                    var privacyValue = $(this).attr('data-value');
                    $(this).css('backgroundColor', '#'+calendar.getEventColor(privacyValue));
                }
            });
            
        };
        
	this.loadEvents = function(){
			  			calendar.loadTasks();
                                        
                                        var firstTimestamp = this.getFirstTimestamp();
                                        var lastTimestamp = this.getLastTimestamp();
                                        var loadedEvents = events.get(firstTimestamp, lastTimestamp, calendar.getPrivacy());
                                        //console.log(loadedEvents);
			  		$('.calendarFrame .day').each(function(){
			  			var startstamp = $(this).data("timestamp");
			  			var appointments = calendar.filterTasksAndEvents(startstamp, loadedEvents);
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
									  
                                                                          var bgColor = calendar.getEventColor(value.privacy);
									  
									  list += '<li data-eventId="'+value.id+'" onclick="$(\'#eventDetail_'+value.id+'\').toggle();" style="background-color: '+bgColor+';">'+title+'</li>';
									  list += '<li class="eventDetail" id="eventDetail_'+value.id+'" onclick="events.show('+value.id+', '+privacy.authorize(value.privacy, value.user)+');" style="background-color: '+bgColor+';"><i class="icon white-pencil"></i>'+startTime+' - '+stopTime+'<br>'+value.place+'</li>';
									
								  }
								 });
							}
							
						$(this).children('.eventList').append(list);
			  			calendar.loadColorsIntoSide();
			  			
			  			
			  		});
			  		
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
								day += date;
								day += '<div class="dropdown">';
									day += '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
										day += filesystem.generateIcon('settings');
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
					this.loader = setTimeout(function(){calendar.loadTasks(); calendar.loadEvents();}, 1000);
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
					var stopStamp = date.getTime()/1000+86400;
					
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
					this.loader = setTimeout(function() {calendar.loadEvents();}, 100);
					this.updateViewDetail('day', this.shownTimeObject);
			  	};
	
	this.updateViewDetail = function(type, dateObj){
			  		
			  		if(type == 'month'){
			  			
			  			
				  		$('#calendarView .button').removeClass('active');
				  		$('#month').addClass('active');
                                                
			  			calendar.toggleListType('boxes');
			  			
			  			
			  			$('#calendarViewDetail .button').unbind('click');
			  			
						$('#calendarViewDetail #prev').bind('click',function(){
							calendar.shownTimeObject.setMonth(calendar.shownTimeObject.getMonth()-1);
                                                        console.log(calendar.shownTimeObject);
			  				calendar.loadMonth(calendar.shownTimeObject);
						});
						
						$('#calendarViewDetail #next').bind('click',function(){
							calendar.shownTimeObject.setMonth(calendar.shownTimeObject.getMonth()+1);
			  				calendar.loadMonth(calendar.shownTimeObject);
							
						});
			  			
						$('#calendarViewDetail #text').text(this.getMonthName(calendar.shownTimeObject.getMonth()));
						
						
			  		}else if(type == 'week'){
			  			
				  		$('#calendarView .button').removeClass('active');
				  		$('#week').addClass('active');
				  		
						
						
			  			$('#calendarViewDetail .button').unbind('click');
			  			
						$('#calendarViewDetail #prev').click(function(){
							calendar.shownTimeObject.setSeconds(-(7*86400));
							
			  				calendar.loadWeek(calendar.shownTimeObject.getTime()/1000);
						});
						
						$('#calendarViewDetail #next').click(function(){
							calendar.shownTimeObject.setSeconds(+(7*86400));
			  				calendar.loadWeek(calendar.shownTimeObject.getTime()/1000);
							
						});
						
						var nextWeek = new Date(calendar.shownTimeObject.getTime()+(7*86400000));
						
						$('#calendarViewDetail #text').html(calendar.pad2(calendar.shownTimeObject.getDate())+'.'+calendar.pad2(calendar.shownTimeObject.getMonth()+1)+' - '+calendar.pad2(nextWeek.getDate())+'.'+calendar.pad2(nextWeek.getMonth()+1));
						
			  		}else if(type == 'day'){
			  			
				  		$('#calendarView .button').removeClass('active');
				  		$('#day').addClass('active');
				  		
						$('#calendarViewDetail #text').html(calendar.pad2(calendar.shownTimeObject.getDate())+'.'+calendar.pad2(calendar.shownTimeObject.getMonth()+1));
						
						
						
			  			$('#calendarViewDetail .button').unbind('click');
			  			
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
					
			  		//$('#calendarView .button').unbind('click');
			  		
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
			  		
			  		//events
			  		var appointments = events.get(startStamp, stopStamp);
					var list = '';
					
					if(appointments){
						$.each( appointments, function( key, value ) {
						
						  if($('#sideEvent_'+value.id).length === 0){
						  	
							  var startDate = new Date(value.startStamp*1000);
							  var endDate = new Date(value.stopStamp*1000);
							  list += '<li data-eventId="'+value.id+'" onclick="events.show('+value.id+', '+privacy.authorize(value.privacy, value.user)+');" id="sideEvent_'+value.id+'"><span class="icon white-bell white"></span><span class="icon blue-bell"></span>'+value.title+'</li>';
							
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
        this.pad2 = function(d) {
            return (d < 10) ? '0' + d.toString() : d.toString();
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
            
            return api.query('api/calendar/events/getEventData/',{eventId : eventId});
	};
	
	this.create = function(startStamp, stopStamp, title, place, privacy, callback){
            var result="";
            $.ajax({
                url:"api/calendar/events/create/",
                async: false,  
                type: "POST",
                data: $.param({startStamp : startStamp,stopStamp : stopStamp,title : title,place : place})+'&'+privacy,
                success:function(data) {
                   result = data;
                   if(typeof callback === 'function'){
                       callback(); //execute callback if var callback is function
                   }
                }
            });
            return result;
				   
	};
        
	this.addForm = function(startstamp){
			  		var d = new Date(startstamp*1000);
			  		
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
                                        field0['inputName'] = 'eventTitle';
                                        field0['type'] = 'text';
                                        fieldArray[0] = field0;

                                        var field1 = [];
                                        field1['caption'] = 'Place';
                                        field1['inputName'] = 'eventPlace';
                                        field1['type'] = 'text';
                                        fieldArray[1] = field1;


                                        var field = "<input type='text' name='startDate' id='startDate' class='startDate datepicker' value='"+formattedDate+"' style='width: 72px;margin-right:5px;'>";
					field += "&nbsp;<input type='text' name='startTime' id='startTime' class='startTime eventTime' value='15:30' style='width: 37px;'><span class='endDate eventTime'>&nbsp;to&nbsp;</span>";
					field += "&nbsp;<input type='text' name='endTime' id='endTime' class='endTime eventTime' value='16:30' style='width: 37px;'>";
					  		    	

                                        var field2 = [];
                                        field2['caption'] = 'Day';
                                        field2['inputName'] = 'day';
                                        field2['type'] = 'html';
                                        field2['value'] = field;
                                        fieldArray[2] = field2;

                                        var field3 = [];
                                        field3['caption'] = 'All-Day';
                                        field3['type'] = 'html';
                                        field3['value'] = "<input type='checkbox' name='allDay' id='eventAllDay' value='true' onclick='$(\'.eventTime\').toggle();'>";
                                        fieldArray[3] = field3;

                                        var field4 = [];
                                        field4['caption'] = 'Privacy';
                                        field4['type'] = 'privacy';
                                        field4['value'] = 'f//f';
                                        fieldArray[4] = field4;

                                        var field5 = [];
                                        field5['caption'] = '';
                                        field5['type'] = 'html';
                                        field5['value'] = "<div style='margin-top:30px'></div>";
                                        fieldArray[5] = field5;

                                        var field6 = [];
                                        field6['caption'] = 'Users';
                                        field6['type'] = 'html';
                                        field6['value'] = "<div class='userSelectionInput'></div>";
                                        fieldArray[6] = field6;

                                        var modalOptions = {};
                                        modalOptions['buttonTitle'] = 'Create Event';

                                        modalOptions['action'] = function(){
                                                if($('#eventTitle').val().length > 0 && $('#startDate').val().length > 0 && $('#endTime').val().length > 0){
                                                        var callback = function(){
                                                            calendar.loadEvents();
                                                            calendar.loadEventsIntoSide(new Date(calendar.shownTimeObject.getFullYear(), calendar.shownTimeObject.getMonth(), 1, 0, 0, 0));
                                                            gui.alert('The Event has been added', 'Events');
                                                            $('.blueModal').slideUp()
                                                        };
                                                        
                                                        var startDate = $('#startDate').val();
                                                        if($('#eventAllDay').is('checked')){
                                                            var startDate = new Date($('#startDate').val()+"-00:00");
                                                            var stopDate = new Date($('#startDate').val()+"-23:59");
                                                            
                                                            var startTime = startDate.getTime()/1000-3599;
                                                            var stopTime = stopDate.getTime()/1000-3599;
                                                            //$startTime = strtotime($_POST['startDate']."-00:00")-3599;
                                                            //$stopTime = strtotime($_POST['startDate']."-23:59")-3599; //no idea why, but it works	
                                                        }else{
                                                            var startDate = new Date($('#startDate').val()+"-"+$('#startTime').val());
                                                            var stopDate = new Date($('#startDate').val()+"-"+$('#endTime').val());
                                                            
                                                            var startTime = startDate.getTime()/1000;
                                                            var stopTime = stopDate.getTime()/1000;
                                                            //$startTime = strtotime($_POST['startDate']."-".$_POST['startTime']);
                                                            //$stopTime = strtotime($_POST['startDate']."-".$_POST['endTime']);
                                                        }
                                                        
                                                        events.create(startTime, stopTime, $('#createEventFormContainer #eventTitle').val(),  $('#createEventFormContainer #eventPlace').val(), $('#createEventFormContainer #privacyField :input').serialize(), callback);

                                                }else{
                                                    gui.alert('You need to fill out all the fields.');
                                                }};
                                        formModal.init('Create Event', '<div id="createEventFormContainer"></div>', modalOptions);
                                        gui.createForm('#createEventFormContainer',fieldArray, options);
                                        
                                        
                                        //init datepicker in modal
                                        $('.datepicker').datepicker({
                                            changeMonth: true,
                                            changeYear: true
                                          });

                                        $('.userSelectionInput').userSearch();
              		
			  	};
	
	this.join = function(originalEventId, addToVisitors){
            
            return api.query('api/calendar/events/join/',{originalEventId: originalEventId,addToVisitors: addToVisitors});
			  		
	};
	
	this.joinForm = function(originalEventId){
			  		
			  		var eventData = events.getData(originalEventId);
			  		
			  		var content  = '<form id="joinEvent" method="post">';
			  				content += '<table class="formTable">';
					  		    content += '<tr>';
					  		    	content += '<td style="width:65px;">';
					  		    	content += '';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += '<input type="hidden" id="originalEventId" value='+originalEventId+'>';
					  		    	content += 'If you join an event the even will be added to your personal calendar.';
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td>&nbsp;</td>';
					  		    content += '</tr>';
					  		    content += '<tr>';
					  		    	content += '<td align="right">';
					  		    	content += '<input type="checkbox" checked="checked" id="addToVisitors">';
					  		    	content += '</td>';
					  		    	content += '<td>';
					  		    	content += 'Add yourself to the <b>public</b> guest list.';
					  		    	content += '</td>';
					  		    content += '</tr>';
					  		    	
			  		    	content += '</table>';
			  		    content += '</form>';
			  		    
			  		var onSubmit = function(){
                                            events.join($('#joinEvent #originalEventId').val(), $('#joinEvent #addToVisitors').is(':checked'));
			  		};
  					//create modal
              		modal.create('Join the event "'+eventData.title+'"', content, [onSubmit, 'Save']);
			  	};
	
	this.show = function(eventId, editable){
			  		var eventData = events.getData(eventId);
			  		
			  		var startDate = new Date(eventData.startStamp*1000);
			  		var  stopDate = new Date(eventData.stopStamp*1000);
			  		
			  		
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
              		$('.datepicker').datepicker({
                                            changeMonth: true,
                                            changeYear: true
                                          });
              		
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
              					
	              			$.post("api/calendar/events/update/",searchString,function(data){
					            if(empty(data)){
					            	gui.alert('The event has been updated.', 'Events');
					            	$('.blueModal').slideUp();
					            }else{
					            	gui.alert(data, 'Events', 'error');
					            }
					        });

              			}else{
              				gui.alert('You need to fill out all the fields.', 'Events', 'error');
              			}
              			
              			
              			return false;
              		});
                        
                        
                        
                        
                                        
                                        
                                        var formModal = new gui.modal();

                                        var fieldArray = [];
                                        var options = [];
                                        options['headline'] = '';
                                        options['buttonTitle'] = 'Save';
                                        options['noButtons'] = true;

                                        var field0 = [];
                                        field0['caption'] = 'Title';
                                        field0['required'] = true;
                                        field0['inputName'] = 'eventTitle';
                                        field0['type'] = 'text';
                                        fieldArray[0] = field0;

                                        var field1 = [];
                                        field1['caption'] = 'Place';
                                        field1['inputName'] = 'eventPlace';
                                        field1['type'] = 'text';
                                        fieldArray[1] = field1;


                                        var field = "<input type='text' name='startDate' id='startDate' class='startDate datepicker' value='"+formattedDate+"' style='width: 72px;margin-right:5px;'>";
					field += "&nbsp;<input type='text' name='startTime' id='startTime' class='startTime eventTime' value='15:30' style='width: 37px;'><span class='endDate eventTime'>&nbsp;to&nbsp;</span>";
					field += "&nbsp;<input type='text' name='endTime' id='endTime' class='endTime eventTime' value='16:30' style='width: 37px;'>";
					  		    	

                                        var field2 = [];
                                        field2['caption'] = 'Day';
                                        field2['inputName'] = 'day';
                                        field2['type'] = 'html';
                                        field2['value'] = field;
                                        fieldArray[2] = field2;

                                        var field3 = [];
                                        field3['caption'] = 'All-Day';
                                        field3['type'] = 'html';
                                        field3['value'] = "<input type='checkbox' name='allDay' id='eventAllDay' value='true' onclick='$(\'.eventTime\').toggle();'>";
                                        fieldArray[3] = field3;

                                        var field4 = [];
                                        field4['caption'] = 'Privacy';
                                        field4['type'] = 'privacy';
                                        field4['value'] = 'f//f';
                                        fieldArray[4] = field4;

                                        var field5 = [];
                                        field5['caption'] = '';
                                        field5['type'] = 'html';
                                        field5['value'] = "<div style='margin-top:30px'></div>";
                                        fieldArray[5] = field5;

                                        var field6 = [];
                                        field6['caption'] = 'Users';
                                        field6['type'] = 'html';
                                        field6['value'] = "<div class='userSelectionInput'></div>";
                                        fieldArray[6] = field6;

                                        var modalOptions = {};
                                        modalOptions['buttonTitle'] = 'Create Event';

                                        modalOptions['action'] = function(){
                                                if($('#eventTitle').val().length > 0 && $('#startDate').val().length > 0 && $('#endTime').val().length > 0){
                                                        var callback = function(){
                                                            calendar.loadEvents();
                                                            calendar.loadEventsIntoSide(new Date(calendar.shownTimeObject.getFullYear(), calendar.shownTimeObject.getMonth(), 1, 0, 0, 0));
                                                            gui.alert('The Event has been added', 'Events');
                                                            $('.blueModal').slideUp()
                                                        };
                                                        
                                                        var startDate = $('#startDate').val();
                                                        if($('#eventAllDay').is('checked')){
                                                            var startDate = new Date($('#startDate').val()+"-00:00");
                                                            var stopDate = new Date($('#startDate').val()+"-23:59");
                                                            
                                                            var startTime = startDate.getTime()/1000-3599;
                                                            var stopTime = stopDate.getTime()/1000-3599;
                                                            //$startTime = strtotime($_POST['startDate']."-00:00")-3599;
                                                            //$stopTime = strtotime($_POST['startDate']."-23:59")-3599; //no idea why, but it works	
                                                        }else{
                                                            var startDate = new Date($('#startDate').val()+"-"+$('#startTime').val());
                                                            var stopDate = new Date($('#startDate').val()+"-"+$('#endTime').val());
                                                            
                                                            var startTime = startDate.getTime()/1000;
                                                            var stopTime = stopDate.getTime()/1000;
                                                            //$startTime = strtotime($_POST['startDate']."-".$_POST['startTime']);
                                                            //$stopTime = strtotime($_POST['startDate']."-".$_POST['endTime']);
                                                        }
                                                        
                                                        events.create(startTime, stopTime, $('#createEventFormContainer #eventTitle').val(),  $('#createEventFormContainer #eventPlace').val(), $('#createEventFormContainer #privacyField :input').serialize(), callback);

                                                }else{
                                                    gui.alert('You need to fill out all the fields.');
                                                }};
                                        formModal.init('Create Event', '<div id="createEventFormContainer"></div>', modalOptions);
                                        gui.createForm('#createEventFormContainer',fieldArray, options);
                                        
                                        
                                        //init datepicker in modal
                                        $('.datepicker').datepicker({
                                            changeMonth: true,
                                            changeYear: true
                                          });

                                        $('.userSelectionInput').userSearch();
                        
                        
                        
			  		
			  	};
	
	this.get = function(startStamp, stopStamp, privacy){
            
            if(typeof startStamp === 'object'){
                var requests = [];
                $.each(startStamp,function(index, value){
                    //you can also enter a single type instead of multiple values
                    requests.push({ startStamp: startStamp[index],stopStamp: stopStamp[index],privacy: privacy});
                });
                    return api.query('api/calendar/events/getEvents/', { request: requests});
            }else
                return api.query('api/calendar/events/getEvents/',{request: [{ startStamp: startStamp,stopStamp: stopStamp,privacy: privacy}]})[0];
            
            
            return api.query('api/calendar/events/getEvents/',{ startStamp: startStamp,stopStamp: stopStamp,privacy: privacy});
	};
};