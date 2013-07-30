<?
session_start();
include_once("../inc/config.php");
include_once("../inc/functions.php");
echo"<html>";
echo"<head>";
echo"<meta name=\"description\" content='Discover the social webOS. Connect with your friends, read your favourite book or RSS-Feed, watch your favourite movie, listen your favourite song and be creative...'>";


//define type and itemId
if(!empty($_GET[folder])){
	$type = "Folder";
	$itemId = $_GET[folder];
	
	$query = mysql_query("SELECT name, folder FROM folders WHERE id='".mysql_real_escape_string($itemId)."'");
	$data = mysql_fetch_array($query);
	$title = "$data[name]";
	
}else if(!empty($_GET[element])){
	$type = "Element";
	$itemId = $_GET[element];
}else if(!empty($_GET[file])){
	$type = "File";
	$itemId = $_GET[file];
}

	//page title
	echo"<title>";
	echo"universeOS - $type $title";
	echo"</title>\n";
	
	
	
	//facebook open graph picture
	if(empty($picture)){
            echo"<meta property=\"og:image\" content=\"http://universeos.org/gfx/logo.png\"/>\n";
	}else{
            echo"<meta property=\"og:image\" content=\"http://universeos.org/$picture\"/>\n";
	}
	
	//meta information
	echo"<meta name=\"title\" content=\"universeOS - $type $title\">";
	echo"<meta name=\"keywords\" content=\"universeOS, $type, $title\">";
        echo"<meta name=\"Robots\" content=\"index,follow\">";
        echo"<style>";
        ?>

        body{
        background:#ffffff;
        }

		a, a:link, a:hover {
		color: #0088cc;
		text-decoration: none;
		}

        table {
          max-width: 100%;
          background-color: transparent;
          border-collapse: collapse;
          border-spacing: 0;
        }

        .table {
          width: 100%;
          margin-bottom: 20px;
        }

        .table th,
        .table td {
          padding: 8px;
          line-height: 20px;
          text-align: left;
          vertical-align: top;
          border-top: 1px solid #dddddd;
        }

        .table th {
          font-weight: bold;
        }

        .table thead th {
          vertical-align: bottom;
        }
        
        body{
            margin:0px;
        }
        
        .fileTable{
            width:100%;
        }
        
        .strippedRow:first-child {background:rgb(202, 216, 230)}
        .strippedRow:nth-child(2n+3) {background:rgb(202, 216, 230)}
        
        .watermark{
        	position: fixed;
        	bottom:0px;
        	right:0px;
        	height:30px;
        	width:100px;
        	background: rgba(0,0,0,0.8);
        	color: #FFFFFF;
        }
        
        <?
        echo"</style>";
        
echo"</head>";
echo"<body>";
echo"<table>";

switch($type){
	case "Folder":
                        showFileBrowser($itemId, '', '', false);
		
		break;
	case "Element":
                        showFileList($itemId, '', true);
		break;
	case "File":
                        echo openFile($_GET[file], $_GET[link]);
		break;
}
echo"</table>";
echo'<div class="watermark">';
echo'<a href="http://universeos.org" target="_parent">universeOS</a>';
echo"</div>";
echo"<script>";
// echo"window.location.href = \"http://universeos.org/index.php?openFromLink=openFromLink=$type\";";
echo"</script>";
echo"</body>";
echo"</html>";
?>