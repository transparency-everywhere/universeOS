<?php
session_start();
include_once("inc/functions.php");
include_once("inc/config.php");
?>
		<script type="text/javascript" src="inc/js/guest.js"></script>
        <link rel="stylesheet" type="text/css" href="inc/css/guest.css" media="all" />
        <div id="topThing">
            <span>2013 <a href="http://transparency-everywhere.com" target="blank" style="color: #54545B;">Transparency Everywhere</a></span>
            
            <span id="socialBar">
            	<a href="http://wiki.universeOS.org" target="_blank" title="Get your information out of the universe Wiki."><img src="gfx/startPage/wikipedia.png"></a>
            	<a href="http://twitter.com/universeOS" target="_blank" title="Have a look at our Twitter account."><img src="gfx/startPage/twitter.png"></a>
            	<a href="http://facebook.com/universeOS" target="_blank" title="Like us on Facebook."><img src="gfx/startPage/facebook.png"></a>
            	<a href="#" onclick="altert('Will follow soon');" target="_blank" title="Have a look at our Source"><img src="gfx/startPage/bitbucket.png"></a>
            </span>
            <span style="">
            	<a href="#" style="color: #54545B;" onclick="showContent('1', 'Site Notice');">Site Notice</a>
            </span>
        </div>
        <div id="reload"></div>
        <div id="bodywrap">
        	  <?
    include("register.php");
    ?>
    <style>
    	#guestInfoBox{
    		background: #383838;
			border: 1px solid #222222;
			padding: 5px;
			position: absolute;
			top:3px;
			left:3px;
			width:250px;
    	}
    	
		#guestInfoBox h1, #guestInfoBox h2, #guestInfoBox h3, #guestInfoBox h4{
			margin: 0;
			margin-top: 5px;
			line-height: 30px;
		}
		
    	#guestInfoBox a {
			color: #FFFFFF;
			position: absolute;
			right: 8px;
			top: 0px;
		}
    	
    	#guestInfoBox ul{
    		margin: 10px 0px 10px 10px;
    	}
    	
    	#guestInfoBox ul li{
    		line-height: 19px;
    		line-height: 15px;
			margin: 10px 0 15px 22px;
    	}
    	
    	#guestInfoBox ul li img{
    		height: 12px;
    		margin-left: -19px;
    		position: absolute;
    		margin-top: 2px;
    		
    	}
    	#betaBox{
    		background: #383838;
			border: 1px solid #222222;
			padding: 5px;
			position: absolute;
			top:3px;
			left:272px;
			width:250px;
    	}
    	
		#betaBox h1, #betaBox h2, #betaBox h3, #betaBox h4{
			margin: 0;
			margin-top: 5px;
			line-height: 30px;
		}
		
    	#betaBox a {
			color: #FFFFFF;
			position: absolute;
			right: 8px;
			top: 0px;
		}
    </style>
        <div id="guestInfoBox">
        	<a href="#" onclick="$('#guestInfoBox').hide();">x</a>
        	<h2>universeOS</h2>
        	<h3>"The world shares a Desktop"</h3>
        	<p>Be part of the first social webOS which shares the following amazing features:</p>
        	<ul>
        		<li><img src="./gfx/bulletPoint.png">organize, open and share your files</li>
        		<li><img src="./gfx/bulletPoint.png">use public files and make your files accesible to the public</li>
        		<li><img src="./gfx/bulletPoint.png">present yourself through your profile and form groups with your buddies</li>
        		<li><img src="./gfx/bulletPoint.png">encrypt your data and communication to be sure that it doesn't fall into the wrong hands</li>
        	</ul>
        	<p>But finally the fact that we don't make profit with your data is most important<br>to us.</p>
        	
        </div>
        <div id="betaBox">
        	<a href="#" onclick="$('#betaBox').hide();">x</a>
        	<h2>Beta Test Run</h2>
        	<p>Currently we are still in the beta phase of our project. Therefore we cannot guarantee 100% of security but we are well on the way to it. So you shouldn’t handle highly sensitive data in the filesystem and chat and excuse if there are little issues in the workflow. We appreciate criticism and error reports because it helps us to improve the universeOS. So have fun with the universeOS.</p>
        </div>
        <? include("modules/reader/index.php") ?>
        
        <div id="invisiblefilesystem"><? include("modules/filesystem/filesystem.php") ?></div></div>
        <div id="alerter" class="container"></div><div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>
        <div id="suggest">
        </div>
        
        
        
    <div id="dockplayer" style="display: none">
	    <?
	    //include("modules/player/dockplayer.php");
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
                <!-- <td><div id="modulePlayer" class="module">&nbsp;&nbsp;Player</div></td> -->
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