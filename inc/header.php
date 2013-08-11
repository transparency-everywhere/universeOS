<!DOCTYPE html>
<html lang="en" dir="ltr" class="client-nojs">
        
        
        <!--meta information-->
        <meta name="description" content='Discover the social webOS. Connect with your friends, read your favourite book or RSS-Feed, watch your favourite movie, listen your favourite song and be creative...'>
        <META Name="keywords" content='universe, universeos, webdesktop, web desktop, social webdesktop, webos, youtube, youtube playlist, documentsrss,  free speech, human rights, privacy, community, social'>
        <meta name="title" content="universeOS">
        <meta name="Robots" content="index">
        <meta name="author" content="Transparency Everywhere">
        <meta name="classification" content=''>
        <meta name="reply-to" content=info@transparency-everywhere.com>
        <meta name="Identifier-URL" content="universeOS.org">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    

        
        <!--facebook open graph-->
        <meta property="og:image" content="http://universeos.org/gfx/logo.png"/>
        <meta property="og:site_name" content="universeOS"/>
        
        <!--favicon-->
        <link rel="shortcut icon" href="http://universeOS.org/gfx/favicon.ico" />
        
        <link href="inc/bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" type="text/css" href="inc/style.css">
        
        
		<script type="text/javascript" src="inc/plugins.js"></script>
        <script type="text/javascript" src="inc/functions.js"></script>
        
        <link href="inc/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        
        <script type="inc/jquery.jplayer.min.js"></script>
        <script src="inc/bootstrap/js/bootstrap.min.js"></script>
        
                <script type="text/javascript">


                //reload
                setInterval(function()
                {
                    $("#reload").load("reload.php");
                    //reloadFeed("friends");
                }, 3000);


                //loads clock into the dock, yeah.
                clock();
                
                //replace all links with <a href=link>link</a>
                $('body').html($('body').html().replace("/(b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|])/ig","<a href='$1'>$1</a>"));
                
                


        </script>
        <script src="inc/plugins/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="inc/plugins/ckeditor/jquery.js"></script>
        <?
        
        //define bg-image
        if(!empty($userdata[backgroundImg])){ 
            ?>
        <style type="text/css">
            body{
                background-image: url(<?=$userdata[backgroundImg];?>);
                background-attachment: no-repeat;
            }
        </style>
        <? }
        
        //look for startmessages
        if(!empty($userdata[startLink])){
        $startLink = ", popper('$userdata[startLink]');";
        }?>
        <title>universeOS</title>
    </head>