<?
session_start();
$buddy = $_GET[buddy];
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
