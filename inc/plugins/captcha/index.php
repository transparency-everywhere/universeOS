<?php
	/**
	 * PHP MATH CAPTCHA
	 * Copyright (C) 2010  Constantin Boiangiu  (http://www.php-help.ro)
	 * 
	 * This program is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 * 
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 * 
	 * You should have received a copy of the GNU General Public License
	 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 **/
	
	/**
	 * @author Constantin Boiangiu
	 * @link http://www.php-help.ro
	 * 
	 * This script is provided as-is, with no guarantees.
	 */
	
	/* the captcha result is stored in session */
	session_start();
	/* a simple form check */
	if( isset( $_POST['secure'] ) )
	{
		if($_POST['secure'] != $_SESSION['security_number'])
		{
			$error = "OOOK! Here's what you must do: click Start -> Run and write calc.";
		}
		else
		{
			$error = "Man, you're good! Your result is correct.";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP math captcha</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<script language="javascript" type="text/javascript">
	/* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
	function reloadCaptcha()
	{
		document.getElementById('captcha').src = document.getElementById('captcha').src+ '?' +new Date();
	}
</script>
</head>

<body>
<div id="container">
	
    <h1>PHP Math captcha image</h1>
    <p>
    	Math captcha image form validation. All the captcha script resides in index.php. To implement this, all you need to do is add the captcha image to your forms ( &lt;img src="math_captcha_path/image.php" /&gt; ) and do the form validation.<br />
		Captha result is stored in session, so remember to start your session with the same name, if any used.<br />
		For this to work, you need PHP installed with GD support and ttftext. Remember to add the font used in your captcha.
    </p>
    <strong>Demo</strong>
    <form method="post" action="">
        <input type="text" name="secure" value="what's the result?" onclick="this.value=''" />
        <input type="submit" value="am I right?" /><br />
        <span class="explain">click on the image to reload it</span>
        <img src="image.php" alt="Click to reload image" title="Click to reload image" id="captcha" onclick="javascript:reloadCaptcha()" />
    </form>
	<?=$error?>
</div>    
</body>
</html>
