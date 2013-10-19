//initialize
var usernames = [];
                $(document).ready(function(){
                			
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
                

		            //init dashcloses 
					$('.dashBox .dashClose').click(function(){
						$(this).parent('.dashBox').slideUp();
					});	
                
                
                	//old creepy way to initalize windows => in future => css media width
                    var docWidth;
                    var oneSixthWidth;
                    var oneSixthHeight;
                        docWidth = $(document).width();
                    var docHeight = $(document).height();
                        oneSixthWidth = docWidth/6;
                        oneSixthHeight = docHeight/6;
                        var FeedOb = {
                        'top' : oneSixthHeight/6,
                        'left' : oneSixthWidth/5,
                        'width' : oneSixthWidth,
                        'height' : oneSixthHeight*2.75
                            }
                        var FileOb = {
                        'top' : oneSixthHeight*3.4-100,
                        'left' : oneSixthWidth/5,
                        'width' : oneSixthWidth*2.5,
                        'height' : oneSixthHeight*1.8
                            }
                        var ReaderOb = {
                        'top' : oneSixthHeight/6,
                        'left' : oneSixthWidth*1.3,
                        'width' : oneSixthWidth*3.5,
                        'height' : oneSixthHeight*2.75
                            }
                        var ChatOb = {
                        'top' : oneSixthHeight*3.4,
                        'left' : oneSixthWidth*2.8,
                        'width' : oneSixthWidth*2,
                        'height' : oneSixthHeight*1.8
                            }
                        var BuddylistOb = {
                        'top' : oneSixthHeight/6,
                        'left' : oneSixthWidth*4.9,
                        'width' : oneSixthWidth*1,
                        'height' : oneSixthHeight*5
                            }
                        $("#feed").css(FeedOb);
                        $("#filesystem").css(FileOb);
                        $("#reader").css(ReaderOb);
                        $("#chat").css(ChatOb);
                        $("#buddylist").css(BuddylistOb);
                        //$("#feed:hidden").fadeIn(3000);
                        $("#filesystem:hidden").fadeIn(3000);
                        //$("#chat:hidden").fadeIn(3000);
                        $("#buddylist:hidden").fadeIn(3000);
                        
                        
                        
                        //init draggable windows
                        initDraggable();
                        
                        //init bootstrap popover
                        $('.bsPopOver').popover();
                        
                        //init bootstrap alert
                        $(".alert").alert();
                    
                });
                
                

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

    
              
//window functions

              function initDraggable(){
                    //Draggable Window
                    $(function() {
                            $(".fenster").draggable({ 
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
                            




                    });
                    //Resizeable window
                    $(function() {
                            $(".fenster").resizable({
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
                    });
                    $(function() {
                        $('.fenster').children().click(function(){
							
                           	if($(this) != undefined){
                            $('.fenster').css('z-index', 1);
                            $(this).parent(".fenster").css('z-index', 999); 
                            $(this).parent(".fenster").css('position', 'absolute');
                            }
                        });
                    });
    	                            // $('.fenster').mouseout(function(){
                            	// $(this).children('.titel').hide(); 
                            	// $(this).css('padding', '0'); 
                            	// $(this).children('.inhalt').css('margin', '0'); 
                            	// $(this).children('.inhalt').css( "width", "+=12" );
                            // });
                            // $('.fenster').mouseover(function(){ 
                            	// $(this).children('.titel').show(); 
                            	// $(this).css('padding', '2px 0 35px'); 
                            	// $(this).children('.inhalt').css('margin', '0 6px'); 
                            	// $(this).children('.inhalt').css( "width", "auto" );
                            // });
              }


              function applicationOnTop(id){
                  $('.fenster').css('z-index', 1);
                  $("#"+id+"").css('z-index', 999);
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
                        }
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
                        }
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
              	$('#alerter').hide();
              	
              	$('#alerter').append('<div class="alert '+alertClass+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>').show().delay(5000).fadeOut();
              	
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
        createNewTab('reader_tabView', title,'','showContent.php?content='+content,true);return true
          
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
			  data: { userid : id },
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
       
//reader
	function openUploadTab(element){
	
        showApplication('filesystem');
        createNewTab('fileBrowser_tabView', 'Upload File','','modules/filesystem/upload.php?element='+element,true);return true
	}
	
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
        createNewTab('fileBrowser_tabView', title,'','modules/filesystem/showElement.php?element='+elementId,true);return true
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
            
        	}return false
        }
        
        if(type == 'RSS'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=RSS&linkId='+typeId,true);
            return false
        }
        
        if(type == 'wikipedia'){
        	//typeId needs to be changed to title
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=wiki&title='+typeId,true);
            return false
        }
        
        //real files
        if(type == 'UFF'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=UFF&fileId='+typeId,true);
            return false
        }
        if(type == 'document' ||type == 'application/pdf' ||type == 'text/plain'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?fileId='+typeId,true);
            return false
        }
        if(type == 'video' ||type == 'video/mp4' ||type == 'video/quicktime'  ){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=video&fileId='+typeId,true);
            return false
        }
        if(type == 'image/png' ||type == 'image/jpeg' || type == 'image'){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=image&fileId='+typeId,true);
            return false
            
        }else{
            alert(type);
            return false
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
    	openURL("http://wiki.universeos.org/index.php?title="+title, title)
    }
    
    function openURL(url, title){
    		url = encodeURI(url);
    		url = 'modules/reader/browser/?url='+url;
            createNewTab('reader_tabView',title,'',url,true);
            showApplication('reader');   
            return false
    }
    
    
//IM CHAT  
//IM CHAT  
//IM CHAT

       function openChatDialoge(username){
            showApplication('chat');   
            
            	//check if dialoge allready exists
                if($("#test_"+ username +"").length == 0){
                    createNewTab('chat_tabView1',username,'',"modules/chat/chatt.php?buddy="+username+"",true);
                    
                }else{
                	//if dialoge doesnt exists => bring dialoge to front..
                	
                	

                }
       }
       
        
      function chatLoadMore(username, limit){
           $.get("doit.php?action=chatLoadMore&buddy="+username+"&limit="+limit,function(data){
                    $('.chatMainFrame_'+username).append(data);
            },'html');
       }
       
    function addStrToChatInput(buddy, string){
        $('#chatInput_'+buddy).val($('#chatInput_'+buddy).val() + string);
    }
              
    function chatSetKey(username){

		if(localStorage.key[username]){
	  		jsAlert('', 'The key already has been set.');
		}else{
			localStorage.key[username] = $('#chatKeyInput_'+username).val();
			$('#chatCryptionMarker_'+username).val('true');
		  	$('#chatKeySettings_'+username).html('<a href="#" onclick="chatDeactivateKey(\''+username+'\'); return false;">deactivate key</a>');
			
	  		jsAlert('', 'The key for your buddy '+username+' has been set.');
			
            chatEncrypt(username);
            
            $('#toggleKey_'+username+' .lockIcon').addClass('locked');
            $('#chatKeySettings__'+username).hide();
            
		}
    }
    
    function chatDeactivateKey(username){
    	localStorage.key[username] = '';
			$('#chatCryptionMarker_'+username).val('false');
		$('#chatKeySettings_'+username).html('<form action="" method="post" target="submitter" onsubmit="chatSetKey(\''+username+'\'); return false;"><input type="password" name="key" placeholder="type key" id="chatKeyInput_'+username+'"></form>');
	    jsAlert('', 'The key has been removed');
        $('#toggleKey_'+username+' .lockIcon').removeClass('locked');
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
        $("#chatWindow_" + username + "").load("modules/chat/chatt.php?buddy=" + username + "&reload=1");
    }
    
    function chatMessageSubmit(username, userid){
    	if(localStorage.key[username]){
    		
    		 $('#chatInput_'+userid).val(CryptoJS.AES.encrypt($('#chatInput_'+userid).val(), localStorage.key[username]));
 		     //set cryption marker true so the php script could mark the message as crypted
    		 $('#chatCryptionMarker_'+username).val('true'); 
    		 
    	}else{
    		 $('#chatCryptionMarker_'+username).val('false'); 
    	}
    }
    
    function chatEncrypt(username){
    	if(localStorage.key[username]){
	    	$('.cryptedChatMessage_'+username).each(function(){
	    		var content = $(this).html();
	    		content = CryptoJS.AES.decrypt(content, localStorage.key[username]);
	    		content = content.toString(CryptoJS.enc.Utf8);
	    		$(this).html(content);
	    		$(this).removeClass('.cryptedChatMessage_'+username);
	    	});
    	}
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
                        }
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

                        
                        $(".rightclick").css('z-index', '9999');

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
                  return false
              }
              
              function showProfile(userId){
                  showApplication('reader');
                  createNewTab('reader_tabView',useridToUsername(userId),'',"./profile.php?user=" + userId + "",true);
                  return false
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
                      if (f.style.overflowY != 'scroll') { f.style.overflowY = 'scroll' }
                      return;
                   }
                   /* Make sure element does not have scroll bar to prevent jumpy-ness */
                   if (f.style.overflowY != 'hidden') { f.style.overflowY = 'hidden' }
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
                        }
                })(jQuery);  	

				var delay = (function(){
				  var timer = 0;
				  return function(callback, ms){
				    clearTimeout (timer);
				    timer = setTimeout(callback, ms);
				  };
				})();