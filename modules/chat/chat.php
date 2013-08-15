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
                <div class="grayBar">Your last conversations:</div>
                <div style="position: absolute; top: 51px; right: 0px; bottom: 0px; left: 0px; overflow: auto;">
                   <ul id="chatWelcomeList">
<?
            $userArray[0] = 0;
            $i = "0";
            $mailSQL = mysql_query("SELECT * FROM messages WHERE receiver='$_SESSION[userid]' OR sender='$_SESSION[userid]' ORDER BY timestamp DESC");
            while($mailData = mysql_fetch_array($mailSQL)) {
                if(!in_array("$mailData[sender]",$userArray)){
                $userArray[$i] = "$mailData[sender]";
                $mailUserSql = mysql_query("SELECT * FROM user WHERE userid='$mailData[sender]'");
                $mailUserData = mysql_fetch_array($mailUserSql);
                        ?>
                        <li onclick="createNewTab('chat_tabView1','<?=$mailUserData[username];?>','','modules/chat/chatt.php?buddy=<?=$mailUserData[username];?>',true);return false">
                        	<div>
	                        	<header>
	                        		<?=universeTime($mailData[timestamp]);?>
	                        		<div class="pull-right"><?=showUserPicture($mailUserData[userid], "15",1);?></div>
	                        		<span class="pull-right"><?=$mailUserData[username];?>&nbsp;</span>
	                        	</header>
	                        	<div class="content">
	                        		<a href="?action=show&reload=1&messageId=<?=$mailUserData[userid];?>" style="margin-top:5px;"><?=universeText(substr($mailData[text], 0, 255));?></a>
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
