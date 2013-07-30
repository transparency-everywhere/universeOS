  <html>
    <head>
        
        
        <!--meta information-->
        <meta name="description" content='Discover the social webOS. Connect with your friends, read your favourite book or RSS-Feed, watch your favourite movie, listen your favourite song and be creative...'>
        <META Name="keywords" content='universe, universeos, webdesktop, web desktop, social webdesktop, webos, youtube, youtube playlist, documentsrss,  free speech, human rights, privacy, community, social'>
        <meta name="title" content="universeOS">
        <meta name="Robots" content="index">
        <meta name="author" content="Transparency Everywhere">
        <meta name="classification" content=''>
        <meta name="reply-to" content=info@transparency-everywhere.com>
        <meta name="Identifier-URL" content="universeOS.org">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!--facebook pic-->
        <meta property="og:image" content="http://universeos.org/logo.png"/>
        
        <!--favicon-->
        <link rel="shortcut icon" href="http://universeOS.org/gfx/favicon.ico" />
        
        <link href="inc/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" type="text/css" href="inc/style.css">
        <script type="text/javascript" src="inc/ajax.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="inc/jquery2.js"></script>
        <script src="inc/plugins/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="inc/plugins.js"></script>
	
		<script src="inc/plugins/CryptoJS/rollups/aes.js"></script>
	
        <script type="text/javascript" src="inc/functions.js"></script>
        <link rel="shortcut icon" href="http://universeOS.org/gfx/favicon.ico" />
        <link href="inc/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        <script type="inc/jquery.jplayer.min.js"></script>
        <script src="inc/bootstrap/js/bootstrap.min.js"></script>
                <script type="text/javascript">

                $(document).mousemove(function(event){
                    window.mouseX = event.pageX;
                    window.mouseY = event.pageY;
                    $('.mousePop').hide();
                });
                    
                
              
              function updateUserActivity() {
              $("#loader").load("doit.php?action=updateUserActivity");
                }
//              function addComment() {
//              $("#loader").load("showComment.php");
//                }
              function showModuleMail() {
                    $.get("modules/mail/index.php",function(data){
                          $('#bodywrap').append(data);
                    },'html');
                }
              function showModuleSettings() {
                    $.get("modules/settings/index.php",function(data){
                          $('#bodywrap').append(data);
                          applicationOnTop('settings');
                    },'html');
                    
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
                
              function play() {
              $("#jquery_jplayer_2").jPlayer("play");
              }    
              function playPlaylist(playlist, row, fileId) {
                  
              alert("lol a" + fileId + " b" + playlist + " c" + row + " ");
              $("#dockplayer").load("./player/dockplayer.php?file=" + fileId +"&reload=1&playList=" + playlist +"&row=" + row + "");
              play();
              }
              function playFileDock(fileId) {
              $("#dockplayer").load("./player/dockplayer.php?file=" + fileId +"&reload=1");
              }
                
              function nextPlaylistItem(playList, row){
             	  $("#playListPlayer").load("playListplayer.php?playList=" + playList +"&row=" + row +"");
              }
              
              
              

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
                
                
                $(document).ready(function(){
                			
                	//init search
					$("#searchField").keyup(function()
					{
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
                        $("#reader:hidden").fadeIn(3000);
                        //$("#chat:hidden").fadeIn(3000);
                        $("#buddylist:hidden").fadeIn(3000);
                        
                        
                        
                        //init draggable windows
                        initDraggable();
                        
                        //init bootstrap popover
                        $('.bsPopOver').popover();
                    
                });
                
                
                function loader(id, link){
                    $("#" + id + "").load("" + link + "");
                }
                
                
                
                function deleteFromPersonals(id){
                    $("#loader").load("doit.php?action=deleteFromPersonals&id=" + id + "");
                }
                
                
                
                
                //initialize mousePop(tooltip)
                $('.tooltipper').mouseenter(function(){
                    
                    var type = $(this).attr("data-popType");
                    var id = $(this).attr("data-typeId");
                    var text = $(this).attr("data-text");
                    mousePop(type, id, text);
                }).mouseleave(function(){
                    $('.mousePop').hide();
                });



                //reload
                setInterval(function()
                {
                    $("#reload").load("reload.php");
                    //reloadFeed("friends");
                }, 3000);


                //loads clock into the dock, yeah.
                clock();
                
                //replace all links with <a href=link>link</a>
                $('body').html($('body').html().replace("/(b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|])/ig","<a href='$1'>$1</a>"));
                
                


        </script>
        <script src="inc/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="inc/plugins/ckeditor/jquery.js"></script>
        <?
        
        //define bg-image
        if(!empty($userdata[backgroundImg])){ 
            ?>
        <style type="text/css">
            body{
                background-image: url(<?=$userdata[backgroundImg];?>);
                background-attachment: no-repeat;
            }
        </style>
        <? }
        
        //look for startmessages
        if(!empty($userdata[startLink])){
        $startLink = ", popper('$userdata[startLink]');";
        }?>
        <title>universeOS</title>
    </head>