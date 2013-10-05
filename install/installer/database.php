<?
class install{

	function checkRequirements(){
		return true;
		//check if db acces, db name exits
	}
	
	function createDbConfigFile(){
	}
	
	function createUniverseDB(){
	}
	
	function proofRegistration(){
	}
	
	
	function run(){
		
		if($this->$checkRequirements()){
			
			
		}
		
	}
}

if(isset($_POST['submitAndInstall'])){
	
	$install = new install();
	$install->run();
	
 } ?>

<!doctype html>
<html lang="en">

<body>
<header>
<h2 style="text-align: center; font-size: 24px; color: #000000;"><u>Database</u></h2>
</header>
	
<table>
		<tr>
			<td>Host</td>
			<td><input type="host"></td>
		</tr>
		<tr>
			<td>Database Name</td>
			<td><input type="dbName"></td>
		</tr>
		<tr>
			<td>User</td>
			<td><input type="dbUser"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="dbPassword"></td>
		</tr>
	</table>
<p> </p>
<p><a href="index.php?page=success" class="Button">Continue</a></p>
<p>&nbsp; </p>

</body>
</html>


