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


var applications = new function(){
    this.getList = function(){
        
        var apps = [];
        
        //reader
        var app = [];
        app['title'] = 'reader';
        app['source'] = 'reader.js';
        app['className'] = 'reader'; // name of the the javascript class object
        app['active'] = true;
        app['position'] = {width: 5, height:  4, top: 0, left: 4, hidden: true};
        app['icon'] = 'gfx/applicationIcons/white/reader.svg';
        apps[0] = app;
        
        //filesystem
        var app = [];
        app['title'] = 'filesystem';
        app['source'] = 'filesystem.js';
        app['className'] = 'filesystem'; // name of the the javascript class object
        app['active'] = true;
        app['position'] = {width: 8, height:  5, top: 0, left: 2};
        app['icon'] = 'gfx/applicationIcons/white/filesystem.svg';
        apps[1] = app;
        
        
        
        
        if(proofLogin()){
            //feed
            var app = [];
            app['title'] = 'feed';
            app['source'] = 'feed.js';
            app['active'] = true;
            app['className'] = 'feed'; // name of the the javascript class object
            app['position'] = {width: 2, height:  5, top: 0, left: 0};
            app['icon'] = 'gfx/applicationIcons/white/feed.svg';
            apps[2] = app;
            
            //chat
            var app = [];
            app['title'] = 'chat';
            app['source'] = 'chat.js';
            app['className'] = 'chat'; // name of the the javascript class object
            app['active'] = true;
            app['position'] = {width: 2, height:  2, top: 0, left: 5, hidden:true};
            app['icon'] = 'gfx/applicationIcons/white/chat.svg';
            apps[3] = app;

            //settings
            var app = [];
            app['title'] = 'settings';
            app['source'] = 'settings.js';
            app['className'] = 'settings'; // name of the the javascript class object
            app['active'] = false;
            app['position'] = {width: 2, height:  5, top: 0, left: 0};
            app['icon'] = 'gfx/applicationIcons/white/settings.svg';

            apps[4] = app;

            //calendar
            var app = [];
            app['title'] = 'calendar';
            app['source'] = 'calendar.js';
            app['className'] = 'calendar'; // name of the the javascript class object
            app['active'] = false;
            app['position'] = {width: 2, height:  2, top: 0, left: 9};
            app['icon'] = 'gfx/applicationIcons/white/calendar.svg';
            apps[5] = app;


            //buddylist
            var app = [];
            app['title'] = 'buddylist';
            app['className'] = 'buddylist'; // name of the the javascript class object
            app['source'] = 'buddylist.js';
            app['active'] = true;
            app['position'] = {width: 2, height:  5, top: 0, left: 9};
            app['icon'] = 'gfx/applicationIcons/white/buddylist.svg';
            apps[6] = app;
            
            //buddylist
            var app = [];
            app['title'] = 'player';
            app['className'] = 'player'; // name of the the javascript class object
            app['source'] = 'player.js';
            app['active'] = true;
            app['position'] = {width: 2, height:  5, top: 0, left: 9};
            app['icon'] = 'gfx/applicationIcons/white/player.svg';
            apps[7] = app;
        }
        
        return apps;
    };
    
    this.init = function(){
        var sessionApplications = this.getList();
        
        $.each(sessionApplications, function(index, value){
            if(value)
            if(value['source']){
                gui.loadScript('inc/js/'+value['source']);
                if(value['active']){

                    //eval is evil!!!!!!!! maybe we should look for another solution
                    eval(value['className']).init();
                };
            }
        });
    };
    
};

              
var application = function(id){
        this.id = id;
	this.create = function(title, type, value, style){
            var id = this.id;
            var content;
            if(type === 'html'){
                content = value;
            }else if(type === 'url'){
                content = api.query(value,{});
            }
            
		var windowStyle = '';
		
                
                //check if style parameters are set
                //for width, height, top, left
                //if value is not nummeric(eg '222px') use value,
                //otherwise use pixelraster(see gui class)
		if(typeof style['width'] != 'undefined'){
                    if(isNaN(style['width'])){
			windowStyle +='width:'+style['width']+';';
                    }else{
                        windowStyle +='width:'+gui.getRasterWidth(style['width'])+'px;';
                    }
		}
                
		if(typeof style['height'] != 'undefined'){
                    if(isNaN(style['width'])){
			windowStyle +='height:'+style['width']+';';
                    }else{
                        windowStyle +='height:'+gui.getRasterHeight(style['height'])+'px;';
                    }
		}
                
		if(typeof style['top'] != 'undefined'){
                    if(isNaN(style['top'])){
			windowStyle +='top:'+style['top']+';';
                    }else{
                        windowStyle +='top:'+gui.getRasterMarginTop(style['top'])+'px;';
                    }
		}
                
		if(typeof style['left'] != 'undefined'){
                    if(isNaN(style['left'])){
			windowStyle +='left:'+style['left']+';';
                    }else{
                        windowStyle +='left:'+gui.getRasterMarginLeft(style['left'])+'px;';
                    }
		}
                
                windowStyle +='display:none;';
                
		
		
		var output = '<div class="fenster" id="'+id+'" style="'+windowStyle+'">';
			output += '<header class="titel">';
			output += '<p>'+title+'&nbsp;</p>';
			output += '<p class="windowMenu">';
				output += '<a href="javascript:'+id+'.applicationVar.hide();"><span class="icon dark-minimize"></span></a>';
				output += '<a href="#" onclick="'+id+'.applicationVar.fullscreen();" class="fullScreenIcon"><span class="icon dark-maximize"></span></a>';
                                output += '<a href="#"><span class="icon dark-close"></span></a>'
			output += '</p>';
		output += '</header>';
		output += '<div class="inhalt autoflow" id="'+id+'Main">'+content+'</div>';
		output += '</div>';

		
		$('#bodywrap').append(output);
                
		if(typeof style['hidden'] == 'undefined' || style['hidden'] !== true){
                        $('#'+id+'.fenster').fadeIn(4000);
		}
                  
		
              init.draggableApplications();
              init.resizableApplications();
              this.onTop();
	};
	
	this.onTop = function(){
            var id = this.id;
		
          $(".fenster").css('z-index', 998);
          $("#"+id+"").css('z-index', 999);
          $("#"+id+"").css('position', 'absolute');
		
	};
	
	this.show = function(){
          var id = this.id;
          this.onTop();
          $("#" + id +"").show();
	};
	
	this.hide = function(){
          var id = this.id;
          $("#" + id +"").hide();
	};
	
	this.fullscreen = function(){
            var moduleId = this.id;
              	$('#'+moduleId+' .fullScreenIcon').attr("onClick",moduleId+".applicationVar.returnFromFullScreen()");
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
	};
	
	this.returnFromFullScreen = function(){
          var id = this.id;
          $('#'+id+' .fullScreenIcon').attr("onClick",id+".applicationVar.fullscreen()");
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
        
