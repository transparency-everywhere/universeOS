<?php
include_once("inc/functions.php");
include_once("inc/config.php");
$db = new db();

$db->query('ALTER TABLE `user` ADD `application_preset` VARCHAR(255) NOT NULL AFTER `profile_info`; UPDATE `user` SET `application_preset`=\'social\'');
?>


    

