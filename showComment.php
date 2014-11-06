<?php
session_start();
include("inc/config.php");
include("inc/functions.php");
if(proofLogin()){
if(isset($_POST[comment])) {
    echo'<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>';
    $commentClass = new comments();
    $commentClass->addComment($_POST['type'], $_POST['itemid'], $_POST['user'], $_POST['comment']);
    
    
    ?>
    <script>
    $('#<?=$_POST[type];?>Comment_<?=$_POST[itemid];?>', parent.document).html( function(){
        $(this).html('');
        $(this).load('doit.php?action=showSingleComment&type=<?=$_POST[type];?>&itemid=<?=$_POST[itemid];?>');
    });
    </script>
    <?
    
    
}
}else{
    jsAlert("Please login or sign up to write a comment.");
}
if($_GET[type] == "feed"){
$commentid = $_GET[feedid];
$commentClass = new comments();
$commentClass->showFeedComments($commentid);
    
}
else {
$commentid = $_GET[id];
$classComments = new comments();
$classComments->showComments(comment, $commentid);
}
?>