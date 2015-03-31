<?
include("../../inc/config.php");
include("../../inc/functions.php");

$user = "$_GET[user]";
$profilesql = mysql_query("SELECT * FROM user WHERE userid='$user'");
$profiledata = mysql_fetch_array($profilesql);
$birth_day = date("dS", $profiledata['birthdate']);
$birth_month = date("F", $profiledata['birthdate']);
$birth_year = date("Y", $profiledata['birthdate']);

if(isset($_POST[submitComment])) {
    $commentClass = new comments();
    $commentClass->addComment(profile, $user, getUser(), $_POST['comment']);
}
?>

      <link rel="stylesheet" type="text/css" href="modules/profile/profile.css">  

    <div id="profileWrap">
        <div id="loader"></div>
    <div>&nbsp;</div>
    <div class="border-radius shadow profileElement" id="profileInfo">
        <div style=" width:100px; float: left;"><?=showUserPicture($user, 100);?></div>
        <div style="float: left;"><a href="#" onclick="buddylist.addBuddy(<?=$profiledata['userid'];?>);" target="submitter">Add to Buddylist</a></div>
        <div><?=$profiledata['username'];?><br><?=$profiledata['realname'];?></div>
        <div>Born on the <?=$birth_day;?> of <?=$birth_month;?> <?=$birth_year;?>. From <?=$profiledata['home'];?>, lives in <?=$profiledata['place'];?>. Went to <?=$profiledata['school1'];?>.<br><br></div>
    </div>
    <div class="border-radius shadow profileElement" id="profileFav">
        <div style="width: 200px; height:300px" class="border-box">
            bla
        </div>
    </div>
    <?
    $classComments = new comments();
    $classComments->showComments(profile, $user);
    ?>
    </div>
      