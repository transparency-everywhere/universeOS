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
    /**
    * Defines which applications are loaded and in which order they are
    */


    this.getList = function(){
        
        var apps = [];
        
        //filesystem
        apps[0] =   {
                        'title':'filesystem',
                        'source':'filesystem.js',
                        'className':'filesystem',
                        'active': true,
                        'position':{width: 6, height:  5, top: 7, left: 6},
                        'icon':'gfx/applicationIcons/white/filesystem.svg'
                    };
        
        //reader
        apps[1] =  {
                        'title':'display',
                        'source':'reader.js',
                        'className':'reader',
                        'active': true,
                        'position':{width: 5, height:  4, top: 0, left: 4, hidden: false},
                        'icon':'gfx/applicationIcons/white/filesystem.svg'
                    };
        
        //telescope
        apps[2] =  {
                        'title':'telescope',
                        'source':'telescope.js',
                        'className':'telescope',
                        'active': true,
                        'position':{width: 8, height:  5, top: 0, left: 2, hidden:true},
                        'icon':'gfx/applicationIcons/white/filesystem.svg'
                    };
        
        
        
        
        if(!proofLogin())
            return apps;
        
            //filesystem 
            apps[0] =   {
                        'title':'filesystem',
                        'source':'filesystem.js',
                        'className':'filesystem',
                        'active': true,
                        'position':{width: 6, height:  5, top: 0, left: 3},
                        'icon':'gfx/applicationIcons/white/filesystem.svg'
                    };
            //feed
            //change position with display
            apps[3] = apps[2];
            apps[2] = {
                        'title':'feed',
                        'source':'feed.js',
                        'className':'feed',
                        'active': true,
                        'position':{width: 2, height:  5, top: 0, left: 0},
                        'icon':'gfx/applicationIcons/white/feed.svg'
                    };
            
            //chat
            apps[4] =   {
                        'title':'chat',
                        'source':'chat.js',
                        'className':'chat',
                        'active': true,
                        'position':{width: 2, height:  2, top: 0, left: 5, hidden:true},
                        'icon':'gfx/applicationIcons/white/chat.svg'
                    };

            apps[5] =   {
                        'title':'settings',
                        'source':'settings.js',
                        'className':'settings',
                        'active': false,
                        'position':{width: 2, height:  5, top: 0, left: 0},
                        'icon':'gfx/applicationIcons/white/settings.svg'
                    };

            //calendar
            apps[6] =   {
                        'title':'calendar',
                        'source':'calendar.js',
                        'className':'calendar',
                        'active': false,
                        'position':{width: 8, height:  5, top: 0, left: 2},
                        'icon':'gfx/applicationIcons/white/calendar.svg'
                    };


            //buddylist
            //
            //change places with reader, to use preloading of buddy data to save requests
            apps[7] = apps[1];
            apps[1] =   {
                        'title':'buddylist',
                        'source':'buddylist.js',
                        'className':'buddylist',
                        'active': true,
                        'position':{width: 2, height:  5, top: 0, left: 9},
                        'icon':'gfx/applicationIcons/white/buddylist.svg'
                    };
            
            apps[8] =   {
                        'title':'player',
                        'source':'player.js',
                        'className':'player',
                        'active': true,
                        'position':{width: 2, height:  5, top: 0, left: 9},
                        'icon':'gfx/applicationIcons/white/player.svg'
                    };
//            apps[8] =   {
//                        'title':'App Center',
//                        'source':'appCenter.js',
//                        'className':'appCenter',
//                        'active': false,
//                        'position':{width: 8, height:  5, top: 0, left: 2},
//                        'icon':'gfx/applicationIcons/white/player.svg'
//                    };
        
        return apps;
    };
    /**
    * Loads and initializes scripts which are defined in applications.getList
    */
    this.init = function(options){
        var sessionApplications = this.getList();
        

        $.each(sessionApplications, function(index, value){
            



            if(value){

              if(options.embed){
                if(value.title !== 'filesystem')
                  value.active = false;
              }

              if(value['source']){
                  gui.loadScript('inc/js/'+value['source']);
                  if(value['active']){
                      eval(value['className']).init();
                  };
              }


            }
        });
        
        this.initApplicationSizes();

        if(options.embed){
          switch(options.embed.type){
            case 'folder':
              folders.open(options.embed.itemId);
              filesystem.applicationVar.show();
              filesystem.applicationVar.fullscreen();
            break;
            case 'element':
              folders.open(options.embed.itemId);
              filesystem.applicationVar.show();
              filesystem.applicationVar.fullscreen();
            break;
          }
        }
        
    };
    
    /**
    * Shows application and inits if necessary
    * @param {string} applicationTitle - The title of the global application var.
    */
    this.show = function(applicationTitle){
        if(typeof window[applicationTitle].applicationVar === 'undefined')
            window[applicationTitle].init();
        
        window[applicationTitle].applicationVar.show();
    };
    /**
    * Hides application
    * @param {string} applicationTitle - The title of the global application var.
    */
    this.hide = function(applicationTitle){
        if(typeof window[applicationTitle].applicationVar !== 'undefined')
            window[applicationTitle].applicationVar.show();
    };
    this.hideAll = function(){
        $.each(applications.getList(), function(index,value){
            if(typeof window[value.title] !== 'undefined')
                if(typeof window[value.title].applicationVar !== 'undefined')
                    window[value.title].applicationVar.hide();
        });
    };
    
    this.getStyleFromList = function(title){
        var returnVal;
        $.each(this.getList(), function(index,value){
            if(value.title == title){
                returnVal = value.position;
            }
        });
        return returnVal;
    };

    this.updatePreset = function(presetType){
      api.query("api/user/updateApplicationPreset/", { preset : presetType },function(result){
        alert(result);
      });
    };
    this.getPreset = function(){
      return api.query("api/user/getApplicationPreset/", {});
    };
    this.initApplicationSizes = function(preset){
        if(!preset)
          preset = this.getPreset();
        
        if(proofLogin()){
            
            if(preset === 'all'){
                
                var styleObj = {
                   "filesystem":
                   {
                               "width":gui.getRasterWidth(applications.getStyleFromList('filesystem')['width'])+"px",
                               "height":gui.getRasterHeight(applications.getStyleFromList('filesystem')['height'])+"px",
                               "top":gui.getRasterMarginTop(applications.getStyleFromList('filesystem')['top'])+"px",
                               "left":gui.getRasterMarginLeft(applications.getStyleFromList('filesystem')['left'])+"px",
                               "display":'block'
                   },
                   "feed":
                   {
                               "width":gui.getRasterWidth(applications.getStyleFromList('feed')['width'])+"px",
                               "height":gui.getRasterHeight(applications.getStyleFromList('feed')['height'])+"px",
                               "top":gui.getRasterMarginTop(applications.getStyleFromList('feed')['top'])+"px",
                               "left":gui.getRasterMarginLeft(applications.getStyleFromList('feed')['left'])+"px",
                               "display":'block'
                   },
                   "buddylist":
                   {
                               "width":gui.getRasterWidth(applications.getStyleFromList('buddylist')['width'])+"px",
                               "height":gui.getRasterHeight(applications.getStyleFromList('buddylist')['height'])+"px",
                               "top":gui.getRasterMarginTop(applications.getStyleFromList('buddylist')['top'])+"px",
                               "left":gui.getRasterMarginLeft(applications.getStyleFromList('buddylist')['left'])+"px",
                               "display":'block'
                   }
               };
            } 
            else if(preset === 'social'){
                
                var styleObj = {
                   "reader":
                   {
                               "width":gui.getRasterWidth(applications.getStyleFromList('filesystem')['width'])+"px",
                               "height":gui.getRasterHeight(applications.getStyleFromList('filesystem')['height'])+"px",
                               "top":gui.getRasterMarginTop(applications.getStyleFromList('filesystem')['top'])+"px",
                               "left":gui.getRasterMarginLeft(applications.getStyleFromList('filesystem')['left'])+"px",
                               "display":'block'
                   },
                   "feed":
                   {
                               "width":gui.getRasterWidth(applications.getStyleFromList('feed')['width'])+"px",
                               "height":gui.getRasterHeight(applications.getStyleFromList('feed')['height'])+"px",
                               "top":gui.getRasterMarginTop(applications.getStyleFromList('feed')['top'])+"px",
                               "left":gui.getRasterMarginLeft(applications.getStyleFromList('feed')['left'])+"px",
                               "display":'block'
                   },
                   "buddylist":
                   {
                               "width":gui.getRasterWidth(applications.getStyleFromList('buddylist')['width'])+"px",
                               "height":gui.getRasterHeight(applications.getStyleFromList('buddylist')['height'])+"px",
                               "top":gui.getRasterMarginTop(applications.getStyleFromList('buddylist')['top'])+"px",
                               "left":gui.getRasterMarginLeft(applications.getStyleFromList('buddylist')['left'])+"px",
                               "display":'block'
                   }
               };
            }
            else if(preset === 'creative'){
                
                var styleObj = {
                   "telescope":
                   {
                               "width":gui.getRasterWidth(6)+"px",
                               "height":gui.getRasterHeight(10)+"px",
                               "top":gui.getRasterMarginTop(0)+"px",
                               "left":gui.getRasterMarginLeft(0)+"px",
                               "display":'block'
                   },
                   "feed":
                   {
                               "width":gui.getRasterWidth(3)+"px",
                               "height":gui.getRasterHeight(10)+"px",
                               "top":gui.getRasterMarginTop(0)+"px",
                               "left":gui.getRasterMarginLeft(6)+"px",
                               "display":'block'
                   },
                   "buddylist":
                   {
                               "width":gui.getRasterWidth(2)+"px",
                               "height":gui.getRasterHeight(10)+"px",
                               "top":gui.getRasterMarginTop(0)+"px",
                               "left":gui.getRasterMarginLeft(9)+"px",
                               "display":'block'
                   }
               };
            }
            
        }else{
            var styleObj = {
                               "filesystem":
                               {
                                           "width":gui.getRasterWidth(applications.getStyleFromList('filesystem')['width'])+"px",
                                           "height":gui.getRasterHeight(applications.getStyleFromList('filesystem')['height'])+"px",
                                           "top":gui.getRasterMarginTop(applications.getStyleFromList('filesystem')['top'])+"px",
                                           "left":gui.getRasterMarginLeft(applications.getStyleFromList('filesystem')['left'])+"px",
                                           "display":'block'
                               }
                           };
        }
            
            applications.updateApplicationStyle(styleObj);
    };
    
    this.getStyleForUser = function(){
        return {"filesystem":{"width":"463px","height":"377px","top":"170.812px","left":"408.844px"},"buddylist":{"width":"250px","height":"377px","top":"60.8163px","left":"745.286px"},"feed":{"width":"250px","height":"377px","top":"60.8163px","left":"20.1429px"},"chat":{"width":"302px","height":"329px","top":"158.122px","left":"261.857px"},"player":{"width":"544px","height":"329px","top":"109.469px","left":"181.286px"}};
    };
    
    this.getStyles = function(){
        var result = {};
        $.each(this.getList(), function(index, value){
            if($('#'+value.className).length > 0){
                result[value.title] = {
                    
                    width:$('#'+value.className).css('width'),
                    height:$('#'+value.className).css('height'),
                    top:$('#'+value.className).css('top'),
                    left:$('#'+value.className).css('left')
                };
            }
        });
        return result;
    };
    
    
    
    this.setToUserStyle = function(){
        this.updateApplicationStyle(this.getStyleForUser());
    };
    
    this.updateApplicationStyle = function(stylesObj){
        var applicationList = this.getList();
        var self = this;
        
        $.each(applicationList, function(index,value){
            if(typeof stylesObj[value.className] !== 'undefined'){
                self.setStyle(value.className, stylesObj[value.className]);
            }
        });
        
        
        
    };
    this.setStyle = function(title, attributes){
        $("#" + title + "").css(attributes);
    };
};

              
var application = function(id){
        this.id = id;
	this.create = function(title, type, value, options){
            var id = this.id;
            var applicationDomID =  this.id //used as id attribute of .application
            
            var content;
            if(type === 'html'){
                content = value;
            }else if(type === 'url'){
                content = api.query(value,{});
            }else if(type === 'appCenterApplication'){
                var applicationDomID =  gui.generateId() //used as id attribute of .application
                this.id = applicationDomID;
                //@sec
                content = "<iframe src=\""+value.path+"\" sandbox></iframe>";
            }
		var windowStyle = '';
		
                
                
                if(typeof options != 'undefined'){
                    //check if style parameters are set
                    //for width, height, top, left
                    //if value is not nummeric(eg '222px') use value,
                    //otherwise use pixelraster(see gui class)
                    if(typeof options['width'] != 'undefined'){
                        if(isNaN(options['width'])){
                            windowStyle +='width:'+options['width']+';';
                        }else{
                            windowStyle +='width:'+gui.getRasterWidth(options['width'])+'px;';
                        }
                    }
                    if(typeof options['height'] != 'undefined'){
                        if(isNaN(options['width'])){
                            windowStyle +='height:'+options['width']+';';
                        }else{
                            windowStyle +='height:'+gui.getRasterHeight(options['height'])+'px;';
                        }
                    }

                    if(typeof options['top'] != 'undefined'){
                        if(isNaN(options['top'])){
                            windowStyle +='top:'+options['top']+';';
                        }else{
                            windowStyle +='top:'+gui.getRasterMarginTop(options['top'])+'px;';
                        }
                    }

                    if(typeof options['left'] != 'undefined'){
                        if(isNaN(options['left'])){
                            windowStyle +='left:'+options['left']+';';
                        }else{
                            windowStyle +='left:'+gui.getRasterMarginLeft(options['left'])+'px;';
                        }
                    }
                }
                windowStyle +='display:none;';
                
		
		
		var output = '<div class="fenster" id="'+applicationDomID+'" style="'+windowStyle+'">';
			output += '<header class="titel">';
			output += '<p>'+title+'&nbsp;</p>';
			output += '<p class="windowMenu">';
				output += '<a href="javascript:'+id+'.applicationVar.hide();"><span class="icon  dark-icon dark-minimize"></span><span class="icon blue-icon blue-minimize"></span></a>';
				output += '<a href="#" onclick="'+id+'.applicationVar.fullscreen();" class="fullScreenIcon"><span class="icon dark-icon dark-maximize"></span><span class="icon blue-icon blue-maximize"></span></a>';
                                output += '<a href="javascript:'+id+'.applicationVar.close();"><span class="icon dark-icon dark-close"></span><span class="icon blue-icon blue-close"></span></a>';
			output += '</p>';
		output += '</header>';
		output += '<div class="inhalt autoFlow" id="'+applicationDomID+'Main">'+content+'</div>';
		output += '</div>';

		
		$('#bodywrap').append(output);
                
                if(typeof options != 'undefined'){
                    if((typeof options['hidden'] == 'undefined' || options['hidden'] !== true)){
                            $('#'+applicationDomID+'.fenster').fadeIn(1000);
                    }
                    if(typeof options['onClose'] == 'function'){
                        this.onClose = options['onClose'];
                    };
                }
              init.draggableApplications();
              init.resizableApplications();
              init.applicationOnTop();
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
        
        this.close = function(){
            if(typeof this.onClose === 'function'){
                this.onClose();
            };
        };
	
	this.fullscreen = function(){
            var moduleId = this.id;
              	$('#'+moduleId+' .fullScreenIcon').attr("onClick",moduleId+".applicationVar.returnFromFullScreen()");
              	$('#'+moduleId+' .fullScreenIcon .icon').removeClass('dark-maximize');
              	$('#'+moduleId+' .fullScreenIcon .icon').addClass('dark-contract');
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
          $('#'+id+' .fullScreenIcon .icon').removeClass('dark-contract');
          $('#'+id+' .fullScreenIcon .icon').addClass('dark-maximize');
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
        
