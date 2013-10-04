<?php
session_start();
include_once("inc/functions.php");
include_once("inc/config.php");
?>

		<script type="text/javascript" src="inc/js/guest.js"></script>
        <link rel="stylesheet" type="text/css" href="inc/css/guest.css" media="all" />
        <div id="topThing">
            <span>&copy; 2013 <a href="http://transparency-everywhere.com" target="blank" style="color: #54545B;">Transparency Everywhere</a></span>
            
            <span style="">&nbsp;&nbsp;<a href="#" style="color: #54545B;" onclick="showContent('1', 'Site Notice');">Site Notice</a></span>
            <span style=""></span>
        </div>
        <div id="reload"></div>
        <div id="bodywrap">
        	  <?
    include("register.php");
    ?>
        <div id="guestInfoBox">
        	<a href="#" onclick="$('.guestInfoBox').close();">x</a>
        	<h2>universeOS?</h2>
        	<h3>Tell me more!</h3>
        	<p>The universeOS is bla lorem ipsum bla<br>something somthing intersting<br>might be in this useless texting.</p>
        </div>
        <? include("modules/reader/index.php") ?>
        
        <div id="invisiblefilesystem"><? include("modules/filesystem/filesystem.php") ?></div></div>
        <div id="alerter" class="container"></div><div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>
        <div id="suggest">
        </div>
        
        
        
    <div id="dockplayer" style="display: none">
	    <?
	    include("modules/player/dockplayer.php");
	    ?>
    </div>
        
        
    <div class="box-shadow border-top-right-radius" id="startbox" style="display: none;">
        <div id="startMainWindow">
            <div id="startMainHeader" class="border-top-right-radius">
                <span style="font-size:19pt;font-weight: 1; font-weight: 100; margin-left:5px;">Please Log In</span>
            </div>
        <hgroup>
            <form action="login.php" method="post" target="submitter" id="loginForm">
            <table width="100%" valign="center">
                <tr height="10">
                    <td></td>
                </tr>
                <tr valign="top">
                    <td><input type="text" name="username" placeholder="username" id="loginUsername" class="margin bigInput" style="width: 170px;"></td>
                    <td><input type="password" name="password" placeholder="password" id="loginPassword" class="margin bigInput" style="width: 170px;"></td>
                </tr>
                <tr>
                    <td style="color: #FFFFFF" align="center"><?=$loginerror;?></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Log In" name="submit" class="btn btn-primary margin"></td>
                </tr>
            </table>
            </form>
        </hgroup>
        </div>
    </div>
        
<div id="dockMenu" class="fancy" style="display: none">
	<header>universeOS&nbsp;</header>
    <ul class="appList">

        <li class="" onclick="toggleApplication('filesystem')" onmouseup="closeDockMenu()"><img src="./gfx/filesystem.png" border="0" height="16">&nbsp;&nbsp;Filesystem</li>


        <li class="" onclick="javascript: toggleApplication('reader')" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">&nbsp;&nbsp;Reader</li>
    </ul>
    <div>
    </div>
</div>
    <div id="dock">
        <table>
            <tr valign="top">
                <td><div id="personalButton" class="module" style="margin-top: 4px;">&nbsp;&nbsp;Log In</div></td>
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
            $("#dockplayer").slideToggle("slow");
        });
        
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
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://analytics.universeos.org//";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();

</script>
<noscript><p><img src="http://analytics.universeos.org/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Code -->
    <?
    include("openFileFromLink.php");
    ?>
    </body>
    </html>