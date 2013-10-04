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
	
	
	

	
	
}else{ ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>universeOS install Script</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
  </head>

  <body>
  
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

  </body>
</html>
<? } ?>
