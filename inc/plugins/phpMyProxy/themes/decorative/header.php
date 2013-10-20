<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Decorative
Description: Fixed-width, two-column design from small sites and blogs.
Version    : 1.0
Released   : 20071101

-->
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $_lang['dir']; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_lang['charset']; ?>">

<meta name="keywords" content="free proxy,free proxy server,surf anonymously,privacy protection,free proxy script,proxy script,free script" />
<meta name="description" content="phpMyProxy is a free, light and powerful php proxy script programed by eProxies.info." />
<title>phpMyProxy - free and Open Source PHP proxy script</title>
<style type="text/css">
/*
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License
*/

* {
	margin: 0;
	padding: 0;
}

body {
	background: url(<?php echo _THEME_DIR; ?>/images/img01.png) repeat-y center;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	color: #9E9D89;
}

form {
}

input, textarea {
	border: 1px solid #DEDED1;
	font: normal small "Trebuchet MS", Arial, Helvetica, sans-serif;
}

input.text {
	padding: .15em;
	background: #FDFDFB url(<?php echo _THEME_DIR; ?>/images/img06.gif) repeat-x;
}

input.button {
	background: #763320 url(<?php echo _THEME_DIR; ?>/images/img07.gif) repeat-x;
	color: #FFFFFF;
}

h1, h2, h3 {
	font-family: Georgia, "Times New Roman", Times, serif;
	color: #D15803;
}

h1 {
	letter-spacing: -.05em;
	font-size: 2.2em;
}

h2 {
	margin-top: 1em;
	letter-spacing: -.05em;
	font-size: 1.4em;
}

h3 {
	margin-top: 1em;
}

p, ul, ol {
	margin-top: 1em;
	line-height: 160%;
}

ul {
	list-style: none;
}

ul li {
	margin-left: 1em;
	padding-left: .75em;
	background: url(<?php echo _THEME_DIR; ?>/images/img05.gif) no-repeat left center;
}

ol {
	margin-left: 1em;
	list-style-position: inside;
}

blockquote {
	margin-left: 1em;
	padding-left: .75em;
	border-left: 1px solid #9E9D89;
}

a {
	text-decoration: none;
	color: #D15803;
}

a:hover {
	text-decoration: underline;
	color: #827F4F;
}

/* Header */

#header {
	width: 860px;
	height: 100px;
	margin: 0 auto;
	padding: 0 40px;
	background: url(<?php echo _THEME_DIR; ?>/images/img02.png);
}

#logo {
	float: left;
}

#logo h1, #logo p {
	margin: 0;
	padding-top: 25px;
	float: left;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-style: italic;
	color: #F3F3E9;
}

#logo h1 {
	font-size: 3em;
}

#logo p {
	padding: 48px 0 0 5px;
	letter-spacing: -.055em;
	font-size: 1.4em;
	font-weight: bold;
}

#logo a {
	color: #F3F3E9;
}

#search {
	float: right;
	width: 15em;
	padding-top: 50px;
}

#search fieldset {
	border: none;
}

#search #s {
	width: 13em;
}

/* Page */

#page {
	width: 940px;
	margin: 0 auto;
}

/* Content */

#content {
	float: right;
	width: 580px;
	padding: 35px 50px 0 0;
	background: url(<?php echo _THEME_DIR; ?>/images/img04.png) no-repeat right top;
}

.post {
}

.post .meta {
	margin: 0 0 0.1em 0;
	padding: 0 0 1px 10px;
	background: url(<?php echo _THEME_DIR; ?>/images/img08.png) no-repeat left bottom;
	line-height: normal;
}

.post .meta small {
	font-size: 12px;
}

/* Sidebar */

#sidebar {
	float: left;
	width: 240px;
	padding: 20px 0 0 30px;
	background: url(<?php echo _THEME_DIR; ?>/images/img03.png) no-repeat;
	font-size: smaller;
	color: #E3E3D4;
}

#sidebar ul {
	padding: 0;
	list-style: none;
}

#sidebar li {
	margin-bottom: 3em;
	padding: 0;
	background: none;
}

#sidebar li ul {
}

#sidebar li li {
	margin: 0 0 0 1em;
	padding-left: .75em;
	background: url(<?php echo _THEME_DIR; ?>/images/img09.gif) no-repeat left center;
}

#sidebar h2 {
	color: #FFFFFF;
}

#sidebar a {
	color: #FFFFFF;
}

/* Footer */

#footer {
	width: 860px;
	margin: 0 auto;
	padding: 20px 40px;
	text-align: right;
}

#footer p {
	font-size: 10px;
}
</style>

</head>
<body>
<!-- start header -->
<div id="header">
	<div id="logo">
		<h1><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">phpMyProxy</a></h1>
		<p><?php echo $_lang['slogan']; ?></p>
	</div>
	<div id="search">
		<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
			<fieldset>
			<input id="<?php echo $_config['url_var_name']; ?>" type="text" name="<?php echo $_config['url_var_name']; ?>" value="" class="text" />
			<input id="x" type="submit" value="<?php echo $_lang['submit']; ?>" class="button" />
			</fieldset>
		</form>
	</div>
</div>
<!-- end header -->
<!-- start page -->
<div id="page">
	<!-- start content -->
	<div id="content">
		<div class="post">
			<div class="meta"><small><?php echo $_lang['your_ip']; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_SERVER['REMOTE_ADDR']; ?></small></div>
			<div class="meta"><small><?php echo $_lang['server_ip']; ?>:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_SERVER['SERVER_ADDR']; ?></small></div>
