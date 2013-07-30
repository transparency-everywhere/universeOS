<?PHP
session_start();
include("inc/config.php");
include("inc/functions.php");
if(isset($_POST[submit]) && !empty($_POST[username])) {
     $savemail = mysql_real_escape_string($_POST[username]);
     if(isset($saveid)){
     $sql = mysql_query("SELECT userid, password, hash FROM user WHERE userid='$saveid'");         
     }else if(isset($savemail)){
     $sql = mysql_query("SELECT userid, password, hash FROM user WHERE username='$savemail'");
     }
     $data = mysql_fetch_array($sql);
     $timestamp = time();
     $timestamp = ($timestamp / 2);
     $password = md5($_POST[password]);
     $hash = md5($timestamp);
     $userid = $data[userid];
     if($password == $data[password]) {
         $_SESSION['userid'] = $data[userid];
         $_SESSION['userhash'] = $hash;
         mysql_query("UPDATE user SET hash='$hash' WHERE userid='$_SESSION[userid]'");
         
            
            $feed = "is logged in";
            createFeed("$_SESSION[userid]", $feed, "60", "feed", "p");
         ?>
		<script type="text/javascript">
		parent.window.location.href = "index.php";
		</script>
        <?
        die();
     }
     
     else {
      $loginerror = "wrong";
      jsAlert("$loginerror");
     }
  }else{
	jsAlert("error!");
  }
  ?>