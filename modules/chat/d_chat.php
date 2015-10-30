
            <div class="dhtmlgoodies_aTab">
                <div style="position: absolute; top: 0; right: 0px; bottom: 0px; left: 0px; overflow: auto;">
                   <ul id="chatWelcomeList">
<?
include('../../inc/config.php');
include('../../inc/functions.php');
            $userArray[0] = 0;
            $i = "0";
            $mailSQL = mysql_query("SELECT * FROM messages WHERE receiver='$_SESSION[userid]' OR sender='$_SESSION[userid]' ORDER BY timestamp DESC");
            while($mailData = mysql_fetch_array($mailSQL)) {
                if(!in_array($mailData['sender'],$userArray) && $mailData['sender'] !== getUser()){
                $userArray[$i] = "$mailData[sender]";
                $mailUserSql = mysql_query("SELECT * FROM user WHERE userid='$mailData[sender]'");
                $mailUserData = mysql_fetch_array($mailUserSql);
                        ?>
                        <li onclick="im.openDialogue('<?=$mailUserData['username'];?>');">
                        	<div>
	                        	<div class="content">
	                        		<table width="100%">
	                        			<tr>
	                        				<td width="40" valign="bottom"><?=showUserPicture($mailUserData['userid'], "30",1);?></td>
	                        				<td width="150" valign="bottom" style="font-size: 23px;"><?=$mailUserData['username'];?></td>
	                        				<td valign="bottom" align="right">
                                                                    <a href="#" style="margin-top:5px;">
                                                                    <?php
                                                                    $guiClass = new gui();
                                                                    $guiClass->universeTime($mailData['timestamp']);
                                                                    ?>
                                                                    </a>
                                                                </td>
	                        			</tr>
	                        		</table>
	                        	</div>
                        	</div>
                        </li>
            <?
            $i++;
            }
            }
            if($i < "1"){?>
                        
            <center>
                <span class="icon blue-comment" style="height: 90px;width: 90px; margin-top:5px;margin-right: -17px;"></span>
                <h2 style="margin-top:0;">Chat</h2>
                <h3>Click on a user in your buddylist to open a dialogue</h3>
            </center>
                        
            <?php
            }
            ?>
                </ul>
                </div>
            </div>