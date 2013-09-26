		<style>
			#calendar{
				position: relative;
			}
			
			#calendar header{
			}
			
			#calendar header span{
				width: 14.2%;
				float:left;
				
			}
			
			#calendar #main{
				position: absolute;
				top: 0px;
				right: 100px;
				bottom: 0px;
				left: 0px;
			}
			
			#calendar #side{
				width: 100px;
				background: #c9c9c9;
				position: absolute;
				top: 0px;
				right: 0px;
				bottom: 0px;
			}
			
			#calendar #side #months{
				list-style: none;
				padding:0px;
			}
			
			#calendar #side #months .current{
				font-size: 15pt;
			}
			
			.day ul{
				margin:0px;
				padding:0px;
				list-style:none;
				font-size: 11px;
				
			}
		
			.calendarFrame{
				width: 100%;
			}
			
			.calendarFrame .day{
				height: 60px;
				width: 14.2%;
				float:left;
				border:1px solid #c9c9c9;
			   -moz-box-sizing:    border-box;
			   -webkit-box-sizing: border-box;
			    box-sizing:        border-box;
			}
			
			.calendarFrame .day header{
				border-bottom: 1px solid #c9c9c9;
				padding: 2px;
				font-size: 10pt;
			}
			
			.calendarFrame .day header{
				border-bottom: 1px solid #c9c9c9;
				padding: 2px;
				font-size: 10pt;
			}
			
			.calendarFrame .day.today header{
				border-bottom: 1px solid #c9c9c9;
				padding: 2px;
				font-size: 10pt;
				background: #c9c9c9;
			}

			.calendarFrame .day:first-child {
				background:rgb(223, 236, 250)
				}
				
			.calendarFrame .day:nth-child(2n+3) {
				background:rgb(223, 236, 250)
				}

		</style>
	<div class="fenster">
		<header>
			Calendar
		</header>
		<div id="calendar" class="inhalt">
			<div id="main">
				<header>
					<span>Monday</span>
					<span>Tuesday</span>
					<span>Wednesday</span>
					<span>Thursday</span>
					<span>Friday</span>
					<span>Saturday</span>
					<span>Sunday</span>
				</header>
				<div class="calendarFrame">
					
				</div>
			</div>
			<div id="side">
				<ul id="months">
					
				</ul>
			</div>
		</div>
	</div>
		<script>
		
		

				var monthName = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")
				
			//transfers a number to be always shown with two digits
			function beautifyDate(value){
				if(value.length === 1)
					return '0'+value;
				else if(value.length === 2)
					return value;
				else
					return value;
			}
			
			//gets all appointments for a day
			function getAppointmentsForDay(time){
				var array = [];
				array[0] = 'startStamp';
				
				return array;
			}
		   
			//appends a daybox to the calender
			function appendDayToCalender(time){
				
					var selection;
					var dayDateObject = new Date(time * 1000);
					
					var today = new Date();
						today.setHours(0,0,0,0);
						
					var month = beautifyDate(dayDateObject.getMonth()+1);
					var date = beautifyDate(dayDateObject.getDate());
						 month = beautifyDate(month);
						 date = beautifyDate(date);
					
					var dayClass = ''; //css class that is added to the day
					
					
						if(today.getTime()/1000 == time){
							dayClass = 'today';
						}else{
							dayClass = '';
						}
					
					var appointments = getAppointmentsForDay(time);
					var list = '<ul><li>'+appointments+'</li></ul>';
					var day = '<div class="day '+dayClass+'"><header>'+date+'.'+month+'</header>'+list+'</div>';
					
					$('.calendarFrame').append(day);
			}
			
			
			function loadMonth(selection){
				
				$('.calendarFrame').html('');
				
				if(selection == undefined){
					var d = new Date(); //now
				}else{
					var temp = new Date();
					var d = new Date(temp.getFullYear(), selection, 1, 0, 0, 0);
				}
				
				var firstDayOfMonth = new Date(d.getFullYear(), d.getMonth(), 1, 0, 0, 0);
				var lastSecondOfMonth = new Date(d.getFullYear(), d.getMonth() + 1, 0, 0, 0, 0);
				
				var month = lastSecondOfMonth.getMonth();
				
				var firstDayOfMonthWeekday = firstDayOfMonth.getDay();
				
					firstSecondOfMonth = (firstDayOfMonth.getTime()/1000);
					lastSecondOfMonth = (lastSecondOfMonth.getTime()/1000);
					
				//get unixtime of today, 0:00
				var today = new Date(d.getFullYear(),d.getMonth(), d.getDate(),0,0,0);
				var unixTimeToday = today.getTime()/1000;
				var daysInMonth = (lastSecondOfMonth-firstSecondOfMonth)/86400;
				
				var offset = new Array("6", "5", "4", "3", "2", "1", "0");
				
				
				
				$('#side #months').html('<li onclick="loadMonth('+(d.getMonth()-1)+')">'+monthName[d.getMonth()-1]+'</li><li class="current">'+monthName[d.getMonth()]+'</li><li onclick="loadMonth('+(d.getMonth()+1)+')">'+monthName[d.getMonth()+1]+'</li>');
				
				var loadedDays = 0;
				var offSetStartTime = firstSecondOfMonth-(offset[firstDayOfMonthWeekday]*86400); //get starttime for first offset day
				
				
				while(loadedDays < offset[firstDayOfMonthWeekday]){
					appendDayToCalender(offSetStartTime);
					loadedDays++;
					offSetStartTime = offSetStartTime+86400;
				}
				
				
				daysInCalender = 30; //forgets day offset if first of month is not a monday
				loadedDays = 0;
				var whileTime = firstSecondOfMonth;
				while(loadedDays < daysInCalender){
					
					
					appendDayToCalender(whileTime);
					whileTime = whileTime+86400;
					loadedDays++;
				}


			}
			loadMonth();
			initDraggable();
		</script>