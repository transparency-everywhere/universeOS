<?php
session_start();
include("../../inc/config.php");
include("../../inc/functions.php");


echo openFile($_GET[fileId], $_GET[linkId], $_GET[type],  $_GET[title], $_GET[typeInfo], $_GET[extraInfo1],  $_GET[extraInfo2]);
?>