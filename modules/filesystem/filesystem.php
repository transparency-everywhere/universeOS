<?php
    if(!isset($_SESSION)){ 
        session_start(); 
    }
    include_once('../../'."inc/config.php");
    include_once(universeBasePath.'/'."inc/functions.php");

    $_GET['reload'] = '';

    if(!isset($_GET['folder'])) {
        $folder = "1";
    }
    ?> 
    <div id="fileBrowserFrame">
    </div>