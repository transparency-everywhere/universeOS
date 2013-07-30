<?
session_start();
if(isset($_GET[reload])){
    include("../../inc/config.php");
    include_once("../../inc/functions.php");
}else if(empty($_GET[reload])){
    include("inc/config.php");
    include_once("inc/functions.php");
}
?>
        <?
        if(empty($_GET[action])){ ?>
<div id="invisiblemail">
    <script>
            //Draggable Window
            $(function() {
                    $(".fenster").draggable({ 
                            cancel: '.inhalt',
                            containment: 'body',
                            scroll: false,
                            stack: { group: '.fenster', min: 1 }
                    });



            });
            //Resizeable window
            $(function() {
                    $(".fenster").resizable({
                            maxHeight: $(window).height(),
                            maxWidth: $(window).width()
                    });
            });
        </script>
<div class="fenster" id="settings">
    <header class="titel">
        <p>Mail</p><p class="windowMenu"><a href="javascript:closeModuleMail();"><img src="./gfx/icons/close.png" width="16"></a>
    </header>
    <div class="inhalt autoflow">
            <div class="leftNav">
                <ul>
                    <li><a href="modules/mail/index.php?action=inbox&reload=1" target="MailframeRight">Inbox</a></li>
                    <li><a href="modules/mail/index.php?action=outbox&reload=1" target="MailframeRight">Outbox</a></li>
                    <li>Saved</li>
                    <li><a href="modules/mail/index.php?action=new&reload=1" target="MailframeRight">New</a></li>
                </ul>
            </div>
            <iframe src="modules/mail/index.php?action=inbox&reload=1" class="frameRight" name="MailframeRight"></iframe>
        </div>
    </div>
</div>
<?}if($_GET[action] == "inbox"){
    if(isset($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="../../inc/style.css">
<?
}else if(empty($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="inc/style.css">
<?
}?>
 <body style="background: #FFFFFF;">
            <div id="mailList">
            <table cellspacing="0" width="100%">
            <?
            $userArray[0] = 0;
            $i = "1";
            $mailSQL = mysql_query("SELECT * FROM messages WHERE receiver='$_SESSION[userid]' ORDER BY timestamp ASC");
            while($mailData = mysql_fetch_array($mailSQL)) {
                if(!in_array("$mailData[sender]",$userArray)){
                $userArray[$i] = "$mailData[sender]";
                $mailUserSql = mysql_query("SELECT * FROM user WHERE userid='$mailData[sender]'");
                $mailUserData = mysql_fetch_array($mailUserSql);
                        if($i%2 == 0) {
                            $bgcolor="white";
                        } else {    
                            $bgcolor="blue";
                        }
                        ?>
                    <tr class="<?=$bgcolor;?>bg" height="40" valign="center" width="100%" onclick="location.href='index.php?action=showMessage&id=<?=$mailUserData[userid];?>&reload=1'">
                        <td align="center"><?=showUserPicture($mailUserData[userid], "30",1);?></td>
                        <td><i><?=$mailUserData[username];?></i></td>
                        <td><?=universeTime($mailData[timestamp]);?></td>
                        <td><a href="?action=show&reload=1&messageId=<?=$mailUserData[userid];?>"><?=substr(htmlentities($mailData[text]), 0, 25);?></a></td>
                    </tr>
            <?
            $i++;
            }
            }
            ?>
            </table>        
            </div>
 </body>
<? }else if($_GET[action] == "outbox"){
    if(isset($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="../../inc/style.css">
<?
}else if(empty($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="inc/style.css">
<?
}?>

 <body style="background: #FFFFFF;">
            <div id="mailList">
                
            <table cellspacing="0" width="100%">
            <?
            $userArray[0] = 0;
            $i = "1";
            $mailSQL = mysql_query("SELECT * FROM messages WHERE sender='$_SESSION[userid]' ORDER BY timestamp ASC");
            while($mailData = mysql_fetch_array($mailSQL)) {
                if(!in_array("$mailData[receiver]",$userArray)){
                $userArray[$i] = "$mailData[receiver]";
                $mailUserSql = mysql_query("SELECT * FROM user WHERE userid='$mailData[receiver]'");
                $mailUserData = mysql_fetch_array($mailUserSql);
                        if($i%2 == 0) {
                            $bgcolor="white";

                        } else {    
                            $bgcolor="blue";
                        }


            ?>
                    <tr class="<?=$bgcolor;?>bg" height="40" valign="center" width="100%">
                        <td align="center"><?=showUserPicture($mailUserData[userid], "30",1);?></td>
                        <td><i><?=$mailUserData[username];?></i></td>
                        <td><?=universeTime($mailData[timestamp]);?></td>
                        <td><a href="?action=show&reload=1&messageId=<?=$mailUserData[userid];?>"><?=substr(htmlentities($mailData[text]), 0, 25);?></a></td>
                    </tr>
            <?
            }
            $i++;
            }
            ?>
            </table>        
            </div>
 </body>
 <?
} else if($_GET[action] == "new"){
        if(isset($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="../../inc/style.css">
<?
}else if(empty($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="inc/style.css">
<?
}?>
 <bodystyle="background: #FFFFFF;"">
 <div>
        <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="../../inc/functions.js"></script>
        <div>
            <p>To: <input typ="text" name="receiver"</p>
        </div>
        
     <textarea name="feed" style="width:90%; height: 20px; overflow-y:hidden; margin-top:5px; margin-left: 5%;" onkeyup="autoGrowField(this ,300)">add</textarea>   
 </div>
 </body>
<? } else if($_GET[action] == "showMessage"){
    if(isset($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="../../inc/style.css">
<?
}else if(empty($_GET[reload])){?>
 <link rel="stylesheet" type="text/css" href="inc/style.css">
<?
}?>

 <body style="background: #FFFFFF;">
        <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="../../inc/functions.js"></script>

             <ol>
<?
$buddy = $_GET[id];
$buddySQL = mysql_query("SELECT * FROM user WHERE username='$buddy'");
$buddyData = mysql_fetch_array($buddySQL);
 $userSQL = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
 $userData = mysql_fetch_array($userSQL);
$chatSQL = mysql_query("SELECT * FROM messages WHERE sender='$_SESSION[userid]' && receiver='$buddy' OR sender='$buddy' && receiver='$_SESSION[userid]' ORDER BY timestamp DESC LIMIT 0,30");
while($chatData = mysql_fetch_array($chatSQL)) {
    $one = "1";
    if($chatData[receiver] == $_SESSION[userid]){
    mysql_query("UPDATE `messages` SET  `read`='1' WHERE  id='$chatData[id]'");
    }
    $sender = $chatData[sender];
    $whileid = $_SESSION[userid];
    if($sender == $whileid){
    $authorid =  $userData[userid];
    } else {
    
    $authorid =  $buddyData[userid];
    $authorName = $buddyData[username];
    $authorImage = $buddyData[userPicture];
    }
    if($chatData[crypt] == "1"){
        $message = universeDecode("1355004;13305852;13304655;13287897;13305852;", "abc");
    } else{
        $message = $chatData[text];
    }
    ?>
            <div class="box-shadow space-top border-radius message"><?=userSignature($authorid, $chatData[timestamp], 1);?><div><?=$message;?></div></div>
            <?
}
?>
            
        </ol>
        <form action="" target="MailframeRight" method="post">
        <textarea name="feed" style="width:90%; height: 20px; overflow-y:hidden; margin-top:5px; margin-left: 5%;" onkeyup="autoGrowField(this ,300)"></textarea>
        <input type="submit" value="send">
        </form>
 </body>
 <?
}
?>


