<!DOCTYPE html>
<html lang="en" dir="ltr" class="client-nojs">
        
        
        <!--meta information-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">


		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
		<!-- <meta property="fb:app_id" content="141861192518680">

		<meta property="twitter:user_id" content="139909832" />
		<meta name="twitter:site" content="@PolicyMic">
		<meta name="twitter:card" content="summary">
		
		<meta name="language" content="en" />
		<meta name="robots" content="index, follow" />
		<meta name="author" content="Transparency Everywhere" />
		<meta name="medium" content="webDesktop" />
		
		<meta property="og:site_name" content="universeOS" />
		<meta property="identifier-URL" content="http://universeos.org" />
		<meta property="article:publisher" content="https://www.facebook.com/policymic" /> -->

        <meta name="description" content='Discover the social webOS. Connect with your friends, read your favourite book or RSS-Feed, watch your favourite movie, listen your favourite song and be creative...'>
        <META Name="keywords" content='universe, universeos, universe os, webdesktop, web desktop, social webdesktop , youtube, youtube playlist, documents, rss, free speech, human rights, privacy, community, social'>
        <meta name="title" content="universeOS">
        <meta name="Robots" content="index">
        <meta name="author" content="Transparency Everywhere">
        <meta name="classification" content=''>
        <meta name="reply-to" content=info@transparency-everywhere.com>
        <meta name="Identifier-URL" content="universeOS.org">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

        
        <!--facebook open graph-->
        <meta property="og:image" content="http://universeos.org/gfx/logo.png"/>
        <meta property="og:site_name" content="universeOS"/>
        
        <link rel="apple-touch-icon" sizes="57x57" href="img/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="img/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="img/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="img/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="img/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="img/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="img/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="img/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
        <link rel="manifest" href="img/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="img/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        
        <link rel="stylesheet" type="text/css" href="inc/css/plugins.css" />
        <link rel="stylesheet" type="text/css" href="inc/css/style.css" media="all" />
        
        
	<script type="text/javascript" src="inc/js/plugins.js"></script>
        <script type="text/javascript" src="inc/js/functions.js"></script>
        
        <!-- <script type="inc/js/jquery.jplayer.min.js"></script> -->
        <script src="inc/js/bootstrap.min.js"></script>
        <script src="inc/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="inc/plugins/ckeditor/jquery.js"></script>
        <?
        
        //define bg-image
        if(!empty($userdata['backgroundImg'])){ 
            ?>
            <style type="text/css">
                body{
                    background-image: url(<?=$userdata['backgroundImg'];?>);
                    background-attachment: no-repeat;
                }
            </style>
            <? }
        
        //look for startmessages
        if(!empty($userdata['startLink'])){
        $startLink = ", popper('$userdata[startLink]');";
        }?>
        <title>universeOS</title>
    </head>