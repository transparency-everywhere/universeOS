<?php
session_start();
require_once("../../inc/config.php");
require_once("../../inc/functions.php");
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?
showComments($_GET[type], $_GET[itemid]);
?>
