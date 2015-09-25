<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />

<title>universeOS Installer</title>

<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="installer/index.css" type="text/css" media="screen" />
<script type="text/javascript" src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"/></script>
<script type="text/javascript" src="inc/js/plugins.js"/></script>


<script>

	someForm.setAttribute( "autocomplete", "off" );
</script>
</head>

<!-- BODY -->
<body>
<!-- contentbox -->
<section id="content">
  
  <?php 
  session_start();
  
  $INSTALL_CONFIG['current_version'] = '0.4.1';
  
  $INSTALL_CONFIG['connection_type'] = 'https';
  
  $INSTALL_CONFIG['min_php_version'] = '5.4';
  $INSTALL_CONFIG['min_disk_space'] = 10;
  $INSTALL_CONFIG['min_mysql_version'] = 5.0;
	
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

	function checkRequirements($server,$user,$password,$db, $title, $url, $salt, $publicKey, $privateKey){
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
		
                
                //check if php version is ok
                if(version_compare(phpversion(), $INSTALL_CONFIG['min_php_version'], '<')){
			$errorMessage[] = "phpVersion";
                }
                
		
		//check if free space is bigger than or euqal to 10MB
		if(round(disk_free_space(".")/(1024*1024)) >= $INSTALL_CONFIG['min_disk_space']){
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
		$exists = remoteFileExists($INSTALL_CONFIG['connection_type'].'://development.transparency-everywhere.com/current.zip');
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
	
	function createDbConfigFile(){
		
		$serverdb = $_SESSION['server'];
		$userdb = $_SESSION['user'];
		$passworddb = $_SESSION['password'];
		$dbname = $_SESSION['db'];
		
		$Datei = "inc/config/test.php";
$Text = '<?php
$server = \''.$serverdb.'\';
$user = \''.$userdb.'\';
$password = \''.$passworddb.'\';
$db = \''.$dbname.'\';
?>';
		
		$File = fopen($Datei, "w");
		fwrite($File, $Text);
		fclose($File);

		
	}
        
        
        function createFiles(){
            //dbConfig.php
            
            //keyConfig.php
            
        }
	
	function createServerConfigFile(){
		
		$URL = $_SESSION['URL'];
		$salt = $_SESSION['salt'];
		if(!empty($_SESSION['indexSalt']))
			$indexSalt = $_SESSION['indexSalt'];
		
		$Datei = "inc/config/serverConfig.php";
$Text = '<?php
$universe_URL = \''.$URL.'\';
$universe_title = \''.$title.'\';
$universe_salt = \''.$salt.'\';
$universe_indexSalt = \''.$indexSalt.'\';
?>';
		
		$File = fopen($Datei, "w");
		fwrite($File, $Text);
		fclose($File);

		
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
		
	}
	
	function proofRegistration(){
		
	}
	
	
	function run(){
		
		if($this->checkRequirements()){
			$this->downloadAndUnpack();
			$this->createDbConfigFile();
			$this->createServerConfigFile();
			
		}
		
	}
}
	
	
	
	$install = new install();
	
	if(isset($_POST['submitAndInstall'])){
		
		$install->run();
		
		
		
	
		
		
	}
    $page = '0';
    if(isset($_GET['page']))
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
			
			if(in_array('phpVersion',$checkRequirements)){
				echo'<li>The universeOS requires PHP '.$INSTALL_CONFIG['min_disk_space'].' or later. Your version is '.phpversion().' </li>';
			}
			
			echo"</ul>";
			$continue = 'disabled="disabled" onclick="return false;"';
		}else{
			
			$continue = "";
			echo"<h3>The universeOS is ready to install.<h3>";
		}
	?>
	<div class="controlBar">
		<a href="?page=database" class="btn pull-left">Back</a>
		<a href="?page=success" class="btn btn-info pull-right" <?=$continue;?>>Install the universeOS</a>
		
	</div>
	</div>
	<?php
        break;
    case 'database':
        ?>
        <!--Database Form Field -->
        <form action="?page=keys" method="post">
            <header>
            <h2>General</h2>
            </header>
            <table class="content">
                            <tr>
                                    <td>&nbsp;</td>
                                    <td><h4>General Settings</h4></td>
                            </tr>
                            <tr>
                                    <td>Title:</td>
                                    <td><input type="text" name="universeTitle" placeholder="The title of your universe"></td>
                            </tr>
                            <tr>
                                    <td>Installation URL:</td>
                                    <td><input type="text" name="URL" placeholder="The URL under which your universe will be found"></td>
                            </tr>
                            <tr>
                                    <td>Enlist to Index:</td>
                                    <td><input type="checkBox" name="enlistToIndex" checked></td>
                            </tr>
                            <tr>
                                    <td>Encryption Salt:</td>
                                    <td><input type="text" name="salt" placeholder="Just type a very long random string"></td>
                            </tr>
                            <tr>
                                    <td>Admin Password:</td>
                                    <td><input type="text" name="adminPassword" placeholder="Choose a strong password!"></td>
                            </tr>
                            <tr>
                                    <td>&nbsp;</td>
                            </tr>
                            <tr>
                                    <td>&nbsp;</td>
                                    <td><h4>Database Settings</h4></td>
                            </tr>
                            <tr>
                                    <td>Host</td>
                                    <td><input type="text" name="host" placeholder="localhost"></td>
                            </tr>
                            <tr>
                                    <td>Database Name</td>
                                    <td><input type="text" name="dbName"></td>
                            </tr>
                            <tr>
                                    <td>User</td>
                                    <td><input type="text" name="dbUser"></td>
                            </tr>
                            <tr>
                                    <td>Password</td>
                                    <td><input type="text" name="dbPassword"></td>
                            </tr>
                            <tr>
                                    <td>&nbsp;</td>
                                    <td><h4>Analytic Script</h4></td>
                            </tr>
                            <tr>
                                    <td colspan="2"><textarea name="analytic script"></textarea></td>
                            </tr>
                            <tr>
                                    <td>Database Name</td>
                                    <td><input type="text" name="dbName"></td>
                            </tr>
                            <tr>
                                    <td>User</td>
                                    <td><input type="text" name="dbUser"></td>
                            </tr>
                            <tr>
                                    <td>Password</td>
                                    <td><input type="text" name="dbPassword"></td>
                            </tr>
                    </table>
            <p> </p>
            <div class="controlBar">
                    <a href="index.php" class="btn pull-left">Back</a>
                    <input type="submit" class="btn btn-info pull-right" value="Continue">
            </div>
            <p>&nbsp; </p>
        </form> 
        
    <?php  
        break;
    case 'keys':
        ?>
        <!--Database Form Field -->
        <form action="?page=check" method="post">
            <header>
            <h2>Database</h2>
            </header>
            <table class="content">
                
                            <!--pass values from previous step-->
                            <input type="hidden" name="universeTitle" value="<?=$_POST['universeTitle'];?>">
                            <input type="hidden" name="URL" value="<?=$_POST['URL'];?>">
                            <input type="hidden" name="enlistToIndex" value="<?=$_POST['enlistToIndex'];?>">
                            <input type="hidden" name="salt" value="<?=$_POST['salt'];?>">
                            <input type="hidden" name="adminPassword" value="<?=$_POST['adminPassword'];?>">
                            <input type="hidden" name="host" value="<?=$_POST['host'];?>">
                            <input type="hidden" name="dbName" value="<?=$_POST['dbName'];?>">
                            <input type="hidden" name="dbUser" value="<?=$_POST['dbUser'];?>">
                            <input type="hidden" name="dbPassword" value="<?=$_POST['dbPassword'];?>">
                
                            <tr>
                                    <td><h4>Keys</h4></td>
                            </tr>
                            <tr>
                                    <td style="min-width:300px;">Choose type of key generation:</td>
                                    <td>
                                        <select id="uploadOrGenerate">
                                            <option value="">Please Choose</option>
                                            <option value="generate">Automatically generate Keys in your Browser</option>
                                            <option value="upload">Manually generate Keys on your computer</option>
                                        </select>
                                    </td>
                            </tr>
                            <tr style="display:none" id="loader" align="center">
                                <td colspan="2" align="center"><center><img src="gfx/ripple.gif" width="100"><br>...generating keys. This could take some minutes.</center></td>
                            </tr>
                            <tr class="keyRow">
                                    <td>Public Key</td>
                                    <td class="generate"><textarea name="publicKey" id="publicKey"></textarea></td>
                                    <td class="upload"><input type="file" name="publicKey"></textarea></td>
                                    
                            </tr>
                            <tr class="keyRow">
                                    <td>Private Key</td>
                                    <td class="generate"><textarea name="privateKey" id="privateKey"></textarea></td>
                                    <td class="upload"><input type="file" name="privateKey"></textarea></td>
                            </tr>
                            <tr>
                                    <td>&nbsp;</td>
                            </tr>
                    </table>
            <p> </p>
            <div class="controlBar">
                    <a href="history.back();" class="btn pull-left">Back</a>
                    <input type="submit" class="btn btn-info pull-right" value="Continue">
            </div>
            <p>&nbsp; </p>
        </form> 
        
    <?php  
        break;
    case 'success':
    
        $install->run();
        include('installer/success.php');
        
        
      	break;
    default:
        include('installer/start.php');
        
    } 
  ?>

</section>
<script>
    
                    $('#uploadOrGenerate').change(function(){
                        console.log($('#uploadOrGenerate').val());
                        switch($(this).val()){
                            case 'generate':
                                $('.upload').hide();
                                $('.generate').show();
                                $('textarea, input').attr("disabled","disabled");
                                
                                $('#loader').show();
                                
                                generateKeys(function(publicKey, privateKey){
                                    
                                    $('#loader').hide();
                                    $('#publicKey').val(publicKey);
                                    $('#privateKey').val(privateKey);
                                    
                                    $('textarea, input').removeAttr("disabled","false");
                                });
                                
                                
                                break;
                            case 'upload':
                                $('.generate').hide();
                                $('.upload').show();
                                break;
                        }
                        $('.keyRow').show();
                    });
                    
                    
                    function generateKeys(callback){
                        var crypt,publicKey,privateKey;

                        crypt = new JSEncrypt({default_key_size: 4096});

                        crypt.getKey(function () {
                                privateKey = crypt.getPrivateKey(); //encrypt privatestring, using the password hash
                                publicKey = crypt.getPublicKey();
                                callback(publicKey, privateKey);
                        });
                    }
</script>
</body>
</html>
