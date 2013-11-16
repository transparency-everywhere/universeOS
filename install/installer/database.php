<form action="index.php?page=check" method="post">
<header>
<h2>Database</h2>
</header>

<table class="content">
		<tr>
			<td><h4>General Settings</h4></td>
		</tr>
		<tr>
			<td>Title of your universe:</td>
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
	</table>
<p> </p>
<div class="controlBar">
	<a href="index.php" class="btn pull-left">Back</a>
	<input type="submit" class="btn btn-info pull-right" value="Continue">
</div>
<p>&nbsp; </p>
</form>


