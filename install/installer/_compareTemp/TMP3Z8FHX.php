<?php error_reporting(0) ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

<title>UniverseOS Installer Version 1.0</title>

<link rel="stylesheet" href="index.css" type="text/css" media="screen" />
</head>

<!-- BODY -->
<body>

<!-- navigation -->
<nav><span class="imgcenter"><img src="Unbenannt-2.png" width="303" height="117"  alt=""/></span></nav>

<!-- contentbox -->
<section id="content">
  
  <?php 

  $page = $_GET['page']; 

    switch($page) {
    case 'check':
        include('check.php');
        break;
    case 'database':
        include('database.php');
        break;
    case 'success':
        include('success.php');
        break;
    default:
        include('start.php');
    } 
  ?>
  
  <footer>
<p>&nbsp; </p>
<h2 style="font-size: 10px">Copyright whatever</h2>
</footer>

</section>



</body>
</html>
