<?php
$url = $_GET['url'];
?>
<style>
.browser{
	position:absolute;
	top: 0px;
	right: 0px;
	bottom: 0px;
	left: 0px;
}

.browser header{
	height: 25px;
	background: #c9c9c9;
	position: absolute;
	top: 0px;
	right: 0px;
	left: 0px;
	padding: 5px;
	overflow: auto;
}

.browser header form{
	margin:0px;
}


.browser header input{
	height: 15px;
	padding: 5px 5px 2px 5px;
	position: absolute;
	top: 5px;
	right: 5px;
	left: 5px;
	min-width: 400px;
}

.browser div{
	
	position:absolute;
	top: 35px;
	right: 0px;
	bottom: 0px;
	left: 0px;
}

.browser iframe{
	width:100%;
	height:100%;
}

.browser footer{
	display: none;	
}

</style>
<div class="browser">
<header>
	<form>
		
	</form>
		
		<input type="text" class="browserInput" placeholder="http://transparency-everywhere.com" value="<?=$url;?>">
</header>
<iframe src="<?=$url;?>">
	
</iframe>
<footer>
	
</footer>
</div>
