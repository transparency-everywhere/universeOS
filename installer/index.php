<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

<title>universeOS Installer</title>

<link rel="stylesheet" href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" type="text/css" media="screen" />
<link rel="stylesheet" href="index.css" type="text/css" media="screen" />

<script>

	someForm.setAttribute( "autocomplete", "off" );
</script>
</head>

<!-- BODY -->
<body>
<!-- contentbox -->
<section id="content">
  
  <?php 
  include('installConfig.php');
  



                $parentparentdir=realpath('../');
                define('universeBasePath', $parentparentdir);

  if(!is_writable(universeBasePath.'/inc/classes')){
  	die('inc/classes needs to be writable');
  }
  if(!is_readable(universeBasePath.'/__db.sql')){
  	die('./__db.sql needs to be writable');
  }

  session_start();
  
	
  function createSalt($salt){
		
		return hash('sha512', sha1($salt));
  }
  function remoteFileExists($url) {
  	//made by "Tom Haigh", taken from http://stackoverflow.com/questions/981954/how-can-one-check-to-see-if-a-remote-file-exists-using-php
	
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);

    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

        if ($statusCode == 200) {
            $ret = true;   
        }
    }

    curl_close($curl);

    return $ret;
  }
  
  class install{

	function checkRequirements($server,$user,$password,$db, $title, $url, $salt){
		//check db
		$dbName = $db;
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
			
			$_SESSION['db'] = $dbName;
			
			$_SESSION['URL'] = createSalt($salt);
			$_SESSION['title'] = createSalt($salt);
			$_SESSION['salt'] = createSalt($salt);
			
		}else{
			$space = false;
			$errorMessage[] = "space";
		}
		
		//check if zip file on universe server is available
		$exists = remoteFileExists('http://development.transparency-everywhere.com/current.zip');
		if (!$exists) {
			$remoteZip = false;
			$errorMessage[] = "remoteZip";
		}
		
		if($dbServer AND $db AND $space AND $remoteZip){
			return true;
		}else{
			return $errorMessage;
		}
		
		
	}
	
	function downloadAndUnpack(){
		ini_set('error_reporting', E_ALL);


		$downloadString = file_get_contents("http://development.transparency-everywhere.com/current.zip");
		$save = file_put_contents('current.zip',$downloadString);
		
			$zip = new ZipArchive();
			$x = $zip->open('current.zip');
			if ($x === true) {
				$zip->extractTo("./"); // change this to the correct site path
				$zip->close();
			    unlink('current.zip');
			}
		$message = "Your .zip file was uploaded and unpacked.";
	}
	
	function createUniverseDB(){
                error_reporting(E_ALL);
                $path = universeBasePath.'/__db.sql';
                include(universeBasePath.'/inc/config.php');
                include(universeBasePath.'/inc/functions.php');
                error_reporting(E_ALL);
				$db = new db();
                $db->importFile($path);
	}
	
	function proofRegistration(){
		
	}
	
	function run($options){
            //, 'uni_basepath'=>$_POST['db_name'], 'uni_sysfolder_folder_id'=>$uni_sysfolder_folder_id, 'appCenter_folder_id'=>$appCenter_folder_id, 
		//if($this->checkRequirements($options['db_server'], $options['db_user'], $options['db_password'], $options['db_name'], $options['universe_title'],  $options['universe_url'],  $options['universe_salt'])){
		if(true){
                        include_once('../inc/classes/class_universe.php');
                        //create first config to create folders
                        $universe = new universe();
                        $universe->createConfig($options);
                        $this->createUniverseDB();
                        
                        //
			//$this->createServerConfigFile();
			
		}
		
	}
}
	
	
	
	$install = new install();
	
	if(isset($_POST['submitAndInstall'])){
		$options = array('db_server'=>$_POST['host'],'db_user'=>$_POST['dbUser'], 'db_password'=>$_POST['dbPassword'], 'db_name'=>$_POST['dbName'], 'universe_title'=>$_POST['universeTitle'], 'uni_basepath'=>$_POST['basePath'], 'uni_url'=>$_POST['URL']);
		$install->run($options);
	}
  
    if(empty($_GET['page']))
        $_GET['page'] = 'start';
    $page = $_GET['page']; 

    switch($page) {
    case 'check':
		
		$checkRequirements = $install->checkRequirements($_POST['host'],$_POST['dbUser'],$_POST['dbPassword'],$_POST['dbName'], $_POST['universeTitle'], $_POST['URL'], $_POST['salt']);
		
		
		
		
	    echo"<header>";
		echo"<h2>Checklist</h2>";
		echo'</header>';
		echo'<div class="content">';
		
		
		
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
			
			if(in_array('remoteZip',$checkRequirements)){
				echo"<li>This script is not able to download the current version of the universe from the remote server, please ensure, that your installer is up to date, that your server can communicate with the internet and that our server is not down.</li>";
			}
			
			echo"</ul>";
			$continue = 'disabled="disabled" onclick="return false;"';
		}else{
			
			$continue = "";
			echo"<h3>The universeOS is ready to install.<h3>";
		}
	?>
	<div class="controlBar">
		<a href="index.php?page=database" class="btn pull-left">Back</a>
		<a href="index.php?page=success" class="btn btn-info pull-right" <?=$continue;?>>Install the universeOS</a>
		
	</div>
	</div>
	<?php
        break;
    case 'database':
        include('database.php');
        break;
    case 'keys':
        include('keys.php');
        break;
    case 'success':
    
		$options = array('db_server'=>$_POST['host'],'db_user'=>$_POST['dbUser'], 'db_password'=>$_POST['dbPassword'], 'db_name'=>$_POST['dbName'], 'universe_title'=>$_POST['universeTitle'], 'uni_basepath'=>$_POST['basePath'], 'uni_basepath'=>$_POST['basePath']);
		
		$install->run($options);
        include('success.php');
        
        
      	break;
    default:
        include('start.php');
        
    } 
  ?>
  
<footer>
<p style="font-size: 10px">Copyright whatever</p>
</footer>

</section>
<dock></dock>
</body>
</html>
