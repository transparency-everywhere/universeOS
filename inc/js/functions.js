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
                
                $('*').on('scroll',function(){
                 $('.itemSettingsWindow, .rightClick').hide();
                });
          
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
        
        applications.init();
        
        
        //init draggable windows
        init.GUI();
        
        //init bootstrap popover
        $('.bsPopOver').popover();
        
        //init bootstrap alert
        $(".alert").alert();
        
    };
    this.reload = function(){
        //fetch request data like open filebrowsers & feeds
        var requests = [];
        
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
        
        requests.push({
                    action:'buddylist',
                    subaction:'reload',
                    data: {
                        buddy_checksum: buddylist.checksum
                    }
                });
                
        requests.push({
            action : 'IM',
            subaction:'sync',
            data: {
                last_message_received:im.lastMessageReceived
            }
        });
        
        var feedsArray = [];
        $('.feedFrame').each(function(){
            feedsArray.push({'type':$(this).data('type'), 'last_feed_received':$(this).data('last')});
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
                }else if(responseElement.subaction === 'openRequest'){
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
    this.getAllData = function(userid){
        //data will only be returned if getUser()==userid or userid is on buddylist of getUser()
        
            return api.query('api/user/getAllData/', { user_id:userid });
    }
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
        field0['caption'] = 'Privacy';
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
        };
	
};

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

//not in use because of async
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
       
//can be deleted
function del_reloadFeed(type){
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
    var username = useridToUsername(userId);
    reader.tabs.addTab(username, '',gui.loadPage("./profile.php?user=" + userId));
    reader.applicationVar.show();
    return false;
};
  
function showPlaylist(id){
              	popper('doit.php?action=showPlaylist&id='+id);
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

                var outStr = dayNames[day]+', '+monthNames[month]+'. '+now.getDate()+'.&nbsp;&nbsp;'+hoursRoundedTwo+':'+minutesRoundedTwo;
                $('#clockDiv').html(outStr);
                setTimeout('clock()',36000);
              }

//PLUGINS
//PLUGINS
//PLUGINS

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
				
(function ( $ ) {
 //tags
    $.fn.userSearch = function( options ) {
 
        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            source: null,
            seperator: ",",
            fieldName: "users",
            val: ''
        }, options );
 
 		var output, buddies, username, userPicture;
 		output = '<div class="userSearch">';
 		output += '<ul class="inputTagBar" style="display:none;">';
 		output += '</ul>';
 		output += '<input type="text" placeholder="search users..." class="userTagSearch">';
 		output += '<ul class="inputTagSuggestions">';
 		output += '</ul>';
 		output += '<input type="hidden" value="'+settings.val+'" name="'+settings.fieldName+'">';
 		output += '<div>';
		
		//add output to the chosen element
 		this.html(output);
 		//search suggestions
 		$('.userTagSearch').keyup(function(key){
 			var shownUsers = [];
 			$('.inputTagBar li').each(function(){
 				shownUsers[shownUsers.length+1] = $(this).data('user');
 			});
 			if(shownUsers.length === 0)
 				$('.inputTagBar').hide();
 			else
 				$('.inputTagBar').show();
 			
 			//hide if no input
 			if($('.userTagSearch').val().length === 0)
 				$('.inputTagSuggestions').hide();
 				
 			output = '';
 			
 			//backspace =-> delete last tag
 			if(key.keyCode === 8){
 				$('.inputTagSuggestions').hide();
 			}else{
 				$('.inputTagSuggestions').show();
				$('.inputTagSuggestions').html('');
				
				
				//load suggestions from API
 				var suggestions = searchUserByString($(this).val(), '0,30');
				$.each(suggestions, function( index, value ) {
					  			if(value !== undefined && !in_array(index, shownUsers)){
					  			
					  			username = String(value);
					  			username = username.slice(0,-1);
					  			userPicture = User.showPicture(index);
					  			
					  			
					  			output += '<li onclick="addUserToInputTagBar('+index+')">';
					  			output += userPicture;
					  			output += ' ';
					  			output += username;
					  			output += '</li>';
					  			}
					  			
				});
				if(output.length === 0){
					output = 'no results...';
				}
				$('.inputTagSuggestions').html(output);
				
 				
 			}
 		});
 
        // Greenify the collection based on the settings variable.
        return this;
 
    };
 
}( jQuery ));

function removeUserFromInputTagBar(userid){
	//remove value from input
	var oldValue = $('.userSearch input[type=hidden]').val();
	$('.userSearch input[type=hidden]').val(oldValue.replace(userid+',',''));
	if($('.userSearch input[type=hidden]').val().length === 0){
		$('.inputTagBar').hide();
	}
	//remove tag
	$('.inputTagBar .userTag_'+userid).remove();
}

function addUserToInputTagBar(userid){
	//dumb way to find out which users are already loaded
	var shownUsers = [];
 	$('.inputTagBar li').each(function(){
 		shownUsers[shownUsers.length+1] = $(this).data('user');
 	});
	if(!in_array(userid, shownUsers)){
		//add value to input
		$('.userSearch input[type=hidden]').val($('.userSearch input[type=hidden]').val()+userid+',');
		$('.inputTagBar').show();
		username = useridToUsername(userid);
		//append tag to tagBar
		$('.inputTagBar').append('<li class="userTag_'+userid+'" data-user="'+userid+'">'+username+'<a onclick="removeUserFromInputTagBar(\''+userid+'\');">x</a></li>');
	}
			 
}


if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}