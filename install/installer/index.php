<?php error_reporting(0) ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

<title>universeOS Installer</title>

<link rel="stylesheet" href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" type="text/css" media="screen" />
<link rel="stylesheet" href="index.css" type="text/css" media="screen" />
</head>

<!-- BODY -->
<body>

<!-- navigation -->
<nav><span class="imgcenter"><img src="Unbenannt-2.png" width="303" height="117"  alt=""/></span></nav>

<!-- contentbox -->
<section id="content">
  
  <?php 

  
  class install{

	function checkRequirements($server,$user,$password,$db){
		if(!mysql_connect("$server","$user","$password")){
			$dbServer = false;
		}else{
			$dbServer = true;
		}
		
		if(!mysql_select_db("$db")){
			$db = false;
		}else{
			$db = true;
		}
	}
	
	function createDbConfigFile($server,$user,$password,$db){
	}
	
	function createUniverseDB(){
		
	}
	
	function proofRegistration(){
		
	}
	
	
	function run(){
		
		if($this->checkRequirements()){
			
			
		}
		
	}
	}
	
	if(isset($_POST['submitAndInstall'])){
		
		$install = new install();
		$install->run();
		
		
		
	
		
		
	}
  
  
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
<h2 style="font-size: 10px">Copyright whatever</h2>
</footer>

</section>
</body>
</html>