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
<nav>
<img><img src="images/universe.png"  alt="" width="150" height="150" id="Universe"/></img>
<h3><strong style="text-align: center; color: #000;">UniverseOS Version 1.0</strong></h3>
</nav>

<!-- contentbox -->
<section id="content">

<?php 
$page = trim(strip_tags($_GET['page']));

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
