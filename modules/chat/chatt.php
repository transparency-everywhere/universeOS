<?
session_start();
$buddy = $_GET[buddy];
// if(isset($_COOKIE['openChatWindows'])) {
// if(!in_array("$buddy",$_COOKIE[openChatWindows])) {
    // $path = "http://localhost/universe";
    // $countChatWindows = ("$_COOKIE[countChatWindows]" + 1);
    // setcookie("countChatWindows", "$countChatWindows",  time()+3600*24*31, "/"); 
    // setcookie("openChatWindows[$countChatWindows]", "$buddy",  time()+3600*24*31, "/");
// }}
// if(empty($_COOKIE['openChatWindows'])){
    // $path = "http://localhost/universe";
    // setcookie("countChatWindows", "1", time()+3600*24*31, "/");
    // setcookie("openChatWindows[1]", "$buddy", time()+3600*24*31, "/");
// }
require_once ("../../inc/config.php");
require_once ("../../inc/functions.php"); ?>
<div id="">
      <?
      if(!isset($_GET[reload])){
      $top = "20";
      }else{
      }
      include("chatreload.php");
      ?>
</div>
