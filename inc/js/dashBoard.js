    //This file is published by transparency - everywhere with the best deeds.
    //Check transparency - everywhere.com for further information.
    //Licensed under the CC License, Version 4.0 (the "License");
    //you may not use this file except in compliance with the License.
    //You may obtain a copy of the License at
    //
    //https://creativecommons.org/licenses/by/4.0/legalcode
    //
    //Unless required by applicable law or agreed to in writing, software
    //distributed under the License is distributed on an "AS IS" BASIS,
    //WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    //See the License for the specific language governing permissions and
    //limitations under the License.
//@author nicZem for Tranpanrency - everywhere.com
        
        

    
function initDashClose(){
	//init dashcloses
	$('.dashBox .dashClose').click(function(){
		$(this).parent('.dashBox').slideUp();
	});
}
    
//dashboard
var dashBoard = new function(){
	
	
	
	this.view = 'down'; // up or down 
	
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
                
		var content = "<ul class=\"appList\">";
                
                $.each(applications.getList(),function(index,value){
                    content+= "<li onclick=\""+value.className+".show()\" onmouseup=\"closeDockMenu()\"><img src=\""+value.icon+"\" border=\"0\" height=\"16\">"+value.title+"</li>";
                });
            
                $('#dashBoard #scrollFrame').prepend(dashBoard.generateDashBox('Applications', content, '', 'appBox'));
                
	
	};
	
	this.slideUp = function(){
            $('#dashGrid').animate({ marginBottom: 0}, // what we are animating
                    1000, // how fast we are animating
                    'linear', // the type of easing
                    function() { // the callback
                        $('#dock #toggleDashboardButton').removeClass('white-chevron-up').addClass('white-chevron-down');

                    });
                    this.view = 'up';
	};
	this.slideDown = function(){
		
			$('#dashGrid').animate({marginBottom: -300}, 300, function() {
                                $('#dock #toggleDashboardButton').removeClass('white-chevron-down').addClass('white-chevron-up');
			});
			this.view = 'down';
	};
	this.toggle = function(){
		if(this.view === 'up'){
			this.slideDown();
		}else if(this.view === 'down'){
			this.slideUp();
		}
	};
        
        this.generateDashBox = function(title, content, footer, id){
            var output = '';
            if(id){
                output += "<div class=\"dashBox\" id=\""+id+".Box\">";
            }
		
			output += "<a class=\"dashClose\"></a>";
			output += "<header>"+title+"</header>";
		
			output += "<div class=\"content\">"+content+"</div>";
			
			if(!empty(footer)){
			output += "<footer>"+footer+"</footer>";
			}
			
		if(id){
			output += "</div>";
		}
		return output;
        };
        this.updateDashBox = function(type){
            updateDashbox(type);
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

