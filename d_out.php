<?php
session_start();
include_once("inc/functions.php");
include_once("inc/config.php");
$usersql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
$userdata = mysql_fetch_array($usersql);
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
        
        <link href="inc/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" type="text/css" href="inc/style.css">
        <script type="text/javascript" src="inc/ajax.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript" src="inc/jquery2.js"></script>
<!--        <script src="inc/plugins/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>-->
<!--        <link rel="stylesheet" type="text/css" href="inc/plugins/uploadify/uploadify.css">-->
	<script type="text/javascript" src="inc/plugins.js"></script>
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
					url: "modules/suggestions/blSearch.php",
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
                    $("#feedInput").keyup(function()
                    {
                            var feed;

                            feed = $("#feedInput").val();
                            if (feed.match(/http/g) != null)
                            {
                                    $.ajax(
                                    {
                                            type: "POST",
                                            url: "modules/suggestions/blSearch.php",
                                            data: "search=" + feed,
                                            success: function(messagea)
                                            {	
                                                    $("#suggest").empty();
                                                    if (feed.length < 2)
                                                    {						
                                                            $("#suggest").append(messagea);
                                                            
                    
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
                    $("#reload").load("reload.php");
                }, 30000);


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
        <script>
       //proof registration
       
        function checkReg(){
                var valuee;
                var check;
                var checkBox;
                var passwordCheck;
                $(".checkReg").each(function() {
                    valuee = $(this).val();
                        if(valuee === ""){
                            check = "FALSE";
                        }else{
                            if(check === ""){
                            check = "TRUE";
                            }
                        }
                });
                $(".checkRegBox").each(function() {
                    checkBox = $(this).is(':checked');
                        if(valuee){
                            checkBox = "TRUE";
                        }else{
                            checkBox = "FALSE";
                        }
                });
                if($("#password1").val() != $("#password2").val()){
                    passwordCheck = "FALSE";
                }

                    if(check == "FALSE" || passwordCheck === "FALSE" || checkBox === "FALSE"){
                        if(check == "FALSE"){
                        alert("Please fill out all the fields");
                        }
                        if(checkBox == "FALSE"){
                        alert("Your have to accept our terms.");
                        }
                        if(passwordCheck == "FALSE"){
                        alert("Your passwords dont match.");
                        }
                    }else{
                        processRegistration()
                        //$("#regForm").submit();
                    }
        }
function checkUsername(id){
    var username = $("#"+id).val();
    if(username.length > 2){
        
                //check server for new messages
                $.post("api.php?action=checkUsername", {
                       username:username
                       }, function(result){
                            var res = result;
                            if(res == "1"){
                                //load checked message
                                $('#checkUsernameStatus').html('<a style="color: green">&nbsp;succes!</a>');
                            }else{
                                $('#checkUsernameStatus').html('<a style="color: red">&nbsp;already in use</a>');
                            }
                       }, "html");
                
               
        
        
    }else{
        //html to short
        $('#checkUsernameStatus').html('<o style="color: red">&nbsp;to short</o>');
    }
}

function processRegistration(){
    var username = $("#username").val();
    var password = $("#password1").val();
    var captcha = $("#captcha").val();
                //submit registration
                $.post("api.php?action=processSiteRegistration", {
                       username:username,
                       password:password,
                       captcha:captcha
                       }, function(result){
                            var res = result;
                            if(res == 1){
                                //load checked message
                                alert('You just joined the universeOS');
                                deleteTab('Register');
                            }else{
                                alert(res);
                            }
                       }, "html");

}
</script>
<style>
    #bodywrap{
        top: 31px;
    }
    
    #offlineHeader{
        position: absolute;
        top: 0px;
        right: 0px;
        left: 0px;
        padding-top:10px;
        padding-bottom:10px;
        background: #000000;
        color: #383D3C;
        padding-top: 5px;
        padding-bottom: 5px;
        padding-left:10px;
    }
</style>
        <?
        if(!empty($userdata[backgroundImg])){
            ?>
<style type="text/css">
    body{
        background-image: url(<?=$userdata[backgroundImg];?>);
        background-attachment: no-repeat;
    }
</style>
        <? }
        if(!empty($userdata[startLink])){
        $startLink = ", popper('$userdata[startLink]');";
        }?>
        <title>universeOS</title>
        <script src="inc/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body onclick="clearMenu()" onload="clock()<?=$startLink;?>">
        <div style="position: absolute; top: 0px; right: 0px; left: 0px; padding-top:10px; padding-bottom:10px; background: #000; color: #383D3C; padding-top: 5px; padding-bottom: 3px; padding-left:10px;">
            <span style=""><a href="http://transparency-everywhere.com" target="blank" style="color: #54545B;">&copy; 2013 Transparency Everywhere</a></span>
            
            <span style="margin-right: 10px; float:right;"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FuniverseOS&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=true&amp;font=segoe+ui&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=459795090736643" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:83px; height:21px; float: left" allowTransparency="true"></iframe>&nbsp;&nbsp;<a href="contact.php" target="blank" target="blank" style="color: #54545B;">Contact</a>&nbsp;&nbsp;<a href="contact.php" target="blank" style="color: #54545B;">Site Notice</a></span>
            <span style="margin-right: 30px; float:right;"></span>
        </div>
        <div id="reload"></div>
        <div id="bodywrap">
            
        <? include("modules/reader/index.php") ?>
        
        <div id="invisiblefilesystem"><? include("modules/filesystem/filesystem.php") ?></div></div>
        <div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>
        <div id="suggest">
        </div>
        
        
        
    <div id="dockplayer" style="display: none">
        <?
        include("player/dockplayer.php");
        ?>
    </div>
        
        
    <style type="text/css">
#startbox {
    
    position: absolute;
    bottom: 40px;
    left: 0px;
    height: 150px;
    width: 430px;
    background: #000;
}

#startMainWindow {
    height: 150px;
    overflow: auto;
}

#startMainHeader{
    height:30px;
    width: 430px;
    border-bottom:2px solid #3a3a3a;
    color: #FFF;
    padding-top: 10px;
}

.border-radius {
    border-radius: 6px;
    -o-border-radius: 6px;
    -moz-border-radius: 6px;
    -khtml-border-radius: 6px;
    -webkit-border-radius: 6px;
}

.border-top-right-radius {
    border-top-right-radius: 6px;
    -o-border-top-right-radius: 6px;
    -moz-border-top-right-radius: 6px;
    -khtml-border-top-right-radius: 6px;
    -webkit-border-top-right-radius: 6px;
}

.text-shadow{
   text-shadow:0 0 1px #fff, 2px 2px 3px #fff, -3px -3px 5px #fff, -3px 3px 5px #fff, 3px -3px 5px #fff;
}

.margin{
    margin-left: 20px;
}

.button{
	cursor: pointer;
	text-decoration:none;
	border: 1px solid rgb(153, 153, 153);
	padding: 5px 5px 5px 5px;
	color:rgb(0, 0, 0);
	font-size:12px;
	font-family:arial, serif;
	text-shadow: 0px 0px 5px rgb(255, 255, 255);
	font-size: 12px;
	border-radius:5px 5px 5px 5px;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius:5px 5px 5px 5px;
	box-shadow:0px 0px 0px rgb(0, 0, 0);
	-moz-box-shadow:0px 0px 0px rgb(0, 0, 0);
	-webkit-box-shadow:0px 0px 0px rgb(0, 0, 0);
	background-color: rgb(255, 255, 255);
	background-image:linear-gradient(-90deg, rgb(238, 238, 238), rgb(204, 204, 204));
	background-image:-webkit-gradient(linear, 50% 0%, 50% 100%, from(rgb(238, 238, 238)), to(rgb(204, 204, 204)));
	background-image:-moz-linear-gradient(-90deg, rgb(238, 238, 238), rgb(204, 204, 204));
}
.button:hover{
	cursor: pointer;
	text-decoration:none;
	border: 1px solid rgb(153, 153, 153);
	padding: 5px 5px 5px 5px;
	color:rgb(0, 0, 0);
	font-size:12px;
	font-family:arial, serif;
	text-shadow: 0px 0px 5px rgb(255, 255, 255);
	font-size: 12px;
	border-radius:5px 5px 5px 5px;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius:5px 5px 5px 5px;
	box-shadow:0px 0px 0px rgb(0, 0, 0);
	-moz-box-shadow:0px 0px 0px rgb(0, 0, 0);
	-webkit-box-shadow:0px 0px 0px rgb(0, 0, 0);
	background-color: rgb(255, 255, 255);
	background-image:linear-gradient(-90deg, rgb(238, 238, 238), rgb(204, 204, 204));
	background-image:-webkit-gradient(linear, 50% 0%, 50% 100%, from(rgb(238, 238, 238)), to(rgb(204, 204, 204)));
	background-image:-moz-linear-gradient(-90deg, rgb(238, 238, 238), rgb(204, 204, 204));
}
.button:active{
	cursor: pointer;
	text-decoration:none;
	border: 1px solid rgb(153, 153, 153);
	padding: 5px 5px 5px 5px;
	color:rgb(0, 0, 0);
	font-size:12px;
	font-family:arial, serif;
	text-shadow: 0px 0px 5px rgb(255, 255, 255);
	font-size: 12px;
	border-radius:5px 5px 5px 5px;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius:5px 5px 5px 5px;
	box-shadow:0px 0px 0px rgb(0, 0, 0);
	-moz-box-shadow:0px 0px 0px rgb(0, 0, 0);
	-webkit-box-shadow:0px 0px 0px rgb(0, 0, 0);
	background-color: rgb(255, 255, 255);
	background-image:linear-gradient(-90deg, rgb(238, 238, 238), rgb(204, 204, 204));
	background-image:-webkit-gradient(linear, 50% 0%, 50% 100%, from(rgb(238, 238, 238)), to(rgb(204, 204, 204)));
	background-image:-moz-linear-gradient(-90deg, rgb(238, 238, 238), rgb(204, 204, 204));
}
</style>
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
    <script>
        //open player
        $("#modulePlayer").click(function () {
            $("#dockplayer").toggle("slow");
        });
        
        //open menu
        $("#moduleMenu").click(function () {
            $("#dockMenu").toggle("slow");
        }); 
        
        //open login box
        $("#personalButton").click(function () {
            $("#startbox").toggle("slow");
            $("#startbox").css('z-index', 9999);
            $("#startbox").css('position', 'absolute');
        });
    </script>
        
        
    </div>
    <? 
    $zeit=gettimeofday();
    $endzeit=$zeit["usec"];
    $gesamtzeit=round(($endzeit-$startzeit)/1000,0);
    
    //jsAlert("$gesamtzeit");
    ?>
    </body>
    

