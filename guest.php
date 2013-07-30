<?php
session_start();
include_once("inc/functions.php");
include_once("inc/config.php");
//$usersql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
//$userdata = mysql_fetch_array($usersql);?>
    <script>
    
    	//
		function showRegistration(){
			showApplication('reader');
			if($('#registerTable').length == 0){
				createNewTab('reader_tabView','Register','','register_new.php',true);return false
			}
		}
    
    
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
    if(/^[a-zA-Z0-9- ]*$/.test(username) == false) {
        $('#checkUsernameStatus').html('<a style="color: red">&nbsp;contains illegal characters</a>');
    }else if(username.length > 2){
        
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
    
    .dockSeachResult{
        bottom: 41px;
    }
</style>
    <body onclick="clearMenu()" onload="clock()<?=$startLink;?>">
        <div style="position: absolute; top: 0px; right: 0px; left: 0px; padding-top:10px; padding-bottom:10px; background: #000; color: #383D3C; padding-top: 5px; padding-bottom: 3px; padding-left:10px;">
            <span style="color: #54545B;">&copy; 2013 <a href="http://transparency-everywhere.com" target="blank" style="color: #54545B;">Transparency Everywhere</a></span>
            
            <span style="margin-right: 10px; float:right;"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FuniverseOS&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=true&amp;font=segoe+ui&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=459795090736643" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:83px; height:21px; float: left" allowTransparency="true"></iframe>&nbsp;&nbsp;<a href="#" style="color: #54545B;" onclick="showContent('1', 'Site Notice');">Site Notice</a></span>
            <span style="margin-right: 30px; float:right;"></span>
        </div>
        <div id="reload"></div>
        <div id="bodywrap">
        	<div id="" style="position: absolute; top: 30px; left:30px;">
        		<ul>
        			<li>
        				
					</li>
        		</ul>
			</div>
            
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
    width: 460px;
    background: #3a3a3a; /* Old browsers */
	background: -moz-linear-gradient(top, #3a3a3a 0%, #303030 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#3a3a3a), color-stop(100%,#303030)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #3a3a3a 0%,#303030 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #3a3a3a 0%,#303030 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #3a3a3a 0%,#303030 100%); /* IE10+ */
	background: linear-gradient(to bottom, #3a3a3a 0%,#303030 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3a3a3a', endColorstr='#303030',GradientType=0 ); /* IE6-9 */
}

#startMainWindow {
    height: 150px;
    overflow: none;
}

#startMainHeader{
    height:30px;
    width: 460px;
    border-bottom:2px solid #3a3a3a;
    color: #FFF;
    padding-top: 10px;background: rgb(38,38,38); /* Old browsers */
background: -moz-linear-gradient(top, rgba(38,38,38,1) 0%, rgba(40,40,40,1) 49%, rgba(40,40,40,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(38,38,38,1)), color-stop(49%,rgba(40,40,40,1)), color-stop(100%,rgba(40,40,40,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(38,38,38,1) 0%,rgba(40,40,40,1) 49%,rgba(40,40,40,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#262626', endColorstr='#282828',GradientType=0 ); /* IE6-9 */
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
            <div id="startMainHeader" class="border-top-right-radius">
                <span style="font-size:19pt;font-weight: 1; font-weight: 100; margin-left:5px;">Please Sign In</span>
            </div>
        <hgroup>
            <form action="login.php" method="post" target="submitter">
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
                    <td><input type="button" onclick="showRegistration();" class="btn btn-success margin" value="Create Account"></td>
                    <td><input type="submit" value="Sign In" name="submit" class="btn btn-primary margin"></td>
                </tr>
            </table>
            </form>
        </hgroup>
        </div>
    </div>
    <script>
    <?
    $type = $_GET[type];
	$typeId = $_GET[typeId];
	switch($type){
		//focus on filesystem 
		case folders:
			echo"openFolder('$typeId');";
			break;
		case elements:
			break;
		//focus on reader
		case files:
			break;
		case links:
			break;
	}
    ?>
    </script>
        
<div id="dockMenu" class="fancy" style="display: none">
    <p style="font-size:15pt; margin-bottom:-15px;">universeOS</p>
    <ul class="appList">

        <li class="" onclick="toggleApplication('filesystem')" onmouseup="closeDockMenu()"><img src="./gfx/filesystem.png" border="0" height="16">&nbsp;&nbsp;Filesystem</li>


        <li class="" onclick="javascript: toggleApplication('reader')" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">&nbsp;&nbsp;Reader</li>
    </ul>
    <div>
    <div class="listContainer">
        <ul class="list messageList" id="dockMenuSystemAlerts"></ul>
        <header>System</header>
    </div>
    <div class="listContainer">
        <ul class="list messageList" id="dockMenuBuddyAlerts"></ul>
        <header>Buddies</header>
    </div>
    </div>
<!--    <p>Buddies</p>
    <ul class="messageList" id="dockMenuBuddyAlerts">
        
    </ul>-->
</div>
<!--    <div id="dockMenu" class="" style="display: none">
        <div class="dockMenuElement" onclick="toggleApplication('filesystem')" onmouseup="closeDockMenu()"><img src="./gfx/filesystem.png" border="0" height="16">&nbsp;&nbsp;Filesystem</div>
        <div class="dockMenuElement" onclick="javascript: toggleApplication('reader')" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">&nbsp;&nbsp;Reader</div>
    </div>-->
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
            $("#startbox").hide("slow");
            $("#dockMenu").toggle("slow");
        }); 
        
        //open login box
        $("#personalButton").click(function () {
            $("#dockMenu").hide("slow");
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
    ?><!-- Piwik -->
<script type="text/javascript"> 
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://analytics.universeos.org//";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://analytics.universeos.org/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Code -->

    </body>
    

