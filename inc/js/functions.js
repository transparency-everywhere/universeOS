//initialize
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
                                    	
                                        if($(this).is(':checked')){
                                            //$('.privacyBuddyTrigger').prop('checked', true);
                                        }else{
                                           // $('.privacyBuddyTrigger').prop('checked', false);
                                        }
                                    	$('.privacyShowBuddy').show();
                                    });
                                    
                                    $('.privacyGroupTrigger').click(function(){
                                    	$('.privacyShowGroups').show();
                                        if($(this).is(':checked')){
                                            //$('.privacyGroupTrigger').prop('checked', true);
                                        }else{
                                            //$('.privacyGroupTrigger').prop('checked', false);
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
              	}
              	this.resizableApplications = function(){
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
              	}
              	this.applicationOnTop = function(){
                        $('.fenster').children().mousedown(function(){
							
                           	if($(this) != undefined){
                           		
                  			$(".fenster").css('z-index', 999);
                            $(this).parent(".fenster").css('z-index', 9999); 
                            $(this).parent(".fenster").css('position', 'absolute');
                            }
                        });
              	}
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
              	}
              	
              	this.dashBox = function(){
		            //init dashcloses 
					$('.dashBox .dashClose').click(function(){
						$(this).parent('.dashBox').slideUp();
					});	
              	}
              	
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
              		
              	}
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
              	}
              	
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
              		
              		
              		//fade in applications
                    $("#filesystem:hidden").fadeIn(3000);
                    $("#buddylist:hidden").fadeIn(3000);
                    
                    $("#feed:hidden").fadeIn(3000);
                    $("#chat:hidden").fadeIn(3000);
                        
              	};
              	
              }
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
				  
				  }
				  
				  this.addTab = function(title, type, content){
				  	this.index++;
				  	$(this.parentSelector).children('.tabs .tabBar').append('<li class="active" id="1">'+firstTitle+'</li>');
				  	$(this.parentSelector).children('.tabs').append('<div class="tab active" id="'+this.index+'"></div>');
				  }
				  
				  this.updateTab = function(id, title, type, content){
				  	
				  }
				  this.deleteTab = function(id){
				  	
				  }
			  }
              
			  var application = new function(){
			  	this.onTop = new function(id){
			  		
                  $(".fenster").css('z-index', 998);
                  $("#"+id+"").css('z-index', 999);
                  $("#"+id+"").css('position', 'absolute');
			  		
			  	};
			  	
			  	this.show = new function(id){
                  applicationOnTop(id);
                  $("#" + id +"").show();
			  	};
			  	
			  	this.hide = new function(id){
                  $("#" + id +"").hide();
			  	};
			  	
			  	this.fullscreen = new function(id){
                  $("#" + id +"").toggle();
			  	};
			  	
			  	this.returnFromFullScreen = new function(id){
              	$('#'+id+' .fullScreenIcon').attr("onClick","moduleFullscreen('"+id+"')");
                  var returnFullScreenCSS = {
                        'position' : 'absolute',
                        'top' : window.fullScreenOldMarginY,
                        'left' : window.fullScreenOldMarginX,
                        'width' : window.fullScreenOldX,
                        'height' : window.fullScreenOldY
                        }
                  $("#" + id + "").css(returnFullScreenCSS);
			  	};
			  }


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
	              	
	              	$('#alerter').append('<div class="alert '+alertClass+'"><button type="button" class="close" data-dismiss="alert">&times;</button>'+message+'</div>');
	              	$('.alert').delay(5000).fadeOut(function(){
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
              	}
              	
              }
              
              var filesystem =  new function() {
              	
              	this.openShareModal = function(type, typeId){
              		
              		var title;
              		var content;
              		var universeFileBrowserURL;
              		switch(type){
              			case 'file':
              				var fileTitle = files.fileIdToFileTitle(typeId);
              				title = 'Share "'+fileTitle+'"';
              				universeFileBrowserURL = '?file='+typeId;
              				universeKickStarterURL = '?file='+typeId; //should be the same like fileBrowserURL 
              			break;
              			case 'elemement':
              			break;
              		}
              		universeFileBrowserURL = sourceURL+'/out/'+universeFileBrowserURL;
              		
              		content = '<ul class="shareList">';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #facebook\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Facebook <img src="gfx/startPage/facebook.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #twitter\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Twitter <img src="gfx/startPage/twitter.png"></li>';
	              		content += '<li onclick="$(\'.shareBox li\').hide(); $(\'.shareBox #embed\').slideDown(); $(\'.shareList li\').removeClass(\'active\'); $(this).addClass(\'active\');">Embed Code <img src="gfx/startPage/wikipedia.png"></li>';
              		content += '</ul>';
              		
              		content += '<ul class="shareBox">';
              			content += '<li id="facebook"><center><a target="_blank" href="http://www.facebook.com/sharer/sharer.php?u='+universeFileBrowserURL+'" class="btn btn-success"><img src="gfx/startPage/facebook.png" height="20"> Click Here To Share</a></center></li>'
              			content += '<li id="embed"><center><textarea><iframe src="'+universeFileBrowserURL+'"></iframe></textarea></center>Just place the HTML code for your Filebrowser wherever<br> you want the Browser to appear on your site.</li>';
              			content += '<li id="twitter"><center><a target="_blank" href="https://twitter.com/share?url='+universeFileBrowserURL+'" class="btn btn-success"><img src="gfx/startPage/twitter.png" height="20"> Click Here To Share</a></center></li>';
              		content += '</ul>';
              		
              		
              		modal.create(title, content);
              	}
              	
              }
              
              
              var modal =  new function() {
			    this.title = localStorage.currentUser_userid;
			    this.html = '';
			    this.create = function (title, content, action) {
			    	this.html += '<div class="blueModal border-radius container">';
	            		this.html += '<header>';
	            			this.html += title;
	            		this.html += '</header>';
	            		this.html += '<div class="content">';
	            		this.html += content;
	            		this.html += '</div>';
	            		this.html += '<footer>';
	            		
	                 		this.html += '<a href="#" onclick="$(\'.blueModal\').hide(); return false;" class="btn pull-left">Close</a>';
	                 		if(typeof action !== 'undefined'){
	                			this.html += '<a href="#" onclick="'+action+'" class="btn btn-primary pull-right">&nbsp;&nbsp;Next&nbsp;&nbsp;</a>';
	                 		}
	            		
	            		this.html += '</footer>';
	            		
	            	this.html += '</div>';
            		
            		$('#popper').append(this.html);
			    };
			}
       
       
//encryption functions

	function symEncrypt(key, message){
		var msg
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
			var hash = CryptoJS.MD5(password);
			return hash.toString(CryptoJS.enc.Hex);
		}
		this.SHA512 = function(string){
			var hash = CryptoJS.SHA512(salt+passwordHashMD5);
			return hash.toString(CryptoJS.enc.Hex);
		}
	}
	
	
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
				    
				    return [passwordHash, passwordHashMD5, keyHash];
			    }
			    
			    this.randomString = function(){
			    	return hash.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
			    	
			    }
			}
	
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
        createNewTab('fileBrowser_tabView', 'Upload File','','modules/filesystem/upload.php?element='+element,true);return true
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
				        		eval(data);
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
        if(type == 'audio' ||type == 'audio/wav' ||type == 'audio/mpeg'  ){
            createNewTab('reader_tabView',title,'','./modules/reader/openFile.php?type=audio&fileId='+typeId,true);
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
	    return string
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
		var mdOld = CryptoJS.MD5(oldPassword);
		var passwordHashMD5Old  = mdOld.toString(CryptoJS.enc.Hex);
		
		var saltOld = getSalt('auth', userid, passwordHashMD5Old); //get auth salt, using md5 hash as key
		console.log(saltOld);
		
	    var shaPassOld = CryptoJS.SHA512(saltOld+passwordHashMD5Old);
	    var passwordHashOld = shaPassOld.toString(CryptoJS.enc.Hex);
		
		//cypher new password
		var mdNew = CryptoJS.MD5(newPassword);
		var passwordHashMD5New  = mdNew.toString(CryptoJS.enc.Hex);
		console.log(passwordHashMD5New);
		
		
    	var saltNew = saltOld;
		console.log(salt);
	    var shaPassNew = CryptoJS.SHA512(saltNew+passwordHashMD5New);
    	var salt = symEncrypt(passwordHashMD5New, saltNew); //encrypt the new salt with md5password
	    var passwordHashNew = shaPassNew.toString(CryptoJS.enc.Hex);
	    
	    console.log(passwordHashNew);
	    
	    var shaKeyNew = CryptoJS.SHA512(passwordHashMD5New+salt);
	    var keyHashNew = shaKeyNew.toString(CryptoJS.enc.Hex);
	    
	    console.log('dsa');
	    var privateKey = getPrivateKey('user', userid, saltOld, passwordHashMD5Old); //get the old private key, using the old salt
	    console.log(privateKey);
	    var newPrivateKey = symEncrypt(passwordHashMD5New, privateKey);
		    		
	    
	                $.post("api.php?action=updatePassword", {
	                       oldPassword:passwordHashOld,
	                       newPassword:passwordHashNew,
	                       newPrivateKey: newPrivateKey,
	                       newSalt: salt
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
	    			
	    		
		    		var salt = getSalt('auth', localStorage.currentUser_userid, localStorage.currentUser_passwordHashMD5);
	                var privateKey = getPrivateKey('user', localStorage.currentUser_userid, salt);
		    		
		    		
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