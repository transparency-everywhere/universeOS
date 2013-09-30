<?

?>
<div id="dockplayer" style="display: none">
    <?
    include("modules/player/dockplayer.php");
    ?>
</div>
<!--    <p>Buddies</p>
    <ul class="messageList" id="dockMenuBuddyAlerts">
        
    </ul>-->
</div>
<div id="dock">
    <table>
        <tr>
            <td><a class="module" id="startButton" title="toggle Dashboard" href="#dashBoard"><?=showUserPicture($userdata['userid'], 15);?><i class="icon-retweet icon-white" style="margin-left:15px;"><span class="iconAlert" id="appAlerts"></span></i><i class="icon-user icon-white"><span class="iconAlert" id="openFriendRequests"></span></i><i class="icon-envelope icon-white"><span class="iconAlert" id="newMessages"></span></i></a><td>

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
    $("#dashBoard").slideToggle("slow");
    }); 
    $("#personalButton").click(function () {
    $("#personalFeed").toggle("slow");
    });
</script>