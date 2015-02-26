//initialize
var sourceURL = 'http://localhost/universe';


var usernames = [];
var userPictures = [];

var privateKeys = [];
var messageKeys = [];

var openDialogueInterval;

var focus = true;

$(document).ready(function(){
        
        universe.init();
    
});

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
      
      
      
//              
//          $("#buddylist").css({
//          'top' : offsetTop,
//          'right' : offsetRight+20,
//          'width' : widthSm,
//          'height' : heightBig,
//          'z-index' : '9998'
//              });
//          
//          $("#feed").css({
//          'top' : offsetTop+20,
//          'right' : offsetRight,
//          'width' : widthSm,
//          'height' : heightBig,
//          'z-index' : '9997'
//              });
//              
//              
//          $("#chat").css({
//          'top' : offsetTop,
//          'left' : offsetLeft,
//          'width' : widthBig,
//          'height' : heightBig,
//          'z-index' : '997'
//              });
//              
//          $("#filesystem").css({
//          'top' : offsetTop+20,
//          'left' : offsetLeft+20,
//          'width' : widthBig,
//          'height' : heightBig,
//          'z-index' : '998'
//              });
//              
//          $("#reader").css({
//          'top' : offsetTop+40,
//          'left' : offsetLeft+40,
//          'width' : widthBig,
//          'height' : heightBig,
//          'z-index' : '999'
//              });
	};
	
	this.dashBox = function(){
            //init dashcloses 
			$('.dashBox .dashClose').click(function(){
				$(this).parent('.dashBox').slideUp();
			});	
	};
	
//	this.toolTipper = function(){
//  
//  
//
//          $(document).mousemove(function(event){
//              window.mouseX = event.pageX;
//              window.mouseY = event.pageY;
//              $('.mousePop').hide();
//          });
//          
//          
//          
//          //initialize mousePop(tooltip)
//          $('.tooltipper').mouseenter(function(){
//              
//              var type = $(this).attr("data-popType");
//              var id = $(this).attr("data-typeId");
//              var text = $(this).attr("data-text");
//              mousePop(type, id, text);
//          }).mouseleave(function(){
//              $('.mousePop').hide();
//          });
//		
//	};
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
		
		//this.toolTipper();
		this.search();
		
		dashBoard.init();
		//fade in applications
                
                $('*').on('scroll',function(){
                 $('.itemSettingsWindow, .rightClick').hide();
                });
                
                $('body').on('click',function(){
                 $('.itemSettingsWindow, .rightClick').hide();
                });
                
                item.initSettingsToggle();
          
	};
	
};

//gui.loadScript is defined double because it is needed to load the gui class
var gui = new function(){
    this.loadScript = function(url){
        // get some kind of XMLHttpRequest
        var xhrObj = new XMLHttpRequest();
        // open and send a synchronous request
        xhrObj.open('GET', url, false);
        xhrObj.send('');
        // add the returned content to a newly created script tag
        var se = document.createElement('script');
        se.type = "text/javascript";
        se.text = xhrObj.responseText;
        document.getElementsByTagName('head')[0].appendChild(se);
    };
};

var universe = new function(){
    this.notificationArray = [];
    this.init = function(){
        
        gui.loadScript('inc/js/gui.js');
        
        gui.loadScript('inc/js/item.js');
        
        gui.loadScript('inc/js/links.js');
        
        gui.loadScript('inc/js/api.js');
        
        gui.loadScript('inc/js/folders.js');
        
        gui.loadScript('inc/js/elements.js');
        
        gui.loadScript('inc/js/fav.js');
        
        gui.loadScript('inc/js/playlists.js');
        
        gui.loadScript('inc/js/notification.js');
        
        gui.loadScript('inc/js/tasks.js');
        
        gui.loadScript('inc/js/dashBoard.js');
        
        gui.loadScript('inc/js/browser.js');
        
        gui.loadScript('inc/js/UFF.js');
        
        gui.loadScript('inc/js/comments.js');
        
        gui.loadScript('inc/js/media.js');
        
        gui.loadScript('inc/js/application.js');
        
        gui.loadScript('inc/js/debug.js');
        
        gui.loadScript('inc/js/im.js');
        
        gui.loadScript('inc/js/groups.js');
        
        
        //gui.loadScript('inc/js/calendar.js');
        
        
        applications.init();
        
        
        //init draggable windows
        init.GUI();
        
        //init bootstrap popover
        $('.bsPopOver').popover();
        
        //init bootstrap alert
        $(".alert").alert();
        
        //init reload
        if(proofLogin()){
            setInterval(function()
            {
                universe.reload();
            }, 3000);
        }


        //loads clock into the dock, yeah.
        clock();
                
        
    };
    this.reload = function(){
        //fetch request data like open filebrowsers & feeds
        var requests = [];
        
        //push uff documents to request
        $.each(reader.uffChecksums,function(index, value){
                    if(typeof(value) != 'undefined'){
                        requests.push({
                                                            action : 'UFF',
                                                            subaction:'sync',
                                                            data: {
                                                                file_id:index,
                                                                checksum:value
                                                            }
                        });
                    }
                });
        
        if(proofLogin())
        //push buddylist checksum
        requests.push({
                    action:'buddylist',
                    subaction:'reload',
                    data: {
                        buddy_checksum: buddylist.checksum
                    }
                });
                
                
        //sync chat request
        requests.push({
            action : 'IM',
            subaction:'sync',
            data: {
                last_message_received:im.lastMessageReceived
            }
        });
        
        
        //sync feed request
        var feedsArray = [];
        $('.feedFrame').each(function(){
            
            //@bug
            if($(this).data('type') != 'group'){
                               feedsArray.push({'type':$(this).data('type'), 'last_feed_received':$(this).data('last')});
                               console.log($(this).data('type'));
            }
        });
        requests.push({
            action : 'feed',
            subaction: 'sync',
            data:feedsArray
        });

        var requestData = {
            request:requests
        };
        
        var response = api.query('api/reload/', requestData);
        
        //@async
        var temp = this;
        $.each(response, function(key, value){
            temp.handleReloadTypes(value);
        });
        
    };
    this.handleReloadTypes = function(responseElement){
        switch(responseElement.action){
            
            
            case'buddylist':
                if(responseElement.subaction === 'reload'){
                    buddylist.reload();
                }
                else if(responseElement.subaction === 'openRequest'){
                    var notificationId = this.notificationArray.length+1;
                    this.notificationArray[notificationId]
                            = new notification({
                                                    message: User.showPicture(responseElement.data.userid)+useridToUsername(responseElement.data.userid)+' want\'s to be your friend',
                                                    acceptButton:{
                                                        action: 'buddylist.acceptBuddyRequest('+responseElement.data.userid+')',
                                                        value: 'accept'
                                                    },
                                                    cancelButton:{
                                                        action: 'buddylist.denyBuddyRequest('+responseElement.data.userid+')',
                                                        value: 'decline'
                                                    }
                                                });
                    this.notificationArray[notificationId].push();
                    
                }
                break;
            case 'groups':
                if(responseElement.subaction === 'openRequest'){
                    
                    var notificationId = this.notificationArray.length+1;
                    this.notificationArray[notificationId]
                            = new notification({
                                                    message: User.showPicture(responseElement.data.author)+useridToUsername(responseElement.data.author)+' invited you into the group '+responseElement.data.group_title,
                                                    acceptButton:{
                                                        action: 'groups.join('+responseElement.data.group_id+')',
                                                        value: 'join'
                                                    },
                                                    cancelButton:{
                                                        action: 'groups.declineRequest('+responseElement.data.group_id+')',
                                                        value: 'decline'
                                                    }
                                                });
                    this.notificationArray[notificationId].push();
                    
                }
                break;
                
            case 'IM':
                if(responseElement.subaction === 'sync'){
                    im.sync(responseElement.data);
                }
                break;
            case 'UFF':
                if(responseElement.subaction === 'sync'){
                    if($('.uffViewer_'+responseElement.data['file_id']).length > 0){
                            $('.uffViewer_'+responseElement.data['file_id']).html(function(){
                                
                                var caretPosition = getCaretPosition(this);
                                
                                $.get('doit.php?action=loadUff&id='+responseElement.data['file_id']+'&noJq=true', function(uffContent) {
                                    $('.uffViewer_'+responseElement.data['file_id']).val(uffContent);
                                });
                                
                            });
                    }
                };
            case 'notification':
                if(responseElement.subaction === 'push'){
                    var notificationId = this.notificationArray.length+1;
                    this.notificationArray[notificationId]
                            = new notification({
                                                    message: User.showPicture(responseElement.data.user)+useridToUsername(responseElement.data.user)+responseElement.data.description,
                                                    acceptButton:{
                                                        action: responseElement.data.link.replace(/\\/g,''),
                                                        value: 'show'
                                                    },
                                                    cancelButton:{
                                                        action: '',
                                                        value: 'ignore'
                                                    }
                                                });
                    this.notificationArray[notificationId].push();
                    
                    
                };
                break;
            case 'feed':
                if(responseElement.subaction === 'sync'){
                    feed.reload(responseElement.data.type, responseElement.data.typeId);
                };
                break;
        };
    };
};

var User = new function(){
    this.userid;
    this.setUserId = function(id){
        
        this.userid = id;
    };
    this.getBorder = function(lastActivity){
    //every userpicture has a border, this border is green if the lastactivty defines that
    //the user is online and its red if the lastactivity defines that the user is offline.



        var border;
        if(lastActivity === 1){
                border = 'border-color: green';
            }else{
                border = 'border-color: red';
            }

        return border;
    };
    this.showPicture = function(userid, lastActivity, size){
	 
        debug.log('showPicture initialized...');       
        var userpicture = getUserPicture(userid);
        if(typeof lastActivity === 'undefined'){
            debug.log('     get user activity for user '+userid);
            var lastActivity = User.getLastActivity(userid); //get last activity so the border of the userpicture can show if the user is online or offline
        }
        if(typeof size === 'undefined'){
            
            debug.log('     set size to standard size');
            var size = 20;
        }
        
        var radius = size/2;

        var ret;
        ret = '<div class="userPicture userPicture_'+userid+'" style="background: url(\''+userpicture+'\'); '+User.getBorder(lastActivity)+'; width: '+size+'px;height:  '+size+'px;background-size: 100%;border-radius:'+radius+'px"></div>';

        debug.log('     update border for all other userpictures of this user');
        $('.userPicture_'+userid).css('border', User.getBorder(lastActivity)); //update all shown pictures of the user

        debug.log('...showSignature finished');
        return ret;
	            
    };
    this.getLastActivity = function(request){
		            //load data from server
                            var result = api.query('api.php?action=getLastActivity',{ request : request });
		            
		            if(is_numeric(request)){
		                if(result.length > 0){
		                    response = result;
		                }
		            }else{
		                var response = new Array();
		                
		                var lastActivityArray = JSON.parse(result);
		                $.each(lastActivityArray, function(index, value) {
		                        response[index]=parseInt(value); 
		                   });
		                
		                
		            }
		            
		            
		            
		            if(is_numeric(request)){
		                return parseInt(response);
		            }else{
		                return response
		            }
        
    };
    this.getProfileInfo = function(userid){
        return api.query('api/user/getProfileInfo/', { user_id:userid });
    }
    this.getAllData = function(userid){
        //data will only be returned if getUser()==userid or userid is on buddylist of getUser()
        return api.query('api/user/getAllData/', { user_id:userid });
    }
    
    this.getGroups = function(){
        return api.query('api/user/getGroups/', { });
    };
    this.inGroup = function(group_id){
        return jQuery.inArray(group_id, User.getGroups() );
    };
    this.showSignature = function(userid, timestamp, reverse){
        
        debug.log('showSignature for user '+userid+' initialized...');
        var username = useridToUsername(userid);
        
        var output="";
            output += "<div class=\"signature\" style=\"background: #EDEDED; border-bottom: 1px solid #c9c9c9;\">";
            output += "    <table width=\"100%\">";
            output += "        <tr width=\"100%\">";
            if(reverse){
                output += "            <td style=\"width:50px; padding-right:10px;\">"+User.showPicture(userid, undefined, 40)+"<\/td>";
                output += "            <td>";
                output += "                <table>";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 16px;line-height: 17px;\" align=\"left\"><a href=\"#\" onclick=\"showProfile("+userid+");\">"+username+"<\/a><\/td>";
                output += "                    <\/tr>             ";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 12px;line-height: 23px;\">";
                output += "                            <i>";
                output += universeTime(timestamp);
                output += "                            <\/i>";
                output += "                        <\/td>";
                output += "                    <\/tr>";
                output += "                <\/table>";
                output += "            <\/td>";
            }else{
                output += "            <td>";
                output += "                <table>";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 10pt;\">&nbsp;"+username+"<\/td>";
                output += "                    <\/tr>             ";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 08pt;\">&nbsp;<i>";
                output += universeTime(timestamp)+"<\/i>";
                output += "                        <\/td>";
                output += "                    <\/tr>";
                output += "                <\/table>";
                output += "            <\/td>";
                output += "            <td><span class=\"pictureInSignature\">"+User.showPicture(userid, undefined, 40)+"<\/span><\/td>";
            }
            output += "        <\/tr>";
            output += "    <\/table>";
            output += "    <\/div>";

         debug.log('...showSignature finished');
            return output;
        
    };
    this.showProfile = function(user_id){
        var profile_userdata = this.getProfileInfo(user_id);
        var realname = '', city = '', birthdate = '';
        if(typeof profile_userdata['realname'] !== 'undefined'){
            realname = '<span class="realName">'+profile_userdata['realname']+'</span>';
        }
        if(typeof profile_userdata.home !== 'undefined'){
            city += '<span class="city">from '+profile_userdata.home+'</span>';
        }
        if(typeof profile_userdata.place !== 'undefined'){
            city += '<span class="home">from '+profile_userdata.home+'</span>';
        }
        
        var buttons = '';
        if(!buddylist.isBuddy(user_id) || user_id != User.userid){
            buttons = '<a class="button">Add Friend</a>';
        }
        
        
        var output   = '<div class="profile">';
                output += '<header>';
                    output += User.showPicture(user_id);
                        output += '<div class="main">';
                        
                            output += '<span class="userName">';
                                output += useridToUsername(user_id);
                            output += '</span>';
                            output += '<span class="realName">';
                                output += realname;
                            output += '</span>';
                            output += '<span class="place">';
                                output += city;
                            output += '</span>';
                        output += '</div>';
                        
                        output += '<div>';
                        output += buttons;
                        output += '</div>';

                output  += '</header>';
                //output  += '<div class="">';
                    output += '<div class="profileNavLeft">';
                        output += '<ul>';
                            output += '<li data-type="favorites"><img src="gfx/profile/sidebar_fav.svg"/>Favorites</li>';
                            output += '<li data-type="files"><img src="gfx/profile/sidebar_files.svg"/>Files</li>';
                            output += '<li data-type="playlists"><img src="gfx/profile/sidebar_playlist.svg"/>Playlists</li>';
                            output += '<li class="openChat"><img src="gfx/chat_icon.svg"/>Open Chat</li>';
                        output += '</ul>';
                        output += '</div>';
                    output += '<div class="profileMain">';
                        output += '<ul class="profileMainNav">';
                            output += '<li class="active" data-type="activity">Activity</li>';
                            output += '<li data-type="friends">Friends</li>';
                            
                            output += '<li data-type="info">Info</li>';
                            output += '<li data-type="groups">Groups</li>';
                        output += '</ul>';
                        output += '<div class="content">';
                        
                            output += '<div class="profile_tab favorites_tab">';
                                output += fav.show(1);
                            output += '</div>';
                            output += '<div class="profile_tab files_tab">'+filesystem.showFileBrowser(profile_userdata['homefolder'])+'</div>';
                            output += '<div class="profile_tab playlists_tab">';
                                
                                output += '<ul>';
                                var profile_playlists = playlists.getUserPlaylists('show',user_id);
                                $.each(profile_playlists['ids'], function(index, value){
                                    output += '<li onclick="playlists.showInfo(\''+value+'\');"><div><span class="icon icon-playlist"></span>';
                                    output += '<span  style="font-size:18px; padding-top: 5px;" onclick="">'+profile_playlists['titles'][index]+'</span>';
                                    //output += item.showItemSettings('user', value);
                                    output += '</div></li>';
                                });
                                output += '</ul>';
                            output += '</div>';
                            
                            
                            output += '<div class="profile_tab activity_tab" style="display:block"></div>';
                            
                            
                            
                            //buddies
                            output += '<div class="profile_tab friends_tab">';
                                output += '<ul>';
                                var profile_buddylist = buddylist.getBuddies(user_id);
                                $.each(profile_buddylist, function(index, value){
                                    output += '<li><div>'+User.showPicture(value);
                                    output += '<span class="username" style="padding-top: 5px; font-size:18px;">'+useridToUsername(value)+'</span>';
                                    output += '<span class="realname">'+useridToUsername(value)+'</span>';
                                    output += item.showItemSettings('user', value);
                                    output += '</div></li>';
                                });
                                output += '</ul>';
                            output += '</div>';
                            
                            output += '<div class="profile_tab info_tab">';
                            
                                $.each(profile_userdata, function(key, value){
                                    switch(key){
                                        case 'home':
                                            key = 'from';
                                            break;
                                        case 'city':
                                            key = 'lives in';
                                            break;
                                        case 'school1':
                                            key = 'school';
                                            break;
                                        case 'university1':
                                            key = 'university';
                                            break;
                                    }
                                    if(!is_numeric(key)){
                                        output += '<span class="'+key+'"><label>'+key+'</label>'+value+'</span>';
                                    }
                                });
                            
                            output += '</div>';
                            
                            
                            output += '<div class="profile_tab groups_tab">';
                            
                                output += '<ul>';
                                var profile_groups = groups.get(user_id);
                                if(typeof profile_groups !== 'undefined'){
                                    $.each(profile_groups, function(index, value){
                                        output += '<li onclick="groups.show('+value+')"><div><span class="icon icon-group"></span>';
                                        output += '<span class="username" style="font-size:18px; padding-top: 5px;">'+groups.getTitle(value)+'</span>';
                                        output += '</div></li>';
                                    });
                                }
                                output += '</ul>';
                            output += '</div>';
                        
                        output += '</div>';
                    //output += '</div>';
                output  += '</div>';
            output  += '</div>';
        
        reader.tabs.addTab('Profle', 'html', output);
        
        //load feed
        var profileFeed = new Feed('user', '.activity_tab', user_id);
        
        $('.profileMainNav li, .profileNavLeft li').click(function(){
            var type = $(this).attr('data-type');
            $(this).parent().parent().parent().find('.profileMainNav li').removeClass('active');
            $(this).parent().parent().parent().find('.profileNavLeft li').removeClass('active');
            $(this).addClass('active');
            $(this).parent().parent().parent().find('.content .profile_tab').hide();
            $(this).parent().parent().parent().find('.content .'+type+'_tab').show();
        });
        
        
    };
    this.logout = function(){
        gui.alert('Goodbye :)', '');
        api.query('api/user/logout/index.php', {},function(data){
             
            window.location.href=window.location.href;
        });
    };
    this.getRealName = function(userid){
        var realname = this.getProfileInfo(userid)['realname'];
        console.log(realname);
        return realname;
    };
};
          
var privacy = new function(){
	
	this.load = function(selector, val, editable){
			  		if(typeof editable == 'undefined')
			  			editable = false;
			  		
			  		
			  		$.post("api/item/privacy/load/", {
	                       val:val, editable : editable
	                       }, function(result){
		                   		$(selector).html(result);
	                       }, "html");
			  		
			  		
			  	};
	this.show = function(val, editable){

			  		if(typeof editable == 'undefined')
			  			editable = false;
                                    
                                        return api.query('api/item/privacy/load', { val : val, editable : editable });
			  	};
         
        this.updatePrivacy = function(type, item_id, privacy, callback){
            
            var result="";
            $.ajax({
                url:"api/item/privacy/",
                async: false,  
                type: "POST",
                data: $.param({type : type, itemId: item_id})+'&'+privacy,
                success:function(data) {
                   result = data;
                   if(typeof callback === 'function'){
                       callback(); //execute callback if var callback is function
                   }
                }
            });
            return result;
            
        };
        this.showUpdatePrivacyForm = function(type, item_id){
            var title;
            switch(type){
                case 'folder':
                    var itemData = folders.getData(item_id);
                    title = 'Folder '+itemData['name'];
                    break;
                case 'element':
                    var itemData = elements.getData(item_id);
                    title = 'Element '+ itemData['title'];
                    break;
                case 'comment':
                    title = 'Comment';
                    break;
                case 'feed':
                    var itemData = feed.getData(item_id);
                    title = 'Feed';
                    break;
                case 'file':
                    title = 'File '+files.fileIdToFolderTitle(item_id);
                    break;
                case 'link':
                    var itemData = links.getData(item_id);
                    title = 'Link '+linkData['title'];
                    break;
            }
            
            
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = '';
        field0['inputName'] = 'privacy';
        field0['type'] = 'html';
        field0['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[0] = field0;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = title;
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The privacy has been updated');
                $('.blueModal').remove();
            };
            privacy.updatePrivacy(type, item_id, $('#createLinkFormContainer #privacyField :input').serialize(), callback);
        };
        privacy.load('#privacyField', itemData['privacy'], true);
        formModal.init(title, '<div id="createLinkFormContainer"></div>', modalOptions);
        gui.createForm('#createLinkFormContainer',fieldArray, options);
            
        };
	
	//checks if user is authorized, to edit an item with privacy.
	this.authorize = function(privacy, author){
			  		if(author == localStorage.currentUser_userid)
			  			return true;
			  			
			  		var result = api.query('api.php?action=authorize', {privacy: privacy, author: author});
				    
                                        if(parseInt(result) === 1)
                                                     return true;
                                        else
                                                     return false;
			  	};
                                
        this.init = function(){
                                    $('.privacyPublicTrigger').click(function(){
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').addClass('active');
                                        
                                        $('input[type=checkbox].privacyPublicTrigger').prop('checked',true);
                                        
                                        //maybe $(this).next('input[type=checkbox].uncheckPublic')
                                        $('input[type=checkbox].uncheckPublic').prop('checked', false);

                                    });


                                    $('.privacyHiddenTrigger').click(function(){
                                        
                                        $('li.privacyHiddenTrigger').addClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                        $('input[type=checkbox].privacyHiddenTrigger').prop('checked',true);
                                        if(1){
                                            $('.uncheckHidden').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyShowFriendDetails').click(function(){
                                    	$('.privacyShowBuddy').show();
                                    });

                                    $('.privacyCustomTrigger').click(function(){
                                        
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                        
                                        if($(this).is(':checked')){
                                            $('.uncheckCustom').prop('checked', false);
                                        }
                                    });


                                    
                                    $('.privacyOnlyMeTrigger').click(function(){
                                        if($(this).is(':checked')){
                                            $('.uncheckOnlyMe').prop('checked', false);
                                        }
                                    });
                                    
                                    $('.privacyBuddyTrigger').click(function(){
                                    	
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
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
                                        $('li.privacyHiddenTrigger').removeClass('active');
                                        $('li.privacyPublicTrigger').removeClass('active');
                                    	$('.privacyShowGroups').show();
                                    	var groupTriggerId = '.privacyGroupTrigger_'+$(this).data('groupid');
                                        if($(this).is(':checked')){
                                                $('.privacyBuddyTrigger').prop('checked', false);
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
                                    
                                    
                                    
                                    $('.uncheckGroups').click(function(){
                                        
                                        if($(this).is(':checked')){
                                            $('input[type=checkbox].privacyGroupTrigger').prop('checked',false);
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
       
function universeTime(timestamp){
    var time = Math.floor(Date.now() / 1000);
    
    var unTime;
    
     var difference = (time - timestamp);
     if(difference < 60){
         unTime = "just";
     } else if(difference > 60 && difference < 600){
         unTime = "some minutes ago";
     } else if(difference > 600 && difference < 3600){
         unTime = Math.floor(difference / 60);
         unTime = unTime+" minutes ago";
     } else if(difference > 3600 && difference < 3600*24){
         unTime = "one day ago";
     } else if(difference > 3600*24 && difference < 3600*24*31){
         var udTime = Math.floor(difference / 86400);
         unTime = udTime+" days ago";
     } else if(difference > 3600*24*31){
         unTime = "more than one month ago";
     }
     
     return unTime;
};
       
function universeText(string){
    
    string = string.replace(":'(", '<a class="smiley smiley1"></a>');
    string = string.replace(":'(", '<a class="smiley smiley1"></a>');//crying smilye /&#039; = '
    string = string.replace(':|', '<a class="smiley smiley2"></a>');
    string = string.replace(';)', '<a class="smiley smiley3"></a>');
    string = string.replace(':P', '<a class="smiley smiley4"></a>');
    string = string.replace(':-D', '<a class="smiley smiley5"></a>');
    string = string.replace(':D', '<a class="smiley smiley5"></a>');
    string = string.replace(':)', '<a class="smiley smiley6"></a>');
    string = string.replace(':(', '<a class="smiley smiley7"></a>');
    string = string.replace(':-*', '<a class="smiley smiley8"></a>');
    string = string.replace("#(^|[^\"=]{1})(http://|ftp://|mailto:|https://)([^\s<>]+)([\s\n<>]|$)#sm","\\1<a target=\"_blank\" href=\"\\2\\3\">\\3</a>\\4");
       // # Links
        //$str = preg_replace_callback("#\[itemThumb type=(.*)\ typeId=(.*)\]#", 'showChatThumb' , $str);
   return string;
};

function proofLogin(){
    var result = api.query('api.php?action=proofLogin', {});
    if(result == '1'){
                  return true;
              }else{
                  return false;
              }
}
			
function searchUserByString(string, limit){
    var result = api.query('api.php?action=searchUserByString', { string : string, limit : limit });
    
    if(result.length === 0 && result == null){
        result = false;           
    }
    return result;
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
//encryption functions

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
	    	var salt = sec.symDecrypt(key, encryptedSalt); //encrypt salt using key
	    	return salt;
}

function createSalt(type, itemId, receiverType, receiverId, salt){
			var ret;
			$.ajax({
                            url:"api.php?action=createSalt",
                            async: false,  
                            type: "POST",
                            data: { type: type, itemId: itemId, receiverType: receiverType, receiverId: receiverId, salt: salt },
                            success:function(data){
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
		
		var authSaltEncrypted = sec.symEncrypt(shaPass, authSaltDecrypted);
		var keySaltEncrypted = sec.symEncrypt(shaPass, keySaltDecrypted);
		
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
		    var keyHash = hash.SHA512(shaPass+salt);
			
	    	privateKey = sec.symDecrypt(keyHash, encryptedKey); //encrypt private Key using password
                privateKeys[index] = privateKey;
            }else{
                
                privateKey = privateKeys[index];
                
            }
	    	return privateKey;
	    };
            
        this.getPublicKey = function(type, itemId){
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
	};
	
};

var sec =  new function() {
		
    //check pw
    this.scorePassword = function(pass) {
        var score = 0;
        if (!pass)
            return score;

        // award every unique letter until 5 repetitions
        var letters = new Object();
        for (var i=0; i<pass.length; i++) {
            letters[pass[i]] = (letters[pass[i]] || 0) + 1;
            score += 5.0 / letters[pass[i]];
        }

        // bonus points for mixing it up
        var variations = {
            digits: /\d/.test(pass),
            lower: /[a-z]/.test(pass),
            upper: /[A-Z]/.test(pass),
            nonWords: /\W/.test(pass),
        }

        variationCount = 0;
        for (var check in variations) {
            variationCount += (variations[check] == true) ? 1 : 0;
        }
        score += (variationCount - 1) * 10;

        return parseInt(score);
    }
                
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
                                    return cypher.getPrivateKey(type, itemId, salt, password);
    };

    this.symEncrypt = function(key, message){
        var msg;
        msg = CryptoJS.AES.encrypt(message, key);
        return String(msg);
    };
    this.symDecrypt = function(key, message){
        var msg;
        msg = CryptoJS.AES.decrypt(message, key);
        var output = CryptoJS.enc.Utf8.stringify(msg);
        return String(output);
    };

    this.asymEncrypt = function(publicKey, message){
        var encrypt = new JSEncrypt();
        encrypt.setPublicKey(publicKey);
        return encrypt.encrypt(message);
    };

    this.asymDecrypt = function(privateKey, encryptedMessage){
        var message;
        var decrypt = new JSEncrypt();
        decrypt.setPrivateKey(privateKey);
        message = decrypt.decrypt(encryptedMessage);
        return message;
    };


    this.randomString = function(){
			    	return hash.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
			    	
			    };
};
var tabs = function(parentIdentifier){
    this.parentIdentifier = parentIdentifier;
    this.tabHistory = [];
		this.init = function(){
                    parentIdentifier = this.parentIdentifier;
			$(parentIdentifier).append('<div class="tabFrame"><header><ul></ul></header></div>');
                        
		};
                this.initClicks = function(){
                    parentIdentifier = this.parentIdentifier;
                    var classVar = this;
                    $('.tabFrame>header li').click(function(){
                            var tabId = $(this).attr('data-tab');
                            var tabParentIdentifier = $(this).attr('data-parent-identifier');
                            classVar.showTab(tabId);
                            
                            $(parentIdentifier+' .tabFrame header ul li').removeClass('active');
                            $(this).addClass('active');
                    });
                    
                    $('.tabFrame>header li .close').click(function(){
                        var tabId = $(this).parent('li').attr('data-tab');
                        var tabParentIdentifier = $(this).parent('li').attr('data-parent-identifier');
                        classVar.removeTab(tabId);
                    });
                };
		this.addTab = function(title, contentType, content, onClose){
                    parentIdentifier = this.parentIdentifier;
			var numberOfTabs = $(parentIdentifier+' .tabFrame .tab').length;
                        
                        var headerId = randomString(6, '#aA');
                        
			$(parentIdentifier+' .tabFrame header ul').append('<li id='+headerId+' data-tab="'+(numberOfTabs+1)+'" data-parent-identifier="'+parentIdentifier+'" data-title="'+title+'" class="active">'+title+'<span class="close">x</span></li>');

                        $(parentIdentifier+' .tabFrame .tab').hide();
                        
                        if(typeof onClose === 'function'){
                            $('#'+headerId+' .close').click(function(){
                                onClose();
                            });
                        }
                        
                        this.tabHistory.push(numberOfTabs+1);
                        $(parentIdentifier+' .tabFrame').append('<div class="tab tab_'+(numberOfTabs+1)+'">'+content+'</div>');
                        this.initClicks();
                        
                        return numberOfTabs+1;
		};
                this.getTabByTitle = function(tabTitle){
                    parentIdentifier = this.parentIdentifier;
                    var ret;
                    $(parentIdentifier+' .tabFrame header ul li').each(function(){
                        if($(this).attr('data-title') == tabTitle){
                            ret = $(this).attr('data-tab');
                        }
                    });
                            return ret;
                };
		this.showTab = function(tab){
                    this.tabHistory.push(tab);
                    parentIdentifier = this.parentIdentifier;
                    $(parentIdentifier+' .tabFrame .tab').hide();
                    $(parentIdentifier+' .tabFrame .tab.tab_'+tab).show();
		};
                
                //is used after closing a tab to show the last tab that was shown
                this.showLastTab = function(current_tab){
                    var last_tab;
                    var i = this.tabHistory.length-1;
                    
                    
                    //counts down until i = 0 (each array element)
                    //proofs if tab exists
                    //until last tab isnt the current tab or undefined 
                    while((last_tab==current_tab||last_tab==undefined)&&i!==0){
                        if(this.tabExists(this.tabHistory[i]))
                            last_tab = this.tabHistory[i];
                        i--;
                    }
                    this.showTab(last_tab);
                };
                this.tabExists = function(tab_identifier){
                    parentIdentifier = this.parentIdentifier;
                    if($(parentIdentifier+' .tabFrame .tab.tab_'+tab_identifier).length > 0){
                        return true;
                    }else{
                        return false;
                    }
                };
		this.updateTabContent = function(tab_identifier ,content){
                    if(parseInt(tab_identifier) != tab_identifier){
                        tab_identifier = this.getTabByTitle(tab_identifier);
                    }
                    parentIdentifier = this.parentIdentifier;
                    $(parentIdentifier+' .tabFrame .tab.tab_'+tab_identifier).html(content);
			
		};
		this.removeTab = function(tab_identifier){
                    parentIdentifier = this.parentIdentifier;
                    
                    
                    if($(parentIdentifier+' .tabFrame header ul li').length === 1){
                        return true;
                    }
                    
                    this.showLastTab(tab_identifier);
                    $(parentIdentifier+' .tabFrame header ul li').each(function(){
                        if($(this).attr('data-tab') == tab_identifier)
                            $(this).remove();
                    });
                    $(parentIdentifier+' .tabFrame .tab.tab_'+tab_identifier).remove();
                    
		};
		this.moveTab = function(parentIdentifier, tab){
			
		};
};

	
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
function empty(value){
	  	if(value.length == 0) {
	  		return true;
	  	}else{
	  		return false;
	  	}
	  }

function maxLength(string, maxlength){
	  	if(string.length <= maxlength)
	  		return string;
	  	else{
	  		return string.substring(0, maxlength-5)+'(..)';
	  		
	  	}
	  }
	  
//updates 
function updatePictureStatus(userId, borderColor){
    $('.userPicture_'+userId).css('border-color', borderColor);
}

function showContent(content, title){
  reader.applicationVar.show();
  reader.tabs.addTab(title, '',gui.loadPage('showContent.php?content='+content));
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
       

//filesystem

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
        filesystem.show();
        filesystem.openFolder(folderId);
        return false;
        
    }

function openElement(elementId, title){
        filesystem.show();
        
        filesystem.tabs.addTab(title, '',gui.loadPage('modules/filesystem/showElement.php?element='+elementId));
}

function openFile(type, typeId, title, typeInfo, extraInfo1, extraInfo2){
        
        title = 'Open '+title;
        
        //bring reader to front
        reader.applicationVar.show();
        
        
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
                         
                reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&typeInfo='+vId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1'));
 	
        	}else{
        		
                reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1'));
 
        	}return false;
        }
        
        if(type == 'RSS'){
            
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=RSS&linkId='+typeId));
            return false;
        }
        
        if(type == 'wikipedia'){
        	//typeId needs to be changed to title
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=wiki&title='+typeId));
            return false;
        }
        
        //real files
        if(type == 'UFF'){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=UFF&fileId='+typeId));
            return false;
        }
        if(type == 'document' ||type == 'application/pdf' ||type == 'text/plain'){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?fileId='+typeId));
            return false;
        }
        if(type == 'video' ||type == 'video/mp4' ||type == 'video/quicktime'  ){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=video&fileId='+typeId));
            return false;
        }
        if(type == 'audio' ||type == 'audio/wav' ||type == 'audio/mpeg'  ){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=audio&fileId='+typeId));
            return false;
        }
        if(type == 'image/png' ||type == 'image/jpeg' || type == 'image'){
            reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=image&fileId='+typeId));
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
                                                        UFF.write(id, input);
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
                
            reader.tabs.addTab(title, '',gui.loadPage(url));
            reader.applicationVar.show();
            return false;
    }
    
    
 
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

function isYoutubeURL(url){
    return /((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/.test(url);
}

function youtubeURLToVideoId(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    }else{
        return false;
    }
}

function getYoutubeData(videoId){
    var result;
    	$.ajax(
	{
		url: "https://gdata.youtube.com/feeds/api/videos/"+videoId+"?v=2&alt=json",
                async:false,
		success: function(data)
		{
			result = data;
		}
	});
        
        return result.entry;
    
}

function getYoutubeTitle(videoId){
    var data = getYoutubeData(videoId);
    return data.title.$t;
}
function loader(id, link){
                  $("#" + id + "").load("" + link + "");
              }
              
function popper(url) {
              $("#loader").load("" + url +"");
                }
              
function deleteFromPersonals(id){
                  $("#loader").load("doit.php?action=deleteFromPersonals&id=" + id + "");
              }
          
function showProfile(userId){
    User.showProfile(userId);
    return false;
};

//outdated
function showPlaylist(id){
    playlists.showInfo(id);
}
  
function startPlayer(type, typeid){
              $("#dockplayer").load("player/dockplayer.php?reload=1&" + type +"=" + typeid + "");
              }

function closeDockMenu(){
                $("#dockMenu").hide("fast");
              }
  
function clock() {
    
    var dayNames=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]
    var monthNames=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
                var now = new Date();

                    var hours = now.getHours();
                    var minutes = now.getMinutes();
                    var pad = "00";
                    var day =  now.getDay();
                    var month =  now.getMonth();

                    var minutesRoundedOne = "" + minutes;
                    var minutesRoundedTwo = pad.substring(0, pad.length - minutesRoundedOne.length)+''+minutesRoundedOne;
                    var hoursRoundedOne = "" + hours;
                    var hoursRoundedTwo = pad.substring(0, pad.length - hoursRoundedOne.length) + hoursRoundedOne;

                var outStr = dayNames[day]+', '+monthNames[month]+'. '+now.getDate()+'.<span style="margin-left:8px;">'+hoursRoundedTwo+':'+minutesRoundedTwo+'</span>';
                $('#clockDiv').html(outStr);
                setTimeout('clock()',36000);
              }

function getUserPicture(request){
            debug.log('getUserPicture initizialized with request'+request);
			            var post;
			            var userid;
			            if(is_numeric(request)){
                                        debug.log('     numeric request');
			                userid = request;
			                //check if username is stored
			                if(typeof userPictures[userid] !== 'undefined'){
			                    //return stored username
                                            
                                            debug.log('     username for userid '+userid+'is stored:'+userPictures[userid]);
			                    return userPictures[userid];
			                }else{
                                            
			                    post = request;
			                }
			            }else{
                                        
                                        debug.log('     string request - request should be a username');
			                post = request;
			            }
			            
			            //load data from sercer
			            var result = '';
                                    
                                    debug.log('     ajax request initialized');
                                    result = api.query('api.php?action=getUserPicture', {request : post});
			            
			            if(is_numeric(request)){
                                        
                                        debug.log('     numeric request');
			                if(result.length > 0){
			                    
			                }
			                userPictures[userid]=result;
			            }else{
			                var response = new Array();
			                
			                var userPictureObject = JSON.parse(result);
			                $.each(userPictureObject, function(index, value) {
			                        //add value to userPictures var
			                        userPictures[index]=htmlentities(value);
			                        response[index]=htmlentities(value);
			                    });
			                
			                
			            }
			            if(is_numeric(request)){
                                        
                                        debug.log('     return numeric response');
			                return userPictures[userid];
			            }else{
			                return response;
			            }
                                    
                                    
				
			}

function loadMiniFileBrowser($target, folder, element, level, showGrid, select){
        api.query('api/item/loadMiniFileBrowser/', {folder: folder, element: element, level: level, showGrid: showGrid, select: select},function(result){
            $target.html(result)
        });
}

function is_url(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
    if(!pattern.test(str)) {
      return false;
    } else {
      return true;
    }
}

function htmlspecialchars(string, quote_style, charset, double_encode) {
  //       discuss at: http://phpjs.org/functions/htmlspecialchars/
  //      original by: Mirek Slugen
  //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //      bugfixed by: Nathan
  //      bugfixed by: Arno
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
  //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //         input by: Ratheous
  //         input by: Mailfaker (http://www.weedem.fr/)
  //         input by: felix
  // reimplemented by: Brett Zamir (http://brett-zamir.me)
  //             note: charset argument not supported
  //        example 1: htmlspecialchars("<a href='test'>Test</a>", 'ENT_QUOTES');
  //        returns 1: '&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;'
  //        example 2: htmlspecialchars("ab\"c'd", ['ENT_NOQUOTES', 'ENT_QUOTES']);
  //        returns 2: 'ab"c&#039;d'
  //        example 3: htmlspecialchars('my "&entity;" is still here', null, null, false);
  //        returns 3: 'my &quot;&entity;&quot; is still here'

  var optTemp = 0,
    i = 0,
    noquotes = false;
  if (typeof quote_style === 'undefined' || quote_style === null) {
    quote_style = 2;
  }
  string = string.toString();
  if (double_encode !== false) { // Put this first to avoid double-encoding
    string = string.replace(/&/g, '&amp;');
  }
  string = string.replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');

  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'ENT_IGNORE' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/'/g, '&#039;');
  }
  if (!noquotes) {
    string = string.replace(/"/g, '&quot;');
  }

  return string;
}


//taken from: http://stackoverflow.com/questions/1181575/javascript-determine-whether-an-array-contains-a-value - eyelidlessness
var indexOf = function(needle) {
    if(typeof Array.prototype.indexOf === 'function') {
        indexOf = Array.prototype.indexOf;
    } else {
        indexOf = function(needle) {
            var i = -1, index = -1;

            for(i = 0; i < this.length; i++) {
                if(this[i] === needle) {
                    index = i;
                    break;
                }
            }

            return index;
        };
    }

    return indexOf.call(this, needle);
};