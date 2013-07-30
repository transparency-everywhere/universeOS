<?php
session_start();
include_once("../inc/functions.php");
include_once("../inc/config.php");
?>
  <html>
    <head>
        <!--meta information-->
        <meta name="description" content='Discover a new way to use the Internet. Connect with your friends, read your favourite RSS-Feed, watch your favourite movie, listen your favourite song or just hang around in the universeOS'>
        <META Name="keywords" content='rss, rss reader, youtube, free speech, human rights, web os, browser os, web operating system, browser operating system, privacy, community, transparency-everywhere os, transparency-everywhere community'>
        <meta name="title" content="universeOS">
        <meta name="Robots" content="index,follow">
        <meta name="author" content="Transparency Everywhere">
        <meta name="classification" content=''>
        <meta name="reply-to" content=info@transparency-everywhere.com>
        <meta name="Identifier-URL" content="universeOS.org">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!--facebook pic-->
        <meta property="og:image" content="http://universeos.org/logo.png"/>
        
        <!--favicon-->
        <link rel="shortcut icon" href="http://universeOS.org/gfx/favicon.ico" />
        
        <link href="../inc/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" type="text/css" href="../inc/style.css">
        <script type="text/javascript" src="../inc/ajax.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="../inc/jquery2.js"></script>
<!--        <script src="inc/plugins/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>-->
<!--        <link rel="stylesheet" type="text/css" href="inc/plugins/uploadify/uploadify.css">-->
	<script type="text/javascript" src="../inc/plugins.js"></script>
        <script type="text/javascript" src="../inc/functions.js"></script>
        <link rel="shortcut icon" href="http://universeOS.org/gfx/favicon.ico" />
        <link href="../inc/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        <script type="../inc/jquery.jplayer.min.js"></script>
        <script src="../inc/bootstrap/js/bootstrap.min.js"></script>
                <script type="text/javascript">

   $(document).mousemove(function(event){
       window.mouseX = event.pageX;
       window.mouseY = event.pageY;
       $('.mousePop').hide();
   });
                    
                
              
              function updateUserActivity() {
              $("#loader").load("doit.php?action=updateUserActivity");
                }
              function addComment() {
              $("#loader").load("showComment.php");
                }
              function showMail() {
              $("#loader").load("modules/mail/index.php");
                }
              function showSettings() {
              $("#loader").load("modules/settings/index.php");
                }
              
              function closeModuleSettings() {
              $("#invisibleSettings").hide("slow");
                }
              function openModule(moduleId) {
              $("#invisible" + moduleId + "").toggle("slow");
                }
              function closeModuleFeed() {
              $("#invisiblefeed").hide("slow");
                }
              function openModuleReader() {
              $("#invisiblereader").show("slow");
                }
              function closeModuleReader() {
              $("#invisiblereader").hide("slow");
                }
              function openModuleFilesystem() {
              $("#invisiblefilesystem").show("slow");
                }
              function closeModuleFilesystem() {
              $("#invisiblefilesystem").hide("slow");
                }
              function openModuleChat() {
              $("#invisiblechat").show("slow");
                }
              function closeModuleChat() {
              $("#invisiblechat").hide("slow");
                }
              function openModuleBuddylist() {
              $("#invisiblebuddylist").show("slow");
                }
              function closeModuleBuddylist() {
              $("#invisiblebuddylist").hide("slow");
                }
              function openModuleMail() {
              $("#invisiblemail").show("slow");
                }
              function closeModuleMail() {
              $("#invisiblemail").hide("slow");
                }
              function openPersonalFeed() {
              $("#personalFeed").show("slow");
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
              
              function toggleKey(buddy){
                    $("#toggleValue" + buddy +" ").show("slow");
              }
              
              function nextPlaylistItem(playList, row){
              $("#playListPlayer").load("playListplayer.php?playList=" + playList +"&row=" + row +"");
              }
              
              function showSettingsWindow(id){
                  
              //hide all itemSettingsWindows except for the selected    
              $(".itemSettingsWindow:not(.itemSettingsWindow"+id+")").hide();
              
              //show selected
              $(".itemSettingsWindow"+id+"").toggle();
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
                
                //init Search
                $(document).ready(function(){			
		$("#searchField").keyup(function()
		{
			var search;
			
			search = $("#searchField").val();
			if (search.length > 1)
			{
				$.ajax(
				{
					type: "POST",
					url: "../modules/suggestions/blSearch.php",
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
                });
                
                $(document).ready(function(){
                
                
                
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
                        'top' : oneSixthHeight*3.4,
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
                        $("#feed:hidden").fadeIn(3000);
                        $("#filesystem:hidden").fadeIn(3000);
                        $("#reader:hidden").fadeIn(3000);
                        $("#chat:hidden").fadeIn(3000);
                        $("#buddylist:hidden").fadeIn(3000);
                    
                });
                
                function chatSubmit(username){
                    $("#chatWindow_" + username + "").load("modules/chat/chatt.php?buddy=" + username + "&reload=1");
                }
                
                function loader(id, link){
                    $("#" + id + "").load("" + link + "");
                }
                function zoomIn(){
                    var PictureWidth = $("#viewedPicture").width();
                    var newWidth = PictureWidth*1.25;
                    $("#viewedPicture").css("width", newWidth);
                  }
                function zoomOut(){
                    var PictureWidth = $("#viewedPicture").width();
                    var newWidth = PictureWidth/1.25;
                    $("#viewedPicture").css("width", newWidth);
                  }
                
                
                function toggleProfileTabs(id){
                    $(".profileSlider").hide();
                    $("#" + id + "").slideDown();
                }                
                function toggleGroupTabs(id){
                    $(".groupSlider").hide();
                    $("#" + id + "").slideDown();
                }
                
                function deleteFromPersonals(id){
                    $("#loader").load("doit.php?action=deleteFromPersonals&id=" + id + "");
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
                    $("#reload").load("../reload.php");
                }, 3000);


                //loads clock into the dock, yeah.
                clock();
                
                $('body').html($('body').html().replace("/(b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|])/ig","<a href='$1'>$1</a>"));
                
                
//muss relativ sicher in die functions verschoben werden               
function initWysiwyg(id){

var config = {
		on: {
                    instanceReady: function() {
                        
                                //add eventlistener for onchange
                                this.document.on("keyup", function () {

                                    //if changed update file
                                    // ich muss ein lastudated feld zur db und eine javascript-lastupdated variable erstellen, um konflike zu vermiden
                                    var input = $('.uffViewer_'+id).val();
                                    $.post("../../../doit.php?action=writeUff", {
                                        id:id,
                                        input:input
                                        });
                                });
                    }
                }
	};
        
$('.uffViewer_'+id).ckeditor(config);
}

    

function initUffReader(id, content){
    initWysiwyg(id);
    
    $('.uffViewer_'+id).val(content);
}

        </script>
        <script src="inc/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="inc/plugins/ckeditor/jquery.js"></script>
        <title>universeHOSSA</title>
        <script>
        
        </script>
        <style>
            #outBrowser{
                position: absolute;
                top: 15px;
                left: 15px;
                right: 15px;
                bottom: 80px;
            }
            
            .browserBar{
            }
        </style>
    </head>
    <body onclick="clearMenu()" onload="clock()<?=$startLink;?>">
   
            <div class="fenster" id="outBrowser">
                <header class="titel">
                    <p>universeOS</p>
                </header>
                <div class="inhalt autoFlow">
                    <div class="browserBar">
                        
                    </div>
                    <div class="browserFrame"></div>
                </div>
            </div>
        
        
        
        
        
        <div id="reload">
        </div>
        
        
        <div id="suggest">
        </div>
        
        <div class="box-shadow border-top-right-radius" id="startbox" style="display: none;">
            <div id="startMainWindow">
                <div id="startMainHeader" class="border-top-right-radius grayGradient">
                    <center style="font-size: 13pt;">Please Sign In</center>
                </div>
            <hgroup>
                <form action="login.php" method="post">
                <table width="100%" valign="center">
                    <tr height="10">
                        <td></td>
                    </tr>
                    <tr valign="top">
                        <td><input type="text" name="username" placeholder="username" class="margin bigInput" style="width: 170px;"></td>
                        <td><input type="password" name="password" placeholder="password" class="margin bigInput" style="width: 170px;"></td>
                    </tr>
                    <tr>
                        <td style="color: #FFFFFF" align="center"><?=$loginerror;?></td>
                    </tr>
                    <tr>
                        <td><input type="button" onclick="createNewTab('reader_tabView','Register','','register_new.php',true);return false" class="btn btn-success margin" value="Create Account"></td>
                        <td><input type="submit" value="Sign In" name="submit" class="btn btn-primary margin"></td>
                    </tr>
                </table>
                </form>
            </hgroup>
            </div>
        </div>
        <div id="dockMenu" class="" style="display: none">
            <div class="dockMenuElement" onclick="toggleApplication('filesystem')" onmouseup="closeDockMenu()"><img src="./gfx/filesystem.png" border="0" height="16">&nbsp;&nbsp;Filesystem</div>
            <div class="dockMenuElement" onclick="javascript: toggleApplication('reader')" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">&nbsp;&nbsp;Reader</div>
        </div>
        <div id="dock">
            <table>
                <tr valign="top">
                    <td><div id="personalButton" class="module" style="margin-top: 4px;">&nbsp;&nbsp;Login</div></td>
                    <td><div id="moduleMenu" class="module" style="font-color: #FFF;">&nbsp;&nbsp;Start</div></td>
                    <td><div id="modulePlayer" class="module">&nbsp;&nbsp;Player</div></td>
                    <td align="right" id="clockDiv" style="color: #FFFFFF; float: right"></td>
                    <td align="right"><input type="text" name="searchField" id="searchField" class="border-radius" placeholder="search"></td>
                </tr>
            </table>
        </div>
    <? 
    $zeit=gettimeofday();
    $endzeit=$zeit["usec"];
    $gesamtzeit=round(($endzeit-$startzeit)/1000,0);
    
    //jsAlert("$gesamtzeit");
    ?>
    </body>
    

