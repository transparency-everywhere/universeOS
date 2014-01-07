<div id="invisiblechat">
<div class="fenster" id="chat" style="display: none;">
    <header class="titel">
        <p>Chat</p>
        <p class="windowMenu">
        	<a href="javascript:hideApplication('chat');" style="color: #FFF;"><img src="./gfx/icons/close.png" width="16"></a>
        	<a href="#" onclick="moduleFullscreen('chat');" class="fullScreenIcon"><img src="./gfx/icons/fullScreen.png" width="16"></a>
        </p>
    </header>
    <div class="inhalt" style="overflow: hidden;">
        <div id="chat_tabView1">
            <div class="dhtmlgoodies_aTab">
                <div style="position: absolute; top: 0; right: 0px; bottom: 0px; left: 0px; overflow: auto;">
                   <ul id="chatWelcomeList">
<?
            $userArray[0] = 0;
            $i = "0";
            $mailSQL = mysql_query("SELECT * FROM messages WHERE receiver='$_SESSION[userid]' OR sender='$_SESSION[userid]' ORDER BY timestamp DESC");
            while($mailData = mysql_fetch_array($mailSQL)) {
                if(!in_array($mailData['sender'],$userArray) && $mailData['sender'] !== getUser()){
                $userArray[$i] = "$mailData[sender]";
                $mailUserSql = mysql_query("SELECT * FROM user WHERE userid='$mailData[sender]'");
                $mailUserData = mysql_fetch_array($mailUserSql);
                        ?>
                        <li onclick="openChatDialoge('<?=$mailUserData['username'];?>');">
                        	<div>
	                        	<div class="content">
	                        		<table width="100%">
	                        			<tr>
	                        				<td width="40" valign="bottom"><?=showUserPicture($mailUserData['userid'], "30",1);?></td>
	                        				<td width="150" valign="bottom" style="font-size: 23px;"><?=$mailUserData['username'];?></td>
	                        				<td valign="bottom" align="right"><a href="#" style="margin-top:5px;"><?=universeTime($mailData['timestamp']);?></a></td>
	                        			</tr>
	                        		</table>
	                        	</div>
                        	</div>
                        </li>
            <?
            $i++;
            }
            }
            if($i < "1"){
                echo"You dont have any conversations so far";
            }
            ?>
                </ul>
                </div>
            </div>
</div>
</div>

    </div>
<script type="text/javascript">
initTabs('chat_tabView1',Array('Chat'<?=$initWindows;?>),0,"","",Array(false,true,true,true<?=$initValue;?>));
</script>
         </div>
