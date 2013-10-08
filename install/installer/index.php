<?php

session_start();
error_reporting(0) ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

<title>universeOS Installer</title>

<link rel="stylesheet" href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" type="text/css" media="screen" />
<link rel="stylesheet" href="index.css" type="text/css" media="screen" />
<script>
	someForm.setAttribute( "autocomplete", "off" ); someFormElm.setAttribute( "autocomplete", "off" );
</script>
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
			$errorMessage[] = "dbServer";
			
		}else{
			$dbServer = true;
		
			if(!mysql_select_db("$db")){
				$db = false;
				$errorMessage[] = "db";
			}else{
				$db = true;
			}
			
		}
		
		
		//check if free space is bigger than or euqal to 10MB
		if(round(disk_free_space(".")/(1024*1024)) >= 10){
			$space = true;
			
			
			//save vars in session
			$_SESSION['server'] = $server;
			$_SESSION['user'] = $user;
			$_SESSION['password'] = $password;
			
			$_SESSION['db'] = $db;
		}else{
			$space = false;
			$errorMessage[] = "space";
		}
		
		if($dbServer AND $db AND $space){
			return true;
		}else{
			return $errorMessage;
		}
		
		
	}
	
	function createDbConfigFile(){
		
		$serverdb = $_SESSION['server'];
		$userdb = $_SESSION['user'];
		$passworddb = $_SESSION['password'];
		$dbname = $_SESSION['db'];
		
		$Datei = "inc/config/test.php";
		$Text = "
	<?php
	$server = $serverdb;
	$user = $userdb;
	$password = $passworddb;
	$db = $dbname;
	?>
	";
		
		$File = fopen($Datei, "w");
		fwrite($File, $Text);
		fclose($File);

		
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
	
	
	
	$install = new install();
	
	if(isset($_POST['submitAndInstall'])){
		
		$install->run();
		
		
		
	
		
		
	}
  
  
  $page = $_GET['page']; 

    switch($page) {
    case 'check':
		
		$checkRequirements = $install->checkRequirements($_POST['host'],$_POST['dbUser'],$_POST['dbPassword'],$_POST['dbName']);
		
		
		
		
	    echo"<header>";
		echo"<h2>Checklist</h2>";
		echo"</header>";
			
		echo"<h1>&nbsp;</h1>";
		
		
		
		//if checkRequirements is false, it returns 
		//an array with the erroe message
		if(is_array($checkRequirements)){
			echo"<p>To make sure the UniverseOS will run on your System, check the following details:</p>";
			echo"<ul>";
			if(in_array('dbServer',$checkRequirements)){
				echo"<li>The connection to the MySQL server could not be established.</li>";
			}
			if(in_array('db',$checkRequirements)){
				echo"<li>The connection to the database could not be established.</li>";
			}
			if(in_array('space',$checkRequirements)){
				echo"<li>You need a minimum of 10MB free disk space on your server.</li>";
			}
			
			echo"</ul>";
			$continue = 'disabled="disabled" onclick="return false;"';
		}else{
			
			$continue = "";
			echo"<h3>The universeOS is ready to install.<h3>";
		}
	?>
		<p><a href="index.php?page=database" class="btn pull-left">Back</a><a href="index.php?page=success" class="btn btn-info pull-right" <?=$continue;?>>Install the universeOS</a></p>
		<p>&nbsp; </p>
	<?php
        break;
    case 'database':
        include('database.php');
        break;
    case 'success':
        include('success.php');
        
        $install->createDbConfigFile();
        
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
