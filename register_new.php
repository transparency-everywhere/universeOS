<?php
include("inc/config.php");
include("inc/functions.php");?>
<html>
    <?
if(isset($_POST['submit'])) {
$userCheckSql = mysql_query("SELECT username FROM user WHERE username='$_POST[username]'");
$userCheckData = mysql_fetch_array($userCheckSql);

    
  
  if(!empty($userCheckData[username])) {
      $error[username] = "Username is allready in Use";
  }  
  
  if(empty($_POST["username"])) {
      $error[username] = "Please type username";
  }
  
  if(empty($_POST["policy"])) {
      $error[policy] = "You have to accept our no porn and human rights policy!";
  }


  if(empty($_POST["password"])) {
      $error[password] = "Please type password";
  }
  
 if(empty($_POST["passwordcheck"])) {
      $error[password] = true;
  }
  
  if($_POST["password"] !== $_POST["passwordcheck"]) {
      $error[password] = "Passwords dont match";
  }
  if(empty($error)){
      
  }
  
}
?>
<style type="text/css">

#checkUsernameStatus{
    margin-right: -100px;
    font-size: 10pt;
    
}

#registerTable{
    color: #424242;
    width: 100%;
}

#registerTable input[type=text],#registerTable input[type=password]{
    height: 30px; 
   margin-bottom: -2px;
}

#registerBox{
    margin-top:1em;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    background: #FFFFFF;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=85)";
    filter: alpha(opacity=85);
    -moz-opacity: 0.85;
    -khtml-opacity: 0.85;
    opacity: 0.85;
    border: 1px solid #c9c9c9;
}
</style>
    <div style="position: absolute; top: 21px; bottom: 0px; width: 100%; overflow: auto;" class="gray-gradient">
       
        <div class="grayBar" style="top: 0px; left:0px; right: 0px; height: 20px; overflow: none;"><center>Create an Account</center></div>
       
       <form action="" method="post" onsubmit="checkReg(); return false;" id="regForm">
       <div style="margin-left: 10%; width: 80%; font-size: 13pt;" id="registerBox">
           <table align="center" id="registerTable" style=" font-weight: 200;">
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr
               <tr>
                   <td colspan="2"></td>
                   <td style="font-size:19pt;">Sign Up</td>
               </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                <td width="35%" align="right">Username:&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="text" name="username" class="border-radius checkReg" id="username" onkeyup="checkUsername('username');"><?=$error[username];?><span id="checkUsernameStatus"></span></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                <td align="right">Password:&nbsp;</td>    
                <td>&nbsp;</td>
                <td><input type="password" name="password" size="10" class="border-radius checkReg" id="password1"><?=$error[password];?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                <td align="right">Submit Password:&nbsp;</td>
                <td>&nbsp;</td>
                <td><input type="password" name="passwordcheck" size="10" class="border-radius checkReg" id="password2"><br><?=$error[passwordmatch];?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td align="right" valign="center">Captcha:&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><span><img src="inc/plugins/captcha/image.php" class="captcha" style="border: 1px solid #c9c9c9; float: left;"></span>&nbsp;=&nbsp;<input type="text" name="captcha" class="border-radius checkReg" id="captcha" style="width:34px;"></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><a href="#" onclick="$('.captcha').attr('src', 'inc/plugins/captcha/image.php?dummy='+ new Date().getTime()); return false">reload captcha</a></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td align="right" valign="top"><input type="checkbox" class="border-radius checkRegBox" name="policy" value="wtf" style="margin-top: -2px;">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="font-size:11pt;" valign="top">I accept the "<a href="http://wiki.universeos.org/index.php?title=Policy" target="blank" style="color: #424242; font-size: 10pt;">no porn and human rights</a>"-policy</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;</td>
                    <td colspan="1">
                        <input type="submit" name="submitta" value="register" class="btn btn-success">
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
            </table>
        </div>
    </form>
    </div>
</html>