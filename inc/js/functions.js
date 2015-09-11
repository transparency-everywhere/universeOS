var sourceURL = 'http://'+universeConfig.host+'/'+universeConfig.dir+'/';


var usernames = [];
var userPictures = [];

var privateKeys = [];
var messageKeys = [];

var openDialogueInterval;

var focus = true;

function loadScripts(scriptObject, callback){
    $.ajax({
        type: 'POST',
        url: 'https://scriptglue.herokuapp.com/glue/',
        crossDomain: true,
        data: {data:scriptObject},
        success: function(responseData, textStatus, jqXHR) {
            var se = document.createElement('script');
            se.type = "text/javascript";
            se.text = responseData;
            document.getElementsByTagName('head')[0].appendChild(se);
            callback(responseData);
        },
        error: function (responseData, textStatus, errorThrown) {
            console.log(responseData);
            console.log(textStatus);
            console.log(errorThrown);
            alert('POST failed.');
        }
    });
};
var gui = {};
gui.loadScript = function(url){
    
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
	this.dashBox = function(){
            //init dashcloses 
			$('.dashBox .dashClose').click(function(){
				$(this).parent('.dashBox').slideUp();
			});	
	};

	this.search = function(){
		//init search
			$("#searchField").keyup(function()
			{
				delay(function(){
					
                                        var $loadingArea = $('#searchMenu #loadingArea');
					var searchValue = $("#searchField").val();
					if (searchValue.length > 1)
					{
                                                search.loadResults($loadingArea, searchValue);;
					}
					else
					{
                                            $loadingArea.empty();
					}
					
					search.initResultHandlers(searchValue);
				}, 500 );
			});
                        
                        
                        $('#searchTrigger').bind('click', function(){
                            search.toggleSearchMenu();
                        });
                        
                        $('#searchMenu #toggleSearchMenu').bind('click',function(){
                            search.toggleSearchMenu();
                        });
                        
                        
                $('#searchMenu header ul li').not('.trigger').click(function(){
                    $(this).toggleClass('active');
                });
                        
                $('#searchMenu header ul .trigger').click(function(){
                    $(this).parent().toggleClass('active');
                    $(this).parent().children('li').not('.trigger').toggle();
                });
        };
        
        this.clock = function(){
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
                setTimeout('init.clock()',36000);
        };
	
	//this function is called to initialzie GUI
	//all needed functions are collected here
	this.GUI = function(){
		this.draggableApplications();
		this.resizableApplications();
		this.applicationOnTop();
		
		this.dashBox();
		
		//this.toolTipper();
		this.search();
		
		dashBoard.init();
		//fade in applications
                
                $('*').on('scroll',function(){
                 $('.itemSettingsWindow').hide();
                });
                
                $('body').on('click',function(){
                 $('.itemSettingsWindow').hide();
                });
                
                item.initSettingsToggle();
          
	};
	
};
var session = new function(){
    this.getGUID = function(){
        //https://andywalpole.me/#!/blog/140739/using-javascript-create-guid-from-users-browser-information
        var nav = window.navigator;
        var screen = window.screen;
        var guid = nav.mimeTypes.length;
        guid += nav.userAgent.replace(/\D+/g, '');
        guid += nav.plugins.length;
        guid += screen.height || '';
        guid += screen.width || '';
        guid += screen.pixelDepth || '';
        return guid;
    };
    
    /**
    * @function _guid
    * @description Creates GUID for user based on several different browser variables
    * It will never be RFC4122 compliant but it is robust
    * @returns {Number}
    * @private
    */
    this.getFingerprint = function(){
        
        var guid = this.getGUID();
        
        
        //hash the guid and return it
	var salt = getSalt('auth', User.userid, localStorage.currentUser_shaPass);
        var fingerprint =  hash.SHA512(guid+salt);
        
        //c.heck if fingerprint matches the one in a set cookie -> if not, update the fingerprint..
        if(this.getSessionCookie().length&&this.getSessionCookie() != fingerprint){
            this.updateFingerprintOnServer(this.getSessionCookie(), fingerprint,function(){
                session.saveSessionCookie(fingerprint);
            });
        }

        //setCookie('session_guid', guid, timeInDays);
        
        return fingerprint;
    };
    this.updateFingerprintOnServer = function(old_fingerprint, new_fingerprint, callBack){
        api.query('api/sessions/updateFingerprint/', {old_fingerprint:old_fingerprint, new_fingerprint:new_fingerprint}, callBack);
    };
    
    this.create = function(type, title, callBack){
        api.query('api/sessions/create/', {type:type, title:title, fingerprint: session.getFingerprint()},function(cookie_id){
            if(typeof callBack === 'function'){
                callBack(cookie_id);
            }
        });
    };
    
    this.saveSessionCookie = function(guid, timeInDays){
            setCookie('session_guid', guid, timeInDays);
    };
    this.getSessionCookie = function(){
       return getCookie('session_guid');
    };
    this.updateSessionInfo = function(key, value, callback){
        api.query('api/sessions/updateSessionInfo/', {key:key, value:value, fingerprint: session.getFingerprint()},function(){
            if(typeof callback === 'function'){
                callback();
            }
        });
    };
    this.getSessionInfo = function(callback){
        return api.query('api/sessions/getSessionInfo/', {fingerprint:session.getFingerprint()}, callback);
    };
    
    this.load = function(sessionInfo){
        
            im.lastMessageReceived = sessionInfo.lastMessageReceived;
    };
    
    this.showAddSessionForm = function(){
        
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = '';
        field0['inputName'] = 'html';
        field0['type'] = 'html';
        field0['value'] = 'Should the session be saved permanent or should it be permanent?<br/><br/>';
        field0['advanced'] = false;
        fieldArray[0] = field0;
        
        var captions = ['permanent', 'temporarily'];
        var type_ids = ['permanent', 'temporarily'];
        
        var field1 = [];
        field1['caption'] = 'Type: ';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Title: ';
        field2['inputName'] = 'title';
        field2['type'] = 'text';
        field2['value'] = navigator.sayswho;

        fieldArray[2] = field2;
        
        
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Add Session';
        
        modalOptions['action'] = function(){
            var callback = function(){
                
                //save session cookie, in case the browser updates or an plugin is installed
                session.saveSessionCookie(session.getGUID(), 31);
                gui.alert('The session has been added');
                $('.blueModal').remove();
                universe.reloadState = true;
            };
            session.create($('#addSessionFormContainer #type').val(), $('#addSessionFormContainer #title').val(), callback);
        };
        options['action'] = modalOptions['action'];
        formModal.init('Unrecognized Browser', '<div id="addSessionFormContainer"></div>', modalOptions);
        gui.createForm('#addSessionFormContainer',fieldArray, options);
        
        $('#addSessionFormContainer #type').change(function(){
            if($(this).val() === 'temporarily')
                $('#addSessionFormContainer #title').hide();
            
            else if($(this).val() === 'permanent')
                $('#addSessionFormContainer #title').show();
        })
    };
};

var universe = new function(){
    this.notificationArray = [];
    this.reloadState = true;
    this.init = function(){
        
        gui.loadScript('inc/js/privacy.js');
        
        gui.loadScript('inc/js/gui.js');
        
        gui.loadScript('inc/js/modal.js');
        
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
        
        gui.loadScript('inc/js/shortcuts.js');
        
        gui.loadScript('inc/js/clientDB.js');
        applications.init();
        
        //init draggable windows
        init.GUI();
        
        //init bootstrap popover
        $('.bsPopOver').popover();
        
        //init bootstrap alert
        $(".alert").alert();
        
        //init counter handler
        notifications.updateCounters();
        
        //init reload and load sessionInformation
        if(proofLogin()){
            
            universe.sessionInfo = session.getSessionInfo();
            
            if(universe.sessionInfo.length === 0){
                universe.reloadState = false;
                session.showAddSessionForm();
                
                //automaticly submit the form container because it is so anoying..
                $('#addSessionFormContainer .dynForm').submit();
            }
            session.load(universe.sessionInfo);
            
            
            setInterval(function()
            {
                if(universe.reloadState)
                    universe.reload();
            }, 5000);
        }


        //loads clock into the dock, yeah.
        init.clock();
        
//        
//        var scripts = [];
//        scripts.push('inc/js/privacy.js');
//        scripts.push('inc/js/gui.js');
//        scripts.push('inc/js/modal.js');
//        scripts.push('inc/js/item.js');
//        scripts.push('inc/js/links.js');
//        scripts.push('inc/js/api.js');
//        scripts.push('inc/js/folders.js');
//        scripts.push('inc/js/elements.js');
//        scripts.push('inc/js/fav.js');
//        scripts.push('inc/js/playlists.js');
//        scripts.push('inc/js/notification.js');
//        scripts.push('inc/js/tasks.js');
//        scripts.push('inc/js/dashBoard.js');
//        scripts.push('inc/js/browser.js');
//        scripts.push('inc/js/UFF.js');
//        scripts.push('inc/js/comments.js');
//        scripts.push('inc/js/media.js');
//        scripts.push('inc/js/application.js');
//        scripts.push('inc/js/debug.js');
//        scripts.push('inc/js/im.js');
//        scripts.push('inc/js/groups.js');
//        scripts.push('inc/js/shortcuts.js');
//        var scriptsToLoad = [
//            {
//                'host':'universeos.org',
//                'dir':'',
//                'scripts': scripts
//            }
//        ];
//        //fctn start
//        loadScripts(scriptsToLoad, function(){
//            
//            applications.init();
//
//            //init draggable windows
//            init.GUI();
//
//            //init bootstrap popover
//            $('.bsPopOver').popover();
//
//            //init bootstrap alert
//            $(".alert").alert();
//
//
//
//            //init reload and load sessionInformation
//            if(proofLogin()){
//                universe.sessionInfo = session.getSessionInfo();
//
//                if(universe.sessionInfo.length === 0){
//                    universe.reloadState = false;
//                    session.showAddSessionForm();
//                }
//                session.load(universe.sessionInfo);
//
//
//
//                setInterval(function()
//                {
//                    if(universe.reloadState)
//                        universe.reload();
//                }, 5000);
//            }
//
//
//            //loads clock into the dock, yeah.
//            clock();
//        });
        
                
        
    };
    this.reload = function(){
        //fetch request data like open filebrowsers & feeds
        var requests = [];
        
        requests[0] = {'fingerprint':session.getFingerprint()};
        
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
        
        
        var response = api.query('api/reload/', requestData, function(response){
            if(response !== 'null'){
                response = JSON.parse(response);
                $.each(response, function(key, value){
                    universe.handleReloadTypes(value);
                });
                notifications.updateCounters();
            }
        });
        
        
    };
    this.generateMessage = function(userid, message){
        
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
                                                    message: User.showPicture(responseElement.data.userid)+useridToUsername(responseElement.data.userid)+'&nbsp; want\'s to be your friend',
                                                    acceptButton:{
                                                        action: 'buddylist.acceptBuddyRequest('+responseElement.data.userid+')',
                                                        value: 'accept'
                                                    },
                                                    cancelButton:{
                                                        action: 'buddylist.denyBuddyRequest('+responseElement.data.userid+')',
                                                        value: 'decline'
                                                    },
                                                    type: 'buddylist'
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
                                                    },
                                                    type: 'global'
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
                                                    },
                                                    type:'notification'
                                                });
                    this.notificationArray[notificationId].push();
                    
                    
                };
                break;
            case 'feed':
                if(responseElement.subaction === 'sync'){
                    feed.reload(responseElement.data.type, responseElement.data.typeId);
                };
                break;
                
            case 'sessions':
                if(responseElement.subaction === 'not_found'){
                    universe.reloadState = false;
                    session.showAddSessionForm();
                }
                break;
        };
    };
};

function time(){
    return Math.floor(Date.now() / 1000);
};

var User = new function(){
    this.userid;
    this.lastLoginCheck = 0;
    this.loggedIn = false;
    this.updateUserpicture = function(){
        return true;
    };
    this.proofLogin = function(){
        if(time()-this.lastLoginCheck > 120){
            var result = api.query('api.php?action=proofLogin', {});
            this.lastLoginCheck = time();
            if(result == '1'){
                  this.loggedIn = true;
                  return true;
            }else{
                  this.loggedIn = false;
                  return false;
            }
        }else{
            return this.loggedIn;
        }
    };
    this.updateGUI = function(userid){
            $('.userProfile').each(function(){
                if($(this).attr('data-userid') == userid){
                    $(this).replaceWith(User.generateProfile(userid));
                }
            });
    };
    this.setUserId = function(id){
        
        this.userid = id;
    };
    this.getBorder = function(lastActivity){
    //every userpicture has a border, this border is green if the lastactivty defines that
    //the user is online and its red if the lastactivity defines that the user is offline.


        var border;
        if(lastActivity === 1){
                border = 'border: 1px solid green';
            }else{
                border = 'border: none;';
            }

        return border;
    };
    this.showPicture = function(userid, lastActivity, size){
	 
         
        if(typeof size === 'undefined'){
            debug.log('     set size to standard size');
            var size = 20;
        }

        var radius = size/2;
         
        if(typeof userid === 'object'){
            var userpictures = getUserPictureBASE64(userid);
            
            var lastActivities = this.getLastActivity(userid);
            var returns = [];
            $.each(userid, function(index, value){
                var html = '<div class="userPicture userPicture_'+value+'" style="background: url(\''+userpictures[index]+'\'); '+User.getBorder(lastActivities[index])+'; width: '+size+'px;height:  '+size+'px;background-size: 100%;border-radius:'+radius+'px"></div>';
                returns.push(html);
            });
            return returns;
        }else{
            
            var userpicture = getUserPicture(userid);
            if(typeof lastActivity === 'undefined'){
                var lastActivity = User.getLastActivity(userid); //get last activity so the border of the userpicture can show if the user is online or offline
            }
            var ret;
            ret = '<div class="userPicture userPicture_'+userid+'" style="background: url(\''+userpicture+'\'); '+User.getBorder(lastActivity)+'; width: '+size+'px;height:  '+size+'px;background-size: 100%;border-radius:'+radius+'px"></div>';
            // $('.userPicture_'+userid).css('border', User.getBorder(lastActivity)); //update all shown pictures of the user
            return ret;
        }
    };
    this.getLastActivity = function(userid){
        //if type or itemId is array, handle as request for multiple items
        if(typeof userid === 'object'){
            var requests = [];
            $.each(userid,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({userid : value});
            });
            
            return api.query('api/user/getLastActivity/', { request: requests});
        }
        return api.query('api/user/getLastActivity/', { request: [{userid : userid}]})[0];
    
    };
    this.getProfileInfo = function(userid){
        if(typeof userid === 'undefined')
            userid = User.userid;
        //if type or itemId is array, handle as request for multiple items
        if(typeof userid === 'object'){
            if(userid.length === 0)
                return [];
                var requests = [];
                $.each(userid,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({user_id : value});
            });
            
                return clientDB.savePipe('users', api.query('api/user/getProfileInfo/', { request: requests}));
            
        }else{
            //userid is string :/
            var result = clientDB.select('users',{'userid':userid+''});
            if(result)
                return result
            
            return clientDB.savePipe('users', api.query('api/user/getProfileInfo/', { request: [{user_id : userid}]})[0]);
        };
    }
        
   
    this.getCypher = function(id){
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
    this.updatePassword = function(user_id, old_password, new_password, callback){
        //generate old password hash for authentification
	var userCypher = User.getCypher(user_id);
	var shaPass = hash.SHA512(old_password);
	var oldPasswordHash = cypher.getKey('auth', user_id, shaPass);
        console.log(oldPasswordHash);
        
        
        //generate hash and encrypted salt
        var keys = cypher.createKeysForUser(new_password);
        
        var newPasswordHash = keys['authHash'];
        var newAuthSaltEncrypted = keys['authSaltEncrypted'];
        
        api.query('api/user/updatePassword/', {oldPasswordHash:oldPasswordHash,newPasswordHash:newPasswordHash, newAuthSalt:newAuthSaltEncrypted}, function(result){
            
            if(typeof callback === 'function'){
                callback(result);
            }
        });
    };
        
    this.getAllData = function(userid){
        //data will only be returned if getUser()==userid or userid is on buddylist of getUser()
        return api.query('api/user/getAllData/', { user_id:userid });
    };
    this.getGroups = function(){
        return api.query('api/user/getGroups/', { });
    };
    this.getHistoryArray = function(){
        var history = userHistory.get();
        var historyArray = [];
        if(history === undefined){
            return historyArray;
        } else {
            $.each(history, function(key, value){
                var type = value['type'];
                var itemId = value['item_id'];
                var title = value['title'];
                if(typeof title === 'undefined' || title === null || title === ''){
                    try {
                        title = handlers[type+'s'].getTitle(itemId);//plus "s" because handlers are in plural (element > elements)
                    }
                    catch(err) {
                        console.log('handler get title error:'+err);
                    }
                }
                historyArray.push({type: type, itemId: itemId, title: title, timestamp: ''});
            });
            return historyArray;
        }
    };
    this.inGroup = function(group_id){
        return jQuery.inArray(group_id+'', User.getGroups());
    };
    this.generateSingleSignature = function(userid, timestamp, username, userpicture, reverse){
        
            var output="";
            output += "<div class=\"signature\">";
            output += "    <table width=\"100%\">";
            output += "        <tr width=\"100%\">";
            if(reverse){
                output += "            <td style=\"width:50px; padding-right:10px;\">"+userpicture+"<\/td>";
                output += "            <td>";
                output += "                <table>";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 16px;line-height: 17px;\" align=\"left\"><a href=\"#\" onclick=\"User.showProfile("+userid+");\">"+username+"<\/a><\/td>";
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
                output += "                        <td style=\"\">&nbsp;<a href=\"#\" onclick=\"User.showProfile('"+userid+"')\">"+username+"<\/td>";
                output += "                    <\/tr>             ";
                output += "                    <tr>";
                output += "                        <td style=\"font-size: 12px;\">&nbsp;<i>";
                output += universeTime(timestamp)+"<\/i>";
                output += "                        <\/td>";
                output += "                    <\/tr>";
                output += "                <\/table>";
                output += "            <\/td>";
                output += "            <td><span class=\"pictureInSignature\">"+userpicture+"<\/span><\/td>";
            }
            output += "        <\/tr>";
            output += "    <\/table>";
            output += "    <\/div>";
            return output;
    };
    this.showSignature = function(userid, timestamp, reverse){
        
        if(typeof userid === 'object'||typeof timestamp === 'object'){
            var userpictures = User.showPicture(userid, undefined, 40);
            var usernames = useridToUsername(userid);
            console.log(userid);
            console.log(usernames);
            var results = [];
            $.each(userid, function(index, value){
                
                results.push(User.generateSingleSignature(value, timestamp[index], usernames[index], userpictures[index], reverse));
                
            });
            return results;
        }else{
            return this.generateSingleSignature(userid, timestamp, useridToUsername(userid), User.showPicture(userid, undefined, 40), reverse);
        }
        
    };
    
    
    
    /**
     * Generates HTML for Profile
     * @param {number} userid of user.
     * @returns {string} HTML
    */
    this.generateProfile = function(user_id){
        var profile_userdata = this.getProfileInfo(user_id);
        var realname = '', city = '', birthdate = '';
        if(typeof profile_userdata['realname'] !== 'undefined'){
            realname = '<span class="realName">'+profile_userdata['realname']+'</span>';
        }
        if(typeof profile_userdata.home !== 'undefined' && profile_userdata.home.length > 0){
            city += '<span class="city">from '+profile_userdata.home+'</span>';
        }
        if(typeof profile_userdata.place !== 'undefined' && profile_userdata.place.length > 0){
            city += '<span class="place">lives in '+profile_userdata.place+'</span>';
        }
        
        var buttons = '';
        if(!buddylist.isBuddy(user_id) || user_id != User.userid){
            buttons = '<a class="button" onclick="buddylist.addBuddy('+user_id+'); $(this).text(\'request sent\');">Add Friend</a>';
        }
        if(user_id ===User.userid)
            buttons += '<a class="button" onclick="settings.showUpdateProfileForm()">Edit Your Profile</a>';
        
        
        var output   = '<div class="profile userProfile" data-userid="'+user_id+'">';
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
                        
                        output += '<div class="buttons">';
                        output += buttons;
                        output += '</div>';

                output  += '</header>';
                //output  += '<div class="">';
                    output += '<div class="profileNavLeft leftNav dark">';
                        output += '<ul>';
                            output += '<li data-type="favorites"><span class="icon blue-heart"></span><span class="icon white-heart white"></span>Favorites</li>';
                            output += '<li data-type="files"><span class="icon blue-file"></span><span class="icon white-file white"></span>Files</li>';
                            output += '<li data-type="playlists"><span class="icon blue-playlist"></span><span class="icon white-playlist white"></span>Playlists</li>';
                            output += '<li class="openChat" data-type="openChat"><img src="gfx/chat_icon.svg"/>Open Chat</li>';
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
                                
//                                output += '<ul>';
//                                var profile_playlists = playlists.getUserPlaylists('show',user_id);
//                                if(profile_playlists['ids'])
//                                    $.each(profile_playlists['ids'], function(index, value){
//                                        output += '<li onclick="playlists.showInfo(\''+value+'\');"><div><span class="icon icon-playlist"></span>';
//                                        output += '<span  style="font-size:18px; padding-top: 5px;" onclick="">'+profile_playlists['titles'][index]+'</span>';
//                                        //output += item.showItemSettings('user', value);
//                                        output += '</div></li>';
//                                    });
//                                output += '</ul>';
                            output += '</div>';
                            
                            
                            output += '<div class="profile_tab activity_tab" style="display:block"></div>';
                            
                            
                            
                            //buddies
                            output += '<div class="profile_tab friends_tab">';
//                                output += '<ul>';
//                                var profile_buddylist = buddylist.getBuddies(user_id);
//                                $.each(profile_buddylist, function(index, value){
//                                    output += '<li><div>'+User.showPicture(value);
//                                    output += '<span class="username" style="padding-top: 5px; font-size:18px;">'+useridToUsername(value)+'</span>';
//                                    output += '<span class="realname">'+useridToUsername(value)+'</span>';
//                                    output += item.showItemSettings('user', value);
//                                    output += '</div></li>';
//                                });
//                                output += '</ul>';
                            output += '</div>';
                            
                            //userinfo
                            output += '<div class="profile_tab info_tab" style="padding:15px;">';
                            var i = 0;
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
                                        case 'homefolder':
                                            key = 0;
                                            break;
                                    }
                                    if(!is_numeric(key)&&!empty(value)){
                                        i++;
                                        output += '<span class="'+key+'"><label>'+key+'</label>'+value+'</span>';
                                    }
                                });
                                if(i === 0){
                                    output += 'the user didn\'t published any user information';
                                }
                                
                            
                            output += '</div>';
                            
                            //groups
                            output += '<div class="profile_tab groups_tab">';
                            
//                                output += '<ul>';
//                                var profile_groups = groups.get(user_id);
//                                if(typeof profile_groups !== 'undefined'){
//                                    $.each(profile_groups, function(index, value){
//                                        output += '<li onclick="groups.showProfile('+value+')"><div><span class="icon icon-group"></span>';
//                                        output += '<span class="username" style="font-size: 20px; padding-top: 10px;">'+groups.getTitle(value)+'</span>';
//                                        output += '</div></li>';
//                                    });
//                                }
//                                output += '</ul>';
                            output += '</div>';
                        
                        output += '</div>';
                    //output += '</div>';
                output  += '</div>';
            output  += '</div>';
            return output;
    };
    /**
     * Initializes handlers for profile
     * @param {number} userid of user.
    */
    this.initProfileHandlers = function(user_id){
        
    };
    /**
     * Shows profile
     * @param {number} userid of user.
    */
    this.showProfile = function(user_id){
        applications.show('reader');
        var profileTab =reader.tabs.addTab(gui.shorten(useridToUsername(user_id), 8), 'html', gui.generateLoadingArea());
        var output = this.generateProfile(user_id);
        reader.tabs.updateTabContent(profileTab, output);
        
        //load feed
        var profileFeed = new Feed('user', '.activity_tab', user_id);
        $('.profileMainNav li, .profileNavLeft li').unbind('click');
        $('.profileMainNav li, .profileNavLeft li').bind('click', function(){
            var type = $(this).attr('data-type');
            //if openChat openDialogue and return
            if(type === 'openChat'){
                if(buddylist.isBuddy(user_id))
                   chat.openDialogue(user_id);
                else
                    gui.alert('You need to add '+useridToUsername(user_id)+' to your buddylist to start a chat');
                return null;
            }
           
              
            $(this).parent().parent().parent().find('.profileMainNav li').removeClass('active');
            $(this).parent().parent().parent().find('.profileNavLeft li').removeClass('active');
            $(this).addClass('active');
            $(this).parent().parent().parent().find('.content .profile_tab').hide();
            $(this).parent().parent().parent().find('.content .'+type+'_tab').show();
        });
        userHistory.push('user', user_id, useridToUsername(user_id));
        
    };
    
    this.searchByString = function(string, limit){
        var result = api.query('api.php?action=searchUserByString', { string : string, limit : limit });

        if(result.length === 0 && result == null){
            result = false;           
        }
        return result;
    }
    
    this.logout = function(){
        gui.alert('Goodbye :)', '');
        api.query('api/user/logout/index.php', {},function(data){
             
            location.reload();
        });
    };
    this.getRealName = function(userid){
        var profileInfo = this.getProfileInfo(userid);
        if(is_numeric(userid)){
            if(empty(profileInfo['realname']))
                return 'Juergen Vogel';
            else
                return profileInfo['realname'];
	}else{
		 var response = new Array();
		                
		$.each(profileInfo, function(index, value) {
                    if(empty(value['realname']))
                        response[index]='Juergen Vogel';
                    else
                        response[index]=value['realname'];
                });
                return response;  
	}
    };
    this.getPrivacy = function(){
        return api.query('api/user/getPrivacy/', {});
    };
};

function showProfile(userid){
    User.showProfile(userid);
};

function useridToUsername(id){
        var profileInfo = User.getProfileInfo(id);
        if(is_numeric(id)){
                return profileInfo['username'];
	}else{
		var response = new Array();
                if(profileInfo.length > 0){
                    $.each(profileInfo, function(index, value) {
                            response[index]=value['username'];
                    }); 
                }
                return response;  
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
    
    
    var smileys = [];
    smileys.push([['(yes) ', '(y) '], 'yes']);
    smileys.push([[';) '], 'wink']);
    smileys.push([['O.o ', 'O_o ', 'O_ó '], 'weird']);
    smileys.push([[':P ', ':p '], 'tongue']);
    smileys.push([[':O ', ':o ', 'O_O'], 'surprised']);
    smileys.push([['(s) ', '(sun) '], 'sun']);
    smileys.push([['* '], 'star']);
    smileys.push([[':| '], 'speechless']);
    smileys.push([[':) '], 'smile']);
    smileys.push([['(z) '], 'sleep']);
    smileys.push([[':/ '], 'skeptic']);
    smileys.push([[':( '], 'sad']);
    smileys.push([['(p) ','(puke)'], 'puke']);
    smileys.push([['(n) '], 'no']);
    smileys.push([[':x '], 'mute']);
    smileys.push([['>:( '], 'angry']);
    smileys.push([['-.- ','-_- '], 'annoyed']);
    smileys.push([['(a) ','(anon) '], 'anon']);
    smileys.push([['8) '],'cool']);
    smileys.push([[":' ("],'cry']);
    smileys.push([[':3 '],'cute']);
    smileys.push([['3:) '],'devil']);
    smileys.push([['(d) ','(dino) '],'dino']);
    smileys.push([[']:) '],'evil']); 
    smileys.push([['>:o ', '>:O '],'furious']);
    smileys.push([['^_^ '],'happy']);
    smileys.push([['<3 '],'heart']);
    smileys.push([[':* ',':-* '],'kiss']);
    smileys.push([[':D '],'laugh']);
    smileys.push([['(m) ','(music) '],'music']);
    for(var index in smileys){
        var smiley = smileys[index];
        if(smiley[0].length > 1){
            for(var codeIndex in smiley[0]){
                string = string.replace(smiley[0][codeIndex], '<span class="smiley emoticon-'+smileys[index][1]+'"></span>');
            }
        }else{
            string = string.replace(smileys[index][0][0], '<span class="smiley emoticon-'+smileys[index][1]+'"></span>');
        }
    }

    string = string.replace("#(^|[^\"=]{1})(http://|ftp://|mailto:|https://)([^\s<>]+)([\s\n<>]|$)#sm","\\1<a target=\"_blank\" href=\"\\2\\3\">\\3</a>\\4");
       // # Links
        //$str = preg_replace_callback("#\[itemThumb type=(.*)\ typeId=(.*)\]#", 'showChatThumb' , $str);
    return string;
};

function proofLogin(){
    return User.proofLogin();
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
            
                
                var dbResult = clientDB.select('salts', {'type':type, 'itemId':itemId});
                if(!dbResult){
                    var encryptedSalt = api.query('api.php?action=getSalt', { type : type, itemId : itemId });
                
                    clientDB.insert('salts',{'type':type, 'itemId':itemId, 'encryptedSalt':encryptedSalt});
           
                }else
                    encryptedSalt = dbResult.encryptedSalt;
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
        
var search = new function(){
    this.getFilters = function(){
        var result = {'source':[], 'type':[]};
        $('#searchMenu header #selectSource li.active').not('.trigger').each(function(){
            result.source.push($(this).attr('data-value'));
        });
        $('#searchMenu header #selectType li.active').not('.trigger').each(function(){
            result.type.push($(this).attr('data-value'));
        });
        return result;
    };
    this.toggleSearchMenu = function(){
        var $searchMenu = $('#searchMenu');
        var ms = 700;
        
        $('#bodywrap').unbind('click');
        if($searchMenu.hasClass('open')){
            
            this.hideSearchMenu();
            
        }else{
          
            $('#bodywrap').animate({
                    marginLeft: -337
            }, ms);
            $searchMenu.animate({
                    marginRight: 0
            }, ms);  
            $searchMenu.addClass('open');
            
            $('#bodywrap').bind('click', function(){
                search.toggleSearchMenu();
            });
        }
    };
    this.hideSearchMenu = function(){
        $('#bodywrap').unbind('click');
        var $searchMenu = $('#searchMenu');
        var ms = 700;
            $('#bodywrap').animate({
                    marginLeft: 0
            }, ms);
            $searchMenu.animate({
                    marginRight: -337
            }, ms);  
            $searchMenu.removeClass('open');
    };
    this.initResultHandlers = function(query){
                item.initRightClick();
                $('.resultList a:link, .resultList .icon-gear, .resultList .white-gear').unbind('click');
		$('.resultList a:link').bind('click', function(){
			search.toggleSearchMenu();
                        $('#searchField').val('');
		});
                
                $('.resultList .white-gear, .resultList .icon-gear').bind('click', function(){
                        $(this).parent().next('li').slideToggle();
                });
                
                var openInTelescope = function(){
                    telescope.query(query);
                    //search.toggleSearchMenu();
                };
                $('#searchMenu .loadAll a').unbind('click');
                $('#searchMenu .loadAll a').bind('click', function(){
                    openInTelescope();
                });
                
                $('#searchMenu #openInTelescope').unbind('click');
                $('#searchMenu #openInTelescope').bind('click', function(){
                    openInTelescope();
                });
    };
    this.extendResults = function(query, type, limit, offset){
        
        return api.query('api/search/extendResults/', {query:query, type:type, limit:limit, offset:offset});
    };
    //basicQuery is used inside the docksearch
    //query is used inside telescope
    this.basicQuery = function(query){
        api.query('api/search/query/',{query:query});
    };
    this.loadResults = function($object, query){
        var html = '';
        $object.html('...loading');
        api.query('api/search/loadDockList/',{query:query},function(results){
            var results = JSON.parse(results);
            
            html += results.users;
        
            html += results.folders;

            html += results.elements;

            html += results.files;

            if(typeof results.groups !== 'undefined')
                html += results.groups;

            html += results.wikis;

            html += results.youtubes;

            html += results.spotifies;

            $object.html(html);
            
            search.initResultHandlers(query);
        });
        
        
        
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
    };
                
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
                            
    this.updatePassword = function(oldPassword, newPassword, userid){
    	
		//cypher old password
		var shaPass_old = hash.SHA512('abcabc');
		var passwordHash_old = cypher.getKey('auth', userid, shaPass_old);
                

		var privateKey = cypher.getPrivateKey('user', userid);
		var keysNew = cypher.createKeysForUser(newPassword);
                privateKey = sec.symEncrypt(keysNew['keyHash'], privateKey); //encrypt privatestring, using the password hash
	    
                $.post("api.php?action=updatePassword", {
                    oldPassword:passwordHash_old,
                    password:keysNew['authHash'],
                    authSalt:keysNew['authSaltEncrypted'],
                    keySalt:keysNew['keySaltEncrypted'],
                    privateKey:privateKey
	    	}, function(result){
		    	if(result == 1){
		    		
	    			parent.localStorage.currentUser_passwordHashMD5 = passwordHashMD5New;
		    		gui.alert('Your password has been changed', 'Security');
		    	}else{
		    		gui.alert(result, 'Security');
		    	}
	    	}, "html");
	    
	    
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
    
    

var support = new function(){
    this.alert = function($attachedTo, message,callback, arrowPosition, $actionTarget){
        var footer = '<footer><a class="button pull-right next">Next</a></footer>';
        if(typeof $actionTarget === 'object')
            footer = '<footer></footer>';
        
        var $box = $('<div class="alert support"><div>'+message+'</div>'+footer+'</div>');
        
        // .position() uses position relative to the offset parent, 
        var pos = $attachedTo.offset();
        // .outerWidth() takes into account border and padding.
        var width = $attachedTo.outerWidth();
        
        if((pos.left)+width > $(window).width()){
            var temp = (pos.left)-width;
            pos.left = temp;
        }
        var boxHeight = 140, boxWidth = 280,iconClass = '';
        
        if(typeof arrowPosition === 'undefined')
            arrowPosition = 'bottom';
        var arrowClass = 'arrow-'+arrowPosition;
        
        switch(arrowClass){
            case 'arrow-bottom-left':
                pos.top = pos.top-boxHeight;
                iconClass = 'down';
                break;
            case 'arrow-bottom-right':
                pos.top = pos.top-boxHeight;
                pos.left = pos.left-boxWidth;
                iconClass = 'down right';
                break;
            case 'arrow-top':
                
                
                pos.top = pos.top+boxHeight;
                iconClass = 'up';
                break;
            case 'arrow-left':
                pos.left = pos.left+boxWidth;
                iconClass = 'left';
                break;
            case 'arrow-right':
                pos.left = pos.left-boxWidth;
                iconClass = 'right';
                break;
        }
        $box.children('footer').append('<span class="icon blue-chevron-'+iconClass+'"></span>');
        
        //show the menu directly over the placeholder
        $box.css({
            top: pos.top + "px",
            left: (pos.left) + "px"
        }).addClass(arrowClass).show();
        
        $('#loader').append($box);
        
        if(typeof $actionTarget === 'undefined'){
            $actionTarget = $('.alert.support .next');
        }
        $actionTarget.click(function(){
            $('.alert.support').remove();
            callback();
	});
        
    };
    this.loadArticle = function(title, callback){
        var url = 'http://wiki.transparency-everywhere.com/en/api.php?action=parse&page='+encodeURIComponent(title)+'&format=json';
        console.log(url);
        var ret = api.loadSource(url, callback);
        if(!callback)
            return ret;
    };
    this.loadSection = function(article_title, section_title, callback){
        section_title = section_title.replace(' ', '_');
        article_title = article_title.replace(' ', '_');
        this.loadArticle(article_title, function(data){
            
            var html = JSON.parse(data);
            html = html.parse.text['*'];
            var returnString;
            //console.log(html.parse.text['*']);
            var i = 0;
            console.log($(html+'').children('#'+section_title).parent().nextUntil('h2').text());
        });
    };
    this.showTour = function(){
        var callbacks = {};
      //dock
      
      
        callbacks['openSearchResult'] = function(){
            
        };
      
        callbacks['searchSomething'] = function(){
                                    support.alert($('#searchField'), 'Enter a keyword, lets say for a youtube video',function(){}, 'right', $('#searchField'));
        };
      
        callbacks['searchTrigger'] = function(){
                                    support.alert($('#searchTrigger'), 'Click on this icon to open the search bar',callbacks['searchSomething'], 'bottom-right', $('#searchTrigger'));  
        };
        
        callbacks['openFilesystem'] = function(){
            applications.hide('feed');
            applications.show('filesystem');
            support.alert($('#filesystem'), 'This is the filesystem. Open Folders, Archives and files.',callbacks['searchTrigger'], 'left');
            $('.alert.support').css('marginLeft', '-95px');
        };
        
        
        callbacks['sendFeed'] = function(){
            support.alert($('#feedInput'), 'Send your first feed. But be carefull, everything you post can be seen by everyone unless you change the privacy.',callbacks['openFilesystem'], 'left');
            $('.alert.support').css('marginLeft', '-95px');
        };
        
        callbacks['dashTasks'] = function(){
            delay(function(){
                                    support.alert($('#taskBox'), 'Add Tasks for you or certain groups.',callbacks['dashGroups'], 'right');
            },700);
                                    
        };
        callbacks['dashGroups'] = function(){
                                    support.alert($('#groupBox'), 'You can create groups and invite your friends or colleagues to share files and other items with them.',callbacks['applicationList'], 'left');
                                    $('.alert.support').css('marginLeft', '-95px');
        };
                                
        callbacks['applicationList'] = function(){
                                    support.alert($('#appBox_box li:nth-of-type(2)'), 'You can open all available Applications with this list. Click on "Feed" to open the Application.',callbacks['sendFeed'], 'left', $('#appBox_box li:nth-of-type(3)'));
                                        $('.alert.support').css('marginLeft', '-95px');
        };
        
        callbacks['openDashboard'] = function(){
                                    support.alert($('#toggleDashboardButton'), 'Klick on this button you can toggle the "Dashboard" the second important panel inside the universe',callbacks['dashTasks'], 'bottom-left',$('#toggleDashboardButton'));
        };
      
        callbacks['init'] = function(){
                                    support.alert($('#dock'), 'This is the Dock, it is the main control for the universe',callbacks['openDashboard'], 'bottom-left');
        };
        
        
            //search something and play video
            //pause video inside the dock
            //toggle dashboard
            //open application feed
      
      //applications
            //feed
                //say your friends what you are doing
            //filesystem
                //open your userfolder
                //create element
                //add link or upload file
                //change privacy
            //settings
            
       
        applications.hideAll();
        
        callbacks['init']();
            
    };
};


var tabs = function(parentIdentifier){
    this.parentIdentifier = parentIdentifier;
    this.tabHistory = [0];  //start history with tab 0
    this.init = function(){
                        parentIdentifier = this.parentIdentifier;
                            $(parentIdentifier).append('<div class="tabFrame"><header><ul></ul></header></div>');

                    };
    this.initClicks = function(){
                        parentIdentifier = this.parentIdentifier;
                        var classVar = this;
                        $(parentIdentifier+' .tabFrame>header li').click(function(){
                                var tabId = $(this).attr('data-tab');
                                var tabParentIdentifier = $(this).attr('data-parent-identifier');
                                classVar.showTab(tabId);

                                $(parentIdentifier+' .tabFrame header ul li').removeClass('active');
                                $(this).addClass('active');
                        });

                        $(parentIdentifier+' .tabFrame>header li .close').click(function(){
                            var tabId = $(this).parent('li').attr('data-tab');
                            var tabParentIdentifier = $(this).parent('li').attr('data-parent-identifier');
                            classVar.removeTab(tabId);
                        });
                    };
    this.addTab = function(title, contentType, content, onClose){
                            parentIdentifier = this.parentIdentifier;
                            var numberOfTabs = $(parentIdentifier+' .tabFrame .tab').length;

                            var headerId = randomString(6, '#aA');

                            $(parentIdentifier+' .tabFrame header ul li').removeClass('active');
                            $(parentIdentifier+' .tabFrame header ul').append('<li id='+headerId+' data-tab="'+(numberOfTabs+1)+'" data-parent-identifier="'+parentIdentifier+'" data-title="'+title+'" class="active">'+gui.shorten(title, 15)+'<span class="close"><i class="icon icon-close"></i><i class="icon blue-close"></i></span></li>');

                            $(parentIdentifier+' .tabFrame .tab').hide();

                            if(typeof onClose === 'function'){
                                $('#'+headerId+' .close').click(function(){
                                    onClose();
                                });
                            }

                            this.tabHistory.push(numberOfTabs+1);

                            var $tab = $('<div class="tab tab_'+(numberOfTabs+1)+'"></div>');
                            if(typeof content === 'string')
                                $tab.append(content);

                            $(parentIdentifier+' .tabFrame').append($tab);

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
    //returns #uniqueId
    this.getTabIdByTabNumber = function(tabNumber){
                        parentIdentifier = this.parentIdentifier;
                        var ret;
                        $(parentIdentifier+' .tabFrame header ul li').each(function(){
                            if($(this).attr('data-tab') == tabNumber){
                                ret = $(this).attr('id');
                            }
                        });
                                return ret;
                    };
    this.getTabIdByTitle = function(tabTitle){
                        return this.getTabIdByTabNumber(this.getTabByTitle(tabTitle));
                    };
    this.showTab = function(tab){
                        this.tabHistory.push(tab);
                        parentIdentifier = this.parentIdentifier;

                        $(parentIdentifier+' .tabFrame header ul li').removeClass('active');
                        $(parentIdentifier+' .tabFrame header ul li#'+this.getTabIdByTabNumber(tab)).addClass('active');

                        $(parentIdentifier+' .tabFrame .tab').hide();
                        $(parentIdentifier+' .tabFrame .tab.tab_'+tab).show();
                    };

    //is used after closing a tab to show the last tab that was shown
    this.getLastTabId = function(){
        return this.tabHistory[this.tabHistory.length-1];
    };
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
    this.updateTabTitle = function(tab_identifier, title){
                        parentIdentifier = this.parentIdentifier;
                        $(parentIdentifier+' .tabFrame header ul li').each(function(){
                            if($(this).attr('data-tab') == tab_identifier)
                                $(this).html(gui.shorten(title,15)+'<span class="close"><i class="icon icon-close"></i><i class="icon blue-close"></i></span>');
                        });
                        this.initClicks();
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
    
              
//general functions
function empty(value){
                if(typeof value === 'undefined')
                    return true;
	  	if(value.length === 0)
	  		return true;
	  	else
	  		return false;
	  	
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
//feed
function feedLoadMore(destination ,type, user, limit){
	    $.get("doit.php?action=feedLoadMore&user="+user+"&limit="+limit+"&type="+type,function(data){
	    	$(destination).append(data);
		},'html');
	}
       

//filesystem
function smallSymbols() {
    $('#showElement').removeClass('largeSymbols');
    $('#showElement').addClass('smallSymbols');
}
function largeSymbols() {
    $('#showElement').removeClass('smallSymbols');
    $('#showElement').addClass('largeSymbols');
}
function listView() {
    $('#showElement').removeClass('smallSymbols');
    $('#showElement').removeClass('largeSymbols');
    
}

function initUploadify(id, uploader, element, timestamp, token){
		
	    $(function() {
	            $(id).uploadify({
	                    'formData'     : {
	                            'timestamp' : timestamp,
	                            'token'     : token,
	                            'element'   : element
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
	


function openFolder(folderId){
        applications.show('filesystem');
        filesystem.openFolder(folderId);
        return false;
        
    }

function openElement(elementId, title){
        applications.show('filesystem');
        filesystem.tabs.addTab(title, '',gui.loadPage('modules/filesystem/showElement.php?element='+elementId));
}

function openFile(type, typeId, title, typeInfo, extraInfo1, extraInfo2){
        
        title = 'Open '+title;
        
        userHistory.push(type, typeId);
        
        //bring reader to front
        applications.show('reader');
        
        
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
                         
                        //use player.loadYoutubeVideo in future
                        reader.tabs.addTab(title, '',gui.loadPage('./modules/reader/openFile.php?type=youTube&linkId='+linkId+'&typeInfo='+vId+'&extraInfo1='+playlist+'&extraInfo2='+row+'&external=1'));
 	
        	}else{
        		//use player.loadYoutubeVideo in future
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
            applications.show('reader');
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

function closeDockMenu(){
                $("#dockMenu").hide("fast");
              }
function getUserPictureBASE64(userid){
    
        //if type or itemId is array, handle as request for multiple items
        if(typeof userid === 'object'){
            var requests = [];
            $.each(userid,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({userid : value});
            });
            
            return api.query('api/user/getPicture/', { request: requests});
        }
        return api.query('api/user/getPicture/', { request: [{userid : userid}]})[0];
}
function getUserPicture(request){
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

                                            var userPictureObject = result;
                                            if(response.length > 0){
                                                $.each(userPictureObject, function(index, value) {
                                                        //add value to userPictures var
                                                        userPictures[index]=htmlentities(value);
                                                        response[index]=htmlentities(value);
                                                    });
                                            }

                                        }
if(is_numeric(request)){
                                        
                                        debug.log('     return numeric response');
			                return userPictures[userid];
			            }else{
			                return response;
			            }
                                    
                                    
				
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

function nl2br(str, is_xhtml) {
  //  discuss at: http://phpjs.org/functions/nl2br/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Philip Peterson
  // improved by: Onno Marsman
  // improved by: Atli Þór
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Maximusya
  // bugfixed by: Onno Marsman
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  //    input by: Brett Zamir (http://brett-zamir.me)
  //   example 1: nl2br('Kevin\nvan\nZonneveld');
  //   returns 1: 'Kevin<br />\nvan<br />\nZonneveld'
  //   example 2: nl2br("\nOne\nTwo\n\nThree\n", false);
  //   returns 2: '<br>\nOne<br>\nTwo<br>\n<br>\nThree<br>\n'
  //   example 3: nl2br("\nOne\nTwo\n\nThree\n", true);
  //   returns 3: '<br />\nOne<br />\nTwo<br />\n<br />\nThree<br />\n'

  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display

  return (str + '')
    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
};

var handler = new function(){
  this.query = function(handler_title, query, offset, max_results){
      
        //if type or itemId is array, handle as request for multiple items
        if(typeof handler_title === 'object'||typeof query === 'object'){
            var requests = [];
            $.each(query,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({
                                'handler_title':handler_title,
                                'action':'getTitle',
                                'parameters': {
                                    'query':value,
                                    'offset':offset,
                                    'max_results':max_results
                                }});
            });
            return api.query('api/handlers/', { request: requests});
        }else{
            
            return api.query('api/handlers/', { request: [{
                                'handler_title':handler_title,
                                'action':'query',
                                'parameters': {
                                    'query':query,
                                    'offset':offset,
                                    'max_results':max_results
                                }}]});
        };
  
      
      
      
      return api.query('api/handlers/', {
          'handler_title':handler_title,
          'action':'query',
          'parameters': {
              'query':query,
              'offset':offset,
              'max_results':max_results
          }
      });
  };
  //example
  //handler.getTitle('folders',1);
  //example
  //handler.getTitle('files',[1,2,9,54]);
  this.getTitle = function(handler_title, url){
        if(handler_title === 'folders'||handler_title === 'collections'||handler_title === 'files')
            return handlers[handler_title].getTitle(url);
        //if type or itemId is array, handle as request for multiple items
        if(typeof handler_title === 'object'||typeof url === 'object'){
            var requests = [];
            $.each(url,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({
                                'handler_title':handler_title,
                                'action':'getTitle',
                                'parameters': {
                                    'url':value
                                }});
            });
            return api.query('api/handlers/', { request: requests});
        }else{
            
            return api.query('api/handlers/', { request: [{
                                'handler_title':handler_title,
                                'action':'getTitle',
                                'parameters': {
                                    'url':url
                                }}]});
        };
  };
  this.getDescription = function(handler_title, url){
        if(typeof handler_title === 'object'||typeof url === 'object'){
            var requests = [];
            $.each(url,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({
                                'handler_title':handler_title,
                                'action':'getDescription',
                                'parameters': {
                                    'url':value
                                }});
            });
            return api.query('api/handlers/', { request: requests});
        }else{
            
            return api.query('api/handlers/', { request: [{
                                                            'handler_title':handler_title,
                                                            'action':'getDescription',
                                                            'parameters': {
                                                                'url':url
                                                            }}]});
        };
  };
  this.getThumbnail = function(handler_title, url){
        if(typeof handler_title === 'object'||typeof url === 'object'){
            var requests = [];
            $.each(url,function(index, value){
                //you can also enter a single type instead of multiple values
                requests.push({
                                'handler_title':handler_title,
                                'action':'getThumbnail',
                                'parameters': {
                                    'url':value
                                }});
            });
            return api.query('api/handlers/', { request: requests});
        }else{
            
            return api.query('api/handlers/', { request: [{
                                                            'handler_title':handler_title,
                                                            'action':'getThumbnail',
                                                            'parameters': {
                                                                'url':url
                                                            }}]});
        };
  };
  this.getLinkHandlerName = function(link){
      var ret = null;
      $.each(handlers, function(index, value){
          if(value.regex && link.match(value.regex)){
             ret = index;
          }
      });
      return ret;
  };
};

function singleOrMulti(item, cb){
    if(typeof item === 'object'){
        var results = [];
        $.each(item,function(index, value){
            results.push(cb(value));
        });
        return results;
    }else{
        return cb(item);
    }
}


//this class is used to add service objects
//IT IS NOT SUPPOSED FOR DIRECT CALLS
//USE WRAPPER handler INSTEAD
//E.G.:
//instead of useing
//handlers.youtube.getTitle('https://www.youtube.com/watch?v=9bZkp7q19f0');
//use
//handler.getTitle('youtube', 'https://www.youtube.com/watch?v=9bZkp7q19f0');
var handlers = {
    'youtube': {
                    application : 'reader',
                    regex : /((http|https):\/\/)?(www\.)?(youtube\.com)(\/)?([a-zA-Z0-9\-\.]+)\/?/,
                    open : function($target, link, onStop){
                            var videoId;
                            if(is_url(link)){
                                videoId = youtubeURLToVideoId(link);
                            }else{
                                videoId = link;
                            }

                            //generate random player id
                            //otherwise multiple videos
                            //can not be opened
                            var playerId = gui.generateId();

                            var output="";
                            output += "        <div id=\"ytplayer_"+playerId+"\"><\/div>";

                            $target.html(output);

                            // Load the IFrame Player API code asynchronously.
                            var tag = document.createElement('script');
                            tag.src = "http://www.youtube.com/player_api";
                            var firstScriptTag = document.getElementsByTagName('script')[0];
                            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                            //yt player api is added in plugins.js
                            var ytplayer;
                            ytplayer = new YT.Player('ytplayer_'+playerId, {
                              height: '100%',
                              width: '100%',
                              videoId: videoId,
                              autoplay: 1,
                              events: {
                                'onReady': onPlayerReady
                              }
                            });

                            function onPlayerReady(){
                                ytplayer.playVideo();
                            }

                            if(typeof onStop === 'function'){
                              ytplayer.addEventListener('onStateChange', function(state){
                                  if(state.data === 0){
                                      onStop();
                                  }
                                  if(state.data === 1){
                                      var callback = function(){
                                          ytplayer.pauseVideo();
                                      };
                                      console.log('pause');
                                      player.updateActiveItemPause(callback);
                                  }
                                  if(state.data === 2){
                                      var callback = function(){
                                          ytplayer.playVideo();
                                      };
                                      console.log('play');
                                      player.updateActiveItemPlay(callback);
                                  }
                              });
                            }

                    },
                    
                    getTitle : function(link){
                        if(typeof link === 'object'){
                            //return '';
                            var sources = handler.getTitle('youtube', link);
                            var results = [];
                            $.each(sources, function(index, value){
                                results.push(value);
                            });
                            return results;
                        }else
                            return handler.getTitle('youtube', link);
                    },
                    getDescription : function(link){
                        if(typeof link === 'object'){
                            //return '';
                            var sources = handler.getDescription('youtube', link);
                            var results = [];
                            $.each(sources, function(index, value){
                                results.push(value);
                            });
                            return results;
                        }else
                            return toString(handler.getDescription('youtube', link)).replace(/^"(.+(?="$))"$/, '$1');
                    },
                    getThumbnail : function(link){
                        if(typeof link === 'object'){
                            var sources = handler.getThumbnail('youtube', link);
                            var results = [];
                            $.each(sources, function(index, value){
                                results.push('<img src=\''+value.replace(/^"(.+(?="$))"$/, '$1')+'\'/>');
                            });
                            return results;
                        }else
                            return '<img src=\''+toString(handler.getThumbnail('youtube', link)).replace(/^"(.+(?="$))"$/, '$1')+'\'/>';
                    },
                    query: function(query, offset, max_results){
                        return handler.query('youtube', query, offset, max_results);
                    },
                    handler: function(link){
                        player.openItem('youtube', link);
                    }
                    
                },
    'wikipedia': {
                    application : 'reader',
                    regex : /((http|https):\/\/)?(www\.)?(wikipedia\.org)(\/)?([a-zA-Z0-9\-\.]+)\/?/,
                    open : function($target, link, onStop){
                            var title = this.getTitle(link);
                            var langCode = link.match(/((http|https):\/\/)?((www|(.*?))\.)?(wikipedia\.org)(\/)?([a-zA-Z0-9\-\.]+)\/?/, '');
                            
                            $target.html('<iframe style="border:none;position: absolute; top: 0; left: 0; height: 100%;width: 100%;right: 0;" src="https://'+langCode[4]+'.wikipedia.org/w/index.php?title='+title+'&printable=yes"></iframe>');
                    },
                    getTitle : function(link){
                        return singleOrMulti(link, function(value){
                            return toString(value).replace(/((http|https):\/\/)?((www|(.*?))\.)(wikipedia\.org)(\/)?([a-zA-Z0-9\-\.]+)\/?/, '');
                        });
                        
                    },
                    getDescription : function(link){
                        return handler.getDescription('wikipedia', link);
                    },
                    getThumbnail : function(link){
                        var apiResult = handler.getThumbnail('wikipedia', link);
                        if(apiResult)
                            return '<img src="'+apiResult+'"/>';
                        else
                            return '<span class="icon icon-wikipedia"></span>';
                    },
                    query: function(query, offset, max_results){
                        return handler.query('wikipedia', query, offset, max_results);
                    },
                    handler: function(link){
                        var title = this.getTitle(link);
                        reader.tabs.addTab('title', 'someHtml');
                    }
                },
    'folders': {
                    application : 'reader',
                    open : function($target, link, onStop){
                            

                    },
                    query: function(query, offset, max_results){
                        return handler.query('folders', query, offset, max_results);
                    },
                    getTitle: function(id){
                        return folders.folderIdToFolderTitle(id);
                    },
                    getDescription: function(id){
                        return 'wubba dubba du. wubbta tralala';
                    },
                    getThumbnail: function(id){
                        return '<span class="icon icon-folder"></span>';
                    },
                    handler: function(id){
                        folders.open(id);
                    }
                    
                },
    'collections': {
                    query: function(query, offset, max_results){
                        return handler.query('elements', query, offset, max_results);
                    },
                    getTitle: function(selector){
                        
                            return elements.getTitle(selector);
                        
                    },
                    getDescription: function(id){
                        
                        return singleOrMulti(id, function(value){
                            return 'wubba dubba du. wubbta asdasd';
                        });
                    },
                    getThumbnail: function(id){
                        return singleOrMulti(id, function(value){
                            return '<span class="icon icon-filesystem"></span>';
                        });
                    },
                    handler: function(id){
                        elements.open(id);
                    }
                    
                },
    'elements': {
                    query: function(query, offset, max_results){
                        return handler.query('elements', query, offset, max_results);
                    },
                    getTitle: function(id){
                        return elements.getTitle(id);
                    },
                    getDescription: function(id){
                        return 'wubba dubba du. wubbta asdasd';
                    },
                    getThumbnail: function(id){
                        return '<span class="icon icon-filesystem"></span>';
                    },
                    handler: function(id){
                        elements.open(id);
                    }
                    
                },
    'files': {
                    query: function(query, offset, max_results){
                        return handler.query('files', query, offset, max_results);
                    },
                    getTitle: function(id){
                        return filesystem.getFileTitle(id);
                    },
                    getDescription: function(id){
                        return singleOrMulti(id, function(value){
                            return 'wubba dubba du. wubbta asdasd';
                        });
                    },
                    getThumbnail: function(id){
                        return singleOrMulti(id, function(value){
                            return '<span class="icon icon-folder"></span>';
                        });
                    },
                    handler: function(id){
                        reader.openFile(id);
                    }
                },
    'links': {
                    handler: function(id){
                        var linkData = links.getData(id);
                        reader.openLink(handler.getLinkHandlerName(linkData['link']), linkData.link, linkData.title);
                    }
    }
};

var userHistory = new function(){
    this.storage = [];
    this.push = function(type, item_id, title){
        if(typeof title === 'undefined')
            title = '';
        this.storage.unshift({type:type, item_id:item_id, title:title});
        //reload history in display
        $("#reader .hometab .home.sectionA div.itemsA").html(reader.buildHistory(User.getHistoryArray()));
    };
    this.get = function(){
        return this.storage;
    };
};

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}
/**
 * Overwrites obj1's values with obj2's and adds obj2's if non existent in obj1
 * @param obj1
 * @param obj2
 * @returns obj3 a new object based on obj1 and obj2
 */
function merge_options(obj1,obj2){
    //http://stackoverflow.com/questions/171251/how-can-i-merge-properties-of-two-javascript-objects-dynamically
    var obj3 = {};
    for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
    for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
    return obj3;
}
