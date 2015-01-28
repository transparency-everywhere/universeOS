<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include_once("inc/functions.php");
include_once("inc/config.php");
?>
	<script type="text/javascript" src="inc/js/guest.js"></script>
        <link rel="stylesheet" type="text/css" href="inc/css/guest.css" media="all" />
        <div id="bodywrap">
        
        <div id="guestArea">
            <div class="guestBox registerBox">
                <h2>Sign Up</h2>
                <div class="pull-left">
                    <form id="registrationForm">
                    <input type="text" id="regUsername" placeholder="Your Username" onblur="registration.checkUsername('regUsername');" class="checkReg">
                    <span id="checkUsernameStatus" class="regError"><div class="arrow-right"></div></span>
                    <input type="password" id="password" placeholder="Your Password" class="checkReg" onkeyup="registration.checkPassword('password');">
                    <span id="checkPasswordStatus" class="regError"><div class="arrow-right"></div></span>
                    <input type="password" style="margin-bottom:15px;" id="passwordRepeat" placeholder="Repeat Password" class="checkReg">
                    <span style="display: inline-block;padding-top: 10px"><input type="checkbox" style="display: inline-block;" class="checkRegBox"> I accept the <a href="#">terms</a></span>
                    <input type="hidden" value="" id="checkReg">
                    <input type="submit" class="button" value="Sign me up!">
                    </form>
                </div>
                <img src="gfx/loading-bubbles.svg" width="150" height="150">
                <p>
                    Lorem Ipsum Supidubu bbaskd jaskaj sdfakshkgjsfdbsnk
                </p>
            </div>
            <div class="guestBox" id="clearBox">
                <div class="image"><img src="gfx/guest/guest_01.png" style="width: 153px"/></div>
                <p>save, organize and share your files</p>
            </div>
            <div class="guestBox">
                <div class="image"><img src="gfx/guest/guest_02.png" style="width: 125px"/></div>
                <p>use public files and make your files accesible</p>
            </div>
            <div class="guestBox">
                <div class="image"><img src="gfx/guest/guest_03.png" style="width: 159px; width: 159px; margin-top: 12px;"/></div>
                <p>present yourself and stay in touch with your buddies</p>
            </div>
            <div class="guestBox">
                <div class="image"><img src="gfx/guest/guest_04.png" style="width: 169px"/></div>
                <p>encrypt your data and communication</p>
            </div>
        </div>
        
        <div id="alerter" class="container"></div><div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>
        <div id="suggest">
        </div>
        
    </div>
        
    <!--login menu-->
    <div class="box-shadow border-top-right-radius" id="startbox" style="display: none;">
        <div id="startMainWindow">
            <div id="startMainHeader" class="border-top-right-radius">
                <span style="font-size:19pt;font-weight: 1; font-weight: 100; margin-left:5px;">Please Log In</span>
            </div>
            <hgroup>
                <form method="post" target="submitter" id="loginForm" onsubmit="login(); return false;">
                <table width="100%" valign="center">
                    <tr height="10">
                        <td></td>
                    </tr>
                    <tr valign="top">
                        <td><input type="text" placeholder="username" id="loginUsername" class="margin bigInput" style="width: 170px;"></td>
                        <td><input type="password" placeholder="password" id="loginPassword" class="margin bigInput" style="width: 170px;"></td>
                    </tr>
                    <tr>
                        <td style="color: #FFFFFF" align="center"></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Log In" name="submit" class="btn btn-primary margin"></td>
                    </tr>
                </table>
                </form>
            </hgroup>
        </div>
    </div>
    <!--/login menu-->
        
    <div id="dockMenu" class="fancy" style="display: none">
        <header>universeOS&nbsp;</header>
        <ul class="appList">

            <li class="" onclick="filesystem.show()" onmouseup="closeDockMenu()"><i class="icon icon-folder" style="height:16px;width:16px;"></i>&nbsp;&nbsp;Filesystem</li>


            <li class="" onclick="reader.show()" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">&nbsp;&nbsp;Reader</li>
        </ul>
        <div>
        </div>
    </div>
    <div id="dock">
        <table>
            <tr valign="top">
                <td><div id="personalButton" class="button">Log In</div></td>
                <td><div id="moduleMenu" class="button">Start</div></td>
                <!-- <td><div id="modulePlayer" class="module">&nbsp;&nbsp;Player</div></td> -->
                <td align="right"><input type="text" name="searchField" id="searchField" placeholder="search"></td>
                <td align="right" id="clockDiv" style="color: #FFFFFF; float: right"></td>
            </tr>
        </table>
    </div>
    <script>
        //open menu
        $("#moduleMenu").click(function () {
            $("#startbox").hide("slow");
            $("#dockMenu").slideToggle("slow");
        }); 
        
        //open login box
        $("#personalButton").click(function () {
            $("#dockMenu").hide("slow");
            $("#startbox").slideToggle("slow");
            $("#startbox").css('z-index', 9999);
            $("#startbox").css('position', 'absolute');
        });
    </script>
<!-- Piwik only on page for unregistered users -->
<script type="text/javascript"> 
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://analytics.transparency-everywhere.com//";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="http://analytics.transparency-everywhere.com/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Code -->
<?php
			
include('actions/openFileFromLink.php');
?>
</body>
</html>