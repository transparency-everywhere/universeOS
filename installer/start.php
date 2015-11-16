<header>
    <h2><img src="gfx/guest/guest_header.svg" height="40">&nbsp;Installer</h2>
</header>
	
<div class="content">
<p>Welcome to the UniverseOS Installer,</p>
<p>in the following steps you will install the universeOS version <?=$INSTALL_CONFIG['current_version'];?></p>

<?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    echo "";
} else { 
    echo "Your current connection is not secure, please use https to ensure that no one is listening."; //describe tls as "https" lets everyone understand it
}?>


<p>Those requirements are needed:</p>


<ul style="margin:20px 40px; ">
    <li>Minimum <?=$INSTALL_CONFIG['min_disk_space'];?> MB disk space</li>
    <li>MySQL <?=$INSTALL_CONFIG['min_mysql_version'];?> or later</li>
    <li>PHP <?=$INSTALL_CONFIG['min_php_version'];?> or later</li>
</ul>
<p></p>
<div class="controlBar">
	<a href="?page=database" class="btn btn-info pull-right">Continue</a>
</div>
</div>