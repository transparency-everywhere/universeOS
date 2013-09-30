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


.browser header span{
	float:left;
}

.browser header input{
	float: left;
	height: 15px;
	padding: 5px 5px 2px 5px;
	margin-left: 5px;
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
	<form onsubmit="alert('stuff'); return false;">	
		<span><a href="#" class="browserBack btn btn-small"><<</a> <a href="#" class="browserNext btn btn-small">>></a> <a href="#" class="browserToggleProxy btn btn-small" class="You are currently not using your proxy"><i class="icon icon-eye-open"></i></a> </span><input type="text" class="browserInput" placeholder="http://transparency-everywhere.com" value="<?=$url;?>">

		
	</form>
	</header>
<iframe src="<?=$url;?>">
	
</iframe>
<footer>
	
</footer>
</div>
