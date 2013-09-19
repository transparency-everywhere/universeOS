<?

?>
<div id="dockplayer" style="display: none">
    <?
    include("modules/player/dockplayer.php");
    ?>
</div>
<div id="dockMenu" class="fancy" style="display: none">
	<header>universeOS&nbsp;</header>
    <ul class="appList">
        <li onclick="toggleApplication('feed')" onmouseup="closeDockMenu()"><img src="./gfx/feed.png" border="0" height="16">&nbsp;&nbsp;Feed</li>

        <li class="" onclick="toggleApplication('filesystem')" onmouseup="closeDockMenu()"><img src="./gfx/filesystem.png" border="0" height="16">&nbsp;&nbsp;Filesystem</li>


        <li class="" onclick="javascript: toggleApplication('reader')" onmouseup="closeDockMenu()"><img src="./gfx/viewer.png" border="0" height="16">&nbsp;&nbsp;Reader</li>
        <li class="" onclick="javascript: toggleApplication('buddylist')" onmouseup="closeDockMenu()"><img src="./gfx/buddylist.png" border="0" height="16">&nbsp;&nbsp;Buddylist</li>
        <li class="" onclick="javascript: toggleApplication('chat')" onmouseup="closeDockMenu()"><img src="./gfx/buddylist.png" border="0" height="16">&nbsp;&nbsp;Chat</li>
        <li class="" onclick="javascript: showModuleSettings();" onmouseup="closeDockMenu()"><img src="./gfx/settings.png" border="0" height="16">&nbsp;&nbsp;Settings</li>
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
<div id="dock">
    <table>
        <tr>
            <td><div class="module" id="startButton"><?=showUserPicture($userdata['userid'], 15);?><i class="icon-retweet icon-white" style="margin-left:15px;"><span class="iconAlert" id="appAlerts"></span></i><i class="icon-user icon-white"><span class="iconAlert" id="openFriendRequests"></span></i><i class="icon-envelope icon-white"><span class="iconAlert" id="newMessages"></span></i></div><td>

            <td><div id="modulePlayer" class="module">&nbsp;&nbsp;Player</div>   </td>
            <td><a href="doit.php?action=logout" target="submitter" class="module" style="tex-decoration: none; color: #797979; min-width:10px;" title="logout">&nbsp;<i class="icon-white icon-off"></i>&nbsp;</a></td>
            <td align="right" id="clockDiv" style="color: #FFFFFF; float: right"></td>
            <td align="right"><input type="text" name="searchField" id="searchField" class="border-radius" placeholder="search"></td>
        </tr>
    </table>
</div>
<script>
    $("#modulePlayer").click(function () {
    $("#dockplayer").toggle("slow");
    });
    $("#startButton").click(function () {
    $("#dockMenu").slideToggle("slow");
    }); 
    $("#personalButton").click(function () {
    $("#personalFeed").toggle("slow");
    });
</script>