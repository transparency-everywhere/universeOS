<?php
      session_start();
  include_once("../../inc/config.php");
  include_once("../../inc/functions.php");
                    if(isset($_POST[AccSetSubmit])) {
                        $birthdate = gmmktime("0", "0", "0", "$_POST[birth_month]", "$_POST[birth_day]", "$_POST[birth_year]");
                        mysql_query("UPDATE user SET realname='$_POST[AccSetRealname]', email='$_POST[AccSetMail]', place='$_POST[place]', home='$_POST[home]', birthdate='$birthdate', school1='$_POST[school1]', university1='$_POST[university1]', employer='$_POST[work]' WHERE userid='$_SESSION[userid]'");
                        jsAlert("Your changes have been saved");
                        }
                    $AccSetSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
                    $AccSetData = mysql_fetch_array($AccSetSql);
                    if($AccSetData[birthdate]){
                    $birth_day = date("d", $AccSetData[birthdate]);
                    $birth_month = date("m", $AccSetData[birthdate]);
                    $birth_year = date("Y", $AccSetData[birthdate]);
                    }
                 ?>
            <form action="modules/settings/index.php" method="post" target="submitter">
                <div class="controls" style="max-width: 450px;">
                    <div class="controls controlls-row">
                        <h3 class="span5">Edit your profile</h3>
                    </div>
                    
                    <div class="controls controlls-row">
                        <span class="span2">Picture</span>
                        <div class="span3">
                            <?=showUserPicture($_SESSION[userid], 100);?><br style="clear: both;">
                            <a href="javascript: loader('settingsFrame', 'modules/settings/picture.php');" class="btn" style="margin-top: 10px; margin-bottom: 15px;">edit</a>
                        </div>
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Name</span>
                        <input type="text" name="AccSetRealname" class="span3" value="<?=$AccSetData[realname];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">City</span>
                        <input type="text" name="place" class="span3" value="<?=$AccSetData[place];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Hometown</span>
                        <input type="text" name="home" class="span3" value="<?=$AccSetData[home];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Birthdate</span>
                        <input type="text" name="birth_day" class="span1" value="<?=$birth_day;?>">
                        <input type="text" name="birth_month" class="span1" value="<?=$birth_month;?>">
                        <input type="text" name="birth_year" class="span1" value="<?=$birth_year;?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">School</span>
                        <input type="text" name="school1" class="span3" value="<?=$AccSetData[school1];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">University</span>
                        <input type="text" name="university1" class="span3" value="<?=$AccSetData[university1];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span2">Work</span>
                        <input type="text" name="work" class="span3" value="<?=$AccSetData[employer];?>">
                    </div>
                    <div class="controls controlls-row">
                        <span class="span5">
                            <input type="submit" name="AccSetSubmit" value="Save" class="btn btn-info pull-right" style="margin: 0 30px;">
                            <a  class="btn pull-right" onclick="$('#invisibleSettings').remove();">cancel</a>
                        </span>
                    </div>
                </div>
            </form>